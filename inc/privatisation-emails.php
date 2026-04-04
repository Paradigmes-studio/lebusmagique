<?php

declare(strict_types=1);

function mkwvs_priv_get_email_headers(): array
{
    return [
        'Content-Type: text/html; charset=UTF-8',
        'From: Le Bus Magique <contact@lebusmagiquelille.fr>',
        'Cc: lebusmagique.lille@gmail.com',
    ];
}

function mkwvs_priv_get_email_variables_config(): array
{
    return [
        'confirmation' => [
            '{prenom}'       => 'Prénom du demandeur',
            '{nom}'          => 'Nom du demandeur',
            '{type}'         => 'Type (particulier, entreprise, association)',
            '{organisation}' => 'Organisation (si entreprise/association)',
            '{date}'         => 'Date de l\'évènement (jj/mm/aaaa)',
            '{heure_debut}'  => 'Heure de début',
            '{heure_fin}'    => 'Heure de fin',
            '{nb_personnes}' => 'Nombre de personnes',
            '{montant_ttc}'  => 'Montant TTC',
            '{numero_devis}' => 'Numéro de devis',
        ],
        'reception' => [
            '{prenom}'       => 'Prénom du demandeur',
            '{nom}'          => 'Nom du demandeur',
            '{type}'         => 'Type (particulier, entreprise, association)',
            '{organisation}' => 'Organisation (si entreprise/association)',
            '{date}'         => 'Date de l\'évènement (jj/mm/aaaa)',
            '{heure_debut}'  => 'Heure de début',
            '{heure_fin}'    => 'Heure de fin',
            '{nb_personnes}' => 'Nombre de personnes',
        ],
        'refus' => [
            '{prenom}'       => 'Prénom du demandeur',
            '{type}'         => 'Type (particulier, entreprise, association)',
            '{date}'         => 'Date de l\'évènement (jj/mm/aaaa)',
        ],
        'notification' => [
            '{prenom}'       => 'Prénom du demandeur',
            '{nom}'          => 'Nom du demandeur',
            '{type}'         => 'Type (particulier, entreprise, association)',
            '{organisation}' => 'Organisation (si entreprise/association)',
            '{email}'        => 'Email du demandeur',
            '{telephone}'    => 'Téléphone du demandeur',
            '{date}'         => 'Date de l\'évènement (jj/mm/aaaa)',
            '{heure_debut}'  => 'Heure de début',
            '{heure_fin}'    => 'Heure de fin',
            '{nb_personnes}' => 'Nombre de personnes',
            '{description}'  => 'Description de l\'évènement',
            '{montant_ht}'   => 'Montant HT',
            '{montant_ttc}'  => 'Montant TTC',
            '{lien_admin}'   => 'Lien vers la demande dans l\'admin',
        ],
    ];
}

function mkwvs_priv_build_email_values(int $post_id): array
{
    $date_raw = get_field('priv_date', $post_id);
    $heure_debut = (int) get_field('priv_heure_debut', $post_id);
    $heure_fin = (int) get_field('priv_heure_fin', $post_id);
    $montant_ht = (float) get_field('priv_montant_ht', $post_id);
    $montant_ttc = (float) get_field('priv_montant_ttc', $post_id);

    $date_formatted = $date_raw ? date('d/m/Y', strtotime($date_raw)) : '';
    $heure_debut_label = ($heure_debut === 0) ? 'minuit' : $heure_debut . 'h';
    $heure_fin_label = ($heure_fin === 0) ? 'minuit' : $heure_fin . 'h';

    return [
        '{prenom}'       => esc_html(get_field('priv_prenom', $post_id) ?? ''),
        '{nom}'          => esc_html(get_field('priv_nom', $post_id) ?? ''),
        '{type}'         => esc_html(ucfirst(get_field('priv_type', $post_id) ?? '')),
        '{organisation}' => esc_html(get_field('priv_organisation', $post_id) ?? ''),
        '{email}'        => esc_html(get_field('priv_email', $post_id) ?? ''),
        '{telephone}'    => esc_html(get_field('priv_telephone', $post_id) ?? ''),
        '{date}'         => esc_html($date_formatted),
        '{heure_debut}'  => esc_html($heure_debut_label),
        '{heure_fin}'    => esc_html($heure_fin_label),
        '{nb_personnes}' => esc_html((string) get_field('priv_nb_personnes', $post_id)),
        '{description}'  => esc_html(get_field('priv_description', $post_id) ?? ''),
        '{montant_ht}'   => number_format($montant_ht, 2, ',', ' ') . ' €',
        '{montant_ttc}'  => number_format($montant_ttc, 2, ',', ' ') . ' €',
        '{numero_devis}' => esc_html(get_field('priv_numero_devis', $post_id) ?? ''),
        '{lien_admin}'   => esc_url(admin_url('post.php?post=' . $post_id . '&action=edit')),
    ];
}

function mkwvs_priv_email_envelope(string $content): string
{
    return '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #9bb909;">
        <h1 style="font-size: 22px; color: #1a1a1a; margin: 0;">LE BUS MAGIQUE</h1>
    </div>

    <div style="padding: 25px 0;">
        ' . $content . '
    </div>

    <div style="border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 11px; color: #999;">
        <p>Le Bus Magique — Façade de l\'Esplanade - Avenue Cuvier, 59800 LILLE</p>
        <p>Tél : 06 98 30 07 86 — lebusmagique.lille@gmail.com</p>
    </div>
</body>
</html>';
}

function mkwvs_priv_render_custom_email(string $email_key, int $post_id): ?string
{
    $templates = get_option('priv_email_templates', []);
    $typed_keys = ['confirmation', 'reception', 'refus'];

    if (in_array($email_key, $typed_keys, true)) {
        $type = get_field('priv_type', $post_id) ?: 'particulier';
        $body = $templates[$email_key][$type]['body'] ?? '';
    } else {
        $body = $templates[$email_key]['body'] ?? '';
    }

    if (empty($body)) {
        return null;
    }

    $values = mkwvs_priv_build_email_values($post_id);
    $body = str_replace(array_keys($values), array_values($values), $body);

    return mkwvs_priv_email_envelope($body);
}

function mkwvs_priv_get_custom_subject(string $email_key, int $post_id = 0): ?string
{
    $templates = get_option('priv_email_templates', []);
    $typed_keys = ['confirmation', 'reception', 'refus'];

    if (in_array($email_key, $typed_keys, true) && $post_id > 0) {
        $type = get_field('priv_type', $post_id) ?: 'particulier';
        $subject = $templates[$email_key][$type]['subject'] ?? '';
    } else {
        $subject = $templates[$email_key]['subject'] ?? '';
    }

    return !empty($subject) ? $subject : null;
}

function mkwvs_priv_get_email_defaults(): array
{
    $recap_confirmation = '
<div style="background: #f8f4e8; padding: 15px; margin: 20px 0; border-radius: 5px;">
<h3 style="margin: 0 0 10px; font-size: 15px;">Récapitulatif</h3>
<p><strong>Date :</strong> {date}</p>
<p><strong>Horaires :</strong> {heure_debut} à {heure_fin}</p>
<p><strong>Nombre de personnes :</strong> {nb_personnes}</p>
<p><strong>Montant TTC :</strong> {montant_ttc}</p>
</div>

<p>Contactez-nous pour affiner et adapter ce premier devis en fonction de vos possibilités et de vos attentes.</p>
<p>Pour confirmer votre réservation, merci de nous retourner le devis signé accompagné du versement des arrhes (30% du montant TTC).</p>
<p>À très bientôt à bord !</p>
<p><strong>L\'équipe du Bus Magique</strong></p>';

    $recap_reception = '
<div style="background: #f8f4e8; padding: 15px; margin: 20px 0; border-radius: 5px;">
<h3 style="margin: 0 0 10px; font-size: 15px;">Votre demande</h3>
<p><strong>Date :</strong> {date}</p>
<p><strong>Horaires :</strong> {heure_debut} à {heure_fin}</p>
<p><strong>Nombre de personnes :</strong> {nb_personnes}</p>
</div>

<p>Votre demande sera étudiée par notre équipe et <strong>nous vous répondrons sous 7 jours</strong>.</p>
<p>N\'hésitez pas à nous contacter pour toute question.</p>
<p>À bientôt !</p>
<p><strong>L\'équipe du Bus Magique</strong></p>';

    $footer_refus = '<p>Nous vous invitons à consulter notre calendrier pour trouver une date alternative qui pourrait vous convenir. N\'hésitez pas à soumettre une nouvelle demande.</p>
<p>Nous espérons avoir le plaisir de vous accueillir prochainement à bord !</p>
<p><strong>L\'équipe du Bus Magique</strong></p>';

    return [
        'confirmation' => [
            'particulier' => '<p>Bonjour {prenom},</p>
<p>Nous avons bien reçu votre demande de privatisation et nous réjouissons de vous accueillir à bord !</p>
<p>Vous trouverez ci-joint votre devis n° <strong>{numero_devis}</strong> pour la privatisation de la péniche.</p>' . $recap_confirmation,

            'entreprise' => '<p>Bonjour,</p>
<p>Nous avons bien reçu votre demande de privatisation pour le compte de {organisation}.</p>
<p>Vous trouverez ci-joint votre devis n° <strong>{numero_devis}</strong> pour la privatisation de la péniche.</p>' . $recap_confirmation,

            'association' => '<p>Bonjour,</p>
<p>Nous avons bien reçu la demande de privatisation de {organisation} et sommes ravis de soutenir votre projet.</p>
<p>Vous trouverez ci-joint votre devis n° <strong>{numero_devis}</strong> pour la privatisation de la péniche.</p>' . $recap_confirmation,
        ],
        'reception' => [
            'particulier' => '<p>Bonjour {prenom},</p>
<p>Nous avons bien reçu votre demande de privatisation et nous réjouissons de vous accueillir à bord !</p>' . $recap_reception,

            'entreprise' => '<p>Bonjour,</p>
<p>Nous avons bien reçu votre demande de privatisation pour le compte de {organisation}.</p>' . $recap_reception,

            'association' => '<p>Bonjour,</p>
<p>Nous avons bien reçu la demande de privatisation de {organisation} et sommes ravis de soutenir votre projet.</p>' . $recap_reception,
        ],
        'refus' => [
            'particulier' => '<p>Bonjour {prenom},</p>
<p>Nous avons bien étudié votre demande de privatisation pour le <strong>{date}</strong>.</p>
<p>Malheureusement, nous ne sommes pas en mesure de donner une suite favorable à votre demande pour cette date.</p>' . $footer_refus,

            'entreprise' => '<p>Bonjour,</p>
<p>Nous avons bien étudié la demande de privatisation de {organisation} pour le <strong>{date}</strong>.</p>
<p>Malheureusement, nous ne sommes pas en mesure de donner une suite favorable à cette demande pour cette date.</p>' . $footer_refus,

            'association' => '<p>Bonjour,</p>
<p>Nous avons bien étudié la demande de privatisation de {organisation} pour le <strong>{date}</strong>.</p>
<p>Malheureusement, nous ne sommes pas en mesure de donner une suite favorable à cette demande pour cette date.</p>' . $footer_refus,
        ],
        'notification' => '<h2 style="color: #1a1a1a;">Nouvelle demande de privatisation</h2>

<div style="background: #f8f4e8; padding: 15px; margin: 15px 0; border-radius: 5px;">
<h3 style="margin: 0 0 10px;">Demandeur</h3>
<p><strong>Type :</strong> {type}</p>
<p><strong>Nom :</strong> {prenom} {nom}</p>
<p><strong>Email :</strong> {email}</p>
<p><strong>Téléphone :</strong> {telephone}</p>
</div>

<div style="background: #f4f5f9; padding: 15px; margin: 15px 0; border-radius: 5px;">
<h3 style="margin: 0 0 10px;">Évènement</h3>
<p><strong>Date :</strong> {date}</p>
<p><strong>Horaires :</strong> {heure_debut} à {heure_fin}</p>
<p><strong>Nombre de personnes :</strong> {nb_personnes}</p>
<p><strong>Description :</strong> {description}</p>
</div>

<p><strong>Montant HT :</strong> {montant_ht} — <strong>Montant TTC :</strong> {montant_ttc}</p>

<p style="margin-top: 20px;">
<a href="{lien_admin}" style="display: inline-block; background: #9bb909; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Voir la demande dans l\'admin</a>
</p>',
    ];
}

function mkwvs_priv_send_confirmation_email(int $post_id, string $pdf_path): void
{
    $email = get_field('priv_email', $post_id);

    $custom_body = mkwvs_priv_render_custom_email('confirmation', $post_id);
    if ($custom_body !== null) {
        $subject = mkwvs_priv_get_custom_subject('confirmation', $post_id)
            ?? 'Votre devis de privatisation — Le Bus Magique';
        $values = mkwvs_priv_build_email_values($post_id);
        $subject = str_replace(array_keys($values), array_values($values), $subject);
        $body = $custom_body;
    } else {
        $subject = 'Votre devis de privatisation — Le Bus Magique';
        ob_start();
        include get_template_directory() . '/emails/privatisation-confirmation.php';
        $body = ob_get_clean();
    }

    wp_mail($email, $subject, $body, mkwvs_priv_get_email_headers(), [$pdf_path]);
}

function mkwvs_priv_send_reception_email(int $post_id): void
{
    $email = get_field('priv_email', $post_id);

    $custom_body = mkwvs_priv_render_custom_email('reception', $post_id);
    if ($custom_body !== null) {
        $subject = mkwvs_priv_get_custom_subject('reception', $post_id)
            ?? 'Votre demande de privatisation — Le Bus Magique';
        $values = mkwvs_priv_build_email_values($post_id);
        $subject = str_replace(array_keys($values), array_values($values), $subject);
        $body = $custom_body;
    } else {
        $subject = 'Votre demande de privatisation — Le Bus Magique';
        ob_start();
        include get_template_directory() . '/emails/privatisation-reception.php';
        $body = ob_get_clean();
    }

    wp_mail($email, $subject, $body, mkwvs_priv_get_email_headers());
}

function mkwvs_priv_send_reservation_email(int $post_id): void
{
    $email = get_field('priv_email', $post_id);
    $subject = 'Votre demande de réservation d\'espace — Le Bus Magique';

    ob_start();
    include get_template_directory() . '/emails/privatisation-reservation.php';
    $body = ob_get_clean();

    wp_mail($email, $subject, $body, mkwvs_priv_get_email_headers());
}

function mkwvs_priv_send_refusal_email(int $post_id): void
{
    $email = get_field('priv_email', $post_id);

    $custom_body = mkwvs_priv_render_custom_email('refus', $post_id);
    if ($custom_body !== null) {
        $subject = mkwvs_priv_get_custom_subject('refus', $post_id)
            ?? 'Votre demande de privatisation — Le Bus Magique';
        $values = mkwvs_priv_build_email_values($post_id);
        $subject = str_replace(array_keys($values), array_values($values), $subject);
        $body = $custom_body;
    } else {
        $subject = 'Votre demande de privatisation — Le Bus Magique';
        ob_start();
        include get_template_directory() . '/emails/privatisation-refus.php';
        $body = ob_get_clean();
    }

    wp_mail($email, $subject, $body, mkwvs_priv_get_email_headers());
}

function mkwvs_priv_send_notification_email(int $post_id, bool $is_urgent): void
{
    $to = 'lebusmagique.lille@gmail.com';

    $mode = get_field('priv_mode', $post_id) ?: 'privatisation';
    $default_subject = ($mode === 'reservation')
        ? 'Nouvelle demande de réservation d\'espace'
        : 'Nouvelle demande de privatisation';

    $custom_body = mkwvs_priv_render_custom_email('notification', $post_id);
    if ($custom_body !== null) {
        $base_subject = mkwvs_priv_get_custom_subject('notification')
            ?? $default_subject;
        $values = mkwvs_priv_build_email_values($post_id);
        $base_subject = str_replace(array_keys($values), array_values($values), $base_subject);
        $subject = ($is_urgent ? '[URGENT] ' : '') . $base_subject;
        $body = $custom_body;
    } else {
        $subject = ($is_urgent ? '[URGENT] ' : '') . $default_subject;
        ob_start();
        include get_template_directory() . '/emails/privatisation-notification.php';
        $body = ob_get_clean();
    }

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Le Bus Magique <contact@lebusmagiquelille.fr>',
    ];

    wp_mail($to, $subject, $body, $headers);
}
