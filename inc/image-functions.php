<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Rendu des images ACF via wp_get_attachment_image() : srcset, sizes, lazy loading
 * et texte alternatif de la médiathèque, là où les gabarits sortaient l'original brut.
 *
 * $image accepte un tableau ACF, un ID de média ou une URL.
 * $fallback_alt sert uniquement quand la médiathèque n'a pas de texte alternatif.
 */
function mkwvs_image($image, string $size = 'full', array $attr = [], string $fallback_alt = ''): string
{
    $defaults = ['loading' => 'lazy', 'decoding' => 'async'];
    $id       = mkwvs_image_id($image);

    if ($id > 0) {
        if ($fallback_alt !== '' && trim((string) get_post_meta($id, '_wp_attachment_image_alt', true)) === '') {
            $defaults['alt'] = $fallback_alt;
        }

        $html = wp_get_attachment_image($id, $size, false, array_merge($defaults, $attr));
        if ($html !== '') {
            return $html;
        }
    }

    $url = mkwvs_image_url($image);
    if ($url === '') {
        return '';
    }

    $attr = array_merge($defaults, ['alt' => $fallback_alt], $attr);
    $out  = '';
    foreach ($attr as $name => $value) {
        if ($value === null || $value === false) {
            continue;
        }
        $out .= sprintf(' %s="%s"', esc_attr((string) $name), esc_attr((string) $value));
    }

    return sprintf('<img src="%s"%s>', esc_url($url), $out);
}

function mkwvs_the_image($image, string $size = 'full', array $attr = [], string $fallback_alt = ''): void
{
    echo mkwvs_image($image, $size, $attr, $fallback_alt);
}

function mkwvs_image_id($image): int
{
    if (is_array($image)) {
        return isset($image['ID']) ? (int) $image['ID'] : 0;
    }

    return is_numeric($image) ? (int) $image : 0;
}

function mkwvs_image_url($image): string
{
    if (is_array($image)) {
        return isset($image['url']) ? (string) $image['url'] : '';
    }

    if (is_numeric($image)) {
        return (string) wp_get_attachment_image_url((int) $image, 'full');
    }

    return is_string($image) ? $image : '';
}
