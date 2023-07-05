<?php
// Custom Post Type Solutions Complètes
add_action( 'init', 'mkwvs_create_post_type_carte' );
function mkwvs_create_post_type_carte(){

  $labels = array(
    'name'                => 'La Carte',
    'singular_name'       => 'La Carte',
    'menu_name'           => 'La Carte',
    'parent_item_colon'   => 'Element parent',
    'all_items'           => 'Voir tous les éléments',
    'view_item'           => 'Voir cet élément',
    'add_new_item'        => 'Ajouter un élément',
    'add_new'             => 'Ajouter ',
    'edit_item'           => 'Editer cet élément',
    'update_item'         => 'Mettre à jour',
    'search_items'        => 'Rechercher',
    'not_found'           => 'Aucun élement',
    'not_found_in_trash'  => 'Aucun élément dans la corbeille',
  );
  $args = array(
    'label'               => 'la carte',
    'description'         => 'Custom Post Type La Carte',
    'labels'              => $labels,
    'supports'            => array( 'title', 'thumbnail', 'editor', 'page-attributes'),
    'taxonomies'          => array(),
    'hierarchical'        => true,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'show_in_rest'        => true,
    'menu_position'       => 6,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'page',
    'menu_icon'           => 'dashicons-list-view',
  );
  register_post_type( 'carte', $args );

  // Custom Taxonomy "Typologie"
  $labels = array(
    'name'              => 'Typologies',
    'singular_name'     => 'Typologie',
    'search_items'      => __( 'Rechercher une typologie' ),
    'all_items'         => __( 'Toutes les typologies' ),
    'parent_item'       => __( 'Typologie parent' ),
    'parent_item_colon' => __( 'Typologie parent :' ),
    'edit_item'         => __( 'Editer cette typologie' ),
    'update_item'       => __( 'Mettre à jour cette typologie' ),
    'add_new_item'      => __( 'Ajouter une nouvelle typologie' ),
    'new_item_name'     => __( 'Nouvelle typologie' ),
    'menu_name'         => __( 'Typologies' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_in_rest'       => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'typologie', 'with_front' => false ),
  );
  register_taxonomy( 'typologie', 'carte', $args );
}