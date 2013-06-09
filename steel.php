<?php
/*
Plugin Name: Steel
Plugin URI: https://github.com/starverte/steel.git
Description: Core plugin of the Sparks Framework. Includes custom widgets, social functions, and options menu
Version: 0.6.0
Author: Star Verte LLC
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

include_once dirname( __FILE__ ) . '/events.php';
include_once dirname( __FILE__ ) . '/teams.php';
include_once dirname( __FILE__ ) . '/widgets.php';

add_action( 'admin_enqueue_scripts', 'steel_admin_scripts' );
function steel_admin_scripts() {
	wp_enqueue_style( 'steel-admin-style', plugins_url('steel/css/admin.css') );
	
	// Load scripts and styles for Twitter Bootstrap
	wp_enqueue_script( 'bootstrap-admin', plugins_url( '/steel/js/bootstrap-admin.min.js'), array('jquery') , '2.3.1', true );
	wp_enqueue_style( 'bootstrap-admin-style', plugins_url( '/steel/css/bootstrap-admin.min.css' ) );
}

add_action( 'wp_enqueue_scripts', 'steel_scripts' );
function steel_scripts() {
	wp_enqueue_script( 'pin-it-button', 'http://assets.pinterest.com/js/pinit.js'); // Load script for "Pin It" button
}


/*
 * Add options page
 */
add_action('admin_menu', 'sparks_admin_add_page');
function sparks_admin_add_page() {
	add_menu_page('Sparks', 'Sparks', 'manage_options', 'sparks', 'sparks_options_page',   plugins_url('steel/img/sparks.png'), 50);
}
function sparks_options_page() { ?>

	<div class="wrap">
    <h2>Sparks Options</h2>
    <form action="options.php" method="post">
			<?php settings_fields('sparks_options'); ?>
      <?php do_settings_sections('sparks'); ?>
      <?php settings_errors(); ?>
      <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>
	</div><?php	
}

/*
 * Register settings for options page
 */
add_action('admin_init', 'sparks_admin_init');
function sparks_admin_init(){
	register_setting('sparks_options', 'sparks_options', 'sparks_options_validate' );
	add_settings_section('sparks_social', 'Social Media', 'sparks_social_text', 'sparks');
	add_settings_field('fb_app_id', 'Facebook App ID', 'fb_app_id_setting', 'sparks', 'sparks_social' );
	if (is_plugin_active('sparks-store/store.php')) {
		add_settings_section('sparks_store', 'PayPal', 'sparks_store_text', 'sparks');
		add_settings_field('paypal_merch_id', 'Merchant ID', 'paypal_merch_id_setting', 'sparks', 'sparks_store' );
	}
}
function sparks_store_text() { echo ''; }
function paypal_merch_id_setting() {
	$options = get_option('sparks_options');
	if (isset($options['merch_id'])) { echo "<input id='paypal_merch_id' name='sparks_options[merch_id]' size='40' type='text' value='{$options['merch_id']}' />"; }
	else { echo "<input id='paypal_merch_id' name='sparks_options[merch_id]' size='40' type='text' value='' />"; }
}
function sparks_social_text() { echo 'Social media profile information'; }
function fb_app_id_setting() {
	$options = get_option('sparks_options');
	if (isset($options['fb_app_id'])) { echo "<input id='db_app_id' name='sparks_options[fb_app_id]' size='40' type='text' value='{$options['fb_app_id']}' />"; }
	else { echo "<input id='fb_app_id' name='sparks_options[fb_app_id]' size='40' type='text' value='' />"; }
}
function sparks_options_validate($input) {
	global $newinput;
	$newinput['merch_id'] = trim($input['merch_id']);
	if(!preg_match('/^[a-z0-9]{13}$/i', $newinput['merch_id']) & !empty($newinput['merch_id'])) { add_settings_error( 'merch_id' , 'invalid' , 'Invalid PayPal Merchant ID. <span style="font-weight:normal;display:block;">A PayPal Merchant ID consists of 13 alphanumeric characters.</span>'  ); }
	$newinput['fb_app_id'] = trim($input['fb_app_id']);
	if (!preg_match('/^[0-9]{15}$/i', $newinput['fb_app_id']) & !empty($newinput['fb_app_id'])) { add_settings_error( 'fb_app_id' , 'invalid' , 'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>'  ); }
	return $newinput;
}

/*
 * Empty search fix
 */
add_filter( 'request', 'steel_request' );
function steel_request( $query_vars ) {
	if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) { $query_vars['s'] = " "; }
	return $query_vars;
}

/*
 * Create function tweet_this
 */
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

/*
 * Create function like_this (Facebook)
 */
function like_this( $args = array() ) {
	$url = get_permalink();
	$defaults = array(
		'data_href' => $url,
		'data_send' => 'false',
		'data_layout' => 'standard',
		'data_show_faces' => 'false',
		'data_width' => '450',
		'data_action' => 'like',
		'data_font' => 'lucida grande',
		'data_color' => 'light',
		'data_ref' => '',
	);
	$args = wp_parse_args($args, $defaults);
	$args = (object) $args;
	printf('<div class="fb-like" data-href="%s" data-send="%s" data-layout="%s" data-show-faces="%s" data-width="%s" data-action="%s" data-font="%s" data-colorscheme="%s" data-ref="%s"></div>', $args->data_href, $args->data_send, $args->data_layout, $args->data_show_faces, $args->data_width, $args->data_action, $args->data_font, $args->data_color, $args->data_ref);
}

/*
 * Create function pin_it (Pinterest)
 */
function pin_it( $args = array() ) {
	$url = get_permalink();
	$title = the_title( '', '', false);
	$thumb_id = get_post_thumbnail_id();
	$thumbnail = wp_get_attachment_url( $thumb_id );
	$defaults = array(
		'data_url' => $url,
		'data_thumb' => $thumbnail,
		'data_text' => $title,
		'data_count' => 'horizontal',
	);
	$args = wp_parse_args($args, $defaults);
	$args = (object) $args;
	printf('<a href="http://pinterest.com/pin/create/button/?url=%s&media=%s&description=%s" class="pin-it-button" count-layout="%s">', $args->data_url, $args->data_thumb, $args->data_text, $args->data_count);
	printf('<img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>');
}

/*
 * Create [columns] and [column] shortcodes
 */
add_shortcode( 'columns', 'columns_shortcode' );
add_shortcode( 'column', 'column_shortcode' );
function columns_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'num' => 2 ), $atts ) );
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	return '<div class="columns columns-'. esc_attr($num) .'">' . do_shortcode($new) . '</div>';
}
function column_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => null ), $atts ) );
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	if (isset($title) && !empty($title)) { return '<div class="column"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div>'; }
	else { return '<div class="column"><p>' . $new . '</p></div>'; }
}
?>
