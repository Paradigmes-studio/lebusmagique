<?php
/**
 * Template Name: Page légale
 */
?>
<?php global $a_months; ?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php $toc = []; ?>
    <?php $content = mkwvs_legal_anchor_headings(apply_filters('the_content', get_the_content()), $toc); ?>

    <section class="legal-hero">
      <div class="legal-hero__inner">
        <h1><?php echo get_the_title(); ?></h1>
        <span class="legal-hero__rule" aria-hidden="true"></span>
        <p class="legal-hero__date">Mise à jour le <time datetime="<?php echo get_the_modified_date('Y-m-d'); ?>"><?php echo get_the_modified_date('j'); ?> <?php echo mb_strtolower($a_months[get_the_modified_date('m')]); ?> <?php echo get_the_modified_date('Y'); ?></time></p>
      </div>
    </section>

    <?php if (!empty($toc)) : ?>
      <nav class="legal-toc" aria-label="Sommaire de la page">
        <ul>
          <?php foreach ($toc as $item) : ?>
            <li><a href="#<?php echo esc_attr($item['id']); ?>"><?php echo esc_html($item['label']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <article class="legal-content">
      <?php echo $content; ?>
    </article>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer();
