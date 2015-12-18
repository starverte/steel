<?php
/**
 * Allows creation of cards (sub-post objects) to be displayed in multiple ways
 *
 * Replaces previous Slides. A slide is now a card. A slideshow is now a deck.
 *
 * @package Steel\Cards
 *
 * @since 1.4.0
 */

include_once dirname( __FILE__ ) . '/msx-card.php';
include_once dirname( __FILE__ ) . '/msx-card-deck.php';
include_once dirname( __FILE__ ) . '/msx-card-set.php';

/**
 * Add theme support for post formats used by Cards
 *
 * @todo Add support for 'gallery' format.
 * @todo Add support for 'quote' format.
 * @todo Add support for 'status' format.
 * @todo Add support for 'audio' format.
 * @todo Add support for 'chat' format.
 */
function steel_cards_after_setup_theme() {
  global $_wp_theme_features;

  if ( empty( $_wp_theme_features['post-formats'] ) ) {
    $_wp_theme_features['post-formats'] = array( array( 'image', 'video', 'link' ) );
  } elseif ( true === $_wp_theme_features['post-formats'] ) {
    return;
  } else {
    $_wp_theme_features['post-formats'][0][] = 'image';
    $_wp_theme_features['post-formats'][0][] = 'video';
    $_wp_theme_features['post-formats'][0][] = 'link';
  }
}
add_action( 'after_setup_theme', 'steel_cards_after_setup_theme', 100 );

/**
 * Register custom post type and image size
 */
function steel_cards_init() {
  register_post_type( 'msx_card', msx_card_post_type_args() );
  register_post_type( 'msx_card_deck', msx_card_deck_post_type_args() );
  register_taxonomy( 'msx_card_set', array( 'msx_card', 'msx_card_deck' ), msx_card_set_taxonomy_args() );
  add_image_size( 'msx-card-thumb', 300, 185, true );
}
add_action( 'init', 'steel_cards_init' );

/**
 * Add meta boxes to Edit screen
 */
function steel_card_deck_add_meta_boxes() {
  add_meta_box(
    'msx_card_deck_edit',
    'Cards',
    'msx_card_deck_edit',
    'msx_card_deck',
    'advanced',
    'high'
  );
}
add_action( 'add_meta_boxes', 'steel_card_deck_add_meta_boxes' );

/**
 * Retrieve post meta field, based on post ID and key.
 *
 * The post meta fields are retrieved from the cache where possible,
 * so the function is optimized to be called more than once.
 *
 * @see WordPress 4.3.1 get_post_custom()
 *
 * @param string $key     The meta key minus the module prefix.
 * @param int    $post_id Optional. Post ID. Default is ID of the global $post.
 * @return string Value for post meta for the given post and given key.
 */
function steel_deck_meta( $key, $post_id = 0 ) {
  $meta = steel_meta( 'deck', $key, $post_id );
  return $meta;
}

/**
 * Return IDs for all decks
 */
function steel_get_decks() {
  $args = array( 'post_type' => 'msx_decks', 'posts_per_page' => -1 );
  $msx_decks = get_posts( $args );
  $decks = array();
  $decks[0] = 'None';
  if ( $msx_decks ) {
    foreach ( $msx_decks as $deck ) {
      $post_id = $deck->ID;
      $title = $deck->post_title;
      $decks[ $post_id ] = $title;
    }
    wp_reset_postdata();
  }
  return $decks;
}

/**
 * Sanitize options based on steel_get_decks
 *
 * @param mixed $input Unfiltered input.
 */
function steel_sanitize_get_decks( $input ) {
  $valid = steel_get_decks( 'options' );

  if ( array_key_exists( $input, $valid ) ) {
    return $input;
  } else {
    return;
  }
}
