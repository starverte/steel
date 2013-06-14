<?php
/*
 * Various shortcodes for easily adding customized content
 *
 * @package Sparks
 * @sub-package Steel
 *
 * @since 0.7.0
 */
 
/*
 * Create [columns] and [column] shortcodes
 */
add_shortcode( 'columns', 'columns_shortcode' );
add_shortcode( 'column', 'column_shortcode' );
function columns_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'num' => 2 ), $atts ) );
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	return '<div class="columns columns-'. esc_attr($num) .'">' . do_shortcode($new) . '</div>';
}
function column_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => null ), $atts ) );
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	if (isset($title) && !empty($title)) { return '<div class="column"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div>'; }
	else { return '<div class="column"><p>' . $new . '</p></div>'; }
}
 ?>
