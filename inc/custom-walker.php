<?php
/**
 * Menu With Custom Walker
 */

class Loop_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = NULL) {
        /*if ($depth === 0 ) { // Root Element
            $output .= '<ul class="o-menu-galaxy__menu">'. "\n";
        }*/
    }

    function end_lvl(&$output, $depth = 0, $args = NULL) {

        /*if ($depth === 0){
            $output .= '</ul>'. "\n";
        }*/
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

        // Get Properties
        $object = $item->object;
        $type = $item->type;
        $title = $item->title;
        $description = $item->description;
        $permalink = $item->url;

        // Manage Items Classes
        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;
        $item->classes[]   = sanitize_title($title);

        // Link Attributes
        $attributes  = '';
        ! empty( $item->attr_title )
        and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        ! empty( $item->target )
        and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        ! empty( $item->xfn )
        and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        ! empty( $item->url )
        and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

        if ($depth === 0 ) { // Root Element

            $output .= '<li class="' . trim(implode(" ", $item->classes)) . '">' . "\n";
            $output .= '<a href="'.$item->url.'">'. $title . '</a>';
            if ( in_array('menu-item-has-children', $item->classes) ) {
                $output .= '<ul class="sub-menu">';
            }

        }

        if ($depth === 1 &&  in_array('has-icon', $item->classes) ) { // Root Element

            $output .= '<li class="' . trim(implode(" ", $item->classes)) . '">' . "\n";
            $output .= '<a href="'.$item->url.'">';
            // Get Post Thumbnail
            $icon = wp_get_attachment_image_src(get_post_thumbnail_id($item->object_id), 'thumbnail');
            $output .= file_get_contents($icon[0]);
            $output .=  $title;
            $output .=  '</a>';
            $output .= '</li>';

        }
        if ($depth === 1 && !in_array('has-icon', $item->classes) ) {

            $output .= '<li class="' . trim(implode(" ", $item->classes)) . '">' . "\n";
            $output .= '<a href="'.$item->url.'">'. $title . '</a>';
            $output .= '</li>';

        }
    }

    function end_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;
        if ($depth === 0) { // Root Element

            if ( in_array('menu-item-has-children', $item->classes) ) {
                $output .= '</ul>';
            }

            // Close Root <li>
            $output .= '</li>';

        }

        if ($depth === 1 && in_array('has-icon', $item->classes)) { // Root Element

            // Close Root <li>
            //$output .= '</li>';

        }


    }

}
