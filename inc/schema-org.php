<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Données communes utilisées par tous les schemas (adresse postale, coordonnées, contact).
 * Source : ACF Options Theme (Infos Générales).
 */
function mkwvs_schema_get_common_data(): array
{
    $address = function_exists('get_field') ? get_field('option_contact_address', 'option') : '';
    $city = function_exists('get_field') ? get_field('option_contact_city', 'option') : '';
    $zipcode = function_exists('get_field') ? get_field('option_contact_zipcode', 'option') : '';
    $country = function_exists('get_field') ? get_field('option_contact_country', 'option') : '';
    $phone = function_exists('get_field') ? get_field('option_contact_phone', 'option') : '';
    $email = function_exists('get_field') ? get_field('option_contact_email', 'option') : '';
    $open_hours = function_exists('get_field') ? get_field('option_open_hours', 'option') : '';

    $socials = [];
    if (function_exists('have_rows') && have_rows('option_social_network_list', 'option')) {
        while (have_rows('option_social_network_list', 'option')) {
            the_row();
            $url = get_sub_field('item_social_network_url');
            if (!empty($url)) {
                $socials[] = $url;
            }
        }
    }

    return [
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'address' => trim((string) $address),
        'city' => trim((string) $city),
        'zipcode' => trim((string) $zipcode),
        'country' => trim((string) $country),
        'phone' => trim((string) $phone),
        'email' => trim((string) $email),
        'hours' => trim((string) $open_hours),
        'socials' => $socials,
        'logo' => get_stylesheet_directory_uri() . '/img/favicon.png',
    ];
}

/**
 * Parse le champ texte "Jeudi : 11h - 23h\nVendredi : 11h - 0h..." en openingHoursSpecification.
 */
function mkwvs_schema_parse_opening_hours(string $raw): array
{
    $map = [
        'lundi' => 'Monday',
        'mardi' => 'Tuesday',
        'mercredi' => 'Wednesday',
        'jeudi' => 'Thursday',
        'vendredi' => 'Friday',
        'samedi' => 'Saturday',
        'dimanche' => 'Sunday',
    ];
    $spec = [];
    $lines = preg_split('/[\n\r]+/', $raw);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        if (!preg_match('/^([a-zà-ü]+)\s*:\s*(\d{1,2})h?\s*[-–]\s*(\d{1,2})h?/iu', $line, $m)) {
            continue;
        }
        $day_fr = mb_strtolower($m[1]);
        if (!isset($map[$day_fr])) {
            continue;
        }
        $open = str_pad($m[2], 2, '0', STR_PAD_LEFT) . ':00';
        $close_hour = (int) $m[3];
        if ($close_hour === 0) {
            $close_hour = 24;
        }
        $close = str_pad((string) $close_hour, 2, '0', STR_PAD_LEFT) . ':00';
        $spec[] = [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => $map[$day_fr],
            'opens' => $open,
            'closes' => $close,
        ];
    }
    return $spec;
}

/**
 * Construit le bloc address + contact commun.
 */
function mkwvs_schema_build_base(array $data, string|array $type = 'Organization'): array
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $type,
        'name' => $data['name'],
        'url' => $data['url'],
        'logo' => $data['logo'],
        'image' => $data['logo'],
    ];

    if ($data['address'] || $data['city']) {
        $schema['address'] = array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $data['address'],
            'addressLocality' => $data['city'],
            'postalCode' => $data['zipcode'],
            'addressCountry' => $data['country'] ?: 'FR',
        ]);
    }
    if ($data['phone']) {
        $schema['telephone'] = $data['phone'];
    }
    if ($data['email']) {
        $schema['email'] = $data['email'];
    }
    if (!empty($data['socials'])) {
        $schema['sameAs'] = $data['socials'];
    }
    return $schema;
}

/**
 * Schema.org global : injecté dans <head> sur toutes les pages.
 * - Organization sur toutes les pages
 * - LocalBusiness + openingHours sur la home (signal fort d'entité locale)
 */
function mkwvs_schema_inject_global(): void
{
    if (is_admin() || is_feed()) {
        return;
    }

    $data = mkwvs_schema_get_common_data();

    if (is_front_page() || is_home()) {
        $schema = mkwvs_schema_build_base($data, 'LocalBusiness');
        $schema['description'] = get_bloginfo('description')
            ?: "Péniche associative à Lille : restaurant, bar, programmation culturelle, coworking et privatisation.";
        $schema['priceRange'] = '€€';
        if ($data['hours']) {
            $hours = mkwvs_schema_parse_opening_hours($data['hours']);
            if (!empty($hours)) {
                $schema['openingHoursSpecification'] = $hours;
            }
        }
    } else {
        $schema = mkwvs_schema_build_base($data, 'Organization');
    }

    echo "\n" . '<script type="application/ld+json">'
        . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        . '</script>' . "\n";
}
add_action('wp_head', 'mkwvs_schema_inject_global', 99);

/**
 * Schema.org spécifique : injecté sur les pages /restauration/ et /coworking/.
 */
function mkwvs_schema_inject_page_specific(): void
{
    if (is_admin() || is_feed()) {
        return;
    }

    $template = get_page_template_slug();
    $data = mkwvs_schema_get_common_data();
    $hours = $data['hours'] ? mkwvs_schema_parse_opening_hours($data['hours']) : [];

    $schema = null;

    if ($template === 'templates/restauration.php') {
        $schema = mkwvs_schema_build_base($data, 'Restaurant');
        $schema['description'] = "Restaurant et bar sur péniche à Lille. Saveurs locales, cuisine saine et conviviale, options végétariennes, brunch dominical.";
        $schema['servesCuisine'] = ['Française', 'Bistro', 'Végétarienne'];
        $schema['priceRange'] = '€€';
        $schema['acceptsReservations'] = 'True';
        if (!empty($hours)) {
            $schema['openingHoursSpecification'] = $hours;
        }
        $schema['hasMenu'] = get_permalink();
    } elseif ($template === 'templates/coworking.php') {
        $schema = mkwvs_schema_build_base($data, ['LocalBusiness', 'CoworkingSpace']);
        $schema['description'] = "Espace de coworking atypique sur péniche à Lille, au bord de la Deûle. Wifi, café, ambiance chaleureuse et cadre unique pour travailler autrement.";
        $schema['priceRange'] = 'Gratuit';
        $schema['isAccessibleForFree'] = true;
        if (!empty($hours)) {
            $schema['openingHoursSpecification'] = $hours;
        }
        $schema['amenityFeature'] = [
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Wifi', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Bar / Café', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Terrasse', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Accès gratuit', 'value' => true],
        ];
    }

    if ($schema !== null) {
        echo "\n" . '<script type="application/ld+json">'
            . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            . '</script>' . "\n";
    }
}
add_action('wp_head', 'mkwvs_schema_inject_page_specific', 100);
