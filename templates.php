<?php
/**
 * If current theme is Flint or a child theme of Flint,
 * these action hooks are used to display meta created
 * by the Steel plugin and its modules.
 *
 * @package Steel\Templates
 */

/**
 * Display team profile information
 */
function steel_profile_entry_meta_above() {
  $title = steel_profile_meta( 'title' );
  $email = steel_profile_meta( 'email' );
  $phone = steel_profile_phone();

  $output  = '';
  $output .= ! empty( $title ) ? '<h4>' . $title . '</h4>' : '';
  $output .= ! empty( $email ) | ! empty( $phone ) ? '<p>' : '';
  $output .= ! empty( $email ) ? $email : '';
  $output .= ! empty( $email ) && ! empty( $phone ) ? '<br>' : '';
  $output .= ! empty( $phone ) ? $phone : '';
  $output .= ! empty( $email ) | ! empty( $phone ) ? '</p>' : '';
  echo $output;
}
add_action( 'flint_entry_meta_above_steel_profile','steel_profile_entry_meta_above', 10 );
