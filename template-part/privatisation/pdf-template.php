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
$heure_debut_label = $heure_debut . 'h';

$client_name = ($type !== 'particulier' && $organisation) ? $organisation : $prenom . ' ' . $nom;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px 40px; }
    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #9bb909; padding-bottom: 15px; }
    .header h1 { font-size: 22px; color: #1a1a1a; margin: 0 0 5px; }
    .header p { margin: 2px 0; font-size: 10px; color: #666; }
    .meta-row { display: table; width: 100%; margin-bottom: 20px; }
    .meta-left, .meta-right { display: table-cell; width: 50%; vertical-align: top; }
    .meta-right { text-align: right; }
    .client-box { background: #f8f4e8; padding: 12px 15px; margin-bottom: 20px; }
    .client-box h3 { margin: 0 0 5px; font-size: 13px; color: #1a1a1a; }
    .client-box p { margin: 2px 0; }
    .quote-title { font-size: 14px; font-weight: bold; color: #1a1a1a; margin-bottom: 15px; }
    table.quote-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    table.quote-table th { background: #1a1a1a; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
    table.quote-table th:last-child, table.quote-table td:last-child { text-align: right; }
    table.quote-table th:nth-child(2), table.quote-table td:nth-child(2),
    table.quote-table th:nth-child(3), table.quote-table td:nth-child(3) { text-align: center; }
    table.quote-table td { padding: 7px 10px; border-bottom: 1px solid #e0e0e0; }
    table.quote-table tr:nth-child(even) td { background: #fafafa; }
    .totals { width: 250px; margin-left: auto; margin-bottom: 25px; }
    .totals table { width: 100%; border-collapse: collapse; }
    .totals td { padding: 5px 10px; }
    .totals td:last-child { text-align: right; font-weight: bold; }
    .totals tr.total-ttc { background: #9bb909; color: #fff; font-size: 13px; }
    .totals tr.total-ttc td { padding: 8px 10px; }
    .payment { background: #f4f5f9; padding: 15px; margin-bottom: 20px; font-size: 10px; }
    .payment h4 { margin: 0 0 8px; font-size: 11px; }
    .payment p { margin: 3px 0; }
    .validity { font-style: italic; font-size: 10px; margin-bottom: 30px; }
    .footer { border-top: 1px solid #ccc; padding-top: 10px; text-align: center; font-size: 9px; color: #999; }
    .footer p { margin: 2px 0; }
</style>
</head>
<body>
    <div class="header">
        <h1>LE BUS MAGIQUE</h1>
        <p>Facade de l'Esplanade - Avenue Cuvier, 59800 LILLE</p>
        <p>Tel : 06 98 30 07 86 — lebusmagique.lille@gmail.com</p>
    </div>

    <div class="meta-row">
        <div class="meta-left">
            <div class="client-box">
                <h3><?php echo esc_html($client_name); ?></h3>
                <?php if ($type !== 'particulier' && $organisation): ?>
                    <p><?php echo esc_html($prenom . ' ' . $nom); ?></p>
                <?php endif; ?>
                <p>Profil : <?php echo esc_html(ucfirst($type)); ?></p>
            </div>
        </div>
        <div class="meta-right">
            <p><strong>Devis n° <?php echo esc_html($devis_number); ?></strong></p>
            <p>Date : <?php echo date('d/m/Y'); ?></p>
        </div>
    </div>

    <p class="quote-title">
        <?php if ($description): ?>
            <?php echo esc_html($description); ?> —
        <?php endif; ?>
        Privatisation du <?php echo esc_html($date_event); ?> de <?php echo esc_html($heure_debut_label); ?> à <?php echo esc_html($heure_fin_label); ?>
        <br><small><?php echo esc_html($nb_personnes); ?> personne<?php echo $nb_personnes > 1 ? 's' : ''; ?></small>
    </p>

    <table class="quote-table">
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Qté</th>
                <th>P.U. HT</th>
                <th>Total HT</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quote['lines'] as $line): ?>
            <tr>
                <td><?php echo esc_html($line['designation']); ?></td>
                <td><?php echo esc_html($line['qty']); ?></td>
                <td><?php echo number_format($line['unit_price'], 2, ',', ' '); ?> €</td>
                <td><?php echo number_format($line['total'], 2, ',', ' '); ?> €</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Total HT</td>
                <td><?php echo number_format($quote['total_ht'], 2, ',', ' '); ?> €</td>
            </tr>
            <?php if ($quote['tva_10'] > 0): ?>
            <tr>
                <td>TVA 10%</td>
                <td><?php echo number_format($quote['tva_10'], 2, ',', ' '); ?> €</td>
            </tr>
            <?php endif; ?>
            <?php if ($quote['tva_20'] > 0): ?>
            <tr>
                <td>TVA 20%</td>
                <td><?php echo number_format($quote['tva_20'], 2, ',', ' '); ?> €</td>
            </tr>
            <?php endif; ?>
            <tr class="total-ttc">
                <td>Total TTC</td>
                <td><?php echo number_format($quote['total_ttc'], 2, ',', ' '); ?> €</td>
            </tr>
        </table>
    </div>

    <div class="payment">
        <h4>Modalités de règlement</h4>
        <p><strong>30% d'arrhes à la réservation</strong></p>
        <p>Par chèque à l'ordre de : LE BUS MAGIQUE</p>
        <p>Par virement : compte n° 31566832153</p>
        <p>IBAN : FR76 1350 7000 2031 5668 3215 393</p>
        <p>BIC : CCBPFRPPLIL</p>
    </div>

    <p class="validity">Devis valable 30 jours à réception.</p>

    <div class="footer">
        <p>LE BUS MAGIQUE</p>
        <p>SIRET : 84018125900036 — RCS : RNA:W595031094 — NAF : 5610C</p>
        <p>TVA Intracom : FR50840181259</p>
    </div>
</body>
</html>
