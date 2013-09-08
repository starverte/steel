<?php
/*
 * Creates custom post-type "quotes" for displaying random quotes, testimonials, etc.
 *
 * @package Sparks
 * @sub-package Steel
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

	function __construct() {
		$widget_ops = array( 'description' => __('Displays a random quote') );
		parent::__construct( 'cat', __('Steel: Random Quote(s)'), $widget_ops );
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
		$instance['cat'] = (int) $new_instance['cat'];
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$cat = isset( $instance['cat'] ) ? $instance['cat'] : '';

		// Get menus
		$cats = get_categories();

		// If no menus exists, direct the user to go and create some.
		if ( !$cats ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
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
			foreach ( $cats as $cat ) {
				echo '<option value="' . $cat->term_id . '"'
					. selected( $cat, $cat->term_id, false )
					. '>'. $cat->name . '</option>';
			}
		?>
			</select>
		</p>
		<?php
	}
}
?>