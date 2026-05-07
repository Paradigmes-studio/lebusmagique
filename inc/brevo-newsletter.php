<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

const MKWVS_BREVO_NEWSLETTER_LIST_ID = 4;
const MKWVS_BREVO_NEWSLETTER_RATE_LIMIT = 5;
const MKWVS_BREVO_NEWSLETTER_RATE_WINDOW = 600;

function mkwvs_brevo_subscribe_ajax(): void
{
    check_ajax_referer('brevo_subscribe', 'nonce');

    if (!defined('BREVO_API_V3_KEY') || empty(BREVO_API_V3_KEY)) {
        wp_send_json_error(['message' => 'Service indisponible.'], 503);
    }

    $website = isset($_POST['website']) ? (string) $_POST['website'] : '';
    if ($website !== '') {
        wp_send_json_success(['message' => 'Merci pour votre inscription.']);
    }

    $consent = isset($_POST['consent']) ? (string) $_POST['consent'] : '';
    if ($consent === '') {
        wp_send_json_error(['message' => 'Veuillez accepter les conditions.'], 400);
    }

    $email = isset($_POST['email']) ? sanitize_email((string) $_POST['email']) : '';
    if ($email === '' || !is_email($email)) {
        wp_send_json_error(['message' => 'Adresse e-mail invalide.'], 400);
    }

    $ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    $rate_key = 'mkwvs_brevo_rl_' . md5($ip);
    $count = (int) get_transient($rate_key);
    if ($count >= MKWVS_BREVO_NEWSLETTER_RATE_LIMIT) {
        wp_send_json_error(['message' => 'Trop de tentatives. Veuillez réessayer plus tard.'], 429);
    }
    set_transient($rate_key, $count + 1, MKWVS_BREVO_NEWSLETTER_RATE_WINDOW);

    $response = wp_remote_post('https://api.brevo.com/v3/contacts', [
        'timeout' => 10,
        'headers' => [
            'api-key'      => BREVO_API_V3_KEY,
            'content-type' => 'application/json',
            'accept'       => 'application/json',
        ],
        'body' => wp_json_encode([
            'email'         => $email,
            'listIds'       => [MKWVS_BREVO_NEWSLETTER_LIST_ID],
            'updateEnabled' => true,
        ]),
    ]);

    if (is_wp_error($response)) {
        error_log('[brevo-newsletter] HTTP error: ' . $response->get_error_message());
        wp_send_json_error(['message' => 'Une erreur est survenue. Veuillez ré-essayer.'], 502);
    }

    $code = (int) wp_remote_retrieve_response_code($response);
    if ($code === 201 || $code === 204) {
        wp_send_json_success(['message' => 'Merci pour votre inscription.']);
    }

    error_log('[brevo-newsletter] API status ' . $code . ' body=' . wp_remote_retrieve_body($response));
    wp_send_json_error(['message' => 'Une erreur est survenue. Veuillez ré-essayer.'], 502);
}

add_action('wp_ajax_brevo_subscribe', 'mkwvs_brevo_subscribe_ajax');
add_action('wp_ajax_nopriv_brevo_subscribe', 'mkwvs_brevo_subscribe_ajax');
