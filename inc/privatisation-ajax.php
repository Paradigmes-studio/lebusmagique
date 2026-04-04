<?php

declare(strict_types=1);

add_action('wp_ajax_priv_check_email', 'mkwvs_priv_ajax_check_email');
add_action('wp_ajax_nopriv_priv_check_email', 'mkwvs_priv_ajax_check_email');

function mkwvs_priv_ajax_check_email(): void
{
    check_ajax_referer('priv_submit_nonce');

    $email = sanitize_email($_POST['email'] ?? '');
    if (!is_email($email)) {
        wp_send_json_error('Email invalide');
    }

    $domain = substr($email, strrpos($email, '@') + 1);
    if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
        wp_send_json_error('Ce domaine email ne semble pas exister. Vérifiez l\'adresse saisie.');
    }

    wp_send_json_success();
}

add_action('wp_ajax_priv_get_calendar', 'mkwvs_priv_ajax_get_calendar');
add_action('wp_ajax_nopriv_priv_get_calendar', 'mkwvs_priv_ajax_get_calendar');

function mkwvs_priv_ajax_get_calendar(): void
{
    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
    $month = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('n');

    if ($month < 1 || $month > 12 || $year < (int) date('Y') || $year > (int) date('Y') + 2) {
        wp_send_json_error('Date invalide');
    }

    $statuses = mkwvs_priv_get_month_statuses($year, $month);
    $bookings = mkwvs_priv_get_bookings_for_month($year, $month);
    $min_hours = mkwvs_priv_get_month_min_hours($year, $month);
    $max_hours = mkwvs_priv_get_month_max_hours($year, $month);
    $reservable = mkwvs_priv_get_month_reservable($year, $month);

    wp_send_json_success([
        'year'       => $year,
        'month'      => $month,
        'statuses'   => $statuses,
        'bookings'   => (object) $bookings,
        'min_hours'  => (object) $min_hours,
        'max_hours'  => (object) $max_hours,
        'reservable' => (object) $reservable,
    ]);
}

add_action('wp_ajax_priv_submit', 'mkwvs_priv_ajax_submit');
add_action('wp_ajax_nopriv_priv_submit', 'mkwvs_priv_ajax_submit');

function mkwvs_priv_ajax_submit(): void
{
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'priv_submit_nonce')) {
        wp_send_json_error('Nonce invalide');
    }

    $required = ['type', 'nom', 'prenom', 'email', 'telephone', 'date', 'heure_debut', 'heure_fin', 'nb_personnes', 'description'];
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || $_POST[$field] === '') {
            wp_send_json_error('Champ requis manquant : ' . $field);
        }
    }

    if (empty($_POST['rgpd_consent'])) {
        wp_send_json_error('Vous devez accepter la politique de confidentialité');
    }

    $type = sanitize_text_field($_POST['type']);
    if (!in_array($type, ['particulier', 'entreprise', 'association'], true)) {
        wp_send_json_error('Type de demandeur invalide');
    }
    $nom = sanitize_text_field($_POST['nom']);
    $prenom = sanitize_text_field($_POST['prenom']);
    $email = sanitize_email($_POST['email']);
    $telephone = sanitize_text_field($_POST['telephone']);
    $organisation = sanitize_text_field($_POST['organisation'] ?? '');
    $date = sanitize_text_field($_POST['date']);
    $heure_debut = (int) $_POST['heure_debut'];
    $heure_fin = (int) $_POST['heure_fin'];
    $nb_personnes = (int) $_POST['nb_personnes'];
    $description = sanitize_textarea_field($_POST['description'] ?? '');

    if (!is_email($email)) {
        wp_send_json_error('Email invalide');
    }

    $email_domain = substr($email, strrpos($email, '@') + 1);
    if (!checkdnsrr($email_domain, 'MX') && !checkdnsrr($email_domain, 'A')) {
        wp_send_json_error('Le domaine de cet email n\'existe pas');
    }

    $tel_clean = preg_replace('/[\s.\-()]+/', '', $telephone);
    if (!preg_match('/^(0[1-9])\d{8}$/', $tel_clean) && !preg_match('/^\+33[1-9]\d{8}$/', $tel_clean)) {
        wp_send_json_error('Numéro de téléphone invalide (format français attendu)');
    }

    $date_obj = DateTimeImmutable::createFromFormat('Y-m-d', $date);
    if (!$date_obj || $date_obj->format('Y-m-d') !== $date) {
        wp_send_json_error('Format de date invalide');
    }

    if ($nb_personnes < 1 || $nb_personnes > 100) {
        wp_send_json_error('Nombre de personnes invalide (1-100)');
    }

    $heure_debut_calc = ($heure_debut === 0) ? 24 : $heure_debut;
    $heure_fin_calc = ($heure_fin <= 2) ? $heure_fin + 24 : $heure_fin;
    if ($heure_fin_calc <= $heure_debut_calc) {
        wp_send_json_error('L\'heure de fin doit être après l\'heure de début');
    }

    $date_status = mkwvs_priv_get_date_status($date);
    if ($date_status === 'grey') {
        wp_send_json_error('Cette date n\'est pas disponible');
    }

    $min_hour = mkwvs_priv_get_min_hour($date);
    if ($min_hour !== null && $heure_debut !== 0 && $heure_debut < $min_hour) {
        wp_send_json_error('Ce jour, la privatisation est possible uniquement à partir de ' . $min_hour . 'h');
    }

    $max_hour = mkwvs_priv_get_max_hour($date);
    if ($max_hour !== null && ($heure_fin === 0 || $heure_fin > $max_hour)) {
        wp_send_json_error('Ce jour, la privatisation doit se terminer avant ' . $max_hour . 'h');
    }

    $overlap = mkwvs_priv_check_overlap($date, $heure_debut, $heure_fin);
    if ($overlap !== null) {
        $label_debut = ($overlap['heure_debut'] === 0) ? 'minuit' : $overlap['heure_debut'] . 'h';
        $label_fin = ($overlap['heure_fin'] === 0) ? 'minuit' : $overlap['heure_fin'] . 'h';
        wp_send_json_error('Ce créneau est trop proche d\'une privatisation prévue de ' . $label_debut . ' à ' . $label_fin . '. Un battement d\'une heure est nécessaire entre deux privatisations.');
    }

    $mode = sanitize_text_field($_POST['mode'] ?? 'privatisation');
    if (!in_array($mode, ['reservation', 'privatisation'], true)) {
        $mode = 'privatisation';
    }

    // Réservation d'espace : max 40 personnes
    if ($mode === 'reservation' && $nb_personnes > 40) {
        wp_send_json_error('La réservation d\'espace est limitée à 40 personnes. Au-delà, une privatisation est nécessaire.');
    }

    if ((int) $date_obj->format('N') === 6 && $heure_fin_calc <= 16 && $date_status !== 'red') {
        $date_status = 'green';
    }

    $title = $prenom . ' ' . $nom . ' - ' . $date;

    if ($mode === 'reservation') {
        // Réservation d'espace : pas d'options, pas de devis, toujours pending
        $post_id = wp_insert_post([
            'post_type'   => 'privatisation',
            'post_title'  => $title,
            'post_status' => 'priv_pending',
        ]);

        if (is_wp_error($post_id)) {
            wp_send_json_error('Erreur lors de la création de la demande');
        }

        update_field('priv_mode', 'reservation', $post_id);
        update_field('priv_type', $type, $post_id);
        update_field('priv_nom', $nom, $post_id);
        update_field('priv_prenom', $prenom, $post_id);
        update_field('priv_email', $email, $post_id);
        update_field('priv_telephone', $telephone, $post_id);
        update_field('priv_organisation', $organisation, $post_id);
        update_field('priv_date', $date, $post_id);
        update_field('priv_date_status', $date_status, $post_id);
        update_field('priv_heure_debut', $heure_debut, $post_id);
        update_field('priv_heure_fin', $heure_fin, $post_id);
        update_field('priv_nb_personnes', $nb_personnes, $post_id);
        update_field('priv_description', $description, $post_id);
        update_field('priv_rgpd_consent', true, $post_id);

        mkwvs_priv_send_reservation_email($post_id);
        mkwvs_priv_send_notification_email($post_id, false);

        wp_send_json_success([
            'message' => 'Votre demande de réservation d\'espace a bien été envoyée. Nous vous répondrons sous 7 jours.',
            'post_id' => $post_id,
        ]);
    }

    // Mode privatisation
    $opt_data = [
        'nb_personnes'          => $nb_personnes,
        'heure_debut'           => $heure_debut,
        'heure_fin'             => $heure_fin,
        'opt_son_lumiere'       => !empty($_POST['opt_son_lumiere']),
        'opt_artiste'           => !empty($_POST['opt_artiste']),
        'opt_dj'                => !empty($_POST['opt_dj']),
        'opt_tonnelle'          => !empty($_POST['opt_tonnelle']),
        'opt_repas'             => sanitize_text_field($_POST['opt_repas'] ?? 'aucun'),
        'opt_service_table'     => !empty($_POST['opt_service_table']) && $nb_personnes <= 60,
        'opt_boissons'          => !empty($_POST['opt_boissons']),
        'opt_boissons_quantite' => (int) ($_POST['opt_boissons_quantite'] ?? 0),
        'opt_autre'             => sanitize_textarea_field($_POST['opt_autre'] ?? ''),
    ];

    $quote = mkwvs_priv_calculate_quote($opt_data);

    // 81+ personnes : toujours en attente (validation manuelle de la jauge)
    $post_status = ($date_status === 'green' && $nb_personnes <= 80) ? 'priv_accepted' : 'priv_pending';

    $post_id = wp_insert_post([
        'post_type'   => 'privatisation',
        'post_title'  => $title,
        'post_status' => $post_status,
    ]);

    if (is_wp_error($post_id)) {
        wp_send_json_error('Erreur lors de la création de la demande');
    }

    update_field('priv_mode', 'privatisation', $post_id);
    update_field('priv_type', $type, $post_id);
    update_field('priv_nom', $nom, $post_id);
    update_field('priv_prenom', $prenom, $post_id);
    update_field('priv_email', $email, $post_id);
    update_field('priv_telephone', $telephone, $post_id);
    update_field('priv_organisation', $organisation, $post_id);
    update_field('priv_date', $date, $post_id);
    update_field('priv_date_status', $date_status, $post_id);
    update_field('priv_heure_debut', $heure_debut, $post_id);
    update_field('priv_heure_fin', $heure_fin, $post_id);
    update_field('priv_nb_personnes', $nb_personnes, $post_id);
    update_field('priv_description', $description, $post_id);
    update_field('priv_opt_son_lumiere', $opt_data['opt_son_lumiere'], $post_id);
    update_field('priv_opt_artiste', $opt_data['opt_artiste'], $post_id);
    update_field('priv_opt_dj', $opt_data['opt_dj'], $post_id);
    update_field('priv_opt_tonnelle', $opt_data['opt_tonnelle'], $post_id);
    update_field('priv_opt_repas', $opt_data['opt_repas'], $post_id);
    update_field('priv_opt_service_table', $opt_data['opt_service_table'], $post_id);
    update_field('priv_opt_boissons', $opt_data['opt_boissons'], $post_id);
    update_field('priv_opt_boissons_quantite', $opt_data['opt_boissons_quantite'], $post_id);
    update_field('priv_opt_autre', $opt_data['opt_autre'], $post_id);
    update_field('priv_montant_ht', $quote['total_ht'], $post_id);
    update_field('priv_montant_ttc', $quote['total_ttc'], $post_id);
    update_field('priv_rgpd_consent', true, $post_id);

    $devis_number = mkwvs_priv_generate_devis_number($post_id);
    update_field('priv_numero_devis', $devis_number, $post_id);

    if ($post_status === 'priv_accepted') {
        $pdf_path = mkwvs_priv_generate_pdf($post_id, $quote);
        update_field('priv_pdf_path', $pdf_path, $post_id);
        mkwvs_priv_send_confirmation_email($post_id, $pdf_path);
        mkwvs_priv_send_notification_email($post_id, false);
        $message = 'Votre demande a bien été envoyée. Votre devis est en pièce jointe du mail de confirmation.';
    } else {
        $is_urgent = ($date_status === 'red');
        mkwvs_priv_send_reception_email($post_id);
        mkwvs_priv_send_notification_email($post_id, $is_urgent);
        $message = 'Votre demande a bien été envoyée. Elle sera étudiée et nous vous répondrons sous 7 jours.';
    }

    wp_send_json_success([
        'message' => $message,
        'post_id' => $post_id,
    ]);
}
