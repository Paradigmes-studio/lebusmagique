<?php
$sub_category = null;
$categories = get_the_terms(get_the_ID(), 'category');
if (sizeof($categories) > 0 ){
    foreach($categories as $category){
        if ($category->parent === 2){
            $sub_category = $category;
        }
    }
}
$post_information_author     = get_post_meta(get_the_ID(), 'post_information_author', true);
?>

  <a class="o-list-blog__link" href="<?php echo get_permalink(); ?>">

      <article class="o-list-blog__post">

          <?php $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'medium'); ?>
          <?php if (false !== $post_thumbnail) : ?>

          <figure class="o-list-blog__thumbnail">
            <img class="o-list-blog__image" src="<?php echo $post_thumbnail[0] ?>" alt="<?php echo get_the_title(); ?>"/>
          </figure>

          <?php endif; ?>

          <div class="o-list-blog__content">
            <h2 class="o-list-blog__title"><?php echo get_the_title(); ?></h2>
            <date class="o-list-blog__date"><?php echo get_the_date('d M Y'); ?></date>
            <p class="o-list-blog__excerpt"><?php echo get_the_excerpt(); ?></p>
            <?php if (!empty($post_information_author)) : ?>
            <span class="o-list-blog__author">Par <span><?php echo get_the_title($post_information_author); ?></span></span>
            <?php endif; ?>
          </div>

      </article>
  </a>
