<?php
/**
 * Template Name: Links Social Media
 */

$image      = get_field('lsm_image');
$zones      = get_field('lsm_zones') ?: array();
$zone_left  = get_field('lsm_zone_left');
$zone_width = get_field('lsm_zone_width');
$bg_color   = get_field('lsm_bg_color') ?: '#ffd41f';
$debug      = isset($_GET['debug']);

$zone_left  = is_numeric($zone_left) ? floatval($zone_left) : 11;
$zone_width = is_numeric($zone_width) ? floatval($zone_width) : 78;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title><?php echo esc_html(get_the_title()); ?> - Le Bus Magique</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background: <?php echo esc_attr($bg_color); ?>;
      display: flex;
      justify-content: center;
      min-height: 100vh;
    }
    .lsm-image {
      position: relative;
      width: 100%;
      max-width: 500px;
    }
    .lsm-image img {
      display: block;
      width: 100%;
      height: auto;
    }
    .lsm-zone {
      position: absolute;
      left: <?php echo $zone_left; ?>%;
      width: <?php echo $zone_width; ?>%;
      border-radius: 999px;
    }
    <?php if ($debug) : ?>
    .lsm-zone {
      background: rgba(255, 0, 0, 0.35);
      outline: 2px dashed #f00;
      color: #fff;
      font: bold 14px/1 sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    <?php endif; ?>
  </style>
</head>
<body>
  <div class="lsm-image">
    <?php if ($image) : ?>
      <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>">
      <?php foreach ($zones as $i => $zone) : ?>
        <?php if (empty($zone['zone_url']) && !$debug) { continue; } ?>
        <a
          class="lsm-zone"
          href="<?php echo esc_url($zone['zone_url']); ?>"
          <?php if (!empty($zone['zone_label'])) : ?>aria-label="<?php echo esc_attr($zone['zone_label']); ?>" title="<?php echo esc_attr($zone['zone_label']); ?>"<?php endif; ?>
          style="top: <?php echo floatval($zone['zone_top']); ?>%; height: <?php echo floatval($zone['zone_height']); ?>%;"
        ><?php echo $debug ? $i + 1 : ''; ?></a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>
