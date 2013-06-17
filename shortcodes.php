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

/*
 * Create [tooltip] shortcode
 */
add_shortcode( 'tooltip', 'tooltip_shortcode' );
function tooltip_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => '', 'placement' => 'top' ), $atts ) );
	$new = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	return '<a class="steel-tooltip" href="#" data-toggle="tooltip" title="' . $title . '" data-placement="' . $placement . '">' . $new . '</a>';
}

/*
 * Create [popover] shortcode
 */
add_shortcode( 'popover', 'popover_shortcode' );
function tooltip_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'title' => '', 'placement' => 'top', 'text' => null, 'color' => 'default', 'size' => 'default' ), $atts ) );
	$new_content = strip_tags($content, '<a><strong><em><blockquote><code><ol><ul><li>');
	$new_text = strip_tags($text, '<a><strong><em><blockquote><code><ol><ul><li>');
	switch ($color) {
		case 'default' : $btn_color = ''; break;
		case 'blue' : $btn_color = ' btn-primary'; break;
		case 'green' : $btn_color = ' btn-success'; break;
		case 'yellow' : $btn_color = ' btn-warning'; break;
		case 'red' : $btn_color = ' btn-danger'; break;
		case 'black' : $btn_color = ' btn-inverse'; break;
	}
	switch ($size) {
		case 'default' : $btn_size = ''; break;
		case 'large' : $btn_size = ' btn-large'; break;
		case 'small' : $btn_size = ' btn-small'; break;
		case 'mini' : $btn_size = ' btn-mini'; break;
	}
	return '<a class="btn steel-tooltip' . $btn_color . $btn_size . '" href="#" data-toggle="popover" title="' . $title . '" data-content="' . $new_text . '" data-placement="' . $placement . '">' . $new_content . '</a>';
}
?>
