
<?php get_header(); ?>

<section class="section-landing-standard">
  <div class="section-landing-top">
    <img class="top-image" src="<?php echo get_stylesheet_directory_uri() . '/images/la_peniche.png'; ?>">
    <div class="hublot">
      <img src="<?php echo get_stylesheet_directory_uri() . '/images/yellow-hublot.svg'; ?>">
      <h1 class="big-title"><img class="icon" src="<?php echo get_stylesheet_directory_uri() . '/images/hublot-icon-accueil.svg'; ?>">Bienvenu·e·s<br> à bord du <br>bus magique !</h1>
    </div>

    <div class="text-yellow-background top">
      <div class="icon-top-landing">
        <img src="<?php echo get_stylesheet_directory_uri() . '/images/ancre.svg'; ?>">
      </div>
      <p class="to_show">Scrollez jeunesse !</p>
    </div>
  </div>

  <div class="text-yellow-background bottom">
    <p>Embarquement immédiat vers votre tiers-lieu convivial et participatif sur une jolie péniche amarrée à l’entrée de la Citadelle de Lille. </p>
  </div>

</section>

<section class="section-content section-content--white">
  <h2>Prog' de la semaine</h2>
  <div class="events">
    <div class="event">
      Concert de La Rue qui t'Emmerde
    </div>
    <div class="event">
      Atelier écolo DIY
    </div>
    <div class="event">
      Cours de cuisine japonaise vegan
    </div>
  </div>
  <a href="<?php echo get_permalink(get_page_by_path("programmation")); ?>">Voir la programmation</a>
</section>


<section class="section-content section-content--beige">

    <h2>Que faire à bord Capitaine ?</h2>

    <?php $hp = get_page_by_path('accueil'); ?>

    <?php $image = get_field('page_home_encart_1_image', $hp->ID); ?>
    <?php $titre = get_field('page_home_encart_1_titre', $hp->ID); ?>
    <?php $description = get_field('page_home_encart_1_description', $hp->ID); ?>
    <?php $libelle = get_field('page_home_encart_1_libelle', $hp->ID); ?>
    <?php $link = get_field('page_home_encart_1_link', $hp->ID); ?>
    <div class="bloc-media-text">
      <div class="bloc-media-text__media">
        <img src="<?php echo $image['url'] ?>" alt="">
      </div>
      <div class="bloc-media-text__content">
        <h3><?php echo $titre; ?></h3>
        <?php echo apply_filters('the_content', $description) ?>
        <a href="<?php echo $link; ?>" class="cta cta--jungle-green"><?php echo $libelle; ?></a>
      </div>
    </div>


    <?php $image = get_field('page_home_encart_2_image', $hp->ID); ?>
    <?php $titre = get_field('page_home_encart_2_titre', $hp->ID); ?>
    <?php $description = get_field('page_home_encart_2_description', $hp->ID); ?>
    <?php $libelle = get_field('page_home_encart_2_libelle', $hp->ID); ?>
    <?php $link = get_field('page_home_encart_2_link', $hp->ID); ?>
    <div class="bloc-media-text bloc-media-text--right">
      <div class="bloc-media-text__media">
        <img src="<?php echo $image['url'] ?>" alt="">
      </div>
      <div class="bloc-media-text__content">
        <h3><?php echo $titre; ?></h3>
        <?php echo apply_filters('the_content', $description) ?>
        <a href="<?php echo $link; ?>" class="cta cta--red"><?php echo $libelle; ?></a>
      </div>
    </div>

    <?php $image = get_field('page_home_encart_3_image', $hp->ID); ?>
    <?php $titre = get_field('page_home_encart_3_titre', $hp->ID); ?>
    <?php $description = get_field('page_home_encart_3_description', $hp->ID); ?>
    <?php $libelle = get_field('page_home_encart_3_libelle', $hp->ID); ?>
    <?php $link = get_field('page_home_encart_3_link', $hp->ID); ?>
    <div class="bloc-media-text">
      <div class="bloc-media-text__media">
        <img src="<?php echo $image['url'] ?>" alt="">
      </div>
      <div class="bloc-media-text__content">
        <h3><?php echo $titre; ?></h3>
        <?php echo apply_filters('the_content', $description) ?>
        <a href="<?php echo $link; ?>" class="cta cta--green"><?php echo $libelle; ?></a>
      </div>
    </div>

    <div class="bloc-fullwidth">
      <img src="<?php echo get_stylesheet_directory_uri() . '/images/hp-map@2x.jpg'; ?>" alt="">
    </div>

</section>
<?php get_footer(); ?>
