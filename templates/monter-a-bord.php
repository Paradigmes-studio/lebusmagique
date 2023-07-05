<?php
/**
 * Template Name: Monter à bord
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <section class="section-landing-standard">

        <?php include(locate_template('template-part/blocks/page-head.php')); ?>

    </section>

    <section class="o-wrapper o-page-content">

      <?php echo apply_filters('the_content', get_the_content()); ?>

    </section>

    <?php if( have_rows('page_prestation_list')) : ?>
      <section class="sections-price">
        <div class="sections-price__header">
          <h2 class="has-text-align-center">Comment adhérer</h2>
          <p class="has-text-align-center">L'adhésion se fait via le formulaire ci-dessous. Mais il faut être physiquement à bord de notre beau navire où un serveur/un bénévole te donnera le code de validation pour bien enregistrer ton adhésion. Et donc, si tu lis cette page depuis ton canapé, viens boire un verre avec nous!</p>
        </div>
        <div class="sections-price__list">
		<a href="#adhesion">
     	   <?php while(have_rows('page_prestation_list')) : the_row(); ?>
          	 <?php $color = get_sub_field('page_prestation_item_color'); ?>
			  <?php $icon = get_sub_field('page_prestation_item_icon'); ?>
			  <?php $toptitle = get_sub_field('page_prestation_item_toptitle'); ?>
			  <?php $title = get_sub_field('page_prestation_item_title'); ?>
			  <?php $details = get_sub_field('page_prestation_item_details'); ?>
			  <?php $price = get_sub_field('page_prestation_item_price'); ?>
			  <section class="section-price <?php echo $color; ?>">
				  <div class="font-icon <?php echo $color; ?>">
					  <img src="<?php echo $icon['url']; ?>">
				  </div>
				  <p class="text-font category <?php echo $color; ?>"><?php echo $toptitle; ?></p>
				  <h2 class="activity"><?php echo $title; ?></h2>
				  <div class="price price-<?php echo $color; ?>">
					  <p class="text-font <?php echo $color; ?>"><?php echo $price; ?></p>
		  </a>
            </div>
          </section>
        <?php endwhile; ?>
        </div>
      </section>
    <?php endif; ?>

    <section>
		<a name="adhesion">
      <h2 class="has-text-align-center">Notre formulaire d'adhésion</h2> </a>
	   <iframe src="https://lebusmagiquelille.fr/adhesion/createAdhesionClient.php" title="Notre formulaire d'adhésion" width="100%" height="900" style="border-radius: 60px;border:9px solid rgb(155, 185, 9);"></iframe>
   <!-- 
  <iframe id="haWidget" allowtransparency="true" scrolling="auto" src="https://www.helloasso.com/associations/le-bus-magique/adhesions/j-adhere-au-bus-magique-et-j-embarque/widget" style="width:100%;height:750px;border:none;" onload="window.scroll(0, this.offsetTop)"></iframe><div style="width:100%;text-align:center;">Propulsé par <a href="https://www.helloasso.com" rel="nofollow">HelloAsso</a></div> -->
    </section>
	
    <section>
		<a name="don">
      <h2 class="has-text-align-center">Faire un don via HelloAsso</h2>
	<p class="has-text-align-center">Si tu es déjà adhérent ou si tu ne souhaites pas adhérer mais que tu veux tout de même nous soutenir financièrement, je ne dirais qu'un mot : merci beaucoup de croire en nous et pour ta bienveillance ! (Je sais, ça fait un peu plus qu'un seul mot, mais en réalité, il nous en faudrait mille pour t'exprimer notre gratitude...)</p>
		
		<div class="has-text-align-center">
		<?php echo do_shortcode('[helloasso campaign="https://www.helloasso.com/associations/le-bus-magique/formulaires/1/" type="widget-bouton"]'); ?>
	</div>
		</a>
    </section>
  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
