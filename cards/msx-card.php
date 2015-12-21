<?php
/**
 * Matchstix Card (custom post type)
 *
 * @package MSX\Cards
 *
 * @since 0.2.0
 */

/**
 * Return arguments for registering msx_deck
 */
function msx_card_post_type_args() {
  global $msx_text_domain;
  $labels = array(
    'name'                  => _x( 'Cards', 'post type general name', $msx_text_domain ),
    'singular_name'         => _x( 'Card', 'post type singular name', $msx_text_domain ),
    'menu_name'             => __( 'Cards', $msx_text_domain ),
    'name_admin_bar'        => __( 'Cards', $msx_text_domain ),
    'add_new'               => __( 'Add New', $msx_text_domain ),
    'add_new_item'          => __( 'Add New Card', $msx_text_domain ),
    'edit_item'             => __( 'Edit Card', $msx_text_domain ),
    'new_item'              => __( 'New Card', $msx_text_domain ),
    'view_item'             => __( 'View Card', $msx_text_domain ),
    'update_item'           => __( 'Update', $msx_text_domain ),
    'search_items'          => __( 'Search Cards', $msx_text_domain ),
    'not_found'             => __( 'No cards found.', $msx_text_domain ),
    'not_found_in_trash'    => __( 'No cards found in Trash.', $msx_text_domain ),
    'all_items'             => __( 'All Cards', $msx_text_domain ),
    'archives'              => __( 'Card Archives', $msx_text_domain ),
    'insert_into_item'      => __( 'Insert into card', $msx_text_domain ),
    'uploaded_to_this_item' => __( 'Uploaded to this card', $msx_text_domain ),
    'featured_image'        => __( 'Featured Image', $msx_text_domain ),
    'set_featured_image'    => __( 'Set featured image', $msx_text_domain ),
    'remove_featured_image' => __( 'Remove featured image', $msx_text_domain ),
    'use_featured_image'    => __( 'Use as featured image', $msx_text_domain ),
    'filter_items_list'     => __( 'Filter cards list', $msx_text_domain ),
    'items_list_navigation' => __( 'Cards list navigation', $msx_text_domain ),
    'items_list'            => __( 'Cards list', $msx_text_domain ),
  );
  $args = array(
    'label'               => __( 'msx_card', $msx_text_domain ),
    'description'         => __( 'A card', $msx_text_domain ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'author', 'thumbnail', 'post-formats' ),
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
