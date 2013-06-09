<?php
/*
Plugin Name: Sparks Events
Plugin URI: //Not yet developed
Description: Part of the Sparks Framework. A plugin that adds the ability of an events calendar.
Version: alpha
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
	along with Events.  If not, see <http://www.gnu.org/licenses/>.
*/

function sparks_events_init() {
  $labels = array(
    'name' => 'Events',
    'singular_name' => 'Event',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Event',
    'edit_item' => 'Edit Event',
    'new_item' => 'New Event',
    'all_items' => 'All Events',
    'view_item' => 'View Event',
    'search_items' => 'Search Events',
    'not_found' =>  'No events found',
    'not_found_in_trash' => 'No events found in Trash. Did you check recycling?', 
    'parent_event_colon' => '',
    'menu_name' => 'Events'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'events' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
  ); 

  register_post_type( 'sp_event', $args );
}
add_action( 'init', 'sparks_events_init' );

//add filter to ensure the text Event, or event, is displayed when user updates an event 

function codex_sp_event_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['sp_event'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Event updated. <a href="%s">View event</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Event updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Event published. <a href="%s">View event</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Event saved.'),
    8 => sprintf( __('Event submitted. <a target="_blank" href="%s">Preview event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview event</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Event draft updated. <a target="_blank" href="%s">Preview event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'codex_sp_event_updated_messages' );

// BEGIN - Create custom fields
add_action( 'add_meta_boxes', 'sp_event_add_custom_boxes' );

function sp_event_add_custom_boxes() {
	add_meta_box('sp_event_meta', 'Details', 'sp_event_meta', 'sp_event', 'side', 'high');
}

/* Staff Details */
function sp_event_meta() {
	global $post;
	$custom = get_post_custom($post->ID);
	
?>
    <p><label>Location</label> 
	<input type="text" size="10" name="event_loc" value="<?php if (isset($custom['event_loc'])) { echo $custom["event_loc"] [0]; } ?>" /></p>
    <p><label>Starts</label> 
	<input class="datepicker" type="text" size="15" name="event_start_date" value="<?php if (isset($custom['event_start'])) { echo date( 'F j, Y', $custom["event_start"] [0] ); } ?>" />
    <input class="times" type="text" size="5" name="event_start_time" value="<?php if (isset($custom['event_start'])) { echo date( 'g:i a', $custom["event_start"] [0] ); } ?>" /></p>
    <p><label>Ends</label> 
	<input class="datepicker" type="text" size="15" name="event_end_date" value="<?php if (isset($custom['event_end'])) { echo date( 'F j, Y', $custom["event_end"] [0] ); } ?>" />
    <input class="times" type="text" size="5" name="event_end_time" value="<?php if (isset($custom['event_end'])) { echo date( 'g:i a', $custom["event_end"] [0] ); } ?>" /></p>
    <p><input type="checkbox" name="event_multi_day" value="event_multi_day" <?php if (isset($custom['event_multi_day']) && $custom['event_multi_day'][0] == true) { echo 'checked=\"checked\"'; } ?>><label style="padding-left:0.5em;">Multi-day Event</label></p>
	<?php
}

/* Save Details */
add_action('save_post', 'save_event_details');


function save_event_details(){
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
  if (isset($_POST['event_loc'])) {
	  update_post_meta($post->ID, "event_loc", $_POST["event_loc"]);
  }
  if (isset($_POST['event_start_date']) && isset($_POST['event_start_time'])) {
	  update_post_meta($post->ID, "event_start_date", $_POST["event_start_date"]);
	  update_post_meta($post->ID, "event_start_time", $_POST["event_start_time"]);
	  $event_start = $_POST['event_start_date'] . ' ' . $_POST['event_start_time'];
	  update_post_meta($post->ID, "event_start", strtotime($event_start));
  }
  if (isset($_POST['event_end_date']) && isset($_POST['event_end_time'])) {
	  update_post_meta($post->ID, "event_end_date", $_POST["event_end_date"]);
	  update_post_meta($post->ID, "event_end_time", $_POST["event_end_time"]);
	  $event_end = $_POST['event_end_date'] . ' ' . $_POST['event_end_time'];
	  update_post_meta($post->ID, "event_end", strtotime($event_end));
  }
  if (isset($_POST['event_multi_day'])) {
	  update_post_meta($post->ID, "event_multi_day", true);
  }
  elseif (isset($post->ID)) {
	  update_post_meta($post->ID, "event_multi_day", false);
  }
}
// END - Custom Fields

//Load datepicker
function events_admin_init() {
        wp_register_script( 'event-script', plugins_url('/js/event-script.js', __FILE__) );
        wp_enqueue_script( 'event-script' );
		wp_register_style( 'event-style', plugins_url('/css/event-style.css', __FILE__) );
		wp_enqueue_style( 'event-style' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
        if (wp_script_is('jquery-base', 'registered')) {
        	wp_enqueue_style( 'jquery-base' );
		}
		else {
			wp_register_style( 'jquery-base', 'http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css' );
        	wp_enqueue_style( 'jquery-base' );
		}
}
add_action( 'admin_enqueue_scripts', 'events_admin_init' );

add_action( 'widgets_init', 'sparks_events_widgets' );

function sparks_events_widgets() {
	register_widget( 'Upcoming_Events_Widget' );
}

class Upcoming_Events_Widget extends WP_Widget {

	function Upcoming_Events_Widget() {
		$widget_ops = array( 'classname' => 'upcoming-events', 'description' => __('A widget that displays upcoming events', 'upcoming-events') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'upcoming-events-widget' );
		
		$this->WP_Widget( 'upcoming-events-widget', __('Upcoming Events Widget', 'upcoming-events'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$numposts = $instance['numposts'];
		$show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;

		echo $before_widget;

		// Display the widget title 
		if ( $title )
			echo $before_title . $title . $after_title;
		
		$today = time();

		$args = array(
			'post_type' => 'sp_event',
			'meta_key' => 'event_start',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'posts_per_page' => $numposts ,
			'meta_query' => array(
				array(
					'key' => 'event_end',
					'value' => $today,
					'compare' => '>=',
				)
			)
		);
		$the_query = new WP_Query( $args );
 
  		while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
		<?php	$custom = get_post_custom();
                $event_loc = $custom['event_loc'][0];
                $event_start = $custom['event_start'][0];
                $event_end = $custom['event_end'][0];
                $start_mth = date( 'M', $event_start );
                $start_day = date( 'j', $event_start );
				if (isset($custom['event_multi_day']) && $custom['event_multi_day'][0] == true) {
					$start_time = date( 'M j, g:i a', $event_start );
					$end_time = date( 'M j, g:i a', $event_end );			
				}
				else {
                	$start_time = date( 'g:i a', $event_start );
                	$end_time = date( 'g:i a', $event_end );
				}?>
                
                <div class="event-date">
                    <div class="month"><?php echo $start_mth; ?></div>
                    <div class="day"><?php echo $start_day; ?></div>
                </div>
                
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <p class="event-time"><?php echo $start_time; ?> to <?php echo $end_time; ?></p>
                </header><!-- .entry-header -->

		<a class="directions" href="https://maps.google.com/maps?q=<?php echo $event_loc; ?>" target="_blank">Map</a>
                
                <div class="entry-content">
			<?php the_content(); ?>
                </div><!-- .entry-content -->

            </div><!-- #post-<?php the_ID(); ?> --> <?php
		
		endwhile;
		
		echo $after_widget;
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['numposts'] = strip_tags( $new_instance['numposts'] );
		return $instance;
	}

	
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Upcoming Events', 'upcoming-events'), 'show_info' => true );
		$defaults = array( 'numposts' => __('3', 'upcoming-events'), 'show_info' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'upcoming-events'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>"><?php _e('Number of Events:', 'upcoming-events'); ?></label>
			<input id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" value="<?php echo $instance['numposts']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

//Query function
function the_events($events = -1) {
	$today = time();
	$args = array(
			'post_type' => 'sp_event',
			'meta_key' => 'event_start',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'posts_per_page' => $events,
			'meta_query' => array(
				array(
					'key' => 'event_end',
					'value' => $today,
					'compare' => '>=',
				)
			)
		);
		query_posts($args);
}
?>
