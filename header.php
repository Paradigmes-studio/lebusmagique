<?php // Foool Header ?>
<!DOCTYPE html>
<html lang="fr" class="loading">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />

        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() . '/img/favicon.png' ?>" />
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon/site.webmanifest">
        <link rel="mask-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        
        <script type="text/javascript" src="https://booking.ureserve.co/shop/external-booking/js/le-bus-magique.js" crossorigin="anonymous"></script>
        <?php wp_head(); ?>

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body <?php body_class(); ?>>

    <header>
      <a href="<?php echo get_home_url('/'); ?>"><img class="icon-bus" src="<?php echo get_stylesheet_directory_uri() . '/images/icon-bus.svg'; ?>"></a>
      <button id="menu-toggle" class="version standard"><svg xmlns="http://www.w3.org/2000/svg" id="svg4503" version="1.1" viewBox="0 0 19.128 15.61" height="15.61" width="19.128">
          <defs id="defs4507" />
          <g id="g4501">
            <path id="squiggly-1"
                  d="m 17.168,0.76500003 c -1.742684,-1.0201435 -3.900316,-1.0201435 -5.643,0 C 10.932145,1.1232263 10.256426,1.3213591 9.5639996,1.34 8.8678646,1.339758 8.1863496,1.1402308 7.5999996,0.76500003 6.7427926,0.27019943 5.7717416,0.00658893 4.7819996,2.5251277e-8 3.7906086,0.00140003 2.8172736,0.26534613 1.9609996,0.76500003 1.3705586,1.1286813 0.6933406,1.3272535 -4e-7,1.34 v 1.845 c 0.990178,-0.010305 1.961273,-0.2736471 2.821,-0.765 0.590541,-0.3633299 1.267752,-0.5615548 1.961,-0.574 0.694587,0.00347 1.374169,0.2023918 1.961,0.574 0.856274,0.4996539 1.829609,0.7636033 2.821,0.765 C 10.55476,3.17893 11.52691,2.9153023 12.385,2.42 c 0.592951,-0.3578753 1.268664,-0.5556616 1.961,-0.574 0.695015,5.157e-4 1.375403,0.1996707 1.961,0.574 0.856827,0.4983317 1.829799,0.7621828 2.821,0.765 V 1.32 C 18.436417,1.31622 17.758891,1.1243715 17.168,0.76500003 Z" />
            <path id="squiggly-2"
                  d="m 17.168,13.19 c -1.742684,-1.020144 -3.900316,-1.020144 -5.643,0 -0.592855,0.358226 -1.268574,0.556359 -1.961,0.575 C 8.867865,13.764758 8.18635,13.565231 7.6,13.19 6.742793,12.695199 5.771742,12.431589 4.782,12.425 3.790609,12.4264 2.817274,12.690346 1.961,13.19 1.370559,13.553681 0.693341,13.752253 0,13.765 v 1.845 c 0.990178,-0.0103 1.961273,-0.273647 2.821,-0.765 0.590541,-0.36333 1.267752,-0.561555 1.961,-0.574 0.694587,0.0035 1.374169,0.202392 1.961,0.574 0.856274,0.499654 1.829609,0.763603 2.821,0.765 0.99076,-0.0061 1.96291,-0.269698 2.821,-0.765 0.592951,-0.357875 1.268664,-0.555662 1.961,-0.574 0.695015,5.16e-4 1.375403,0.199671 1.961,0.574 0.856827,0.498332 1.829799,0.762183 2.821,0.765 v -1.865 c -0.691583,-0.0038 -1.369109,-0.195629 -1.96,-0.555 z" />
            <path id="squiggly-3"
                  d="m 17.168,6.9775002 c -1.742684,-1.020144 -3.900316,-1.020144 -5.643,0 -0.592855,0.358226 -1.268574,0.556359 -1.961,0.575 -0.696135,-2.42e-4 -1.37765,-0.199769 -1.964,-0.575 -0.857207,-0.494801 -1.828258,-0.758411 -2.818,-0.765 -0.991391,0.0014 -1.964726,0.265346 -2.821,0.765 -0.590441,0.363681 -1.267659,0.562253 -1.961,0.575 v 1.845 c 0.990178,-0.0103 1.961273,-0.273647 2.821,-0.765 0.590541,-0.36333 1.267752,-0.561555 1.961,-0.574 0.694587,0.0035 1.374169,0.202392 1.961,0.574 0.856274,0.499654 1.829609,0.763603 2.821,0.765 0.99076,-0.0061 1.96291,-0.269698 2.821,-0.765 0.592951,-0.357875 1.268664,-0.555662 1.961,-0.574 0.695015,5.16e-4 1.375403,0.199671 1.961,0.574 0.856827,0.498332 1.829799,0.762183 2.821,0.765 v -1.865 c -0.691583,-0.0038 -1.369109,-0.195629 -1.96,-0.555 z" />
          </g>
        </svg>
      </button>
      <section class="volet-menu">
        <div class="volet-menu__navigation">
          <?php wp_nav_menu( array('menu' => 'navigation') ); ?>
          <!-- <div class="volet-menu__navigation-left">
            <a href="<?php echo get_home_url('/') . '/programmation'; ?>"><h2 class="volet-menu__titles">Programmation</h2></a>
            <a href="<?php echo get_home_url('/') . '/restauration'; ?>"><h2 class="volet-menu__titles">Restauration</h2></a>
            <a href="<?php echo get_home_url('/') . '/coworking'; ?>"><h2 class="volet-menu__titles">Coworking</h2></a>
            <a href="<?php echo get_home_url('/') . '/location'; ?>"><h2 class="volet-menu__titles">Location</h2></a>
          </div>
          <div class="volet-menu__navigation-right">
              <span>
                <h2 class="volet-menu__titles">Qui sommes-nous ?</h2>
                <ul>
                  <li><a href="<?php echo get_home_url('/') . '/association'; ?>"><h2 class="volet-menu__titles">L'Association</h2></a></li>
                  <li><a href="<?php echo get_home_url('/') . '/notre-histoire'; ?>"><h2 class="volet-menu__titles">Notre histoire</h2></a></li>
                  <li><a href="<?php echo get_home_url('/') . '/monter-a-bord'; ?>"><h2 class="volet-menu__titles">Le Bus m'agite</h2></a></li>
                </ul>
              </span>
            <a href="contact.html"><h2 class="volet-menu__titles">Contact</h2></a>
          </div> -->
        </div>

        <div class="reseaux-sociaux">
          <?php if (have_rows('option_social_network_list', 'option')) : ?>
            <?php while (have_rows('option_social_network_list', 'option')) : the_row(); ?>
              <?php $social_network_name = get_sub_field('item_social_network_name', 'option'); ?>
              <?php $social_network_icon = get_sub_field('item_social_network_icon', 'option'); ?>
              <?php $social_network_url  = get_sub_field('item_social_network_url', 'option'); ?>
              <a href="<?php echo $social_network_url; ?>" target="_blank"><img class="link-social" src="<?php echo $social_network_icon['url']; ?>" ></a>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>

        <footer class="footer-location">
          <h2 class="accroche has-text-align-center">Psst ! La péniche a aussi son gîte !</h2>
          <a href="https://www.sejour-insolite-peniche-lille.com/" class="cta cta-footer cta-decoration" target="_blank">Je passe la nuit sur la péniche
          </a>
        </footer>
      </section>

    </header>
