<?php

/**
 * Template Name: Événement — Blind test Lille
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    // ============================================================
    // Données structurées — Event récurrent + FAQPage
    // https://schema.org/Event
    // ============================================================
    $page_url = get_permalink();
    $thumbnail_id = get_post_thumbnail_id();
    $event_image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

    $event_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => 'Blind test à Lille — Le Bus Magique',
        'description' => 'Blind test musical hebdomadaire sur une péniche à Lille. Tous les mardis à 20h, en équipe, entrée libre.',
        'url' => $page_url,
        'image' => $event_image ?: null,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventSchedule' => [
            '@type' => 'Schedule',
            'repeatFrequency' => 'P1W',
            'byDay' => 'https://schema.org/Tuesday',
            'startTime' => '20:00',
            'duration' => 'PT2H',
            'scheduleTimezone' => 'Europe/Paris',
        ],
        'location' => [
            '@type' => 'Place',
            'name' => 'Le Bus Magique',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Avenue Cuvier',
                'addressLocality' => 'Lille',
                'postalCode' => '59800',
                'addressCountry' => 'FR',
            ],
        ],
        'organizer' => [
            '@type' => 'Organization',
            'name' => 'Le Bus Magique',
            'url' => home_url('/'),
        ],
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'EUR',
            'availability' => 'https://schema.org/InStock',
            'url' => $page_url,
        ],
    ];
    $event_schema = array_filter($event_schema);

    $faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => "Faut-il réserver pour participer au blind test ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "La réservation n'est pas obligatoire mais fortement conseillée pour les équipes de 4 personnes et plus. Contactez-nous via le formulaire pour garantir votre table.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien coûte l'entrée au blind test ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "L'entrée au blind test du Bus Magique est gratuite. Seules les consommations au bar sont payantes.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quels types de musiques sont joués pendant le blind test ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Chansons françaises, pop internationale, musiques de films, génériques cultes, variété et tubes rétro. Une sélection accessible à tous, sans être un mélomane confirmé.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quelle taille d'équipe pour jouer ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Les équipes comptent entre 2 et 6 personnes. Vous pouvez venir seul(e), nous formons des équipes sur place avec d'autres participants.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Où se trouve la péniche Le Bus Magique ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Le Bus Magique est amarré quai de l'Esplanade à Lille (59800), en bord de Deûle, à proximité de la Citadelle et du Vieux-Lille.",
                ],
            ],
        ],
    ];
    ?>
    <script type="application/ld+json"><?php echo wp_json_encode($event_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <script type="application/ld+json"><?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <section class="section-landing-standard">

      <?php include(locate_template('template-part/blocks/page-head.php')); ?>

      <div class="text-yellow-background bottom priv-intro">
        <h2>Un blind test musical tous les mardis soir sur péniche</h2>
        <p>Le Bus Magique, péniche amarrée quai de l'Esplanade à Lille, vous propose son blind test hebdomadaire : une soirée musicale conviviale, mêlant tubes rétro, hits pop, variété française et culture générale. Venez tester vos oreilles en équipe, un verre à la main, sur une péniche au cœur de Lille. Ouvert à tous, gratuit, sans inscription.</p>
      </div>

    </section>

    <section class="event-recurrent">

      <div class="event-recurrent__grid">

        <article class="event-card event-card--red">
          <h2>Comment ça se passe ?</h2>
          <p>Nos soirées blind test démarrent à 20h, chaque mardi (hors vacances scolaires). Constituez une équipe de 2 à 6 personnes et venez affronter les autres tablées autour d'une trentaine d'extraits musicaux sélectionnés par notre animatrice. Chansons françaises, pop internationale, musiques de films, génériques cultes : un best-of accessible à tous, sans être un·e mélomane confirmé·e.</p>
          <p>Chaque manche dure environ 15 minutes, avec 10 extraits à identifier (titre, interprète, film ou série d'origine). Les équipes notent leurs réponses sur une feuille, et les résultats sont annoncés à la fin de chaque manche. Lots à gagner pour les trois premières équipes : bouteilles, bons de consommation et goodies du Bus Magique.</p>
        </article>

        <article class="event-card event-card--green">
          <h2>Où et quand ?</h2>
          <ul>
            <li><strong>Quand</strong> : tous les mardis, 20h (hors vacances scolaires)</li>
            <li><strong>Durée</strong> : environ 2h, de 20h à 22h</li>
            <li><strong>Où</strong> : péniche Le Bus Magique, quai de l'Esplanade, 59800 Lille</li>
            <li><strong>Accès</strong> : métro Cormontaigne (ligne 2) à 10 min à pied, ou tram Bois Blancs</li>
            <li><strong>Tarif</strong> : entrée libre et gratuite</li>
            <li><strong>Réservation</strong> : conseillée pour les équipes de 4+, via notre <a href="/contact/">formulaire de contact</a></li>
          </ul>
        </article>

        <article class="event-card event-card--yellow">
          <h2>Manger et boire pendant le blind test</h2>
          <p>Notre <a href="/restauration/">bar</a> reste ouvert tout au long de la soirée. À la carte : bières locales des brasseries lilloises, vins natures, cocktails maison, softs bio, ainsi que nos planches apéro à partager (charcuterie, fromages, houmous, légumes de saison).</p>
          <p>Arrivez dès 19h pour grignoter tranquillement avant de démarrer, ou commandez directement à table pendant la partie. Notre équipe sert pendant toute la durée du blind test.</p>
        </article>

        <article class="event-card event-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche associative lilloise amarrée sur la Deûle depuis 2019, à deux pas de la Citadelle et du Vieux-Lille. Un lieu atypique, chaleureux et intergénérationnel où se croisent <a href="/restauration/">restauration</a>, <a href="/programmation/">programmation culturelle</a>, <a href="/coworking/">coworking</a> et <a href="/location/">événements privés</a>.</p>
          <p>Gérée par une association loi 1901, la péniche accueille chaque semaine plusieurs centaines de Lillois et visiteurs autour d'un projet de tiers-lieu convivial et inclusif. <a href="/monter-a-bord/">En savoir plus sur la péniche et adhérer</a>.</p>
        </article>

      </div>

      <div class="event-recurrent__faq">
        <h2>Questions fréquentes sur le blind test</h2>

        <details class="event-faq-item">
          <summary><h3>Faut-il réserver pour participer au blind test ?</h3></summary>
          <p>La réservation n'est pas obligatoire mais fortement conseillée pour les équipes de 4 personnes et plus. Contactez-nous via <a href="/contact/">le formulaire de contact</a> pour garantir votre table.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Combien coûte l'entrée au blind test ?</h3></summary>
          <p>L'entrée au blind test du Bus Magique est entièrement gratuite. Seules les consommations au bar sont payantes (bières à partir de 3,50 €, planches apéro à partager dès 12 €).</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Quels types de musiques sont joués ?</h3></summary>
          <p>La playlist mélange chansons françaises, pop internationale, musiques de films, génériques cultes, variété et tubes rétro des années 80-2010. Une sélection grand public, pensée pour que chacun·e puisse reconnaître des titres, même sans être un·e mélomane confirmé·e.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Quelle taille d'équipe pour jouer ?</h3></summary>
          <p>Les équipes comptent entre 2 et 6 personnes. Si vous venez seul·e ou en duo, nous pouvons former des équipes sur place avec d'autres participants. C'est l'occasion de rencontrer d'autres Lillois !</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Où se trouve la péniche Le Bus Magique ?</h3></summary>
          <p>Le Bus Magique est amarré <strong>quai de l'Esplanade à Lille (59800)</strong>, en bord de Deûle, à proximité immédiate de la Citadelle et du Vieux-Lille. Parking Esplanade à 2 min, métro Cormontaigne à 10 min à pied.</p>
        </details>
      </div>

      <div class="event-recurrent__cta">
        <h2>Prochaines dates de blind test à Lille</h2>
        <p>Retrouvez les dates à venir et réservez directement sur notre <a href="/programmation/">agenda de programmation</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a> pour ne rien manquer.</p>
        <?php
        $home = get_page_by_path('accueil');
        $prog_image = $home ? get_field('programmation_du_mois', $home->ID) : null;
        if (is_array($prog_image) && !empty($prog_image['url'])) : ?>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('programmation'))); ?>" class="event-recurrent__prog-link" aria-label="Voir la programmation complète du Bus Magique à Lille">
            <img src="<?php echo esc_url($prog_image['url']); ?>" alt="<?php echo esc_attr($prog_image['alt'] ?: 'Programmation du mois au Bus Magique à Lille'); ?>" loading="lazy">
          </a>
        <?php else : ?>
          <a href="/programmation/" class="cta">Voir la programmation</a>
        <?php endif; ?>
      </div>

    </section>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
