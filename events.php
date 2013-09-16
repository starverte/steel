<?php
/*
 * Allows creation and management of events
 *
 * @package Sparks
 * @sub-package Steel
 *
 */


/*
 * Create custom post type
 */
if (is_module_active('events')) {add_action( 'init', 'steel_events_init', 0 );}
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
	if(preg_match('/\edit\.php/', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; } //Detects if the save action is coming from a quick edit/batch edit.
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
		$this->WP_Widget( 'upcoming-events-widget', __('Steel: Upcoming Events Widget', 'upcoming-events'), $widget_ops, $control_ops );
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

/*
 * Use custom template for calendar archive page
 */
function steel_calendar_template()
{
        // not a search page, don't do anything
        if( ! is_tax('steel_calendar') )
            return;

        include( plugins_url( '/templates/calendar.php' , __FILE__ ) );
				
}
add_action( 'template_redirect', 'steel_calendar_template', 1 );

/**
 * Display calendar with days that have events with links.
 *
 * The calendar is cached, which will be retrieved, if it exists. If there are
 * no posts for the month, then it will not be displayed.
 *
 * Based on the WordPress core function get_calendar
 *
 * @uses calendar_week_mod()
 *
 * @param bool $initial Optional, default is true. Use initial calendar names.
 * @param bool $echo Optional, default is true. Set to false for return.
 * @return string|null String when retrieving, null when displaying.
 */
function get_events_calendar($initial = true, $echo = true) {
	global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

	$cache = array();
	$key = md5( $m . $monthnum . $year );
	if ( $cache = wp_cache_get( 'get_calendar', 'calendar' ) ) {
		if ( is_array($cache) && isset( $cache[ $key ] ) ) {
			if ( $echo ) {
				echo apply_filters( 'get_calendar',  $cache[$key] );
				return;
			} else {
				return apply_filters( 'get_calendar',  $cache[$key] );
			}
		}
	}

	if ( !is_array($cache) )
		$cache = array();

	// Quick check. If we have no posts at all, abort!
	if ( !$posts ) {
		$gotsome = $wpdb->get_var("SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'steel_event' AND post_status = 'publish' LIMIT 1");
		if ( !$gotsome ) {
			$cache[ $key ] = '';
			wp_cache_set( 'get_calendar', $cache, 'calendar' );
			return;
		}
	}

	if ( isset($_GET['w']) )
		$w = ''.intval($_GET['w']);

	// week_begins = 0 stands for Sunday
	$week_begins = intval(get_option('start_of_week'));

	// Let's figure out when we are
	if ( !empty($monthnum) && !empty($year) ) {
		$thismonth = ''.zeroise(intval($monthnum), 2);
		$thisyear = ''.intval($year);
	} elseif ( !empty($w) ) {
		// We need to get the month from MySQL
		$thisyear = ''.intval(substr($m, 0, 4));
		$d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
		$thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
	} elseif ( !empty($m) ) {
		$thisyear = ''.intval(substr($m, 0, 4));
		if ( strlen($m) < 6 )
				$thismonth = '01';
		else
				$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
	} else {
		$thisyear = gmdate('Y', current_time('timestamp'));
		$thismonth = gmdate('m', current_time('timestamp'));
	}

	$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
	$last_day = date('t', $unixmonth);

	// Get the next and previous month and year with at least one post
	$previous = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
		FROM $wpdb->posts
		WHERE post_date < '$thisyear-$thismonth-01'
		AND post_type = 'steel_event' AND post_status = 'publish'
			ORDER BY post_date DESC
			LIMIT 1");
	$next = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
		FROM $wpdb->posts
		WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
		AND post_type = 'steel_event' AND post_status = 'publish'
			ORDER BY post_date ASC
			LIMIT 1");

	/* translators: Calendar caption: 1: month name, 2: 4-digit year */
	$calendar_caption = _x('%1$s %2$s', 'calendar caption');
	$calendar_output = '<table id="wp-calendar">
	<caption>' . sprintf($calendar_caption, $wp_locale->get_month($thismonth), date('Y', $unixmonth)) . '</caption>
	<thead>
	<tr>';

	$myweek = array();

	for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
		$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
	}

	foreach ( $myweek as $wd ) {
		$day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
		$wd = esc_attr($wd);
		$calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	$calendar_output .= '
	</tr>
	</thead>

	<tfoot>
	<tr>';

	if ( $previous ) {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="prev"><a href="' . get_month_link($previous->year, $previous->month) . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($previous->month), date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year)))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';
	} else {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
	}

	$calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';

	if ( $next ) {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="next"><a href="' . get_month_link($next->year, $next->month) . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($next->month), date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) ) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';
	} else {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
	}

	$calendar_output .= '
	</tr>
	</tfoot>

	<tbody>
	<tr>';

	// Get days with posts
	$dayswithposts = $wpdb->get_results("SELECT DISTINCT DAYOFMONTH(post_date)
		FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
		AND post_type = 'steel_event' AND post_status = 'publish'
		AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'", ARRAY_N);
	if ( $dayswithposts ) {
		foreach ( (array) $dayswithposts as $daywith ) {
			$daywithpost[] = $daywith[0];
		}
	} else {
		$daywithpost = array();
	}

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'camino') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false)
		$ak_title_separator = "\n";
	else
		$ak_title_separator = ', ';

	$ak_titles_for_day = array();
	$ak_post_titles = $wpdb->get_results("SELECT ID, post_title, DAYOFMONTH(post_date) as dom "
		."FROM $wpdb->posts "
		."WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' "
		."AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59' "
		."AND post_type = 'steel_event' AND post_status = 'publish'"
	);
	if ( $ak_post_titles ) {
		foreach ( (array) $ak_post_titles as $ak_post_title ) {

				$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) );

				if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
					$ak_titles_for_day['day_'.$ak_post_title->dom] = '';
				if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
					$ak_titles_for_day["$ak_post_title->dom"] = $post_title;
				else
					$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
		}
	}

	// See how much we should pad in the beginning
	$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
	if ( 0 != $pad )
		$calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

	$daysinmonth = intval(date('t', $unixmonth));
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
		if ( isset($newrow) && $newrow )
			$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
		$newrow = false;

		if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
			$calendar_output .= '<td id="today">';
		else
			$calendar_output .= '<td>';

		if ( in_array($day, $daywithpost) ) // any posts today?
				$calendar_output .= '<a href="' . get_day_link( $thisyear, $thismonth, $day ) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
		else
			$calendar_output .= $day;
		$calendar_output .= '</td>';

		if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
			$newrow = true;
	}

	$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
	if ( $pad != 0 && $pad != 7 )
		$calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

	$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

	$cache[ $key ] = $calendar_output;
	wp_cache_set( 'get_calendar', $cache, 'calendar' );

	if ( $echo )
		echo apply_filters( 'get_calendar',  $calendar_output );
	else
		return apply_filters( 'get_calendar',  $calendar_output );

}
?>
