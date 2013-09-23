<?php
/*
Plugin Name: Steel
Plugin URI: https://github.com/starverte/steel.git
Description: Core plugin of the Sparks Framework. Includes custom widgets, social functions, and options menu
Version: 0.8.1
Author: Star Verte LLC
Author URI: http://starverte.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/

  Copyright 2013  Star Verte LLC  (email : info@starverte.com)
  
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include_once dirname( __FILE__ ) . '/quotes.php';
include_once dirname( __FILE__ ) . '/shortcodes.php';
include_once dirname( __FILE__ ) . '/teams.php';
include_once dirname( __FILE__ ) . '/templates/templates.php';
include_once dirname( __FILE__ ) . '/widgets.php';

/**
 * Returns current plugin version.
 */
function steel_version() {
    if ( ! function_exists( 'get_plugins' ) )
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['Version'];
}

/**
 * Load scripts
 */
add_action( 'admin_enqueue_scripts', 'steel_admin_scripts' );
function steel_admin_scripts() { wp_enqueue_style( 'steel-admin-style', plugins_url('steel/css/admin.css') ); }

/**
 * Returns current plugin version.
 */
function steel_version() {
    if ( ! function_exists( 'get_plugins' ) )
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['Version'];
}

/**
 * Load scripts
 */
add_action( 'admin_enqueue_scripts', 'steel_admin_scripts' );
function steel_admin_scripts() { wp_enqueue_style( 'steel-admin-style', plugins_url('steel/css/admin.css') ); }

add_action( 'wp_enqueue_scripts', 'steel_scripts' );
function steel_scripts() {
  wp_enqueue_script( 'pin-it-button', 'http://assets.pinterest.com/js/pinit.js'); // Load script for "Pin It" button
  wp_enqueue_script( 'steel-run', plugins_url( '/steel/js/run.js' ), array('jquery') , steel_version() , true ); // Load front-end scripts
}

/*
 * Add options page
 */
add_action('admin_menu', 'steel_admin_add_page');
function steel_admin_add_page() {
  add_menu_page('Steel', 'Steel', 'manage_options', 'steel', 'steel_options_page',   plugins_url('steel/img/sparks.png'), 100);
}
function steel_options_page() { ?>

  <div class="wrap">
  <?php echo '<img width="32" height="32" src="' . plugins_url( 'img/sparks.png' , __FILE__ ) . '" style="margin-right: 10px; float: left; margin-top: 7px;" /><h2>Steel Options</h2>'; ?>
  <form action="options.php" method="post">
    <?php settings_fields('steel_options'); ?>
    <?php do_settings_sections('steel'); ?>
    <?php settings_errors(); ?>
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
  </form>
  </div><?php 
}

/*
 * Register settings for options page
 */
add_action('admin_init', 'steel_admin_init');
function steel_admin_init(){
  register_setting('steel_options', 'steel_options', 'steel_options_validate' );
  add_settings_section('steel_social', 'Social Media', 'steel_social_text', 'steel');
  add_settings_field('fb_app_id', 'Facebook App ID', 'fb_app_id_setting', 'steel', 'steel_social' );
  if (is_plugin_active('sparks-store/store.php')) {
    add_settings_section('sparks_store', 'PayPal', 'sparks_store_text', 'steel');
    add_settings_field('paypal_merch_id', 'Merchant ID', 'paypal_merch_id_setting', 'steel', 'sparks_store' );
  }
  add_settings_section('steel_mods', 'Modules', 'steel_mods_output', 'steel');
  add_settings_field('mod_teams', 'Teams', 'mod_teams_setting', 'steel', 'steel_mods' );
}
function sparks_store_text() { echo ''; }
function paypal_merch_id_setting() {
  $options = get_option('steel_options');
  if (isset($options['paypal_merch_id'])) { echo "<input id='paypal_merch_id' name='steel_options[paypal_merch_id]' size='40' type='text' value='{$options['paypal_merch_id']}' />"; }
  else { echo "<input id='paypal_merch_id' name='steel_options[paypal_merch_id]' size='40' type='text' value='' />"; }
}
function steel_social_text() { echo 'Social media profile information'; }
function fb_app_id_setting() {
  $options = get_option('steel_options');
  if (isset($options['fb_app_id'])) { echo "<input id='fb_app_id' name='steel_options[fb_app_id]' size='40' type='text' value='{$options['fb_app_id']}' />"; }
  else { echo "<input id='fb_app_id' name='steel_options[fb_app_id]' size='40' type='text' value='' />"; }
}
function steel_mods_output() { echo 'Activate and deactivate modules within Steel'; }
function mod_teams_setting() {
  $options = get_option('steel_options');
  if (isset($options['mod_teams'])) { $teams = $options['mod_teams']; }
  else { $teams = "0"; } ?>
  <input name="steel_options[mod_teams]" type="radio" value="true" <?php checked( $teams, "true" ) ?>> Active
  <input name="steel_options[mod_teams]" type="radio" value="false" <?php checked( $teams, "false" ) ?>> Not Active
  <?php
}
function steel_options_validate($input) {
  global $newinput;
  if (is_plugin_active('sparks-store/store.php')) {
    $newinput['paypal_merch_id'] = trim($input['paypal_merch_id']);
    if(!preg_match('/^[a-z0-9]{13}$/i', $newinput['paypal_merch_id']) & !empty($newinput['paypal_merch_id'])) { add_settings_error( 'paypal_merch_id' , 'invalid' , 'Invalid PayPal Merchant ID. <span style="font-weight:normal;display:block;">A PayPal Merchant ID consists of 13 alphanumeric characters.</span>'  ); }
    $newinput['fb_app_id'] = trim($input['fb_app_id']);
  }
  $newinput['fb_app_id'] = trim($input['fb_app_id']);
  if (!preg_match('/^[0-9]{15}$/i', $newinput['fb_app_id']) & !empty($newinput['fb_app_id'])) { add_settings_error( 'fb_app_id' , 'invalid' , 'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>'  ); }
  $newinput['mod_teams'] = trim($input['mod_teams']);
  return $newinput;
}

/*
 * Add function steel_open
 */
function steel_open( $scripts = array() ) {
  $defaults = array('facebook' => true);
  $scripts = wp_parse_args( $scripts, $defaults );
  
  if ($scripts['facebook'] == true) {
    $steel_options = get_option('steel_options');
    if (isset($steel_options['fb_app_id'])) {
      $fb_app_id = $steel_options["fb_app_id"];
      echo '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=' . $fb_app_id . '"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\')); </script>';
    }
    else { return; }
  }
  else { return; }
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
 * Add function is_module_active
 */
function is_module_active( $mod ) {
  $options = get_option('steel_options');
  if (isset($options['mod_'.$mod])) { $mod_status = $options['mod_'.$mod]; }
  else $mod_status = "false";
  if ($mod_status == "true")
    return true;
  else
    return false;
}

/*
 * Create function to remove admin bar from front end by default
 */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}
?>
