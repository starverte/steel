<?php
/*
 * Allows creation of audio playlists that can be formatted for and submitted to iTunes
 *
 * @package Steel\Podcast
 */

add_action( 'init', 'steel_podcast_init', 0 );
function steel_podcast_init() {
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
    'label'               => __( 'steel_podcast', 'steel' ),
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
  register_post_type( 'steel_podcast', $args );

  add_image_size( 'steel-episode-thumb', 300, 185, true);
}

/*
 * Create custom meta boxes
 */
add_action( 'add_meta_boxes', 'steel_podcast_meta_boxes' );
function steel_podcast_meta_boxes() {
  add_meta_box('steel_podcast_podcast', 'Add/Edit Series'     , 'steel_podcast_podcast', 'steel_podcast', 'side', 'high'  );
  add_meta_box('steel_podcast_info'     , 'Using this Podcast', 'steel_podcast_info'     , 'steel_podcast', 'side');
  add_meta_box('steel_podcast_settings' , 'Podcast Settings'  , 'steel_podcast_settings' , 'steel_podcast', 'side');
}
function steel_podcast_podcast() {
  $series_media     = steel_podcast_meta( 'media' );
  $series_order     = steel_podcast_meta( 'order' );
  $series_media_url = steel_podcast_meta( 'media_url' );

  $series = explode(',', $series_order);

  $output = '';
  $output .= '<a href="#" class="button add_episode_media" id="btn_above" title="Add episode to podcast"><span class="dashicons dashicons-playlist-audio"></span> Add Episode</a>';
  $output .= '<div id="series_wrap"><div id="series">';
  foreach ($series as $episode) {
    if (!empty($episode)) {
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
function steel_podcast_info() {
  global $post; ?>

  <p>To use this episoder in your posts or pages use the following shortcode:</p>
  <p><code>[steel_podcast id="<?php echo $post->ID; ?>"]</code> or</p><p><code>[steel_podcast name="<?php echo strtolower($post->post_title); ?>"]</code></p><?php
}
function steel_podcast_settings() {
  global $post;
  $skins = array('Default','Bar','Simple','Tabs','Thumbnails');
  $the_skin = steel_podcast_meta( 'skin' );
  $transitions = array('Default','Fade');
  $the_transition = steel_podcast_meta( 'transition' ); ?>

  <p><label for="series_skin">Skin</label>&nbsp;&nbsp;&nbsp;
     <select id="series_skin" name="series_skin">
        <option value="">Select</option>
        <?php
          foreach ($skins as $skin) {
            $option  = '<option value="' . $skin . '" '. selected( $the_skin, $skin ) .'>';
            $option .= $skin;
            $option .= '</option>';
            echo $option;
          }
        ?>
      </select>
  </p>
  <p><label for="series_transition">Transition</label>&nbsp;&nbsp;&nbsp;
     <select id="series_transition" name="series_transition">
        <option value="">Select</option>
        <?php
          foreach ($transitions as $transition) {
            $option  = '<option value="' . $transition . '" '. selected( $the_transition, $transition ) .'>';
            $option .= $transition;
            $option .= '</option>';
            echo $option;
          }
        ?>
      </select>
  </p><?php
}

/*
 * Save data from meta boxes
 */
add_action('save_post', 'save_steel_podcast');
function save_steel_podcast() {
  global $post;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id))) { return $post_id; }
  if (defined('DOING_AJAX') && DOING_AJAX && (isset($post_id))) { return $post_id; }
  if (preg_match('/\edit\.php/', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; }
  if (isset($_POST['series_order']   )) {
    update_post_meta($post->ID, 'series_order'   , $_POST['series_order']);
    $series = explode(',', get_post_meta($post->ID, 'series_order', true));
    foreach ($series as $episode) {
      if (isset($_POST['episode_' . $episode . '_title'   ])) { update_post_meta($post->ID, 'episode_' . $episode . '_title'   , $_POST['episode_' . $episode . '_title'   ]); }
      if (isset($_POST['episode_' . $episode . '_summary' ])) { update_post_meta($post->ID, 'episode_' . $episode . '_summary' , $_POST['episode_' . $episode . '_summary' ]); }
      if (isset($_POST['episode_' . $episode . '_date'    ])) { update_post_meta($post->ID, 'episode_' . $episode . '_date'    , $_POST['episode_' . $episode . '_date'    ]); }
      if (isset($_POST['episode_' . $episode . '_author'  ])) { update_post_meta($post->ID, 'episode_' . $episode . '_author'  , $_POST['episode_' . $episode . '_author'  ]); }
      if (isset($_POST['episode_' . $episode . '_duration'])) { update_post_meta($post->ID, 'episode_' . $episode . '_duration', $_POST['episode_' . $episode . '_duration']); }
    }
  }

  if (isset($_POST['series_author']   )) { update_post_meta($post->ID, 'series_author'   , $_POST['series_author']   ); }
  if (isset($_POST['series_media']    )) { update_post_meta($post->ID, 'series_media'    , $_POST['series_media']    ); }
  if (isset($_POST['series_media_url'])) { update_post_meta($post->ID, 'series_media_url', $_POST['series_media_url']); }
}

/*
 * Display Series metadata
 */
function steel_podcast_meta( $key, $post_id = NULL ) {
  $meta = steel_meta( 'series', $key, $post_id );
  return $meta;
}
function steel_episode_meta( $key, $post_id = NULL ) {
  $meta = steel_meta( 'episode', $key, $post_id );
  return $meta;
}

/*
 * Display Podcast by id
 */
function steel_podcast( $post_id, $size = 'full', $name = NULL ) {
  $name = empty($name) ? $post_id : $name;

  $series_media      = steel_podcast_meta( 'media'     , $post_id );
  $series_order      = steel_podcast_meta( 'order'     , $post_id );
  $series_media_url  = steel_podcast_meta( 'media_url' , $post_id );
  $series_skin       = steel_podcast_meta( 'skin'      , $post_id );
  $series_transition = steel_podcast_meta( 'transition', $post_id );

  $series_class  = 'carousel episode';
  $series_class .= !empty($series_skin) ? ' carousel-'.strtolower($series_skin) : ' carousel-default' ;
  $series_class .= !empty($series_transition) && 'Default' !== $series_transition ? ' carousel-'.strtolower($series_transition) : '' ;

  $series = explode(',', $series_order);

  $output     = '';
  $indicators = '';
  $items      = '';
  $controls   = '';
  $count      = -1;
  $i          = -1;

  //Wrapper for series
  foreach ($series as $episode) {
    if (!empty($episode)) {
      $image   = wp_get_attachment_image_src( $episode, $size );
      $title   = steel_podcast_meta( 'title_'  .$episode, $post_id );
      $content = steel_podcast_meta( 'content_'.$episode, $post_id );
      $link    = steel_podcast_meta( 'link_'   .$episode, $post_id );
      $i += 1;

      $items .= $i >= 1 ? '<div class="item">' : '<div class="item active">';
      $items .= !empty($link) ? '<a href="'.$link.'">' : '';
      $items .= '<img id="episode_img_'.$episode.'" src="'.$image[0].'" alt="'.$title.'">';
      $items .= !empty($link) ? '</a>' : '';

      if (!empty($title) || !empty($content)) {
        $items .= '<div class="carousel-caption">';
        if ('Bar' !== $series_skin) {
          if (!empty($title  )) { $items .= '<h3 id="episode_'.$episode.'_title">' .$title  .'</h3>'; }
          if (!empty($content)) { $items .= '<p class="hidden-xs" id="episode_'.$episode.'_summary">'.$content.'</p>' ; }
        }
        else {
          if (!empty($title)) { $items .= '<p id="episode_'.$episode.'_title">' .$title.'</p>'; }
        }
        $items .= '</div>';//.carousel-caption
      }
      $items .= '</div>';//.item
    }
  }

  $col_lg = floor(12/($i + 1));
  $rem_lg = 12 - ($col_lg * ($i + 1));
  $spc_lg = floor($rem_lg/2);

  //Indicators
  if (empty($series_skin) | (!empty($series_skin) && 'Default' === $series_skin)) {
    $indicators .= '<ol class="carousel-indicators">';
    foreach ($series as $episode) {
      if (!empty($episode)) {
        $count += 1;
        $indicators .= $count >= 1 ? '<li data-target="#carousel_'.$post_id.'" data-episode-to="'.$count.'"></li>' : '<li data-target="#carousel_'.$post_id.'" data-episode-to="'.$count.'" class="active"></li>';
      }
    }
    $indicators .= '</ol>';
  }
  elseif (!empty($series_skin) && 'Tabs' === $series_skin) {
    $indicators .= '<ol class="nav nav-tabs carousel-indicators">';
    foreach ($series as $episode) {
      if (!empty($episode)) {
        $count += 1;
        $title   = steel_podcast_meta( 'title_'  .$episode, $post_id );
        $indicators .= $count >= 1 ? '<li data-target="#carousel_'.$post_id.'" data-episode-to="'.$count.'"><a href="#carousel_'.$post_id.'">' . $title . '</a></li>' : '<li class="active" data-target="#carousel_'.$post_id.'" data-episode-to="'.$count.'"><a href="#carousel_'.$post_id.'">' . $title . '</a></li>';
      }
    }
    $indicators .= '</ol>';
  }
  elseif (!empty($series_skin) && 'Thumbnails' === $series_skin) {
    $indicators .= '<div class="carousel-thumbs hidden-sm hidden-xs">';
    $indicators .= '<span class="col-lg-'.$spc_lg.' col-md-'.$spc_lg.'"></span>';
    foreach ($series as $episode) {
      if (!empty($episode)) {
        $count += 1;
        $image   = wp_get_attachment_image_src( $episode, 'steel-episode-thumb' );
        $title   = steel_podcast_meta( 'title_'  .$episode, $post_id );
        $indicators .= $count >= 1 ? '<span class="col-lg-'.$col_lg.' col-md-'.$col_lg.'" data-target="#carousel_'.$post_id.'" data-episode-to="'.$count.'"><img id="episode_thumb_'.$episode.'" src="'.$image[0].'" alt="'.$title.'"></span>' : '<span class="col-lg-'.$col_lg.' col-md-'.$col_lg.'" data-target="#carousel_'.$post_id.'" data-episode-to="'.$count.'"><img id="episode_thumb_'.$episode.'" src="'.$image[0].'" alt="'.$title.'"></span>';
      }
    }
    $indicators .= '<span class="col-lg-'.$spc_lg.' col-md-'.$spc_lg.'"></span>';
    $indicators .= '</div>';
  }

  //Controls
  $controls .= (!empty($series_skin) && 'Simple' === $series_skin) ? '<div class="carousel-controls">' : '';
  $controls .= '<a class="left ' .'carousel-control" href="#carousel_'.$post_id.'" data-episode="prev"><span class="icon-prev' .'"></span></a>';
  $controls .= '<a class="right '.'carousel-control" href="#carousel_'.$post_id.'" data-episode="next"><span class="icon-next'.'"></span></a>';
  $controls .= (!empty($series_skin) && 'Simple' === $series_skin) ? '</div>' : '';

  //Output
  $output .= !empty($series_skin) && 'Tabs' === $series_skin ? $indicators : '';
  $output .= '<div id="carousel_'.$name.'" class="'.$series_class.'" data-ride="carousel">';
  $output .= empty($series_skin) | (!empty($series_skin) && 'Default' === $series_skin) ? $indicators : '';
  $output .= '<div class="carousel-inner">';
  $output .= $items;
  $output .= '</div>';
  $output .= $controls;
  $output .= '</div>';
  $output .= !empty($series_skin) && 'Thumbnails' === $series_skin ? $indicators : '';

  return $output;
}

/*
 * Create [steel_podcast] shortcode
 */
if ( shortcode_exists( 'steel_podcast' ) ) { remove_shortcode( 'steel_podcast' ); }
add_shortcode( 'steel_podcast', 'steel_podcast_shortcode' );
function steel_podcast_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array( 'id' => null, 'name' => null, 'size' => 'full' ), $atts ) );

  if (!empty($id)) {
    $output = steel_podcast( $id, $size );
  }
  elseif (!empty($name)) {
    $show = get_page_by_title( $name, OBJECT, 'steel_podcast' );
    $output = steel_podcast( $show->ID, $size, $name );
  }
  else {
    return;
  }
  return $output;
}
?>
