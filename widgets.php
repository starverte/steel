<?php
/*
 * Creates custom widgets
 *
 * @package Sparks
 * @sub-package Steel
 *
 */
add_action( 'widgets_init', 'steel_widgets' );

function steel_widgets() {
    register_widget( 'Steel_Link_Widget' );
}

class Steel_Link_Widget extends WP_Widget {

  function Steel_Link_Widget() {
		$widget_ops = array( 'classname' => 'link-widget', 'description' => __('A widget that only displays a title with a link', 'link-widget') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'link-widget' );
		
		$this->WP_Widget( 'link-widget', __('Custom Link Widget', 'link-widget'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$href = $instance['href'];
		$show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;

		echo $before_widget;

		// Display the widget title 
		if ( $title ) {
			echo '<a href=' . $href . '>';
			echo $before_title . $title . $after_title;
			echo '</a>';
		}

		echo $after_widget;
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['href'] = strip_tags( $new_instance['href'] );
		return $instance;
	}

	
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('', 'link-widget'), 'show_info' => true );
		$defaults = array( 'href' => __('http://', 'link-widget'), 'show_info' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'link-widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e('Link:', 'link-widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" value="<?php echo $instance['href']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}
?>
