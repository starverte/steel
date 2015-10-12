<?php
/**
 * Creates custom widgets
 *
 * @package Steel\Widgets
 */

include_once dirname( __FILE__ ) . '/inc/class-widget-button.php';
include_once dirname( __FILE__ ) . '/inc/class-widget-link.php';
include_once dirname( __FILE__ ) . '/inc/class-widget-list-group.php';

/**
 * Register custom widgets
 */
function steel_widgets_init() {
  register_widget( 'Steel_Widget_Button' );
  register_widget( 'Steel_Widget_Link' );
  register_widget( 'Steel_Widget_List_Group' );
}
add_action( 'widgets_init', 'steel_widgets_init' );
