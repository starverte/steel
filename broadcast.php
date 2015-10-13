<?php
/**
 * Create audio or video series to display on website or publish on podcast feed
 *
 * @package Steel\Broadcast
 * @since 1.3.0
 *
 * @todo Use term meta in WordPress 4.4
 *       (https://make.wordpress.org/core/2015/09/04/taxonomy-term-metadata-proposal)
 */

/**
 * Return arguments for registering steel_broadcast
 */
function steel_broadcast_post_type_args() {
  $labels = array(
    'name'                => _x( 'Media Series', 'Post Type General Name', 'steel' ),
    'singular_name'       => _x( 'Series', 'Post Type Singular Name', 'steel' ),
    'menu_name'           => __( 'Broadcast', 'steel' ),
    'all_items'           => __( 'Media Series', 'steel' ),
    'view_item'           => __( 'View', 'steel' ),
    'add_new_item'        => __( 'New Series', 'steel' ),
    'add_new'             => __( 'New series', 'steel' ),
    'edit_item'           => __( 'Edit Series', 'steel' ),
    'update_item'         => __( 'Update', 'steel' ),
    'search_items'        => __( 'Search media series', 'steel' ),
    'not_found'           => __( 'No media series found', 'steel' ),
    'not_found_in_trash'  => __( 'No media series found in Trash.', 'steel' ),
  );
  $rewrite = array(
    'slug' => 'broadcast',
  );
  $args = array(
    'label'               => __( 'steel_broadcast', 'steel' ),
    'description'         => __( 'A group of related media items', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail' ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-megaphone',
    'can_export'          => false,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'post',
  );
  return $args;
}

/**
 * Return arguments for registering steel_broadcast_channel
 */
function steel_broadcast_channel_taxonomy_args() {
  $labels = array(
    'name'                       => _x( 'Channels', 'Taxonomy General Name', 'steel' ),
    'singular_name'              => _x( 'Channel', 'Taxonomy Singular Name', 'steel' ),
    'menu_name'                  => __( 'Channels', 'steel' ),
    'all_items'                  => __( 'All Channels', 'steel' ),
    'new_item_name'              => __( 'New Channel', 'steel' ),
    'add_new_item'               => __( 'Add New Channel', 'steel' ),
    'edit_item'                  => __( 'Edit Channel', 'steel' ),
    'update_item'                => __( 'Update', 'steel' ),
    'separate_items_with_commas' => __( 'Separate channels with commas', 'steel' ),
    'search_items'               => __( 'Search channels', 'steel' ),
    'add_or_remove_items'        => __( 'Add or remove channels', 'steel' ),
    'choose_from_most_used'      => __( 'Choose from the most used channels', 'steel' ),
  );
  $rewrite = array(
    'slug' => 'channel',
  );
  $args = array(
    'labels'            => $labels,
    'hierarchical'      => false,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'rewrite'           => $rewrite,
  );
  return $args;
}

/**
 * Register custom post type and custom taxonomy
 */
function steel_broadcast_init() {
  register_post_type( 'steel_broadcast', steel_broadcast_post_type_args() );

  register_taxonomy(
    'steel_broadcast_channel',
    'steel_broadcast',
    steel_broadcast_channel_taxonomy_args()
  );

  add_image_size( 'steel-broadcast', 1400, 1400 );
}
add_action( 'init', 'steel_broadcast_init' );

/**
 * Add meta boxes to Edit Series screen
 */
function steel_broadcast_add_meta_boxes() {
  add_meta_box(
    'steel_broadcast_item_list',
    'Series Media',
    'steel_broadcast_item_list',
    'steel_broadcast',
    'side',
    'high'
  );
}
add_action( 'add_meta_boxes', 'steel_broadcast_add_meta_boxes' );

/**
 * Display media series items on Edit Series screen
 */
function steel_broadcast_item_list() {
  global $post;
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
  ?>

  <a href="#" class="button btn-media-add" id="btn_above" title="Add item to series">
    <span class="dashicons dashicons-images-alt"></span> Add item
  </a>
  <div id="series_wrap">
    <div id="series" class="ui-sortable"><?php

    foreach ( $media as $medium ) {
      if ( 0 != $medium->ID && $post->ID != $medium->ID ) {
        $medium_custom = get_post_custom( $medium->ID );
        $medium_metadata = wp_get_attachment_metadata( $medium->ID );

        if ( 25 < strlen( $medium->post_title ) ) {
          $medium_title = substr( $medium->post_title, 0, 25 ) . '...';
        } else {
          $medium_title = $medium->post_title;
        }

        if ( 25 < strlen( basename( $medium->guid ) ) ) {
          $medium_audio_file = substr( basename( $medium->guid ), 0, 15 ) . '...' . substr( basename( $medium->guid ), -7 );
        } else {
          $medium_audio_file = basename( $medium->guid );
        }
        ?>
        <div class="item ui-sortable-handle" id="<?php echo $medium->ID; ?>">
          <header class="item-header">
            <span class="controls-title" id="controls_<?php echo $medium->ID; ?>">
              <?php echo $medium_title; ?>
            </span>
            <a class="item-delete" href="#" onclick="item_delete('<?php echo $medium->ID; ?>' )" title="Delete item">
              <span class="dashicons dashicons-dismiss"></span>
            </a>
          </header>
          <p>
            <textarea class="item-title" name="post_title_<?php echo $medium->ID; ?>" id="post_title_<?php echo $medium->ID; ?>" placeholder="Title" rows="1"><?php echo $medium->post_title; ?></textarea>
            <textarea class="item-content" name="post_content_<?php echo $medium->ID; ?>" id="post_content_<?php echo $medium->ID; ?>" placeholder="Summary" rows="3"><?php echo $medium->post_content; ?></textarea>
          </p>

          <div class="item-h2">
            <p><strong>Details</strong></p>
          </div>
          <span class="dashicons dashicons-calendar"></span>
          <input class="item-date" type="text" size="28" name="date_published_<?php echo $medium->ID; ?>" id="date_published_<?php echo $medium->ID; ?>" value="<?php echo date( 'F j, Y', strtotime( $medium_custom['date_published'][0] ) ); ?>" placeholder="Date published">
          <span class="dashicons dashicons-businessman"></span>
          <input class="item-artist" type="text" size="28" name="artist_<?php echo $medium->ID; ?>" id="artist_<?php echo $medium->ID; ?>" value="<?php echo $medium_metadata['artist']; ?>" placeholder="Artist/Speaker">
          <div class="clearfix"></div>
          <div class="item-h2">
            <p><strong>Files</strong></p>
          </div>
          <div>
            <span class="dashicons dashicons-media-audio"></span>
            <span class="audio-file"><?php echo $medium_audio_file; ?></span>
            <div class="clearfix"></div>
          </div>
        </div>
      <?php
      }
    } ?>

    </div>
    <a href="#" class="btn-media-add" title="Add item to series">
      <div class="item-new">
        <p><span class="glyphicon glyphicon-plus-sign"></span><br>Add item</p>
      </div>
    </a>
  </div>
  <input type="hidden" name="item_list" id="item_list" value="<?php echo $item_list; ?>">
  <div class="clearfix"></div><?php
}

/**
 * Save steel_broadcast
 */
function steel_broadcast_save() {
  global $post;

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && (isset( $post_id )) ) {
    return $post_id;
  }
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX && (isset( $post_id )) ) {
    return $post_id;
  }
  if ( preg_match( '/\edit\.php/', $_SERVER['REQUEST_URI'] ) && ( isset( $post_id ) ) ) {
    return $post_id;
  }

  $post_custom = get_post_custom();
  $media = array();
  $start_date = 0;
  $end_date = 0;

  if ( isset( $_POST['item_list'] ) ) {
    update_post_meta( $post->ID, 'item_list', $_POST['item_list'] );
    if ( ! empty( $_POST['item_list'] ) ) {
      $items = explode( ',', $_POST['item_list'] );
    }
  } else {
    if ( ! empty( $post_custom['item_list'][0] ) ) {
      $items = explode( ',', $post_custom['item_list'][0] );
    }
  }

  if ( ! empty( $items ) ) {
    foreach ( $items as $item_id ) {
      array_push( $media, get_post( $item_id ) );
    }

    foreach ( $media as $medium ) {
      if ( ! empty( $medium ) && 0 != $medium->ID && $post->ID != $medium->ID ) {
        $medium_array = array(
          'ID' => $medium->ID,
        );
        $medium_metadata = wp_get_attachment_metadata( $medium->ID );

        if ( isset( $_POST[ 'post_title_' . $medium->ID ] ) ) {
          $medium_array['post_title'] = $_POST[ 'post_title_' . $medium->ID ];
        }

        if ( isset( $_POST[ 'post_content_' . $medium->ID ] ) ) {
          $medium_array['post_content'] = $_POST[ 'post_content_' . $medium->ID ];
        }

        if ( isset( $_POST[ 'date_published_' . $medium->ID ] ) ) {
          update_post_meta( $medium->ID, 'date_published', $_POST[ 'date_published_' . $medium->ID ] );
          $start_date = min( strtotime( $_POST[ 'date_published_' . $medium->ID ] ), $start_date );
          $end_date = max( strtotime( $_POST[ 'date_published_' . $medium->ID ] ), $end_date );
        }

        if ( isset( $_POST[ 'artist_' . $medium->ID ] ) ) {
          $medium_metadata['artist'] = $_POST[ 'artist_' . $medium->ID ];
        }

        wp_update_post( $medium_array );
        wp_update_attachment_metadata( $medium->ID, $medium_metadata );
      }
    }

    if ( 0 != $start_date ) {
      update_post_meta( $post->ID, 'start_date', $start_date );
    }

    if ( 0 != $end_date ) {
      update_post_meta( $post->ID, 'end_date', $start_date );
    }
  }
}
add_action( 'save_post', 'steel_broadcast_save' );

/**
 * Display custom form fields on Channels/New Channel screen
 */
function steel_broadcast_add_form_fields() {
  ?>
  <div class="form-field">
    <label for="channel_meta[cover_photo_id]"><?php _e( 'Cover Photo', 'steel' ); ?></label>
    <input type="hidden" name="channel_meta[cover_photo_id]" id="channel_cover_photo_id" value="" />
    <div id="channel_cover_photo"></div>
    <a href="#" class="button btn-channel-cover" title="<?php _e( 'Set Cover Photo', 'steel' ); ?>">
      <span class="dashicons dashicons-format-image"></span>
      <?php _e( 'Set Cover Photo', 'steel' ); ?>
    </a>
    <p class="description">
      <?php _e( 'iTunes requires square JPG or PNG images that are at least 1400x1400 pixels', 'steel' ); ?>
    </p>
  </div><?php
}
add_action( 'steel_broadcast_channel_add_form_fields', 'steel_broadcast_add_form_fields', 10, 2 );

/**
 * Display custom form fields on Edit Channel screen
 *
 * @param object $term Current taxonomy term object.
 */
function steel_broadcast_edit_form_fields( $term ) {
  $the_term = $term->term_id;
  $term_meta = get_option( 'steel_broadcast_channel_' . $the_term );
  $itunes_cats = steel_broadcast_itunes_cats(); ?>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[cover_photo_id]"><?php _e( 'Cover Photo', 'steel' ); ?></label>
    </th>
    <td>
      <input type="hidden" name="channel_meta[cover_photo_id]" id="channel_cover_photo_id" value="<?php esc_attr_e( $term_meta['cover_photo_id'] ); ?>" />
      <div id="channel_cover_photo">
        <?php
          if ( ! empty( $term_meta['cover_photo_id'] ) ) { ?>
            <img class="cover-photo" src="<?php echo wp_get_attachment_url( $term_meta['cover_photo_id'] ); ?>" width="140" height="140" /><?php
          }
        ?>
      </div>
      <a href="#" class="button btn-channel-cover" title="<?php _e( 'Set Cover Photo', 'steel' ); ?>">
        <span class="dashicons dashicons-format-image"></span>
        <?php _e( 'Set Cover Photo', 'steel' ); ?>
      </a>
      <p class="description">
        <?php _e( 'iTunes requires square JPG or PNG images that are at least 1400x1400 pixels', 'steel' ); ?>
      </p>
    </td>
  </tr>
  <tr>
    <th scope="row" valign="top"><h3><?php _e( 'Podcast Information', 'steel' ); ?></h3></th>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[link]"><?php _e( 'Link', 'steel' ); ?></label>
    </th>
    <td>
      <input type="text" name="channel_meta[link]" value="<?php echo $term_meta['link']; ?>" />
      <p class="description"><?php _e( 'The podcast feed URL.', 'steel' ); ?></p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[copyright]"><?php _e( 'Copyright Notice', 'steel' ); ?></label>
    </th>
    <td>
      <input type="text" name="channel_meta[copyright]" value="<?php echo $term_meta['copyright']; ?>" />
      <p class="description">
        <?php _e( 'i.e. "2015 Star Verte LLC. All Rights Reserved."', 'steel' ); ?>
      </p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[author]"><?php _e( 'Author', 'steel' ); ?></label>
    </th>
    <td>
      <input type="text" name="channel_meta[author]" value="<?php echo $term_meta['author']; ?>" />
      <p class="description">
        <?php _e( 'The individual or corporate author of the podcast.', 'steel' ); ?>
      </p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[category]"><?php _e( 'Category', 'steel' ); ?></label>
    </th>
    <td>
      <select name="channel_meta[category]">
        <?php foreach ( $itunes_cats as $key => $value ) : ?>
        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $term_meta['category'], $key ); ?>>
          <?php echo esc_attr( $value ); ?>
        </option>
        <?php endforeach; ?>
      </select>
    </td>
  </tr>
  <tr>
    <th scope="row" valign="top"><h4><?php _e( 'Contact Information', 'steel' ); ?></h4></th>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[owner_name]"><?php _e( 'Name', 'steel' ); ?></label>
    </th>
    <td>
      <input type="text" name="channel_meta[owner_name]" value="<?php echo $term_meta['owner_name']; ?>" />
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[owner_email]"><?php _e( 'Email', 'steel' ); ?></label>
    </th>
    <td>
      <input type="email" name="channel_meta[owner_email]" value="<?php echo $term_meta['owner_email']; ?>" />
    </td>
  </tr><?php
}
add_action( 'steel_broadcast_channel_edit_form_fields', 'steel_broadcast_edit_form_fields', 10, 2 );

/**
 * Save custom form fields on New Channel and Edit Channel screens
 *
 * @param int $term_id Term ID.
 */
function steel_broadcast_channel_save( $term_id ) {
  $the_term = $term_id;
  if ( isset( $_POST['channel_meta'] ) ) {
    $term_meta = get_option( 'steel_broadcast_channel_' . $the_term );
    $term_keys = array_keys( $_POST['channel_meta'] );
    foreach ( $term_keys as $key ) {
      if ( isset( $_POST['channel_meta'][ $key ] ) ) {
        $term_meta[ $key ] = $_POST['channel_meta'][ $key ];
      }
    }
    update_option( 'steel_broadcast_channel_' . $the_term, $term_meta );
  }
}
add_action( 'edited_steel_broadcast_channel', 'steel_broadcast_channel_save', 10, 2 );
add_action( 'create_steel_broadcast_channel', 'steel_broadcast_channel_save', 10, 2 );

/**
 * Retrieve iTunes categories
 */
function steel_broadcast_itunes_cats() {
  return array(
    'arts' => 'Arts',
    'art.design' => '— Design',
    'art.fashion' => '— Fashion & Beauty',
    'art.food' => '— Food',
    'art.lit' => '— Literature',
    'art.performing' => '— Performing Arts',
    'art.visual' => '— Visual Arts',
    'business' => 'Business',
    'bus.news' => '— Business News',
    'bus.career' => '— Careers',
    'bus.invest' => '— Investing',
    'bus.management' => '— Management & Marketing',
    'bus.shop' => '— Shopping',
    'comedy' => 'Comedy',
    'education' => 'Education',
    'edu.tech' => '— Education Technology',
    'edu.higher' => '— Higher Education',
    'edu.k12' => '— K-12',
    'edu.lang' => '— Language Courses',
    'edu.training' => '— Training',
    'games' => 'Games & Hobbies',
    'gam.auto' => '— Automotive',
    'gam.aviation' => '— Aviation',
    'gam.hobbies' => '— Hobbies',
    'gam.video' => '— Video Games',
    'gam.other' => '— Other Games',
    'government' => 'Government & Organizations',
    'gov.local' => '— Local',
    'gov.national' => '— National',
    'gov.nonprofit' => '— Non-Profit',
    'gov.regional' => '— Regional',
    'health' => 'Health',
    'health.alt' => '— Alternative Health',
    'health.fitness' => '— Fitness & Nutrition',
    'health.self' => '— Self-Help',
    'health.sex' => '— Sexuality',
    'kids' => 'Kids & Family',
    'music' => 'Music',
    'news' => 'News & Politics',
    'religion' => 'Religion & Spirituality',
    'rel.buddhism' => '— Buddhism',
    'rel.christianity' => '— Christianity',
    'rel.hinduism' => '— Hinduism',
    'rel.islam' => '— Islam',
    'rel.judaism' => '— Judaism',
    'rel.spirituality' => '— Spirituality',
    'rel.other' => '— Other',
    'science' => 'Science & Medicine',
    'sci.medicine' => '— Medicine',
    'sci.natural' => '— Natural Sciences',
    'sci.social' => '— Social Sciences',
    'society' => 'Society & Culture',
    'soc.hist' => '— History',
    'soc.journal' => '— Personal Journals',
    'soc.philosophy' => '— Philosophy',
    'soc.travel' => '— Places & Travel',
    'sports' => 'Sports & Recreation',
    'sports.amateur' => '— Amateur',
    'sports.college' => '— College & High School',
    'sports.outdoor' => '— Outdoor',
    'sports.pro' => '— Professional',
    'technology' => 'Technology',
    'tech.gadgets' => '— Gadgets',
    'tech.news' => '— Tech News',
    'tech.podcast' => '— Podcasting',
    'tech.software' => '— Software How-To',
    'tv' => 'TV & Film',
  );
}

/**
 * Retrieve list of media items for a series.
 *
 * @see get_posts()
 *
 * @param int $post_id The media series post ID.
 * @return array List of posts.
 */
function steel_broadcast_media( $post_id = 0 ) {
  if ( 0 == $post_id ) {
    $post_id = get_the_ID();
  } else {
    $post_id = absint( $post_id );
  }

  $post_custom = get_post_custom( $post_id );

  if ( ! empty( $post_custom['item_list'][0] ) && ',' != $post_custom['item_list'][0] ) {
    $attachments = array();
    $media = array();

    $items = explode( ',', $post_custom['item_list'][0] );

    foreach ( $items as $item_id ) {
      array_push( $attachments, get_post( $item_id ) );
    }

    foreach ( $attachments as $attachment ) {
      if ( 0 != $attachment->ID && $post->ID != $attachment->ID ) {
        $medium = new stdClass();
        $item_custom = get_post_custom( $attachment-> ID );
        $item_meta = wp_get_attachment_metadata( $attachment-> ID );
        $item_vars = get_object_vars( $attachment );

        foreach ( $item_custom as $key => $value ) {
          $medium->$key = $value[0];
        }

        foreach ( $item_meta as $key => $value ) {
          $medium->$key = $value;
        }

        foreach ( $item_vars as $key => $value ) {
          $medium->$key = $value;
        }

        array_push( $media, $medium );
      }
    }

    return $media;
  } else {
    return false;
  }
}

/**
 * Load template for Broadcast Channel feeds
 */
function steel_broadcast_feed() {
  load_template( dirname( __FILE__ ) . '/inc/broadcast-feed.php' );
}
add_action( 'do_feed_broadcast', 'steel_broadcast_feed', 10, 1 );

/**
 * Add rewrite rules for Broadcast Channel feeds
 *
 * Ensures example.com/feed/broadcast/podcast outputs feed for channel with slug 'podcast'
 *
 * @param object $wp_rewrite Current WP_Rewrite instance, passed by reference.
 */
function steel_broadcast_channel_feed_rewrite( $wp_rewrite ) {
  $feed_rules = array(
    'feed/broadcast/(.+)' => 'index.php?feed=broadcast&steel_broadcast_channel=' . $wp_rewrite->preg_index( 1 ),
  );
  $wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
add_filter( 'generate_rewrite_rules', 'steel_broadcast_channel_feed_rewrite' );

/**
 * Retrieve data for broadcast channel.
 *
 * @param int $term_id The term ID for the broadcast channel.
 *
 * @return object The broadcast channel object.
 */
function steel_broadcast_channel_data( $term_id ) {
  $channel = new stdClass();

  $term_data = get_term_by( 'id', $term_id, 'steel_broadcast_channel', 'ARRAY_A' );
  $term_meta = get_option( 'steel_broadcast_channel_' . $term_id );

  foreach ( $term_data as $key => $value ) {
    $channel->$key = $value;
  }

  foreach ( $term_meta as $key => $value ) {
    $channel->$key = $value;
  }

  return $channel;
}

/**
 * Retrieve data for broadcast channel.
 *
 * @param object $channel The broadcast channel object.
 *
 * @return array|bool The broadcast channel categories or false if none.
 */
function steel_broadcast_channel_itunes_cat( $channel ) {
  if ( ! empty( $channel->category ) ) {
    $itunes_cat = array();

    if ( 'arts' == $channel->category ) {
      $itunes_cat = array(
        'Arts',
        false,
      );
    } elseif ( 'art.design' == $channel->category ) {
      $itunes_cat = array(
        'Design',
        'Arts',
      );
    } elseif ( 'art.fashion' == $channel->category ) {
      $itunes_cat = array(
        'Fashion &amp; Beauty',
        'Arts',
      );
    } elseif ( 'art.food' == $channel->category ) {
      $itunes_cat = array(
        'Food',
        'Arts',
      );
    } elseif ( 'art.lit' == $channel->category ) {
      $itunes_cat = array(
        'Literature',
        'Arts',
      );
    } elseif ( 'art.performing' == $channel->category ) {
      $itunes_cat = array(
        'Performing Arts',
        'Arts',
      );
    } elseif ( 'art.visual' == $channel->category ) {
      $itunes_cat = array(
        'Visual Arts',
        'Arts',
      );
    } elseif ( 'business' == $channel->category ) {
      $itunes_cat = array(
        'Business',
        false,
      );
    } elseif ( 'bus.news' == $channel->category ) {
      $itunes_cat = array(
        'Business News',
        'Business',
      );
    } elseif ( 'bus.career' == $channel->category ) {
      $itunes_cat = array(
        'Careers',
        'Business',
      );
    } elseif ( 'bus.invest' == $channel->category ) {
      $itunes_cat = array(
        'Investing',
        'Business',
      );
    } elseif ( 'bus.management' == $channel->category ) {
      $itunes_cat = array(
        'Management &amp; Marketing',
        'Business',
      );
    } elseif ( 'bus.shop' == $channel->category ) {
      $itunes_cat = array(
        'Shopping',
        'Business',
      );
    } elseif ( 'comedy' == $channel->category ) {
      $itunes_cat = array(
        'Comedy',
        false,
      );
    } elseif ( 'education' == $channel->category ) {
      $itunes_cat = array(
        'Education',
        false,
      );
    } elseif ( 'edu.tech' == $channel->category ) {
      $itunes_cat = array(
        'Education Technology',
        'Education',
      );
    } elseif ( 'edu.higher' == $channel->category ) {
      $itunes_cat = array(
        'Higher Education',
        'Education',
      );
    } elseif ( 'edu.k12' == $channel->category ) {
      $itunes_cat = array(
        'K-12',
        'Education',
      );
    } elseif ( 'edu.lang' == $channel->category ) {
      $itunes_cat = array(
        'Language Courses',
        'Education',
      );
    } elseif ( 'edu.training' == $channel->category ) {
      $itunes_cat = array(
        'Training',
        'Education',
      );
    } elseif ( 'games' == $channel->category ) {
      $itunes_cat = array(
        'Games &amp; Hobbies',
        false,
      );
    } elseif ( 'gam.auto' == $channel->category ) {
      $itunes_cat = array(
        'Automotive',
        'Games &amp; Hobbies',
      );
    } elseif ( 'gam.aviation' == $channel->category ) {
      $itunes_cat = array(
        'Aviation',
        'Games &amp; Hobbies',
      );
    } elseif ( 'gam.hobbies' == $channel->category ) {
      $itunes_cat = array(
        'Hobbies',
        'Games &amp; Hobbies',
      );
    } elseif ( 'gam.video' == $channel->category ) {
      $itunes_cat = array(
        'Video Games',
        'Games &amp; Hobbies',
      );
    } elseif ( 'gam.other' == $channel->category ) {
      $itunes_cat = array(
        'Other Games',
        'Games &amp; Hobbies',
      );
    } elseif ( 'government' == $channel->category ) {
      $itunes_cat = array(
        'Government & Organizations',
        false,
      );
    } elseif ( 'gov.local' == $channel->category ) {
      $itunes_cat = array(
        'Local',
        'Government &amp; Organizations',
      );
    } elseif ( 'gov.national' == $channel->category ) {
      $itunes_cat = array(
        'National',
        'Government &amp; Organizations',
      );
    } elseif ( 'gov.nonprofit' == $channel->category ) {
      $itunes_cat = array(
        'Non-Profit',
        'Government &amp; Organizations',
      );
    } elseif ( 'gov.regional' == $channel->category ) {
      $itunes_cat = array(
        'Regional',
        'Government &amp; Organizations',
      );
    } elseif ( 'health' == $channel->category ) {
      $itunes_cat = array(
        'Health',
        false,
      );
    } elseif ( 'health.alt' == $channel->category ) {
      $itunes_cat = array(
        'Alternative Health',
        'Health',
      );
    } elseif ( 'health.fitness' == $channel->category ) {
      $itunes_cat = array(
        'Fitness &amp; Nutrition',
        'Health',
      );
    } elseif ( 'health.self' == $channel->category ) {
      $itunes_cat = array(
        'Self-Help',
        'Health',
      );
    } elseif ( 'health.sex' == $channel->category ) {
      $itunes_cat = array(
        'Sexuality',
        'Health',
      );
    } elseif ( 'kids' == $channel->category ) {
      $itunes_cat = array(
        'Kids &amp; Family',
        false,
      );
    } elseif ( 'music' == $channel->category ) {
      $itunes_cat = array(
        'Music',
        false,
      );
    } elseif ( 'news' == $channel->category ) {
      $itunes_cat = array(
        'News &amp; Politics',
        false,
      );
    } elseif ( 'religion' == $channel->category ) {
      $itunes_cat = array(
        'Religion &amp; Spirituality',
        false,
      );
    } elseif ( 'rel.buddhism' == $channel->category ) {
      $itunes_cat = array(
        'Buddhism',
        'Religion &amp; Spirituality',
      );
    } elseif ( 'rel.christianity' == $channel->category ) {
      $itunes_cat = array(
        'Christianity',
        'Religion &amp; Spirituality',
      );
    } elseif ( 'rel.hinduism' == $channel->category ) {
      $itunes_cat = array(
        'Hinduism',
        'Religion &amp; Spirituality',
      );
    } elseif ( 'rel.islam' == $channel->category ) {
      $itunes_cat = array(
        'Islam',
        'Religion &amp; Spirituality',
      );
    } elseif ( 'rel.judaism' == $channel->category ) {
      $itunes_cat = array(
        'Judaism',
        'Religion &amp; Spirituality',
      );
    } elseif ( 'rel.spirituality' == $channel->category ) {
      $itunes_cat = array(
        'Spirituality',
        'Religion &amp; Spirituality',
      );
    } elseif ( 'rel.other' == $channel->category ) {
      $itunes_cat = array(
        'Other',
        'Religion &amp; Spirituality',
      );
    } elseif ( 'science' == $channel->category ) {
      $itunes_cat = array(
        'Science &amp; Medicine',
        false,
      );
    } elseif ( 'sci.medicine' == $channel->category ) {
      $itunes_cat = array(
        'Medicine',
        'Science &amp; Medicine',
      );
    } elseif ( 'sci.natural' == $channel->category ) {
      $itunes_cat = array(
        'Natural Sciences',
        'Science &amp; Medicine',
      );
    } elseif ( 'sci.social' == $channel->category ) {
      $itunes_cat = array(
        'Social Sciences',
        'Science &amp; Medicine',
      );
    } elseif ( 'society' == $channel->category ) {
      $itunes_cat = array(
        'Society &amp; Culture',
        false,
      );
    } elseif ( 'soc.hist' == $channel->category ) {
      $itunes_cat = array(
        'History',
        'Society &amp; Culture',
      );
    } elseif ( 'soc.journal' == $channel->category ) {
      $itunes_cat = array(
        'Personal Journals',
        'Society &amp; Culture',
      );
    } elseif ( 'soc.philosophy' == $channel->category ) {
      $itunes_cat = array(
        'Philosophy',
        'Society &amp; Culture',
      );
    } elseif ( 'soc.travel' == $channel->category ) {
      $itunes_cat = array(
        'Places &amp; Travel',
        'Society &amp; Culture',
      );
    } elseif ( 'sports' == $channel->category ) {
      $itunes_cat = array(
        'Sports &amp; Recreation',
        false,
      );
    } elseif ( 'sports.amateur' == $channel->category ) {
      $itunes_cat = array(
        'Amateur',
        'Sports &amp; Recreation',
      );
    } elseif ( 'sports.college' == $channel->category ) {
      $itunes_cat = array(
        'College &amp; High School',
        'Sports &amp; Recreation',
      );
    } elseif ( 'sports.outdoor' == $channel->category ) {
      $itunes_cat = array(
        'Outdoor',
        'Sports &amp; Recreation',
      );
    } elseif ( 'sports.pro' == $channel->category ) {
      $itunes_cat = array(
        'Professional',
        'Sports &amp; Recreation',
      );
    } elseif ( 'technology' == $channel->category ) {
      $itunes_cat = array(
        'Technology',
        false,
      );
    } elseif ( 'tech.gadgets' == $channel->category ) {
      $itunes_cat = array(
        'Gadgets',
        'Technology',
      );
    } elseif ( 'tech.news' == $channel->category ) {
      $itunes_cat = array(
        'Tech News',
        'Technology',
      );
    } elseif ( 'tech.podcast' == $channel->category ) {
      $itunes_cat = array(
        'Podcasting',
        'Technology',
      );
    } elseif ( 'tech.software' == $channel->category ) {
      $itunes_cat = array(
        'Software How-To',
        'Technology',
      );
    } elseif ( 'tv' == $channel->category ) {
      $itunes_cat = array(
        'TV &amp; Film',
        false,
      );
    }

    return $itunes_cat;
  } else {
    return false;
  }
}
