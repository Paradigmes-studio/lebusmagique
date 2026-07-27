<?php

/**
 * Template Name: Événement - Scène ouverte Lille
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    $page_url = get_permalink();
    $thumbnail_id = get_post_thumbnail_id();
    $event_image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

    $mkwvs_next = mkwvs_upcoming_events(['keywords' => ['scène ouverte', 'scene ouverte', 'poésive', 'poesive', 'slam']], 3);
    $event_dates = mkwvs_schema_event_occurrence($mkwvs_next);

    $event_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => 'Scène ouverte au Bus Magique à Lille',
        'description' => "Scènes ouvertes mensuelles à Lille : drag show, poésie, slam, musique. Péniche Le Bus Magique, avenue Cuvier, espace bienveillant, entrée libre.",
        'url' => $page_url,
        'image' => $event_image ?: mkwvs_og_get_image_url(),
        'startDate' => $event_dates['startDate'] ?? null,
        'endDate' => $event_dates['endDate'] ?? null,
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
                'name' => "Quels types de performances peut-on proposer ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Drag, poésie, slam, musique, lecture : toutes les formes d'expression sont les bienvenues sur nos scènes ouvertes, des premiers pas sur scène aux artistes confirmé·es. Chaque scène est un moment unique, bienveillant et ouvert à toustes.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il faire l'atelier pour participer à la scène ouverte poésie ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Pas du tout. Pour Les Poésives, vous pouvez débarquer directement à 20h30 pour la scène ouverte sans avoir fait l'atelier d'écriture. Les deux sont indépendants : faites ce qui vous correspond.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Faut-il avoir écrit ou préparé quelque chose pour venir ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Pas du tout. Vous pouvez venir juste pour écouter et passer un moment doux. Si vous voulez monter sur scène, vous partagez un texte que vous avez écrit ou un extrait que vous aimez. L'important, c'est de vibrer ensemble.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien coûte l'entrée à la scène ouverte ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "L'entrée est à prix libre ou gratuite selon les soirées. Le Bus Magique étant un café associatif, une adhésion à prix libre valable un an vous est proposée à la première visite, à partir de 1 €, à régler au bar.",
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
    <?php if ($event_schema !== null) : ?>
    <script type="application/ld+json"><?php echo wp_json_encode($event_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <?php endif; ?>
    <script type="application/ld+json"><?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <div class="bm-event">

      <!-- HERO -->
      <div class="bm-hero">
        <span class="bm-hero__icon">⚓</span>
        <h1>Les scènes ouvertes du Bus Magique, à Lille</h1>
        <p class="bm-hero__desc">
          Sur le pont du Bus Magique, on aime donner la parole, la scène et le micro
          à celles et ceux qui ont envie de créer, partager, performer. Drag, poésie,
          musique… chaque scène ouverte est un moment unique, bienveillant et ouvert à toustes.
        </p>
      </div>

      <!-- BLOC 1 : DRAG SHOW -->
      <div class="bm-block">
        <div class="bm-block__title">
          <span class="bm-block__title-icon">💄</span>
          <div>
            <h2>Drag Show, scène ouverte</h2>
            <p>Présenté par Stargirl</p>
          </div>
        </div>
        <hr class="bm-divider">
      </div>
      <div class="bm-cards">
        <article class="bm-card bm-card--pink">
          <h2>C'est quoi ?</h2>
          <p>Une soirée animée par Stargirl pour mettre en lumière de nouveaux talents drag. À chaque édition, un casting inédit d'artistes vient performer dans un cadre bienveillant : premiers pas sur scène ou confirmé·es, chaque artiste apporte son univers.</p>
          <p>Glamour, extravagance, émotion : chaque show est unique. Vous n'assisterez jamais deux fois au même !</p>
        </article>
        <article class="bm-card bm-card--teal">
          <h2>Où et quand ?</h2>
          <div class="bm-info"><span class="bm-info__label">Quand :</span><span class="bm-info__val">Consultez la <a href="/programmation/">programmation</a> pour la prochaine date</span></div>
          <div class="bm-info"><span class="bm-info__label">Heure :</span><span class="bm-info__val">20h (show majoritairement debout)</span></div>
          <div class="bm-info"><span class="bm-info__label">Où :</span><span class="bm-info__val">Péniche Le Bus Magique, avenue Cuvier, 59800 Lille</span></div>
          <div class="bm-info"><span class="bm-info__label">Accès :</span><span class="bm-info__val">Arrêt de bus Champ de Mars ou métro Rihour</span></div>
          <div class="bm-info"><span class="bm-info__label">Tarif :</span><span class="bm-info__val">Entrée à prix libre : cash, Lydia ou PayPal sur place</span></div>
          <div class="bm-box bm-box--warning">
            <strong>⚠️ Accessibilité</strong>
            Show majoritairement debout : des chaises sont disponibles, demandez-en une au bar. La péniche n'est pas totalement accessible (un pont pour la terrasse, un escalier pour la salle, le bar et les toilettes).
          </div>
        </article>
      </div>

      <!-- BLOC 2 : LES POÉSIVES -->
      <div class="bm-block">
        <div class="bm-block__title">
          <span class="bm-block__title-icon">📝</span>
          <div>
            <h2>Les Poésives, scène ouverte poésie</h2>
            <p>Une fois par mois à bord</p>
          </div>
        </div>
        <hr class="bm-divider">
      </div>
      <div class="bm-cards">
        <article class="bm-card bm-card--purple">
          <h2>Programme de la soirée</h2>
          <div class="bm-timeline">
            <div class="bm-timeline__item">
              <span class="bm-timeline__time">19h00</span>
              <div class="bm-timeline__content">
                <strong>Atelier d'écriture</strong>
                <p>Un espace pour se mettre en mots avant la scène. Ouvert à toustes, débutant·e ou plume aguerrie.</p>
              </div>
            </div>
            <div class="bm-timeline__item">
              <span class="bm-timeline__time">20h30</span>
              <div class="bm-timeline__content">
                <strong>Scène ouverte ✨</strong>
                <p>Lis un texte personnel ou un extrait que tu veux partager. 5 minutes max par personne, inscription sur place.</p>
              </div>
            </div>
          </div>
          <p style="font-size:0.82rem;color:#888;margin-top:0.5rem;">On est pleinement responsable de la parole qu'on porte sur scène.</p>
        </article>
        <article class="bm-card bm-card--teal">
          <h2>Où et quand ?</h2>
          <div class="bm-info"><span class="bm-info__label">Quand :</span><span class="bm-info__val">Une fois par mois : consultez la <a href="/programmation/">programmation</a></span></div>
          <div class="bm-info"><span class="bm-info__label">Atelier :</span><span class="bm-info__val">19h à 20h30</span></div>
          <div class="bm-info"><span class="bm-info__label">Scène :</span><span class="bm-info__val">À partir de 20h30</span></div>
          <div class="bm-info"><span class="bm-info__label">Où :</span><span class="bm-info__val">Péniche Le Bus Magique, avenue Cuvier, 59800 Lille</span></div>
          <div class="bm-info"><span class="bm-info__label">Tarif :</span><span class="bm-info__val">Entrée gratuite ! Adhésion à prix libre à partir de 1&nbsp;€ (réglée au bar)</span></div>
          <div class="bm-info"><span class="bm-info__label">Inscription :</span><span class="bm-info__val">Sur place le soir même (5 min max par personne)</span></div>
        </article>
      </div>

      <!-- INFOS COMMUNES -->
      <div class="bm-cards">
        <article class="bm-card bm-card--yellow">
          <h2>Manger et boire à bord</h2>
          <p>Notre <a href="/restauration/">bar</a> est ouvert toute la soirée lors de chaque scène ouverte : bières locales, vins natures, cocktails maison, softs bio. Restauration sur place possible également.</p>
          <p>Arrivez un peu avant le début pour vous installer tranquillement et commander un verre avant que ça commence.</p>
        </article>
        <article class="bm-card bm-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche associative amarrée à l'entrée de la Citadelle de Lille depuis 2019. Un tiers-lieu chaleureux, intergénérationnel et participatif, où se croisent <a href="/restauration/">restauration</a>, <a href="/programmation/">programmation culturelle</a>, <a href="/coworking/">coworking</a> et <a href="/location/">événements privés</a>.</p>
          <p>Ici, tout le monde a sa place à bord : la péniche est un endroit sauf et heureux, où aucune discrimination n'est admise. On embarque ensemble, dans le respect de chacun·e. <a href="/monter-a-bord/">Découvrir le projet et adhérer</a>.</p>
          <span class="bm-badge">⚓ Endroit sauf &amp; heureux : tolérance zéro pour toute discrimination</span>
        </article>
      </div>

      <!-- FAQ -->
      <div class="bm-faq">
        <h2>Questions fréquentes sur les scènes ouvertes</h2>

        <details class="bm-faq__item">
          <summary>Quels types de performances peut-on proposer ?</summary>
          <div class="bm-faq__answer">Drag, poésie, slam, musique, lecture : toutes les formes d'expression sont les bienvenues, des premiers pas sur scène aux artistes confirmé·es. On est pleinement responsable de la parole qu'on porte sur scène, dans le respect de toustes.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Faut-il faire l'atelier pour participer à la scène ouverte poésie ?</summary>
          <div class="bm-faq__answer">Pas du tout ! Pour Les Poésives, vous pouvez débarquer directement à 20h30 pour la scène ouverte sans avoir fait l'atelier d'écriture. Les deux sont indépendants, faites ce qui vous correspond.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Faut-il avoir écrit ou préparé quelque chose pour venir ?</summary>
          <div class="bm-faq__answer">Pas du tout ! Vous pouvez venir juste pour écouter et passer un moment doux. Si vous voulez lire, vous partagez un texte que vous avez écrit ou un extrait que vous aimez. L'important, c'est de vibrer ensemble.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Combien coûte l'entrée ?</summary>
          <div class="bm-faq__answer">Selon les soirées, l'entrée est à prix libre (Drag Show) ou gratuite (Les Poésives). Le Bus Magique étant un café associatif, une adhésion à prix libre valable un an vous est proposée à la première visite, à partir de 1&nbsp;€, à régler au bar.</div>
        </details>

        <details class="bm-faq__item">
          <summary>Où se trouve la péniche Le Bus Magique ?</summary>
          <div class="bm-faq__answer">On est amarrés <strong>avenue Cuvier, 59800 Lille</strong>, à l'entrée de la Citadelle, le long de la Deûle. Accès par l'arrêt de bus Champ de Mars ou le métro Rihour.</div>
        </details>
      </div>

      <!-- CTA -->
      <div class="bm-cta">
        <h2>Prochaines scènes ouvertes à Lille</h2>
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
        <p>Retrouvez les dates à venir sur notre <a href="/programmation/">agenda</a>, ou suivez-nous sur <a href="https://www.instagram.com/le_bus_magique_lille" target="_blank" rel="noopener">Instagram</a> et <a href="https://www.facebook.com/lebusmagiquelille" target="_blank" rel="noopener">Facebook</a>.</p>
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
