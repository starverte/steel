<?php
/**
 * Deprecated functions for backwards compatibility
 *
 * @package Steel
 * @since 1.3.0
 */

/**
 * Mark a function as deprecated and inform when it has been used.
 *
 * There is a hook deprecated_function_run that will be called that can be used
 * to get the backtrace up to what file and function called the deprecated
 * function.
 *
 * The current behavior is to trigger a user error if WP_DEBUG is true.
 *
 * This function is to be used in every function that is deprecated.
 *
 * @param string $function    The function that was called.
 * @param string $version     The version of Steel that deprecated the function.
 * @param string $replacement Optional. The function that should have been called. Default null.
 */
function steel_deprecated_function( $function, $version, $replacement = null ) {

	/**
	 * Filter whether to trigger an error for deprecated functions.
	 *
	 * @param bool $trigger Whether to trigger the error for deprecated functions. Default true.
	 */
	if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
    if ( ! is_null( $replacement ) )
      trigger_error( sprintf( __('%1$s is <strong>deprecated</strong> since Steel version %2$s! Use %3$s instead.', 'steel'), $function, $version, $replacement ) );
    else
      trigger_error( sprintf( __('%1$s is <strong>deprecated</strong> since Steel version %2$s with no alternative available.', 'steel'), $function, $version ) );
	}
}

/**
 * Returns current plugin version.
 *
 * @deprecated 1.2.0 Use actual version number instead.
 *
 * @todo Remove backwards compatibility in Steel 1.4
 */
function steel_version() {
  steel_deprecated_function( __FUNCTION__, '1.2.0' );
  $steel_plugin_data = get_plugin_data( dirname( __FILE__ ) . '/steel.php', false );
  return $steel_plugin_data['Version'];
}

/*
 * Display Team Profile Title
 * @deprecated 1.2.6 Use steel_profile_meta() instead
 */
function profile_title() {
  steel_deprecated_function( __FUNCTION__, '1.2.6', 'steel_profile_meta()' );
  echo steel_profile_meta( 'title' );
}

/*
 * Display Team Profile Email
 * @deprecated 1.2.6 Use steel_profile_meta() instead
 */
function profile_email() {
  steel_deprecated_function( __FUNCTION__, '1.2.6', 'steel_profile_meta()' );
  echo steel_profile_meta( 'email' );
}

/*
 * Display profile phone number
 * @deprecated 1.2.6 Use steel_profile_phone() instead
 */
function profile_phone( $pattern = "$1.$2.$3" ) {
  steel_deprecated_function( __FUNCTION__, '1.2.6', 'steel_profile_phone()' );
  echo steel_profile_phone( $pattern );
}

/**
 * [alert] shortcode
 *
 * Add Bootstrap alert.
 * Provide contextual feedback messages for typical user actions
 * with the handful of available and flexible alert messages.
 *
 * @deprecated 1.3.0 Use steel_shortcode_alert
 */
function alert_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_alert()' );
  return steel_shortcode_alert( $atts, $content );
}

/**
 * [badge] shortcode
 *
 * Add Bootstrap badge.
 * Easily highlight new or unread items to links and more.
 *
 * @deprecated 1.3.0 Use steel_shortcode_badge
 */
function badge_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_badge()' );
  return steel_shortcode_badge( $atts, $content );
}

/**
 * [btn_group] shortcode
 *
 * Add Bootstrap button group.
 * Group a series of buttons together on a single line with the button group.
 *
 * @deprecated 1.3.0 Use steel_shortcode_btn_group
 */
function btn_group_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_btn_group()' );
  return steel_shortcode_btn_group( $atts, $content );
}

/**
 * [btn] shortcode
 *
 * Add Bootstrap button.
 * Use Bootstrap’s custom button styles for actions in forms, dialogs, and more.
 *
 * @deprecated 1.3.0 Use steel_shortcode_btn
 */
function btn_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_btn()' );
  return steel_shortcode_btn( $atts, $content );
}

/**
 * [column] shortcode
 *
 * Add columns to a post or page.
 *
 * @deprecated 1.3.0 Use steel_shortcode_column
 */
function column_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_column()' );
  return steel_shortcode_column( $atts, $content );
}

/**
 * [glyphicon] shortcode
 *
 * Add Bootstrap glyphicon.
 *
 * @deprecated 1.3.0 Use steel_shortcode_glyphicon
 */
function glyphicon_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_glyphicon()' );
  return steel_shortcode_glyphicon( $atts, $content );
}

/**
 * [label] shortcode
 *
 * Add Bootstrap label.
 * Small and adaptive tag for adding context to just about any content.
 *
 * @deprecated 1.3.0 Use steel_shortcode_label
 */
function label_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_label()' );
  return steel_shortcode_label( $atts, $content );
}

/**
 * [panel_group] shortcode
 *
 * Add Bootstrap panel group.
 * Extend the default collapse behavior to create an accordion with the panel component.
 *
 * @deprecated 1.3.0 Use steel_shortcode_label
 */
function panel_group_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_panel_group()' );
  return steel_shortcode_panel_group( $atts, $content );
}

/**
 * [panel] shortcode
 *
 * Add Bootstrap panel.
 * While not always necessary, sometimes you need to put your DOM in a box.
 * For those situations, try the panel component.
 *
 * @deprecated 1.3.0 Use steel_shortcode_panel
 */
function panel_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_panel()' );
  return steel_shortcode_panel( $atts, $content );
}

/**
 * [progress] shortcode
 *
 * Add Bootstrap progress bar.
 * Stylize the HTML5 <progress> element with a few extra classes
 * and some crafty browser-specific CSS.
 *
 * @deprecated 1.3.0 Use steel_shortcode_progress
 */
function progress_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_progress()' );
  return steel_shortcode_progress( $atts, $content );
}

/**
 * [tooltip] shortcode
 *
 * Add Bootstrap tooltip.
 *
 * @deprecated 1.3.0 Use steel_shortcode_tooltip
 */
function tooltip_shortcode( $atts, $content = null ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_shortcode_tooltip()' );
  return steel_shortcode_tooltip( $atts, $content );
}
