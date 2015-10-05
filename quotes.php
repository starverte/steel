<?php
/**
 * Creates custom post-type "quotes" for displaying random quotes, testimonials, etc.
 *
 * @package Steel\Quotes
 */

include_once dirname( __FILE__ ) . '/inc/class-widget-random-quote.php';

/**
 * Register widget for displaying random quote
 */
function steel_quotes_widgets_init() {
  register_widget( 'Steel_Widget_Random_Quote' );
}
add_action( 'widgets_init', 'steel_quotes_widgets_init' );
