<?php
/*
 * Creates custom post-type "quotes" for displaying random quotes, testimonials, etc.
 *
 * @package Steel
 * @module Quotes
 *
 */

/*
 * Register random quote widget
 */
add_action( 'widgets_init', 'steel_quotes_widgets' );
function steel_quotes_widgets() {
	register_widget( 'Steel_Quotes_Widget' );
}
class Steel_Quotes_Widget extends WP_Widget {
	
	function Steel_Quotes_Widget() {
		$widget_ops = array( 'classname' => 'random-quotes-widget', 'description' => __('Displays a random quote', 'random-quotes-widget') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'random-quotes-widget' );
		$this->WP_Widget( 'random-quotes-widget', __('Steel: Random Quote(s)', 'random-quotes-widget'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		// Get list
		$cat = !empty( $instance['cat'] ) ? get_category( $instance['cat'] ) : false;

		if ( !$cat )
			return;

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$quotes = new WP_Query(
			array(
				'post_type' => 'post',
				'orderby' => 'rand',
				'posts_per_page' => 1,
				'tax_query' => array(
				  'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => $cat->slug
					),
				  array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => array( 'post-format-quote' )
					)
				)
			)
		);
		
		while ($quotes->have_posts()) : $quotes->the_post(); ?>
    	<blockquote><?php the_content(); ?></blockquote>
    <?php endwhile;

		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['list'] = (int) $new_instance['list'];
		return $instance;
	}

	function form( $instance ) {
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$list  = !empty($instance['list'])  ? $instance['list']  : '';

		// Get menus
		$cats = get_categories();
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('Category:'); ?></label>
			<select id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>">
      	<option value="">All Categories</option>
				<?php 
					foreach ($cats as $cat) {
						$option  = '<option value="' . $cat->term_id . '" '. selected( $list, $cat->term_id ) .'>';
						$option .= $cat->cat_name;
						$option .= '</option>';
						echo $option;
					}
        ?>
			</select>
		</p>
		<?php
	}
}
?>
