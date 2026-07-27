<?php

/**
 * Template Name: Événement - Jam session Lille
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    $page_url = get_permalink();
    $thumbnail_id = get_post_thumbnail_id();
    $event_image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

    $mkwvs_next = mkwvs_upcoming_events(['keywords' => ['jam']], 3);
    $event_dates = mkwvs_schema_event_occurrence($mkwvs_next);

    $event_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => 'Jam session au Bus Magique à Lille',
        'description' => "Jam session ouverte aux musicien·nes de tous niveaux à Lille. Scène libre sur péniche dès 21h, entrée gratuite, bar ouvert. Venez jouer ou écouter.",
        'url' => $page_url,
        'image' => $event_image ?: mkwvs_og_get_image_url(),
        'startDate' => $event_dates['startDate'] ?? null,
        'endDate' => $event_dates['endDate'] ?? null,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventSchedule' => [
            '@type' => 'Schedule',
            'repeatFrequency' => 'P1M',
            'startTime' => '21:00',
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
        'performer' => [
            '@type' => 'PerformingGroup',
            'name' => 'Le Bus Magique',
        ],
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'EUR',
            'availability' => 'https://schema.org/InStock',
            'validFrom' => get_the_date('c'),
            'url' => $page_url,
        ],
    ];
    $event_schema = empty($event_dates) ? null : array_filter($event_schema);

    $faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => "Faut-il être musicien·ne pour venir ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Pas du tout. La soirée est ouverte à toustes : musicien·ne chevronné·e, débutant·e curieux·se ou simple amateur·rice de bonne musique. À 21h la jam est ouverte à tous les niveaux, à 22h place à l'impro collective. Et si vous voulez juste écouter, vous êtes les bienvenu·es aussi.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quels instruments sont disponibles sur place ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "On met à disposition une guitare électrique, une basse, un clavier, une batterie et 2 micros chant. Vous pouvez aussi monter à bord avec vos propres instruments.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien coûte l'entrée à la jam session ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "L'événement est gratuit. Comme nous sommes un café associatif, on vous proposera une adhésion à prix libre valable un an à votre première visite, à partir de 1 €, à régler au bar.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il réserver pour la jam session ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Non, pas de réservation nécessaire : débarquez directement à bord. Si vous venez en grand équipage, arrivez un peu tôt pour trouver de bonnes places.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Peut-on manger sur place ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Bien sûr. Notre bar et notre cuisine sont ouverts toute la soirée : bières locales, vins natures, cocktails maison, softs bio et de quoi grignoter jusqu'au dernier accord.",
                ],
            ],
        ],
    ];
    ?>
    <?php if ($event_schema !== null) : ?>
    <script type="application/ld+json"><?php echo wp_json_encode($event_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <?php endif; ?>
    <script type="application/ld+json"><?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <div class="bm-event">

      <!-- HERO -->
      <div class="bm-hero">
        <span class="bm-hero__icon">🎸</span>
        <h1>Jam session au Bus Magique : montez à bord, on improvise !</h1>
        <p class="bm-hero__desc">
          Musicien·ne confirmé·e ou total·e débutant·e, peu importe vos influences :
          ici tout le monde a sa place sur le pont. Et si vous voulez juste écouter,
          nos douceurs et la Deûle s'en chargent.
        </p>
      </div>

      <!-- 4 CARTES -->
      <div class="bm-cards">

        <article class="bm-card bm-card--orange">
          <h2>Programme de la soirée</h2>
          <div class="bm-timeline">
            <div class="bm-timeline__item">
              <span class="bm-timeline__time">21h00</span>
              <div class="bm-timeline__content">
                <strong>Jam ouverte à toustes</strong>
                <p>Quel que soit votre niveau ou vos influences, l'occasion idéale de laisser libre cours à vos envies d'impros ou de reprises. Débutant·e ou confirmé·e, tout le monde monte sur le pont !</p>
              </div>
            </div>
            <div class="bm-timeline__item">
              <span class="bm-timeline__time">22h00</span>
              <div class="bm-timeline__content">
                <strong>Jam full impro ✨</strong>
                <p>Le moment phare de la soirée : on improvise ensemble, un moment unique et hors du temps. Laissez-vous porter par l'énergie collective et la magie de la création.</p>
              </div>
            </div>
          </div>
        </article>

        <article class="bm-card bm-card--teal">
          <h2>Où et quand ?</h2>
          <div class="bm-info"><span class="bm-info__label">Quand :</span><span class="bm-info__val">Une fois par mois, consultez la <a href="/programmation/">programmation</a> pour la prochaine date</span></div>
          <div class="bm-info"><span class="bm-info__label">Heure :</span><span class="bm-info__val">Dès 21h</span></div>
          <div class="bm-info"><span class="bm-info__label">Où :</span><span class="bm-info__val">Péniche Le Bus Magique, avenue Cuvier, 59800 Lille</span></div>
          <div class="bm-info"><span class="bm-info__label">Accès :</span><span class="bm-info__val">À deux pas de la Citadelle : arrêt de bus Champ de Mars ou métro Rihour</span></div>
          <div class="bm-info"><span class="bm-info__label">Tarif :</span><span class="bm-info__val">Événement gratuit, adhésion à prix libre à partir de 1&nbsp;€ (réglée au bar)</span></div>
          <div class="bm-info"><span class="bm-info__label">Réservation :</span><span class="bm-info__val">Pas nécessaire : débarquez directement à bord !</span></div>
        </article>

        <article class="bm-card bm-card--yellow">
          <h2>Instrus à bord &amp; bons spectateurs bienvenus</h2>
          <p>On met à disposition sur place de quoi jouer direct. Vous pouvez aussi monter avec vos propres instruments, bien sûr !</p>
          <div class="bm-tags">
            <span class="bm-tag">🎸 Guitare électrique</span>
            <span class="bm-tag">🎸 Basse</span>
            <span class="bm-tag">🎹 Clavier</span>
            <span class="bm-tag">🥁 Batterie</span>
            <span class="bm-tag">🎤 2 micros chant</span>
          </div>
          <p>Pas musicien·ne ? Pas de problème, moussaillon ! Installez-vous, profitez de nos douceurs au <a href="/restauration/">bar</a> et laissez-vous bercer par la musique et le doux clapotis de la Deûle.</p>
        </article>

        <article class="bm-card bm-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche associative amarrée à l'entrée de la Citadelle de Lille depuis 2019. Un tiers-lieu chaleureux, intergénérationnel et participatif, où se croisent <a href="/restauration/">restauration</a>, <a href="/programmation/">programmation culturelle</a>, <a href="/coworking/">coworking</a> et <a href="/location/">événements privés</a>. Restauration sur place possible toute la soirée.</p>
          <p>Ici, tout le monde a sa place à bord : la péniche est un endroit sauf et heureux, où aucune discrimination n'est admise. <a href="/monter-a-bord/">Adhérer et soutenir le projet</a>.</p>
          <span class="bm-badge">⚓ Endroit sauf &amp; heureux : tolérance zéro pour toute discrimination</span>
        </article>

      </div>

      <!-- FAQ -->
      <div class="bm-faq">
        <h2>Questions fréquentes sur la jam session</h2>

        <details class="bm-faq__item">
          <summary>Faut-il être musicien·ne pour venir ?</summary>
          <div class="bm-faq__answer">Pas du tout ! La soirée est ouverte à toustes : musicien·ne chevronné·e, débutant·e curieux·se ou simple amateur·rice de bonne musique. À 21h, la jam est ouverte à tous les niveaux. À 22h, place à l'impro collective. Et si vous voulez juste écouter et profiter, vous êtes les bienvenu·es aussi !</div>
        </details>

        <details class="bm-faq__item">
          <summary>Quels instruments sont disponibles sur place ?</summary>
          <div class="bm-faq__answer">On met à disposition : une guitare électrique, une basse, un clavier, une batterie et 2 micros chant. Vous pouvez aussi monter à bord avec vos propres instruments : plus on est de fous, plus on joue !</div>
        </details>

        <details class="bm-faq__item">
          <summary>C'est gratuit ?</summary>
          <div class="bm-faq__answer">Oui, l'événement est gratuit ! Comme nous sommes un café associatif, on vous proposera une adhésion à prix libre valable un an à votre première visite, à partir de 1&nbsp;€, à régler directement au bar. Ensuite, libre à vous de consommer et de profiter de la soirée.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Faut-il réserver ?</summary>
          <div class="bm-faq__answer">Non, pas de réservation nécessaire pour la jam session : débarquez directement à bord ! Si vous venez en grand équipage, arrivez un peu tôt pour trouver de bonnes places.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Peut-on manger sur place ?</summary>
          <div class="bm-faq__answer">Bien sûr ! Notre bar et notre cuisine sont ouverts toute la soirée. Bières locales, vins natures, cocktails maison, softs bio… et de quoi grignoter pour tenir jusqu'au dernier accord.</div>
        </details>
      </div>

      <!-- CTA -->
      <div class="bm-cta">
        <h2>Prochaines jam sessions à Lille</h2>
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
        <p>Retrouvez les dates à venir sur notre <a href="/programmation/">agenda</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a> pour ne rien manquer.</p>
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
