<?php get_header(); ?>

<section class="o-wrapper o-landing">

	<h1>Blog</h1>

	<div class="c-list-blog">
	<?php echo get_template_part('template-part/listes/liste', 'articles'); ?>
	</div>

</section>

<?php get_footer();
