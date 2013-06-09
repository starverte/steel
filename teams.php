<?php
/*
Plugin Name: Sparks Teams
Plugin URI: //Not yet developed
Description: Part of the Sparks Framework. A plugin that allows creation and management of "teams" for use with staff, elders, board members, etc.
Version: 0.9
Author: Star Verte LLC
Author URI: http://www.starverte.com
License: GPLv2 or later

    Copyright 2013  Star Verte LLC  (email : info@starverte.com)
    
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    You should have received a copy of the GNU General Public License
    along with Sparks Teams.  If not, see <http://www.gnu.org/licenses/>.
*/

// Register Custom Post Type
function sparks_teams_init() {
	$labels = array(
		'name'                => _x( 'Profiles', 'Post Type General Name', 'sparks' ),
		'singular_name'       => _x( 'Profile', 'Post Type Singular Name', 'sparks' ),
		'menu_name'           => __( 'Teams', 'sparks' ),
		'all_items'           => __( 'All Profiles', 'sparks' ),
		'view_item'           => __( 'View Profile', 'sparks' ),
		'add_new_item'        => __( 'Add New Profile', 'sparks' ),
		'add_new'             => __( 'New Profile', 'sparks' ),
		'edit_item'           => __( 'Edit Profile', 'sparks' ),
		'update_item'         => __( 'Update Profile', 'sparks' ),
		'search_items'        => __( 'Search teams', 'sparks' ),
		'not_found'           => __( 'No profiles found', 'sparks' ),
		'not_found_in_trash'  => __( 'No profiles found in trash. Did you check recycling?', 'sparks' ),
	);

	$rewrite = array(
		'slug'                => '',
		'with_front'          => true,
		'pages'               => false,
		'feeds'               => false,
	);

	$args = array(
		'label'               => __( 'sp_team_profile', 'sparks' ),
		'description'         => __( 'Member(s) of "Teams"', 'sparks' ),
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

	register_post_type( 'sp_team_profile', $args );
	
	$labels2 = array(
		'name'                       => _x( 'Teams', 'Taxonomy General Name', 'sparks' ),
		'singular_name'              => _x( 'Team', 'Taxonomy Singular Name', 'sparks' ),
		'menu_name'                  => __( 'Teams', 'sparks' ),
		'all_items'                  => __( 'All Teams', 'sparks' ),
		'parent_item'                => __( '', 'sparks' ),
		'parent_item_colon'          => __( '', 'sparks' ),
		'new_item_name'              => __( 'New Team Name', 'sparks' ),
		'add_new_item'               => __( 'Add New Team', 'sparks' ),
		'edit_item'                  => __( 'Edit Team', 'sparks' ),
		'update_item'                => __( 'Update Team', 'sparks' ),
		'separate_items_with_commas' => __( 'Separate teams with commas', 'sparks' ),
		'search_items'               => __( 'Search teams', 'sparks' ),
		'add_or_remove_items'        => __( 'Add or remove teams', 'sparks' ),
		'choose_from_most_used'      => __( 'Choose from the most used teams', 'sparks' ),
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

	register_taxonomy( 'sp_team', 'sp_team_profile', $args2 );
}

// Hook into the 'init' action
add_action( 'init', 'sparks_teams_init', 0 );

// BEGIN - Create custom fields
add_action( 'add_meta_boxes', 'sp_teams_add_custom_boxes' );

function sp_teams_add_custom_boxes() {
	add_meta_box('sp_teams_meta', 'Team Member Profile', 'sp_teams_meta', 'sp_team_profile', 'side', 'high');
}

/* Team Member Profile */
function sp_teams_meta() {
	global $post;
	$custom = get_post_custom($post->ID);
	
?>
    <p><label>Name</label> 
	<input type="text" size="10" name="team_member_name" value="<?php if (isset($custom['team_member_name'])) { echo $custom["team_member_name"] [0]; } ?>" /></p>
    <p><label>Phone</label> 
	<input type="tel" size="10" name="team_member_phone" value="<?php if (isset($custom['team_member_phone'])) { echo $custom["team_member_phone"] [0]; } ?>" /></p>
    <p><label>Email</label> 
	<input type="email" size="10" name="team_member_email" value="<?php if (isset($custom['team_member_email'])) { echo $custom["team_member_email"] [0]; } ?>" /></p>
    
	<?php
}

/* Save Details */
add_action('save_post', 'save_teams_details');


function save_teams_details(){
  global $post;
  
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id)) ) {
	return $post_id;
  }

  if( defined('DOING_AJAX') && DOING_AJAX && (isset($post_id)) ) { //Prevents the metaboxes from being overwritten while quick editing.
	return $post_id;
  }

  if( ereg('/\edit\.php', $_SERVER['REQUEST_URI']) && (isset($post_id)) ) { //Detects if the save action is coming from a quick edit/batch edit.
	return $post_id;
  }
  // save all meta data
  if (isset($_POST['team_member_name'])) {
  	update_post_meta($post->ID, "team_member_name", $_POST["team_member_name"]);
  }
  if (isset($_POST['team_member_phone'])) {
  	update_post_meta($post->ID, "team_member_phone", $_POST["team_member_phone"]);
  }
  if (isset($_POST['team_member_email'])) {
  	update_post_meta($post->ID, "team_member_email", $_POST["team_member_email"]);
  }
}
// END - Custom Fields
?>