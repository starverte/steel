<?php
/**
 * Widgets: Steel_Widget_Random_Quote class
 *
 * @package Steel\Widgets
 * @since 1.3.0
 */

/**
 * Random quote widget
 *
 * @uses WP_Widget
 */
class Steel_Widget_Random_Quote extends WP_Widget {

  /**
   * PHP5 constructor.
   */
  function __construct() {
    $widget_ops = array(
      'classname' => 'steel-widget-random-quote',
      'description' => __( 'Displays a random quote', 'steel' ),
    );
    $control_ops = array(
      'width' => 300,
      'height' => 350,
      'id_base' => 'steel-widget-random-quote',
    );
    parent::__construct( 'steel-widget-random-quote', __( 'Steel: Random Quote', 'steel' ), $widget_ops, $control_ops );
  }

  /**
   * Echo the widget content.
   *
   * @param array $args     Display arguments including before_title, after_title,
   *                        before_widget, and after_widget.
   * @param array $instance The settings for the particular instance of the widget.
   */
  function widget( $args, $instance ) {
    $cat = ! empty( $instance['cat'] ) ? get_category( $instance['cat'] ) : false;

    if ( ! $cat ) {
      return;
    }

    $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

    echo $args['before_widget'];

    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . $instance['title'] . $args['after_title'];
    }

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
            'terms' => $cat->slug,
          ),
          array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => array( 'post-format-quote' ),
          )
        ),
      )
    );

    while ( $quotes->have_posts() ) : $quotes->the_post(); ?>
      <blockquote><?php the_content(); ?></blockquote>
    <?php endwhile;

    echo $args['after_widget'];
  }

  /**
   * Update a particular instance.
   *
   * This function should check that $new_instance is set correctly. The newly-calculated
   * value of `$instance` should be returned. If false is returned, the instance won't be
   * saved/updated.
   *
   * @param array $new_instance New settings for this instance as input by the user via
   *                            {@see WP_Widget::form()}.
   * @param array $old_instance Old settings for this instance.
   * @return array Settings to save or bool false to cancel saving.
   */
  function update( $new_instance, $old_instance ) {
    $instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
    $instance['list'] = (int) $new_instance['list'];
    return $instance;
  }

  /**
   * Output the settings update form.
   *
   * @param array $instance Current settings.
   */
  function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
    $list  = ! empty( $instance['list'] )  ? $instance['list']  : '';

    $cats = get_categories();
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e( 'Category:' ); ?></label>
      <select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>">
        <option value="">All Categories</option>
        <?php
          foreach ( $cats as $cat ) {
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
