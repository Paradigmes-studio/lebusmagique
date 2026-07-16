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

    $parent = array();
    $find   = function ($a) use (&$parent) {
        while ($parent[$a] !== $a) {
            $parent[$a] = $parent[$parent[$a]];
            $a          = $parent[$a];
        }
        return $a;
    };

    $runs_prev = array();
    $boxes     = array();
    $rid       = 0;

    for ($y = 0; $y < $h; $y++) {
        $runs = array();
        $x    = 0;

        while ($x < $w) {
            $rgb = imagecolorat($img, $x, $y);
            if ((($rgb >> 16) & 0xFF) >= 235 && (($rgb >> 8) & 0xFF) >= 235 && ($rgb & 0xFF) >= 235) {
                $x0 = $x;
                do {
                    $x++;
                    if ($x >= $w) {
                        break;
                    }
                    $rgb = imagecolorat($img, $x, $y);
                } while ((($rgb >> 16) & 0xFF) >= 235 && (($rgb >> 8) & 0xFF) >= 235 && ($rgb & 0xFF) >= 235);
                $parent[$rid] = $rid;
                $runs[]       = array($x0, $x - 1, $rid);
                $rid++;
            } else {
                $x++;
            }
        }

        foreach ($runs as $run) {
            foreach ($runs_prev as $prev) {
                if ($run[0] <= $prev[1] && $prev[0] <= $run[1]) {
                    $ra = $find($run[2]);
                    $rb = $find($prev[2]);
                    if ($ra !== $rb) {
                        $parent[$rb] = $ra;
                    }
                }
            }
        }

        foreach ($runs as $run) {
            $boxes[$run[2]] = array($run[0], $y, $run[1], $y);
        }

        $runs_prev = $runs;
    }

    imagedestroy($img);

    $merged = array();
    foreach ($boxes as $id => $box) {
        $root = $find($id);
        if (!isset($merged[$root])) {
            $merged[$root] = $box;
        } else {
            $merged[$root][0] = min($merged[$root][0], $box[0]);
            $merged[$root][1] = min($merged[$root][1], $box[1]);
            $merged[$root][2] = max($merged[$root][2], $box[2]);
            $merged[$root][3] = max($merged[$root][3], $box[3]);
        }
    }

    $min_height = max(6, $h * 0.005);
    $kept       = array();

    foreach ($merged as $box) {
        list($x0, $y0, $x1, $y1) = $box;
        $bw = $x1 - $x0 + 1;
        $bh = $y1 - $y0 + 1;
        if ($bw < $w * 0.4 || $x0 < $w * 0.02 || $x1 > $w * 0.98) {
            continue;
        }
        if ($bh < $min_height || $bh > $h * 0.15 || $bw / $bh < 3) {
            continue;
        }
        $kept[] = $box;
    }

    if (!$kept) {
        return array();
    }

    usort($kept, function ($a, $b) {
        return $a[1] - $b[1];
    });

    $zones = array();
    $left  = $w;
    $right = 0;

    foreach ($kept as $box) {
        $zones[] = array(
            'top'    => round($box[1] / $h * 100, 2),
            'height' => round(($box[3] - $box[1] + 1) / $h * 100, 2),
        );
        $left    = min($left, $box[0]);
        $right   = max($right, $box[2]);
    }

    return array(
        'zones' => $zones,
        'left'  => round($left / $w * 100, 2),
        'width' => round(($right - $left) / $w * 100, 2),
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
