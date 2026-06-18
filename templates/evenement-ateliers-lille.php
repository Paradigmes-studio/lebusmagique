<?php

/**
 * Template Name: Événement - Ateliers Lille
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
        'name' => 'Ateliers créatifs et culturels au Bus Magique à Lille',
        'description' => "Programmation régulière d'ateliers à Lille : écriture créative, linogravure, café philo, broderie, punch needle, fleurs de Bach. Péniche Le Bus Magique, avenue Cuvier.",
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

    <div class="bm-event">

      <!-- HERO -->
      <div class="bm-hero">
        <span class="bm-hero__icon">🎨</span>
        <h1>Des ateliers créatifs et culturels à Lille, sur péniche</h1>
        <p class="bm-hero__desc">
          Chaque mois, une programmation d'ateliers créatifs, artistiques et réflexifs :
          écriture, linogravure, café philo, broderie, punch needle, sonothérapie, bien-être.
          Des moments conviviaux animés par des intervenant·es locaux, dans une ambiance bienveillante.
        </p>
      </div>

      <!-- 4 CARTES -->
      <div class="bm-cards">

        <article class="bm-card bm-card--orange">
          <h2>Quels ateliers au Bus Magique ?</h2>
          <p>Notre programmation tourne autour de plusieurs familles d'ateliers :</p>
          <ul>
            <li><strong>Écriture créative</strong> : jeux d'écriture thématiques, à partager à voix haute ou non</li>
            <li><strong>Café philo</strong> : une discussion guidée autour d'une question, ouverte à tous niveaux</li>
            <li><strong>Ateliers manuels</strong> : linogravure, broderie palestinienne, punch needle, atelier vinyle</li>
            <li><strong>Bien-être</strong> : sonothérapie, fleurs de Bach, olfactothérapie, réflexologie plantaire</li>
            <li><strong>Engagement</strong> : texticologie (mode responsable), ateliers citoyens</li>
          </ul>
        </article>

        <article class="bm-card bm-card--teal">
          <h2>Comment s'inscrire ?</h2>
          <div class="bm-info"><span class="bm-info__label">Fréquence :</span><span class="bm-info__val">Plusieurs ateliers par mois (voir la <a href="/programmation/">programmation</a>)</span></div>
          <div class="bm-info"><span class="bm-info__label">Durée :</span><span class="bm-info__val">1h30 à 3h selon l'atelier</span></div>
          <div class="bm-info"><span class="bm-info__label">Où :</span><span class="bm-info__val">Péniche Le Bus Magique, avenue Cuvier, 59800 Lille</span></div>
          <div class="bm-info"><span class="bm-info__label">Accès :</span><span class="bm-info__val">À deux pas de la Citadelle : arrêt de bus Champ de Mars ou métro Rihour</span></div>
          <div class="bm-info"><span class="bm-info__label">Places :</span><span class="bm-info__val">Limitées (8 à 15 personnes)</span></div>
          <div class="bm-info"><span class="bm-info__label">Tarif :</span><span class="bm-info__val">10 à 35 € selon l'atelier et le matériel, certains à prix libre</span></div>
          <div class="bm-info"><span class="bm-info__label">Inscription :</span><span class="bm-info__val">Via la fiche événement ou par <a href="/contact/">mail</a></span></div>
        </article>

        <article class="bm-card bm-card--yellow">
          <h2>Dans quel esprit ?</h2>
          <p>Nos ateliers sont pensés comme des temps de pause, de création et de rencontre. Pas besoin d'être artiste ou expert·e : l'accueil est bienveillant, les animateur·rices sont pédagogues, et chacun·e vient comme il ou elle est.</p>
          <p>Matériel fourni sur place, <a href="/restauration/">bar</a> ouvert pour un café ou un verre, ambiance détendue au bord de la Deûle : la recette parfaite pour décrocher du quotidien et rencontrer d'autres Lillois·es.</p>
        </article>

        <article class="bm-card bm-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche culturelle associative lilloise, amarrée sur la Deûle près de la Citadelle. Un tiers-lieu convivial qui allie <a href="/restauration/">restauration</a>, <a href="/programmation/">événements culturels</a>, <a href="/coworking/">coworking</a> et <a href="/location/">privatisation</a>.</p>
          <p>Porté par une association loi 1901, le projet repose sur l'engagement de bénévoles et adhérent·es. La péniche est un endroit sauf et heureux, où aucune discrimination n'est admise. <a href="/monter-a-bord/">Rejoindre l'aventure</a>.</p>
          <span class="bm-badge">⚓ Endroit sauf &amp; heureux : tolérance zéro pour toute discrimination</span>
        </article>

      </div>

      <!-- FAQ -->
      <div class="bm-faq">
        <h2>Questions fréquentes sur les ateliers</h2>

        <details class="bm-faq__item">
          <summary>Quels ateliers sont proposés au Bus Magique ?</summary>
          <div class="bm-faq__answer">Écriture créative, linogravure, café philo, broderie palestinienne, punch needle, fleurs de Bach, texticologie, sonothérapie : les ateliers varient chaque mois. Retrouvez le détail sur notre <a href="/programmation/">programmation mensuelle</a>.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Faut-il s'inscrire à l'avance ?</summary>
          <div class="bm-faq__answer">Oui, les ateliers ont des places limitées (généralement 8 à 15 personnes selon l'activité). L'inscription se fait via la page de l'événement sur notre programmation ou par mail via <a href="/contact/">le formulaire de contact</a>.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Combien coûte un atelier ?</summary>
          <div class="bm-faq__answer">Les tarifs varient selon l'atelier : généralement entre 10 et 35 € selon la durée et le matériel fourni. Certains ateliers (café philo notamment) sont à prix libre ou gratuits. Le détail est précisé sur chaque fiche événement.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Faut-il amener du matériel ?</summary>
          <div class="bm-faq__answer">Non, le matériel est fourni par les intervenant·es. Si une précision spécifique est nécessaire (tenue confortable, carnet personnel), elle vous sera communiquée au moment de l'inscription.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Faut-il être adhérent·e pour participer ?</summary>
          <div class="bm-faq__answer">Non, les ateliers sont ouverts à toutes et tous, adhérent·es ou non. L'adhésion à l'association est cependant recommandée pour soutenir le projet et bénéficier de tarifs préférentiels sur certaines activités. <a href="/monter-a-bord/">En savoir plus sur l'adhésion</a>.</div>
        </details>
      </div>

      <!-- CTA -->
      <div class="bm-cta">
        <h2>Prochains ateliers à Lille</h2>
        <?php $mkwvs_next = mkwvs_upcoming_events(['category' => 'ateliers-artistiques'], 3); ?>
        <?php if ($mkwvs_next) : ?>
          <ul class="bm-next">
            <?php foreach ($mkwvs_next as $mkwvs_ev) : ?>
              <li><a href="<?php echo esc_url(get_permalink($mkwvs_ev)); ?>">
                <span class="bm-next__date"><?php echo esc_html(mkwvs_event_date_label($mkwvs_ev->ID)); ?></span>
                <span class="bm-next__title"><?php echo esc_html(get_the_title($mkwvs_ev)); ?></span>
              </a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <p>Retrouvez les dates et inscriptions sur notre <a href="/programmation/">agenda</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a>.</p>
        <?php
        $home = get_page_by_path('accueil');
        $prog_image = $home ? get_field('programmation_du_mois', $home->ID) : null;
        if (is_array($prog_image) && !empty($prog_image['url'])) : ?>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('programmation'))); ?>" class="bm-cta__prog-link" aria-label="Voir la programmation complète du Bus Magique à Lille">
            <img src="<?php echo esc_url($prog_image['url']); ?>" alt="<?php echo esc_attr($prog_image['alt'] ?: 'Programmation du mois au Bus Magique à Lille'); ?>" loading="lazy">
          </a>
        <?php else : ?>
          <a href="/programmation/" class="cta">Voir la programmation</a>
        <?php endif; ?>
      </div>

    </div>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
