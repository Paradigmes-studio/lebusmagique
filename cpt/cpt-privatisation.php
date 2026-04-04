<?php

declare(strict_types=1);

add_action('init', 'mkwvs_create_post_type_privatisation');

function mkwvs_create_post_type_privatisation(): void
{
    $labels = [
        'name'               => 'Privatisations',
        'singular_name'      => 'Privatisation',
        'menu_name'          => 'Privatisations',
        'all_items'          => 'Toutes les demandes',
        'view_item'          => 'Voir la demande',
        'add_new_item'       => 'Ajouter une demande',
        'add_new'            => 'Ajouter',
        'edit_item'          => 'Modifier la demande',
        'update_item'        => 'Mettre à jour',
        'search_items'       => 'Rechercher',
        'not_found'          => 'Aucune demande',
        'not_found_in_trash' => 'Aucune demande dans la corbeille',
    ];

    $args = [
        'label'               => 'Privatisations',
        'description'         => 'Demandes de privatisation de la péniche',
        'labels'              => $labels,
        'supports'            => ['title'],
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => false,
        'menu_position'       => 7,
        'menu_icon'           => 'dashicons-calendar-alt',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    ];

    register_post_type('privatisation', $args);
}

add_action('init', 'mkwvs_register_privatisation_statuses');

function mkwvs_register_privatisation_statuses(): void
{
    register_post_status('priv_pending', [
        'label'                     => 'En attente',
        'public'                    => false,
        'internal'                  => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('En attente <span class="count">(%s)</span>', 'En attente <span class="count">(%s)</span>'),
    ]);

    register_post_status('priv_accepted', [
        'label'                     => 'Acceptée',
        'public'                    => false,
        'internal'                  => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Acceptée <span class="count">(%s)</span>', 'Acceptées <span class="count">(%s)</span>'),
    ]);

    register_post_status('priv_refused', [
        'label'                     => 'Refusée',
        'public'                    => false,
        'internal'                  => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Refusée <span class="count">(%s)</span>', 'Refusées <span class="count">(%s)</span>'),
    ]);
}

add_action('admin_footer-post.php', 'mkwvs_privatisation_status_dropdown');
add_action('admin_footer-post-new.php', 'mkwvs_privatisation_status_dropdown');

function mkwvs_privatisation_status_dropdown(): void
{
    global $post;

    if ($post->post_type !== 'privatisation') {
        return;
    }

    $statuses = [
        'priv_pending'  => 'En attente',
        'priv_accepted' => 'Acceptée',
        'priv_refused'  => 'Refusée',
    ];

    echo '<script>
    jQuery(document).ready(function($) {';

    foreach ($statuses as $value => $label) {
        $selected = ($post->post_status === $value) ? ' selected="selected"' : '';
        echo '$("select#post_status").append("<option value=\"' . $value . '\"' . $selected . '>' . $label . '</option>");';

        if ($post->post_status === $value) {
            echo '$("#post-status-display").text("' . $label . '");';
        }
    }

    echo '});
    </script>';
}

add_filter('display_post_states', 'mkwvs_privatisation_display_states', 10, 2);

function mkwvs_privatisation_display_states(array $states, \WP_Post $post): array
{
    if ($post->post_type !== 'privatisation') {
        return $states;
    }

    $custom_states = [
        'priv_pending'  => 'En attente',
        'priv_accepted' => 'Acceptée',
        'priv_refused'  => 'Refusée',
    ];

    if (isset($custom_states[$post->post_status])) {
        $states[$post->post_status] = $custom_states[$post->post_status];
    }

    return $states;
}

// Hide "Statut de la date" field in admin (only used by front-end form)
add_filter('acf/prepare_field/name=priv_date_status', '__return_false');

// Admin metabox — Accept/Refuse actions
add_action('add_meta_boxes', 'mkwvs_priv_add_action_metabox');

add_action('admin_notices', 'mkwvs_priv_admin_overlap_notice');

function mkwvs_priv_admin_overlap_notice(): void
{
    if (!isset($_GET['priv_error'])) {
        return;
    }

    $message = sanitize_text_field(wp_unslash($_GET['priv_error']));
    echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($message) . '</p></div>';
}

function mkwvs_priv_add_action_metabox(): void
{
    add_meta_box(
        'priv_actions',
        'Actions',
        'mkwvs_priv_render_action_metabox',
        'privatisation',
        'side',
        'high'
    );
}

function mkwvs_priv_render_action_metabox(\WP_Post $post): void
{
    if ($post->post_status !== 'priv_pending') {
        $statuses = [
            'priv_accepted' => 'Acceptée',
            'priv_refused'  => 'Refusée',
        ];
        echo '<p><strong>Statut :</strong> ' . ($statuses[$post->post_status] ?? $post->post_status) . '</p>';
        return;
    }

    $accept_url = wp_nonce_url(
        admin_url('admin-post.php?action=priv_accept&post_id=' . $post->ID),
        'priv_accept_' . $post->ID
    );
    $refuse_url = wp_nonce_url(
        admin_url('admin-post.php?action=priv_refuse&post_id=' . $post->ID),
        'priv_refuse_' . $post->ID
    );

    echo '<a href="' . esc_url($accept_url) . '" class="button button-primary" style="margin-right:10px;">Accepter</a>';
    echo '<a href="' . esc_url($refuse_url) . '" class="button" style="color:red;">Refuser</a>';
}

add_action('admin_post_priv_accept', 'mkwvs_priv_handle_accept');

function mkwvs_priv_handle_accept(): void
{
    $post_id = (int) ($_GET['post_id'] ?? 0);

    if (!current_user_can('edit_post', $post_id)) {
        wp_die('Accès refusé');
    }

    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'priv_accept_' . $post_id)) {
        wp_die('Nonce invalide');
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'privatisation' || $post->post_status !== 'priv_pending') {
        wp_die('Demande invalide');
    }

    $priv_date = get_field('priv_date', $post_id);
    $priv_heure_debut = (int) get_field('priv_heure_debut', $post_id);
    $priv_heure_fin = (int) get_field('priv_heure_fin', $post_id);

    $overlap = mkwvs_priv_check_overlap($priv_date, $priv_heure_debut, $priv_heure_fin, $post_id);
    if ($overlap !== null) {
        $label_debut = ($overlap['heure_debut'] === 0) ? 'minuit' : $overlap['heure_debut'] . 'h';
        $label_fin = ($overlap['heure_fin'] === 0) ? 'minuit' : $overlap['heure_fin'] . 'h';
        wp_redirect(admin_url('post.php?post=' . $post_id . '&action=edit&priv_error=' . urlencode('Impossible d\'accepter : ce créneau chevauche une privatisation déjà acceptée de ' . $label_debut . ' à ' . $label_fin . '.')));
        exit;
    }

    $opt_data = [
        'nb_personnes'          => (int) get_field('priv_nb_personnes', $post_id),
        'heure_debut'           => (int) get_field('priv_heure_debut', $post_id),
        'heure_fin'             => (int) get_field('priv_heure_fin', $post_id),
        'opt_son_lumiere'       => get_field('priv_opt_son_lumiere', $post_id),
        'opt_artiste'           => get_field('priv_opt_artiste', $post_id),
        'opt_dj'                => get_field('priv_opt_dj', $post_id),
        'opt_tonnelle'          => get_field('priv_opt_tonnelle', $post_id),
        'opt_repas'             => get_field('priv_opt_repas', $post_id) ?: 'aucun',
        'opt_service_table'     => get_field('priv_opt_service_table', $post_id),
        'opt_boissons'          => get_field('priv_opt_boissons', $post_id),
        'opt_boissons_quantite' => (int) get_field('priv_opt_boissons_quantite', $post_id),
    ];

    $quote = mkwvs_priv_calculate_quote($opt_data);
    $pdf_path = mkwvs_priv_generate_pdf($post_id, $quote);

    update_field('priv_pdf_path', $pdf_path, $post_id);
    wp_update_post(['ID' => $post_id, 'post_status' => 'priv_accepted']);

    mkwvs_priv_send_confirmation_email($post_id, $pdf_path);

    wp_redirect(admin_url('post.php?post=' . $post_id . '&action=edit&message=1'));
    exit;
}

add_action('admin_post_priv_refuse', 'mkwvs_priv_handle_refuse');

function mkwvs_priv_handle_refuse(): void
{
    $post_id = (int) ($_GET['post_id'] ?? 0);

    if (!current_user_can('edit_post', $post_id)) {
        wp_die('Accès refusé');
    }

    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'priv_refuse_' . $post_id)) {
        wp_die('Nonce invalide');
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'privatisation' || $post->post_status !== 'priv_pending') {
        wp_die('Demande invalide');
    }

    wp_update_post(['ID' => $post_id, 'post_status' => 'priv_refused']);
    mkwvs_priv_send_refusal_email($post_id);

    wp_redirect(admin_url('post.php?post=' . $post_id . '&action=edit&message=1'));
    exit;
}

add_action('pre_get_posts', 'mkwvs_priv_admin_filter_statuses');

function mkwvs_priv_admin_filter_statuses(\WP_Query $query): void
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if (($query->get('post_type') ?? '') !== 'privatisation') {
        return;
    }

    if (!$query->get('post_status')) {
        $query->set('post_status', ['priv_pending', 'priv_accepted', 'priv_refused', 'trash']);
    }
}

// ============================================================
// Blocked dates admin page
// ============================================================

add_action('admin_menu', 'mkwvs_priv_blocked_dates_menu');

function mkwvs_priv_blocked_dates_menu(): void
{
    add_submenu_page(
        'edit.php?post_type=privatisation',
        'Dates bloquées',
        'Dates bloquées',
        'edit_posts',
        'priv-blocked-dates',
        'mkwvs_priv_blocked_dates_page'
    );
}

add_action('admin_menu', 'mkwvs_priv_tarifs_menu');

function mkwvs_priv_tarifs_menu(): void
{
    add_submenu_page(
        'edit.php?post_type=privatisation',
        'Tarifs',
        'Tarifs',
        'edit_posts',
        'priv-tarifs',
        'mkwvs_priv_tarifs_page'
    );
}

add_action('admin_post_priv_save_tarifs', 'mkwvs_priv_handle_save_tarifs');

function mkwvs_priv_handle_save_tarifs(): void
{
    if (!current_user_can('edit_posts')) {
        wp_die('Accès refusé');
    }

    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'priv_save_tarifs')) {
        wp_die('Nonce invalide');
    }

    $defaults = mkwvs_priv_get_default_tarifs();
    $tarifs = [];

    foreach (array_keys($defaults) as $key) {
        $val = $_POST['priv_tarif_' . $key] ?? '';
        if ($val !== '') {
            $tarifs[$key] = (float) $val;
        }
    }

    update_option('priv_tarifs', $tarifs);

    wp_redirect(admin_url('edit.php?post_type=privatisation&page=priv-tarifs&saved=1'));
    exit;
}

function mkwvs_priv_tarifs_page(): void
{
    $defaults = mkwvs_priv_get_default_tarifs();
    $saved = get_option('priv_tarifs', []);

    $groups = [
        'Location' => [
            'icon'   => 'dashicons-building',
            'desc'   => 'Tarifs horaires selon le créneau et le nombre de personnes',
            'fields' => [
                'location_9_minuit_small'  => ['label' => 'Location 9h-minuit', 'detail' => '< 50 personnes', 'unit' => '€/h HT'],
                'location_9_minuit_large'  => ['label' => 'Location 9h-minuit', 'detail' => '≥ 50 personnes', 'unit' => '€/h HT'],
                'location_minuit_2h_small' => ['label' => 'Location minuit-2h', 'detail' => '< 50 personnes', 'unit' => '€/h HT'],
                'location_minuit_2h_large' => ['label' => 'Location minuit-2h', 'detail' => '≥ 50 personnes', 'unit' => '€/h HT'],
            ],
        ],
        'Frais' => [
            'icon'   => 'dashicons-media-text',
            'desc'   => 'Frais fixes appliqués à chaque réservation',
            'fields' => [
                'frais_dossier' => ['label' => 'Frais de dossier', 'unit' => '€ HT'],
            ],
        ],
        'Options' => [
            'icon'   => 'dashicons-plus-alt',
            'desc'   => 'Prestations complémentaires au choix du client',
            'fields' => [
                'son_lumiere' => ['label' => 'Son et lumière', 'unit' => '€ HT'],
                'artiste'     => ['label' => 'Artiste', 'unit' => '€ HT'],
                'dj'          => ['label' => 'DJ set / Blindtest / Karaoké', 'unit' => '€ HT'],
                'tonnelle'    => ['label' => 'Tonnelle', 'unit' => '€ HT'],
            ],
        ],
        'Restauration' => [
            'icon'   => 'dashicons-food',
            'desc'   => 'Formules repas et boissons',
            'fields' => [
                'repas_2_plats' => ['label' => 'Entrée-plat ou plat-dessert', 'unit' => '€/pers HT'],
                'repas_3_plats' => ['label' => 'Entrée-plat-dessert', 'unit' => '€/pers HT'],
                'service_table' => ['label' => 'Service à table (3h)', 'unit' => '€ HT'],
                'boisson'       => ['label' => 'Boisson (vins, bières, softs)', 'unit' => '€/unité HT'],
            ],
        ],
    ];
    ?>
    <style>
        .priv-tarifs-wrap { max-width: 900px; }
        .priv-tarifs-wrap .wp-editor-container iframe { min-height: 400px !important; }
        .priv-tarifs-wrap .wp-editor-container textarea { min-height: 400px !important; }
        .priv-tarifs-wrap h1 { display: flex; align-items: center; gap: 8px; }
        .priv-tarifs-card {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            margin-bottom: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .priv-tarifs-card__header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            border-bottom: 1px solid #f0f0f1;
            background: #f6f7f7;
        }
        .priv-tarifs-card__header .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
            color: #2271b1;
        }
        .priv-tarifs-card__header h2 {
            margin: 0;
            padding: 0;
            font-size: 14px;
            font-weight: 600;
        }
        .priv-tarifs-card__header .description {
            margin-left: auto;
            font-style: italic;
            color: #787c82;
        }
        .priv-tarifs-card__body { padding: 0; }
        .priv-tarifs-row {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f1;
            gap: 12px;
        }
        .priv-tarifs-row:last-child { border-bottom: 0; }
        .priv-tarifs-row:hover { background: #f9f9f9; }
        .priv-tarifs-row__label {
            flex: 1;
            font-weight: 500;
            min-width: 0;
        }
        .priv-tarifs-row__detail {
            color: #787c82;
            font-size: 12px;
            font-weight: 400;
        }
        .priv-tarifs-row__input {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .priv-tarifs-row__input input[type="number"] {
            width: 80px;
            text-align: right;
            padding: 4px 8px;
        }
        .priv-tarifs-row__unit {
            color: #787c82;
            font-size: 12px;
            min-width: 70px;
        }
        .priv-tarifs-row__badge {
            font-size: 11px;
            background: #f0f6e9;
            color: #5a8006;
            padding: 2px 8px;
            border-radius: 10px;
            white-space: nowrap;
        }
    </style>
    <div class="wrap priv-tarifs-wrap">
        <h1><span class="dashicons dashicons-money-alt"></span> Tarifs de privatisation</h1>

        <?php if (isset($_GET['saved'])): ?>
            <div class="notice notice-success is-dismissible"><p>Tarifs enregistrés.</p></div>
        <?php endif; ?>

        <p class="description" style="margin-bottom: 20px;">Laissez un champ vide pour utiliser la valeur par défaut (indiquée en placeholder).</p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="priv_save_tarifs">
            <?php wp_nonce_field('priv_save_tarifs'); ?>

            <?php foreach ($groups as $group_label => $group): ?>
                <div class="priv-tarifs-card">
                    <div class="priv-tarifs-card__header">
                        <span class="dashicons <?php echo esc_attr($group['icon']); ?>"></span>
                        <h2><?php echo esc_html($group_label); ?></h2>
                        <span class="description"><?php echo esc_html($group['desc']); ?></span>
                    </div>
                    <div class="priv-tarifs-card__body">
                        <?php foreach ($group['fields'] as $key => $field): ?>
                            <div class="priv-tarifs-row">
                                <div class="priv-tarifs-row__label">
                                    <label for="priv_tarif_<?php echo $key; ?>">
                                        <?php echo esc_html($field['label']); ?>
                                        <?php if (!empty($field['detail'])): ?>
                                            <span class="priv-tarifs-row__detail">(<?php echo esc_html($field['detail']); ?>)</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <div class="priv-tarifs-row__input">
                                    <input type="number" step="0.01" min="0"
                                           name="priv_tarif_<?php echo $key; ?>"
                                           id="priv_tarif_<?php echo $key; ?>"
                                           value="<?php echo isset($saved[$key]) ? esc_attr($saved[$key]) : ''; ?>"
                                           placeholder="<?php echo esc_attr($defaults[$key]); ?>">
                                    <span class="priv-tarifs-row__unit"><?php echo esc_html($field['unit']); ?></span>
                                </div>
                                <?php if (isset($saved[$key])): ?>
                                    <span class="priv-tarifs-row__badge">Modifié (défaut : <?php echo esc_html($defaults[$key]); ?>)</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php submit_button('Enregistrer les tarifs'); ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'mkwvs_priv_emails_menu');

function mkwvs_priv_emails_menu(): void
{
    add_submenu_page(
        'edit.php?post_type=privatisation',
        'Emails',
        'Emails',
        'edit_posts',
        'priv-emails',
        'mkwvs_priv_emails_page'
    );
}

add_action('admin_post_priv_save_emails', 'mkwvs_priv_handle_save_emails');

function mkwvs_priv_handle_save_emails(): void
{
    if (!current_user_can('edit_posts')) {
        wp_die('Accès refusé');
    }

    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'priv_save_emails')) {
        wp_die('Nonce invalide');
    }

    $typed_keys = ['confirmation', 'reception', 'refus'];
    $types = ['particulier', 'entreprise', 'association'];
    $templates = get_option('priv_email_templates', []);

    foreach ($typed_keys as $key) {
        if (!isset($templates[$key]) || !is_array($templates[$key])) {
            $templates[$key] = [];
        }
        foreach ($types as $type) {
            $subject = sanitize_text_field(wp_unslash($_POST['priv_email_subject_' . $key . '_' . $type] ?? ''));
            $body = wp_kses_post(wp_unslash($_POST['priv_email_body_' . $key . '_' . $type] ?? ''));

            if ($subject || $body) {
                $templates[$key][$type] = [
                    'subject' => $subject,
                    'body'    => $body,
                ];
            }
        }
    }

    $notif_subject = sanitize_text_field(wp_unslash($_POST['priv_email_subject_notification'] ?? ''));
    $notif_body = wp_kses_post(wp_unslash($_POST['priv_email_body_notification'] ?? ''));
    if ($notif_subject || $notif_body) {
        $templates['notification'] = [
            'subject' => $notif_subject,
            'body'    => $notif_body,
        ];
    }

    update_option('priv_email_templates', $templates);

    wp_redirect(admin_url('edit.php?post_type=privatisation&page=priv-emails&saved=1'));
    exit;
}

add_action('admin_post_priv_reset_email', 'mkwvs_priv_handle_reset_email');

function mkwvs_priv_handle_reset_email(): void
{
    if (!current_user_can('edit_posts')) {
        wp_die('Accès refusé');
    }

    $key = sanitize_text_field($_GET['email_key'] ?? '');
    $type = sanitize_text_field($_GET['email_type'] ?? '');
    $valid_keys = ['confirmation', 'reception', 'refus', 'notification'];
    $typed_keys = ['confirmation', 'reception', 'refus'];
    $valid_types = ['particulier', 'entreprise', 'association'];

    if (!in_array($key, $valid_keys, true)) {
        wp_die('Clé invalide');
    }

    $nonce_key = $type ? 'priv_reset_email_' . $key . '_' . $type : 'priv_reset_email_' . $key;
    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', $nonce_key)) {
        wp_die('Nonce invalide');
    }

    $templates = get_option('priv_email_templates', []);

    if (in_array($key, $typed_keys, true) && in_array($type, $valid_types, true)) {
        unset($templates[$key][$type]);
    } else {
        unset($templates[$key]);
    }

    update_option('priv_email_templates', $templates);

    wp_redirect(admin_url('edit.php?post_type=privatisation&page=priv-emails&reset=1'));
    exit;
}

function mkwvs_priv_emails_page(): void
{
    $templates = get_option('priv_email_templates', []);
    $variables_config = mkwvs_priv_get_email_variables_config();
    $defaults = mkwvs_priv_get_email_defaults();

    $tabs = [
        'confirmation' => 'Confirmation',
        'reception'    => 'Accusé de réception',
        'refus'        => 'Refus',
        'notification' => 'Notification admin',
    ];

    $typed_keys = ['confirmation', 'reception', 'refus'];
    $types = ['particulier' => 'Particulier', 'entreprise' => 'Entreprise', 'association' => 'Association'];

    $default_subjects = [
        'confirmation' => 'Votre devis de privatisation — Le Bus Magique',
        'reception'    => 'Votre demande de privatisation — Le Bus Magique',
        'refus'        => 'Votre demande de privatisation — Le Bus Magique',
        'notification' => 'Nouvelle demande de privatisation',
    ];

    $active_tab = sanitize_text_field($_GET['tab'] ?? 'confirmation');
    if (!isset($tabs[$active_tab])) {
        $active_tab = 'confirmation';
    }
    ?>
    <div class="wrap">
        <h1>Emails de privatisation</h1>

        <?php if (isset($_GET['saved'])): ?>
            <div class="notice notice-success is-dismissible"><p>Emails enregistrés.</p></div>
        <?php endif; ?>
        <?php if (isset($_GET['reset'])): ?>
            <div class="notice notice-success is-dismissible"><p>Email réinitialisé (le template par défaut sera utilisé).</p></div>
        <?php endif; ?>

        <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs as $key => $label): ?>
                <?php
                $has_custom = false;
                if (in_array($key, $typed_keys, true)) {
                    foreach (array_keys($types) as $t) {
                        if (!empty($templates[$key][$t]['body'])) {
                            $has_custom = true;
                            break;
                        }
                    }
                } else {
                    $has_custom = !empty($templates[$key]['body']);
                }
                ?>
                <a href="?post_type=privatisation&page=priv-emails&tab=<?php echo $key; ?>"
                   class="nav-tab <?php echo $active_tab === $key ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html($label); ?>
                    <?php if ($has_custom): ?>
                        <span style="color: #9bb909; margin-left: 4px;" title="Contenu personnalisé">&#9679;</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </h2>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="priv_save_emails">
            <?php wp_nonce_field('priv_save_emails'); ?>

            <?php foreach ($tabs as $key => $label):
                $display = ($key === $active_tab) ? '' : 'display: none;';
                $is_typed = in_array($key, $typed_keys, true);
            ?>
                <div id="tab-<?php echo $key; ?>" style="<?php echo $display; ?>">

                    <?php if ($is_typed): ?>
                        <div class="priv-subtabs" style="margin: 15px 0; border-bottom: 1px solid #ccc; padding-bottom: 0;">
                            <?php foreach ($types as $type_key => $type_label): ?>
                                <a href="#" class="priv-subtab" data-tab="<?php echo $key; ?>" data-type="<?php echo $type_key; ?>"
                                   style="display: inline-block; padding: 8px 16px; margin-bottom: -1px; text-decoration: none; color: #333; border: 1px solid transparent; border-bottom-color: #ccc; <?php echo $type_key === 'particulier' ? 'border: 1px solid #ccc; border-bottom-color: #fff; background: #fff; font-weight: 600;' : ''; ?>">
                                    <?php echo esc_html($type_label); ?>
                                    <?php if (!empty($templates[$key][$type_key]['body'])): ?>
                                        <span style="color: #9bb909; margin-left: 2px;" title="Contenu personnalisé">&#9679;</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <?php foreach ($types as $type_key => $type_label):
                            $tpl = $templates[$key][$type_key] ?? [];
                            $subject = $tpl['subject'] ?? '';
                            $body = $tpl['body'] ?? $defaults[$key][$type_key] ?? '';
                            $is_custom = !empty($tpl['body']);
                            $editor_id = 'priv_email_body_' . $key . '_' . $type_key;
                            $sub_display = ($type_key === 'particulier') ? '' : 'display: none;';
                            $reset_url = wp_nonce_url(
                                admin_url('admin-post.php?action=priv_reset_email&email_key=' . $key . '&email_type=' . $type_key),
                                'priv_reset_email_' . $key . '_' . $type_key
                            );
                        ?>
                            <div class="priv-subpanel" data-tab="<?php echo $key; ?>" data-type="<?php echo $type_key; ?>" style="<?php echo $sub_display; ?>">
                                <table class="form-table">
                                    <tr>
                                        <th><label for="priv_email_subject_<?php echo $key . '_' . $type_key; ?>">Objet</label></th>
                                        <td>
                                            <input type="text" name="priv_email_subject_<?php echo $key . '_' . $type_key; ?>"
                                                   id="priv_email_subject_<?php echo $key . '_' . $type_key; ?>"
                                                   class="large-text"
                                                   value="<?php echo esc_attr($subject); ?>"
                                                   placeholder="<?php echo esc_attr($default_subjects[$key]); ?>">
                                            <p class="description">Laissez vide pour utiliser l'objet par défaut : <?php echo esc_html($default_subjects[$key]); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label>Corps de l'email</label></th>
                                        <td>
                                            <?php
                                            wp_editor($body, $editor_id, [
                                                'textarea_name' => $editor_id,
                                                'textarea_rows'  => 30,
                                                'teeny'          => true,
                                                'media_buttons'  => false,
                                            ]);
                                            ?>
                                            <?php if (!$is_custom): ?>
                                                <p class="description">Le template par défaut est actuellement utilisé. Saisissez du contenu pour le personnaliser.</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>

                                <div style="background: #f0f0f1; padding: 15px; border-radius: 4px; margin: 10px 0 20px;">
                                    <h4 style="margin: 0 0 10px;">Variables disponibles <small>(cliquez pour copier)</small></h4>
                                    <?php foreach ($variables_config[$key] as $var => $desc): ?>
                                        <code style="cursor: pointer; display: inline-block; margin: 2px 4px; padding: 3px 8px; background: #fff; border: 1px solid #ccc; border-radius: 3px;"
                                              onclick="navigator.clipboard.writeText('<?php echo esc_js($var); ?>'); this.style.borderColor='#9bb909'; var el=this; setTimeout(function(){el.style.borderColor='#ccc';}, 500);"
                                              title="<?php echo esc_attr($desc); ?>">
                                            <?php echo esc_html($var); ?>
                                        </code>
                                        <span style="font-size: 12px; color: #666;"><?php echo esc_html($desc); ?></span><br>
                                    <?php endforeach; ?>
                                </div>

                                <?php if ($is_custom): ?>
                                    <p>
                                        <a href="<?php echo esc_url($reset_url); ?>"
                                           class="button"
                                           onclick="return confirm('Réinitialiser cet email ? Le template par défaut sera utilisé.');">
                                            Réinitialiser « <?php echo esc_html($label . ' — ' . $type_label); ?> »
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <?php
                        $subject = $templates[$key]['subject'] ?? '';
                        $body = $templates[$key]['body'] ?? $defaults[$key] ?? '';
                        $is_custom = !empty($templates[$key]['body']);
                        $reset_url = wp_nonce_url(
                            admin_url('admin-post.php?action=priv_reset_email&email_key=' . $key),
                            'priv_reset_email_' . $key
                        );
                        ?>
                        <table class="form-table">
                            <tr>
                                <th><label for="priv_email_subject_<?php echo $key; ?>">Objet</label></th>
                                <td>
                                    <input type="text" name="priv_email_subject_<?php echo $key; ?>"
                                           id="priv_email_subject_<?php echo $key; ?>"
                                           class="large-text"
                                           value="<?php echo esc_attr($subject); ?>"
                                           placeholder="<?php echo esc_attr($default_subjects[$key]); ?>">
                                    <p class="description">Laissez vide pour utiliser l'objet par défaut : <?php echo esc_html($default_subjects[$key]); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label>Corps de l'email</label></th>
                                <td>
                                    <?php
                                    wp_editor($body, 'priv_email_body_' . $key, [
                                        'textarea_name' => 'priv_email_body_' . $key,
                                        'textarea_rows'  => 30,
                                        'teeny'          => true,
                                        'media_buttons'  => false,
                                    ]);
                                    ?>
                                    <?php if (!$is_custom): ?>
                                        <p class="description">Le template par défaut est actuellement utilisé. Saisissez du contenu pour le personnaliser.</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>

                        <div style="background: #f0f0f1; padding: 15px; border-radius: 4px; margin: 10px 0 20px;">
                            <h4 style="margin: 0 0 10px;">Variables disponibles <small>(cliquez pour copier)</small></h4>
                            <?php foreach ($variables_config[$key] as $var => $desc): ?>
                                <code style="cursor: pointer; display: inline-block; margin: 2px 4px; padding: 3px 8px; background: #fff; border: 1px solid #ccc; border-radius: 3px;"
                                      onclick="navigator.clipboard.writeText('<?php echo esc_js($var); ?>'); this.style.borderColor='#9bb909'; var el=this; setTimeout(function(){el.style.borderColor='#ccc';}, 500);"
                                      title="<?php echo esc_attr($desc); ?>">
                                    <?php echo esc_html($var); ?>
                                </code>
                                <span style="font-size: 12px; color: #666;"><?php echo esc_html($desc); ?></span><br>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($is_custom): ?>
                            <p>
                                <a href="<?php echo esc_url($reset_url); ?>"
                                   class="button"
                                   onclick="return confirm('Réinitialiser cet email ? Le template par défaut sera utilisé.');">
                                    Réinitialiser « <?php echo esc_html($label); ?> »
                                </a>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>

            <?php submit_button('Enregistrer les emails'); ?>
        </form>
    </div>

    <script>
    jQuery(function($) {
        // Main tab navigation
        $('.nav-tab-wrapper .nav-tab').on('click', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var tab = new URLSearchParams(href.split('?')[1]).get('tab');

            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');

            $('[id^="tab-"]').hide();
            $('#tab-' + tab).show();

            history.replaceState(null, '', href);
        });

        // Sub-tab navigation for typed emails
        $('.priv-subtab').on('click', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            var type = $(this).data('type');

            // Update sub-tab styles
            $(this).siblings('.priv-subtab').css({
                'border': '1px solid transparent',
                'border-bottom-color': '#ccc',
                'background': 'none',
                'font-weight': 'normal'
            });
            $(this).css({
                'border': '1px solid #ccc',
                'border-bottom-color': '#fff',
                'background': '#fff',
                'font-weight': '600'
            });

            // Show/hide sub-panels
            $('.priv-subpanel[data-tab="' + tab + '"]').hide();
            $('.priv-subpanel[data-tab="' + tab + '"][data-type="' + type + '"]').show();

            // Resize TinyMCE editors hidden at init
            var editorId = 'priv_email_body_' + tab + '_' + type;
            if (typeof tinymce !== 'undefined') {
                var editor = tinymce.get(editorId);
                if (editor) {
                    editor.theme.resizeTo('100%', 500);
                }
            }
        });
    });
    </script>
    <?php
}

// ============================================================
// Blocked dates — form handlers
// ============================================================

add_action('admin_post_priv_block_dates', 'mkwvs_priv_handle_block_dates');

function mkwvs_priv_handle_block_dates(): void
{
    if (!current_user_can('edit_posts')) {
        wp_die('Accès refusé');
    }

    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'priv_block_dates')) {
        wp_die('Nonce invalide');
    }

    $start = sanitize_text_field($_POST['date_start'] ?? '');
    $end = sanitize_text_field($_POST['date_end'] ?? '');
    $reason = sanitize_text_field($_POST['reason'] ?? '');

    if (!$start || !$end) {
        wp_redirect(admin_url('edit.php?post_type=privatisation&page=priv-blocked-dates&error=missing'));
        exit;
    }

    if ($end < $start) {
        wp_redirect(admin_url('edit.php?post_type=privatisation&page=priv-blocked-dates&error=order'));
        exit;
    }

    $blocked = get_option('priv_blocked_dates', []);
    $blocked[] = [
        'start'  => $start,
        'end'    => $end,
        'reason' => $reason,
    ];
    update_option('priv_blocked_dates', $blocked);

    wp_redirect(admin_url('edit.php?post_type=privatisation&page=priv-blocked-dates&success=1'));
    exit;
}

add_action('admin_post_priv_unblock_dates', 'mkwvs_priv_handle_unblock_dates');

function mkwvs_priv_handle_unblock_dates(): void
{
    if (!current_user_can('edit_posts')) {
        wp_die('Accès refusé');
    }

    $index = (int) ($_GET['index'] ?? -1);

    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'priv_unblock_' . $index)) {
        wp_die('Nonce invalide');
    }

    $blocked = get_option('priv_blocked_dates', []);
    if (isset($blocked[$index])) {
        array_splice($blocked, $index, 1);
        update_option('priv_blocked_dates', $blocked);
    }

    wp_redirect(admin_url('edit.php?post_type=privatisation&page=priv-blocked-dates&unblocked=1'));
    exit;
}

function mkwvs_priv_blocked_dates_page(): void
{
    $blocked = get_option('priv_blocked_dates', []);

    // Sort by start date (display only, no DB write)
    usort($blocked, fn($a, $b) => $a['start'] <=> $b['start']);

    $today = date('Y-m-d');
    ?>
    <div class="wrap">
        <h1>Dates bloquées</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="notice notice-success is-dismissible"><p>Période bloquée avec succès.</p></div>
        <?php endif; ?>
        <?php if (isset($_GET['unblocked'])): ?>
            <div class="notice notice-success is-dismissible"><p>Période débloquée.</p></div>
        <?php endif; ?>
        <?php if (($_GET['error'] ?? '') === 'missing'): ?>
            <div class="notice notice-error is-dismissible"><p>Les dates de début et de fin sont obligatoires.</p></div>
        <?php endif; ?>
        <?php if (($_GET['error'] ?? '') === 'order'): ?>
            <div class="notice notice-error is-dismissible"><p>La date de fin doit être après la date de début.</p></div>
        <?php endif; ?>

        <h2>Bloquer une période</h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-bottom: 30px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
            <input type="hidden" name="action" value="priv_block_dates">
            <?php wp_nonce_field('priv_block_dates'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="date_start">Date de début</label></th>
                    <td><input type="date" name="date_start" id="date_start" required min="<?php echo $today; ?>"></td>
                </tr>
                <tr>
                    <th><label for="date_end">Date de fin</label></th>
                    <td><input type="date" name="date_end" id="date_end" required min="<?php echo $today; ?>"></td>
                </tr>
                <tr>
                    <th><label for="reason">Raison</label></th>
                    <td><input type="text" name="reason" id="reason" class="regular-text" placeholder="Ex : Fermeture annuelle, évènement privé..."></td>
                </tr>
            </table>
            <?php submit_button('Bloquer cette période', 'primary', 'submit', false); ?>
        </form>

        <h2>Périodes bloquées</h2>
        <?php if (empty($blocked)): ?>
            <p>Aucune période bloquée.</p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 20%;">Date de début</th>
                        <th style="width: 20%;">Date de fin</th>
                        <th>Raison</th>
                        <th style="width: 15%;">Statut</th>
                        <th style="width: 12%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blocked as $i => $period):
                        $is_past = $period['end'] < $today;
                        $start_formatted = date('d/m/Y', strtotime($period['start']));
                        $end_formatted = date('d/m/Y', strtotime($period['end']));
                        $unblock_url = wp_nonce_url(
                            admin_url('admin-post.php?action=priv_unblock_dates&index=' . $i),
                            'priv_unblock_' . $i
                        );
                    ?>
                        <tr<?php echo $is_past ? ' style="opacity: 0.5;"' : ''; ?>>
                            <td><?php echo esc_html($start_formatted); ?></td>
                            <td><?php echo esc_html($end_formatted); ?></td>
                            <td><?php echo esc_html($period['reason'] ?: '—'); ?></td>
                            <td>
                                <?php if ($is_past): ?>
                                    <span style="color: #999;">Passée</span>
                                <?php else: ?>
                                    <span style="color: #e6534e; font-weight: 600;">Active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($unblock_url); ?>"
                                   class="button button-small"
                                   onclick="return confirm('Débloquer cette période ?');">
                                    Débloquer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

// Custom admin columns
add_filter('manage_privatisation_posts_columns', 'mkwvs_priv_admin_columns');

function mkwvs_priv_admin_columns(array $columns): array
{
    $new_columns = [];
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['priv_date'] = 'Date évènement';
    $new_columns['priv_type'] = 'Type';
    $new_columns['priv_nb'] = 'Personnes';
    $new_columns['priv_montant'] = 'Montant TTC';
    $new_columns['priv_status'] = 'Statut';
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}

add_action('manage_privatisation_posts_custom_column', 'mkwvs_priv_admin_column_content', 10, 2);

function mkwvs_priv_admin_column_content(string $column, int $post_id): void
{
    switch ($column) {
        case 'priv_date':
            echo esc_html(get_field('priv_date', $post_id));
            break;
        case 'priv_type':
            echo esc_html(ucfirst(get_field('priv_type', $post_id) ?? ''));
            break;
        case 'priv_nb':
            echo esc_html(get_field('priv_nb_personnes', $post_id));
            break;
        case 'priv_montant':
            $montant = get_field('priv_montant_ttc', $post_id);
            echo $montant ? number_format((float) $montant, 2, ',', ' ') . ' €' : '-';
            break;
        case 'priv_status':
            $status = get_post_status($post_id);
            $labels = [
                'priv_pending'  => '<span style="color:orange;">En attente</span>',
                'priv_accepted' => '<span style="color:green;">Acceptée</span>',
                'priv_refused'  => '<span style="color:red;">Refusée</span>',
            ];
            echo $labels[$status] ?? $status;
            break;
    }
}

add_action('admin_init', 'mkwvs_priv_migrate_blocked_dates');

function mkwvs_priv_migrate_blocked_dates(): void
{
    if (get_option('priv_blocked_dates_migrated')) {
        return;
    }

    $blocked = get_option('priv_blocked_dates', []);
    $cleaned = array_values(array_filter($blocked, function ($period) {
        return !str_starts_with($period['reason'] ?? '', 'Privatisation : ');
    }));

    update_option('priv_blocked_dates', $cleaned);
    update_option('priv_blocked_dates_migrated', true);
}
