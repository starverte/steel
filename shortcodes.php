<?php
/**
 * Various shortcodes for easily adding customized content
 *
 * @package Steel\Shortcodes
 */

/**
 * Deregister shortcodes from other plugins and themes
 */
if ( shortcode_exists( 'alert' )       ) { remove_shortcode( 'alert' ); }
if ( shortcode_exists( 'badge' )       ) { remove_shortcode( 'badge' ); }
if ( shortcode_exists( 'btn' )         ) { remove_shortcode( 'btn' ); }
if ( shortcode_exists( 'btn_group' )   ) { remove_shortcode( 'btn_group' ); }
if ( shortcode_exists( 'column' )      ) { remove_shortcode( 'column' ); }
if ( shortcode_exists( 'glyph' )       ) { remove_shortcode( 'glyph' ); }
if ( shortcode_exists( 'label' )       ) { remove_shortcode( 'label' ); }
if ( shortcode_exists( 'panel' )       ) { remove_shortcode( 'panel' ); }
if ( shortcode_exists( 'panel_group' ) ) { remove_shortcode( 'panel_group' ); }
if ( shortcode_exists( 'progress' )    ) { remove_shortcode( 'progress' ); }
if ( shortcode_exists( 'tooltip' )     ) { remove_shortcode( 'tooltip' ); }

/**
 * Builds the Alert shortcode output.
 *
 * Provide contextual feedback messages for typical user actions
 * with the handful of available and flexible alert messages.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display alert.
 */
function steel_shortcode_alert( $atts, $content = '' ) {
  extract( shortcode_atts( array( 'color' => 'info' ), $atts ) );

  $new = strip_tags( $content, '<a><strong><em><code><ol><ul><li>' );

  $alert_class = 'alert-' . $color;

  return '<div class="alert '. $alert_class .'">' . $new . '</div>';
}
add_shortcode( 'alert', 'steel_shortcode_alert' );

/**
 * Builds the Badge shortcode output.
 *
 * Easily highlight new or unread items to links and more.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display badge.
 */
function steel_shortcode_badge( $atts, $content = '' ) {
  $new = strip_tags( $content, '<a>' );
  return '<span class="badge">' . $new . '</span>';
}
add_shortcode( 'badge', 'steel_shortcode_badge' );

/**
 * Builds the Button shortcode output.
 *
 * Use Bootstrap’s custom button styles for actions in forms, dialogs, and more.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display button.
 */
function steel_shortcode_btn( $atts, $content = '' ) {
  extract( shortcode_atts( array(
    'color'     => 'default',
    'link'      => '#',
    'placement' => 'top auto',
    'toggle'    => '',
    'title'     => '',
    'body'      => '',
    'target'    => '',
  ), $atts ) );

  $new = strip_tags( $content, '<a><strong><em>' );

  $btn_class = 'btn-' . $color;

  switch ( $toggle ) {
    case 'tooltip':
      if ( ! empty( $title ) ) {
        $btn_class .= ' steel-tooltip';
        $data       = ' data-toggle="tooltip"';
        $data      .= ' data-placement="' . $placement . '"';
      } else { $data = ''; }
      break;
    case 'popover':
      if ( ! empty( $body ) ) {
        $btn_class .= ' steel-popover';
        $data       = ' data-toggle="popover"';
        $data      .= ' data-placement="' . $placement . '"';
        $data      .= ' data-content="' . $body. '"';
      } else { $data = ''; }
      break;
    default:
      $data = '';
      break;
  }

  $output  = '<a ';
  $output .= 'class="btn '. $btn_class .'" ';
  $output .= 'popover' !== $toggle ? 'href="' . $link . '"' : '';
  $output .= $data;
  $output .= ! empty( $title )  ? ' title="'  . $title  . '"' : '';
  $output .= ! empty( $target ) ? ' target="' . $target . '"' : '';
  $output .= '>';
  $output .= do_shortcode( $new );
  $output .= '</a>';
  return $output;
}
add_shortcode( 'btn', 'steel_shortcode_btn' );

/**
 * Builds the Button Group shortcode output.
 *
 * Group a series of buttons together on a single line with the button group.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display button group.
 */
function steel_shortcode_btn_group( $atts, $content = '' ) {
  $new = strip_tags( $content, '<a><strong><code>' );

  $output  = '<div class="btn-group">';
  $output .= do_shortcode( $new );
  $output .= '</div>';
  return $output;
}
add_shortcode( 'btn_group', 'steel_shortcode_btn_group' );

/**
 * Builds the Column shortcode output.
 *
 * Add columns to a post or page.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display column.
 */
function steel_shortcode_column( $atts, $content = '' ) {
  extract( shortcode_atts( array(
    'title' => '',
    'num'   => '2',
    'first' => false,
    'last'  => false,
  ), $atts ) );

  switch ( $num ) {
    case '2' :
      $style = 'col-lg-6 col-md-6';
      break;
    case '3' :
      $style = 'col-lg-4 col-md-4';
      break;
    case '4' :
      $style = 'col-lg-3 col-md-3';
      break;
    case '5' :
      if ( $first or $last ) {
        $style = 'col-lg-3 col-md-3';
      } else {
        $style = 'col-lg-2 col-md-2';
      }
      break;
    case '6' :
      $style = 'col-lg-2 col-md-2';
      break;
  }

  $new = strip_tags( $content, '<a><strong><em><blockquote><code><ol><ul><li>' );

  if ( $first ) {
    if ( ! empty( $title ) ) {
      return '<div class="row-fluid"><div class="' . $style . '"><h3>' . esc_attr( $title ) .'</h3>' . $new . '</div>';
    } else {
      return '<div class="row-fluid"><div class="' . $style . '">' . $new . '</div>';
    }
  } elseif ( $last ) {
    if ( ! empty( $title ) ) {
      return '<div class="' . $style . '"><h3>' . esc_attr( $title ) .'</h3>' . $new . '</div></div>';
    } else {
      return '<div class="' . $style . '">' . $new . '</div></div>';
    }
  } else {
    if ( ! empty( $title ) ) {
      return '<div class="' . $style . '"><h3>' . esc_attr( $title ) .'</h3>' . $new . '</div>';
    } else {
      return '<div class="' . $style . '">' . $new . '</div>';
    }
  }
}
add_shortcode( 'column', 'steel_shortcode_column' );

/**
 * Builds the Glyphicon shortcode output.
 *
 * Add a glyphicon to any post or page.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display glyphicon.
 */
function steel_shortcode_glyphicon( $atts, $content = '' ) {
  extract( shortcode_atts( array( 'icon' => '' ), $atts ) );
  return '<i class="glyphicon glyphicon-'. $icon .'"></i> ';
}
add_shortcode( 'glyph', 'steel_shortcode_glyphicon' );

/**
 * Builds the Label shortcode output.
 *
 * Small and adaptive tag for adding context to just about any content.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display label.
 */
function steel_shortcode_label( $atts, $content = '' ) {
  extract( shortcode_atts( array( 'color' => 'default' ), $atts ) );

  $new = strip_tags( $content, '<a>' );

  $label_class = 'label-' . $color;

  return '<span class="label '. $label_class .'">' . $new . '</span>';
}
add_shortcode( 'label', 'steel_shortcode_label' );

/**
 * Builds the Panel shortcode output.
 *
 * While not always necessary, sometimes you need to put your DOM in a box.
 * For those situations, try the panel component.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display panel.
 */
function steel_shortcode_panel( $atts, $content = '' ) {
  extract( shortcode_atts( array(
    'color'   => 'default',
    'heading' => '',
    'title'   => '',
    'footer'  => '',
  ), $atts ) );

  $new = strip_tags( $content, '<a><strong><em><code><ol><ul><li>' );

  global $group_id, $panel_int;
  $panel_int += 1;

  $panel_class = ' panel-' . $color;

  $output  = '<div class="panel'. $panel_class .'"';
  $output .= ! empty( $group_id ) ? ' data-parent="' . $group_id . '"' : '';
  $output .= '>';

  if ( ! empty( $title ) ) {
    $output .= '<div class="panel-heading">';
    $output .= '<h4 class="panel-title">';
    $output .= ! empty( $group_id ) ? '<a data-toggle="collapse" data-parent="#' . $group_id . '" href="#' . $group_id . '-' . $panel_int . '">' : '';
    $output .= $title;
    $output .= ! empty( $group_id ) ? '</a>' : '';
    $output .= '</h4>'; // .panel-title
    $output .= '</div>'; // .panel-heading
  } elseif ( ! empty( $heading ) ) { $output .= '<div class="panel-heading">' . $heading . '</div>'; }

  $collapse_class = 'panel-collapse collapse';
  $collapse_class .= 1 === $panel_int ? ' in' : '';

  $output .= ! empty( $group_id ) ? '<div class="' . $collapse_class . '" id="' . $group_id . '-' . $panel_int . '">' : '';
  $output .= '<div class="panel-body">' . $new . '</div>';
  $output .= ! empty( $group_id ) ? '</div>' : '';

  if ( ! empty( $footer ) ) {
    $output .= '<div class="panel-footer">' . $footer . '</div>';
  }

  $output .= '</div>';
  return $output;
}
add_shortcode( 'panel', 'steel_shortcode_panel' );

/**
 * Builds the Panel Group shortcode output.
 *
 * Extend the default collapse behavior to create an accordion with the panel component.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display panel group.
 */
function steel_shortcode_panel_group( $atts, $content = '' ) {
  $new = strip_tags( $content, '<a><strong><em><code><ol><ul><li>' );

  global $group_id, $panel_int;
  $group_id  = rand( 0,999 );
  $panel_int = 0;

  $output  = '<div class="panel-group" id="' . $group_id . '">';
  $output .= do_shortcode( $new );
  $output .= '</div>';
  return $output;
}
add_shortcode( 'panel_group', 'steel_shortcode_panel_group' );

/**
 * Builds the Progress shortcode output.
 *
 * Stylize the HTML5 <progress> element with a few extra classes
 * and some crafty browser-specific CSS.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display progress bar.
 */
function steel_shortcode_progress( $atts, $content = '' ) {
  extract( shortcode_atts( array(
    'color'   => 'default',
    'percent' => 0,
    'style'   => false,
  ), $atts ) );

  switch ( $color ) {
    case 'default' :
      $progress_bar_class = '';
      break;
    default :
      $progress_bar_class = ' progress-bar-' . $color;
      break;
  }

  switch ( $style ) {
    case 'striped' :
      $progress_class = ' progress-striped';
      break;
    case 'animated' :
      $progress_class = ' progress-striped active';
      break;
    default :
      $progress_class = '';
      break;
  }

  $output  = '<div class="progress'. $progress_class .'">';
  $output .= '<div class="progress-bar'. $progress_bar_class .'" role="progressbar" aria-valuenow="'. $percent .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $percent .'%">';
  $output .= '<span class="sr-only">'. $percent .'% Complete</span>';
  $output .= '</div></div>';
  return $output;
}
add_shortcode( 'progress', 'steel_shortcode_progress' );

/**
 * Builds the Tooltip shortcode output.
 *
 * Add a tooltip to any HTML element
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $atts Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display tooltip.
 */
function steel_shortcode_tooltip( $atts, $content = '' ) {
  extract( shortcode_atts( array(
    'title' => '',
    'placement' => 'top auto',
  ), $atts ) );

  $new = strip_tags( $content, '<a><strong><em>' );
  return '<a class="steel-tooltip" href="#" data-toggle="tooltip" title="' . $title . '" data-placement="' . $placement . '">' . $new . '</a>';
}
add_shortcode( 'tooltip', 'steel_shortcode_tooltip' );
