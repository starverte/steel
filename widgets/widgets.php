<?php
/**
 * Creates custom widgets
 *
 * @package Steel\Widgets
 */

/**
 * Include Steel_Widget_Button class
 */
include_once dirname( __FILE__ ) . '/class-widget-button.php';

/**
 * Include Steel_Widget_Link class
 */
include_once dirname( __FILE__ ) . '/class-widget-link.php';

/**
 * Include Steel_Widget_List_Group class
 */
include_once dirname( __FILE__ ) . '/class-widget-list-group.php';

/**
 * Register custom widgets
 */
function steel_widgets_init() {
  register_widget( 'Steel_Widget_Button' );
  register_widget( 'Steel_Widget_Link' );
  register_widget( 'Steel_Widget_List_Group' );
}
add_action( 'widgets_init', 'steel_widgets_init' );
