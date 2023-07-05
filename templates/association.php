<?php
/**
 * Template Name: Association
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <section class="section-landing-standard">

        <?php include(locate_template('template-part/blocks/page-head.php')); ?>

    </section>

    <?php if( have_rows('page_services_list')) : ?>
      <ul class="list-items">
        <?php while(have_rows('page_services_list')) : the_row(); ?>
          <?php $icon = get_sub_field('page_services_item_icon'); ?>
          <?php $name = get_sub_field('page_services_item_name'); ?>
          <?php $descriptif = get_sub_field('page_services_item_descriptif'); ?>
          <li class="items"><img class="icon" src="<?php echo $icon['url']; ?>"><h2><?php echo $name; ?></h2><p><?php echo $descriptif; ?></p></li>
        <?php endwhile; ?>
      </ul>
    <?php endif; ?>

    <section class="section-bggreen">
      <p>Le Bus Magique c’est avant tout une association qui carbure à certaines valeurs qui lui sont essentielles : le bien-être, le respect de l’environnement, le lien social, le soutien de l’économie locale et la culture. Il est ouvert à toutes et à tous et, dans cet esprit, fait la part belle aux initiatives de chacun. Sans ce souffle collectif et cette envie commune, notre péniche n’aurait pas été bien loin ! Retrouve toutes les aventures qui l’ont menée jusqu’ici dans la rubrique “histoire de la péniche”. Nous continuons à écrire cette histoire avec celles et ceux qui souhaitent nous aider à faire vivre ce lieu magique...
        <br><br><br>Si tu partages nos valeurs et que tu souhaites partager ton énergie, tes inspirations et ton temps pour mettre encore plus de magie dans ce bus (qui n’en est pas un 😉), dans ce bus (qui n'en est pas un ;) ), tu peux prendre connaissance des projets en cours et contacter leurs référents !
      </p>
      <a class="cta" href="<?php echo get_permalink(get_page_by_path('contact')); ?>">
        nous contacter
      </a>

    </section>

    <?php if (have_rows('page_projet_list')) : ?>
    <section>
      <h2 class="has-text-align-center">Nos projets en cours</h2>

      <div class="o-projects swiper-container">

        <div class="swiper-wrapper">
          <?php while (have_rows('page_projet_list')) : the_row(); ?>
            <?php $title = get_sub_field('page_projet_item_title'); ?>
            <?php $description = get_sub_field('page_projet_item_description'); ?>
            <?php $email = get_sub_field('page_projet_item_email'); ?>
            <?php $couleur = get_sub_field('page_projet_item_color'); ?>
            <?php $image = get_sub_field('page_projet_item_image'); ?>
            <?php $image_profil = get_sub_field('page_projet_item_image_profil'); ?>
          <div class="o-projects__item swiper-slide o-projects__item--<?php echo $couleur; ?>">

            <div class="o-projects__item-header">
              <h3><?php echo $title; ?></h3>
              <p><?php echo $description; ?></p>
              <p class="o-projects__item-contact">
                <?php if($image_profil != '') { ?>
                <span class="o-projects__item-profile">
                    <img src="<?php echo $image_profil['url']; ?>" alt="<?php echo $title; ?>">
                </span>
              <?php } ?>
                <span class="o-projects__item-contact-text">
                  En savoir plus&nbsp;:<br>
                  <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                </span>
              </p>
            </div>

            <div class="o-projects__item-content">
              <div class="o-projects__item-image">
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $title; ?>">
              </div>
              <!-- <span class="spacer"></span> -->
            </div>

          </div>
          <?php endwhile; ?>



        </div>

        <div class="o-slider__navigation">
          <button class="o-slider__prev"><?php locate_template( 'images/fleche.svg', true, false ); ?></button>
          <button class="o-slider__next"><?php locate_template( 'images/fleche.svg', true, false ); ?></button>
        </div>

      </div>

    </section>
    <?php endif; ?>


    <section>
      <h2 class="has-text-align-center">Le trombi' de l'équipage</h2>
      <p style="text-align:center">Les photos arrivent bientôt... encore un peu de patience !!!</p>
    <?php if (have_rows('page_trombi_list')) : ?>
      <?php while (have_rows('page_trombi_list')) : the_row(); ?>
        <?php $name = get_sub_field('page_trombi_item_name'); ?>
        <?php $function = get_sub_field('page_trombi_item_function'); ?>
        <?php $image = get_sub_field('page_trombi_item_image'); ?>

        <img src="<?php echo $image['url']; ?>" alt="<?php echo $name; ?>" />
        <p><?php echo $name; ?></p>
        <p><?php echo $function; ?></p>
      <?php endwhile; ?>
    <?php endif; ?>
    </section>



  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
