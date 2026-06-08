<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

const MKWVS_FB_CATEGORIES_VERSION = '2';

const MKWVS_FB_CATEGORY_DEFAULT = 'culturels-festifs';

const MKWVS_FB_CATEGORIES = [
    'prog-conviviale' => [
        'name'     => 'Programmation conviviale',
        'keywords' => [
            'rencontres magiques', 'rencontres magique', 'rencontre magique',
            'apéro tricot', 'apero tricot',
            'apéro bénévole', 'apero benevole', 'bénévoles', 'benevoles', 'afterwork',
            'jeux de société', 'jeux de societe', 'quai des jeux', 'tournoi',
            'karaoké', 'karaoke', 'blind test', 'blind-test', 'drag bingo', 'bingo',
            'music quizz', 'café des langues', 'cafe des langues', 'café italien',
            'cafe italien', 'café bilingue', 'cafe bilingue', 'latinos', 'chorale',
        ],
    ],
    'bien-etre' => [
        'name'     => 'Bien-être',
        'keywords' => [
            'bien-être', 'bien être', 'bien etre',
            'permanence', 'olfactothérapie', 'olfactotherapie', 'réflexologie',
            'reflexologie', 'santé au naturel', 'sante au naturel', 'cercle',
            'burn-out', 'burn out', 'astrologie', 'astrofil', 'astr’o', 'astr\'o',
            'ikigaï', 'ikigai',
            'psychologie positive', 'sophrologie', 'relaxation', 'yoga', 'rester zen',
            'cocons électroniques', 'cocons electroniques', 'sexualité', 'sexualite',
        ],
    ],
    'prog-engagee-inclusive' => [
        'name'     => 'Programmation engagée & inclusive',
        'keywords' => [
            'dragshow', 'drag show', 'conférence gesticulée', 'conference gesticulee',
            'beauté où es-tu', 'beaute ou es-tu', 'où es-tu', 'ou es-tu', 'pride',
            'matins magiques', 'matin magique', 'projection', 'café rse', 'cafe rse',
            'transition', 'table ronde', 'fresque', 'l’ess', 'l\'ess', 'porteurs de projet',
            'déjeuner dans le noir', 'dejeuner dans le noir', 'dans le noir',
            'café philo', 'cafe philo', 'marche magique', 'marches magiques',
            'biodiversité', 'biodiversite', 'vélo boulo dodo', 'velo boulo dodo',
            'boulot, dodo', 'gafam',
        ],
    ],
    'culturels-festifs' => [
        'name'     => 'Événements culturels & festifs',
        'keywords' => [
            'concert', 'blues', 'fanfare', 'chauffe marcelle', 'daisy belle', 'la frange',
            'spectacle', 'show', 'soirée électro', 'soiree electro', 'techno',
            'soirée dj', 'soiree dj',
            'impro', 'impropoulpes', 'impronight', 'stand-up', 'stand up', 'comedy club',
            'dom juan', 'dragshow', 'drag show', 'guinguette', 'pride',
        ],
    ],
    'ateliers-artistiques' => [
        'name'     => 'Ateliers de pratique artistique',
        'keywords' => [
            'écriture', 'ecriture', 'photographie instantanée',
            'photographie instantanee', 'encre de chine', 'linogravure', 'punch needle',
            'modelage', 'argile', 'illustration', 'illu inspiration', 'naturaliste',
        ],
    ],
    'jams-scenes-ouvertes' => [
        'name'     => 'Jams & scènes ouvertes',
        'keywords' => [
            'jam', 'ukulele', 'ukulélé', 'tiny house', 'irish session',
            'scène ouverte', 'scene ouverte', 'poésives', 'poesives',
        ],
    ],
];

function mkwvs_fb_restrict_category_taxonomy(array $args, string $taxonomy): array
{
    if ($taxonomy === 'facebook_category') {
        $args['public']             = false;
        $args['publicly_queryable'] = false;
        $args['rewrite']            = false;
        $args['show_ui']            = true;
    }

    return $args;
}
add_filter('register_taxonomy_args', 'mkwvs_fb_restrict_category_taxonomy', 10, 2);

function mkwvs_fb_disable_404_guess_for_categories(bool $guess): bool
{
    $path = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    if (strpos($path, '/facebook_category/') === 0) {
        return false;
    }

    return $guess;
}
add_filter('do_redirect_guess_404_permalink', 'mkwvs_fb_disable_404_guess_for_categories');

function mkwvs_fb_maybe_flush_rewrite(): void
{
    if (get_option('mkwvs_fb_rewrite_flush_version') === MKWVS_FB_CATEGORIES_VERSION) {
        return;
    }
    flush_rewrite_rules(false);
    update_option('mkwvs_fb_rewrite_flush_version', MKWVS_FB_CATEGORIES_VERSION);
}
add_action('init', 'mkwvs_fb_maybe_flush_rewrite', 99);

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

    if ($term_ids === []) {
        $default = get_term_by('slug', MKWVS_FB_CATEGORY_DEFAULT, 'facebook_category');
        if ($default instanceof WP_Term) {
            $term_ids[] = (int) $default->term_id;
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
