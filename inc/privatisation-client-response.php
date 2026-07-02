<?php

declare(strict_types=1);

add_action('admin_post_nopriv_priv_client_response', 'mkwvs_priv_handle_client_response');
add_action('admin_post_priv_client_response', 'mkwvs_priv_handle_client_response');

function mkwvs_priv_client_response_map(): array
{
    return [
        'valide'  => 'priv_confirmed',
        'contact' => 'priv_contact',
        'refus'   => 'priv_declined',
    ];
}

function mkwvs_priv_client_response_open_statuses(): array
{
    return ['priv_accepted', 'priv_confirmed', 'priv_contact', 'priv_declined'];
}

function mkwvs_priv_handle_client_response(): void
{
    $map = mkwvs_priv_client_response_map();

    $post_id = (int) ($_REQUEST['post'] ?? 0);
    $token = (string) ($_REQUEST['token'] ?? '');
    $response = (string) ($_REQUEST['response'] ?? '');

    $post = $post_id > 0 ? get_post($post_id) : null;

    if (!$post instanceof WP_Post || $post->post_type !== 'privatisation' || !isset($map[$response])) {
        mkwvs_priv_client_response_page('Lien invalide', '<p>Ce lien n\'est pas valide.</p>', 400);

        return;
    }

    $stored = (string) get_post_meta($post_id, 'priv_client_token', true);
    if ($stored === '' || !hash_equals($stored, $token)) {
        mkwvs_priv_client_response_page('Lien invalide', '<p>Ce lien n\'est plus valide.</p>', 400);

        return;
    }

    if (!in_array($post->post_status, mkwvs_priv_client_response_open_statuses(), true)) {
        mkwvs_priv_client_response_page('Demande clôturée', '<p>Cette demande n\'est plus modifiable. Contactez-nous directement au 06 98 30 07 86 si besoin.</p>');

        return;
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        if (!wp_verify_nonce((string) ($_POST['_wpnonce'] ?? ''), 'priv_client_response_' . $post_id)) {
            mkwvs_priv_client_response_page('Lien expiré', '<p>Votre session a expiré. Merci de recliquer sur le lien depuis l\'email.</p>', 400);

            return;
        }

        wp_update_post(['ID' => $post_id, 'post_status' => $map[$response]]);
        mkwvs_priv_client_response_thanks($response);

        return;
    }

    mkwvs_priv_client_response_confirm($post_id, $token, $response);
}

function mkwvs_priv_client_response_confirm(int $post_id, string $token, string $response): void
{
    $labels = [
        'valide'  => ['title' => 'Valider votre devis', 'intro' => 'Vous êtes sur le point de valider votre devis de privatisation.', 'button' => 'Oui, je valide mon devis'],
        'contact' => ['title' => 'Être recontacté', 'intro' => 'Vous souhaitez être recontacté par notre équipe au sujet de votre devis.', 'button' => 'Oui, recontactez-moi'],
        'refus'   => ['title' => 'Décliner ce devis', 'intro' => 'Vous souhaitez nous indiquer que vous n\'êtes pas intéressé par ce devis.', 'button' => 'Confirmer'],
    ];
    $l = $labels[$response];

    $action = esc_url(admin_url('admin-post.php'));
    $nonce = wp_nonce_field('priv_client_response_' . $post_id, '_wpnonce', true, false);

    $content = '<p>' . esc_html($l['intro']) . '</p>
<form method="post" action="' . $action . '" style="margin-top: 25px;">
    <input type="hidden" name="action" value="priv_client_response">
    <input type="hidden" name="post" value="' . esc_attr((string) $post_id) . '">
    <input type="hidden" name="token" value="' . esc_attr($token) . '">
    <input type="hidden" name="response" value="' . esc_attr($response) . '">
    ' . $nonce . '
    <button type="submit" style="display:inline-block; background:#9bb909; color:#fff; padding:14px 28px; border:none; border-radius:5px; font-size:15px; font-weight:bold; cursor:pointer;">' . esc_html($l['button']) . '</button>
</form>';

    mkwvs_priv_client_response_page($l['title'], $content);
}

function mkwvs_priv_client_response_thanks(string $response): void
{
    $messages = [
        'valide'  => ['Merci !', 'Votre devis est validé. Nous revenons vers vous rapidement pour le versement des arrhes.'],
        'contact' => ['C\'est noté', 'Notre équipe vous recontacte au plus vite.'],
        'refus'   => ['Merci de nous avoir prévenus', 'Au plaisir de vous accueillir une prochaine fois à bord !'],
    ];
    [$title, $text] = $messages[$response];

    mkwvs_priv_client_response_page($title, '<p>' . esc_html($text) . '</p>');
}

function mkwvs_priv_client_response_page(string $title, string $content, int $status = 200): void
{
    status_header($status);
    nocache_headers();
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title><?php echo esc_html($title); ?> - Le Bus Magique</title>
</head>
<body style="font-family: Helvetica, Arial, sans-serif; font-size: 15px; color: #333; background: #f4f4f4; margin: 0; padding: 40px 20px;">
    <div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.08);">
        <div style="text-align: center; padding: 25px 20px; border-bottom: 3px solid #9bb909;">
            <h1 style="font-size: 22px; color: #1a1a1a; margin: 0;">LE BUS MAGIQUE</h1>
        </div>
        <div style="padding: 30px 25px;">
            <h2 style="font-size: 19px; color: #1a1a1a; margin: 0 0 15px;"><?php echo esc_html($title); ?></h2>
            <?php echo $content; ?>
        </div>
        <div style="border-top: 1px solid #eee; padding: 15px; text-align: center; font-size: 12px; color: #999;">
            Le Bus Magique - Avenue Cuvier, 59800 LILLE - 06 98 30 07 86
        </div>
    </div>
</body>
</html>
    <?php
    exit;
}
