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

function steel_broadcast_item_list() {
  $series_media     = steel_broadcast_meta( 'media' );
  $series_order     = steel_broadcast_meta( 'order' );
  $series_media_url = steel_broadcast_meta( 'media_url' );

  $series = explode( ',', $series_order );

  $output = '';
  $output .= '<a href="#" class="button add_episode_media" id="btn_above" title="Add episode to podcast"><span class="dashicons dashicons-playlist-audio"></span> Add Episode</a>';
  $output .= '<div id="series_wrap"><div id="series">';
  foreach ( $series as $episode ) {
    if ( ! empty( $episode ) ) {
      $output .= '<div class="episode" id="';
      $output .= $episode;
      $output .= '">';
      $output .= '<div class="episode-controls"><span id="controls_'.$episode.'">'.steel_episode_meta( $episode.'_title' ).'</span><a class="del-episode" href="#" onclick="deleteEpisode(\''.$episode.'\')" title="Delete episode"><span class="dashicons dashicons-dismiss" style="float:right"></span></a></div>';
      $output .= '<p><input type="text" size="32" class="episode-title" name="episode_'.$episode.'_title" id="episode_'.$episode.'_title" value="'.steel_episode_meta( $episode.'_title' ).'" placeholder="Title" /><br>';
      $output .= '<textarea cols="28" rows="3" name="episode_'.$episode.'_summary" id="episode_'.$episode.'_summary" placeholder="Summary">'.steel_episode_meta( $episode.'_summary' ).'</textarea></p>';
      $output .= '<span class="dashicons dashicons-calendar" style="float:left;padding:5px;"></span><input type="text" size="22" name="episode_'.$episode.'_date" id="episode_'.$episode.'_date" value="'.steel_episode_meta( $episode.'_date' ).'" placeholder="mm/dd/yyyy" style="margin:0;">';
      $output .= '<span class="dashicons dashicons-businessman" style="float:left;padding:5px;"></span><input type="text" size="22" name="episode_'.$episode.'_author" id="episode_'.$episode.'_author" value="'.steel_episode_meta( $episode.'_author' ).'" placeholder="Author" style="margin:0;">';
      $output .= '<span class="dashicons dashicons-clock" style="float:left;padding:5px;"></span><input type="text" size="22" name="episode_'.$episode.'_duration" id="episode_'.$episode.'_duration" value="'.steel_episode_meta( $episode.'_duration' ).'" placeholder="HH:MM:SS" style="margin:0;">';
      $output .= '</div>';
    }
  }
    $output .= '</div><a href="#" class="add_episode_media add_new_episode" title="Add episode to podcast"><div class="episode-new"><p><span class="glyphicon glyphicon-plus-sign"></span><br>Add Episode</p></div></a>';
  $output .= '</div>';

  echo $output; ?>

  <input type="hidden" name="series_order" id="series_order" value="<?php echo $series_order; ?>">
  <div style="float:none; clear:both;"></div><?php
}

/**
 * Save data from meta boxes
 */
function steel_broadcast_save() {
  global $post;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && (isset( $post_id )) ) { return $post_id; }
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX && (isset( $post_id )) ) { return $post_id; }
  if ( preg_match( '/\edit\.php/', $_SERVER['REQUEST_URI'] ) && (isset( $post_id )) ) { return $post_id; }
  if ( isset( $_POST['series_order'] ) ) {
    update_post_meta( $post->ID, 'series_order'   , $_POST['series_order'] );
    $series = explode( ',', get_post_meta( $post->ID, 'series_order', true ) );
    foreach ( $series as $episode ) {
      if ( isset( $_POST[ 'episode_' . $episode . '_title'   ] ) ) { update_post_meta( $post->ID, 'episode_' . $episode . '_title'   , $_POST[ 'episode_' . $episode . '_title'   ] ); }
      if ( isset( $_POST[ 'episode_' . $episode . '_summary' ] ) ) { update_post_meta( $post->ID, 'episode_' . $episode . '_summary' , $_POST[ 'episode_' . $episode . '_summary' ] ); }
      if ( isset( $_POST[ 'episode_' . $episode . '_date'    ] ) ) { update_post_meta( $post->ID, 'episode_' . $episode . '_date'    , $_POST[ 'episode_' . $episode . '_date'    ] ); }
      if ( isset( $_POST[ 'episode_' . $episode . '_author'  ] ) ) { update_post_meta( $post->ID, 'episode_' . $episode . '_author'  , $_POST[ 'episode_' . $episode . '_author'  ] ); }
      if ( isset( $_POST[ 'episode_' . $episode . '_duration' ] ) ) { update_post_meta( $post->ID, 'episode_' . $episode . '_duration', $_POST[ 'episode_' . $episode . '_duration' ] ); }
    }
  }

  if ( isset( $_POST['series_author'] ) ) { update_post_meta( $post->ID, 'series_author'   , $_POST['series_author'] ); }
  if ( isset( $_POST['series_media'] ) ) { update_post_meta( $post->ID, 'series_media'    , $_POST['series_media'] ); }
  if ( isset( $_POST['series_media_url'] ) ) { update_post_meta( $post->ID, 'series_media_url', $_POST['series_media_url'] ); }
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
    <p class="description"><?php _e( 'Display outputs HTML, Podcast outputs RSS for iTunes', 'steel' ) ?></p>
  </div>
  <div class="form-field">
    <label for="channel_meta[cover_photo_id]"><?php _e( 'Cover Photo', 'steel' ); ?></label>
    <input type="hidden" name="channel_meta[cover_photo_id]" id="channel_cover_photo_id" value="" />
    <div id="channel_cover_photo"></div>
    <a href="#" class="button btn-channel-cover" title="<?php _e( 'Set Cover Photo', 'steel' ); ?>">
      <span class="dashicons dashicons-format-image"></span> <?php _e( 'Set Cover Photo', 'steel' ); ?>
    </a>
    <p class="description"><?php _e( 'iTunes requires square JPG or PNG images that are at least 1400x1400 pixels', 'steel' ); ?></p>
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
    <th scope="row" valign="top"><label for="channel_meta[type]"><?php _e( 'Type', 'steel' ); ?></label></th>
    <td>
      <select name="channel_meta[type]">
        <option value="html" <?php selected( $term_meta['type'], 'html' ); ?>><?php _e( 'Display', 'steel' ); ?></option>
        <option value="rss" <?php selected( $term_meta['type'], 'rss' ); ?>><?php _e( 'Podcast', 'steel' ); ?></option>
      </select>
      <p class="description"><?php _e( 'Display outputs HTML, Podcast outputs RSS for iTunes', 'steel' ) ?></p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="channel_meta[cover_photo_id]"><?php _e( 'Cover Photo', 'steel' ); ?></label></th>
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
        <span class="dashicons dashicons-format-image"></span> <?php _e( 'Set Cover Photo', 'steel' ); ?>
      </a>
      <p class="description"><?php _e( 'iTunes requires square JPG or PNG images that are at least 1400x1400 pixels', 'steel' ); ?></p>
    </td>
  </tr>
  <tr>
    <th scope="row" valign="top"><h3><?php _e( 'Podcast Information', 'steel' ); ?></h3></th>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="channel_meta[link]"><?php _e( 'Link', 'steel' ); ?></label></th>
    <td>
      <input type="text" name="channel_meta[link]" value="<?php echo $term_meta['link']; ?>" />
      <p class="description"><?php _e( 'The podcast feed URL.', 'steel' ) ?></p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="channel_meta[copyright]"><?php _e( 'Copyright Notice', 'steel' ); ?></label></th>
    <td>
      <input type="text" name="channel_meta[copyright]" value="<?php echo $term_meta['copyright']; ?>" />
      <p class="description"><?php _e( 'i.e. "2015 Star Verte LLC. All Rights Reserved."', 'steel' ) ?></p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="channel_meta[author]"><?php _e( 'Author', 'steel' ); ?></label></th>
    <td>
      <input type="text" name="channel_meta[author]" value="<?php echo $term_meta['author']; ?>" />
      <p class="description"><?php _e( 'The individual or corporate author of the podcast.', 'steel' ) ?></p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="channel_meta[category]"><?php _e( 'Category', 'steel' ); ?></label></th>
    <td>
      <select name="channel_meta[category]">
        <?php foreach ( $itunes_cats as $key => $value ) : ?>
        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $term_meta['category'], $key ); ?>><?php echo esc_attr( $value ); ?></option>
        <?php endforeach; ?>
      </select>
    </td>
  </tr>
  <tr>
    <th scope="row" valign="top"><h4><?php _e( 'Contact Information', 'steel' ); ?></h4></th>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="channel_meta[owner_name]"><?php _e( 'Name', 'steel' ); ?></label></th>
    <td>
      <input type="text" name="channel_meta[owner_name]" value="<?php echo $term_meta['owner_name']; ?>" />
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="channel_meta[owner_email]"><?php _e( 'Email', 'steel' ); ?></label></th>
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
