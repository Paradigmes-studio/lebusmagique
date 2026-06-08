<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * L'archive du CPT facebook_events (/facebook-event/) renvoie une erreur 500
 * (rendu plugin). Les événements restent accessibles via la home, la page
 * Programmation et leurs pages individuelles : on redirige l'archive vers
 * /programmation/ plutôt que de servir une page en erreur.
 */
function mkwvs_fb_redirect_events_archive(): void
{
    if (is_admin() || !is_post_type_archive('facebook_events')) {
        return;
    }

    wp_safe_redirect(home_url('/programmation/'), 301);
    exit;
}
add_action('template_redirect', 'mkwvs_fb_redirect_events_archive', 1);
