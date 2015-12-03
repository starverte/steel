<?php
/**
 * Widgets: Steel_Widget_Link class
 *
 * @package Steel\Widgets
 * @since 1.3.0
 */

/**
 * Link widget
 *
 * @uses WP_Widget
 */
class Steel_Widget_Link extends WP_Widget {

  /**
   * PHP5 constructor.
   */
  function __construct() {
    $widget_ops = array(
      'classname' => 'steel-widget-link',
      'description' => __( 'A widget that only displays a title with a link', 'steel' ),
    );
    $control_ops = array(
      'width' => 300,
      'height' => 350,
      'id_base' => 'steel-widget-link',
    );
    parent::__construct( 'steel-widget-link', __( 'Steel: Custom Link Widget (Legacy)', 'steel' ), $widget_ops, $control_ops );
  }

  /**
   * Echo the widget content.
   *
   * @param array $args     Display arguments including before_title, after_title,
   *                        before_widget, and after_widget.
   * @param array $instance The settings for the particular instance of the widget.
   */
  function widget( $args, $instance ) {
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );
    $href = $instance['href'];
    $class = $instance['class'];
    $show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;
    if ( $title ) { echo '<a class="steel-widget-link '. $class . '" href=' . $href . '>' . $before_widget . $before_title . $title . $after_title . $after_widget . '</a>'; }
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
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['href'] = strip_tags( $new_instance['href'] );
    $instance['class'] = strip_tags( $new_instance['class'] );
    return $instance;
  }

  /**
   * Output the settings update form.
   *
   * @param array $instance Current settings.
   */
  function form( $instance ) {
    $defaults = array( 'title' => __( '', 'steel' ), 'show_info' => true );
    $defaults = array( 'href' => __( 'http://', 'steel' ), 'show_info' => true );
    $defaults = array( 'class' => __( '', 'steel' ), 'show_info' => true );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'steel' ); ?></label>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if ( ! empty( $instance['title'] ) ) { echo $instance['title']; } ?>" style="width:100%;" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e( 'Link:', 'steel' ); ?></label>
      <input id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" value="<?php if ( ! empty( $instance['href'] ) ) { echo $instance['href']; } ?>" style="width:100%;" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'class' ); ?>"><?php _e( 'Classes:', 'steel' ); ?></label>
      <input id="<?php echo $this->get_field_id( 'class' ); ?>" name="<?php echo $this->get_field_name( 'class' ); ?>" value="<?php echo $instance['class']; ?>" style="width:100%;" />
    </p><?php
  }
}
