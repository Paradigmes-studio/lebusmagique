<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

function mkwvs_legal_anchor_headings(string $html, array &$toc): string
{
    $toc = [];

    return (string) preg_replace_callback(
        '#<h2([^>]*)>(.*?)</h2>#is',
        static function (array $matches) use (&$toc): string {
            $label = trim(wp_strip_all_tags($matches[2]));
            $id    = 'section-' . sanitize_title($label);

            $toc[] = ['id' => $id, 'label' => $label];

            return '<h2 id="' . esc_attr($id) . '"' . $matches[1] . '>' . $matches[2] . '</h2>';
        },
        $html
    );
}
