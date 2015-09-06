<?php
/**
 *
 */
class Steel_Widget_List_Group extends WP_Widget {

  function __construct() {
    $widget_ops = array( 'description' => __('Add a custom menu to your sidebar.') );
    parent::__construct( 'steel_nav_menu_widget', __('Steel: Menu Panel'), $widget_ops );
  }

  function widget($args, $instance) {
    // Get menu
    $nav_menu = ! empty( $instance['steel_nav_menu_widget'] ) ? wp_get_nav_menu_object( $instance['steel_nav_menu_widget'] ) : false;

    if ( !$nav_menu )
      return;

    $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

    echo '<div class="panel panel-default">';

    if ( !empty($instance['title']) )
      echo '<div class="panel-heading"><h3 class="panel-title">'.$instance['title'].'</h3></div>';

    Steel_List_Group( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );

    echo '</div>';
  }

  function update( $new_instance, $old_instance ) {
    $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
    $instance['steel_nav_menu_widget'] = (int) $new_instance['steel_nav_menu_widget'];
    return $instance;
  }

  function form( $instance ) {
    $title = isset( $instance['title'] ) ? $instance['title'] : '';
    $nav_menu = isset( $instance['steel_nav_menu_widget'] ) ? $instance['steel_nav_menu_widget'] : '';

    // Get menus
    $menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );

    // If no menus exists, direct the user to go and create some.
    if ( !$menus ) {
      echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
      return;
    } ?>

    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('steel_nav_menu_widget'); ?>"><?php _e('Select Menu:'); ?></label>
      <select id="<?php echo $this->get_field_id('steel_nav_menu_widget'); ?>" name="<?php echo $this->get_field_name('steel_nav_menu_widget'); ?>">
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
