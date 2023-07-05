<?php global $a_months; ?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php $post_id = get_the_ID(); ?>
    <section class="o-wrapper o-landing">
      <?php $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'header-thumb'); ?>
      <?php if (false !== $post_thumbnail) : ?>
        <figure>
          <div class="o-landing__decoration o-landing__decoration--artdeco"><?php locate_template( 'img/artdeco-2.svg', true, false ); ?></div>
          <img src="<?php echo $post_thumbnail[0] ?>" alt="<?php echo get_the_title(); ?>"/>
        </figure>
      <?php elseif (false == $post_thumbnail) : ?>
        <figure class="o-landing--no-thumbnail">
          <?php //locate_template( 'img/secteurs.svg', true, false ); ?>
        </figure>
      <?php endif; ?>
      <div class="o-landing__content">
        <?php /* ?>
        <span class="page__date">Publié le <time datetime="<?php echo  get_the_date('Y-m-d', $post_id); ?>"><?php echo  get_the_date('d', $post_id); ?> <?php echo  $a_months[get_the_date('m', $post_id)]; ?> <?php echo  get_the_date('Y', $post_id); ?></time></span>
        <?php */ ?>
        <?php
        $content = get_the_content();
        if (preg_match('#<h1>(.*)</h1>#i', $content) || preg_match('#<!-- wp:acf/header-project#i', $content) )
        {
          //titre h1 dans contenu
        } else {
          ?>
          <h1><?php echo get_the_title(); ?></h1>
          <?php
        }
        ?>
        <?php if(has_excerpt()) { ?>
          <p><?php echo get_the_excerpt(); ?></p>
        <?php } ?>
        <div class="o-landing__decoration o-landing__decoration--demicercles"><?php locate_template( 'img/demicercles.svg', true, false ); ?></div>
      </div>
    </section>

    <section class="o-wrapper o-page-content">

      <?php echo apply_filters('the_content', get_the_content()); ?>

    </section>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();