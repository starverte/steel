<?php
/*
 * Allows creation of media slideshows (a.k.a. carousels, sliders, etc.) for use in template files, posts, pages and more
 * Uses Bootstrap Carousel plugin
 *
 * @package Steel
 * @module Slides
 *
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
    'can_export'          => true,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'rewrite'             => true,
    'capability_type'     => 'post',
  );
  register_post_type( 'steel_slides', $args );

  add_image_size( 'steel-slide-thumb', 290, 180, true);
}

/*
 * Create custom meta boxes
 */
add_action( 'add_meta_boxes', 'steel_slides_meta_boxes' );
function steel_slides_meta_boxes() { add_meta_box('steel_slides_details', 'Add/Edit Slides', 'steel_slides_details', 'steel_slides', 'advanced', 'high'); }
function steel_slides_details() {
  $slides_media = steel_slides_meta( 'media' );
  $slides_order = steel_slides_meta( 'order' );
  $slides_media_url = steel_slides_meta( 'media_url' );
  $the_slides = array('123','234','345','456','567');
  $slides = explode(',', $slides_order);

  $output = '';
  $output .= '<a href="#" class="button add_slide_media" id="btn_above" title="Add slide to slideshow" style="margin-bottom:10px;"><span class="steel-icon-cover-photo"></span> New Slide</a>';
  $output .= '<div id="slides">';
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
      $output .= '<input type="text" size="32" name="slides_link_';
      $output .= $slide;
      $output .= '" id="slides_link_'.$slide.'" value="'.steel_slides_meta( 'link_'.$slide ).'" placeholder="Link" />';
      $output .= '<textarea cols="32" name="slides_content_';
      $output .= $slide;
      $output .= '" id="slides_content_'.$slide.'" placeholder="Caption">'.steel_slides_meta( 'content_'.$slide ).'</textarea>';
      $output .= '</p>';
      $output .= '</div>';
    }
  }
  $output .= '</div>';
  $output .= '<a href="#" class="button add_slide_media" id="btn_below" title="Add slide to slideshow" style="float:left;clear:both;margin-top:10px;"><span class="steel-icon-cover-photo"></span> New Slide</a>';

  echo $output; ?>

  <input type="hidden" name="slides_order" id="slides_order" value="<?php echo $slides_order; ?>">
  <div style="float:none; clear:both;"></div><?php
}

/*
 * Save data from meta boxes
 */
add_action('save_post', 'save_steel_slides');
function save_steel_slides() {
  global $post;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id))) { return $post_id; }
  if(defined('DOING_AJAX') && DOING_AJAX && (isset($post_id))) { return $post_id; }
  if(preg_match('/\edit\.php/', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; }
  if (!empty($_POST['slides_order']   )) {
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
}

/*
 * Display Podcast Episode metadata
 */
function steel_slides_meta( $meta ) {
  global $post;
  $custom = get_post_custom($post->ID);
  $output = !empty($custom['slides_'.$meta][0]) ? $custom['slides_'.$meta][0] : '';
  return $output;
}

function steel_get_image_url( $attachment_id, $size = 'thumbnail', $icon = false ) {
  $image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
  return $image[0];
}
?>