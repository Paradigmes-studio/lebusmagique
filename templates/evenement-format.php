<?php

/**
 * Template Name: Événement - Format (générique)
 *
 * Page evergreen de format d'événement, pilotée par mkwvs_event_formats().
 * Le format est déduit du slug de la page (ex. 'cafe-philo-lille').
 */
?>
<?php get_header(); ?>

<?php
$mkwvs_slug = get_post_field('post_name', get_queried_object_id());
$mkwvs_formats = function_exists('mkwvs_event_formats') ? mkwvs_event_formats() : [];
$f = $mkwvs_formats[$mkwvs_slug] ?? null;
?>

<?php if (!$f) : ?>
  <section class="section-landing-standard">
    <div class="text-yellow-background bottom priv-intro">
      <h1><?php the_title(); ?></h1>
      <p>Format introuvable. <a href="/programmation/">Retour à la programmation</a>.</p>
    </div>
  </section>
<?php else : ?>

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php
    $page_url = get_permalink();
    $thumbnail_id = get_post_thumbnail_id();
    $event_image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';

    $event_schema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $f['schema_name'],
        'description' => $f['schema_desc'],
        'url' => $page_url,
        'image' => $event_image ?: null,
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventSchedule' => [
            '@type' => 'Schedule',
            'repeatFrequency' => 'P1M',
            'startTime' => $f['start'],
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
    ]);

    $faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(static function ($qa) {
            return [
                '@type' => 'Question',
                'name' => $qa[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => wp_strip_all_tags($qa[1])],
            ];
        }, $f['faq']),
    ];
    ?>
    <script type="application/ld+json"><?php echo wp_json_encode($event_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <script type="application/ld+json"><?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <div class="bm-event">

      <div class="bm-hero">
        <span class="bm-hero__icon"><?php echo $f['icon']; ?></span>
        <h1><?php echo esc_html($f['h1']); ?></h1>
        <p class="bm-hero__desc"><?php echo esc_html($f['hero']); ?></p>
      </div>

      <div class="bm-cards">

        <article class="bm-card bm-card--orange">
          <h2>Comment ça se passe ?</h2>
          <?php foreach ($f['how'] as $para) : ?>
            <p><?php echo esc_html($para); ?></p>
          <?php endforeach; ?>
        </article>

        <article class="bm-card bm-card--teal">
          <h2>Où et quand ?</h2>
          <?php foreach ($f['infos'] as $i => $row) : ?>
            <div class="bm-info"><span class="bm-info__label"><?php echo esc_html($row[0]); ?> :</span><span class="bm-info__val"><?php echo wp_kses_post($row[1]); ?></span></div>
            <?php if ($i === 1) : ?>
              <div class="bm-info"><span class="bm-info__label">Où :</span><span class="bm-info__val">Péniche Le Bus Magique, avenue Cuvier, 59800 Lille</span></div>
              <div class="bm-info"><span class="bm-info__label">Accès :</span><span class="bm-info__val">À deux pas de la Citadelle : arrêt de bus Champ de Mars ou métro Rihour</span></div>
            <?php endif; ?>
          <?php endforeach; ?>
        </article>

        <article class="bm-card bm-card--yellow">
          <h2>Manger et boire à bord</h2>
          <p>Notre <a href="/restauration/">bar</a> est ouvert toute la soirée : bières locales, vins natures, cocktails maison, softs bio… et de quoi grignoter sur place. Arrivez un peu avant le début pour vous installer tranquillement.</p>
        </article>

        <article class="bm-card bm-card--blue">
          <h2>À propos du lieu</h2>
          <p>Le Bus Magique est une péniche associative amarrée à l'entrée de la Citadelle de Lille depuis 2019. Un tiers-lieu chaleureux, intergénérationnel et participatif, où se croisent <a href="/restauration/">restauration</a>, <a href="/programmation/">programmation culturelle</a>, <a href="/coworking/">coworking</a> et <a href="/location/">événements privés</a>.</p>
          <p>Ici, tout le monde a sa place à bord : la péniche est un endroit sauf et heureux, où aucune discrimination n'est admise. <a href="/monter-a-bord/">Découvrir le projet et adhérer</a>.</p>
          <span class="bm-badge">⚓ Endroit sauf &amp; heureux : tolérance zéro pour toute discrimination</span>
        </article>

      </div>

      <div class="bm-faq">
        <h2>Questions fréquentes</h2>
        <?php foreach ($f['faq'] as $qa) : ?>
          <details class="bm-faq__item">
            <summary><?php echo esc_html($qa[0]); ?></summary>
            <div class="bm-faq__answer"><?php echo wp_kses_post($qa[1]); ?></div>
          </details>
        <?php endforeach; ?>
      </div>

      <div class="bm-cta">
        <h2>Prochaines dates à Lille</h2>
        <?php $mkwvs_next = mkwvs_upcoming_events(['keywords' => $f['keywords']], 3); ?>
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
      </div>

    </div>

  <?php endwhile; endif; ?>

<?php endif; ?>

<?php get_footer();
