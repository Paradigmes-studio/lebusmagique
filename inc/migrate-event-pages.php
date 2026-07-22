<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Migration one-shot : crée les pages catégories thématiques et événements
 * evergreen si elles manquent (la CI ne déploie que le code, pas le contenu).
 */

add_action('init', 'mkwvs_migrate_event_pages');

function mkwvs_migrate_event_pages(): void
{
    if ((int) get_option('mkwvs_event_pages_migrated', 0) >= 1) {
        return;
    }

    $programmation = get_page_by_path('programmation');
    if (!$programmation instanceof WP_Post) {
        return;
    }

    $evenements_id = mkwvs_migrate_ensure_page('evenements', 'Événements', 0);
    if (!$evenements_id) {
        return;
    }

    $pages = [
        ['blind-test-lille', 'Blind test Lille', $evenements_id, 'templates/evenement-blind-test-lille.php'],
        ['jam-session-lille', 'Jam session Lille', $evenements_id, 'templates/evenement-jam-session-lille.php'],
        ['scene-ouverte-lille', 'Scène ouverte Lille', $evenements_id, 'templates/evenement-scene-ouverte-lille.php'],
        ['ateliers-lille', 'Ateliers Lille', $evenements_id, 'templates/evenement-ateliers-lille.php'],
        ['drag-bingo-lille', 'Drag Show & Drag Bingo à Lille', $evenements_id, 'templates/evenement-format.php'],
        ['cafe-des-langues-lille', 'Café des langues à Lille', $evenements_id, 'templates/evenement-format.php'],
        ['cafe-philo-lille', 'Café philo à Lille', $evenements_id, 'templates/evenement-format.php'],
        ['cafe-italien-lille', 'Café italien à Lille', $evenements_id, 'templates/evenement-format.php'],
        ['prog-conviviale', 'Programmation conviviale', $programmation->ID, 'templates/categorie-evenements.php'],
        ['bien-etre', 'Bien-être', $programmation->ID, 'templates/categorie-evenements.php'],
        ['engagee-inclusive', 'Programmation engagée & inclusive', $programmation->ID, 'templates/categorie-evenements.php'],
        ['culturels-festifs', 'Événements culturels & festifs', $programmation->ID, 'templates/categorie-evenements.php'],
        ['ateliers-artistiques', 'Ateliers de pratique artistique', $programmation->ID, 'templates/categorie-evenements.php'],
        ['jams-scenes-ouvertes', 'Jams & scènes ouvertes', $programmation->ID, 'templates/categorie-evenements.php'],
    ];

    $ok = true;
    foreach ($pages as [$slug, $title, $parent, $template]) {
        if (!mkwvs_migrate_ensure_page($slug, $title, $parent, $template)) {
            $ok = false;
        }
    }

    if ($ok) {
        update_option('mkwvs_event_pages_migrated', 1);
    }
}

function mkwvs_migrate_ensure_page(string $slug, string $title, int $parent, string $template = ''): int
{
    $path = $parent ? get_page_uri($parent) . '/' . $slug : $slug;
    $existing = get_page_by_path($path);
    if ($existing instanceof WP_Post) {
        return $existing->ID;
    }

    $id = wp_insert_post([
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_parent'  => $parent,
        'post_content' => '',
    ]);
    if (is_wp_error($id) || !$id) {
        return 0;
    }
    if ($template !== '') {
        update_post_meta($id, '_wp_page_template', $template);
    }

    return (int) $id;
}
