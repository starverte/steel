<?php
/*
 * Creates custom post-type "quotes" for displaying random quotes, testimonials, etc.
 *
 * @package Sparks
 * @sub-package Steel
 *
 * @since 0.7.0
 */
 
/*
 * Create custom post type steel_quote and custom taxonomy steel_quote_lists
 */
add_action( 'init', 'steel_quotes_init', 0 );
function steel_quotes_init() {
 $post_labels = array(
		'name'                => _x( 'Quotes', 'Post Type General Name', 'steel' ),
		'singular_name'       => _x( 'Quote', 'Post Type Singular Name', 'steel' ),
		'menu_name'           => __( 'Quotes', 'steel' ),
		'parent_item_colon'   => __( 'Parent Quote:', 'steel' ),
		'all_items'           => __( 'All Quotes', 'steel' ),
		'view_item'           => __( 'View Quote', 'steel' ),
		'add_new_item'        => __( 'Add New Quote', 'steel' ),
		'add_new'             => __( 'New Quote', 'steel' ),
		'edit_item'           => __( 'Edit Quote', 'steel' ),
		'update_item'         => __( 'Update Quote', 'steel' ),
		'search_items'        => __( 'Search quotes', 'steel' ),
		'not_found'           => __( 'No quotes found', 'steel' ),
		'not_found_in_trash'  => __( 'No quotes found in Trash. Did you check recycling?', 'steel' ),
	);

	$post_args = array(
		'label'               => __( 'steel_quote', 'steel' ),
		'description'         => __( 'Quotes', 'steel' ),
		'labels'              => $post_labels,
		'supports'            => array( 'title', 'excerpt', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 100,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => false,
		'capability_type'     => 'post',
	);

	register_post_type( 'steel_quote', $post_args );
 
 $tax_labels = array(
   'name'                       => _x( 'Quote Lists', 'Taxonomy General Name', 'steel' ),
   'singular_name'              => _x( 'Quote List', 'Taxonomy Singular Name', 'steel' ),
   'menu_name'                  => __( 'Quote List', 'steel' ),
   'all_items'                  => __( 'All Lists', 'steel' ),
   'parent_item'                => __( 'Parent List', 'steel' ),
   'parent_item_colon'          => __( 'Parent List:', 'steel' ),
   'new_item_name'              => __( 'New List Name', 'steel' ),
   'add_new_item'               => __( 'Add New Quote List', 'steel' ),
   'edit_item'                  => __( 'Edit List', 'steel' ),
   'update_item'                => __( 'Update List', 'steel' ),
   'separate_items_with_commas' => __( 'Separate lists with commas', 'steel' ),
   'search_items'               => __( 'Search quote lists', 'steel' ),
   'add_or_remove_items'        => __( 'Add or remove quote lists', 'steel' ),
   'choose_from_most_used'      => __( 'Choose from the most used lists', 'steel' ),
	);

	$tax_args = array(
		'labels'                     => $tax_labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
	);

	register_taxonomy( 'steel_quote_lists', 'steel_quote', $tax_args );
}
 ?>
