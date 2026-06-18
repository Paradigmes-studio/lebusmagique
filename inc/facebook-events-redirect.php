<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Redirige (301) les événements Facebook PASSÉS vers leur page evergreen de
 * format (ou, à défaut, leur page catégorie, ou /programmation/). Objectif :
 * consolider le signal SEO sur les pages durables et sortir de l'index les
 * fiches obsolètes, sans perdre leur position (le 301 transfère le jus).
 *
 * Les événements À VENIR (start_ts >= maintenant) restent en ligne et indexés.
 *
 * À déployer EN MÊME TEMPS que les pages evergreen (les cibles doivent exister).
 */

/**
 * Table de correspondance format evergreen -> mots-clés de titre.
 * Ordre = priorité de matching, du plus spécifique au plus large.
 * Le slug est celui de la page enfant de /evenements/.
 */
function mkwvs_fb_evergreen_format_map(): array
{
    // [slug page enfant de /evenements/, mots-clés de titre, libellé du lien]
    return [
        ['blind-test-lille',      ['blind test', 'blind-test', 'blindtest'], 'Tout savoir sur le blind test du Bus Magique'],
        ['drag-bingo-lille',      ['drag', 'bingo'], 'Découvrir nos drag shows & drag bingo'],
        ['cafe-philo-lille',      ['café philo', 'cafe philo', 'philo'], 'Découvrir le café philo du Bus Magique'],
        ['cafe-des-langues-lille', ['café des langues', 'cafe des langues', 'langues'], 'Découvrir le café des langues'],
        ['cafe-italien-lille',    ['café italien', 'cafe italien', 'italien'], 'Découvrir le café italien'],
        ['jam-session-lille',     ['jam'], 'Découvrir les jam sessions du Bus Magique'],
        ['ateliers-lille',        ['atelier', 'écriture', 'ecriture', 'linograv', 'punch needle', 'broderie', 'modelage', 'illustration'], 'Découvrir tous nos ateliers à Lille'],
        ['scene-ouverte-lille',   ['scène ouverte', 'scene ouverte', 'poésive', 'poesive', 'slam'], 'Découvrir les scènes ouvertes du Bus Magique'],
    ];
}

/**
 * Lien contextuel d'une fiche événement vers sa page evergreen de format
 * (maillage interne). Retourne null si aucun format ne correspond.
 *
 * @return array{url:string,label:string}|null
 */
function mkwvs_fb_event_format_link(int $post_id): ?array
{
    $title = mb_strtolower(get_the_title($post_id));
    foreach (mkwvs_fb_evergreen_format_map() as $entry) {
        [$slug, $keywords] = $entry;
        foreach ($keywords as $needle) {
            if (strpos($title, $needle) !== false) {
                $page = get_page_by_path('evenements/' . $slug);
                if ($page instanceof WP_Post) {
                    return ['url' => (string) get_permalink($page), 'label' => (string) ($entry[2] ?? '')];
                }
            }
        }
    }
    return null;
}

/**
 * Détermine l'URL de destination d'un événement passé.
 */
function mkwvs_fb_past_event_target(int $post_id): string
{
    $title = mb_strtolower(get_the_title($post_id));

    // 1) Match sur un format evergreen (page enfant de /evenements/).
    foreach (mkwvs_fb_evergreen_format_map() as [$slug, $keywords]) {
        foreach ($keywords as $needle) {
            if (strpos($title, $needle) !== false) {
                $page = get_page_by_path('evenements/' . $slug);
                if ($page instanceof WP_Post) {
                    return (string) get_permalink($page);
                }
            }
        }
    }

    // 2) Sinon, page catégorie via la taxonomie facebook_category.
    if (function_exists('mkwvs_event_categories')) {
        $terms = get_the_terms($post_id, 'facebook_category');
        if ($terms && !is_wp_error($terms)) {
            $cats = mkwvs_event_categories();
            foreach ($terms as $term) {
                if (isset($cats[$term->slug]['page'])) {
                    $page = get_page_by_path('programmation/' . $cats[$term->slug]['page']);
                    if ($page instanceof WP_Post) {
                        return (string) get_permalink($page);
                    }
                }
            }
        }
    }

    // 3) Repli.
    return home_url('/programmation/');
}

function mkwvs_fb_redirect_past_events(): void
{
    if (is_admin() || !is_singular('facebook_events')) {
        return;
    }

    $post_id = get_queried_object_id();
    $start = (int) get_post_meta($post_id, 'start_ts', true);

    // À venir (ou sans date connue) : on laisse la fiche en ligne et indexée.
    if (!$start || $start >= time()) {
        return;
    }

    $target = mkwvs_fb_past_event_target($post_id);
    if ($target && trailingslashit($target) !== trailingslashit((string) get_permalink($post_id))) {
        wp_safe_redirect($target, 301);
        exit;
    }
}
add_action('template_redirect', 'mkwvs_fb_redirect_past_events', 1);
