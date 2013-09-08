<?php
/*
 * Creates custom post-type "quotes" for displaying random quotes, testimonials, etc.
 *
 * @package Sparks
 * @sub-package Steel
 *
 * @since 0.7.2
 */
 
/*
 * Create custom post type steel_quote and custom taxonomy steel_quote_lists
 */
add_action( 'init', 'steel_quotes_init', 0 );
function steel_quotes_init() {
 $post_labels = array(
		'name'                => _x( 'Quotes', 'Post Type General Name', 'steel' ),
		'singular_name'       => _x( 'Quote', 'Post Type Singular Name', 'steel' ),
		'menu_name'           => __( 'Quotes', 'steel' ),
		'parent_item_colon'   => __( 'Parent Quote:', 'steel' ),
		'all_items'           => __( 'All Quotes', 'steel' ),
		'view_item'           => __( 'View Quote', 'steel' ),
		'add_new_item'        => __( 'Add New Quote', 'steel' ),
		'add_new'             => __( 'New Quote', 'steel' ),
		'edit_item'           => __( 'Edit Quote', 'steel' ),
		'update_item'         => __( 'Update Quote', 'steel' ),
		'search_items'        => __( 'Search quotes', 'steel' ),
		'not_found'           => __( 'No quotes found', 'steel' ),
		'not_found_in_trash'  => __( 'No quotes found in Trash. Did you check recycling?', 'steel' ),
	);

	$post_args = array(
		'label'               => __( 'steel_quote', 'steel' ),
		'description'         => __( 'Quotes', 'steel' ),
		'labels'              => $post_labels,
		'supports'            => array(''),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 100,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => false,
		'capability_type'     => 'post',
	);

	register_post_type( 'steel_quote', $post_args );
 
 $tax_labels = array(
   'name'                       => _x( 'Quote Lists', 'Taxonomy General Name', 'steel' ),
   'singular_name'              => _x( 'Quote List', 'Taxonomy Singular Name', 'steel' ),
   'menu_name'                  => __( 'Quote List', 'steel' ),
   'all_items'                  => __( 'All Lists', 'steel' ),
   'parent_item'                => __( 'Parent List', 'steel' ),
   'parent_item_colon'          => __( 'Parent List:', 'steel' ),
   'new_item_name'              => __( 'New List Name', 'steel' ),
   'add_new_item'               => __( 'Add New Quote List', 'steel' ),
   'edit_item'                  => __( 'Edit List', 'steel' ),
   'update_item'                => __( 'Update List', 'steel' ),
   'separate_items_with_commas' => __( 'Separate lists with commas', 'steel' ),
   'search_items'               => __( 'Search quote lists', 'steel' ),
   'add_or_remove_items'        => __( 'Add or remove quote lists', 'steel' ),
   'choose_from_most_used'      => __( 'Choose from the most used lists', 'steel' ),
	);

	$tax_args = array(
		'labels'                     => $tax_labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
	);

	register_taxonomy( 'steel_quote_lists', 'steel_quote', $tax_args );
	wp_insert_term( 'Quotes', 'steel_quote_lists', array( 'slug'=>'quotes', 'description' => 'Basic Quotes category' ) );
	wp_insert_term( 'Testimonials', 'steel_quote_lists', array( 'slug'=>'testimonials', 'description' => 'Testimonial quotes' ) );
}

add_action('admin_menu', 'register_quotes_submenus');
function register_quotes_submenus() {
	add_submenu_page( 'sparks', 'All Quotes', 'All Quotes', 'edit_posts', 'edit.php?post_type=steel_quote&mode=excerpt', '' );
	add_submenu_page( 'sparks', 'New Quote', 'New Quote', 'edit_posts', 'post-new.php?post_type=steel_quote', '' );
	add_submenu_page( 'sparks', 'Quote Lists', 'Quote Lists', 'manage_categories', 'edit-tags.php?taxonomy=steel_quote_lists&post_type=steel_quote', '' );
	
}

/*
 * Register custom meta box for steel_quote
 */
add_action( 'add_meta_boxes', 'steel_quote_add_custom_boxes' );
function steel_quote_add_custom_boxes() {
	add_meta_box('steel_quote_meta', 'Quote Details', 'steel_quote_meta', 'steel_quote', 'normal', 'high');
}
function steel_quote_meta() {
	global $post;
	$custom = get_post_custom($post->ID);
?>
    <p>
    	<label class="screen-reader-text" for="excerpt"><?php _e('Excerpt') ?></label><textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
    	<input type="text" size="40" name="post_title" value="<?php the_title(); ?>" placeholder="Cite" />
    </p>
    
	<?php
}

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
		parent::__construct( 'list', __('Steel: Random Quote(s)'), $widget_ops );
	}

	function widget($args, $instance) {
		// Get list
		$list = ! empty( $instance['list'] ) ? get_term( $instance['list'], 'steel_quote_lists' ) : false;

		if ( !$list )
			return;

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$quotes = new WP_Query(
			array(
				'post_type' => 'steel_quote',
				'orderby' => 'rand',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'steel_quote_lists',
						'field' => 'slug',
						'terms' => $list->slug
					)
				)
			)
		);
		
		while ($quotes->have_posts()) : $quotes->the_post(); ?>
    	<blockquote><?php the_excerpt(); ?><cite><?php the_title(); ?></cite></blockquote>
    <?php endwhile;

		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['list'] = (int) $new_instance['list'];
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$list = isset( $instance['list'] ) ? $instance['list'] : '';

		// Get menus
		$terms = get_terms( 'steel_quote_lists', array( 'hide_empty' => false ) );

		// If no menus exists, direct the user to go and create some.
		if ( !$terms ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('Select List:'); ?></label>
			<select id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>">
		<?php
			foreach ( $terms as $term ) {
				echo '<option value="' . $term->term_id . '"'
					. selected( $list, $term->term_id, false )
					. '>'. $term->name . '</option>';
			}
		?>
			</select>
		</p>
		<?php
	}
}

/*
 * Create [quote] shortcode
 */
add_shortcode( 'quote', 'quote_shortcode' );
function quote_shortcode( $atts ) {
	extract( shortcode_atts( array( 'list' => 'quotes' ), $atts ) );
	$quote_list = ! empty( $list ) ? get_term( $list, 'steel_quote_lists' ) : false;
	if ( !$list )
			return;
	$quoteshortcode = new WP_Query(
		array(
			'post_type' => 'steel_quote',
			'orderby' => 'rand',
			'posts_per_page' => 1,
			'tax_query' => array(
				array(
					'taxonomy' => 'steel_quote_lists',
					'field' => 'slug',
					'terms' => $list
				)
			)
		)
	);
	
	while ($quoteshortcode->have_posts()) : $quoteshortcode->the_post(); ?>
		<blockquote><?php the_excerpt(); ?><cite><?php the_title(); ?></cite></blockquote>
	<?php endwhile;
}
?>