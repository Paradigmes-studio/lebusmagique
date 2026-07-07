<?php

/**
 * Template Name: Événement - Blind test Lille
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    // ============================================================
    // Données structurées : Event récurrent + FAQPage
    // https://schema.org/Event
    // ============================================================
    $page_url = get_permalink();
    $thumbnail_id = get_post_thumbnail_id();
    $event_image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

    $event_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => 'Blind test au Bus Magique à Lille',
        'description' => 'Blind test musical mensuel sur une péniche à Lille, animé par Tof. En équipage, à 19h30, entrée gratuite, réservation conseillée.',
        'url' => $page_url,
        'image' => $event_image ?: null,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventSchedule' => [
            '@type' => 'Schedule',
            'repeatFrequency' => 'P1M',
            'startTime' => '19:30',
            'duration' => 'PT2H30M',
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
            'url' => 'https://uniiti.com/shop/le-bus-magique',
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
                    'text' => "La réservation est recommandée : la péniche a un nombre de places limité et les tables partent vite. Réservez en ligne sur uniiti.com/shop/le-bus-magique, surtout si vous venez en grand équipage.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien coûte l'entrée au blind test ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "L'entrée est gratuite. Le Bus Magique étant un café associatif, une adhésion à prix libre valable un an vous est proposée à la première visite, à partir de 1 €, à régler au bar. Ensuite, libre à vous de consommer à bord.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il être calé·e en musique pour jouer ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Pas du tout. Tof sélectionne des extraits accessibles à tous : tubes connus, variété française, génériques de films et séries, pop internationale. L'objectif, c'est de passer une bonne soirée ensemble.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Peut-on venir seul·e au blind test ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Bien sûr. Venez seul·e et rejoignez un équipage sur place. C'est aussi l'occasion parfaite de faire de nouvelles rencontres.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Où se trouve la péniche Le Bus Magique ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Le Bus Magique est amarré avenue Cuvier, 59800 Lille, à l'entrée de la Citadelle, le long de la Deûle. Accès par l'arrêt de bus Champ de Mars ou le métro Rihour.",
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
        <span class="bm-hero__icon">⚓</span>
        <h1>Le blind test du Bus Magique : le rendez-vous culture musicale mensuel</h1>
        <p class="bm-hero__desc">
          Eh oh, moussaillons ! C'est LE rendez-vous culture musicale animé par Tof.
          Appelez vos complices, formez votre équipage et réservez vite une table pour
          notre blind test mensuel à bord. Tubes, génériques, variété, pop… les oreilles
          sont à la fête !
        </p>
      </div>

      <!-- 4 CARTES -->
      <div class="bm-cards">

        <article class="bm-card bm-card--orange">
          <h2>Comment ça se passe ?</h2>
          <p>Tof, notre animateur du bord, vous fait naviguer à travers des dizaines d'extraits musicaux soigneusement sélectionnés. En équipage, vous donnez vos réponses et tentez de reconnaître titres, artistes et origines des morceaux avant les autres tablées.</p>
          <p>C'est convivial, accessible à tous et ça brasse large : tubes rétro, hits pop, variété française, génériques cultes… Pas besoin d'être un·e mélomane chevronné·e pour participer et bien s'amuser !</p>
        </article>

        <article class="bm-card bm-card--teal">
          <h2>Où et quand ?</h2>
          <div class="bm-info"><span class="bm-info__label">Quand :</span><span class="bm-info__val">Une fois par mois, consultez la <a href="/programmation/">programmation</a> pour la prochaine date</span></div>
          <div class="bm-info"><span class="bm-info__label">Heure :</span><span class="bm-info__val">19h30</span></div>
          <div class="bm-info"><span class="bm-info__label">Où :</span><span class="bm-info__val">Péniche Le Bus Magique, avenue Cuvier, 59800 Lille</span></div>
          <div class="bm-info"><span class="bm-info__label">Accès :</span><span class="bm-info__val">À deux pas de la Citadelle : arrêt de bus Champ de Mars ou métro Rihour</span></div>
          <div class="bm-info"><span class="bm-info__label">Tarif :</span><span class="bm-info__val">Entrée gratuite, adhésion à prix libre à partir de 1&nbsp;€ (réglée au bar)</span></div>
          <div class="bm-info"><span class="bm-info__label">Réservation :</span><span class="bm-info__val">Recommandée : <a href="https://uniiti.com/shop/le-bus-magique" target="_blank" rel="noopener">réservez votre table ici</a></span></div>
        </article>

        <article class="bm-card bm-card--yellow">
          <h2>Manger et boire à bord</h2>
          <p>Notre <a href="/restauration/">bar</a> reste ouvert toute la soirée : bières locales, vins natures, cocktails maison, softs bio… de quoi trinquer entre moussaillons ! Et pour les petits creux, on propose aussi de la restauration sur place.</p>
          <p>Arrivez un peu avant 19h30 pour vous installer tranquillement, commander un verre et faire connaissance avec l'équipage avant le coup d'envoi.</p>
        </article>

        <article class="bm-card bm-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche associative amarrée à l'entrée de la Citadelle de Lille depuis 2019. Un tiers-lieu chaleureux, intergénérationnel et participatif, où se croisent <a href="/restauration/">restauration</a>, <a href="/programmation/">programmation culturelle</a>, <a href="/coworking/">coworking</a> et <a href="/location/">événements privés</a>.</p>
          <p>Ici, tout le monde a sa place à bord : la péniche est un endroit sauf et heureux, où aucune discrimination n'est admise. <a href="/monter-a-bord/">En savoir plus sur la péniche et adhérer</a>.</p>
          <span class="bm-badge">⚓ Endroit sauf &amp; heureux : tolérance zéro pour toute discrimination</span>
        </article>

      </div>

      <!-- FAQ -->
      <div class="bm-faq">
        <h2>Questions fréquentes sur le blind test</h2>

        <details class="bm-faq__item">
          <summary>Faut-il réserver pour participer ?</summary>
          <div class="bm-faq__answer">La réservation est recommandée car la péniche a un nombre de places limité et les tables partent vite ! Réservez directement en ligne via <a href="https://uniiti.com/shop/le-bus-magique" target="_blank" rel="noopener">notre page de réservation</a>. Si vous êtes un grand équipage, n'attendez pas trop.</div>
        </details>

        <details class="bm-faq__item">
          <summary>C'est vraiment gratuit ?</summary>
          <div class="bm-faq__answer">Eh oui, moussaillon ! L'entrée est gratuite. Le Bus Magique est un café associatif, donc on vous proposera une adhésion à prix libre valable un an à votre première visite, à partir de 1&nbsp;€, à régler directement au bar. Ensuite, libre à vous de consommer à bord.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Faut-il être calé·e en musique ?</summary>
          <div class="bm-faq__answer">Pas du tout ! Tof sélectionne des extraits accessibles à tous les équipages : tubes connus, variété française, génériques de films et séries, pop internationale… L'objectif, c'est de passer une bonne soirée ensemble, pas de gagner un Grammy Award.</div>
        </details>

        <details class="bm-faq__item">
          <summary>On peut venir seul·e ?</summary>
          <div class="bm-faq__answer">Bien sûr ! Venez seul·e et rejoignez un équipage sur place. L'équipage du Bus Magique veille à ce que tout le monde trouve son bord. C'est aussi l'occasion parfaite de faire de nouvelles rencontres.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Où se trouve la péniche Le Bus Magique ?</summary>
          <div class="bm-faq__answer">On est amarrés <strong>avenue Cuvier, 59800 Lille</strong>, à l'entrée de la Citadelle, le long de la Deûle. Accès par l'arrêt de bus Champ de Mars ou le métro Rihour. Repérez la péniche, les lumières et les rires, vous ne pouvez pas nous rater !</div>
        </details>
      </div>

      <!-- CTA -->
      <div class="bm-cta">
        <h2>Prochaines dates de blind test à Lille</h2>
        <?php $mkwvs_next = mkwvs_upcoming_events(['keywords' => ['blind test', 'blind-test', 'blindtest']], 3); ?>
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
        <p>Retrouvez les dates à venir sur notre <a href="/programmation/">agenda de programmation</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a> pour ne rien manquer.</p>
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
