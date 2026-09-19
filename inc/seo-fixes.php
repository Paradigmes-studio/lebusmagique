<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Correctifs SEO techniques relevés au crawl de production (septembre 2026).
 */

add_action('template_redirect', 'mkwvs_redirect_evenements_hub');

function mkwvs_redirect_evenements_hub(): void
{
    if (!is_page('evenements')) {
        return;
    }

    $programmation = get_page_by_path('programmation');
    if (!$programmation instanceof WP_Post) {
        return;
    }

    wp_safe_redirect(get_permalink($programmation), 301);
    exit;
}

add_filter('seopress_titles_canonical', 'mkwvs_paged_canonical');

function mkwvs_paged_canonical($canonical_tag)
{
    $paged = (int) get_query_var('paged');
    if ($paged < 2 || !is_string($canonical_tag) || $canonical_tag === '') {
        return $canonical_tag;
    }

    return preg_replace_callback(
        '#href="([^"]+)"#',
        static function (array $matches) use ($paged): string {
            return sprintf('href="%spage/%d/"', trailingslashit($matches[1]), $paged);
        },
        $canonical_tag,
        1
    );
}

add_filter('seopress_titles_title', 'mkwvs_paged_title');

function mkwvs_paged_title($title)
{
    $paged = (int) get_query_var('paged');
    if ($paged < 2 || !is_string($title) || $title === '') {
        return $title;
    }

    $separator = ' - ';
    $position  = strrpos($title, $separator);
    if ($position === false) {
        return sprintf('%s - Page %d', $title, $paged);
    }

    return sprintf(
        '%s - Page %d%s',
        substr($title, 0, $position),
        $paged,
        substr($title, $position)
    );
}
