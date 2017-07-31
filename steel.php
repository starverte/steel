<?php
/**
 * Plugin Name: Steel
 * Plugin URI: https://github.com/starverte/steel.git
 * Description: Steel brings the power of Matchstix to a simple user interface, making any site’s impact spread like wildfire. No programming required.
 * Version: 1.4.2
 * Author: Star Verte LLC
 * Author URI: http://starverte.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/
 *
 *   Copyright 2013-2016 Star Verte LLC (email : dev@starverte.com)
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Steel
 */

include_once dirname( __FILE__ ) . '/bootstrap/bootstrap.php';
include_once dirname( __FILE__ ) . '/bootstrap/shortcodes.php';
include_once dirname( __FILE__ ) . '/deprecated/deprecated.php';
include_once dirname( __FILE__ ) . '/options.php';

if ( steel_module_status( 'broadcast' ) ) {
  include_once dirname( __FILE__ ) . '/broadcast/broadcast.php';
  include_once dirname( __FILE__ ) . '/broadcast/post.php';
}

if ( steel_module_status( 'cards' ) ) {
  include_once dirname( __FILE__ ) . '/cards/cards.php';
}

if ( steel_module_status( 'quotes' ) ) {
  include_once dirname( __FILE__ ) . '/quotes/quotes.php';
}

if ( steel_module_status( 'social_media' ) ) {
  include_once dirname( __FILE__ ) . '/social-media/social-media.php';
}

if ( steel_module_status( 'teams' ) ) {
  include_once dirname( __FILE__ ) . '/teams/teams.php';
}

if ( steel_module_status( 'widgets' ) ) {
  include_once dirname( __FILE__ ) . '/widgets/widgets.php';
}

if ( function_exists( 'flint_the_content' ) ) {
  include_once dirname( __FILE__ ) . '/templates/steel-profile.php';
}

/**
 * Register and load admin scripts and styles
 *
 * @internal
 */
function steel_admin_enqueue_scripts() {
  global $post_type;
  global $taxonomy;
  wp_enqueue_style( 'dashicons' );
  wp_enqueue_style( 'bs-glyphicons', plugins_url( 'steel/css/glyphicons.css' ) );
  wp_enqueue_style( 'bs-grid', plugins_url( 'steel/css/grid.css' ) );
  wp_enqueue_style( 'steel-admin-style', plugins_url( 'steel/css/admin.css' ) );
  wp_enqueue_style( 'steel-font', plugins_url( 'steel/css/starverte.css' ) );
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'jquery-ui-core' );
  wp_enqueue_script( 'jquery-ui-accordion' );
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_script( 'jquery-ui-sortable' );
  wp_enqueue_script( 'jquery-ui-position' );
  wp_enqueue_script( 'jquery-effects-core' );
  wp_enqueue_script( 'jquery-effects-blind' );
  wp_enqueue_media();

  wp_enqueue_script(
    'functions',
    plugins_url( 'steel/js/functions.js' ),
    array( 'jquery' ),
    '1.4.0',
    true
  );

  if ( 'steel_broadcast' == $post_type ) {
    wp_enqueue_script(
      'broadcast-edit',
      plugins_url( 'steel/broadcast/edit.js' ),
      array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ),
      '1.4.0',
      true
    );

    wp_enqueue_style( 'broadcast-style-admin', plugins_url( 'steel/broadcast/admin.css' ) );
  };

  if ( 'steel_broadcast_channel' == $taxonomy ) {
    wp_enqueue_script(
      'broadcast-channel-edit',
      plugins_url( 'steel/broadcast/channel-edit.js' ),
      array( 'jquery' ),
      '1.4.0',
      true
    );
  }

  if ( 'msx_card_deck' == $post_type ) {
    wp_enqueue_script(
      'cards-admin-script',
      plugins_url( 'steel/cards/admin.js' ),
      array( 'jquery' ),
      '1.4.0',
      true
    );

    wp_enqueue_style( 'cards-admin-style', plugins_url( 'steel/cards/admin.css' ) );
  }
}
add_action( 'admin_enqueue_scripts', 'steel_admin_enqueue_scripts' );

/**
 * Register and load display scripts and styles
 *
 * @internal
 */
function steel_enqueue_scripts() {
  $options = steel_get_options();

  if ( true === $options['load_bootstrap_js'] ) {
    wp_deregister_script( 'bootstrap' );

    wp_enqueue_script(
      'bootstrap',
      '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
      array( 'jquery' ),
      '3.3.7',
      true
    );
  }

  if ( true === $options['load_bootstrap_css'] ) {
    wp_deregister_style( 'bootstrap-css' );

    wp_enqueue_style(
      'bootstrap-css',
      '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
      array(),
      '3.3.7'
    );
  } else {
  wp_enqueue_style(
    'glyphicons',
    plugins_url( 'steel/css/glyphicons.css' ),
    array(),
    '3.3.6'
    );
  }

  wp_enqueue_script( 'pin-it-button', '//assets.pinterest.com/js/pinit.js' );

  wp_enqueue_script(
    'steel-run',
    plugins_url( '/steel/js/run.js' ),
    array( 'jquery' ),
    '1.4.0',
    true
  );
}
add_action( 'wp_enqueue_scripts', 'steel_enqueue_scripts' );

/**
 * Add Facebook code at top of body
 *
 * @internal
 */
function steel_open() {
  $options = steel_get_options();
  if ( true === $options['load_facebook'] && ! empty( $options['fb_app_id'] ) ) { ?>
<div id="fb-root"></div>
<script>
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
      return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $options['fb_app_id']; ?>";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
<?php
  } else {
    return;
  }
}
add_action( 'flint_open','steel_open' );

/**
 * Empty search fix
 *
 * @internal
 *
 * @param array $query_vars The array of requested query variables.
 */
function steel_request( $query_vars ) {
  if ( ! empty( $_GET['s'] ) && empty( $_GET['s'] ) ) {
    $query_vars['s'] = ' ';
  }

  return $query_vars;
}
add_filter( 'request', 'steel_request' );

/**
 * Check to see if a particular Steel module is active.
 *
 * @param string $module Module name to check.
 */
function steel_module_status( $module ) {
  $options = steel_get_options();

  if ( true === $options[ 'load_' . $module ] ) {
    return true;
  } else {
    return false;
  }
}

/**
 * Retrieve post meta field, based on post ID, module, and key.
 *
 * The post meta fields are retrieved from the cache where possible,
 * so the function is optimized to be called more than once.
 *
 * @see WordPress 4.3.1 get_post_custom()
 *
 * @param string $module  The module (prefix) to use in key.
 * @param string $key     The meta key minus the module prefix.
 * @param int    $post_id Optional. Post ID. Default is ID of the global $post.
 * @return string Value for post meta for the given post and given key.
 */
function steel_meta( $module, $key, $post_id = 0 ) {
  global $post;
  $custom = get_post_custom( $post_id );
  $meta = ! empty( $custom[ $module . '_' . $key ][0] ) ? $custom[ $module . '_' . $key ][0] : '';
  return $meta;
}

/**
 * Add Google Analytics script to footer
 *
 * @internal
 */
function steel_ga_load() {
  $options = steel_get_options();
  $ga_id = $options['ga_id'];
  if ( ! empty( $ga_id ) ) {
    if ( is_user_logged_in() ) {
?>
<!-- Google Analytics code disabled because user is logged in. -->
    <?php
    } else {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', '<?php echo $ga_id; ?>', 'auto');
  ga('send', 'pageview');
</script>
<?php
    }
  }
}
add_action( 'wp_head','steel_ga_load' );

/**
 * Add action to check if plugin has been updated
 *
 * @internal
 */
function steel_plugins_loaded() {
  $steel_db = 160530;
  if ( get_site_option( 'steel_db_version' ) != $steel_db ) {
    do_action( 'steel_register_update_hook' );
    update_site_option( 'steel_db_version', 160530 );
  }
}
add_action( 'plugins_loaded', 'steel_plugins_loaded' );

/**
 * On update, convert steel_slides to msx_card/msx_card_deck
 *
 * @internal
 */
function steel_slides_x_cards() {
  $steel_slides = get_posts(
    array(
      'post_type' => 'steel_slides',
      'posts_per_page' => -1,
    )
  );

  foreach ( $steel_slides as $slideshow ) {
    $cards = explode( ',', get_post_meta( $slideshow->ID, 'slides_order', true ) );
    $cards_order = '';
    $custom = get_post_custom( $slideshow->ID );

    foreach ( $cards as $card ) {
      if ( ! empty( $card ) ) {
        $new_card = wp_insert_post(
          array(
            'post_title' => $custom[ 'slides_title_' . $card ][0],
            'post_content' => $custom[ 'slides_content_' . $card ][0],
            'post_parent' => $card,
            'post_type' => 'msx_card',
            'post_status' => 'publish',
          )
        );

        set_post_format( $new_card, 'image' );
        update_post_meta( $new_card, 'target', $custom[ 'slides_link_' . $card ][0] );
        update_post_meta( $new_card, 'image', $card );
        delete_post_meta( $slideshow->ID, 'slides_title_' . $card );
        delete_post_meta( $slideshow->ID, 'slides_content_' . $card );
        delete_post_meta( $slideshow->ID, 'slides_link_' . $card );
        $cards_order .= $new_card . ',';
      }
    }

    delete_post_meta( $slideshow->ID, 'slides_order' );
    update_post_meta( $slideshow->ID, 'cards_order', $cards_order );
    set_post_type( $slideshow->ID, 'msx_card_deck' );
  }
}
add_action( 'steel_register_update_hook', 'steel_slides_x_cards' );

/**
 * Load editor on posts page if option selected
 *
 * @internal
 *
 * @param WP_Post $post Post object.
 */
function steel_load_editor_posts_page( $post ) {
  if ( get_option( 'page_for_posts' ) != $post->ID ) {
    return;
  }

  remove_action( 'edit_form_after_title', '_wp_posts_page_notice' );
  add_post_type_support( 'page', 'editor' );
}
if ( steel_module_status( 'editor_posts_page' ) ) {
  add_action( 'edit_form_after_title', 'steel_load_editor_posts_page', 0 );
}

/**
 * Alter display of shortlinks for 'product' post type.
 *
 * @param string $shortlink   Shortlink URL.
 * @param int    $id          Post ID, or 0 for the current post.
 * @param string $context     The context for the link. One of 'post' or 'query'.
 */
function steel_product_get_shortlink( $shortlink, $id, $context ) {
  $post_id = 0;

  if ( 'query' == $context && is_singular( 'product' ) ) {
    $post_id = get_queried_object_id();
  } elseif ( 'post' == $context ) {
    $post_id = $id;
  }

  if ( 'product' == get_post_type( $post_id ) ) {
    $shortlink = home_url( '?p=' . $post_id );
  }

  return $shortlink;
}
add_filter( 'pre_get_shortlink', 'steel_product_get_shortlink', 10, 3 );
