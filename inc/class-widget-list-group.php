<?php
/**
 * Widgets: Steel_Widget_List_Group class
 *
 * @package Steel\Widgets
 * @since 1.3.0
 */

/**
 * List group widget
 *
 * @uses WP_Widget
 */
class Steel_Widget_List_Group extends WP_Widget {

  /**
   * PHP5 constructor.
   */
  function __construct() {
    $widget_ops = array(
      'classname' => 'steel-widget-list-group',
      'description' => __( 'Add a custom menu to your sidebar', 'steel' ),
    );
    $control_ops = array(
      'width' => 300,
      'height' => 350,
      'id_base' => 'steel-widget-list-group',
    );
    parent::__construct( 'steel-widget-list-group', __( 'Steel: Menu Panel', 'steel' ), $widget_ops, $control_ops );
  }

  /**
   * Echo the widget content.
   *
   * @param array $args     Display arguments including before_title, after_title,
   *                        before_widget, and after_widget.
   * @param array $instance The settings for the particular instance of the widget.
   */
  function widget( $args, $instance ) {
    $nav_menu = ! empty( $instance['list-group'] ) ? wp_get_nav_menu_object( $instance['list-group'] ) : false;

    if ( ! $nav_menu ) {
      return;
    }

    $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

    echo '<div class="panel panel-default">';

    if ( ! empty( $instance['title'] ) ) {
      echo '<div class="panel-heading"><h3 class="panel-title">'.$instance['title'].'</h3></div>';
    }

    Steel_Walker_Nav_Menu_List_Group( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );

    echo '</div>';
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
    $instance['list-group'] = (int) $new_instance['list-group'];
    return $instance;
  }

  /**
   * Output the settings update form.
   *
   * @param array $instance Current settings.
   * @return string Default return is 'noform'.
   */
  function form( $instance ) {
    $title = isset( $instance['title'] ) ? $instance['title'] : '';
    $nav_menu = isset( $instance['list-group'] ) ? $instance['list-group'] : '';

    $menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );

    if ( ! $menus ) {
      echo '<p>'. sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), admin_url( 'nav-menus.php' ) ) .'</p>';
      return;
    } ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'list-group' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
      <select id="<?php echo $this->get_field_id( 'list-group' ); ?>" name="<?php echo $this->get_field_name( 'list-group' ); ?>">
      <?php
        foreach ( $menus as $menu ) {
          echo '<option value="' . $menu->term_id . '"'
          . selected( $nav_menu, $menu->term_id, false )
          . '>'. $menu->name . '</option>';
        }
      ?>
      </select>
    </p>
    <?php
  }
}
