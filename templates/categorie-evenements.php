<?php

/**
 * Template Name: Catégorie d'événements
 *
 * Page enfant de /programmation/ présentant une thématique d'événements.
 * La catégorie est déduite du slug de la page (ex. 'engagee-inclusive').
 */
?>
<?php get_header(); ?>

<?php
$mkwvs_cat = mkwvs_event_category_by_page(get_post_field('post_name', get_queried_object_id()));
?>

<?php if (!$mkwvs_cat) : ?>
  <section class="section-landing-standard">
    <div class="text-yellow-background bottom priv-intro">
      <h1><?php the_title(); ?></h1>
      <p>Catégorie introuvable. <a href="/programmation/">Retour à la programmation</a>.</p>
    </div>
  </section>
<?php else : ?>

  <div class="bm-cat" style="--bm-cat-color: <?php echo esc_attr($mkwvs_cat['color']); ?>;">

    <!-- BANDEAU -->
    <div class="bm-cat-hero">
      <span class="bm-cat-hero__pill"><?php echo esc_html('Notre prog\' ' . preg_replace('/^prog\'?\s+/i', '', $mkwvs_cat['label'])); ?></span>
    </div>

    <div class="bm-cat-body">

      <p class="bm-cat-intro"><?php echo esc_html($mkwvs_cat['intro']); ?></p>

      <?php if (!empty($mkwvs_cat['links'])) : ?>
        <div class="bm-cat-links">
          <?php foreach ($mkwvs_cat['links'] as $link) : ?>
            <a class="bm-cat-link" href="<?php echo esc_url(home_url($link[1])); ?>">
              <span class="bm-cat-link__star">★</span>
              <span>
                <strong><?php echo esc_html($link[0]); ?></strong>
                <span class="bm-cat-link__arrow">→ En savoir plus</span>
              </span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php
      $mkwvs_events = mkwvs_event_category_query($mkwvs_cat['term'], 12);
      if ($mkwvs_events->have_posts()) : ?>
        <h2 class="bm-cat-subtitle">Les événements à venir</h2>
        <div class="bm-cat-grid">
          <?php while ($mkwvs_events->have_posts()) : $mkwvs_events->the_post(); ?>
            <a class="bm-cat-card" href="<?php the_permalink(); ?>">
              <span class="bm-cat-card__media">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('news-thumb', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title())]); ?>
                <?php endif; ?>
              </span>
              <span class="bm-cat-card__body">
                <span class="bm-cat-card__star">★</span>
                <span>
                  <strong class="bm-cat-card__title"><?php the_title(); ?></strong>
                  <span class="bm-cat-card__arrow">→ Voir l'événement</span>
                </span>
                <span class="bm-cat-card__cta">Découvrir ici !</span>
              </span>
            </a>
          <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="bm-cat-empty">Aucun événement programmé pour le moment dans cette catégorie. Consultez <a href="/programmation/">toute la programmation</a>.</p>
      <?php endif; ?>

      <p class="bm-cat-back"><a href="/programmation/">← Retour à toute la programmation</a></p>

    </div>
  </div>

<?php endif; ?>

<?php get_footer();
