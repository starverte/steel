<?php
/*
 * Various shortcodes for easily adding customized content
 *
 * @package Steel
 * @module Shortcodes
 *
 * @since 1.1.0
 */
 
/*
 * Create [column] shortcode
 */
if ( shortcode_exists( 'column' ) ) { remove_shortcode( 'column' ); }
add_shortcode( 'column', 'column_shortcode' );
function column_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array(
    'title' => null,
    'num'   => '2',
    'first' => false,
    'last'  => false
  ), $atts ) );

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
 * Create [glyph icon=""] shortcode
 */
add_shortcode( 'glyph', 'glyphicon_shortcode' );
function glyphicon_shortcode( $atts ) {
  extract( shortcode_atts( array( 'icon' => '' ), $atts ) );
  return '<i class="glyphicon glyphicon-'. $icon .'"></i> ';
}

/*
 * Create [btn] shortcode
 */
if ( shortcode_exists( 'btn' ) ) { remove_shortcode( 'btn' ); }
add_shortcode( 'btn', 'btn_shortcode' );
function btn_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array(
    'color' => 'default',
    'link'  => ''
  ), $atts ) );

  $new = strip_tags($content, '<a><strong><code>');

  switch ($color) {    
    case 'blue'  : $btn_class = 'btn-primary'  ; break;
    case 'green' : $btn_class = 'btn-success'  ; break;
    case 'yellow': $btn_class = 'btn-warning'  ; break;
    case 'red'   : $btn_class = 'btn-danger'   ; break;
    default      : $btn_class = 'btn-' . $color; break;
  }
  return '<a class="btn '. $btn_class .'" href="' . $link . '">' . do_shortcode($new) . '</a>';
}

/*
 * Create [btn_group] shortcode
 */
if ( shortcode_exists( 'btn_group' ) ) { remove_shortcode( 'btn_group' ); }
add_shortcode( 'btn_group', 'btn_group_shortcode' );
function btn_group_shortcode( $atts, $content = null ) {
  $new = strip_tags($content, '<a><strong><code>');

  $output  = '<div class="btn-group">';
  $output .= do_shortcode($new);
  $output .= '</div>';
  return $output;
}

/*
 * Create [label] shortcode
 */
if ( shortcode_exists( 'label' ) ) { remove_shortcode( 'label' ); }
add_shortcode( 'label', 'label_shortcode' );
function label_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array( 'color' => 'default' ), $atts ) );

  $new = strip_tags($content, '<a>');

  switch ($color) {    
    case 'blue'       : $label_class = 'label-primary'  ; break;
    case 'green'      : $label_class = 'label-success'  ; break;
    case 'light-blue' : $label_class = 'label-info'     ; break;
    case 'yellow'     : $label_class = 'label-warning'  ; break;
    case 'red'        : $label_class = 'label-danger'   ; break;
    default           : $label_class = 'label-' . $color; break;
  }

  return '<span class="label '. $label_class .'">' . $new . '</span>';
}

/*
 * Create [badge] shortcode
 */
if ( shortcode_exists( 'badge' ) ) { remove_shortcode( 'badge' ); }
add_shortcode( 'badge', 'badge_shortcode' );
function badge_shortcode( $atts, $content = null ) {
  $new = strip_tags($content, '<a>');
  return '<span class="badge">' . $new . '</span>';
}
?>
