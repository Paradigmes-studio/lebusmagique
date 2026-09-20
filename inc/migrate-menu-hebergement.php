<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Migration one-shot : ajoute « Dormir à bord » au menu principal, juste après
 * le logo (la CI ne déploie que le code, pas le contenu).
 */

const MKWVS_MENU_HEBERGEMENT_LABEL = 'Dormir à bord';
const MKWVS_MENU_HEBERGEMENT_POSITION = 5;

add_action('init', 'mkwvs_migrate_menu_hebergement', 20);

function mkwvs_migrate_menu_hebergement(): void
{
    if ((int) get_option('mkwvs_menu_hebergement_migrated', 0) >= 1) {
        return;
    }

    $menu = wp_get_nav_menu_object('navigation');

    if (!$menu instanceof WP_Term) {
        return;
    }

    $page = get_page_by_path(MKWVS_HEBERGEMENT_PAGE_SLUG);

    if (!$page instanceof WP_Post) {
        return;
    }

    $items = wp_get_nav_menu_items($menu->term_id);

    if (!is_array($items)) {
        return;
    }

    foreach ($items as $item) {
        if ('post_type' === $item->type && (int) $item->object_id === $page->ID) {
            update_option('mkwvs_menu_hebergement_migrated', 1);

            return;
        }
    }

    $position = mkwvs_menu_hebergement_position($items);

    foreach ($items as $item) {
        if ((int) $item->menu_order >= $position) {
            wp_update_post([
                'ID' => $item->ID,
                'menu_order' => (int) $item->menu_order + 1,
            ]);
        }
    }

    $item_id = wp_update_nav_menu_item($menu->term_id, 0, [
        'menu-item-title' => MKWVS_MENU_HEBERGEMENT_LABEL,
        'menu-item-object' => 'page',
        'menu-item-object-id' => $page->ID,
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish',
        'menu-item-position' => $position,
    ]);

    if (is_wp_error($item_id)) {
        return;
    }

    update_option('mkwvs_menu_hebergement_migrated', 1);
}

function mkwvs_menu_hebergement_position(array $items): int
{
    $top_level = array_values(array_filter($items, static function ($item): bool {
        return 0 === (int) $item->menu_item_parent;
    }));

    if (count($top_level) < MKWVS_MENU_HEBERGEMENT_POSITION) {
        return count($items) + 1;
    }

    return (int) $top_level[MKWVS_MENU_HEBERGEMENT_POSITION - 1]->menu_order;
}
