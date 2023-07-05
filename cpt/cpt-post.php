<?php
// Makewaves - CPT POST (Overrides Native Post Type "post")

// Post Action : Admin Init
add_action('admin_init','mkwvs_post_admin_post_init');
function mkwvs_post_admin_post_init(){
    
    // Post Sub Title MetaBox + Save Data
    add_meta_box( 'mkwvs_post_information_box', 'Information complémentaires', 'mkwvs_post_information_render', 'post', 'normal', 'high');
    add_action( 'save_post', 'mkwvs_post_information_save_postdata');
    
}

// Post Metabox Infos Render
function mkwvs_post_information_render(){
    global $post;
    
    $post_information_author     = get_post_meta($post->ID, 'post_information_author', true);
    
    // Use nonce for verification
    echo '<input type="hidden" name="mkwvs_post_information_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';
    
    // Custom Fields : Author
    echo '<span style="width:25%;display:block;float:left;">Auteur de l\'article :</span>';
    echo '<input style="width:50%;" type="text" name="post_information_author" id="post_information_author" value="'.$post_information_author.'" />';
    
}

// Post Metabox Infos Save Data
function mkwvs_post_information_save_postdata($post_id){
    
    // Check Nonce
    if (!wp_verify_nonce($_POST['mkwvs_post_information_metabox_nonce'], basename(__FILE__))) return $post_id;
	
    // Check Autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    
    
    // Update Custom Date Year
    $post_information_author = sanitize_text_field($_POST['post_information_author']);
    if (!empty($post_information_author)) update_post_meta ($post_id, 'post_information_author', $post_information_author);
    else update_post_meta ($post_id, 'post_information_author', '');
    
}
