<div class="section-landing-top">
  <div class="swiper-container top-image">
    <div class="swiper-wrapper">
  <?php $image_count = 0; ?>
  <?php if (have_rows('page_head_gallery')) : ?>
    <?php while (have_rows('page_head_gallery')) : the_row(); ?>
      <?php $image = get_sub_field('page_head_gallery_item_image'); ?>
      <?php $image_count++; ?>
      <?php $description = get_sub_field('page_head_gallery_item_descriptif'); ?>
      <img class="swiper-slide" src="<?php echo $image['url']; ?>" alt="<?php echo $description; ?>">
    <?php endwhile; ?>
  <?php else: ?>
    <?php $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'header-thumb'); ?>
    <?php if (false !== $post_thumbnail) : ?>
        <img src="<?php echo $post_thumbnail[0] ?>" alt="<?php echo get_the_title(); ?>"/>
    <?php endif; ?>
  <?php endif; ?>

    </div>
  </div>
<?php $upload_dir = wp_get_upload_dir(); ?>
<div class="hublot<?php if($image_count > 1) echo " has-slider"; ?>">
  <?php $color_hublot = get_field('page_head_hublot_color'); ?>
  <?php if (empty($color_hublot)) $color_hublot = 'red'; ?>
  <img src="<?php echo get_stylesheet_directory_uri() .'/images/'.$color_hublot.'-hublot.svg'; ?>">
  <?php $icon_hublot = get_field('page_head_hublot_icon'); ?>
  <?php if (empty($icon_hublot)) $icon_hublot['url'] = $upload_dir['baseurl'].'/2021/05/hublot-icon-programmation.svg'; ?>
  <h1 class="big-title"><img class="icon" src="<?php echo $icon_hublot['url']; ?>"><?php echo get_the_title(); ?></h1>
  <?php if($image_count > 1): ?>
  <div class="o-slider__navigation">
    <button class="o-slider__prev"><?php locate_template( 'images/fleche.svg', true, false ); ?></button>
    <button class="o-slider__next"><?php locate_template( 'images/fleche.svg', true, false ); ?></button>
  </div>
  <?php endif; ?>
</div>

<div class="text-yellow-background top">
  <?php $icon_scroll= get_field('page_head_icon_scroll'); ?>
  <?php if (empty($icon_scroll)) $icon_scroll['url'] = $upload_dir['baseurl'].'/2021/05/ancre.svg'; ?>
  <?php $accroche_scroll = get_field('page_head_accroche_scroll'); ?>
  <img class="icon-top-landing" src="<?php echo $icon_scroll['url']; ?>">
  <p class="to_show"><?php echo $accroche_scroll; ?></p>
</div>
</div>

<div class="text-yellow-background bottom">
  <?php $subtitle= get_field('page_head_subtitle'); ?>
  <?php if (!empty($subtitle)) : ?>
    <h2><?php echo $subtitle; ?></h2>
  <?php endif; ?>
  <?php $description= get_field('page_head_description'); ?>
  <?php if (!empty($description)) : ?>
    <?php echo apply_filters('the_content', $description); ?>
  <?php endif; ?>
</div>
<?php
