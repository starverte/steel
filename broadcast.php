<?php
/**
 * Create media series to broadcast on your website or podcast
 *
 * @package Steel\Broadcast
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
  $args = array(
    'label'               => __( 'steel_broadcast', 'steel' ),
    'description'         => __( 'A group of related media items', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail' ),
    'hierarchical'        => false,
    'public'              => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-megaphone',
    'can_export'          => false,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'rewrite'             => true,
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
    'slug' => 'channels',
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
  register_taxonomy( 'steel_broadcast_channel', 'steel_broadcast', steel_broadcast_channel_taxonomy_args() );
}
add_action( 'init', 'steel_broadcast_init' );

/**
 * Add meta boxes to Edit Series screen
 */
function steel_broadcast_add_meta_boxes() {
  add_meta_box( 'steel_broadcast_item_list', 'Series Media', 'steel_broadcast_item_list', 'steel_broadcast', 'side', 'high' );
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
    $items = explode( ',', $post_custom['item_list'][0] );
    foreach ( $items as $item_id ) {
      array_push( $media, get_post( $item_id ) );
    }
  } ?>

  <a href="#" class="button btn-media-add" id="btn_above" title="Add item to series">
    <span class="dashicons dashicons-images-alt"></span> Add item
  </a>
  <div id="series_wrap">
    <div id="series" class="ui-sortable"><?php

    foreach ( $media as $medium ) {
      if ( 0 != $medium->ID && $post->ID != $medium->ID ) {
        $medium_custom = get_post_custom( $medium->ID );
        $medium_metadata = wp_get_attachment_metadata( $medium->ID ); ?>
        <div class="item ui-sortable-handle" id="<?php echo $medium->ID; ?>">
          <header class="item-header">
            <span id="controls_<?php echo $medium->ID; ?>"><?php echo $medium->post_title; ?></span><a class="item-delete" href="#" onclick="item_delete('<?php echo $medium->ID; ?>' )" title="Delete item"><span class="dashicons dashicons-dismiss"></span></a>
          </header>
          <p>
            <input type="text" size="32" class="item-title" name="post_title_<?php echo $medium->ID; ?>" id="post_title_<?php echo $medium->ID; ?>" value="<?php echo $medium->post_title; ?>" placeholder="Title">
            <textarea class="item-content" cols="32" name="post_content_<?php echo $medium->ID; ?>" id="post_content_<?php echo $medium->ID; ?>" placeholder="Summary"><?php echo $medium->post_content; ?></textarea>
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
            <span class="audio-file"><?php echo basename( $medium->guid ); ?></span>
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
  <input type="hidden" name="item_list" id="item_list" value="<?php echo $post_custom['item_list'][0]; ?>">
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

  if ( isset( $_POST['item_list'] ) ) {
    update_post_meta( $post->ID, 'item_list', $_POST['item_list'] );
    $items = explode( ',', $_POST['item_list'] );
  } else {
    $items = explode( ',', $post_custom['item_list'][0] );
  }

  $media = array();

  foreach ( $items as $item_id ) {
    array_push( $media, get_post( $item_id ) );
  }

  foreach ( $media as $medium ) {
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
    }

    if ( isset( $_POST[ 'artist_' . $medium->ID ] ) ) {
      $medium_metadata['artist'] = $_POST[ 'artist_' . $medium->ID ];
    }

    wp_update_post( $medium_array );
    update_post_meta( $post->ID, 'medium_' . $medium->ID, $medium_array );
    wp_update_attachment_metadata( $medium->ID, $medium_metadata );
  }
}
add_action( 'save_post', 'steel_broadcast_save' );

/**
 * Display custom form fields on Channels/New Channel screen
 */
function steel_broadcast_add_form_fields() {
  ?>
  <div class="form-field">
    <label for="channel_meta[type]"><?php _e( 'Type', 'steel' ); ?></label>
    <select name="channel_meta[type]">
      <option value="html"><?php _e( 'Display', 'steel' ); ?></option>
      <option value="rss"><?php _e( 'Podcast', 'steel' ); ?></option>
    </select>
    <p class="description">
      <?php _e( 'Display outputs HTML, Podcast outputs RSS for iTunes', 'steel' ); ?>
    </p>
  </div>
  <div class="form-field">
    <label for="channel_meta[cover_photo_id]"><?php _e( 'Cover Photo', 'steel' ); ?></label>
    <input type="hidden" name="channel_meta[cover_photo_id]" id="channel_cover_photo_id" value="" />
    <div id="channel_cover_photo"></div>
    <a href="#" class="button btn-channel-cover" title="<?php _e( 'Set Cover Photo', 'steel' ); ?>">
      <span class="dashicons dashicons-format-image"></span>
      <?php _e( 'Set Cover Photo', 'steel' ); ?>
    </a>
    <p class="description">
      <?php
        _e(
          'iTunes requires square JPG or PNG images that are at least 1400x1400 pixels',
          'steel'
        );
      ?>
    </p>
  </div><?php
}
add_action( 'steel_broadcast_channel_add_form_fields', 'steel_broadcast_add_form_fields', 10, 2 );

/**
 * Display custom form fields on Edit Channel screen
 */
function steel_broadcast_edit_form_fields( $term ) {
  $the_term = $term->term_id;
  $term_meta = get_option( 'steel_broadcast_channel_' . $the_term );
  $itunes_cats = steel_broadcast_itunes_cats(); ?>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="channel_meta[type]"><?php _e( 'Type', 'steel' ); ?></label>
    </th>
    <td>
      <select name="channel_meta[type]">
        <option value="html" <?php selected( $term_meta['type'], 'html' ); ?>>
          <?php _e( 'Display', 'steel' ); ?>
        </option>
        <option value="rss" <?php selected( $term_meta['type'], 'rss' ); ?>>
          <?php _e( 'Podcast', 'steel' ); ?>
        </option>
      </select>
      <p class="description">
        <?php _e( 'Display outputs HTML, Podcast outputs RSS for iTunes', 'steel' ); ?>
      </p>
    </td>
  </tr>
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
 */
function steel_broadcast_channel_save( $term_id ) {
  $the_term = $term_id;
  if ( isset( $_POST['channel_meta'] ) ) {
    $term_meta = get_option( 'steel_broadcast_channel_' . $the_term );
    $term_keys = array_keys( $_POST['channel_meta'] );
    foreach ( $term_keys as $key ) {
      if ( isset ( $_POST['channel_meta'][ $key ] ) ) {
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
