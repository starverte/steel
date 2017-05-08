<?php
/**
 * The Template for displaying Media Series.
 *
 * @package Steel\Broadcast
 * @since 1.3.0
 */

/**
 * Append Media Series content with `the_content` filter
 *
 * @internal
 * @param string $content Content of the current post.
 */
function steel_broadcast_the_content( $content ) {
  if ( is_single() && 'steel_broadcast' == get_post_type() ) {
    $post_custom = get_post_custom();
    $media = array();
    if ( ! empty( $post_custom['item_list'][0] ) ) {
      $item_list = $post_custom['item_list'][0];
      $items = explode( ',', $post_custom['item_list'][0] );
      foreach ( $items as $item_id ) {
        array_push( $media, get_post( $item_id ) );
      }
    } else {
      $item_list = 0;
    }

    $content .= '<div id="media-series-' . the_ID() . '" class="media-series">';

    foreach ( $media as $medium ) {
      $medium_custom = get_post_custom( $medium->ID );
      $medium_metadata = wp_get_attachment_metadata( $medium->ID );

      $content .= '<div id="media-item-' . $medium->ID . '" class="media-item">';
      $content .= '<h4 class="media-title">' . $medium->post_title . '</h4>';
      $content .= '<p class="media-details">';
      $content .= '<span class="media-date">' . date( get_option( 'date_format' ), strtotime( $medium_custom['date_published'][0] ) ) . '</span>';
      $content .= '<span class="media-spacer"> | </span>';
      $content .= '<span class="media-artist">' . $medium_metadata['artist'] . '</span>';
      $content .= '<span class="media-spacer"> | </span>';
      $content .= '<span class="media-content">' . $medium->post_content . '</span>';
      $content .= '</p>'; // End .media-details.
      $content .= '<audio controls preload="none">';
      $content .= '<source src="' . $medium->guid . '" type="' . get_post_mime_type( $medium->ID )  . '">';
      $content .= 'Your browser does not support the audio tag.';
      $content .= '</audio>';
      $content .= '</div>'; // End .media-item.
    }

    $content .= '</div>'; // End .media-series.
  }
  return $content;
}
add_filter( 'the_content', 'steel_broadcast_the_content' );
