<?php
/*
 * Allows creation and management of podcast feeds, including series, audio, and video
 *
 * @package Steel
 * @module Podcast
 *
 */
 
if (is_module_active('podcast')) { add_action( 'init', 'steel_podcast_init', 0 ); }
function steel_podcast_init() {

  // Register Custom Post Type Episode
  $labels = array(
    'name'                => _x( 'Episodes', 'Post Type General Name', 'steel' ),
    'singular_name'       => _x( 'Episode', 'Post Type Singular Name', 'steel' ),
    'menu_name'           => __( 'Podcast', 'steel' ),
    'all_items'           => __( 'All episodes', 'steel' ),
    'view_item'           => __( 'View', 'steel' ),
    'add_new_item'        => __( 'Add New', 'steel' ),
    'add_new'             => __( 'New Episode', 'steel' ),
    'edit_item'           => __( 'Edit', 'steel' ),
    'update_item'         => __( 'Update', 'steel' ),
    'search_items'        => __( 'Search episodes', 'steel' ),
    'not_found'           => __( 'No episodes found', 'steel' ),
    'not_found_in_trash'  => __( 'No episodes found in Trash. Did you check recycling?', 'steel' ),
  );
  $rewrite = array(
    'slug'                => 'episode',
    'with_front'          => true,
    'pages'               => false,
    'feeds'               => false,
  );
  $args = array(
    'label'               => __( 'steel_pod_episode', 'steel' ),
    'description'         => __( 'Create podcast series and episodes', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', ),
    'taxonomies'          => array( 'steel_pod_channel' ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-rss',
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'post',
  );
  register_post_type( 'steel_pod_episode', $args );

  // Register Custom Taxonomy Channel
  $labels = array(
    'name'                       => _x( 'Channels', 'Taxonomy General Name', 'steel' ),
    'singular_name'              => _x( 'Channel', 'Taxonomy Singular Name', 'steel' ),
    'menu_name'                  => __( 'Channels', 'steel' ),
    'all_items'                  => __( 'All Channels', 'steel' ),
    'new_item_name'              => __( 'New Channel', 'steel' ),
    'add_new_item'               => __( 'Add New', 'steel' ),
    'edit_item'                  => __( 'Edit', 'steel' ),
    'update_item'                => __( 'Update', 'steel' ),
    'separate_items_with_commas' => __( 'Separate channels with commas', 'steel' ),
    'search_items'               => __( 'Search channels', 'steel' ),
    'add_or_remove_items'        => __( 'Add or remove channels', 'steel' ),
    'choose_from_most_used'      => __( 'Choose from the most used channels', 'steel' ),
  );
  $rewrite = array(
    'slug'                       => 'channel',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => false,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'rewrite'                    => $rewrite,
  );
  register_taxonomy( 'steel_pod_channel', 'steel_pod_episode', $args );
  
  // Register Custom Taxonomy Series
  $labels = array(
    'name'                       => _x( 'Series', 'Taxonomy General Name', 'steel' ),
    'singular_name'              => _x( 'Series', 'Taxonomy Singular Name', 'steel' ),
    'menu_name'                  => __( 'Series', 'steel' ),
    'all_items'                  => __( 'All Series', 'steel' ),
    'new_item_name'              => __( 'New Series', 'steel' ),
    'add_new_item'               => __( 'Add New', 'steel' ),
    'edit_item'                  => __( 'Edit', 'steel' ),
    'update_item'                => __( 'Update', 'steel' ),
    'separate_items_with_commas' => __( 'Separate series with commas', 'steel' ),
    'search_items'               => __( 'Search series', 'steel' ),
    'add_or_remove_items'        => __( 'Add or remove series', 'steel' ),
    'choose_from_most_used'      => __( 'Choose from the most used series', 'steel' ),
  );
  $rewrite = array(
    'slug'                       => 'series',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => false,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'rewrite'                    => $rewrite,
  );
  register_taxonomy( 'steel_pod_series', 'steel_pod_episode', $args );

}
?>
