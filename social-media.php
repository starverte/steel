<?php
/**
 * Includes social media buttons for Facebook, Twitter, and Pinterest
 *
 * @package Steel\Social Media
 * @since 1.3.0
 */

/**
 * Generate Like button
 *
 * @param array $args An array of arguments.
 */
function steel_btn_like( $args = array() ) {
  $url = get_permalink();

  $defaults = array(
    'data_href'       => $url,
    'data_send'       => 'false',
    'data_layout'     => 'standard',
    'data_show_faces' => 'false',
    'data_width'      => '450',
    'data_action'     => 'like',
    'data_font'       => 'lucida grande',
    'data_color'      => 'light',
    'data_ref'        => '',
  );

  $args = wp_parse_args( $args, $defaults );
  $args = (object) $args;

  printf(
    '<div class="fb-like" data-href="%s" data-send="%s" data-layout="%s" data-show-faces="%s" data-width="%s" data-action="%s" data-font="%s" data-colorscheme="%s" data-ref="%s"></div>',
    $args->data_href,
    $args->data_send,
    $args->data_layout,
    $args->data_show_faces,
    $args->data_width,
    $args->data_action,
    $args->data_font,
    $args->data_color,
    $args->data_ref
  );
}

/**
 * Generate Pin It button (Pinterest)
 *
 * @deprecated 1.3.0 Use steel_btn_pin_it()
 *
 * @param array $args An array of arguments.
 */
function steel_btn_pin_it( $args = array() ) {
  $url       = get_permalink();
  $title     = the_title( '', '', false );
  $thumb_id  = get_post_thumbnail_id();
  $thumbnail = wp_get_attachment_url( $thumb_id );

  $defaults = array(
    'data_url' => $url,
    'data_thumb' => $thumbnail,
    'data_text' => $title,
    'data_count' => 'horizontal',
  );

  $args = wp_parse_args( $args, $defaults );
  $args = (object) $args;

  printf(
    '<a href="http://pinterest.com/pin/create/button/?url=%s&media=%s&description=%s" class="pin-it-button" count-layout="%s"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>',
    $args->data_url,
    $args->data_thumb,
    $args->data_text,
    $args->data_count
  );
}

/**
 * Generate Tweet button
 *
 * @param string $data_count The direction to display the Tweet count (horizontal, vertical, or none).
 * @param string $data_size  The size of the button (default or large).
 * @param string $data_via   The attribution will appear in a Tweet as " via @username" translated into the language of the Tweet author.
 * @param array  $args       An array of additional arguments.
 */
function steel_btn_tweet( $data_count = 'horizontal', $data_size = '', $data_via = '', $args = array() ) {
  $url      = get_permalink();
  $title    = the_title( '', '', false );
  $language = get_bloginfo( 'language' );

  $defaults = array(
    'data_url'      => $url,
    'data_text'     => $title,
    'data_related'  => '',
    'data_lang'     => $language,
    'data_counturl' => $url,
    'data_hashtags' => '',
    'data_dnt'      => '',
  );

  $args = wp_parse_args( $args, $defaults );
  $args = (object) $args;

  if ( '' !== $args->data_hashtags ) {
    $tweet_class = 'twitter-hashtag-button';
    $hashtag     = '#' . $args->data_hashtags;
    $link        = 'https://twitter.com/intent/tweet?button_hashtag=' . $hashtag;
  } else {
    $tweet_class = 'twitter-share-button';
    $hashtag     = '';
    $link        = 'https://twitter.com/share';
  }

  printf(
    '<a href="%s" class="%s" data-count="%s" data-size="%s" data-via="%s" data-url="%s" data-text="%s" data-related="%s" data-lang="%s" data-counturl="%s" data_hashtags="%s" data-dnt="%s">Tweet</a>',
    $link,
    $tweet_class,
    $data_count,
    $data_size,
    $data_via,
    $args->data_url,
    $args->data_text,
    $args->data_related,
    $args->data_lang,
    $args->data_counturl,
    $args->data_hashtags,
    $args->data_dnt
  );
  printf( '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>' );
}
