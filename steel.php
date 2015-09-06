<?php
/*
Plugin Name: Steel
Plugin URI: https://github.com/starverte/steel.git
Description: Core plugin of the Sparks Framework. Works for any theme; but when paired with Flint your WordPress site will be on fire.
Version: 1.2.7
Author: Star Verte LLC
Author URI: http://starverte.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/

  Copyright 2013-2015 Star Verte LLC (email : dev@starverte.com)

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

include_once dirname( __FILE__ ) . '/bootstrap.php';
include_once dirname( __FILE__ ) . '/options.php';

if (steel_is_module_active('podcast'     )) { include_once dirname( __FILE__ ) . '/podcast.php';     }
if (steel_is_module_active('quotes'      )) { include_once dirname( __FILE__ ) . '/quotes.php';      }
if (steel_is_module_active('shortcodes'  )) { include_once dirname( __FILE__ ) . '/shortcodes.php';  }
if (steel_is_module_active('social_media')) { include_once dirname( __FILE__ ) . '/social_media.php';}
if (steel_is_module_active('slides'      )) { include_once dirname( __FILE__ ) . '/slides.php';      }
if (steel_is_module_active('teams'       )) { include_once dirname( __FILE__ ) . '/teams.php';       }
if (steel_is_module_active('widgets'     )) { include_once dirname( __FILE__ ) . '/widgets.php';     }

if (steel_is_flint_active()) { include_once dirname( __FILE__ ) . '/templates.php'; }

/**
 * Load scripts
 */
add_action( 'admin_enqueue_scripts', 'steel_admin_enqueue_scripts' );
function steel_admin_enqueue_scripts() {
  wp_enqueue_style( 'dashicons'                                                  );
  wp_enqueue_style( 'bs-glyphicons'    , plugins_url('steel/css/glyphicons.css') );
  wp_enqueue_style( 'bs-grid'          , plugins_url('steel/css/grid.css'      ) );
  wp_enqueue_style( 'steel-admin-style', plugins_url('steel/css/admin.css'     ) );
  wp_enqueue_style( 'steel-font'       , plugins_url('steel/css/starverte.css' ) );

  wp_enqueue_script( 'jquery'              );
  wp_enqueue_script( 'jquery-ui-core'      );
  wp_enqueue_script( 'jquery-ui-accordion');
  wp_enqueue_script( 'jquery-ui-datepicker');
  wp_enqueue_script( 'jquery-ui-sortable'  );
  wp_enqueue_script( 'jquery-ui-position'  );
  wp_enqueue_script( 'jquery-effects-core' );
  wp_enqueue_script( 'jquery-effects-blind');

  wp_enqueue_media();

  if (steel_is_module_active('podcast')) {
    wp_enqueue_script( 'podcast-mod', plugins_url('steel/js/podcast.js'  ), array('jquery'), '1.2.7', true );
    wp_enqueue_script( 'podcast-channel', plugins_url('steel/js/podcast-channel.js'  ), array('jquery'), '1.2.7', true );
  }

  if (steel_is_module_active('slides')) {
    wp_enqueue_script( 'slides-mod', plugins_url('steel/js/slides.js'  ), array('jquery'), '1.2.7', true );
  }
}
add_action( 'wp_enqueue_scripts', 'steel_enqueue_scripts' );
function steel_enqueue_scripts() {
  $options = steel_get_options();

  if (true === $options['load_bootstrap_js']) {
    // Make sure there aren't other instances of Twitter Bootstrap
    wp_deregister_script('bootstrap');

    // Load Twitter Bootstrap
    wp_enqueue_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array('jquery'), '3.3.5', true );
  }

  if (true === $options['load_bootstrap_css']) {
    // Make sure there aren't other instances of Twitter Bootstrap
    wp_deregister_style('bootstrap-css');

    // Load Twitter Bootstrap
    wp_enqueue_style( 'bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css', array() , '3.3.5' );
  }
  else {
    wp_enqueue_style( 'glyphicons', plugins_url('steel/css/glyphicons.css'), array() , '3.3.5' );
  }

  if (steel_is_module_active('slides')) {
    wp_enqueue_style( 'slides-mod-style', plugins_url('steel/css/slides.css'  ), array(), '1.2.7');
  }

  // Load script for "Pin It" button
  wp_enqueue_script( 'pin-it-button', 'http://assets.pinterest.com/js/pinit.js');

  // Load front-end scripts
  wp_enqueue_script( 'steel-run', plugins_url( '/steel/js/run.js' ), array('jquery'), '1.2.7', true );
}

/*
 * Add function steel_open
 */
add_action('flint_open','steel_open');
function steel_open() {
  $options = steel_get_options();

  if (true === $options['load_facebook']  && !empty($options['fb_app_id'])) {
      echo '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=' . $options['fb_app_id'] . '"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\')); </script>';
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

/**
 * Check to see if a particular Steel module is active.
 */
function steel_is_module_active( $module ) {
  $options = steel_get_options();

  if ( true === $options['load_'.$module] )
    return true;
  else
    return false;
}

/**
 * Check to see if theme Flint is active.
 */
function steel_is_flint_active() {
  $theme = wp_get_theme();
  $name = $theme->get( 'Name' );
  $template = $theme->get( 'Template' );
  $template = !empty($template) ? $template : strtolower ( $name );
  if ( 'flint' === $template )
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
  $custom = NULL === $post_id ? get_post_custom($post->ID) : get_post_custom($post_id);
  $meta = !empty($custom[$mod_prefix.'_'.$key][0]) ? $custom[$mod_prefix.'_'.$key][0] : '';
  return $meta;
}

add_action('wp_footer','steel_footer');
function steel_footer() {
  $options = steel_get_options();

  $ga_id = $options['ga_id'];

  if (!empty($ga_id)) {
    if (is_user_logged_in()) { ?>
      <!-- Google Analytics code disabled because user is logged in. -->
      <?php
    }
    else { ?>
      <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo $ga_id; ?>']);
        _gaq.push(['_trackPageview']);
        (function() {
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
      </script><?php
    }
  }
}

function steel_widgets_init() {
  if (steel_is_module_active('quotes')) {
    register_widget( 'Steel_Quotes_Widget' );
  }
  if (steel_is_module_active('widgets')) {
    register_widget( 'Steel_Widget_Button' );
    register_widget( 'Steel_Link_Widget_Legacy' );
    register_widget( 'Steel_Nav_Menu_Widget' );
  }
}
add_action( 'widgets_init', 'steel_widgets_init' );

function steel_init() {
  if ( steel_is_module_active('podcast') ) {
    $args = steel_get_podcast_args();
    register_post_type( 'steel_podcast', $args );
    add_image_size( 'steel-episode-thumb', 300, 185, true);
  }
  if ( steel_is_module_active('slides') ) {
    $args = steel_get_slides_args();
    register_post_type( 'steel_slides', $args );
    add_image_size( 'steel-slide-thumb', 300, 185, true);
  }
  if ( steel_is_module_active('teams') ) {
    $profile_args = steel_get_profile_args();
    $team_args = steel_get_team_args();
    register_post_type( 'steel_profile', $profile_args );
    register_taxonomy( 'steel_team', 'steel_profile', $team_args );
  }
}
add_action( 'init', 'steel_init', 0 );

function steel_add_meta_boxes() {
  if ( steel_is_module_active('podcast') ) {
    add_meta_box( 'steel_podcast_episode_list', 'Add/Edit Series'   , 'steel_podcast_episode_list', 'steel_podcast', 'side', 'high'  );
    add_meta_box( 'steel_podcast_info'        , 'Using this Podcast', 'steel_podcast_info'        , 'steel_podcast', 'side');
    add_meta_box( 'steel_podcast_settings'    , 'Podcast Settings'  , 'steel_podcast_settings'    , 'steel_podcast', 'side');
  }
  if ( steel_is_module_active('slides') ) {
    add_meta_box( 'steel_slides_slideshow', 'Add/Edit Slides'     , 'steel_slides_slideshow', 'steel_slides', 'advanced', 'high' );
    add_meta_box( 'steel_slides_info'     , 'Using this Slideshow', 'steel_slides_info'     , 'steel_slides', 'side' );
    add_meta_box( 'steel_slides_settings' , 'Slideshow Settings'  , 'steel_slides_settings' , 'steel_slides', 'side' );
  }
  if ( steel_is_module_active('teams') ) {
    add_meta_box( 'steel_teams_meta', 'Team Member Profile', 'steel_teams_meta', 'steel_profile', 'side', 'high' );
  }
}
add_action( 'add_meta_boxes', 'steel_add_meta_boxes' );
