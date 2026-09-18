<?php get_header(); ?>

<section class="o-wrapper o-landing">

	<h1><?php echo esc_html(is_category() ? single_cat_title('', false) : 'Blog'); ?></h1>

	<div class="c-list-blog">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('template-part/listes/liste', 'articles'); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<p>Aucun article pour le moment.</p>
	<?php endif; ?>
	</div>

</section>

<?php get_footer();
