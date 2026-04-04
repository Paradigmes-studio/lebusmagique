<?php

declare(strict_types=1);

function mkwvs_priv_get_default_tarifs(): array
{
    return [
        'location_9_minuit_small'  => 140,
        'location_9_minuit_large'  => 170,
        'location_minuit_2h_small' => 170,
        'location_minuit_2h_large' => 240,
        'frais_dossier'            => 100,
        'son_lumiere'              => 150,
        'artiste'                  => 450,
        'dj'                       => 250,
        'tonnelle'                 => 40,
        'repas_2_plats'            => 25,
        'repas_3_plats'            => 30,
        'service_table'            => 120,
        'boisson'                  => 3.5,
    ];
}

function mkwvs_priv_get_saved_tarifs(): array
{
    $custom = get_option('priv_tarifs', []);
    $defaults = mkwvs_priv_get_default_tarifs();

    return array_merge($defaults, array_intersect_key($custom, $defaults));
}

function mkwvs_priv_get_tarifs(int $nb_personnes): array
{
    $all = mkwvs_priv_get_saved_tarifs();
    $is_small = $nb_personnes < 50;

    return [
        'location_9_minuit'  => $is_small ? $all['location_9_minuit_small'] : $all['location_9_minuit_large'],
        'location_minuit_2h' => $is_small ? $all['location_minuit_2h_small'] : $all['location_minuit_2h_large'],
        'frais_dossier'      => $all['frais_dossier'],
        'son_lumiere'        => $all['son_lumiere'],
        'artiste'            => $all['artiste'],
        'dj'                 => $all['dj'],
        'tonnelle'           => $all['tonnelle'],
        'repas_2_plats'      => $all['repas_2_plats'],
        'repas_3_plats'      => $all['repas_3_plats'],
        'service_table'      => $all['service_table'],
        'boisson'            => $all['boisson'],
    ];
}

function mkwvs_priv_get_tarifs_for_js(): array
{
    $all = mkwvs_priv_get_saved_tarifs();

    return [
        'location_9_minuit_small'  => $all['location_9_minuit_small'],
        'location_9_minuit_large'  => $all['location_9_minuit_large'],
        'location_minuit_2h_small' => $all['location_minuit_2h_small'],
        'location_minuit_2h_large' => $all['location_minuit_2h_large'],
        'frais_dossier'            => $all['frais_dossier'],
        'son_lumiere'              => $all['son_lumiere'],
        'artiste'                  => $all['artiste'],
        'dj'                       => $all['dj'],
        'tonnelle'                 => $all['tonnelle'],
        'repas_2_plats'            => $all['repas_2_plats'],
        'repas_3_plats'            => $all['repas_3_plats'],
        'service_table'            => $all['service_table'],
        'boisson'                  => $all['boisson'],
    ];
}

function mkwvs_priv_get_tarifs_for_display(): array
{
    $all = mkwvs_priv_get_saved_tarifs();
    $formatted = [];

    foreach ($all as $key => $value) {
        $formatted[$key] = ($value == (int) $value)
            ? number_format($value, 0, ',', ' ')
            : number_format($value, 2, ',', ' ');
    }

    return $formatted;
}

function mkwvs_priv_calculate_quote(array $data): array
{
    $nb = (int) $data['nb_personnes'];
    $tarifs = mkwvs_priv_get_tarifs($nb);

    $heure_debut = (int) $data['heure_debut'];
    $heure_fin = (int) $data['heure_fin'];

    $minuit = 24;
    $heure_debut_calc = ($heure_debut === 0) ? 24 : $heure_debut;
    $heure_fin_calc = ($heure_fin <= 2) ? $heure_fin + 24 : $heure_fin;

    $heures_avant_minuit = 0;
    $heures_apres_minuit = 0;

    if ($heure_debut_calc < $minuit) {
        $heures_avant_minuit = min($minuit, $heure_fin_calc) - $heure_debut_calc;
    }
    if ($heure_fin_calc > $minuit) {
        $heures_apres_minuit = $heure_fin_calc - max($minuit, $heure_debut_calc);
    }

    $lines = [];
    $total_ht_20 = 0;
    $total_ht_10 = 0;

    if ($heures_avant_minuit > 0) {
        $total = $heures_avant_minuit * $tarifs['location_9_minuit'];
        $fin_label = ($heure_fin_calc >= $minuit) ? 'minuit' : $heure_fin . 'h';
        $debut_label = ($heure_debut === 0) ? 'minuit' : $heure_debut . 'h';
        $lines[] = [
            'designation' => 'Location de ' . $debut_label . ' à ' . $fin_label,
            'qty'         => $heures_avant_minuit,
            'unit_price'  => $tarifs['location_9_minuit'],
            'total'       => $total,
            'tva'         => 20,
        ];
        $total_ht_20 += $total;
    }

    if ($heures_apres_minuit > 0) {
        $total = $heures_apres_minuit * $tarifs['location_minuit_2h'];
        $lines[] = [
            'designation' => 'Location de minuit à ' . $heure_fin . 'h',
            'qty'         => $heures_apres_minuit,
            'unit_price'  => $tarifs['location_minuit_2h'],
            'total'       => $total,
            'tva'         => 20,
        ];
        $total_ht_20 += $total;
    }

    $lines[] = [
        'designation' => 'Frais de dossier - service - ménage',
        'qty'         => 1,
        'unit_price'  => $tarifs['frais_dossier'],
        'total'       => $tarifs['frais_dossier'],
        'tva'         => 20,
    ];
    $total_ht_20 += $tarifs['frais_dossier'];

    $options_20 = [
        'son_lumiere' => 'Son et lumière',
        'artiste'     => 'Artiste',
        'dj'          => 'DJ set / Blindtest / Karaoké',
        'tonnelle'    => 'Tonnelle',
    ];

    foreach ($options_20 as $key => $label) {
        if (!empty($data['opt_' . $key])) {
            $lines[] = [
                'designation' => $label,
                'qty'         => 1,
                'unit_price'  => $tarifs[$key],
                'total'       => $tarifs[$key],
                'tva'         => 20,
            ];
            $total_ht_20 += $tarifs[$key];
        }
    }

    if (!empty($data['opt_repas']) && $data['opt_repas'] !== 'aucun') {
        $is_3_plats = ($data['opt_repas'] === '3_plats');
        $prix_repas = $is_3_plats ? $tarifs['repas_3_plats'] : $tarifs['repas_2_plats'];
        $label_repas = $is_3_plats ? 'Repas (entrée-plat-dessert)' : 'Repas (entrée-plat ou plat-dessert)';
        $total_repas = $prix_repas * $nb;

        $lines[] = [
            'designation' => $label_repas,
            'qty'         => $nb,
            'unit_price'  => $prix_repas,
            'total'       => $total_repas,
            'tva'         => 10,
        ];
        $total_ht_10 += $total_repas;

        if (!empty($data['opt_service_table'])) {
            $lines[] = [
                'designation' => 'Service à table (3h)',
                'qty'         => 1,
                'unit_price'  => $tarifs['service_table'],
                'total'       => $tarifs['service_table'],
                'tva'         => 10,
            ];
            $total_ht_10 += $tarifs['service_table'];
        }
    }

    if (!empty($data['opt_boissons']) && !empty($data['opt_boissons_quantite'])) {
        $qty_boissons = (int) $data['opt_boissons_quantite'];
        $total_boissons = $qty_boissons * $tarifs['boisson'];
        $lines[] = [
            'designation' => 'Boissons (vins, bières, softs)',
            'qty'         => $qty_boissons,
            'unit_price'  => $tarifs['boisson'],
            'total'       => $total_boissons,
            'tva'         => 10,
        ];
        $total_ht_10 += $total_boissons;
    }

    $total_ht = $total_ht_20 + $total_ht_10;
    $tva_20 = round($total_ht_20 * 0.20, 2);
    $tva_10 = round($total_ht_10 * 0.10, 2);
    $total_ttc = $total_ht + $tva_20 + $tva_10;

    return [
        'lines'     => $lines,
        'total_ht'  => $total_ht,
        'tva_20'    => $tva_20,
        'tva_10'    => $tva_10,
        'total_ttc' => $total_ttc,
    ];
}

function mkwvs_priv_get_date_status(string $date_str): string
{
    $date = new DateTimeImmutable($date_str);
    $now = new DateTimeImmutable('today');

    if ($date < $now) {
        return 'grey';
    }

    if (mkwvs_priv_is_date_blocked($date_str)) {
        return 'grey';
    }

    $day_of_week = (int) $date->format('N');
    $month = (int) $date->format('n');
    $diff = $now->diff($date)->days;

    if ($diff < 21) {
        $is_low_season = ($month >= 10 || $month <= 3);
        // Basse : jeu(4), ven(5) indispos — Haute : mer(3), jeu(4), ven(5) indispos
        $indispo_days = $is_low_season ? [4, 5] : [3, 4, 5];

        if (in_array($day_of_week, $indispo_days, true)) {
            return 'grey';
        }

        return 'red';
    }

    if ($day_of_week === 7) {
        $base_status = 'orange';
    } elseif ($day_of_week === 1 || $day_of_week === 2) {
        $base_status = 'green';
    } elseif ($day_of_week === 3) {
        $is_winter = ($month >= 10 || $month <= 3);
        $base_status = $is_winter ? 'green' : 'orange';
    } else {
        $base_status = 'orange';
    }

    return $base_status;
}

function mkwvs_priv_get_min_hour(string $date_str): ?int
{
    $date = new DateTimeImmutable($date_str);
    $now = new DateTimeImmutable('today');
    $diff = $now->diff($date)->days;

    if ($diff >= 21) {
        return null;
    }

    $day_of_week = (int) $date->format('N');

    // Dimanche : min 19h (les 2 saisons)
    if ($day_of_week === 7) {
        return 19;
    }

    return null;
}

function mkwvs_priv_get_max_hour(string $date_str): ?int
{
    $date = new DateTimeImmutable($date_str);
    $now = new DateTimeImmutable('today');
    $diff = $now->diff($date)->days;

    if ($diff >= 21) {
        return null;
    }

    $day_of_week = (int) $date->format('N');

    // Samedi : dispo de 08h à 16h (les 2 saisons)
    if ($day_of_week === 6) {
        return 16;
    }

    return null;
}

function mkwvs_priv_date_allows_reservation(string $date_str): bool
{
    $date = new DateTimeImmutable($date_str);
    $day_of_week = (int) $date->format('N');
    $month = (int) $date->format('n');

    // Jeu(4), Ven(5), Sam(6), Dim(7) : toujours
    if (in_array($day_of_week, [4, 5, 6, 7], true)) {
        return true;
    }

    // Mer(3) : belle saison uniquement (avril-septembre)
    if ($day_of_week === 3 && $month >= 4 && $month <= 9) {
        return true;
    }

    return false;
}

function mkwvs_priv_get_month_reservable(int $year, int $month): array
{
    $reservable = [];
    $days_in_month = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

    for ($day = 1; $day <= $days_in_month; $day++) {
        $date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);
        if (mkwvs_priv_date_allows_reservation($date_str)) {
            $reservable[$day] = true;
        }
    }

    return $reservable;
}

function mkwvs_priv_is_date_blocked(string $date_str): bool
{
    $blocked = get_option('priv_blocked_dates', []);

    foreach ($blocked as $period) {
        $start = $period['start'] ?? '';
        $end = $period['end'] ?? '';

        if ($start && $end && $date_str >= $start && $date_str <= $end) {
            return true;
        }
    }

    return false;
}

function mkwvs_priv_get_month_statuses(int $year, int $month): array
{
    $statuses = [];
    $days_in_month = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

    for ($day = 1; $day <= $days_in_month; $day++) {
        $date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $statuses[$day] = mkwvs_priv_get_date_status($date_str);
    }

    return $statuses;
}

function mkwvs_priv_get_month_min_hours(int $year, int $month): array
{
    $min_hours = [];
    $days_in_month = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

    for ($day = 1; $day <= $days_in_month; $day++) {
        $date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $hour = mkwvs_priv_get_min_hour($date_str);
        if ($hour !== null) {
            $min_hours[$day] = $hour;
        }
    }

    return $min_hours;
}

function mkwvs_priv_get_month_max_hours(int $year, int $month): array
{
    $max_hours = [];
    $days_in_month = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

    for ($day = 1; $day <= $days_in_month; $day++) {
        $date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $hour = mkwvs_priv_get_max_hour($date_str);
        if ($hour !== null) {
            $max_hours[$day] = $hour;
        }
    }

    return $max_hours;
}

function mkwvs_priv_generate_devis_number(int $post_id): string
{
    $date = get_the_date('Ymd', $post_id);
    $padded_id = str_pad((string) $post_id, 6, '0', STR_PAD_LEFT);
    return '#' . $date . '-' . $padded_id;
}

function mkwvs_priv_get_bookings_for_month(int $year, int $month): array
{
    $first_day = sprintf('%04d-%02d-01', $year, $month);
    $last_day = sprintf('%04d-%02d-%02d', $year, $month, (int) date('t', mktime(0, 0, 0, $month, 1, $year)));

    $query = new WP_Query([
        'post_type'      => 'privatisation',
        'post_status'    => 'priv_accepted',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'priv_date',
                'value'   => [$first_day, $last_day],
                'compare' => 'BETWEEN',
                'type'    => 'DATE',
            ],
        ],
    ]);

    $bookings = [];

    foreach ($query->posts as $post) {
        $date = get_field('priv_date', $post->ID);
        $day = (int) substr($date, -2);

        $bookings[$day][] = [
            'heure_debut' => (int) get_field('priv_heure_debut', $post->ID),
            'heure_fin'   => (int) get_field('priv_heure_fin', $post->ID),
        ];
    }

    wp_reset_postdata();

    return $bookings;
}

function mkwvs_priv_check_overlap(string $date, int $heure_debut, int $heure_fin, ?int $exclude_post_id = null): ?array
{
    $query_args = [
        'post_type'      => 'privatisation',
        'post_status'    => 'priv_accepted',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'priv_date',
                'value'   => $date,
                'compare' => '=',
            ],
        ],
    ];

    if ($exclude_post_id !== null) {
        $query_args['post__not_in'] = [$exclude_post_id];
    }

    $query = new WP_Query($query_args);

    $req_start = ($heure_debut === 0) ? 24 : $heure_debut;
    $req_end = ($heure_fin <= 2) ? $heure_fin + 24 : $heure_fin;

    foreach ($query->posts as $post) {
        $ex_debut = (int) get_field('priv_heure_debut', $post->ID);
        $ex_fin = (int) get_field('priv_heure_fin', $post->ID);

        $ex_start = ($ex_debut === 0) ? 24 : $ex_debut;
        $ex_end = ($ex_fin <= 2) ? $ex_fin + 24 : $ex_fin;

        // 1h buffer between bookings
        if ($req_start < ($ex_end + 1) && ($ex_start - 1) < $req_end) {
            wp_reset_postdata();
            return [
                'post_id'     => $post->ID,
                'heure_debut' => $ex_debut,
                'heure_fin'   => $ex_fin,
            ];
        }
    }

    wp_reset_postdata();

    return null;
}
