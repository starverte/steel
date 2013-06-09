<?php
/*
 * Allows creation and management of events
 *
 * @package Sparks
 * @sub-package Steel
 *
 * @since 0.6.0
 */

/*
 * Create custom post type
 */
add_action( 'init', 'steel_events_init', 0 );
function steel_events_init() {
	$labels = array(
		'name'                => _x( 'Events', 'Post Type General Name', 'steel' ),
		'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'steel' ),
		'menu_name'           => __( 'Events', 'steel' ),
		'all_items'           => __( 'All Events', 'steel' ),
		'view_item'           => __( 'View Event', 'steel' ),
		'add_new_item'        => __( 'Add New Event', 'steel' ),
		'add_new'             => __( 'New Event', 'steel' ),
		'edit_item'           => __( 'Edit Event', 'steel' ),
		'update_item'         => __( 'Update Event', 'steel' ),
		'search_items'        => __( 'Search teams', 'steel' ),
		'not_found'           => __( 'No events found', 'steel' ),
		'not_found_in_trash'  => __( 'No events found in trash. Did you check recycling?', 'steel' ),
	);
	
	$rewrite = array(
		'slug'                => '',
		'with_front'          => true,
		'pages'               => false,
		'feeds'               => false,
	);
	
	$args = array(
		'label'               => __( 'steel_event', 'steel' ),
		'description'         => __( 'Events', 'steel' ),
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
	
	register_post_type( 'steel_event', $args );
	
	$labels2 = array(
		'name'                       => _x( 'Calendars', 'Taxonomy General Name', 'steel' ),
		'singular_name'              => _x( 'Calendar', 'Taxonomy Singular Name', 'steel' ),
		'menu_name'                  => __( 'Calendars', 'steel' ),
		'all_items'                  => __( 'All Calendars', 'steel' ),
		'parent_item'                => __( '', 'steel' ),
		'parent_item_colon'          => __( '', 'steel' ),
		'new_item_name'              => __( 'New Calendar Name', 'steel' ),
		'add_new_item'               => __( 'Add New Calendar', 'steel' ),
		'edit_item'                  => __( 'Edit Calendar', 'steel' ),
		'update_item'                => __( 'Update Calendar', 'steel' ),
		'separate_items_with_commas' => __( 'Separate calendars with commas', 'steel' ),
		'search_items'               => __( 'Search calendars', 'steel' ),
		'add_or_remove_items'        => __( 'Add or remove calendars', 'steel' ),
		'choose_from_most_used'      => __( 'Choose from the most used calendars', 'steel' ),
	);
	
	$rewrite2 = array(
		'slug'                       => 'cal',
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
	
	register_taxonomy( 'steel_calendar', 'steel_event', $args2 );
}

/*
 * Create custom meta boxes
 */
add_action( 'add_meta_boxes', 'steel_events_meta_boxes' );
function steel_events_meta_boxes() { add_meta_box('steel_events_meta', 'Event Details', 'steel_events_meta', 'steel_event', 'side', 'high'); }
function steel_events_meta() {
	global $post;
	$custom = get_post_custom($post->ID); ?>
  
	<p><label>Location</label><input type="text" size="10" name="event_loc" value="<?php if (isset($custom['event_loc'])) { echo $custom["event_loc"] [0]; } ?>" /></p>
	<p>
		<label>Starts</label> 
		<input class="datepicker" type="text" size="15" name="event_start_date" value="<?php if (isset($custom['event_start'])) { echo date( 'F j, Y', $custom["event_start"] [0] ); } ?>" />
		<input class="times" type="text" size="5" name="event_start_time" value="<?php if (isset($custom['event_start'])) { echo date( 'g:i a', $custom["event_start"] [0] ); } ?>" />
	</p>
	<p>
		<label>Ends</label> 
		<input class="datepicker" type="text" size="15" name="event_end_date" value="<?php if (isset($custom['event_end'])) { echo date( 'F j, Y', $custom["event_end"] [0] ); } ?>" />
		<input class="times" type="text" size="5" name="event_end_time" value="<?php if (isset($custom['event_end'])) { echo date( 'g:i a', $custom["event_end"] [0] ); } ?>" />
	</p>
	<p><input type="checkbox" name="event_multi_day" value="event_multi_day" <?php if (isset($custom['event_multi_day']) && $custom['event_multi_day'][0] == true) { echo 'checked=\"checked\"'; } ?>><label style="padding-left:0.5em;">Multi-day Event</label></p><?php
}

/*
 * Save data from meta boxes
 */
add_action('save_post', 'save_steel_event');
function save_steel_event() {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id))) { return $post_id; }
	if(defined('DOING_AJAX') && DOING_AJAX && (isset($post_id))) { return $post_id; } //Prevents the metaboxes from being overwritten while quick editing.
	if(ereg('/\edit\.php', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; } //Detects if the save action is coming from a quick edit/batch edit.
	if (isset($_POST['event_loc'])) { update_post_meta($post->ID, "event_loc", $_POST["event_loc"]); }
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
	if (isset($_POST['event_multi_day'])) { update_post_meta($post->ID, "event_multi_day", true); }
	elseif (isset($post->ID)) { update_post_meta($post->ID, "event_multi_day", false); }
}

/*
 * Load datepicker
 */
add_action( 'admin_enqueue_scripts', 'events_admin_init' );
function events_admin_init() {
	wp_enqueue_script( 'event-script', plugins_url('/js/event-script.js', __FILE__) );
	wp_enqueue_style( 'event-style', plugins_url('/css/event-style.css', __FILE__) );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_enqueue_style( 'jquery-base', 'http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css' );
}

/*
 * Create Upcoming Events Widget
 */
add_action( 'widgets_init', 'steel_events_widgets' );
function steel_events_widgets() {
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
		$title = apply_filters('widget_title', $instance['title'] );
		$numposts = $instance['numposts'];
		$show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		$today = time();
		$args = array(
			'post_type' => 'steel_event',
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
    
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> ><?php
    
		$custom = get_post_custom();
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
      </div><!-- .event-date -->
      <header class="entry-header">
      	<h1 class="entry-title"><?php the_title(); ?></h1>
      	<p class="event-time"><?php echo $start_time; ?> to <?php echo $end_time; ?></p>
      </header><!-- .entry-header -->
      <a class="directions" href="https://maps.google.com/maps?q=<?php echo $event_loc; ?>" target="_blank">Map</a>
      <div class="entry-content"><?php the_content(); ?></div>
		</div><!-- #post-<?php the_ID(); ?> --> <?php
		
		endwhile;
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['numposts'] = strip_tags( $new_instance['numposts'] );
		return $instance;
	}
	function form( $instance ) {
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
		</p><?php
	}
}

/*
 * Create custom function to display events
 */
function the_events($events = -1) {
	$today = time();
	$args = array(
		'post_type' => 'steel_event',
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
