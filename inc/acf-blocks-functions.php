<?php

/**
 * Gutenberg scripts and styles
 * @link https://www.billerickson.net/block-styles-in-gutenberg/
 */
function be_gutenberg_scripts() {

    wp_enqueue_script(
        'mw-editor',
        get_stylesheet_directory_uri() . '/js/editor.js',
        // array( 'wp-blocks', 'wp-dom', 'acf-blocks', 'wp-color-picker', 'wp-tinymce', 'wp-embed', 'wp-components', 'wp-editor', 'wp-i18n' ),
        array( 'wp-blocks', 'wp-dom', 'acf-blocks' ),
        filemtime( get_stylesheet_directory() . '/js/editor.js' ),
        true
    );
}
add_action( 'enqueue_block_editor_assets', 'be_gutenberg_scripts', 99 );


function mkwvs_acf_block_enqueue_assets(){
    if(is_admin()){
        // wp_enqueue_style( 'block-video-with-image', get_template_directory_uri() . '/css/blocks/video-with-image.css' );
        wp_enqueue_style( 'editor-style-frontend', get_template_directory_uri() . '/css/editor.css' );
    }
}

// add_theme_support( 'editor-color-palette', array(
//     array(
//         'name' => __( 'Noir', 'loop' ),
//         'slug' => 'black',
//         'color' => '#000000',
//     ),
//     array(
//         'name' => __( 'Jaune', 'loop' ),
//         'slug' => 'yellow',
//         'color' => '#ffff4e',
//     ),
//     array(
//         'name' => __( 'Gris', 'loop' ),
//         'slug' => 'warm-grey',
//         'color' => '#949494',
//     ),
//     array(
//         'name' => __( 'Blanc', 'loop' ),
//         'slug' => 'white',
//         'color' => '#ffffff',
//     ),
// ) );

add_theme_support( 'editor-color-palette', array() );
add_theme_support( 'disable-custom-colors' );
// Adds support for editor font sizes.
// add_theme_support( 'editor-font-sizes', array(
//
// 	array(
// 		'name'      => __( 'Small', 'loop' ),
// 		'shortName' => __( 'S', 'loop' ),
// 		'size'      => 14,
// 		'slug'      => 'small'
// 	),
// 	array(
// 		'name'      => __( 'Large', 'loop' ),
// 		'shortName' => __( 'L', 'loop' ),
// 		'size'      => 18,
// 		'slug'      => 'large'
// 	),
// 	array(
// 		'name'      => __( 'Big', 'loop' ),
// 		'shortName' => __( 'XL', 'loop' ),
// 		'size'      => 40,
// 		'slug'      => 'big'
// 	),
// ) );
add_theme_support( 'editor-font-sizes', array() );

add_theme_support( 'disable-custom-font-sizes' );

function mkwvs_register_acf_block_types() {

    // register a CTA button block.
    acf_register_block_type(array(
        'name'              => 'cta-button',
        'title'             => __('Bouton CTA', 'loop'),
        'description'       => __('Un bloc Bouton CTA custom.', 'loop'),
        'render_template'   => 'template-part/gutenberg-blocks/cta-button.php',
        'category'          => 'text',
        'icon'              => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0V0z"></path><g><path d="M19 6H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 10H5V8h14v8z"></path></g></svg>',
        'keywords'          => array( 'button', 'cta', 'call-to-action', 'link', 'bouton', 'lien' ),
        'enqueue_assets' => 'mkwvs_acf_block_enqueue_assets',
    ));


    // register a Video with image block.
    acf_register_block_type(array(
        'name'              => 'video-with-image',
        'title'             => __('Vidéo avec image', 'busmagique'),
        'description'       => __('Un bloc custom Vidéo avec image.', 'busmagique'),
        'render_template'   => 'template-part/gutenberg-blocks/video-with-image.php',
        'category'          => 'media',
        'icon'              => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M4 6.47L5.76 10H20v8H4V6.47M22 4h-4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4z"></path></svg>',
        'keywords'          => array( 'video', 'vimeo', 'youtube' ),
        'enqueue_assets' => 'mkwvs_acf_block_enqueue_assets',
    ));


    // // register a Partner block.
    // acf_register_block_type(array(
    //     'name'              => 'partner',
    //     'title'             => __('Membre partenaire', 'loop'),
    //     'description'       => __('Un bloc Membre partenaire custom.', 'loop'),
    //     'render_template'   => 'template-part/gutenberg-blocks/partner.php',
    //     'category'          => 'media',
    //     'icon'              => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M0,0h24v24H0V0z" fill="none"></path><path d="M21,4H3C1.9,4,1,4.9,1,6v12c0,1.1,0.9,2,2,2h18c1.1,0,2-0.9,2-2V6C23,4.9,22.1,4,21,4z M21,18H3V6h18V18z"></path><polygon points="14.5 11 11 15.51 8.5 12.5 5 17 19 17"></polygon></svg>',
    //     'keywords'          => array( 'membre', 'member', 'partenaire', 'partner', 'lien', 'link', 'logo', 'image' ),
    //     'enqueue_assets' => 'mkwvs_acf_block_enqueue_assets',
    // ));


    // register a custom Gallery block.
    acf_register_block_type(array(
        'name'              => 'gallery-mw',
        'title'             => __('Galerie', 'busmagique'),
        'description'       => __('Un bloc custom Galerie.', 'busmagique'),
        'render_template'   => 'template-part/gutenberg-blocks/mw-gallery.php',
        'category'          => 'media',
        'icon'              => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0V0z"></path><g><path d="M20 4v12H8V4h12m0-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 9.67l1.69 2.26 2.48-3.1L19 15H9zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"></path></g></svg>',
        'keywords'          => array( 'gallery', 'galerie', 'image', 'images', 'videos', 'video', 'vidéos', 'vidéo' ),
        'enqueue_assets' => 'mkwvs_acf_block_enqueue_assets',
    ));



}

// // Check if function exists and hook into setup.
if( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'mkwvs_register_acf_block_types');
}

// Gutenberg allowed blocks
add_filter( 'allowed_block_types', 'mw_allowed_block_types', 10, 2 );

function mw_allowed_block_types( $allowed_blocks, $post ) {

	$allowed_blocks = array(
		'core/image',
    'core/embed',
		'core/paragraph',
		'core/heading',
		'core/list',
		// 'core/quote',
		// 'core/cover',
		// 'core/group',
		// 'core/columns',
		'core/media-text',
		// 'core/spacer',
    // 'core/table',
    'acf/cta-button',
    // 'acf/icon',
    'acf/video-with-image',
    'acf/gallery-mw',
    // 'acf/logoslist',
    // 'acf/partnerslist',
    'core/block',
    'mw/container',
	);

	// if( $post->post_type === 'page' ) {
	// 	$allowed_blocks[] = 'core/shortcode';
	// }

	return $allowed_blocks;

}