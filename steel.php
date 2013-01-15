<?php
/*
Plugin Name: Steel
Plugin URI: //Not yet developed
GitHub URI: https://github.com/starverte/steel.git
Description: Core plugin of the Sparks Framework. Includes custom widgets, royalslider, and options menu.
Author: starverte
Author URI: http://starverte.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/

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
*/

include_once dirname( __FILE__ ) . '/royalslider.php';
include_once dirname( __FILE__ ) . '/widgets.php';

add_action( 'admin_enqueue_scripts', 'steel_scripts' );
   
function steel_scripts() {
       wp_register_style( 'SparksStyles', plugins_url('admin.css', __FILE__) );
       wp_enqueue_style( 'SparksStyles' );
}

add_action('admin_init', 'register_sparks_options' );
add_action('admin_menu', 'register_sparks_menu');

// Init plugin options to white list our options
function register_sparks_options(){
	register_setting( 'sparks_options', 'steel_options', 'steel_options_validate' );
}

// Add menu page
function register_sparks_menu() {
	add_options_page('Ozh\'s Sample Options', 'Sample Options', 'manage_options', 'steel_optionsoptions', 'steel_optionsoptions_do_page');
	add_menu_page('Sparks', 'Sparks', 'manage_options', 'steel/admin.php', '',   plugins_url('steel/img/sparks.png'), 50);
}

function steel_options_validate($input) {
	
	// Say our second option must be safe text with no HTML tags
	$input['merch_id'] =  wp_filter_nohtml_kses($input['merch_id']);
	
	return $input;
}
?>