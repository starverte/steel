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
 * Check to see if theme Flint is active.
 *
 * @deprecated 1.3.0 Use steel_is_flint_active()
 */
function is_flint_active() {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_is_flint_active()' );
  return steel_is_flint_active();
}

/**
 * Generate Like button
 *
 * @deprecated 1.3.0 Use steel_btn_like()
 *
 * @param array $args An array of arguments
 */
function like_this( $args = array() ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_btn_like()' );
  return steel_btn_like( $args );
}

/**
 * Generate Pin It button (Pinterest)
 *
 * @deprecated 1.3.0 Use steel_btn_pin_it()
 *
 * @param array $args An array of arguments
 */
function pin_it( $args = array() ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_btn_pin_it()' );
  return steel_btn_pin_it( $args );
}

/**
 * Check to see if a particular Steel module is active.
 *
 * @deprecated 1.3.0 Use steel_is_module_active()
 */
function is_module_active( $module ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_is_module_active()' );
  return steel_is_module_active( $module );
}

/**
 * Generate Tweet button
 *
 * @deprecated 1.3.0 Use steel_btn_tweet()
 *
 * @param string $data_count The direction to display the Tweet count (horizontal, vertical, or none)
 * @param string $data_size  The size of the button (default or large)
 * @param string $data_via   The attribution will appear in a Tweet as " via @username" translated into the language of the Tweet author.
 * @param array  $args       An array of additional arguments
 */
function tweet_this( $data_count = 'horizontal' , $data_size = '' , $data_via = '' , $args = array() ) {
  steel_deprecated_function( __FUNCTION__, '1.3.0', 'steel_btn_tweet()' );
  return steel_btn_tweet( $data_count, $data_size, $data_via, $args );
}

