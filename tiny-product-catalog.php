<?php
/*
Plugin Name: Pieni tuotekatalogi
Description: Lisäosa tuotteiden määrittelyyn ja näyttämiseen.
Author: Mika Tiihonen
*/

require_once('includes/tiny-product-catalog-post-type.php');
require_once('includes/tiny-product-catalog-shortcodes.php');
require_once('includes/tiny-product-catalog-widget.php');

function tpcatalog_setup_menu(){
    add_menu_page('Pieni tuotekatalogi', 'Tuotekatalogi', 'manage_options', 'tiny-product-catalog', 'tpcatalog_display_admin_page');
}

function tpcatalog_display_admin_page() {
    echo '<h1>Pieni tuotekatalogi</h1>';
    echo '<p>Lisää artikkeliin tai sivulle lyhytkoodi [tiny-product-catalog] näyttääksesi kaikki tuotteet tai [tiny-product-catalog category="sinun-kategoriasi"] näyttääksesi tietyn tuotekategorian tuotteet.</p>';
    echo '<p>Meillä on myös vimpain!</p>';
}
add_action('admin_menu', 'tpcatalog_setup_menu');

function tpcatalog_assets() {
    wp_enqueue_style('tpcatalog-css', plugin_dir_url(__FILE__) . 'css/tiny-product-catalog.css');
}

add_action('wp_enqueue_scripts', 'tpcatalog_assets');
?>