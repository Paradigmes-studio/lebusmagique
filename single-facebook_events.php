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

    </section>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();