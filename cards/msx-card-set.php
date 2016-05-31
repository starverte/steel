<?php
/**
 * Matchstix Set of Cards (custom taxonomy)
 *
 * @package MSX\Cards
 *
 * @since 0.2.0
 */

/**
 * Return arguments for registering msx_card_set
 */
function msx_card_set_taxonomy_args() {

  $labels = array(
    'name'                       => _x( 'Card Sets', 'taxonomy general name', 'steel' ),
    'singular_name'              => _x( 'Card Set', 'taxonomy singular name', 'steel' ),
    'menu_name'                  => __( 'Sets', 'steel' ),
    'search_items'               => __( 'Search Sets', 'steel' ),
    'popular_items'              => __( 'Popular Sets', 'steel' ),
    'all_items'                  => __( 'All Sets', 'steel' ),
    'edit_item'                  => __( 'Edit Set', 'steel' ),
    'view_item'                  => __( 'View Set', 'steel' ),
    'update_item'                => __( 'Update Set', 'steel' ),
    'add_new_item'               => __( 'Add New Set', 'steel' ),
    'new_item_name'              => __( 'New Set Name', 'steel' ),
    'separate_items_with_commas' => __( 'Separate sets with commas' ),
    'add_or_remove_items'        => __( 'Add or remove sets' ),
    'choose_from_most_used'      => __( 'Choose from the most used sets' ),
    'not_found'                  => __( 'No sets found.', 'steel' ),
    'no_terms'                   => __( 'No sets', 'steel' ),
    'items_list_navigation'      => __( 'Sets list navigation', 'steel' ),
    'items_list'                 => __( 'Sets list', 'steel' ),
  );

  $rewrite = array(
    'slug'                       => 'card-set',
    'with_front'                 => true,
    'hierarchical'               => false,
  );

  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => false,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => false,
    'rewrite'                    => $rewrite,
  );

  return $args;
}

