<?php

declare(strict_types=1);

/**
 * Fiche événement Facebook (single-facebook_events).
 *
 * Le plugin Import Facebook Events injecte dans le contenu une meta brute
 * (« Date: juillet 3 », « Heure: 02:30 pm », carte Google Maps). On la
 * nettoie côté thème sans toucher au plugin :
 *  - date/heure reformatées en français via mkwvs_event_date_label()
 *  - carte Google Maps remplacée par un lien texte (perf + RGPD)
 *
 * Chaque remplacement échoue proprement si le markup du plugin change :
 * on renvoie alors le contenu inchangé.
 */

function mkwvs_fb_single_clean_meta(string $content): string
{
    if (!is_singular('facebook_events') || !is_main_query() || !in_the_loop()) {
        return $content;
    }

    if (strpos($content, 'ife_eventmeta') === false) {
        return $content;
    }

    $post_id = get_the_ID();

    // Date + Heure du plugin -> libellé français (« Jeudi 3 juillet · 14h30 »).
    $label = function_exists('mkwvs_event_date_label') ? mkwvs_event_date_label($post_id) : '';
    if ($label !== '') {
        $content = preg_replace(
            '#<strong>\s*Date\s*:\s*</strong>.*?<strong>\s*Heure\s*:\s*</strong>\s*<p>.*?</p>#is',
            '<p class="event-when">' . esc_html($label) . '</p>',
            $content,
            1
        );
    }

    // Carte Google Maps -> lien texte à partir de l'adresse du lieu.
    if (preg_match('#<div class="venue">.*?<p>(.*?)</p>#is', $content, $m)) {
        $address = trim(wp_strip_all_tags(html_entity_decode($m[1], ENT_QUOTES, 'UTF-8')));
        if ($address !== '') {
            $maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($address);
            $link = '<a class="event-map-link" href="' . esc_url($maps_url) . '" target="_blank" rel="noopener">Voir sur Google Maps</a>';
            $content = preg_replace('#<div class="map">.*?</div>#is', $link, $content, 1);
        }
    }

    return $content;
}
add_filter('the_content', 'mkwvs_fb_single_clean_meta', 20);
