<?php

function mkwvs_lsm_detect_zones($path)
{
    if (!function_exists('imagecreatefromstring')) {
        return array();
    }

    $data = @file_get_contents($path);
    if (false === $data) {
        return array();
    }

    $img = @imagecreatefromstring($data);
    if (!$img) {
        return array();
    }

    if (!imageistruecolor($img)) {
        imagepalettetotruecolor($img);
    }

    $w = imagesx($img);
    $h = imagesy($img);

    $bands      = array();
    $band_start = null;
    $band_left  = null;
    $band_right = null;

    for ($y = 0; $y < $h; $y++) {
        $count     = 0;
        $leftmost  = -1;
        $rightmost = -1;

        for ($x = 0; $x < $w; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r   = ($rgb >> 16) & 0xFF;
            $g   = ($rgb >> 8) & 0xFF;
            $b   = $rgb & 0xFF;

            if ($r >= 235 && $g >= 235 && $b >= 235) {
                $count++;
                if ($leftmost < 0) {
                    $leftmost = $x;
                }
                $rightmost = $x;
            }
        }

        $span = $rightmost - $leftmost;

        $is_button = $count > 0
            && $leftmost >= $w * 0.02
            && $rightmost <= $w * 0.98
            && $span >= $w * 0.4
            && $count >= $span * 0.6;

        if ($is_button) {
            if (null === $band_start) {
                $band_start = $y;
                $band_left  = $leftmost;
                $band_right = $rightmost;
            } else {
                $band_left  = min($band_left, $leftmost);
                $band_right = max($band_right, $rightmost);
            }
        } elseif (null !== $band_start) {
            $bands[]    = array($band_start, $y, $band_left, $band_right);
            $band_start = null;
        }
    }

    if (null !== $band_start) {
        $bands[] = array($band_start, $h, $band_left, $band_right);
    }

    imagedestroy($img);

    $min_height = max(6, $h * 0.005);
    $zones      = array();
    $lefts      = array();
    $rights     = array();

    foreach ($bands as $band) {
        list($start, $end, $left, $right) = $band;
        if (($end - $start) < $min_height) {
            continue;
        }
        $zones[]  = array(
            'top'    => round($start / $h * 100, 2),
            'height' => round(($end - $start) / $h * 100, 2),
        );
        $lefts[]  = $left;
        $rights[] = $right;
    }

    if (!$zones) {
        return array();
    }

    sort($lefts);
    sort($rights);
    $mid = (int) floor(count($zones) / 2);

    return array(
        'zones' => $zones,
        'left'  => round($lefts[$mid] / $w * 100, 2),
        'width' => round(($rights[$mid] - $lefts[$mid]) / $w * 100, 2),
    );
}

add_action('acf/save_post', 'mkwvs_lsm_maybe_autodetect_zones', 20);
function mkwvs_lsm_maybe_autodetect_zones($post_id)
{
    if (!is_numeric($post_id) || 'templates/links-social-media.php' !== get_page_template_slug($post_id)) {
        return;
    }

    if (!get_field('lsm_autodetect', $post_id)) {
        return;
    }

    update_field('field_lsm_autodetect', 0, $post_id);

    $image = get_field('lsm_image', $post_id);
    if (!$image || empty($image['ID'])) {
        return;
    }

    $path = get_attached_file($image['ID']);
    if (!$path || !file_exists($path)) {
        return;
    }

    $result = mkwvs_lsm_detect_zones($path);
    if (empty($result['zones'])) {
        return;
    }

    $old  = get_field('lsm_zones', $post_id) ?: array();
    $rows = array();

    foreach ($result['zones'] as $i => $zone) {
        $rows[] = array(
            'field_lsm_zone_top'           => $zone['top'],
            'field_lsm_zone_height'        => $zone['height'],
            'field_lsm_zone_url'           => isset($old[$i]['zone_url']) ? $old[$i]['zone_url'] : '',
            'field_lsm_zone_label'         => isset($old[$i]['zone_label']) ? $old[$i]['zone_label'] : '',
            'field_lsm_zone_override_left' => '',
            'field_lsm_zone_override_width' => '',
        );
    }

    for ($i = count($result['zones']); $i < count($old); $i++) {
        if (!is_numeric($old[$i]['zone_left'])) {
            continue;
        }
        $rows[] = array(
            'field_lsm_zone_top'            => $old[$i]['zone_top'],
            'field_lsm_zone_height'         => $old[$i]['zone_height'],
            'field_lsm_zone_url'            => $old[$i]['zone_url'],
            'field_lsm_zone_label'          => $old[$i]['zone_label'],
            'field_lsm_zone_override_left'  => $old[$i]['zone_left'],
            'field_lsm_zone_override_width' => $old[$i]['zone_width'],
        );
    }

    update_field('field_lsm_zones', $rows, $post_id);
    update_field('field_lsm_zone_left', $result['left'], $post_id);
    update_field('field_lsm_zone_width', $result['width'], $post_id);
}
