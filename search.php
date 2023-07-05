<?php

get_header(); ?>



<section id="principal" class="wrapper">
	<main>
	
            <h2>Votre recherche pour "<?php echo get_search_query() ?>"</h2>
            <?php global $wp_query; ?>
            <h3>Voici les <?php echo $wp_query->total_post_count ?> résultats</h3>


            <?php get_template_part('template-part/archive/archive', 'search'); ?>
	           
	</main>
</section>


<?php get_footer();
