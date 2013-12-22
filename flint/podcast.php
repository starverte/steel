<?php
/*
 * More Podcast module functions; specifically for Flint
 *
 * @package Steel
 * @module Podcast
 *
 */
 
add_action('flint_entry_title_steel_pod_episode','steel_pod_episode_subtitle');
function steel_pod_episode_subtitle($args) {
  global $post;
  $defaults = array('container' => 'h2');
  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $custom = get_post_custom($post->ID);
  $output  = '<' . $container . '>';
  $output .= steel_pod_episode_meta('subtitle');
  $output  .= '</' . $container . '>';
  echo $output;
}

add_action('flint_entry_meta_header_steel_pod_episode','steel_pod_episode_header');
function steel_pod_episode_header($args) {
  global $post;
  $defaults = array('author' => '<strong>Author:</strong> ');
  $args = wp_parse_args( $args, $defaults );
  extract($args);
  
  echo do_shortcode( '[audio url=' . steel_pod_episode_meta('media_url') . ']');
  
  $output  = '<p>'. $author;
  $output .= steel_pod_episode_meta('author');
  $output  .= '</p>';
  echo $output;
}

add_action('flint_entry_meta_footer_steel_pod_episode','steel_pod_episode_footer');
function steel_pod_episode_footer() {
  global $post;
  
  $custom = get_post_custom($post->ID);
  $output  = '<p>';
  
  $output  .= '</p>';
  echo $output;
}
?>