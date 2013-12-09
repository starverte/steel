<?php
/*
 * If theme doesn't have templates for custom post types, use Steel's packaged templates.
 *
 * @package Steel
 * @module Teams
 *
 */

add_filter( 'single_template', 'steel_single_template' );
function steel_single_template($single_template) {
     global $post;
     if ($post->post_type == 'steel_profile') {
			 if (file_exists(get_stylesheet_directory() . '/single-steel_profile.php')) {}
			 else { $single_template = dirname( __FILE__ )  . '/single-steel_profile.php'; }
     }
     return $single_template;
}

add_action( 'template_redirect', 'steel_template_redirect' );
function steel_template_redirect()
{
    if( is_tax('steel_team') ) {
			include( dirname( __FILE__ )  . '/taxonomy-steel_team.php' );
			exit();
    }
		else { return; }
}


?>