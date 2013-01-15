<?php
/*
 * Loads RoyalSlider scripts
 *
 * @package Sparks
 * @sub-package Steel
 *
 */
add_action('wp_enqueue_scripts', 'my_scripts_method');

function my_scripts_method() {
	wp_enqueue_script('jquery');
	wp_enqueue_script(
		'royalslider',
		plugins_url('/js/jquery.royalslider-9.0.min.js', __FILE__),
		array('jquery')
	);
	wp_enqueue_script(
		'jquery-easing',
		plugins_url('/js/jquery.easing-1.3.js', __FILE__),
		array('jquery')
	);
	wp_enqueue_script(
		'rs-run',
		plugins_url('/js/run.js', __FILE__),
		true
	);
}    

    /**
     * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
     */
    add_action( 'wp_enqueue_scripts', 'rs_add_my_stylesheet' );

    /**
     * Enqueue plugin style-file
     */
    function rs_add_my_stylesheet() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_style( 'rs-style', plugins_url('royalslider.css', __FILE__) );
        wp_enqueue_style( 'rs-style' );
	wp_register_style( 'rsDefault', plugins_url('/default/rs-default.css', __FILE__) );
        wp_enqueue_style( 'rsDefault' );
    }

?>