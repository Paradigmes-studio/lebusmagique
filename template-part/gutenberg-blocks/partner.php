<?php

/**
 * Partner Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'partner-' . $block['id'];
// if( !empty($block['anchor']) ) {
//     $id = $block['anchor'];
// }

// Create class attribute allowing for custom "className" and "align" values.
$className = 'o-partner';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
// if( !empty($block['align']) ) {
//     $className .= ' align' . $block['align'];
// }

// Load values and assign defaults.
$title = get_field( 'partner_title');
$logo = get_field('partner_logo') ?: '';
$ref = get_field('partner_ref') ?: '';
$link = get_field('partner_link');
$link_url = $link['url'];
$link_target = $link['target'] ? $link['target'] : '_self';
$button_label = get_field('partner_button_label') ?: '';
$button_link = get_field('partner_button_link') ?: '';
$button_link_url = $button_link['url'];
$button_link_target = $button_link['target'] ? $button_link['target'] : '_self';


?>
<div class="<?php echo $className; ?>" id="<?php echo $id; ?>">

  <div class="<?php echo $className; ?>__media<?php if($logo == '') { echo " ".$className."__media--no-image"; } ?>">
  <?php if($logo != '') { ?>
    <?php echo wp_get_attachment_image($logo, 'full'); ?>
  <?php } else { ?>
    <div class="<?php echo $className; ?>__decoration"><?php locate_template( 'img/loop-bg.svg', true, false ); ?></div>
  <?php } ?>
  </div>

  <h4 class="<?php echo $className; ?>__title"><?php echo $title; ?></h4>

  <?php if($ref != '') { ?>
  <p class="<?php echo $className; ?>__ref"><strong><?php echo _('Référent·e', 'loop'); ?></strong><?php echo $ref; ?></h4>
  <?php } ?>

  <?php if($link != '') { ?>
  <p class="<?php echo $className; ?>__link"><a href="<?php echo $link_url; ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo str_replace(array('http://', 'https://'), '', $link_url); ?></a></p>
  <?php } ?>

  <?php if($button_label != '' && $button_link != '') { ?>
  <a class="a-button a-button--alt" href="<?php echo $button_link_url; ?>" target="<?php echo esc_attr( $button_link_target ); ?>">
    <span class="a-button__icon a-icon a-icon--circled"><?php locate_template( 'img/fleche.svg', true, false ); ?></span>
    <span class="a-button__label"><?php echo $button_label; ?></span>
  </a>
  <?php } ?>

</div>
