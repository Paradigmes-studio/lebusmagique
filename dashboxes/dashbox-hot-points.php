<?php

// Add Action On Admin Init 
add_action( 'admin_init', 'foool_dashbox_hot_points_init' );

// Functions Hooked On Admin Init
function foool_dashbox_hot_points_init(){    
    add_action('wp_dashboard_setup', 'foool_dashbox_hot_points');
    add_action('admin_enqueue_scripts', 'foool_dashbox_hot_points_scripts');
    add_action('wp_ajax_foool_dashbox_hot_points_save', 'foool_dashbox_hot_points_save');
}

// Function
function foool_dashbox_hot_points_scripts(){
    $params = array( 'dir_path' => dirname(__FILE__), 'ajaxurl'  => admin_url( 'admin-ajax.php' ) );
    wp_enqueue_style('foool_dashbox_hot_points_styles', get_stylesheet_directory_uri() . '/dashboxes/dashbox-hot-points.css');
    wp_enqueue_script('foool_dashbox_hot_points_scripts', get_stylesheet_directory_uri() . '/dashboxes/dashbox-hot-points.js');
    wp_localize_script('foool_dashbox_hot_points_scripts','foool_dashbox_hot_points_params', $params);
}

// Add Admin Dashbox Counter Render Functions
function foool_dashbox_hot_points(){
    wp_add_dashboard_widget('foool_dashbox_hot_points', 'Chiffres Clés', 'foool_dashbox_hot_points_render');
}

// Admin Dashbox Counter Render Method
function foool_dashbox_hot_points_render(){
    
    // Properties
    $hot_points_1  = array('10','Libelle 1', 'Url Icon 1');
    $hot_points_2  = array('20','Libelle 2', 'Url Icon 2');
    $hot_points_3  = array('30','Libelle 3', 'Url Icon 3');
    
    // Get Existing Options
    $existing_hot_points = get_option('foool_dashbox_hot_points');
    
    
    if (FALSE == $existing_hot_points){
        $existing_hot_points = array($hot_points_1, $hot_points_2, $hot_points_3);
    }
    
    // Nonce Verification Field
    echo '<input type="hidden" name="foool_dashbox_hot_points_nonce" id="foool_dashbox_hot_points_nonce" value="'. wp_create_nonce('foool_dashbox_hot_points_nonce'). '" />';
    
    //
    echo '<h4>Administration des chiffres clés</h4>';
    echo '<div class="content">';
    echo '<table class="widefat">';
    echo '<thead>';
    echo '  <tr>';
    echo '      <th width="10%">N&deg;</th>';
    echo '      <th width="10%">Valeur</th>';
    echo '      <th width="10%">Unité</th>';
    echo '      <th width="35%">Libellé</th>';
    echo '  </tr>';
    echo '</thead>';
    echo '<tbody>';
    if ($existing_hot_points !== false){
        $cpt = 1;
        foreach($existing_hot_points as $hot_points){
            // HOT POINTS
            echo '<tr>';
            echo '  <td>'.$cpt.'</td>';
            echo '  <td><input type="text" class="short-size" name="value_hot_point_'.$cpt.'" id="value_hot_point_'.$cpt.'" value="'.$hot_points[0].'" /></td>';
            echo '  <td><input type="text" class="short-size" name="unite_hot_point_'.$cpt.'" id="unite_hot_point_'.$cpt.'" value="'.str_replace("\'","'",$hot_points[2]).'" /></td>';
            echo '  <td><input type="text" class="long-size" name="libelle_hot_point_'.$cpt.'" id="libelle_hot_point_'.$cpt.'" value="'.str_replace("\'","'",$hot_points[1]).'" /></td>';
            echo '</tr>';  
            $cpt++;
        }
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo ' <a href="#" class="button-primary" id="hot_points_save" >Enregistrer les modifications</a><span class="spinner"></span>';
}

// Ajax Method : Save Admin Dashbox Counter
function foool_dashbox_hot_points_save(){
    
    // Verify Nonce
    if ( !wp_verify_nonce( $_REQUEST['nonce'], 'foool_dashbox_hot_points_nonce')) exit();
    
    $hot_points_1 = $_REQUEST['hot_points_1'];
    $hot_points_2 = $_REQUEST['hot_points_2'];
    $hot_points_3 = $_REQUEST['hot_points_3'];
    
    $hot_points = array($hot_points_1,$hot_points_2,$hot_points_3);
    $updated = update_option('foool_dashbox_hot_points',$hot_points);
    
    if ($updated){$response = array('success' => true);
    }else{$response = array('success' => false);}
    wp_send_json($response);
}
