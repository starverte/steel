<?php
/*
 * Allows profiles to be built that can belong to one or multiple "teams"
 *
 * @package Steel
 * @module Teams
 *
 */

/*
 * Create custom post type
 */
if (is_module_active('teams')) { add_action( 'init', 'steel_teams_init', 0 ); }
function steel_teams_init() {
	$labels = array(
		'name'                => _x( 'Profiles', 'Post Type General Name', 'steel' ),
		'singular_name'       => _x( 'Profile', 'Post Type Singular Name', 'steel' ),
		'menu_name'           => __( 'Teams', 'steel' ),
		'all_items'           => __( 'All Profiles', 'steel' ),
		'view_item'           => __( 'View Profile', 'steel' ),
		'add_new_item'        => __( 'Add New Profile', 'steel' ),
		'add_new'             => __( 'New Profile', 'steel' ),
		'edit_item'           => __( 'Edit Profile', 'steel' ),
		'update_item'         => __( 'Update Profile', 'steel' ),
		'search_items'        => __( 'Search teams', 'steel' ),
		'not_found'           => __( 'No profiles found', 'steel' ),
		'not_found_in_trash'  => __( 'No profiles found in trash. Did you check recycling?', 'steel' ),
	);
	
	$rewrite = array(
		'slug'                => 'profiles',
		'with_front'          => true,
		'pages'               => false,
		'feeds'               => false,
	);
	
	$args = array(
		'label'               => __( 'steel_profile', 'steel' ),
		'description'         => __( 'Member(s) of "Teams"', 'steel' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	
	register_post_type( 'steel_profile', $args );
	
	$labels2 = array(
		'name'                       => _x( 'Teams', 'Taxonomy General Name', 'steel' ),
		'singular_name'              => _x( 'Team', 'Taxonomy Singular Name', 'steel' ),
		'menu_name'                  => __( 'Teams', 'steel' ),
		'all_items'                  => __( 'All Teams', 'steel' ),
		'parent_item'                => __( '', 'steel' ),
		'parent_item_colon'          => __( '', 'steel' ),
		'new_item_name'              => __( 'New Team Name', 'steel' ),
		'add_new_item'               => __( 'Add New Team', 'steel' ),
		'edit_item'                  => __( 'Edit Team', 'steel' ),
		'update_item'                => __( 'Update Team', 'steel' ),
		'separate_items_with_commas' => __( 'Separate teams with commas', 'steel' ),
		'search_items'               => __( 'Search teams', 'steel' ),
		'add_or_remove_items'        => __( 'Add or remove teams', 'steel' ),
		'choose_from_most_used'      => __( 'Choose from the most used teams', 'steel' ),
	);
	
	$rewrite2 = array(
		'slug'                       => 'teams',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	
	$args2 = array(
		'labels'                     => $labels2,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite2,
	);
	
	register_taxonomy( 'steel_team', 'steel_profile', $args2 );
}

/*
 * Create custom meta boxes
 */
add_action( 'add_meta_boxes', 'steel_teams_meta_boxes' );
function steel_teams_meta_boxes() { add_meta_box('steel_teams_meta', 'Team Member Profile', 'steel_teams_meta', 'steel_profile', 'side', 'high'); }
function steel_teams_meta() {
	global $post;
	$custom = get_post_custom($post->ID); ?>
  
	<p><label>Title</label><input type="text"  size="10" name="profile_title" value="<?php if (isset($custom['profile_title'])) { echo                                                              $custom["profile_title"][0]; } ?>" /></p>
	<p><label>Email</label><input type="email" size="10" name="profile_email" value="<?php if (isset($custom['profile_email'])) { echo                                                              $custom["profile_email"][0]; } ?>" /></p>
	<p><label>Phone</label><input type="tel"   size="10" name="profile_phone" value="<?php if (isset($custom['profile_phone'])) { echo preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1.$2.$3", $custom["profile_phone"][0]); } ?>" /></p><?php
}

/*
 * Save data from meta boxes
 */
add_action('save_post', 'save_steel_profile');
function save_steel_profile() {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id))) { return $post_id; }
	if(defined('DOING_AJAX') && DOING_AJAX && (isset($post_id))) { return $post_id; } //Prevents the metaboxes from being overwritten while quick editing.
	if(preg_match('/\edit\.php/', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; } //Detects if the save action is coming from a quick edit/batch edit.
	if (isset($_POST['profile_email'])) { update_post_meta($post->ID, "profile_email", $_POST["profile_email"]); }
	if (isset($_POST['profile_title'])) { update_post_meta($post->ID, "profile_title", $_POST["profile_title"]); }
	if (isset($_POST['profile_phone'])) {
		$new = preg_replace('/[^a-z0-9]+/i', '', $_POST["profile_phone"]);
		update_post_meta($post->ID, "profile_phone", $new);
	}
}

/*
 * Create cystom functions to display data
 */
function profile_title() {
	global $post;
	$custom = get_post_custom($post->ID);
	if (isset($custom['profile_title'])) { echo $custom["profile_title"][0]; }
}
function profile_email() {
	global $post;
	$custom = get_post_custom($post->ID);
	if (isset($custom['profile_email'])) { echo $custom["profile_email"][0]; }
}
function profile_phone($pattern = "$1.$2.$3") {
	global $post;
	$custom = get_post_custom($post->ID);
	if (isset($custom['profile_phone'])) { echo preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", $pattern, $custom["profile_phone"][0]); }
}
?>
