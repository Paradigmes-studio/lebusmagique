<?php
// Makewaves - CPT PAGE (Overrides Native Post Type "page")

// Page Action : Admin Init
add_action('admin_init','mkwvs_page_admin_page_init');
function mkwvs_page_admin_page_init(){
    // Page Sub Title MetaBox + Save Data
    add_meta_box( 'mkwvs_page_info_box', 'Informations complémentaires', 'mkwvs_page_info_render', 'page', 'normal', 'high');
    add_action( 'save_post', 'mkwvs_page_sub_title_save_postdata');
}

// Metabox Page Info Render
function mkwvs_page_info_render(){
    global $post;
    
    $page_sub_title    = get_post_meta($post->ID, 'page_sub_title', true);
    $page_color_hexa   = get_post_meta($post->ID, 'page_color_hexa', true);
    
    // Use nonce for verification
    echo '<input type="hidden" name="mkwvs_page_sub_title_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';
    
    // Custom Sub Title
    echo '<span style="width:25%;display:block;float:left;">Sous-titre de page :</span>';
    echo '<input style="width:50%;" type="text" name="page_sub_title" id="page_sub_title" value="'.$page_sub_title.'" />';
    
    // Custom Color
    echo '<span style="width:25%;display:block;float:left;">Code couleur(ex: #000000) :</span>';
    echo '<input style="width:50%;" type="text" name="page_color_hexa" id="page_color_hexa" value="'.$page_color_hexa.'" />';
    
}

// Metabox Page Info Save Data
function mkwvs_page_sub_title_save_postdata($post_id){
    
    // Check Nonce
    if (!wp_verify_nonce($_POST['mkwvs_page_sub_title_metabox_nonce'], basename(__FILE__))) return $post_id;
	
    // Check Autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    
    // Update Custom Sub Title
    $page_sub_title = sanitize_text_field($_POST['page_sub_title']);
    if (!empty($page_sub_title)) update_post_meta ($post_id, 'page_sub_title', $page_sub_title);
    else update_post_meta ($post_id, 'page_sub_title', '');
    
    // Update Custom Color Title
    $page_color_hexa = sanitize_text_field($_POST['page_color_hexa']);
    if (!empty($page_color_hexa)) update_post_meta ($post_id, 'page_color_hexa', $page_color_hexa);
    else update_post_meta ($post_id, 'page_color_hexa', '');
    
}
