<?php

/**
 * Template Name: Événement — Ateliers Lille
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
        '@type' => 'EventSeries',
        'name' => 'Ateliers créatifs et culturels à Lille — Le Bus Magique',
        'description' => "Programmation régulière d'ateliers à Lille : écriture créative, linogravure, café philo, broderie, punch needle, fleurs de Bach. Péniche Le Bus Magique, quai de l'Esplanade.",
        'url' => $page_url,
        'image' => $event_image ?: null,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
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
        'startDate' => date('Y-m-d'),
    ];
    $event_schema = array_filter($event_schema);

    $faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => "Quels ateliers sont proposés au Bus Magique ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Écriture créative, linogravure, café philo, broderie palestinienne, punch needle, fleurs de Bach, texticologie, sonothérapie : les ateliers varient chaque mois. Retrouvez le détail sur notre programmation.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il s'inscrire à l'avance ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Oui, les ateliers ont des places limitées (généralement 8 à 15 personnes). L'inscription se fait via la page de l'événement sur notre programmation ou par mail.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien coûte un atelier ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Les tarifs varient selon l'atelier (généralement entre 10 et 35 € selon la durée et le matériel fourni). Certains ateliers sont à prix libre. Détail sur chaque fiche événement.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il amener du matériel ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Non, le matériel est fourni par les intervenant·es. Précisions spécifiques communiquées au moment de l'inscription si besoin.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il être adhérent·e pour participer ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Non, les ateliers sont ouverts à toutes et tous. L'adhésion à l'association est cependant recommandée pour soutenir le projet et bénéficier de tarifs préférentiels sur certaines activités.",
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
        <h2>Des ateliers créatifs et culturels à Lille, sur péniche</h2>
        <p>Le Bus Magique, péniche associative amarrée quai de l'Esplanade à Lille, accueille chaque mois une programmation d'ateliers créatifs, artistiques et réflexifs : écriture créative, linogravure, café philo, broderie, punch needle, sonothérapie, bien-être. Des moments conviviaux animés par des intervenant·es locaux, dans une ambiance bienveillante.</p>
      </div>

    </section>

    <section class="event-recurrent">

      <div class="event-recurrent__grid">

        <article class="event-card event-card--red">
          <h2>Quels ateliers au Bus Magique ?</h2>
          <p>Notre programmation tourne autour de plusieurs familles d'ateliers :</p>
          <ul>
            <li><strong>Ateliers d'écriture créative</strong> : chaque mois, une animatrice propose des jeux d'écriture thématiques, à partager à voix haute ou non</li>
            <li><strong>Café philo</strong> : une discussion guidée autour d'une question philosophique, ouverte à tous niveaux de réflexion</li>
            <li><strong>Ateliers manuels</strong> : linogravure, broderie palestinienne, punch needle, atelier vinyle</li>
            <li><strong>Bien-être</strong> : sonothérapie, fleurs de Bach, olfactothérapie, réflexologie plantaire</li>
            <li><strong>Engagement</strong> : texticologie (mode responsable), ateliers citoyens</li>
          </ul>
        </article>

        <article class="event-card event-card--green">
          <h2>Comment s'inscrire ?</h2>
          <ul>
            <li><strong>Fréquence</strong> : plusieurs ateliers par mois (voir <a href="/programmation/">programmation</a>)</li>
            <li><strong>Durée</strong> : 1h30 à 3h selon l'atelier</li>
            <li><strong>Où</strong> : péniche Le Bus Magique, quai de l'Esplanade, 59800 Lille</li>
            <li><strong>Places</strong> : limitées (8 à 15 personnes)</li>
            <li><strong>Tarif</strong> : 10 à 35 € selon atelier et matériel, certains à prix libre</li>
            <li><strong>Inscription</strong> : via la fiche événement sur notre programmation ou par <a href="/contact/">mail</a></li>
          </ul>
        </article>

        <article class="event-card event-card--yellow">
          <h2>Dans quel esprit ?</h2>
          <p>Nos ateliers sont pensés comme des temps de pause, de création et de rencontre. Pas besoin d'être artiste ou expert·e : l'accueil est bienveillant, les animateur·rices sont pédagogues, et chacun·e vient comme il ou elle est.</p>
          <p>Matériel fourni sur place, bar ouvert pour un café ou un verre, ambiance détendue au bord de la Deûle : la recette parfaite pour décrocher du quotidien et rencontrer d'autres Lillois·es.</p>
        </article>

        <article class="event-card event-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche culturelle associative lilloise, amarrée sur la Deûle près de la Citadelle. Un tiers-lieu convivial qui allie <a href="/restauration/">restauration</a>, <a href="/programmation/">événements culturels</a>, <a href="/coworking/">coworking</a> et <a href="/location/">privatisation</a>.</p>
          <p>Porté par une association loi 1901, le projet repose sur l'engagement de bénévoles et adhérent·es. <a href="/monter-a-bord/">Rejoindre l'aventure</a>.</p>
        </article>

      </div>

      <div class="event-recurrent__faq">
        <h2>Questions fréquentes sur les ateliers</h2>

        <details class="event-faq-item">
          <summary><h3>Quels ateliers sont proposés au Bus Magique ?</h3></summary>
          <p>Écriture créative, linogravure, café philo, broderie palestinienne, punch needle, fleurs de Bach, texticologie, sonothérapie : les ateliers varient chaque mois. Retrouvez le détail sur notre <a href="/programmation/">programmation mensuelle</a>.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Faut-il s'inscrire à l'avance ?</h3></summary>
          <p>Oui, les ateliers ont des places limitées (généralement 8 à 15 personnes selon l'activité). L'inscription se fait via la page de l'événement sur notre programmation ou par mail via <a href="/contact/">le formulaire de contact</a>.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Combien coûte un atelier ?</h3></summary>
          <p>Les tarifs varient selon l'atelier : généralement entre 10 et 35 € selon la durée et le matériel fourni. Certains ateliers (café philo notamment) sont à prix libre ou gratuits. Le détail est précisé sur chaque fiche événement.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Faut-il amener du matériel ?</h3></summary>
          <p>Non, le matériel est fourni par les intervenant·es. Si une précision spécifique est nécessaire (tenue confortable, carnet personnel), elle vous sera communiquée au moment de l'inscription.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Faut-il être adhérent·e pour participer ?</h3></summary>
          <p>Non, les ateliers sont ouverts à toutes et tous, adhérent·es ou non. L'adhésion à l'association est cependant recommandée pour soutenir le projet et bénéficier de tarifs préférentiels sur certaines activités. <a href="/monter-a-bord/">En savoir plus sur l'adhésion</a>.</p>
        </details>
      </div>

      <div class="event-recurrent__cta">
        <h2>Prochains ateliers à Lille</h2>
        <p>Retrouvez les dates et inscriptions sur notre <a href="/programmation/">agenda</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a>.</p>
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
