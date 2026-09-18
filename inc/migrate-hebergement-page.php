<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Migration one-shot : crée la page « Dormir sur une péniche à Lille » et
 * importe ses photos depuis le thème (la CI ne déploie que le code).
 */

add_action('init', 'mkwvs_migrate_hebergement_page');

const MKWVS_HEBERGEMENT_PHOTOS = [
    'exterieur' => ['peniche-exterieur.jpg', "La péniche du Bus Magique amarrée sur la Deûle, au pied des remparts de la Citadelle de Lille"],
    'timonerie' => ['timonerie.jpg', "Timonerie de la péniche avec sa barre à roue d'origine et sa vue sur le canal"],
    'terrasse' => ['terrasse.jpg', "Terrasse sur le pont de la péniche, face aux remparts de la Citadelle"],
    'chambre' => ['chambre.jpg', "Lit préparé avec draps blancs et serviettes, sous le hublot du studio"],
    'studio' => ['studio.jpg', "Vue d'ensemble du studio : bar, kitchenette et espace nuit"],
];

function mkwvs_migrate_hebergement_page(): void
{
    if ((int) get_option('mkwvs_hebergement_page_migrated', 0) >= 1) {
        return;
    }

    if (get_page_by_path(MKWVS_HEBERGEMENT_PAGE_SLUG) instanceof WP_Post) {
        update_option('mkwvs_hebergement_page_migrated', 1);

        return;
    }

    $photos = [];
    foreach (MKWVS_HEBERGEMENT_PHOTOS as $key => [$file, $alt]) {
        $id = mkwvs_hebergement_import_photo($file, $alt);
        if (!$id) {
            return;
        }
        $photos[$key] = $id;
    }

    $page_id = wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => 'Dormir sur une péniche à Lille',
        'post_name' => MKWVS_HEBERGEMENT_PAGE_SLUG,
        'post_content' => mkwvs_hebergement_page_content($photos),
    ]);

    if (is_wp_error($page_id) || !$page_id) {
        return;
    }

    update_post_meta($page_id, '_wp_page_template', 'templates/hebergement.php');
    set_post_thumbnail($page_id, $photos['exterieur']);
    update_post_meta($page_id, '_seopress_titles_title', 'Nuit insolite à Lille : dormir sur une péniche | Le Bus Magique');
    update_post_meta(
        $page_id,
        '_seopress_titles_desc',
        "Louez le studio du Marinier sur une péniche amarrée à la Citadelle de Lille. Pour 2 à 3 personnes, terrasse et vue sur le canal. Disponibilités en ligne."
    );

    update_option('mkwvs_hebergement_page_migrated', 1);
}

function mkwvs_hebergement_import_photo(string $file, string $alt): int
{
    $existing = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_key' => '_mkwvs_hebergement_photo',
        'meta_value' => $file,
    ]);

    if (!empty($existing)) {
        return (int) $existing[0];
    }

    $source = get_template_directory() . '/images/hebergement/' . $file;
    if (!file_exists($source)) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $upload = wp_upload_bits($file, null, (string) file_get_contents($source));
    if (!empty($upload['error'])) {
        return 0;
    }

    $attachment_id = wp_insert_attachment([
        'post_mime_type' => 'image/jpeg',
        'post_title' => sanitize_file_name(pathinfo($file, PATHINFO_FILENAME)),
        'post_status' => 'inherit',
    ], $upload['file']);

    if (is_wp_error($attachment_id) || !$attachment_id) {
        return 0;
    }

    wp_update_attachment_metadata(
        $attachment_id,
        wp_generate_attachment_metadata($attachment_id, $upload['file'])
    );
    update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
    update_post_meta($attachment_id, '_mkwvs_hebergement_photo', $file);

    return (int) $attachment_id;
}

function mkwvs_hebergement_page_content(array $photos): string
{
    $content = <<<'HTML'
<!-- wp:html -->
<div class="hebergement">

  <p class="hebergement__chapo">Le Bus Magique loue le studio du Marinier, à l'avant de la péniche, amarrée aux portes de la Citadelle de Lille. Un hébergement insolite à Lille, sur l'eau, à vingt minutes à pied du Vieux-Lille.</p>

  <div class="hebergement__split">
    <div class="hebergement__split-text">
      <h2>Une nuit à bord, au fil de la Deûle</h2>
      <p>Le studio est indépendant du <a href="/restauration/">bar et du restaurant</a> : vous avez votre entrée, votre terrasse sur le pont et votre calme. Les hublots donnent sur le canal, la Citadelle commence de l'autre côté du quai.</p>
      <p>C'est une adresse pour une nuit insolite à Lille, une escapade à deux ou un week-end dans le Nord, dans un logement insolite que personne d'autre ne propose : une vraie péniche, avec sa timonerie et sa barre à roue d'origine.</p>
    </div>
    <figure class="hebergement__split-media">
      <img src="{{timonerie}}" alt="Timonerie de la péniche avec sa barre à roue d'origine et sa vue sur le canal" loading="lazy">
    </figure>
  </div>

  <h2 class="hebergement__title">Le logement</h2>
  <ul class="hebergement__features">
    <li><strong>2 à 3 personnes</strong><span>2 lits et une salle de bain privative</span></li>
    <li><strong>Kitchenette équipée</strong><span>Coin salon, réfrigérateur, plaques et micro-ondes</span></li>
    <li><strong>Terrasse sur le pont</strong><span>Face aux remparts de la Citadelle, vue sur la Deûle</span></li>
    <li><strong>Tout confort</strong><span>Wifi, chauffage, télévision, lave-linge</span></li>
  </ul>

  <div class="hebergement__gallery">
    <figure><img src="{{terrasse}}" alt="Terrasse sur le pont de la péniche, face aux remparts de la Citadelle" loading="lazy"></figure>
    <figure><img src="{{chambre}}" alt="Lit préparé avec draps blancs et serviettes, sous le hublot du studio" loading="lazy"></figure>
    <figure><img src="{{studio}}" alt="Vue d'ensemble du studio : bar, kitchenette et espace nuit" loading="lazy"></figure>
  </div>

  <div class="hebergement__assoc">
    <h2>Dormir ici, c'est soutenir l'association</h2>
    <p>Le Bus Magique est une association loi 1901. Louer le studio finance le tiers-lieu : la <a href="/programmation/">programmation</a>, les ateliers, le café des langues et tout ce qui se passe à bord. Vous dormez dans un logement atypique et vous faites vivre le projet en même temps.</p>
<!-- /wp:html -->
<!-- wp:html -->
  <p>Pour un groupe plus nombreux, la péniche se <a href="/location/">privatise également</a>, en journée comme en soirée.</p>
  </div>

  <h2 class="hebergement__title">Disponibilités</h2>
  [hebergement_calendrier]

  <p class="hebergement__note">Disponibilités et tarifs tenus à jour sur notre annonce. Note des voyageurs : 4,88 sur 5.</p>

  <h2 class="hebergement__title">Questions fréquentes</h2>
  <div class="hebergement__faq">
    <details open>
      <summary>Combien de personnes peut accueillir le studio de la péniche ?</summary>
      <p>Le studio accueille deux à trois personnes. Il dispose de deux lits et d'une salle de bain privative.</p>
    </details>
    <details>
      <summary>Où est amarrée la péniche à Lille ?</summary>
      <p>La péniche est amarrée avenue Cuvier, à l'entrée de la Citadelle de Lille, à une vingtaine de minutes à pied du Vieux-Lille.</p>
    </details>
    <details>
      <summary>Le logement est-il indépendant du bar et du restaurant ?</summary>
      <p>Oui. Le studio occupe le logement du Marinier, à l'avant du bateau, avec son entrée et sa terrasse privée.</p>
    </details>
    <details>
      <summary>Comment réserver une nuit sur la péniche ?</summary>
      <p>Les disponibilités sont affichées sur cette page et la réservation se fait en ligne sur notre annonce.</p>
    </details>
  </div>

</div>
<!-- /wp:html -->
HTML;

    foreach ($photos as $key => $id) {
        $url = wp_get_attachment_image_url($id, 'full');
        $content = str_replace('{{' . $key . '}}', (string) $url, $content);
    }

    return $content;
}
