<?php if (!is_page_template(MKWVS_PENICHE_PAGE_TEMPLATE)) : ?>
  <p class="has-text-align-center" style="max-width:760px;margin:0 auto 60px;padding:0 20px;line-height:1.6;">
    Le Bus Magique est une péniche de 1954 amarrée à l'entrée de la Citadelle de Lille.
    <a href="<?php echo esc_url(mkwvs_peniche_page_url()); ?>" data-umami-event="peniche-entree" data-umami-event-source="<?php echo esc_attr(get_post_field('post_name', get_the_ID()) ?: 'page'); ?>">Découvrir le bateau et ce qu'on y fait</a>.
  </p>
<?php endif; ?>
