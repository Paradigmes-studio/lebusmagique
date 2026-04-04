<?php
$mode = get_field('priv_mode', $post_id) ?: 'privatisation';
$type = get_field('priv_type', $post_id);
$nom = get_field('priv_nom', $post_id);
$prenom = get_field('priv_prenom', $post_id);
$email_client = get_field('priv_email', $post_id);
$telephone = get_field('priv_telephone', $post_id);
$organisation = get_field('priv_organisation', $post_id);
$date_event = get_field('priv_date', $post_id);
$heure_debut = get_field('priv_heure_debut', $post_id);
$heure_fin = get_field('priv_heure_fin', $post_id);
$nb_personnes = get_field('priv_nb_personnes', $post_id);
$description = get_field('priv_description', $post_id);
$montant_ht = get_field('priv_montant_ht', $post_id);
$montant_ttc = get_field('priv_montant_ttc', $post_id);
$date_status = get_field('priv_date_status', $post_id);

$heure_fin_label = ($heure_fin == 0) ? 'minuit' : $heure_fin . 'h';

$edit_url = admin_url('post.php?post=' . $post_id . '&action=edit');

// Check for other requests on the same date
$existing = new WP_Query([
    'post_type'   => 'privatisation',
    'post_status' => ['priv_pending', 'priv_accepted'],
    'meta_query'  => [
        [
            'key'   => 'priv_date',
            'value' => $date_event,
        ],
    ],
    'post__not_in' => [$post_id],
    'fields'       => 'ids',
]);
$other_count = $existing->found_posts;
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <?php if ($is_urgent): ?>
    <div style="background: #e6534e; color: #fff; padding: 12px; text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 20px;">
        URGENT — Demande à moins de 3 semaines
    </div>
    <?php endif; ?>

    <h2 style="color: #1a1a1a;">Nouvelle demande <?php echo $mode === 'reservation' ? 'de réservation d\'espace' : 'de privatisation'; ?></h2>

    <?php if ($mode === 'reservation'): ?>
    <div style="background: #d1ecf1; padding: 10px; margin-bottom: 15px; border-radius: 5px; color: #0c5460;">
        <strong>Réservation d'espace</strong> — Le demandeur souhaite réserver un espace pendant les heures d'ouverture (pas de privatisation complète).
    </div>
    <?php endif; ?>

    <div style="background: #f8f4e8; padding: 15px; margin: 15px 0; border-radius: 5px;">
        <h3 style="margin: 0 0 10px;">Demandeur</h3>
        <p><strong>Type :</strong> <?php echo esc_html(ucfirst($type)); ?></p>
        <p><strong>Nom :</strong> <?php echo esc_html($prenom . ' ' . $nom); ?></p>
        <?php if ($organisation): ?>
            <p><strong>Organisation :</strong> <?php echo esc_html($organisation); ?></p>
        <?php endif; ?>
        <p><strong>Email :</strong> <a href="mailto:<?php echo esc_attr($email_client); ?>"><?php echo esc_html($email_client); ?></a></p>
        <p><strong>Téléphone :</strong> <?php echo esc_html($telephone); ?></p>
    </div>

    <div style="background: #f4f5f9; padding: 15px; margin: 15px 0; border-radius: 5px;">
        <h3 style="margin: 0 0 10px;">Évènement</h3>
        <p><strong>Date :</strong> <?php echo esc_html($date_event); ?>
            <span style="display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; color: #fff; background: <?php echo $date_status === 'green' ? '#9bb909' : ($date_status === 'red' ? '#e6534e' : '#ec6620'); ?>;">
                <?php echo $date_status === 'green' ? 'DISPO' : ($date_status === 'red' ? 'URGENT' : 'À ÉTUDIER'); ?>
            </span>
        </p>
        <p><strong>Horaires :</strong> <?php echo esc_html($heure_debut); ?>h à <?php echo esc_html($heure_fin_label); ?></p>
        <p><strong>Nombre de personnes :</strong> <?php echo esc_html($nb_personnes); ?></p>
        <?php if ($description): ?>
            <p><strong>Description :</strong> <?php echo esc_html($description); ?></p>
        <?php endif; ?>
        <?php $opt_autre = get_field('priv_opt_autre', $post_id); if ($opt_autre): ?>
            <p><strong>Autre service souhaité :</strong> <?php echo esc_html($opt_autre); ?></p>
        <?php endif; ?>
    </div>

    <p><strong>Montant HT :</strong> <?php echo number_format((float)$montant_ht, 2, ',', ' '); ?> € — <strong>Montant TTC :</strong> <?php echo number_format((float)$montant_ttc, 2, ',', ' '); ?> €</p>

    <?php if ($other_count > 0): ?>
    <div style="background: #ffd202; padding: 10px; margin: 15px 0; border-radius: 5px; color: #1a1a1a;">
        <strong>Attention :</strong> <?php echo $other_count; ?> autre(s) demande(s) existe(nt) déjà pour cette date.
    </div>
    <?php endif; ?>

    <p style="margin-top: 20px;">
        <a href="<?php echo esc_url($edit_url); ?>" style="display: inline-block; background: #9bb909; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Voir la demande dans l'admin
        </a>
    </p>
</body>
</html>
