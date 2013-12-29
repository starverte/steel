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
 * Create [tooltip] shortcode
 */
add_shortcode( 'tooltip', 'tooltip_shortcode' );
function tooltip_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array(
    'title' => '',
    'placement' => 'top auto'
  ), $atts ) );
  
  $new = strip_tags($content, '<a><strong><em>');
  return '<a class="steel-tooltip" href="#" data-toggle="tooltip" title="' . $title . '" data-placement="' . $placement . '">' . $new . '</a>';
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
    'color'     => 'default',
    'link'      => '#',
    'placement' => 'top auto',
    'toggle'    => null,
    'title'     => null,
    'body'      => null
  ), $atts ) );

  $new = strip_tags($content, '<a><strong><em>');

  switch ($color) {    
    case 'blue'  : $btn_class = 'btn-primary'  ; break;
    case 'green' : $btn_class = 'btn-success'  ; break;
    case 'yellow': $btn_class = 'btn-warning'  ; break;
    case 'red'   : $btn_class = 'btn-danger'   ; break;
    default      : $btn_class = 'btn-' . $color; break;
  }
  
  switch ($toggle){
    case 'tooltip':
      if (!empty($title)) {
        $btn_class .= ' steel-tooltip';
        $data       = ' data-toggle="tooltip"';
        $data      .= ' data-placement="' . $placement . '"';
      }
      else { $data = ''; }
      break;
    case 'popover':
      if (!empty($body)) {
        $btn_class .= ' steel-popover';
        $data       = ' data-toggle="popover"';
        $data      .= ' data-placement="' . $placement . '"';
        $data      .= ' data-content="' . $body. '"';
      }
      else { $data = ''; }
      break;
    default:
      $data = '';
      break;
  }
  
  $output  = '<a ';
  $output .= 'class="btn '. $btn_class .'" ';
  $output .= $toggle != 'popover' ? 'href="' . $link . '"' : '';
  $output .= $data;
  $output .= !empty($title) ? ' title="' . $title . '"' : '';
  $output .= '>';
  $output .= do_shortcode($new);
  $output .= '</a>';
  return $output;
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
  
  global $group_id, $panel_int;
  $panel_int += 1;

  switch ($color) {
    case 'blue'      : $panel_class = ' panel-primary'  ; break;
    case 'green'     : $panel_class = ' panel-success'  ; break;
    case 'light-blue': $panel_class = ' panel-info'     ; break;
    case 'yellow'    : $panel_class = ' panel-warning'  ; break;
    case 'red'       : $panel_class = ' panel-danger'   ; break;
    default          : $panel_class = ' panel-' . $color; break;
  }

  $output  = '<div class="panel'. $panel_class .'"';
  $output .= !empty($group_id) ? ' data-parent="' . $group_id . '"' : '';
  $output .= '>';

  if (!empty($title)) {
    $output .= '<div class="panel-heading">';
    $output .= '<h4 class="panel-title">';
    $output .= !empty($group_id) ? '<a data-toggle="collapse" data-parent="#' . $group_id . '" href="#' . $group_id . '-' . $panel_int . '">' : '';
    $output .= $title;
    $output .= !empty($group_id) ? '</a>' : '';
    $output .= '</h4>'; //.panel-title
    $output .= '</div>'; //.panel-heading
  }
  elseif (!empty($heading)) { $output .= '<div class="panel-heading">' . $heading . '</div>'; }
  
  $collapse_class = 'panel-collapse collapse';
  $collapse_class .= $panel_int == 1 ? ' in' : '';
  
  $output .= !empty($group_id) ? '<div class="' . $collapse_class . '" id="' . $group_id . '-' . $panel_int . '">' : '';
  $output .= '<div class="panel-body">' . $new . '</div>';
  $output .= !empty($group_id) ? '</div>' : '';

  if (!empty($footer)) {
    $output .= '<div class="panel-footer">' . $footer . '</div>';
  }

  $output .= '</div>';
  return $output;
}

/*
 * Create [panel_group] shortcode
 */
if ( shortcode_exists( 'panel_group' ) ) { remove_shortcode( 'panel_group' ); }
add_shortcode( 'panel_group', 'panel_group_shortcode' );
function panel_group_shortcode( $atts, $content = null ) {
  $new = strip_tags($content, '<a><strong><em><code><ol><ul><li>');
  
  global $group_id, $panel_int;
  $group_id  = rand(0,999);
  $panel_int = 0;

  $output  = '<div class="panel-group" id="' . $group_id . '">';
  $output .= do_shortcode($new);
  $output .= '</div>';
  return $output;
}
?>
