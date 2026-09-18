<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

const MKWVS_UMAMI_SCRIPT_URL = 'https://analytics.symfolidity.com/script.js';
const MKWVS_UMAMI_WEBSITE_ID = 'ec988829-7a24-4ab3-a515-c90d23d31efb';

function mkwvs_umami_inject(): void
{
    if (is_admin() || is_user_logged_in()) {
        return;
    }

    printf(
        '<script defer src="%s" data-website-id="%s"></script>' . "\n",
        esc_url(MKWVS_UMAMI_SCRIPT_URL),
        esc_attr(MKWVS_UMAMI_WEBSITE_ID)
    );
}
add_action('wp_head', 'mkwvs_umami_inject', 5);
