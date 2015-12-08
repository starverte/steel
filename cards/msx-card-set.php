<?php
/**
 * Matchstix Deck of Cards (custom post type)
 *
 * @package MSX\Cards
 *
 * @since 0.2.0
 */

/**
 * Return arguments for registering msx_card_set
 */
function msx_card_set_taxonomy_args() {
  global $msx_text_domain;

  $labels = array(
    'name'                       => _x( 'Card Sets', 'Taxonomy General Name', $msx_text_domain ),
    'singular_name'              => _x( 'Card Set', 'Taxonomy Singular Name', $msx_text_domain ),
    'menu_name'                  => __( 'Sets', $msx_text_domain ),
    'all_items'                  => __( 'All sets', $msx_text_domain ),
    'new_item_name'              => __( 'New Set Name', $msx_text_domain ),
    'add_new_item'               => __( 'Add New Set', $msx_text_domain ),
    'edit_item'                  => __( 'Edit Set', $msx_text_domain ),
    'update_item'                => __( 'Update Set', $msx_text_domain ),
    'view_item'                  => __( 'View Set', $msx_text_domain ),
    'separate_items_with_commas' => __( 'Separate card sets with commas', $msx_text_domain ),
    'add_or_remove_items'        => __( 'Add or remove card sets', $msx_text_domain ),
    'choose_from_most_used'      => __( 'Choose from the most used', $msx_text_domain ),
    'popular_items'              => __( 'Popular Sets', $msx_text_domain ),
    'search_items'               => __( 'Search card sets', $msx_text_domain ),
    'not_found'                  => __( 'No card sets found', $msx_text_domain ),
    'items_list'                 => __( 'Card sets list', $msx_text_domain ),
    'items_list_navigation'      => __( 'Card sets list navigation', $msx_text_domain ),
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
