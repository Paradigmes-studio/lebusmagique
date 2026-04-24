<?php

/**
 * Template Name: Événement — Jam session Lille
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
        'name' => 'Jam session à Lille — Le Bus Magique',
        'description' => "Jam session ouverte aux musiciens et musiciennes à Lille. Scène libre sur péniche, ambiance conviviale, bar ouvert. Venez jouer ou écouter.",
        'url' => $page_url,
        'image' => $event_image ?: null,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventSchedule' => [
            '@type' => 'Schedule',
            'repeatFrequency' => 'P1M',
            'startTime' => '20:30',
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
                'name' => "Faut-il être musicien professionnel pour participer à la jam ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Pas du tout. Notre jam session est ouverte aux musicien·nes de tous niveaux, amateurs comme confirmés. L'esprit est bienveillant, l'important c'est de partager le moment.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quels instruments peut-on amener ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Tous les instruments sont les bienvenus : guitares, basses, claviers, percussions, cuivres, voix. Une batterie, un ampli guitare et un ampli basse sont à disposition sur place.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quel style de musique est joué ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Principalement soul, funk, blues, jazz, rock, pop. La programmation dépend des musicien·nes présents mais l'ambiance est toujours festive et ouverte.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien coûte l'entrée à la jam session ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "L'entrée à la jam session est libre et gratuite. Seules les consommations au bar sont payantes.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Peut-on venir juste pour écouter ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Bien sûr, la jam est autant un moment pour les musicien·nes que pour le public. Venez profiter d'une soirée live dans une ambiance chaleureuse.",
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
        <h2>Une jam session ouverte à tous les musiciens, sur péniche à Lille</h2>
        <p>Le Bus Magique, péniche associative amarrée quai de l'Esplanade à Lille, accueille régulièrement des jam sessions en scène libre : guitares, basses, claviers, cuivres, voix, percussions. Que vous soyez musicien·ne confirmé·e ou amateur curieux, venez jouer ou écouter un live spontané au bord de la Deûle.</p>
      </div>

    </section>

    <section class="event-recurrent">

      <div class="event-recurrent__grid">

        <article class="event-card event-card--red">
          <h2>Comment ça se passe ?</h2>
          <p>La soirée démarre vers 20h30 avec un set d'ouverture assuré par un groupe invité ou un·e musicien·ne résident·e, puis la scène s'ouvre à toutes les personnes qui souhaitent jouer. Pas de programme figé, pas de style imposé : soul, funk, blues, jazz, rock, pop, tout se croise au fil des arrivées.</p>
          <p>Une batterie complète, un ampli guitare et un ampli basse sont installés sur place. Apportez vos instruments et vos pédales. Une feuille d'inscription circule pour organiser les passages et laisser le temps à chacun·e de monter.</p>
        </article>

        <article class="event-card event-card--green">
          <h2>Où et quand ?</h2>
          <ul>
            <li><strong>Fréquence</strong> : une fois par mois (voir la <a href="/programmation/">programmation</a>)</li>
            <li><strong>Horaire</strong> : 20h30 à 23h30</li>
            <li><strong>Où</strong> : péniche Le Bus Magique, quai de l'Esplanade, 59800 Lille</li>
            <li><strong>Accès</strong> : métro Cormontaigne (ligne 2), tram Bois Blancs, parking Esplanade</li>
            <li><strong>Tarif</strong> : entrée libre</li>
            <li><strong>Inscription musicien·ne</strong> : sur place à l'arrivée</li>
          </ul>
        </article>

        <article class="event-card event-card--yellow">
          <h2>Manger et boire pendant la jam</h2>
          <p>Notre <a href="/restauration/">bar</a> est ouvert tout au long de la soirée : bières de brasseries lilloises, vins natures, cocktails maison, softs bio, planches apéro à partager. La cuisine tourne également selon les soirées.</p>
          <p>Service en continu par notre équipe de bénévoles. Arrivez dès 19h pour dîner avant le démarrage, ou rejoignez-nous plus tard pour un verre entre deux sets.</p>
        </article>

        <article class="event-card event-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche associative lilloise sur la Deûle, à proximité de la Citadelle. Un tiers-lieu où se croisent <a href="/restauration/">restauration</a>, <a href="/programmation/">programmation culturelle</a>, <a href="/coworking/">coworking</a> et <a href="/location/">événements privés</a>.</p>
          <p>Géré par une association loi 1901 portée par ses bénévoles et adhérent·es. Nous soutenons la scène musicale locale et offrons un espace d'expression libre aux artistes émergents. <a href="/monter-a-bord/">Adhérer et soutenir le projet</a>.</p>
        </article>

      </div>

      <div class="event-recurrent__faq">
        <h2>Questions fréquentes sur la jam session</h2>

        <details class="event-faq-item">
          <summary><h3>Faut-il être musicien·ne professionnel·le pour participer ?</h3></summary>
          <p>Pas du tout. Notre jam session est ouverte aux musicien·nes de tous niveaux, amateurs comme confirmés. L'esprit est bienveillant, l'important c'est de partager le moment musical.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Quels instruments peut-on amener ?</h3></summary>
          <p>Tous les instruments sont les bienvenus. Une batterie complète, un ampli guitare et un ampli basse sont à disposition sur place. Pour les autres instruments (cuivres, claviers, percussions), apportez le matériel.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Quel style de musique est joué ?</h3></summary>
          <p>Principalement soul, funk, blues, jazz, rock, pop. La programmation dépend des musicien·nes présent·es, donc l'ambiance varie d'une session à l'autre, toujours festive et ouverte.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Combien coûte l'entrée ?</h3></summary>
          <p>L'entrée à la jam session est libre et gratuite. Seules les consommations au bar sont payantes (bières à partir de 3,50 €).</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Peut-on venir juste pour écouter ?</h3></summary>
          <p>Bien sûr. La jam est autant un moment pour les musicien·nes que pour le public. Venez profiter d'un live spontané dans une ambiance chaleureuse, sans avoir à monter sur scène.</p>
        </details>
      </div>

      <div class="event-recurrent__cta">
        <h2>Prochaines jam sessions à Lille</h2>
        <p>Retrouvez les dates à venir sur notre <a href="/programmation/">agenda</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a> pour ne rien manquer.</p>
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
