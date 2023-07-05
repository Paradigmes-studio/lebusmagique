<?php

/**
 * Gallery Latitudes Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


// Create id attribute allowing for custom "anchor" value.
$id = 'gallery-' . $block['id'];

// Create class attribute allowing for custom "className" and "align" values.
$className = 'o-gallery';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
// if( !empty($block['align']) ) {
//     $className .= ' align' . $block['align'];
// }

// Load values and assign defaults.
?>

<?php $counter = 0; ?>
<?php if( have_rows('medias') ): ?>
<div class="<?php echo $className; ?>" id="<?php echo $id; ?>">
  <div class="o-gallery__list swiper-container">
    <div class="swiper-wrapper">
  <?php while( have_rows('medias') ): the_row(); ?>
    <?php
      $image = get_sub_field('image') ?: '';
      $url = get_sub_field('video_url') ?: '';
    ?>
      <div class="o-gallery__item swiper-slide">
      <?php if($url == '') { ?>
        <?php echo wp_get_attachment_image($image, 'video-thumb'); ?>
      <?php } else { ?>
      <?php $video_id = mkwvs_get_video_url_id($url); ?>
        <div class="o-video-with-image">
          <a class="o-video-with-image__link js-modal-btn" href="javascript:void(0);" data-video-id="<?php echo( $video_id ); ?>">
            <span class="a-icon"><?php locate_template( 'img/play.svg', true, false ); ?></span>
          </a>
          <span class="o-video-with-image__thumbnail">
            <span class="o-video-with-image__thumbnail-content">
              <?php echo wp_get_attachment_image($image, 'video-thumb'); ?>
            </span>
          </span>
        </div>
      <?php } ?>
      </div>
    <?php $counter++; ?>
  <?php endwhile; ?>
    </div>
  </div>
  <?php if($counter > 1) { ?>
  <div class="o-gallery__navigation o-slider__navigation">
    <button class="o-slider__prev"><?php locate_template( 'images/fleche.svg', true, false ); ?></button>
    <button class="o-slider__next"><?php locate_template( 'images/fleche.svg', true, false ); ?></button>
  </div>
  <?php } ?>
</div>
<?php endif; ?>
