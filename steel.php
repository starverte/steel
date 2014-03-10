<?php
/*
Plugin Name: Steel
Plugin URI: https://github.com/starverte/steel.git
Description: Core plugin of the Sparks Framework. Works for any theme; but when paired with Flint your WordPress site will be on fire.
Version: 1.1.5
Author: Star Verte LLC
Author URI: http://starverte.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/

  Copyright 2013 Star Verte LLC (email : info@starverte.com)

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

  if (is_module_active('marketplace')) { include_once dirname( __FILE__ ) . '/marketplace.php'; }
//if (is_module_active('podcast'    )) { include_once dirname( __FILE__ ) . '/podcast.php';     }
  if (is_module_active('quotes'     )) { include_once dirname( __FILE__ ) . '/quotes.php';      }
  if (is_module_active('shortcodes' )) { include_once dirname( __FILE__ ) . '/shortcodes.php';  }
  if (is_module_active('slides'     )) { include_once dirname( __FILE__ ) . '/slides.php';      }
  if (is_module_active('teams'      )) { include_once dirname( __FILE__ ) . '/teams.php';       }
  if (is_module_active('widgets'    )) { include_once dirname( __FILE__ ) . '/widgets.php';     }

if (is_flint_active()) { include_once dirname( __FILE__ ) . '/templates.php'; }

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
function steel_admin_scripts() {
  wp_enqueue_style( 'steel-admin-style', plugins_url('steel/css/admin.css'    ) );
  wp_enqueue_style( 'steel-font'       , plugins_url('steel/css/starverte.css') );
  wp_enqueue_style( 'glyphicons'       , plugins_url('steel/css/glyphicons.css') );
  wp_enqueue_style( 'dashicons'                                                 );

  wp_enqueue_script( 'jquery'              );
  wp_enqueue_script( 'jquery-ui-core'      );
  wp_enqueue_script( 'jquery-ui-sortable'  );
  wp_enqueue_script( 'jquery-ui-position'  );
  wp_enqueue_script( 'jquery-effects-core' );
  wp_enqueue_script( 'jquery-effects-blind');

  if (is_module_active('marketplace')) {
    wp_enqueue_script( 'marketplace', plugins_url('steel/js/marketplace.js'  ), array('jquery'), steel_version(), true );
  }

  if (is_module_active('slides')) {
    wp_enqueue_script( 'slides-mod', plugins_url('steel/js/slides.js'  ), array('jquery'), steel_version(), true );
  }

  wp_enqueue_media();
}
add_action( 'wp_enqueue_scripts', 'steel_scripts' );
function steel_scripts() {
  if (is_module_active('bootstrap', 'js')||is_module_active('bootstrap', 'both')) {
    // Make sure there aren't other instances of Twitter Bootstrap
    wp_deregister_script('bootstrap');

    // Load Twitter Bootstrap
    wp_enqueue_script( 'bootstrap', plugins_url('steel/js/bootstrap.min.js'  ), array('jquery'), '3.0.3', true );
  }
  
  if (is_module_active('bootstrap', 'css')||is_module_active('bootstrap', 'both')) {
    // Make sure there aren't other instances of Twitter Bootstrap
    wp_deregister_style ('bootstrap-css');

    // Load Twitter Bootstrap
    wp_enqueue_style ( 'bootstrap-css', plugins_url('steel/css/bootstrap.min.css'), array() , '3.0.3' );
  }
  else {
    wp_deregister_style ('bootstrap-css');
    wp_enqueue_style ( 'glyphicons', plugins_url('steel/css/glyphicons.css'), array() , '3.0.3' );
  }
  
  if (is_module_active('slides')) {
    wp_enqueue_style ( 'slides-mod-style', plugins_url('steel/css/slides.css'  ), array(), steel_version());
  }

  // Load script for "Pin It" button
  wp_enqueue_script( 'pin-it-button', 'http://assets.pinterest.com/js/pinit.js');

  // Load front-end scripts
  wp_enqueue_script( 'steel-run', plugins_url( '/steel/js/run.js' ), array('jquery'), steel_version(), true );
}

/*
 * Add options page
 */
add_action('admin_menu', 'steel_admin_add_page');
function steel_admin_add_page() {
  add_menu_page('Steel', 'Steel', 'manage_options', 'steel', 'steel_options_page', 'none');
}
function steel_options_page() {
  ?>
  <div class="wrap">
    <h2>Steel Options</h2>
    <form action="options.php" method="post">
      <?php
      settings_fields('steel_options');
      do_settings_sections('steel');
      settings_errors();
      ?>
      <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>
  </div>
  <?php 
}

/*
 * Register settings for options page
 */
add_action('admin_init', 'steel_admin_init');
function steel_admin_init(){
  register_setting('steel_options', 'steel_options', 'steel_options_validate' );

  add_settings_section('steel_social', 'Social Media', 'steel_social_text', 'steel');

  add_settings_field('fb_app_id', 'Facebook App ID', 'fb_app_id_setting', 'steel', 'steel_social' );

  if (is_module_active('marketplace')) {
    add_settings_section('steel_marketplace', 'Marketplace', 'steel_marketplace_text', 'steel');

    add_settings_field('paypal_merch_id', 'PayPal Merchant ID', 'paypal_merch_id_setting', 'steel', 'steel_marketplace' );
  }

  add_settings_section('steel_mods', 'Modules', 'steel_mods_output', 'steel');

    add_settings_field('mod_bootstrap'  , 'Bootstrap'  , 'mod_bootstrap_setting'  , 'steel', 'steel_mods' );
    add_settings_field('mod_marketplace', 'Marketplace', 'mod_marketplace_setting', 'steel', 'steel_mods' );
  //add_settings_field('mod_podcast'    , 'Podcast'    , 'mod_podcast_setting'    , 'steel', 'steel_mods' );
  //add_settings_field('mod_quotes'     , 'Quotes'     , 'mod_quotes_setting'     , 'steel', 'steel_mods' );
  //add_settings_field('mod_shortcodes' , 'Shortcodes' , 'mod_shortcodes_setting' , 'steel', 'steel_mods' );
    add_settings_field('mod_slides'     , 'Slides'     , 'mod_slides_setting'     , 'steel', 'steel_mods' );
    add_settings_field('mod_teams'      , 'Teams'      , 'mod_teams_setting'      , 'steel', 'steel_mods' );
  //add_settings_field('mod_widgets'    , 'Widgets'    , 'mod_widgets_setting'    , 'steel', 'steel_mods' );
}
function steel_marketplace_text() { echo ''; }
function paypal_merch_id_setting() {
  $options = get_option('steel_options');

  $output  = '<input id="paypal_merch_id" name="steel_options[paypal_merch_id]" size="40" type="text" value="';
  $output .= !empty($options["paypal_merch_id"]) ? $options["paypal_merch_id"] : '';
  $output .= '">';
  echo $output;
}
function steel_social_text() { echo 'Social media profile information'; }
function fb_app_id_setting() {
  $options = get_option('steel_options');

  $output  = '<input id="fb_app_id" name="steel_options[fb_app_id]" size="40" type="text" value="';
  $output .= !empty($options["fb_app_id"]) ? $options["fb_app_id"] : '';
  $output .= '">';
  echo $output;
}
function steel_mods_output() { echo 'Activate and deactivate modules within Steel'; }
function mod_bootstrap_setting() {
  $options = get_option('steel_options');

  $bootstrap = !empty($options['mod_bootstrap']) ? $options['mod_bootstrap'] : 'both';
  steel_options('mod_bootstrap'); ?>
  
  <div class="radio-group">
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="css"  <?php checked( $bootstrap, 'css'  ) ?>>CSS&nbsp;&nbsp;</label>
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="js"   <?php checked( $bootstrap, 'js'   ) ?>>Javascript</label><br>
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="both" <?php checked( $bootstrap, 'both' ) ?>>Both</label>
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="none" <?php checked( $bootstrap, 'none' ) ?>>None</label>
  </div>

  <?php
}
function mod_marketplace_setting() {
  $options = get_option('steel_options');

  $marketplace = !empty($options['mod_marketplace']) ? $options['mod_marketplace'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_marketplace]"><input name="steel_options[mod_marketplace]" type="radio" value="true"  <?php checked( $marketplace, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_marketplace]"><input name="steel_options[mod_marketplace]" type="radio" value="false" <?php checked( $marketplace, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_podcast_setting() {
  $options = get_option('steel_options');

  $podcast = !empty($options['mod_podcast']) ? $options['mod_podcast'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_podcast]"><input name="steel_options[mod_podcast]" type="radio" value="true"  <?php checked( $podcast, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_podcast]"><input name="steel_options[mod_podcast]" type="radio" value="false" <?php checked( $podcast, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_quotes_setting() {
  $options = get_option('steel_options');

  $quotes = !empty($options['mod_quotes']) ? $options['mod_quotes'] : 'true'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_quotes]"><input name="steel_options[mod_quotes]" type="radio" value="true"  <?php checked( $quotes, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_quotes]"><input name="steel_options[mod_quotes]" type="radio" value="false" <?php checked( $quotes, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_shortcodes_setting() {
  $options = get_option('steel_options');

  $shortcodes = !empty($options['mod_shortcodes']) ? $options['mod_shortcodes'] : 'true'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_shortcodes]"><input name="steel_options[mod_shortcodes]" type="radio" value="true"  <?php checked( $shortcodes, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_shortcodes]"><input name="steel_options[mod_shortcodes]" type="radio" value="false" <?php checked( $shortcodes, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_slides_setting() {
  $options = get_option('steel_options');

  $slides = !empty($options['mod_slides']) ? $options['mod_slides'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_slides]"><input name="steel_options[mod_slides]" type="radio" value="true"  <?php checked( $slides, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_slides]"><input name="steel_options[mod_slides]" type="radio" value="false" <?php checked( $slides, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_teams_setting() {
  $options = get_option('steel_options');

  $teams = !empty($options['mod_teams']) ? $options['mod_teams'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_teams]"><input name="steel_options[mod_teams]" type="radio" value="true"  <?php checked( $teams, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_teams]"><input name="steel_options[mod_teams]" type="radio" value="false" <?php checked( $teams, 'false' ) ?>>Not Active</label>
  <div class="radio-group">
  <?php
}
function mod_widgets_setting() {
  $options = get_option('steel_options');

  $widgets = !empty($options['mod_widgets']) ? $options['mod_widgets'] : 'true'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_widgets]"><input name="steel_options[mod_widgets]" type="radio" value="true"  <?php checked( $widgets, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_widgets]"><input name="steel_options[mod_widgets]" type="radio" value="false" <?php checked( $widgets, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function steel_options_validate($input) {
  global $newinput;
  if (is_module_active('marketplace')) {
    $newinput['paypal_merch_id'] = trim($input['paypal_merch_id']);
    if(!preg_match('/^[a-z0-9]{13}$/i', $newinput['paypal_merch_id']) & !empty($newinput['paypal_merch_id'])) { add_settings_error( 'paypal_merch_id', 'invalid', 'Invalid PayPal Merchant ID. <span style="font-weight:normal;display:block;">A PayPal Merchant ID consists of 13 alphanumeric characters.</span>' ); }
    $newinput['paypal_merch_id'] = trim($input['paypal_merch_id']);
  }

  $newinput['fb_app_id'] = trim($input['fb_app_id']);
  if (!preg_match('/^[0-9]{15}$/i', $newinput['fb_app_id']) & !empty($newinput['fb_app_id'])) { add_settings_error( 'fb_app_id', 'invalid', 'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>' ); }

    $newinput['mod_bootstrap'  ] = trim($input['mod_bootstrap'  ]);
    $newinput['mod_marketplace'] = trim($input['mod_marketplace']);
  //$newinput['mod_podcast'    ] = trim($input['mod_podcast'    ]);
  //$newinput['mod_quotes'     ] = trim($input['mod_quotes'     ]);
  //$newinput['mod_shortcodes' ] = trim($input['mod_shortcodes' ]);
    $newinput['mod_slides'     ] = trim($input['mod_slides'     ]);
    $newinput['mod_teams'      ] = trim($input['mod_teams'      ]);
  //$newinput['mod_widgets'    ] = trim($input['mod_widgets'    ]);

  return $newinput;
}

/*
 * Add function steel_open
 */
function steel_open( $scripts = array() ) {
  $defaults = array('facebook' => true);
  $scripts  = wp_parse_args( $scripts, $defaults );

  if ($scripts['facebook'] == true) {
    if (steel_options('fb_app_id')) {
      $fb_app_id = steel_options('fb_app_id');
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
  if( !empty( $_GET['s'] ) && empty( $_GET['s'] ) ) { $query_vars['s'] = " "; }
    return $query_vars;
}

/*
 * Create function tweet_this
 */
function tweet_this( $data_count = 'horizontal' , $data_size = '' , $data_via = '' , $args = array() ) {
  $url      = get_permalink();
  $title    = the_title( '', '', false);
  $language = get_bloginfo( 'language' );

  $defaults = array(
    'data_url'      => $url,
    'data_text'     => $title,
    'data_related'  => '',
    'data_lang'     => $language,
    'data_counturl' => $url,
    'data_hashtags' => '',
    'data_dnt'      => '',
  );

  $args = wp_parse_args($args, $defaults);
  $args = (object) $args;

  if ( $args->data_hashtags != '' ) {
    $tweet_class = 'twitter-hashtag-button';
    $hashtag     = '#' . $args->data_hashtags;
    $link        = 'https://twitter.com/intent/tweet?button_hashtag=' . $hashtag;
  }
  else {
    $tweet_class = 'twitter-share-button';
    $hashtag     = '';
    $link        = 'https://twitter.com/share';
  }

  printf(
    '<a href="%s" class="%s" data-count="%s" data-size="%s" data-via="%s" data-url="%s" data-text="%s" data-related="%s" data-lang="%s" data-counturl="%s" data_hashtags="%s" data-dnt="%s">Tweet</a>',

    $link,
    $tweet_class,
    $data_count,
    $data_size,
    $data_via,
    $args->data_url,
    $args->data_text,
    $args->data_related,
    $args->data_lang,
    $args->data_counturl,
    $args->data_hashtags,
    $args->data_dnt
  );
  printf('<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>');
}

/*
 * Create function like_this (Facebook)
 */
function like_this( $args = array() ) {
  $url = get_permalink();

  $defaults = array(
    'data_href'       => $url,
    'data_send'       => 'false',
    'data_layout'     => 'standard',
    'data_show_faces' => 'false',
    'data_width'      => '450',
    'data_action'     => 'like',
    'data_font'       => 'lucida grande',
    'data_color'      => 'light',
    'data_ref'        => '',
  );

  $args = wp_parse_args($args, $defaults);
  $args = (object) $args;

  printf(
    '<div class="fb-like" data-href="%s" data-send="%s" data-layout="%s" data-show-faces="%s" data-width="%s" data-action="%s" data-font="%s" data-colorscheme="%s" data-ref="%s"></div>',

    $args->data_href,
    $args->data_send,
    $args->data_layout,
    $args->data_show_faces,
    $args->data_width,
    $args->data_action,
    $args->data_font,
    $args->data_color,
    $args->data_ref
  );
}

/*
 * Create function pin_it (Pinterest)
 */
function pin_it( $args = array() ) {
  $url       = get_permalink();
  $title     = the_title( '', '', false);
  $thumb_id  = get_post_thumbnail_id();
  $thumbnail = wp_get_attachment_url( $thumb_id );

  $defaults = array(
    'data_url' => $url,
    'data_thumb' => $thumbnail,
    'data_text' => $title,
    'data_count' => 'horizontal',
  );

  $args = wp_parse_args($args, $defaults);
  $args = (object) $args;

  printf(
    '<a href="http://pinterest.com/pin/create/button/?url=%s&media=%s&description=%s" class="pin-it-button" count-layout="%s"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>',

    $args->data_url,
    $args->data_thumb,
    $args->data_text,
    $args->data_count
  );
}

/*
 * Add function is_module_active
 */
function is_module_active( $mod, $check = null ) {
  $module = steel_options( 'mod_' . $mod );
  $default_on  = array('quotes','shortcodes','widgets');
  if ($mod == 'bootstrap') :
    $mod_status = !empty($module) ? !empty($check) && $module == $check ? 'true' : 'false' : 'true';
  elseif (in_array($mod, $default_on)) :
    $mod_status = !empty($module) ? $module : 'true';
  else :
    $mod_status = !empty($module) ? $module : 'false';
  endif;

  if ($mod_status == 'true')
    return true;
  else
    return false;
}

/*
 * Add function steel_options
 */
function steel_options( $key ) {
  $options = get_option('steel_options');
  if (empty($options[ $key ])) :
    return false;
  else :
    return $options[ $key ];
  endif;
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

/*
 * Add function is_flint_active
 */
function is_flint_active() {
  $theme = wp_get_theme();
  $name = $theme->get( 'Name' );
  $template = $theme->get( 'Template' );
  $template = !empty($template) ? $template : strtolower ( $name );
  if ($template == 'flint')
    return true;
  else
    return false;
}

/*
 * Add function steel_get_image_url
 */
function steel_get_image_url( $attachment_id, $size = 'thumbnail', $icon = false ) {
  $image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
  return $image[0];
}

/*
 * Display custom metadata
 */
function steel_meta( $mod_prefix, $key, $post_id = NULL ) {
  global $post;
  $custom = $post_id == NULL ? get_post_custom($post->ID) : get_post_custom($post_id);
  $meta = !empty($custom[$mod_prefix.'_'.$key][0]) ? $custom[$mod_prefix.'_'.$key][0] : '';
  return $meta;
}
?>
