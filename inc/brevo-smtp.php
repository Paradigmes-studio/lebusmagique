<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

function mkwvs_brevo_configure_phpmailer($phpmailer): void
{
    if (!defined('BREVO_SMTP_LOGIN') || !defined('BREVO_SMTP_KEY') || !defined('BREVO_SMTP_FROM')) {
        return;
    }

    if (empty(BREVO_SMTP_LOGIN) || empty(BREVO_SMTP_KEY) || empty(BREVO_SMTP_FROM)) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp-relay.brevo.com';
    $phpmailer->Port = 587;
    $phpmailer->SMTPAuth = true;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Username = BREVO_SMTP_LOGIN;
    $phpmailer->Password = BREVO_SMTP_KEY;

    $phpmailer->setFrom(BREVO_SMTP_FROM, 'Le Bus Magique', false);
}

add_action('phpmailer_init', 'mkwvs_brevo_configure_phpmailer');
