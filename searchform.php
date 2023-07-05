<?php
/**
 * Template for displaying search forms in Twenty Seventeen
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="field">
            <input id="search-text" class="input-saisie" name="s"  type="text" placeholder="Rechercher ...">
    </div>
    <div class="button more arrow">
            <input id="search-validation" value="Rechercher" type="submit">
    </div>
    <a class="close"></a>
</form>
