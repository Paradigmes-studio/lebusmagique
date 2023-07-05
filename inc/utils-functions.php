<?php
/**
 * Utils functions.
 *
 * @author Yann Kozon <contact@yannkozon.com>
 * @package YannKozon
 * @since 3.0.0
 */

/**
 * Display image url
 *
 * @param string $filename Filename in image folder.
 */
function image_url( $filename = null ) {
	echo esc_url( get_image_url( $filename ) );
}

/**
 * Return image url
 *
 * @param string $filename Filename in image folder.
 * @return string Url to file.
 */
function get_image_url( $filename = null ) {
	return esc_url( get_template_directory_uri() ) . '/img/' . esc_attr( $filename );
}

/**
 * Display css url
 *
 * @param string $filename Filename in css folder.
 */
function css_url( $filename = null ) {
	echo esc_url( get_css_url( $filename ) );
}

/**
 * Return css url
 *
 * @param string $filename Filename in css folder.
 * @return string Url to file.
 */
function get_css_url( $filename = null ) {
	return esc_url( get_template_directory_uri() ) . '/css/' . esc_attr( $filename );
}

/**
 * Display js url
 *
 * @param string $filename Filename in js folder.
 */
function js_url( $filename = null ) {
	echo esc_url( get_js_url( $filename ) );
}

/**
 * Return js url
 *
 * @param string $filename Filename in js folder.
 * @return string Url to file.
 */
function get_js_url( $filename = null ) {
	return esc_url( get_template_directory_uri() ) . '/js/' . esc_attr( $filename );
}

// function is_custom_load() {
// 	return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'EssorXMLHttpRequest' );
// }
