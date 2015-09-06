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
