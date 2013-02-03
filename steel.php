<?php
/*
Plugin Name: Steel
Plugin URI: //Not yet developed
GitHub URI: https://github.com/starverte/steel.git
Description: Core plugin of the Sparks Framework. Includes custom widgets, royalslider, and options menu.
Author: starverte
Author URI: http://starverte.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/

  Copyright 2013  Star Verte LLC  (email : info@starverte.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once dirname( __FILE__ ) . '/royalslider.php';
include_once dirname( __FILE__ ) . '/widgets.php';

add_action( 'admin_enqueue_scripts', 'steel_scripts' );
   
function steel_scripts() {
       wp_register_style( 'SparksStyles', plugins_url('admin.css', __FILE__) );
       wp_enqueue_style( 'SparksStyles' );
}

add_action('admin_init', 'register_sparks_options' );
add_action('admin_menu', 'register_sparks_menu');

// Init plugin options to white list our options
function register_sparks_options(){
	register_setting( 'sparks_options', 'steel_options', 'steel_options_validate' );
}

// Add menu page
function register_sparks_menu() {
	add_options_page('Ozh\'s Sample Options', 'Sample Options', 'manage_options', 'steel_optionsoptions', 'steel_optionsoptions_do_page');
	add_menu_page('Sparks', 'Sparks', 'manage_options', 'steel/admin.php', '',   plugins_url('steel/img/sparks.png'), 50);
}

function steel_options_validate($input) {
	
	// Say our second option must be safe text with no HTML tags
	$input['merch_id'] =  wp_filter_nohtml_kses($input['merch_id']);
	
	return $input;
}

//Empty search fix
add_filter( 'request', 'my_request_filter' );
function my_request_filter( $query_vars ) {
    if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
        $query_vars['s'] = " ";
    }
    return $query_vars;
}

//Twitter Button
function tweet_this( $data_count = 'horizontal' , $data_size = '' , $data_via = '' , $args = array() ) {
	$url = get_permalink();
	$title = the_title( '', '', false);
	$language = get_bloginfo( 'language' );
	$defaults = array(
		'data_url' => $url,
		'data_text' => $title,
		'data_related' => '',
		'data_lang' => $language,
		'data_counturl' => $url,
		'data_hashtags' => '',
		'data_dnt' => '',
	);
	$args = wp_parse_args($args, $defaults);
	$args = (object) $args;
	
	if ( $args->data_hashtags != '' ) { $tweet_class = 'twitter-hashtag-button'; $hashtag = '#'.$args->data_hashtags; $link = 'https://twitter.com/intent/tweet?button_hashtag='.$hashtag; } else { $tweet_class = 'twitter-share-button'; $hashtag = ''; $link = 'https://twitter.com/share';}
	
	printf('<a href="%s" class="%s" data-count="%s" data-size="%s" data-via="%s" data-url="%s" data-text="%s" data-related="%s" data-lang="%s" data-counturl="%s" data_hashtags="%s" data-dnt="%s">', $link, $tweet_class, $data_count, $data_size, $data_via, $args->data_url, $args->data_text, $args->data_related, $args->data_lang, $args->data_counturl, $args->data_hashtags, $args->data_dnt);
	printf('Tweet</a>');
	printf('<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>');
}
?>
