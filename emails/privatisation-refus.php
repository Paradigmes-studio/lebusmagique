<?php
$type = get_field('priv_type', $post_id);
$nom = get_field('priv_nom', $post_id);
$prenom = get_field('priv_prenom', $post_id);
$organisation = get_field('priv_organisation', $post_id);
$date_event = get_field('priv_date', $post_id);

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

        <p>Nous avons bien étudié votre demande de privatisation pour le <strong><?php echo esc_html($date_event); ?></strong>.</p>

        <p>Malheureusement, nous ne sommes pas en mesure de donner une suite favorable à votre demande pour cette date.</p>

        <p>Nous vous invitons à consulter notre calendrier pour trouver une date alternative qui pourrait vous convenir. N'hésitez pas à soumettre une nouvelle demande.</p>

        <p>Nous espérons avoir le plaisir de vous accueillir prochainement à bord !</p>

        <p><strong>L'équipe du Bus Magique</strong></p>
    </div>

    <div style="border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 11px; color: #999;">
        <p>Le Bus Magique — Façade de l'Esplanade - Avenue Cuvier, 59800 LILLE</p>
        <p>Tél : 06 98 30 07 86 — lebusmagique.lille@gmail.com</p>
    </div>
</body>
</html>
