<?php

/**
 * Video with image Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
// $id = 'testimonial-' . $block['id'];
// if( !empty($block['anchor']) ) {
//     $id = $block['anchor'];
// }

// Create class attribute allowing for custom "className" and "align" values.
$className = 'o-video-with-image';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$video_link = get_field('video-with-image_url');
$video_id = mkwvs_get_video_url_id($video_link);
$image_cover = get_field('video-with-image_image');
?>
<div class="<?php echo esc_attr($className); ?>">
  <a class="o-video-with-image__link js-modal-btn" href="javascript:void(0);" data-video-id="<?php echo( $video_id ); ?>">
    <span class="icons__main">
        <?php locate_template( 'images/play.svg', true, false ); ?>
    </span>
    <span class="o-video-with-image__thumbnail">
      <span class="o-video-with-image__thumbnail-content">
        <?php echo wp_get_attachment_image($image_cover, 'video-thumb'); ?>
        <!--<?php echo wp_get_attachment_image_srcset($image_cover, 'video-thumb'); ?>-->
        <!-- <span style="background: yellow"></span> -->
      </span>
    </span>
  </a>
</div>
