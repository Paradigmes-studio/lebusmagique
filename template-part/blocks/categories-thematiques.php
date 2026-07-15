<?php
/**
 * Bloc "Nos événements par thématique" : 6 pastilles vers les pages catégories.
 * Affiché en haut de la page Programmation.
 */
if (!function_exists('mkwvs_event_categories')) {
    return;
}
$mkwvs_prog = get_page_by_path('programmation');
$mkwvs_prog_url = $mkwvs_prog ? trailingslashit(get_permalink($mkwvs_prog)) : home_url('/programmation/');
?>
<section class="bm-themes" aria-labelledby="bm-themes-title">
    <h2 id="bm-themes-title" class="bm-themes__title">Nos événements par thématique</h2>
    <div class="bm-themes__grid">
        <?php foreach (mkwvs_event_categories() as $cat) : ?>
            <a class="bm-theme" href="<?php echo esc_url($mkwvs_prog_url . $cat['page'] . '/'); ?>">
                <span class="bm-theme__circle">
                    <img class="bm-theme__frame" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/' . $cat['hublot'] . '-hublot.svg'); ?>" alt="" aria-hidden="true">
                    <img class="bm-theme__icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/' . $cat['icon']); ?>" alt="" aria-hidden="true">
                </span>
                <span class="bm-theme__label"><?php echo esc_html($cat['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
