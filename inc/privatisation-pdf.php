<?php

declare(strict_types=1);

use Dompdf\Dompdf;
use Dompdf\Options;

function mkwvs_priv_generate_pdf(int $post_id, array $quote): string
{
    require_once get_template_directory() . '/vendor/autoload.php';
    $devis_number = get_field('priv_numero_devis', $post_id);

    ob_start();
    include get_template_directory() . '/template-part/privatisation/pdf-template.php';
    $html = ob_get_clean();

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Helvetica');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $upload_dir = wp_upload_dir();
    $year = date('Y');
    $dir = $upload_dir['basedir'] . '/privatisations/' . $year;
    if (!is_dir($dir)) {
        wp_mkdir_p($dir);
    }

    $filename = 'devis-' . $post_id . '-' . date('Ymd') . '.pdf';
    $filepath = $dir . '/' . $filename;

    file_put_contents($filepath, $dompdf->output());

    return $filepath;
}
