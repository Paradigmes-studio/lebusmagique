<?php
/**
 * Bloc de maillage vers la page « Notre péniche à Lille ».
 * Sort sans rien afficher tant que la migration n'est pas chargée : pendant un
 * déploiement, ce gabarit peut arriver avant le functions.php qui la requiert.
 */
if (!function_exists('mkwvs_peniche_page_url') || is_page_template('templates/peniche-lille.php')) {
    return;
}
?>
<p class="has-text-align-center" style="max-width:760px;margin:0 auto 60px;padding:0 20px;line-height:1.6;">
  Le Bus Magique est une péniche de 1954 amarrée à l'entrée de la Citadelle de Lille.
  <a href="<?php echo esc_url(mkwvs_peniche_page_url()); ?>" data-umami-event="peniche-entree" data-umami-event-source="<?php echo esc_attr(get_post_field('post_name', get_the_ID()) ?: 'page'); ?>">Découvrir le bateau et ce qu'on y fait</a>.
</p>
