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
  $labels = array(
    'name'                  => _x( 'Cards', 'post type general name', 'steel' ),
    'singular_name'         => _x( 'Card', 'post type singular name', 'steel' ),
    'menu_name'             => __( 'Cards', 'steel' ),
    'name_admin_bar'        => __( 'Cards', 'steel' ),
    'add_new'               => __( 'Add New', 'steel' ),
    'add_new_item'          => __( 'Add New Card', 'steel' ),
    'edit_item'             => __( 'Edit Card', 'steel' ),
    'new_item'              => __( 'New Card', 'steel' ),
    'view_item'             => __( 'View Card', 'steel' ),
    'update_item'           => __( 'Update', 'steel' ),
    'search_items'          => __( 'Search Cards', 'steel' ),
    'not_found'             => __( 'No cards found.', 'steel' ),
    'not_found_in_trash'    => __( 'No cards found in Trash.', 'steel' ),
    'all_items'             => __( 'All Cards', 'steel' ),
    'archives'              => __( 'Card Archives', 'steel' ),
    'insert_into_item'      => __( 'Insert into card', 'steel' ),
    'uploaded_to_this_item' => __( 'Uploaded to this card', 'steel' ),
    'featured_image'        => __( 'Featured Image', 'steel' ),
    'set_featured_image'    => __( 'Set featured image', 'steel' ),
    'remove_featured_image' => __( 'Remove featured image', 'steel' ),
    'use_featured_image'    => __( 'Use as featured image', 'steel' ),
    'filter_items_list'     => __( 'Filter cards list', 'steel' ),
    'items_list_navigation' => __( 'Cards list navigation', 'steel' ),
    'items_list'            => __( 'Cards list', 'steel' ),
  );
  $args = array(
    'label'               => __( 'msx_card', 'steel' ),
    'description'         => __( 'A card', 'steel' ),
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

