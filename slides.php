<?php
/*
 * Allows creation of media slideshows (a.k.a. carousels, sliders, etc.) for use in template files, posts, pages and more
 * Uses Bootstrap Carousel plugin
 *
 * @package Steel/Slides
 */

add_action( 'init', 'steel_slides_init', 0 );
function steel_slides_init() {
  $labels = array(
    'name'                => _x( 'Slideshows', 'Post Type General Name', 'steel' ),
    'singular_name'       => _x( 'Slideshow', 'Post Type Singular Name', 'steel' ),
    'menu_name'           => __( 'Slides', 'steel' ),
    'all_items'           => __( 'All Slideshows', 'steel' ),
    'view_item'           => __( 'View', 'steel' ),
    'add_new_item'        => __( 'Add New', 'steel' ),
    'add_new'             => __( 'New', 'steel' ),
    'edit_item'           => __( 'Edit', 'steel' ),
    'update_item'         => __( 'Update', 'steel' ),
    'search_items'        => __( 'Search slideshows', 'steel' ),
    'not_found'           => __( 'No slideshows found', 'steel' ),
    'not_found_in_trash'  => __( 'No slideshows found in Trash. Did you check recycling?', 'steel' ),
  );
  $args = array(
    'label'               => __( 'steel_slides', 'steel' ),
    'description'         => __( 'Cycle through image and video slides like a carousel', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title' ),
    'hierarchical'        => false,
    'public'              => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-slides',
    'can_export'          => false,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'rewrite'             => true,
    'capability_type'     => 'post',
  );
  register_post_type( 'steel_slides', $args );

  add_image_size( 'steel-slide-thumb', 300, 185, true);
}

/*
 * Create custom meta boxes
 */
add_action( 'add_meta_boxes', 'steel_slides_meta_boxes' );
function steel_slides_meta_boxes() {
  add_meta_box('steel_slides_slideshow', 'Add/Edit Slides'     , 'steel_slides_slideshow', 'steel_slides', 'advanced', 'high'  );
  add_meta_box('steel_slides_info'     , 'Using this Slideshow', 'steel_slides_info'     , 'steel_slides', 'side');
  add_meta_box('steel_slides_settings' , 'Slideshow Settings'  , 'steel_slides_settings' , 'steel_slides', 'side');
}
function steel_slides_slideshow() {
  $slides_media     = steel_slides_meta( 'media' );
  $slides_order     = steel_slides_meta( 'order' );
  $slides_media_url = steel_slides_meta( 'media_url' );

  $slides = explode(',', $slides_order);

  $output = '';
  $output .= '<a href="#" class="button add_slide_media" id="btn_above" title="Add slide to slideshow"><span class="steel-icon-cover-photo"></span> Add Slide</a>';
  $output .= '<div id="slides_wrap"><div id="slides">';
  foreach ($slides as $slide) {
    if (!empty($slide)) {
      $image = wp_get_attachment_image_src( $slide, 'steel-slide-thumb' );
      $output .= '<div class="slide" id="';
      $output .= $slide;
      $output .= '">';
      $output .= '<div class="slide-controls"><span id="controls_'.$slide.'">'.steel_slides_meta( 'title_'.$slide ).'</span><a class="del-slide" href="#" onclick="deleteSlide(\''.$slide.'\')" title="Delete slide"><span class="steel-icon-dismiss" style="float:right"></span></a></div>';
      $output .= '<img id="slide_img_'.$slide.'" src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'">';
      $output .= '<p><input type="text" size="32" class="slide-title" name="slides_title_';
      $output .= $slide;
      $output .= '" id="slides_title_'.$slide.'" value="'.steel_slides_meta( 'title_'.$slide ).'" placeholder="Title" /><br>';
      $output .= '<textarea cols="32" name="slides_content_';
      $output .= $slide;
      $output .= '" id="slides_content_'.$slide.'" placeholder="Caption">'.steel_slides_meta( 'content_'.$slide ).'</textarea></p>';
      $output .= '<span class="steel-icon-link" style="float:left;padding:5px;"></span><input type="text" size="28" name="slides_link_';
      $output .= $slide;
      $output .= '" id="slides_link_'.$slide.'" value="'.steel_slides_meta( 'link_'.$slide ).'" placeholder="Link" style="margin:0;" />';
      $output .= '</div>';
    }
  }
    $output .= '</div><a href="#" class="add_slide_media add_new_slide" title="Add slide to slideshow"><div class="slide-new"><p><span class="glyphicon glyphicon-plus-sign"></span><br>Add Slide</p></div></a>';
  $output .= '</div>';

  echo $output; ?>

  <input type="hidden" name="slides_order" id="slides_order" value="<?php echo $slides_order; ?>">
  <div style="float:none; clear:both;"></div><?php
}
function steel_slides_info() {
  global $post; ?>

  <p>To use this slider in your posts or pages use the following shortcode:</p>
  <p><code>[steel_slideshow id="<?php echo $post->ID; ?>"]</code> or</p><p><code>[steel_slideshow name="<?php echo strtolower($post->post_title); ?>"]</code></p><?php
}
function steel_slides_settings() {
  global $post;
  $skins = array('Default','Bar','Simple','Tabs','Thumbnails');
  $the_skin = steel_slides_meta( 'skin' );
  $transitions = array('Default','Fade');
  $the_transition = steel_slides_meta( 'transition' ); ?>

  <p><label for="slides_skin">Skin</label>&nbsp;&nbsp;&nbsp;
     <select id="slides_skin" name="slides_skin">
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
  <p><label for="slides_transition">Transition</label>&nbsp;&nbsp;&nbsp;
     <select id="slides_transition" name="slides_transition">
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
add_action('save_post', 'save_steel_slides');
function save_steel_slides() {
  global $post;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id))) { return $post_id; }
  if (defined('DOING_AJAX') && DOING_AJAX && (isset($post_id))) { return $post_id; }
  if (preg_match('/\edit\.php/', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; }
  if (isset($_POST['slides_order']   )) {
    update_post_meta($post->ID, 'slides_order'   , $_POST['slides_order']);
    $slides = explode(',', get_post_meta($post->ID, 'slides_order', true));
    foreach ($slides as $slide) {
      if (isset($_POST['slides_title_'   . $slide])) { update_post_meta($post->ID, 'slides_title_'  . $slide, $_POST['slides_title_'   . $slide]); }
      if (isset($_POST['slides_content_' . $slide])) { update_post_meta($post->ID, 'slides_content_'. $slide, $_POST['slides_content_' . $slide]); }
      if (isset($_POST['slides_link_'    . $slide])) { update_post_meta($post->ID, 'slides_link_'   . $slide, $_POST['slides_link_'    . $slide]); }
    }
  }

  if (isset($_POST['slides_author']   )) { update_post_meta($post->ID, 'slides_author'   , $_POST['slides_author']   ); }
  if (isset($_POST['slides_media']    )) { update_post_meta($post->ID, 'slides_media'    , $_POST['slides_media']    ); }
  if (isset($_POST['slides_media_url'])) { update_post_meta($post->ID, 'slides_media_url', $_POST['slides_media_url']); }

  if (!empty($_POST['slides_skin']))      { update_post_meta($post->ID, 'slides_skin', $_POST['slides_skin']); }
    elseif (isset($_POST['slides_skin'])) { update_post_meta($post->ID, 'slides_skin', 'Default'            ); }

  if (!empty($_POST['slides_transition']))      { update_post_meta($post->ID, 'slides_transition', $_POST['slides_transition']); }
    elseif (isset($_POST['slides_transition'])) { update_post_meta($post->ID, 'slides_transition', 'Default'            ); }
}

/*
 * Display Slides metadata
 */
function steel_slides_meta( $key, $post_id = NULL ) {
  $meta = steel_meta( 'slides', $key, $post_id );
  return $meta;
}

/*
 * Display Slideshow by id
 */
function steel_slideshow( $post_id, $size = 'full', $name = NULL ) {
  $name = empty($name) ? $post_id : $name;

  $slides_media      = steel_slides_meta( 'media'     , $post_id );
  $slides_order      = steel_slides_meta( 'order'     , $post_id );
  $slides_media_url  = steel_slides_meta( 'media_url' , $post_id );
  $slides_skin       = steel_slides_meta( 'skin'      , $post_id );
  $slides_transition = steel_slides_meta( 'transition', $post_id );

  $slides_class  = 'carousel slide';
  $slides_class .= !empty($slides_skin) ? ' carousel-'.strtolower($slides_skin) : ' carousel-default' ;
  $slides_class .= !empty($slides_transition) && $slides_transition != 'Default' ? ' carousel-'.strtolower($slides_transition) : '' ;

  $slides = explode(',', $slides_order);

  $output     = '';
  $indicators = '';
  $items      = '';
  $controls   = '';
  $count      = -1;
  $i          = -1;

  //Wrapper for slides
  foreach ($slides as $slide) {
    if (!empty($slide)) {
      $image   = wp_get_attachment_image_src( $slide, $size );
      $title   = steel_slides_meta( 'title_'  .$slide, $post_id );
      $content = steel_slides_meta( 'content_'.$slide, $post_id );
      $link    = steel_slides_meta( 'link_'   .$slide, $post_id );
      $i += 1;

      $items .= $i >= 1 ? '<div class="item">' : '<div class="item active">';
      $items .= !empty($link) ? '<a href="'.$link.'">' : '';
      $items .= '<img id="slide_img_'.$slide.'" src="'.$image[0].'" alt="'.$title.'">';
      $items .= !empty($link) ? '</a>' : '';

      if (!empty($title) || !empty($content)) {
        $items .= '<div class="carousel-caption">';
        if ($slides_skin != 'Bar') {
          if (!empty($title  )) { $items .= '<h3 id="slides_title_'.$slide.'">' .$title  .'</h3>'; }
          if (!empty($content)) { $items .= '<p class="hidden-xs" id="slides_content_'.$slide.'">'.$content.'</p>' ; }
        }
        else {
          if (!empty($title)) { $items .= '<p id="slides_title_'.$slide.'">' .$title.'</p>'; }
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
  if (empty($slides_skin) | (!empty($slides_skin) && $slides_skin == 'Default')) {
    $indicators .= '<ol class="carousel-indicators">';
    foreach ($slides as $slide) {
      if (!empty($slide)) {
        $count += 1;
        $indicators .= $count >= 1 ? '<li data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"></li>' : '<li data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'" class="active"></li>';
      }
    }
    $indicators .= '</ol>';
  }
  elseif (!empty($slides_skin) && $slides_skin == 'Tabs') {
    $indicators .= '<ol class="nav nav-tabs carousel-indicators">';
    foreach ($slides as $slide) {
      if (!empty($slide)) {
        $count += 1;
        $title   = steel_slides_meta( 'title_'  .$slide, $post_id );
        $indicators .= $count >= 1 ? '<li data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><a href="#carousel_'.$post_id.'">' . $title . '</a></li>' : '<li class="active" data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><a href="#carousel_'.$post_id.'">' . $title . '</a></li>';
      }
    }
    $indicators .= '</ol>';
  }
  elseif (!empty($slides_skin) && $slides_skin == 'Thumbnails') {
    $indicators .= '<div class="carousel-thumbs hidden-sm hidden-xs">';
    $indicators .= '<span class="col-lg-'.$spc_lg.' col-md-'.$spc_lg.'"></span>';
    foreach ($slides as $slide) {
      if (!empty($slide)) {
        $count += 1;
        $image   = wp_get_attachment_image_src( $slide, 'steel-slide-thumb' );
        $title   = steel_slides_meta( 'title_'  .$slide, $post_id );
        $indicators .= $count >= 1 ? '<span class="col-lg-'.$col_lg.' col-md-'.$col_lg.'" data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><img id="slide_thumb_'.$slide.'" src="'.$image[0].'" alt="'.$title.'"></span>' : '<span class="col-lg-'.$col_lg.' col-md-'.$col_lg.'" data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><img id="slide_thumb_'.$slide.'" src="'.$image[0].'" alt="'.$title.'"></span>';
      }
    }
    $indicators .= '<span class="col-lg-'.$spc_lg.' col-md-'.$spc_lg.'"></span>';
    $indicators .= '</div>';
  }

  //Controls
  $controls .= (!empty($slides_skin) && $slides_skin == 'Simple') ? '<div class="carousel-controls">' : '';
  $controls .= '<a class="left ' .'carousel-control" href="#carousel_'.$post_id.'" data-slide="prev"><span class="icon-prev' .'"></span></a>';
  $controls .= '<a class="right '.'carousel-control" href="#carousel_'.$post_id.'" data-slide="next"><span class="icon-next'.'"></span></a>';
  $controls .= (!empty($slides_skin) && $slides_skin == 'Simple') ? '</div>' : '';

  //Output
  $output .= !empty($slides_skin) && $slides_skin == 'Tabs' ? $indicators : '';
  $output .= '<div id="carousel_'.$name.'" class="'.$slides_class.'" data-ride="carousel">';
  $output .= empty($slides_skin) | (!empty($slides_skin) && $slides_skin == 'Default') ? $indicators : '';
  $output .= '<div class="carousel-inner">';
  $output .= $items;
  $output .= '</div>';
  $output .= $controls;
  $output .= '</div>';
  $output .= !empty($slides_skin) && $slides_skin == 'Thumbnails' ? $indicators : '';

  return $output;
}

/*
 * Create [steel_slideshow] shortcode
 */
if ( shortcode_exists( 'steel_slideshow' ) ) { remove_shortcode( 'steel_slideshow' ); }
add_shortcode( 'steel_slideshow', 'steel_slideshow_shortcode' );
function steel_slideshow_shortcode( $atts, $content = null ) {
  extract( shortcode_atts( array( 'id' => null, 'name' => null, 'size' => 'full' ), $atts ) );

  if (!empty($id)) {
    $output = steel_slideshow( $id, $size );
  }
  elseif (!empty($name)) {
    $show = get_page_by_title( $name, OBJECT, 'steel_slides' );
    $output = steel_slideshow( $show->ID, $size, $name );
  }
  else {
    return;
  }
  return $output;
}
?>
