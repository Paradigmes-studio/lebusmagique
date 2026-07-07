<?php global $a_months; ?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php $post_id = get_the_ID(); ?>
    <section class="section-landing-standard">

      <?php include(locate_template('template-part/blocks/page-head.php')); ?>

    </section>

    <section class="o-wrapper o-page-content">

      <?php echo apply_filters('the_content', get_the_content()); ?>

      <?php
      // Maillage interne : lien vers la page evergreen du format correspondant.
      $mkwvs_fmt = function_exists('mkwvs_fb_event_format_link') ? mkwvs_fb_event_format_link($post_id) : null;
      if ($mkwvs_fmt) : ?>
        <p class="fiche-format-link" style="text-align:center;margin-top:2rem;">
          <a href="<?php echo esc_url($mkwvs_fmt['url']); ?>" class="cta"><?php echo esc_html($mkwvs_fmt['label']); ?></a>
        </p>
      <?php endif; ?>

    </section>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();