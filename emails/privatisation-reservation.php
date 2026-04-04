<?php
$type = get_field('priv_type', $post_id);
$nom = get_field('priv_nom', $post_id);
$prenom = get_field('priv_prenom', $post_id);
$organisation = get_field('priv_organisation', $post_id);
$date_event = get_field('priv_date', $post_id);
$heure_debut = get_field('priv_heure_debut', $post_id);
$heure_fin = get_field('priv_heure_fin', $post_id);
$nb_personnes = get_field('priv_nb_personnes', $post_id);
$description = get_field('priv_description', $post_id);

$heure_fin_label = ($heure_fin == 0) ? 'minuit' : $heure_fin . 'h';

if ($type === 'entreprise') {
    $salutation = 'Bonjour,';
} elseif ($type === 'association') {
    $salutation = 'Bonjour,';
} else {
    $salutation = 'Bonjour ' . esc_html($prenom) . ',';
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #9bb909;">
        <h1 style="font-size: 22px; color: #1a1a1a; margin: 0;">LE BUS MAGIQUE</h1>
    </div>

    <div style="padding: 25px 0;">
        <p><?php echo $salutation; ?></p>
        <p>Nous avons bien reçu votre demande de réservation d'espace à bord de la péniche.</p>

        <div style="background: #f8f4e8; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin: 0 0 10px; font-size: 15px;">Votre demande</h3>
            <p><strong>Date :</strong> <?php echo esc_html($date_event); ?></p>
            <p><strong>Horaires :</strong> <?php echo esc_html($heure_debut); ?>h à <?php echo esc_html($heure_fin_label); ?></p>
            <p><strong>Nombre de personnes :</strong> <?php echo esc_html($nb_personnes); ?></p>
            <?php if ($description): ?>
                <p><strong>Description :</strong> <?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>

        <p>Votre demande sera étudiée par notre équipe et <strong>nous vous répondrons sous 7 jours</strong>.</p>
        <p>N'hésitez pas à nous contacter pour toute question.</p>
        <p>À bientôt à bord !</p>
        <p><strong>L'équipe du Bus Magique</strong></p>
    </div>

    <div style="border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 11px; color: #999;">
        <p>Le Bus Magique — Façade de l'Esplanade - Avenue Cuvier, 59800 LILLE</p>
        <p>Tél : 06 98 30 07 86 — lebusmagique.lille@gmail.com</p>
    </div>
</body>
</html>
