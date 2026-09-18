<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Migration one-shot : crée la page « Notre péniche à Lille », page cible de la
 * requête « péniche Lille » (la CI ne déploie que le code).
 */

const MKWVS_PENICHE_PAGE_SLUG = 'peniche-lille';
const MKWVS_PENICHE_PAGE_TEMPLATE = 'templates/peniche-lille.php';

add_action('init', 'mkwvs_migrate_peniche_page');

function mkwvs_migrate_peniche_page(): void
{
    if ((int) get_option('mkwvs_peniche_page_migrated', 0) >= 1) {
        return;
    }

    if (get_page_by_path(MKWVS_PENICHE_PAGE_SLUG) instanceof WP_Post) {
        update_option('mkwvs_peniche_page_migrated', 1);

        return;
    }

    $hero_id = mkwvs_peniche_photo_id('peniche-exterieur.jpg', "La péniche du Bus Magique amarrée sur la Deûle, au pied des remparts de la Citadelle de Lille");

    $photos = [
        'photo_timonerie' => mkwvs_peniche_photo_id('timonerie.jpg', "Timonerie de la péniche avec sa barre à roue d'origine et sa vue sur le canal"),
        'photo_studio' => mkwvs_peniche_photo_id('studio.jpg', "Vue d'ensemble du studio : bar, kitchenette et espace nuit"),
    ];

    $page_id = wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => 'Notre péniche à Lille',
        'post_name' => MKWVS_PENICHE_PAGE_SLUG,
        'post_content' => mkwvs_peniche_page_content($photos),
    ]);

    if (is_wp_error($page_id) || !$page_id) {
        return;
    }

    update_post_meta($page_id, '_wp_page_template', MKWVS_PENICHE_PAGE_TEMPLATE);

    if ($hero_id) {
        set_post_thumbnail($page_id, $hero_id);
    }

    $icon_id = mkwvs_peniche_hublot_icon_id();
    if ($icon_id && function_exists('update_field')) {
        update_field('page_head_hublot_icon', $icon_id, $page_id);
    }

    update_post_meta($page_id, '_seopress_titles_title', 'Péniche à Lille : bar, restaurant et tiers-lieu | Le Bus Magique');
    update_post_meta(
        $page_id,
        '_seopress_titles_desc',
        "Le Bus Magique est une péniche amarrée à l'entrée de la Citadelle de Lille : bar, restauration, concerts, coworking et privatisation à bord d'un bateau de 1954."
    );

    flush_rewrite_rules(false);

    update_option('mkwvs_peniche_page_migrated', 1);
}

function mkwvs_peniche_page_url(): string
{
    $page = get_page_by_path(MKWVS_PENICHE_PAGE_SLUG);

    return $page instanceof WP_Post ? (string) get_permalink($page) : home_url('/' . MKWVS_PENICHE_PAGE_SLUG . '/');
}

/**
 * Réutilise les photos déjà importées par la page gîte, sinon les importe.
 */
function mkwvs_peniche_photo_id(string $file, string $alt): int
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

    if (!function_exists('mkwvs_hebergement_import_photo')) {
        return 0;
    }

    return mkwvs_hebergement_import_photo($file, $alt);
}

/**
 * Hublot générique du thème, à défaut de quoi page-head.php pose son icône de repli.
 */
function mkwvs_peniche_hublot_icon_id(): int
{
    $found = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => '_wp_attached_file',
                'value' => 'hublot-icon.svg',
                'compare' => 'LIKE',
            ],
        ],
    ]);

    return empty($found) ? 0 : (int) $found[0];
}

function mkwvs_peniche_page_content(array $photos): string
{
    $content = <<<'HTML'
<!-- wp:html -->
<div class="peniche">

  <p class="peniche__chapo">Le Bus Magique est une péniche amarrée avenue Cuvier, à l'entrée de la Citadelle de Lille, le long de la Deûle. Un bateau de 1954 devenu un tiers-lieu associatif : on y déjeune, on y boit un verre, on y travaille, on y assiste à des concerts et à des ateliers, et on peut même y passer la nuit.</p>

  <div class="peniche__split">
    <div class="peniche__split-text">
      <h2>Une péniche citerne de 1954</h2>
      <p>Le bateau s'appelait l'Île. C'est une péniche citerne construite en 1954, une vieille dame que l'association a choisie en février 2019, après avoir obtenu l'été précédent l'autorisation de stationner devant le Champ de Mars.</p>
      <p>De février 2019 à octobre 2020, des chantiers participatifs l'ont transformée en lieu de vie. C'est aujourd'hui la péniche associative de Lille, ouverte à toutes et à tous. <a href="/notre-histoire/">Lire l'histoire du bateau</a>.</p>
    </div>
    <figure class="peniche__split-media">
      <img src="{{photo_timonerie}}" alt="Timonerie de la péniche avec sa barre à roue d'origine et sa vue sur le canal" loading="lazy">
    </figure>
  </div>

  <h2 class="peniche__title">Ce que l'on fait à bord</h2>
  <ul class="peniche__usages">
    <li>
      <strong>Manger et boire un verre</strong>
      <p>Plats du jour les jeudi et vendredi midi, brunch le dimanche, bières locales et boissons chaudes. Cuisine maison, bio et de saison.</p>
      <a href="/restauration/">Voir la carte de la péniche</a>
    </li>
    <li>
      <strong>Sortir et assister aux événements</strong>
      <p>Concerts, scènes ouvertes, jam sessions, blind tests, drag bingo, café philo, café des langues et ateliers créatifs.</p>
      <a href="/programmation/">Voir la programmation</a>
    </li>
    <li>
      <strong>Travailler au bord de l'eau</strong>
      <p>Espace de coworking ouvert les jeudi et vendredi, de 9h à 12h et de 14h à 17h. Wifi haut débit, réseau Ethernet, café et pâtisseries.</p>
      <a href="/coworking/">Découvrir le coworking</a>
    </li>
    <li>
      <strong>Privatiser le bateau</strong>
      <p>Anniversaire, séminaire, soirée d'entreprise ou mariage : 60 personnes assises en salle, 100 en cocktail, plus une terrasse sur le pont.</p>
      <a href="/location/">Privatiser la péniche</a>
    </li>
  </ul>

  <div class="peniche__access">
    <h2>Où est amarrée la péniche</h2>
    <ul>
      <li><strong>Adresse :</strong> péniche Le Bus Magique, avenue Cuvier, 59800 Lille, à l'entrée de la Citadelle</li>
      <li><strong>Métro :</strong> station Rihour</li>
      <li><strong>Bus :</strong> arrêt Champ de Mars</li>
      <li><strong>V'Lille :</strong> station à moins de 5 minutes à pied</li>
      <li><strong>Voiture :</strong> parking gratuit du Champ de Mars</li>
    </ul>
  </div>

  <div class="peniche__split">
    <div class="peniche__split-text">
      <h2>Dormir sur la péniche</h2>
      <p>À l'avant du bateau, le logement du Marinier se loue à la nuit pour deux à trois personnes, avec sa terrasse privée sur le pont et sa vue sur le canal. C'est un hébergement indépendant du bar et du restaurant.</p>
      <p><a href="/dormir-sur-une-peniche-a-lille/">Voir les disponibilités du gîte</a>.</p>
    </div>
    <figure class="peniche__split-media">
      <img src="{{photo_studio}}" alt="Vue d'ensemble du studio du Marinier : bar, kitchenette et espace nuit" loading="lazy">
    </figure>
  </div>

  <div class="peniche__assoc">
    <h2>Une péniche portée par une association</h2>
    <p>Le Bus Magique est une association loi 1901 née au printemps 2018. Le lieu vit grâce à ses bénévoles et à ses adhérents, autour de quelques valeurs simples : le bien-être, le lien social, le respect de l'environnement et le soutien à l'économie locale.</p>
    <p><a href="/monter-a-bord/">Adhérer et monter à bord</a></p>
  </div>

  <h2 class="peniche__title">Questions fréquentes</h2>
  <div class="peniche__faq">
    <details open>
      <summary>Où se trouve la péniche Le Bus Magique à Lille ?</summary>
      <p>La péniche est amarrée avenue Cuvier, 59800 Lille, à l'entrée de la Citadelle, le long de la Deûle. On y accède par le métro Rihour ou l'arrêt de bus Champ de Mars, et le parking du Champ de Mars est gratuit.</p>
    </details>
    <details>
      <summary>Peut-on manger et boire un verre sur la péniche ?</summary>
      <p>Oui. La péniche sert des plats du jour les jeudi et vendredi midi et un brunch le dimanche, avec une cuisine maison, bio et de saison. Le bar propose des bières locales, des vins et des boissons chaudes.</p>
    </details>
    <details>
      <summary>Faut-il adhérer à l'association pour monter à bord ?</summary>
      <p>Oui. Le Bus Magique est une association, l'adhésion est donc nécessaire. Son montant est libre, c'est vous qui décidez, et elle se prend directement à bord auprès d'un bénévole ou d'un serveur.</p>
    </details>
    <details>
      <summary>Peut-on privatiser la péniche pour un événement ?</summary>
      <p>Oui, pour un anniversaire, un séminaire, une soirée d'entreprise ou un mariage. La salle accueille 60 personnes assises et 100 en cocktail, la terrasse 40 assises et 60 en cocktail. Les créneaux vont du lundi au mercredi en journée et en soirée, les dimanches à partir de 20h et les samedis de 9h à 16h.</p>
    </details>
    <details>
      <summary>Peut-on dormir sur la péniche ?</summary>
      <p>Oui. Le logement du Marinier, à l'avant du bateau, se loue à la nuit pour deux à trois personnes, avec sa terrasse privée et sa salle de bain.</p>
    </details>
  </div>

</div>
<!-- /wp:html -->
HTML;

    foreach ($photos as $key => $id) {
        $url = $id ? wp_get_attachment_image_url($id, 'full') : '';
        $content = str_replace('{{' . $key . '}}', (string) $url, $content);
    }

    return $content;
}
