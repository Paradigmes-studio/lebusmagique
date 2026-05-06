<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

const MKWVS_FB_CATEGORIES_VERSION = '1';

const MKWVS_FB_CATEGORIES = [
    'blind-test' => [
        'name'     => 'Blind test',
        'keywords' => ['blind test'],
    ],
    'jam-session' => [
        'name'     => 'Jam session',
        'keywords' => ['jam', 'irish session'],
    ],
    'scene-ouverte' => [
        'name'     => 'Scène ouverte',
        'keywords' => ['scène ouverte', 'scene ouverte', 'poésives', 'poesives', 'open mic', 'comedy club', 'impro', 'cabaret'],
    ],
    'ateliers' => [
        'name'     => 'Ateliers',
        'keywords' => ['atelier', 'punch needle', 'broderie', 'tricot', 'crochet', 'écriture', 'ecriture', 'textico', 'linogravure', 'café philo', 'cafe philo'],
    ],
    'bien-etre' => [
        'name'     => 'Bien-être',
        'keywords' => ['bien-être', 'bien être', 'reflexologie', 'réflexologie', 'olfactothérapie', 'olfactotherapie', 'sonothérapie', 'sonotherapie', 'yoga', 'astrologie', 'cercle', 'burn-out', 'burn out', 'permanence santé'],
    ],
    'cafe-des-langues' => [
        'name'     => 'Café des langues',
        'keywords' => ['café des langues', 'cafe des langues', 'café italien', 'cafe italien', 'latinos en lille', 'café bilingue', 'cafe bilingue'],
    ],
    'concerts' => [
        'name'     => 'Concerts',
        'keywords' => ['concert', 'bluesy', 'ukulele', 'ukulélé', 'fusy', 'chorale', 'gospel'],
    ],
    'soirees' => [
        'name'     => 'Soirées',
        'keywords' => ['karaoké', 'karaoke', 'party', 'soirée', 'soiree', 'dj', 'apéro', 'apero', 'drag', 'bingo', 'quizz', 'techno'],
    ],
];

function mkwvs_fb_ensure_categories(): void
{
    if (!taxonomy_exists('facebook_category')) {
        return;
    }
    foreach (MKWVS_FB_CATEGORIES as $slug => $data) {
        if (!term_exists($slug, 'facebook_category')) {
            wp_insert_term($data['name'], 'facebook_category', ['slug' => $slug]);
        }
    }
}
add_action('init', 'mkwvs_fb_ensure_categories', 20);

function mkwvs_fb_match_categories(string $title): array
{
    $haystack = mb_strtolower($title);
    $term_ids = [];
    foreach (MKWVS_FB_CATEGORIES as $slug => $data) {
        foreach ($data['keywords'] as $keyword) {
            if (str_contains($haystack, mb_strtolower($keyword))) {
                $term = get_term_by('slug', $slug, 'facebook_category');
                if ($term instanceof WP_Term) {
                    $term_ids[] = (int) $term->term_id;
                }
                break;
            }
        }
    }

    return array_values(array_unique($term_ids));
}

function mkwvs_fb_autocategorize_on_save(int $post_id, WP_Post $post): void
{
    if ($post->post_type !== 'facebook_events') {
        return;
    }
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    $term_ids = mkwvs_fb_match_categories($post->post_title);
    if ($term_ids !== []) {
        wp_set_object_terms($post_id, $term_ids, 'facebook_category', false);
    }
}
add_action('save_post', 'mkwvs_fb_autocategorize_on_save', 20, 2);

function mkwvs_fb_perform_backfill(?callable $logger = null): array
{
    @set_time_limit(300);
    mkwvs_fb_ensure_categories();

    $paged             = 1;
    $total_processed   = 0;
    $total_categorized = 0;

    while (true) {
        $query = new WP_Query([
            'post_type'      => 'facebook_events',
            'post_status'    => 'any',
            'posts_per_page' => 200,
            'paged'          => $paged,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        ]);

        if ($query->posts === []) {
            break;
        }

        foreach ($query->posts as $post_id) {
            $post = get_post($post_id);
            if (!$post instanceof WP_Post) {
                continue;
            }
            $term_ids = mkwvs_fb_match_categories($post->post_title);
            $total_processed++;
            if ($term_ids !== []) {
                wp_set_object_terms($post_id, $term_ids, 'facebook_category', false);
                $total_categorized++;
            }
        }

        if ($logger !== null) {
            $logger(sprintf('Page %d : %d events traités', $paged, count($query->posts)));
        }
        $paged++;
    }

    return [
        'processed'   => $total_processed,
        'categorized' => $total_categorized,
    ];
}

function mkwvs_fb_maybe_schedule_backfill(): void
{
    if (defined('WP_CLI') && WP_CLI) {
        return;
    }
    if (wp_doing_cron()) {
        return;
    }
    if (get_option('mkwvs_fb_categories_backfill_version') === MKWVS_FB_CATEGORIES_VERSION) {
        return;
    }
    if (wp_next_scheduled('mkwvs_fb_cron_backfill', [MKWVS_FB_CATEGORIES_VERSION]) !== false) {
        return;
    }
    wp_schedule_single_event(time() + 30, 'mkwvs_fb_cron_backfill', [MKWVS_FB_CATEGORIES_VERSION]);
}
add_action('init', 'mkwvs_fb_maybe_schedule_backfill', 30);

function mkwvs_fb_cron_backfill_handler(string $version): void
{
    mkwvs_fb_perform_backfill();
    update_option('mkwvs_fb_categories_backfill_version', $version);
}
add_action('mkwvs_fb_cron_backfill', 'mkwvs_fb_cron_backfill_handler', 10, 1);

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('mkwvs fb-recategorize', function (): void {
        $stats = mkwvs_fb_perform_backfill(function (string $line): void {
            WP_CLI::log($line);
        });

        update_option('mkwvs_fb_categories_backfill_version', MKWVS_FB_CATEGORIES_VERSION);

        WP_CLI::success(sprintf(
            '%d events traités, %d catégorisés, %d non catégorisés',
            $stats['processed'],
            $stats['categorized'],
            $stats['processed'] - $stats['categorized'],
        ));
    });
}
