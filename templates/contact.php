<?php
/**
 * Template Name: Contact
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

      <section class="section-landing-standard section-landing-contact">

        <div class="section-landing-top o-contact">

          <div class="o-contact__address">
            
          </div>

          <div class="o-contact__mail">
            <?php $contact_email = get_field('option_contact_email', 'option'); ?>
            <?php if($contact_email != ""): ?>
            <p><a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a></p>
            <?php endif; ?>
          </div>

          <div class="o-contact__photo">
            <img src="<?php echo get_stylesheet_directory_uri() . '/images/hublot-photo.png'; ?>" alt="">
            <img src="<?php echo get_stylesheet_directory_uri() . '/images/deco-contact.svg'; ?>" alt="">
          </div>

          <div class="o-contact__phones">
            <?php if (have_rows('option_contact_list', 'option')) : ?>
            <ul>
            <?php while (have_rows('option_contact_list', 'option')) : the_row(); ?>
              <?php $contact_name = get_sub_field('option_contact_item_name', 'option'); ?>
              <?php $contact_phone = get_sub_field('option_contact_item_phone', 'option'); ?>
              <li>
                <span class="o-contact__name"><?php echo $contact_name ?></span><br>
                <a href="tel:<?php echo $contact_phone; ?>"><?php echo $contact_phone; ?></a>
              </li>
            <?php endwhile; ?>
            </ul>
            <?php endif; ?>
            <p>
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
          </div>


          <div class="text-yellow-background top">
            <?php $icon_scroll= get_field('page_head_icon_scroll'); ?>
            <?php $accroche_scroll = get_field('page_head_accroche_scroll'); ?>
            <img class="icon-top-landing" src="<?php echo $icon_scroll['url']; ?>">
            <p class="to_show"><?php echo $accroche_scroll; ?></p>
          </div>

        </div>

        <div class="text-yellow-background bottom">
          <?php $subtitle= get_field('page_head_subtitle'); ?>
          <?php if (!empty($subtitle)) : ?>
            <h2><?php echo $subtitle; ?></h2>
          <?php endif; ?>
          <?php $description= get_field('page_head_description'); ?>
          <?php if (!empty($description)) : ?>
            <?php echo apply_filters('the_content', $description); ?>
          <?php endif; ?>
        </div>


      </section>


      <section>

        <?php $contact_form_page = get_page_by_path('formulaire-de-contact-1', 'OBJECT', 'wpcf7_contact_form'); ?>
        <?php echo do_shortcode('[contact-form-7 id="'.$contact_form_page->ID.'" ]'); ?>

      </section>

    <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
