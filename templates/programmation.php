<?php
/**
 * Template Name: Programmation
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <section class="section-landing-standard">

        <?php include(locate_template('template-part/blocks/page-head.php')); ?>

    </section>

    <?php if( have_rows('page_programmation_list')) : ?>
    <section>
      <h2 class="has-text-align-center">Événements à venir</h2>
      <!--<p style="text-align: center;">Notre programmation magique reprendra le 10 juin, en attendant venez boire un verre !</p>
      <a class="cta" href="<?php echo get_permalink(get_page_by_path("restauration")); ?>">Voir la carte</a>-->
      <div class="events">
      <?php //while(have_rows('page_programmation_list')) : the_row(); ?>
        <?php //$title = get_sub_field('page_programmation_item_title'); ?>
        <?php //$iframe = get_sub_field('page_programmation_item_iframe'); ?>
          <?php //echo $iframe; ?>
      <?php //endwhile; ?>
          <?php echo do_shortcode('[facebook_events col="2" posts_per_page="20"]'); ?>
      </div>

    </section>
    <?php endif; ?>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
