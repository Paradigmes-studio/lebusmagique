<?php

// Makewaves Dashbox : Add Action On Admin Init
add_action( 'admin_init', 'mkwvs_dashbox_identity_init' );

// Makewaves Dashbox :  Functions Hooked On Admin Init
function mkwvs_dashbox_identity_init(){
    add_action('wp_dashboard_setup', 'mkwvs_dashbox_identity');
    add_action('admin_enqueue_scripts', 'mkwvs_dashbox_identity_scripts');
    add_action('wp_ajax_mkwvs_dashbox_identity_save', 'mkwvs_dashbox_identity_save');
}

// Makewaves Dashbox :  Function
function mkwvs_dashbox_identity_scripts(){
    wp_enqueue_style('mkwvs_dashbox_identity_styles', get_stylesheet_directory_uri() . '/dashboxes/dashbox-identity.css');
    wp_enqueue_script('mkwvs_dashbox_identity_scripts', get_stylesheet_directory_uri() . '/dashboxes/dashbox-identity.js');
    wp_add_inline_script('mkwvs_dashbox_identity_scripts', 'var ajaxurl = "' . esc_js(admin_url('admin-ajax.php')) . '";', 'before');
}

// Makewaves Dashbox :  Add Admin Dashbox Identity Social Render Functions
function mkwvs_dashbox_identity(){
    wp_add_dashboard_widget('mkwvs_dashbox_identity', 'Identité & Réseaux Sociaux', 'mkwvs_dashbox_identity_render');
}

// Makewaves Dashbox :  Admin Dashbox Social Render Method
function mkwvs_dashbox_identity_render(){

    // Properties
    $default_social = array(
        array('0', 'Facebook', '', ''),
        array('0', 'Instagram', '', ''),
        array('0', 'Twitter', '', ''),
        array('0', 'LinkedIn', '', ''),
        array('0', 'Youtube', '', ''),
        array('0', 'Vimeo', '', ''),
    );

    // Get Existing Options
    $existing_coordonnees = get_option('mkwvs_dashbox_coordonnees');
    // Get Existing Options
    $existing_social = get_option('mkwvs_dashbox_social');

    if (FALSE === $existing_social){
        $existing_social = $default_social;
    }

    // Nonce Verification Field
    echo '<input type="hidden" name="mkwvs_dashbox_social_nonce" id="mkwvs_dashbox_social_nonce" value="'. wp_create_nonce('mkwvs_dashbox_social_nonce'). '" />';



    echo '<div class="content">';

    echo '<h3>Coordonnées</h3>';
    echo '<div class="coordonnees">';
    echo '<label for="mkwvs_dashbox_coordonnees_email">Email : </label>';
    echo '<input type="text" name="mkwvs_dashbox_coordonnees_email" id="mkwvs_dashbox_coordonnees_email" value="'.$existing_coordonnees[0].'" /><br />';
    echo '<label for="mkwvs_dashbox_coordonnees_phone">Telephone : </label>';
    echo '<input type="text" name=""mkwvs_dashbox_coordonnees_phone" id="mkwvs_dashbox_coordonnees_phone" value="'.$existing_coordonnees[1].'" /><br />';
    echo '<label for="mkwvs_dashbox_coordonnees_address">Adresse : </label>';
    echo '<input type="text" name=""mkwvs_dashbox_coordonnees_address" id="mkwvs_dashbox_coordonnees_address" value="'.$existing_coordonnees[2].'" /><br />';
    echo '<label for="mkwvs_dashbox_coordonnees_zip_code">Code Postal : </label>';
    echo '<input type="text" name=""mkwvs_dashbox_coordonnees_zip_code" id="mkwvs_dashbox_coordonnees_zip_code" value="'.$existing_coordonnees[3].'" /><br />';
    echo '<label for="mkwvs_dashbox_coordonnees_city">Ville : </label>';
    echo '<input type="text" name=""mkwvs_dashbox_coordonnees_city" id="mkwvs_dashbox_coordonnees_city" value="'.$existing_coordonnees[4].'" /><br />';
    echo '</div>';

    echo '<br />';
    echo '<h3>Réseaux Sociaux</h3>';


    echo '<table class="widefat">';
    echo '<thead>';
    echo '  <tr>';
    echo '      <th width="10%">Actif</th>';
    echo '      <th width="20%">Réseaux</th>';
    echo '      <th width="35%">Lien</th>';
    echo '  </tr>';
    echo '</thead>';
    echo '<tbody>';
    if ($existing_social !== false){
        $cpt = 1;
        foreach($existing_social as $social){
            echo '<tr>';
            echo '  <td><input type="checkbox" class="actif" name="mkwvs_dashbox_social_activation_'.$cpt.'" id="mkwvs_dashbox_social_activation_'.$cpt.'" '.($social[0] == '1' ? 'checked="checked"': '').' /></td>';
            echo '  <td><label class="libelle" name="mkwvs_dashbox_social_libelle_'.$cpt.'" id="mkwvs_dashbox_social_libelle_'.$cpt.'" >'.$social[1].'</label></td>';
            echo '  <td><input class="link" type="text" name="mkwvs_dashbox_social_link_'.$cpt.'" id="mkwvs_dashbox_social_link_'.$cpt.'" value="'.$social[2].'" /></td>';
            echo '</tr>';
            $cpt++;
        }
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '<br /><a href="#" class="button-primary" id="mkwvs_dashbox_identity_save" >Enregistrer</a><span class="spinner"></span>';
}

// Makewaves Dashbox :  Ajax Method : Save Admin Dashboard Counter
function mkwvs_dashbox_identity_save(){

    // Verify Nonce
    if ( !wp_verify_nonce( $_REQUEST['nonce'], 'mkwvs_dashbox_social_nonce')) exit();

    $socials = $_REQUEST['socials'];
    $updated_1 = update_option('mkwvs_dashbox_social',$socials);

    $coordonnees = $_REQUEST['coordonnees'];
    $updated_2 = update_option('mkwvs_dashbox_coordonnees',$coordonnees);

    if ($updated_1 && $updated_2){
        $response = array('success' => true);
    }else{
        $response = array('success' => false);
    }
    wp_send_json($response);
    exit();
}
