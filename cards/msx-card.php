<?php
/**
 * Matchstix Card (custom post type)
 *
 * @package MSX\Cards
 *
 * @since 0.1.0
 */

/**
 * Return arguments for registering msx_deck
 */
function msx_card_post_type_args() {
  global $msx_text_domain;
  $labels = array(
    'name'                => _x( 'Cards', 'Post Type General Name', $msx_text_domain ),
    'singular_name'       => _x( 'Card', 'Post Type Singular Name', $msx_text_domain ),
    'menu_name'           => __( 'Cards', $msx_text_domain ),
    'all_items'           => __( 'All cards', $msx_text_domain ),
    'view_item'           => __( 'View', $msx_text_domain ),
    'add_new_item'        => __( 'Add New', $msx_text_domain ),
    'add_new'             => __( 'New', $msx_text_domain ),
    'edit_item'           => __( 'Edit', $msx_text_domain ),
    'update_item'         => __( 'Update', $msx_text_domain ),
    'search_items'        => __( 'Search cards', $msx_text_domain ),
    'not_found'           => __( 'No cards found', $msx_text_domain ),
    'not_found_in_trash'  => __( 'No cards found in Trash. Did you check recycling?', $msx_text_domain ),
  );
  $args = array(
    'label'               => __( 'msx_card', $msx_text_domain ),
    'description'         => __( 'A card', $msx_text_domain ),
    'labels'              => $labels,
    'supports'            => array( 'title' ),
    'hierarchical'        => false,
    'public'              => false,
    'show_ui'             => true,
    'show_in_menu'        => false,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => false,
    'menu_position'       => 15,
    'menu_icon'           => 'dashicons-slides',
    'can_export'          => false,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'rewrite'             => true,
    'capability_type'     => 'post',
  );
  return $args;
}
