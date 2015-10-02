<?php
/**
 * Allows creation of audio playlists that can be formatted for and submitted to iTunes
 *
 * @package Steel\Broadcast
 */

/**
 * Return arguments for registering steel_broadcast
 */
function steel_broadcast_post_type_args() {
  $labels = array(
    'name'                => _x( 'Podcasts', 'Post Type General Name', 'steel' ),
    'singular_name'       => _x( 'Podcast', 'Post Type Singular Name', 'steel' ),
    'menu_name'           => __( 'Podcasts', 'steel' ),
    'all_items'           => __( 'All Podcasts', 'steel' ),
    'view_item'           => __( 'View', 'steel' ),
    'add_new_item'        => __( 'Add New', 'steel' ),
    'add_new'             => __( 'New', 'steel' ),
    'edit_item'           => __( 'Edit', 'steel' ),
    'update_item'         => __( 'Update', 'steel' ),
    'search_items'        => __( 'Search podcasts', 'steel' ),
    'not_found'           => __( 'No podcasts found', 'steel' ),
    'not_found_in_trash'  => __( 'No podcasts found in Trash. Did you check recycling?', 'steel' ),
  );
  $args = array(
    'label'               => __( 'steel_broadcast', 'steel' ),
    'description'         => __( 'Series of playlists that can be added to iTunes feed', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnails' ),
    'hierarchical'        => false,
    'public'              => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-playlist-audio',
    'can_export'          => false,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'rewrite'             => true,
    'capability_type'     => 'post',
  );
  return $args;
}

function steel_broadcast_episode_list() {
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
add_action( 'save_post', 'steel_save_podcast' );
function steel_save_podcast() {
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
