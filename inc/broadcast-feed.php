<?php
/**
 * iTunes RSS Feed Template for displaying Podcast feed.
 *
 * @package Steel\Broadcast
 */

header( 'Content-Type: ' . feed_content_type( 'rss2' ) . '; charset=' . get_option( 'blog_charset' ), true );
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option( 'blog_charset' ).'"?'.'>';

$term = get_queried_object();
print_r( steel_broadcast_channel_data( $term->term_id ) );
$channel = steel_broadcast_channel_data( $term->term_id );
$channel_cats = steel_broadcast_channel_itunes_cat( $channel );
?>

<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">

<channel>
  <title><?php esc_html_e( $channel->name ); ?></title>
  <link><?php bloginfo_rss( 'url' ) ?></link>
  <language><?php bloginfo_rss( 'language' ); ?></language>
  <copyright>&#x2117; <?php echo $channel->copyright; ?></copyright>
  <itunes:subtitle><?php esc_html_e( $channel->name ); ?></itunes:subtitle>
  <itunes:author><?php esc_html_e( $channel->author ); ?></itunes:author>
  <itunes:summary><?php echo $channel->description; ?></itunes:summary>
  <description><?php echo $channel->description; ?></description>

  <itunes:owner>
    <itunes:name><?php esc_html_e( $channel->owner_name ); ?></itunes:name>
    <itunes:email><?php esc_html_e( $channel->owner_email ); ?></itunes:email>
  </itunes:owner><?php
  if ( $channel_cats ) {
    if ( $channel_cats[1] ) { ?>


  <itunes:category text="<?php echo $channel_cats[1]; ?>">
    <itunes:category text="<?php echo $channel_cats[0]; ?>" />
  </itunes:category>
    <?php
    } else { ?>


  <itunes:category text="<?php echo $channel_cats[0]; ?>" /><?php
    }
  }
  while ( have_posts() ) : the_post(); ?>


  <item>
      <title><?php the_title_rss(); ?></title>
      <link><?php the_permalink_rss(); ?></link>
      <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
      <dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
      <?php the_category_rss( 'rss2' ) ?>

      <guid isPermaLink="false"><?php the_guid(); ?></guid>
      <?php if ( get_option( 'rss_use_excerpt' ) ) : ?>
      <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
      <?php else : ?>
      <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
      <?php $content = get_the_content_feed( 'rss2' ); ?>
      <?php if ( strlen( $content ) > 0 ) : ?>
      <content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
      <?php else : ?>
      <content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
  <?php endif; ?>
  <?php endif; ?>
  <?php rss_enclosure(); ?>
  <?php
  /**
  * Fires at the end of each RSS2 feed item.
  *
  * @since 2.0.0
  */
  do_action( 'rss2_item' );
  ?>
    </item>
<?php endwhile; ?>
</channel>
</rss>
