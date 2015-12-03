<?php
/**
 * The iTunes RSS Feed Template for displaying Podcast feed.
 *
 * @package Steel\Broadcast
 * @since 1.3.0
 */

header( 'Content-Type: ' . feed_content_type( 'rss2' ) . '; charset=' . get_option( 'blog_charset' ), true );
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option( 'blog_charset' ).'"?'.'>';

$term = get_queried_object();
$channel = steel_broadcast_channel_data( $term->term_id );
$channel_cats = steel_broadcast_channel_itunes_cat( $channel );
$channel_cover = wp_get_attachment_image_src( $channel->cover_photo_id, 'steel-broadcast' );
?>

<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">

<channel>
  <title><?php esc_html_e( $channel->name ); ?></title>
  <link><![CDATA[<?php echo $channel->link; ?>]]></link>
  <language><?php bloginfo_rss( 'language' ); ?></language>
  <copyright>&#x2117; <?php echo $channel->copyright; ?></copyright>
  <itunes:author><?php esc_html_e( $channel->author ); ?></itunes:author>
  <itunes:summary><![CDATA[<?php echo $channel->description; ?>]]></itunes:summary>
  <description><![CDATA[<?php echo $channel->description; ?>]]></description>

  <itunes:owner>
    <itunes:name><?php esc_html_e( $channel->owner_name ); ?></itunes:name>
    <itunes:email><?php esc_html_e( $channel->owner_email ); ?></itunes:email>
  </itunes:owner>

  <itunes:image href="<?php echo $channel_cover[0]; ?>" /><?php
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
  while ( have_posts() ) : the_post();

  $media = steel_broadcast_media();
  $img_id = get_post_thumbnail_id();
  $img = wp_get_attachment_image_src( $img_id, 'steel-broadcast' );

  foreach ( $media as $medium ) {
  ?>

    <item>
      <title><?php echo $medium->post_title; ?></title>
      <itunes:author><?php echo $medium->artist; ?></itunes:author>
      <itunes:subtitle><?php the_title_rss(); ?></itunes:subtitle>
<?php if ( strlen( $medium->post_content ) > 0 ) : ?>
      <itunes:summary><![CDATA[<?php echo $medium->post_content; ?>]]></itunes:summary>
<?php endif; ?>
<?php if ( has_post_thumbnail() ) : ?>
      <itunes:image href="<?php echo $img[0]; ?>" />
<?php endif; ?>
      <enclosure url="<?php echo $medium->guid; ?>" length="<?php echo $medium->filesize; ?>" type="<?php echo $medium->mime_type; ?>" />
      <guid><?php echo $medium->guid; ?></guid>
      <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', $medium->date_published, false ); ?></pubDate>
      <itunes:duration><?php echo $medium->length_formatted; ?></itunes:duration>
    </item>
  <?php
  }
    endwhile; ?>
</channel>
</rss>
