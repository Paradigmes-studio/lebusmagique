<?php

/**
 * Template Name: Événement — Scène ouverte Lille
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    $page_url = get_permalink();
    $thumbnail_id = get_post_thumbnail_id();
    $event_image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

    $event_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => 'Scène ouverte à Lille — Le Bus Magique',
        'description' => "Scène ouverte mensuelle à Lille : poésie, slam, drag, stand-up, musique. Péniche Le Bus Magique, quai de l'Esplanade, entrée libre.",
        'url' => $page_url,
        'image' => $event_image ?: null,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventSchedule' => [
            '@type' => 'Schedule',
            'repeatFrequency' => 'P1M',
            'startTime' => '20:00',
            'duration' => 'PT3H',
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
                'name' => "Quels types de performances peut-on proposer ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Poésie, slam, stand-up, chanson, lecture, performance, drag : toutes les formes d'expression artistique sont les bienvenues, tant qu'elles respectent une durée raisonnable (5 à 10 minutes par passage).",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Comment s'inscrire pour monter sur scène ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "L'inscription se fait sur place à l'arrivée, à partir de 19h30. Un·e animateur·rice organise l'ordre de passage pour laisser la place à tout le monde.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il avoir déjà de l'expérience scénique ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Pas du tout. La scène ouverte du Bus Magique est un espace bienveillant d'expression, autant pour les premières fois que pour les artistes confirmé·es.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien coûte l'entrée à la scène ouverte ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "L'entrée est libre et gratuite. Une participation au chapeau est parfois proposée pour soutenir les artistes ou l'association.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Peut-on venir juste pour écouter ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Oui, la scène ouverte est un moment de partage entre artistes et public. Venez assister à des performances variées dans une ambiance conviviale.",
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
        <h2>Une scène ouverte à Lille, pour tous les arts vivants</h2>
        <p>Le Bus Magique, péniche culturelle amarrée quai de l'Esplanade à Lille, accueille une scène ouverte mensuelle dédiée à toutes les formes d'expression : poésie, slam, stand-up, chanson, lecture, performance, drag. Un espace bienveillant pour les artistes émergent·es comme confirmé·es, public amateur ou curieux.</p>
      </div>

    </section>

    <section class="event-recurrent">

      <div class="event-recurrent__grid">

        <article class="event-card event-card--red">
          <h2>Comment ça se passe ?</h2>
          <p>La soirée commence à 20h avec une introduction par l'équipe d'animation, puis les inscrit·es se succèdent sur scène toutes les 5 à 10 minutes. Entre chaque passage, un·e animateur·rice fait le lien, présente les artistes et entretient l'ambiance. Une scène, un micro, des projecteurs : le reste, c'est vous qui l'amenez.</p>
          <p>Les performances sont variées : slam personnel, poésie engagée, chanson acoustique, stand-up, numéro de drag, lecture à voix haute. La soirée dure environ 3 heures, avec une pause bar au milieu.</p>
        </article>

        <article class="event-card event-card--green">
          <h2>Où et quand ?</h2>
          <ul>
            <li><strong>Fréquence</strong> : une fois par mois (voir la <a href="/programmation/">programmation</a>)</li>
            <li><strong>Horaire</strong> : 20h à 23h</li>
            <li><strong>Où</strong> : péniche Le Bus Magique, quai de l'Esplanade, 59800 Lille</li>
            <li><strong>Accès</strong> : métro Cormontaigne (ligne 2), tram Bois Blancs, parking Esplanade</li>
            <li><strong>Tarif</strong> : entrée libre, participation au chapeau</li>
            <li><strong>Inscription scène</strong> : sur place dès 19h30</li>
          </ul>
        </article>

        <article class="event-card event-card--yellow">
          <h2>Manger et boire pendant la soirée</h2>
          <p>Notre <a href="/restauration/">bar</a> reste ouvert toute la soirée : bières locales, vins natures, cocktails maison, softs bio, planches apéro. Arrivez tôt pour dîner, ou rejoignez-nous juste pour la scène ouverte avec un verre à la main.</p>
        </article>

        <article class="event-card event-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche associative lilloise amarrée sur la Deûle, à deux pas de la Citadelle et du Vieux-Lille. Un tiers-lieu inclusif et bienveillant, pensé comme un espace refuge pour la scène émergente locale.</p>
          <p>Nous accueillons des soirées drag, des scènes slam, des résidences artistiques. <a href="/monter-a-bord/">Découvrir le projet et adhérer</a>.</p>
        </article>

      </div>

      <div class="event-recurrent__faq">
        <h2>Questions fréquentes sur la scène ouverte</h2>

        <details class="event-faq-item">
          <summary><h3>Quels types de performances peut-on proposer ?</h3></summary>
          <p>Poésie, slam, stand-up, chanson, lecture, performance, drag : toutes les formes d'expression artistique sont les bienvenues, tant qu'elles respectent une durée raisonnable (5 à 10 minutes par passage).</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Comment s'inscrire pour monter sur scène ?</h3></summary>
          <p>L'inscription se fait sur place à l'arrivée, à partir de 19h30. Un·e animateur·rice organise l'ordre de passage pour laisser la place à tout le monde dans la limite du temps disponible.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Faut-il avoir déjà de l'expérience scénique ?</h3></summary>
          <p>Pas du tout. La scène ouverte du Bus Magique est un espace bienveillant d'expression, autant pour les premières fois que pour les artistes confirmé·es. L'équipe est là pour vous mettre à l'aise.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Combien coûte l'entrée ?</h3></summary>
          <p>L'entrée est libre et gratuite. Une participation au chapeau est parfois proposée en fin de soirée pour soutenir les artistes invité·es ou l'association.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Peut-on venir juste pour écouter ?</h3></summary>
          <p>Oui, la scène ouverte est un moment de partage entre artistes et public. Venez découvrir la scène émergente lilloise dans une ambiance conviviale, sans obligation de performer.</p>
        </details>
      </div>

      <div class="event-recurrent__cta">
        <h2>Prochaines scènes ouvertes à Lille</h2>
        <p>Retrouvez les dates à venir sur notre <a href="/programmation/">agenda</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a>.</p>
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
