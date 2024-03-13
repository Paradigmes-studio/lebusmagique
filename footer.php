

<footer class="footer-adhesion">
  <h2 class="accroche">Envie de monter à bord ?</h2>
  <a href="<?php echo get_permalink(get_page_by_path('monter-a-bord')) ?>" class="cta cta-footer cta-decoration">J'adhère !
    <img class="cta-responsive-decoration" src="<?php echo get_stylesheet_directory_uri() . '/images/Groupe 396.svg'; ?>">
  </a>
  <!-- <img class="cta-decoration" src="<?php echo get_stylesheet_directory_uri() . '/images/footer-cta-decoration.png'; ?>"> -->

</footer>
<footer class="footer-location">
  <h2 class="accroche has-text-align-center">Psst ! La péniche a aussi son gîte !</h2>
  <a href="https://www.airbnb.com/h/lebusmagique" class="cta cta-footer cta-decoration" target="_blank">Je passe la nuit sur la péniche
  </a>
</footer>

<footer class="footer-network-news">
  <img class="footer-logo" src="<?php echo get_stylesheet_directory_uri() . '/images/Groupe 77.svg'; ?>">

  <div class="rubric menu">

    <h2>Menu</h2>
<?php $footer_menu_items = wp_get_nav_menu_items("navigation"); ?>
<?php foreach ($footer_menu_items as $footer_menu_item): ?>
<?php   if($footer_menu_item->type != "custom" && $footer_menu_item->title != "Contact"): ?>
    <p class="menu-links"><a href="<?php echo $footer_menu_item->url; ?>"><?php echo $footer_menu_item->title; ?></a></p>
<?php   endif; ?>
<?php endforeach; ?>

  </div>

  <div class="rubric contact">
    <h2>Contactez-nous</h2>
    <?php $contact_email = get_field('option_contact_email', 'option'); ?>
    <?php if($contact_email != ""): ?>
    <p><a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a></p>
    <?php endif; ?>
    <?php if (have_rows('option_contact_list', 'option')) : ?>
      <?php while (have_rows('option_contact_list', 'option')) : the_row(); ?>
        <!--<?php $contact_name = get_sub_field('option_contact_item_name', 'option'); ?>-->
        <?php $contact_phone = get_sub_field('option_contact_item_phone', 'option'); ?>
        <p><!--<?php echo $contact_name ?>--> <a href="tel:<?php echo $contact_phone; ?>"><?php echo $contact_phone; ?></a></p>
      <?php endwhile; ?>
    <?php endif; ?>
    <p class="address">
        <?php $contact_address = get_field('option_contact_address', 'option'); ?>
        <?php if($contact_address != ""): ?>
          <?php echo $contact_address; ?>
        <?php endif; ?>
        <?php $contact_zipcode = get_field('option_contact_zipcode', 'option'); ?>
        <?php if($contact_zipcode != ""): ?>
          <br/><?php echo $contact_zipcode; ?>
        <?php endif; ?>
        <?php $contact_city = get_field('option_contact_city', 'option'); ?>
        <?php if($contact_city != ""): ?>
           <?php echo $contact_city; ?>
        <?php endif; ?>
    </p>

    <div class="reseaux-sociaux">
      <?php if (have_rows('option_social_network_list', 'option')) : ?>
        <?php while (have_rows('option_social_network_list', 'option')) : the_row(); ?>
          <?php $social_network_name = get_sub_field('item_social_network_name', 'option'); ?>
          <?php $social_network_icon = get_sub_field('item_social_network_icon', 'option'); ?>
          <?php $social_network_url  = get_sub_field('item_social_network_url', 'option'); ?>
          <a href="<?php echo $social_network_url; ?>" target="_blank"><img class="link-social-responsive border-white-responsive" src="<?php echo $social_network_icon['url']; ?>"></a>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <div class="horaires">
      <p>
        Horaires
        <div class="horaires__list">
          <?php $contact_open_hours = get_field('option_open_hours', 'option'); ?>
          <?php if(!empty($contact_open_hours)) : ?>
          <?php echo apply_filters('the_content',$contact_open_hours); ?>
          <?php endif; ?>
        </div>
      </p>
    </div>

  </div>


  <div class="rubric newsletter">
    <h2>Newsletter</h2>
    <p> 1 mail chaque semaine, garanti sans spam et plein d'anecdotes !</p>
    <?php echo do_shortcode('[mp-mc-form list="102be40ec8" button="S\'inscrire" email_text="Ici ton email, et hop !" first_name_text="First Name" last_name_text="Last Name" placeholder="true" firstname="false" lastname="false" success="Merci pour votre inscription." failure="Une erreur est survenue. Veuillez ré-essayer." ]'); ?>
    <!--<form class="form-newsletter">
      <input class="post-email" placeholder="Adresse e-mail">
      <button><img class="icon-email" src="<?php echo get_stylesheet_directory_uri() . '/images/letter.svg'; ?>"></button>
    </form>-->

    <div class="reseaux-sociaux">
      <?php if (have_rows('option_social_network_list', 'option')) : ?>
        <?php while (have_rows('option_social_network_list', 'option')) : the_row(); ?>
          <?php $social_network_name = get_sub_field('item_social_network_name', 'option'); ?>
          <?php $social_network_icon = get_sub_field('item_social_network_icon', 'option'); ?>
          <?php $social_network_url  = get_sub_field('item_social_network_url', 'option'); ?>
          <a href="<?php echo $social_network_url; ?>" target="_blank"><img class="link-social border-white" src="<?php echo $social_network_icon['url']; ?>"></a>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

  </div>

</footer>

<div class="footer__misc">
  <ul>
    <li><a href="<?php echo get_permalink(get_page_by_path('mentions-legales')) ?>">Mentions légales</a></li>
    <li><a href="<?php echo get_permalink(get_page_by_path('confidentialité')) ?>">Confidentialité</a></li>
    <li>Site par <a href="https://atelier-jugeote.com/" target="_blank">Atelier Jugeote</a> &times; <a href="https://makewaves.fr/">Makewaves</a></li>
  </ul>
</div>


<script>
				var site_url = '<?php echo get_site_url(); ?>';
			</script>
        <?php wp_footer(); ?>

    </body>

</html>
