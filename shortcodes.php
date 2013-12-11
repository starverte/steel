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

/*
 * Create [alert] shortcode
 */
if ( shortcode_exists( 'alert' ) ) { remove_shortcode( 'alert' ); }
add_shortcode( 'alert', 'alert_shortcode' );
function alert_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array( 'color' => 'info' ), $atts ) );
  
  $new = strip_tags($content, '<a><strong><em><code><ol><ul><li>');
  
  switch ($color) {
    case 'green'      : $alert_class = 'alert-success'  ; break;
    case 'light-blue' : $alert_class = 'alert-info'     ; break;
    case 'yellow'     : $alert_class = 'alert-warning'  ; break;
    case 'red'        : $alert_class = 'alert-danger'   ; break;
    default           : $alert_class = 'alert-' . $color; break;
  }
  
  return '<div class="alert '. $alert_class .'">' . $new . '</div>';
}

/*
 * Create [progress] shortcode
 */
if ( shortcode_exists( 'progress' ) ) { remove_shortcode( 'progress' ); }
add_shortcode( 'progress', 'progress_shortcode' );
function progress_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array(
    'color'   => 'default',
    'percent' => null,
    'style'   => false
  ), $atts ) );

  switch ($color) {
    case 'default'    : $progress_bar_class = ''                       ; break;
    case 'green'      : $progress_bar_class = ' progress-bar-success'  ; break;
    case 'light-blue' : $progress_bar_class = ' progress-bar-info'     ; break;
    case 'yellow'     : $progress_bar_class = ' progress-bar-warning'  ; break;
    case 'red'        : $progress_bar_class = ' progress-bar-danger'   ; break;
    default           : $progress_bar_class = ' progress-bar-' . $color; break;
  }

  switch ($style) {
    case 'striped'  : $progress_class = ' progress-striped'        ; break;
    case 'animated' : $progress_class = ' progress-striped active' ; break;
    default         : $progress_class = ''                         ; break;
  }

  $output  = '<div class="progress'. $progress_class .'">';
  $output .= '<div class="progress-bar'. $progress_bar_class .'" role="progressbar" aria-valuenow="'. $percent .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $percent .'%">';
  $output .= '<span class="sr-only">'. $percent .'% Complete</span>';
  $output .= '</div></div>';
  return $output;
}

/*
 * Create [panel] shortcode
 */
if ( shortcode_exists( 'panel' ) ) { remove_shortcode( 'panel' ); }
add_shortcode( 'panel', 'panel_shortcode' );
function panel_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array(
    'color'   => 'default',
    'heading' => null,
    'title'   => null,
    'footer'  => null
  ), $atts ) );
  
  $new = strip_tags($content, '<a><strong><em><code><ol><ul><li>');

  switch ($color) {
    case 'blue'      : $panel_class = ' panel-primary'  ; break;
    case 'green'     : $panel_class = ' panel-success'  ; break;
    case 'light-blue': $panel_class = ' panel-info'     ; break;
    case 'yellow'    : $panel_class = ' panel-warning'  ; break;
    case 'red'       : $panel_class = ' panel-danger'   ; break;
    default          : $panel_class = ' panel-' . $color; break;
  }

  $output  = '<div class="panel'. $panel_class .'">';

  if (!empty($title)) {
    $output .= '<div class="panel-heading">';
    $output .= '<div class="panel-title">' . $title . '</div>';
    $output .= '</div>';
  }
  elseif (!empty($heading)) { $output .= '<div class="panel-heading">' . $heading . '</div>'; }

  $output .= '<div class="panel-body">' . $new . '</div>';

  if (!empty($footer)) {
    $output .= '<div class="panel-footer">' . $footer . '</div>';
  }

  $output .= '</div>';
  return $output;
}
?>
