<?php
/**
 * Allows creation and management of profiles that can belong to one or many "teams" for use with staff, elders, board members, and more
 *
 * @package Steel\Teams
 */

/**
 * Return arguments for registering steel_profile
 *
 * @internal
 */
function steel_teams_profile_post_type_args() {
  $labels = array(
    'name'                  => _x( 'Profiles', 'post type general name', 'steel' ),
    'singular_name'         => _x( 'Profile', 'post type singular name', 'steel' ),
    'menu_name'             => __( 'Teams', 'steel' ),
    'name_admin_bar'        => __( 'Profiles', 'steel' ),
    'add_new'               => __( 'Add New', 'steel' ),
    'add_new_item'          => __( 'Add New Profile', 'steel' ),
    'edit_item'             => __( 'Edit Profile', 'steel' ),
    'new_item'              => __( 'New Profile', 'steel' ),
    'view_item'             => __( 'View Profile', 'steel' ),
    'update_item'           => __( 'Update', 'steel' ),
    'search_items'          => __( 'Search Profiles', 'steel' ),
    'not_found'             => __( 'No team profiles found.', 'steel' ),
    'not_found_in_trash'    => __( 'No team profiles found in Trash.', 'steel' ),
    'all_items'             => __( 'All Profiles', 'steel' ),
    'archives'              => __( 'Profile Archives', 'steel' ),
    'insert_into_item'      => __( 'Insert into team profile', 'steel' ),
    'uploaded_to_this_item' => __( 'Uploaded to this team profile', 'steel' ),
    'featured_image'        => __( 'Featured Image', 'steel' ),
    'set_featured_image'    => __( 'Set featured image', 'steel' ),
    'remove_featured_image' => __( 'Remove featured image', 'steel' ),
    'use_featured_image'    => __( 'Use as featured image', 'steel' ),
    'filter_items_list'     => __( 'Filter team profiles list', 'steel' ),
    'items_list_navigation' => __( 'Profiles list navigation', 'steel' ),
    'items_list'            => __( 'Profiles list', 'steel' ),
  );
  $rewrite = array(
    'slug'                => 'profile',
    'with_front'          => true,
    'pages'               => false,
    'feeds'               => false,
  );
  $args = array(
    'label'               => __( 'steel_profile', 'steel' ),
    'description'         => __( 'Member(s) of "Teams"', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
    'hierarchical'        => true,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-groups',
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'page',
  );
  return $args;
}

/**
 * Return arguments for registering steel_team
 *
 * @internal
 */
function steel_teams_team_taxonomy_args() {
  $labels = array(
    'name'                       => _x( 'Teams', 'taxonomy general name', 'steel' ),
    'singular_name'              => _x( 'Team', 'taxonomy singular name', 'steel' ),
    'menu_name'                  => __( 'Teams', 'steel' ),
    'search_items'               => __( 'Search Teams', 'steel' ),
    'popular_items'              => __( 'Popular Teams', 'steel' ),
    'all_items'                  => __( 'All Teams', 'steel' ),
    'edit_item'                  => __( 'Edit Team', 'steel' ),
    'view_item'                  => __( 'View Team', 'steel' ),
    'update_item'                => __( 'Update Team', 'steel' ),
    'add_new_item'               => __( 'Add New Team', 'steel' ),
    'new_item_name'              => __( 'New Team Name', 'steel' ),
    'separate_items_with_commas' => __( 'Separate teams with commas' ),
    'add_or_remove_items'        => __( 'Add or remove teams' ),
    'choose_from_most_used'      => __( 'Choose from the most used teams' ),
    'not_found'                  => __( 'No teams found.', 'steel' ),
    'no_terms'                   => __( 'No teams', 'steel' ),
    'items_list_navigation'      => __( 'Teams list navigation', 'steel' ),
    'items_list'                 => __( 'Teams list', 'steel' ),
  );
  $rewrite = array(
    'slug'                       => 'teams',
    'with_front'                 => true,
    'hierarchical'               => true,
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

/**
 * Register custom post type and custom taxonomy
 *
 * @internal
 */
function steel_teams_init() {
  register_post_type( 'steel_profile', steel_teams_profile_post_type_args() );

  register_taxonomy(
    'steel_team',
    'steel_profile',
    steel_teams_team_taxonomy_args()
  );
}
add_action( 'init', 'steel_teams_init' );

/**
 * Display profile meta on Edit Profile screen
 */
function steel_teams_meta() {
?>

  <p><label>Title</label><br /><input type="text"  size="25" name="profile_title" value="<?php echo steel_profile_meta( 'title' ); ?>" /></p>
  <p><label>Email</label><br /><input type="email" size="25" name="profile_email" value="<?php echo steel_profile_meta( 'email' ); ?>" /></p>
  <p><label>Phone</label><br /><input type="tel"   size="25" name="profile_phone" value="<?php echo steel_profile_phone();        ?>" /></p><?php

  do_action( 'steel_teams_add_meta' );
}

/**
 * Add meta boxes to Edit Profile screen
 *
 * @internal
 */
function steel_teams_add_meta_boxes() {
  add_meta_box(
    'steel_teams_meta',
    'Team Member Profile',
    'steel_teams_meta',
    'steel_profile',
    'side',
    'high'
  );
}
add_action( 'add_meta_boxes', 'steel_teams_add_meta_boxes' );

/**
 * Save data from meta boxes
 *
 * @internal
 */
function steel_teams_profile_save() {
  global $post;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && (isset( $post_id )) ) {
    return $post_id;
  }
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX && (isset( $post_id )) ) {
    return $post_id;
  } //Prevents the metaboxes from being overwritten while quick editing.
  if ( preg_match( '/\edit\.php/', $_SERVER['REQUEST_URI'] ) && (isset( $post_id )) ) {
    return $post_id;
  } //Detects if the save action is coming from a quick edit/batch edit.
  if ( isset( $_POST['profile_email'] ) ) {
    update_post_meta( $post->ID, 'profile_email', $_POST['profile_email'] );
  }
  if ( isset( $_POST['profile_title'] ) ) {
    update_post_meta( $post->ID, 'profile_title', $_POST['profile_title'] );
  }
  if ( isset( $_POST['profile_phone'] ) ) {
    $new = preg_replace( '/[^a-z0-9]+/i', '', $_POST['profile_phone'] );
    update_post_meta( $post->ID, 'profile_phone', $new );
  }
  do_action( 'steel_teams_save_meta' );
}
add_action( 'save_post', 'steel_teams_profile_save' );

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
function steel_profile_meta( $key, $post_id = 0 ) {
  return steel_meta( 'profile', $key, $post_id );
}

/**
 * Retrieve profile phone number based on post ID.
 *
 * @see WordPress 4.3.1 get_post_custom()
 *
 * @param string $pattern The pattern to display the phone number.
 *                        Default is '$1.$2.$3' which becomes '###.###.####'.
 * @param int    $post_id Optional. Post ID. Default is ID of the global $post.
 * @return string Formatted phone number for given profile ID.
 */
function steel_profile_phone( $pattern = '$1.$2.$3', $post_id = 0 ) {
  $phone = steel_profile_meta( 'phone', $post_id );
  return preg_replace( '/([0-9]{3})([0-9]{3})([0-9]{4})/', $pattern, $phone );
}
