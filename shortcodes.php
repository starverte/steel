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
 * Create [column] shortcode
 */
add_shortcode( 'column', 'column_shortcode' );
function column_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => null, 'num' => '2', 'first' => false, 'last' => false ), $atts ) );
	switch ($num) {
		case '2' : $style = 'span6'; break;
		case '3' : $style = 'span4'; break;
		case '4' : $style = 'span3'; break;
		case '5' : if ($first or $last) { $style = 'span3'; } else { $style = 'span2'; } break;
		case '6' : $style = 'span2'; break;
	}
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	if ($first) {
		if (!empty($title)) { return '<div class="row-fluid"><div class="' . $style . '"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div>'; }
		else { return '<div class="row-fluid"><div class="' . $style . '"><p>' . $new . '</p></div>'; }
	}
	elseif ($last) {
		if (!empty($title)) { return '<div class="' . $style . '"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div></div>'; }
		else { return '<div class="' . $style . '"><p>' . $new . '</p></div></div>'; }
	}
	else {
		if (!empty($title)) { return '<div class="' . $style . '"><h3>' . esc_attr($title) .'</h3><p>' . $new . '</p></div>'; }
		else { return '<div class="' . $style . '"><p>' . $new . '</p></div>'; }	
	}
}
?>
