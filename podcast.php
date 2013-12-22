<?php
/*
 * Allows creation and management of podcast feeds, including series, audio, and video
 *
 * @package Steel
 * @module Podcast
 *
 */

add_action( 'init'       , 'steel_podcast_init'    , 0);
add_action( 'edit_term'  , 'save_steel_pod_custom'    );
add_action( 'create_term', 'save_steel_pod_custom'    );

function steel_podcast_init() {

  // Register Custom Post Type Episode
  $labels = array(
    'name'                => _x( 'Episodes', 'Post Type General Name', 'steel' ),
    'singular_name'       => _x( 'Episode', 'Post Type Singular Name', 'steel' ),
    'menu_name'           => __( 'Podcast', 'steel' ),
    'all_items'           => __( 'All episodes', 'steel' ),
    'view_item'           => __( 'View', 'steel' ),
    'add_new_item'        => __( 'Add New', 'steel' ),
    'add_new'             => __( 'New Episode', 'steel' ),
    'edit_item'           => __( 'Edit', 'steel' ),
    'update_item'         => __( 'Update', 'steel' ),
    'search_items'        => __( 'Search episodes', 'steel' ),
    'not_found'           => __( 'No episodes found', 'steel' ),
    'not_found_in_trash'  => __( 'No episodes found in Trash. Did you check recycling?', 'steel' ),
  );
  $rewrite = array(
    'slug'                => 'episode',
    'with_front'          => true,
    'pages'               => false,
    'feeds'               => false,
  );
  $args = array(
    'label'               => __( 'steel_pod_episode', 'steel' ),
    'description'         => __( 'Create podcast series and episodes', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail' ),
    'taxonomies'          => array( 'steel_pod_channel' ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-rss',
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'post',
  );
  register_post_type( 'steel_pod_episode', $args );

  // Register Custom Taxonomy Channel
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
    'slug'                       => 'channels',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => false,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'rewrite'                    => $rewrite,
  );
  register_taxonomy( 'steel_pod_channel', 'steel_pod_episode', $args );
  
  // Register Custom Taxonomy Series
  $labels = array(
    'name'                       => _x( 'Series', 'Taxonomy General Name', 'steel' ),
    'singular_name'              => _x( 'Series', 'Taxonomy Singular Name', 'steel' ),
    'menu_name'                  => __( 'Series', 'steel' ),
    'all_items'                  => __( 'All Series', 'steel' ),
    'new_item_name'              => __( 'New Series', 'steel' ),
    'add_new_item'               => __( 'Add New Series', 'steel' ),
    'edit_item'                  => __( 'Edit Series', 'steel' ),
    'update_item'                => __( 'Update', 'steel' ),
    'separate_items_with_commas' => __( 'Separate series with commas', 'steel' ),
    'search_items'               => __( 'Search series', 'steel' ),
    'add_or_remove_items'        => __( 'Add or remove series', 'steel' ),
    'choose_from_most_used'      => __( 'Choose from the most used series', 'steel' ),
  );
  $rewrite = array(
    'slug'                       => 'series',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => false,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'rewrite'                    => $rewrite,
  );
  register_taxonomy( 'steel_pod_series', 'steel_pod_episode', $args );
}

function steel_pod_series_field($taxonomy = 'steel_pod_series') {
  $output  = '';

  if(empty($taxonomy->term_id)) {
    $output .= '<div class="form-field">';
    $output .= '<label for="steel_pod_series_cover">Cover Photo</label>';
    $output .= '<input type="hidden" name="steel_pod_series_cover" id="steel_pod_series_cover">';
    $output .= '<a href="#" id="steel_pod_series_cover_button" class="button insert-media add_media" title="Set Cover Photo"><span class="steel-icon-cover-photo"></span> Set Cover Photo</a>';
    $output .= '</div>';
  }
  else{
    $steel_pod_series_cover_url = get_option('steel_pod_series_cover' . $taxonomy->term_id);

    $output .= '<tr class="form-field">';
    $output .= '<th scope="row" valign="top"><label for="steel_pod_series_cover">Cover Photo</label></th>';
    $output .= '<td>';

    $output .= !empty($steel_pod_series_cover_url) ?'<img src="' . $steel_pod_series_cover_url . '" style="max-width:300px;margin:1em 0;" id="steel_pod_series_cover_img">' : '<img src="" id="steel_pod_series_cover_img" style="max-width:300px;">';

    $output .= '<input type="hidden" name="steel_pod_series_cover" id="steel_pod_series_cover" value="' . $steel_pod_series_cover_url . '">';
    $output .= '<a href="#" id="steel_pod_series_cover_button" class="button insert-media add_media" title="Set Cover Photo" style="display:block;max-width:300px;"><span class="steel-icon-cover-photo"></span> Set Cover Photo</a>';

    $output .= '</td>';
    $output .= '</tr>';       
  }

  echo $output; ?>

  <script type="text/javascript">
    var file_frame;

    jQuery('#steel_pod_series_cover_button').live('click', function( event ){

      event.preventDefault();

      if ( file_frame ) {
        file_frame.open();
        return;
      }

      file_frame = wp.media.frames.file_frame = wp.media({
        title: jQuery( this ).data( 'uploader_title' ),
        button: {
          text: jQuery( this ).data( 'uploader_button_text' ),
        },
        multiple: false
      });

      file_frame.on( 'select', function() {
        attachment = file_frame.state().get('selection').first().toJSON();

        document.getElementById("steel_pod_series_cover"    ).value        = attachment.url;
        document.getElementById("steel_pod_series_cover_img").src          = attachment.url;
        document.getElementById("steel_pod_series_cover_img").style.margin = '1em 0'       ;
      });

      file_frame.open();

    });;
  </script><?php
}

function steel_pod_channel_fields($taxonomy = 'steel_pod_channel') {
  $output  = '';

  if(empty($taxonomy->term_id)) {
        
    //Channel Type
    $output .= '<div class="form-field">';
    $output .= '<label for="steel_pod_channel_type">Type</label>';
    $output .= '<select name="steel_pod_channel_type">';
    $output .= '<option value="display">Display</option>';
    $output .= '<option value="pod_audio">Audio Podcast</option>';
    $output .= '<option value="pod_video">Video Podcast</option>';
    $output .= '</select>';
    $output .= '</div>';
  }
  else {
    $t_ID = $taxonomy->term_id;
    $steel_pod_channel_type = get_option('steel_pod_channel_type' . $t_ID);
    $steel_pod_channel_copy = get_option('steel_pod_channel_copy' . $t_ID);
    $steel_pod_channel_author = get_option('steel_pod_channel_author' . $t_ID);
    $steel_pod_channel_owner_name = get_option('steel_pod_channel_owner_name' . $t_ID);
    $steel_pod_channel_owner_email = get_option('steel_pod_channel_owner_email' . $t_ID);
    
    $a = array( 'value' => 'display'  , 'label' => 'Display'      );
    $b = array( 'value' => 'pod_audio', 'label' => 'Audio Podcast');
    $c = array( 'value' => 'pod_video', 'label' => 'Video Podcast');
    $options = array($a, $b, $c);
    
    //Channel Type
    $output .= '<tr class="form-field">';
    $output .= '<th scope="row" valign="top"><label for="steel_pod_channel_type">Type</label></th>';
    $output .= '<td>';
    $output .= '<select name="steel_pod_channel_type">';
    foreach ($options as $opt) {
      $output .= '<option value="' . $opt['value'] . '" ';
      $output .= $steel_pod_channel_type == $opt['value'] ? ' selected="selected"' : '';
      $output .= '>' . $opt['label'] . '</option>';
    }
    $output .= '</select>';
    $output .= '</td>';
    $output .= '</tr>'; 
    
    if (is_podcast_channel($t_ID)) {
      $output .= '<tr><th scope="row" valign="top"><h3>Podcast Information</h3></th></tr>';
      
      //Copyright Notice
      $output .= '<tr class="form-field">';
      $output .= '<th scope="row" valign="top"><label for="steel_pod_channel_copy">Copyright Notice</label></th>';
      $output .= '<td>';
      $output .= '<input type="text" name="steel_pod_channel_copy" id="steel_pod_channel_copy" value="' . $steel_pod_channel_copy . '">';
      $output .= '<br><span class="description">i.e. &copy;2013 Star Verte LLC. All Rights Reserved.</span>';
      $output .= '</td>';
      $output .= '</tr>';
      
      //Author
      $output .= '<tr class="form-field">';
      $output .= '<th scope="row" valign="top"><label for="steel_pod_channel_author">Author</label></th>';
      $output .= '<td>';
      $output .= '<input type="text" name="steel_pod_channel_author" id="steel_pod_channel_author" value="' . $steel_pod_channel_author . '">';
      $output .= '<br><span class="description">May be an individual or a corporate author. i.e. Star Verte LLC</span>';
      $output .= '</td>';
      $output .= '</tr>';
      
      //Owner
      $output .= '<tr><th scope="row" valign="top"><h4>Podcast Owner</h4>';
      $output .= '<p class="description">The name and email of an individual person.</p>';
      $output .= '</th></tr>';
      $output .= '<tr class="form-field">';
      $output .= '<th scope="row" valign="top"><label for="steel_pod_channel_owner_name">Name</label></th>';
      $output .= '<td>';
      $output .= '<input type="text" name="steel_pod_channel_owner_name" id="steel_pod_channel_owner_name" value="' . $steel_pod_channel_owner_name . '">';
      $output .= '</td>';
      $output .= '</tr>';
      $output .= '<tr class="form-field">';
      $output .= '<th scope="row" valign="top"><label for="steel_pod_channel_owner_email">Email</label></th>';
      $output .= '<td>';
      $output .= '<input type="text" name="steel_pod_channel_owner_email" id="steel_pod_channel_owner_email" value="' . $steel_pod_channel_owner_email . '">';
      $output .= '</td>';
      $output .= '</tr>';
    }      
  }  
  
  echo $output;
}

/**
* Save custom fields for channels and series
*/
function save_steel_pod_custom($term_id) {
  if (isset($_POST['steel_pod_series_cover']))
    update_option('steel_pod_series_cover' . $term_id, $_POST['steel_pod_series_cover']);
  if (isset($_POST['steel_pod_channel_type']))
    update_option('steel_pod_channel_type' . $term_id, $_POST['steel_pod_channel_type']);
  if (isset($_POST['steel_pod_channel_copy']))
    update_option('steel_pod_channel_copy' . $term_id, $_POST['steel_pod_channel_copy']);
  if (isset($_POST['steel_pod_channel_author']))
    update_option('steel_pod_channel_author' . $term_id, $_POST['steel_pod_channel_author']);
  if (isset($_POST['steel_pod_channel_owner_name']))
    update_option('steel_pod_channel_owner_name' . $term_id, $_POST['steel_pod_channel_owner_name']);
  if (isset($_POST['steel_pod_channel_owner_email']))
    update_option('steel_pod_channel_owner_email' . $term_id, $_POST['steel_pod_channel_owner_email']);
}

function is_podcast_channel($term_id) {
  $type = get_option('steel_pod_channel_type'. $term_id);
  if ($type == 'display') :
    return false;
  else :
    return true;
  endif;
}

/*
 * Create custom meta boxes
 */
add_action( 'add_meta_boxes', 'steel_podcast_meta_boxes' );
function steel_podcast_meta_boxes() { add_meta_box('steel_podcast_meta', 'Episode Details', 'steel_podcast_meta', 'steel_pod_episode', 'side', 'high'); }
function steel_podcast_meta() {
  $episode_media = steel_pod_episode_meta( 'media' );
  $episode_media_url = steel_pod_episode_meta( 'media_url' ); ?>
  
  <p><label>Subtitle </label><br>
  <input type="text" size="25" name="episode_subtitle" value="<?php echo steel_pod_episode_meta( 'subtitle' ); ?>" /></p>

  <p><label>Author(s) </label><br>
  <textarea cols="25"  name="episode_author"><?php echo steel_pod_episode_meta( 'author' ); ?></textarea></p>

  <a href="#" id="episode_media_button" class="button add_media" title="Select audio"><span class="steel-icon-media"></span> Select Audio/Video</a>

  <p><input type="text" name="episode_media_name" id="episode_media_name" value="<?php echo $episode_media; ?>" disabled></p>

  <input type="hidden" name="episode_media_url" id="episode_media_url" value="<?php echo $episode_media_url; ?>">
  <input type="hidden" name="episode_media"     id="episode_media"     value="<?php echo $episode_media;     ?>">
  
  <script type="text/javascript">
    var file_frame;

    jQuery('#episode_media_button').live('click', function( event ){

      event.preventDefault();

      if ( file_frame ) {
        file_frame.open();
        return;
      }

      file_frame = wp.media.frames.file_frame = wp.media({
        title: "Episode Video",
        button: {
          text: "Select File",
        },
        multiple: false
      });

      file_frame.on( 'select', function() {
        attachment = file_frame.state().get('selection').first().toJSON();
        document.getElementById("episode_media"     ).value = attachment.filename;
        document.getElementById("episode_media_name").value = attachment.filename;
        document.getElementById("episode_media_url" ).value = attachment.url;
      });

      file_frame.open();

    });;
  </script><?php
}

/*
 * Save data from meta boxes
 */
add_action('save_post', 'save_steel_pod_episode');
function save_steel_pod_episode() {
  global $post;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id))) { return $post_id; }
  if(defined('DOING_AJAX') && DOING_AJAX && (isset($post_id))) { return $post_id; }
  if(preg_match('/\edit\.php/', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; }
  if (isset($_POST['episode_subtitle'] )) { update_post_meta($post->ID, 'episode_subtitle' , $_POST['episode_subtitle'] ); }
  if (isset($_POST['episode_author']   )) { update_post_meta($post->ID, 'episode_author'   , $_POST['episode_author']   ); }
  if (isset($_POST['episode_media']    )) { update_post_meta($post->ID, 'episode_media'    , $_POST['episode_media']    ); }
  if (isset($_POST['episode_media_url'])) { update_post_meta($post->ID, 'episode_media_url', $_POST['episode_media_url']); }
}

/*
 * Display Podcast Episode metadata
 */
function steel_pod_episode_meta( $meta ) {
  global $post;
  $custom = get_post_custom($post->ID);
  $output = !empty($custom['episode_'.$meta][0]) ? $custom['episode_'.$meta][0] : '';
  return $output;
}
?>
