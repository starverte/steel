<?php
/**
 * Allows creation of media slideshows (a.k.a. carousels, sliders, etc.) for use in template files, posts, pages and more
 * Uses Bootstrap Carousel plugin
 *
 * @package Steel\Slides
 *
 * @since 1.2.5
 */

/**
 * Return arguments for registering steel_slides
 */
function steel_slides_post_type_args() {
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
  return $args;
}

/**
 * Register custom post type and image size
 */
function steel_slides_init() {
  register_post_type( 'steel_slides', steel_slides_post_type_args() );
  add_image_size( 'steel-slide-thumb', 300, 185, true );
}
add_action( 'init', 'steel_slides_init' );

/**
 * Display slides on Edit screen
 */
function steel_slides_slideshow() {
  $slides_media     = steel_slides_meta( 'media' );
  $slides_order     = steel_slides_meta( 'order' );
  $slides_media_url = steel_slides_meta( 'media_url' );

  $slides = explode( ',', $slides_order );

  $output = '';
  $output .= '<a href="#" class="button add_slide_media" id="btn_above" title="Add slide to slideshow"><span class="dashicons dashicons-images-alt"></span> Add Slide</a>';
  $output .= '<div id="slides_wrap"><div id="slides">';
  foreach ( $slides as $slide ) {
    if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
      $image = wp_get_attachment_image_src( $slide, 'steel-slide-thumb' );
      $output .= '<div class="slide" id="';
      $output .= $slide;
      $output .= '">';
      $output .= '<div class="slide-controls"><span id="controls_'.$slide.'">'.steel_slides_meta( 'title_'.$slide ).'</span><a class="del-slide" href="#" onclick="deleteSlide(\''.$slide.'\' )" title="Delete slide"><span class="dashicons dashicons-dismiss" style="float:right"></span></a></div>';
      $output .= '<img id="slide_img_'.$slide.'" src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'">';
      $output .= '<p><input type="text" size="32" class="slide-title" name="slides_title_';
      $output .= $slide;
      $output .= '" id="slides_title_'.$slide.'" value="'.steel_slides_meta( 'title_'.$slide ).'" placeholder="Title" /><br>';
      $output .= '<textarea cols="32" name="slides_content_';
      $output .= $slide;
      $output .= '" id="slides_content_'.$slide.'" placeholder="Caption">'.steel_slides_meta( 'content_'.$slide ).'</textarea></p>';
      $output .= '<span class="dashicons dashicons-admin-links" style="float:left;padding:5px;"></span><input type="text" size="28" name="slides_link_';
      $output .= $slide;
      $output .= '" id="slides_link_'.$slide.'" value="'.steel_slides_meta( 'link_'.$slide ).'" placeholder="Link" />';
      $output .= '</div>';
    }
  }
    $output .= '</div><a href="#" class="add_slide_media add_new_slide" title="Add slide to slideshow"><div class="slide-new"><p><span class="glyphicon glyphicon-plus-sign"></span><br>Add Slide</p></div></a>';
  $output .= '</div>';

  echo $output; ?>

  <input type="hidden" name="slides_order" id="slides_order" value="<?php echo $slides_order; ?>">
  <div style="float:none; clear:both;"></div><?php
}

/**
 * Display slideshow shortcode information on Edit screen
 */
function steel_slides_info() {
  global $post; ?>

  <p>To use this slider in your posts or pages use the following shortcode:</p>
  <p><code>[steel_slideshow id="<?php echo $post->ID; ?>"]</code> or</p><p><code>[steel_slideshow name="<?php echo strtolower( $post->post_title ); ?>"]</code></p><?php
}

/**
 * Display slideshow settings on Edit screen
 */
function steel_slides_settings() {
  global $post;
  $skins = array( 'Default', 'Bar', 'Gallery', 'Simple', 'Tabs', 'Thumbnails' );
  $the_skin = steel_slides_meta( 'skin' );
  $transitions = array( 'Default', 'Fade' );
  $the_transition = steel_slides_meta( 'transition' ); ?>

  <p><label for="slides_skin">Skin</label>&nbsp;&nbsp;&nbsp;
     <select id="slides_skin" name="slides_skin">
        <option value="">Select</option>
        <?php
          foreach ( $skins as $skin ) {
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
          foreach ( $transitions as $transition ) {
            $option  = '<option value="' . $transition . '" '. selected( $the_transition, $transition ) .'>';
            $option .= $transition;
            $option .= '</option>';
            echo $option;
          }
        ?>
      </select>
  </p><?php
}

/**
 * Add meta boxes to Edit screen
 */
function steel_slides_add_meta_boxes() {
  add_meta_box(
    'steel_slides_slideshow',
    'Add/Edit Slides',
    'steel_slides_slideshow',
    'steel_slides',
    'advanced',
    'high'
  );

  add_meta_box(
    'steel_slides_info',
    'Using this Slideshow',
    'steel_slides_info',
    'steel_slides',
    'side'
  );

  add_meta_box(
    'steel_slides_settings',
    'Slideshow Settings',
    'steel_slides_settings',
    'steel_slides',
    'side'
  );
}
add_action( 'add_meta_boxes', 'steel_slides_add_meta_boxes' );

/**
 * Save data from meta boxes
 */
function steel_slides_save() {
  global $post;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && (isset( $post_id )) ) { return $post_id; }
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX && (isset( $post_id )) ) { return $post_id; }
  if ( preg_match( '/\edit\.php/', $_SERVER['REQUEST_URI'] ) && (isset( $post_id )) ) { return $post_id; }
  if ( isset( $_POST['slides_order'] ) ) {
    update_post_meta( $post->ID, 'slides_order', $_POST['slides_order'] );
    $slides = explode( ',', get_post_meta( $post->ID, 'slides_order', true ) );
    foreach ( $slides as $slide ) {
      if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
        if ( isset( $_POST[ 'slides_title_'   . $slide ] ) ) { update_post_meta( $post->ID, 'slides_title_'  . $slide, $_POST[ 'slides_title_'   . $slide ] ); }
        if ( isset( $_POST[ 'slides_content_' . $slide ] ) ) { update_post_meta( $post->ID, 'slides_content_'. $slide, $_POST[ 'slides_content_' . $slide ] ); }
        if ( isset( $_POST[ 'slides_link_'    . $slide ] ) ) { update_post_meta( $post->ID, 'slides_link_'   . $slide, $_POST[ 'slides_link_'    . $slide ] ); }
      }
    }
  }

  if ( isset( $_POST['slides_author'] ) ) { update_post_meta( $post->ID, 'slides_author'   , $_POST['slides_author'] ); }
  if ( isset( $_POST['slides_media'] ) ) { update_post_meta( $post->ID, 'slides_media'    , $_POST['slides_media'] ); }
  if ( isset( $_POST['slides_media_url'] ) ) { update_post_meta( $post->ID, 'slides_media_url', $_POST['slides_media_url'] ); }

  if ( ! empty( $_POST['slides_skin'] ) ) {
    update_post_meta( $post->ID, 'slides_skin', $_POST['slides_skin'] );
  } elseif ( isset( $_POST['slides_skin'] ) ) {
    update_post_meta( $post->ID, 'slides_skin', 'Default' );
  }

  if ( ! empty( $_POST['slides_transition'] ) ) {
    update_post_meta( $post->ID, 'slides_transition', $_POST['slides_transition'] );
  } elseif ( isset( $_POST['slides_transition'] ) ) {
    update_post_meta( $post->ID, 'slides_transition', 'Default' );
  }
}
add_action( 'save_post', 'steel_slides_save' );

/**
 * Retrieve post meta field, based on post ID and key.
 *
 * The post meta fields are retrieved from the cache where possible,
 * so the function is optimized to be called more than once.
 *
 * @see WordPress 4.3.1 get_post_custom()
 *
 * @param string $key     The meta key minus the module prefix.
 * @param int    $post_id Optional. Post ID. Default is ID of the global $post.
 * @return string Value for post meta for the given post and given key.
 */
function steel_slides_meta( $key, $post_id = 0 ) {
  $meta = steel_meta( 'slides', $key, $post_id );
  return $meta;
}

/**
 * Display Slideshow by id
 *
 * @param int          $post_id The post ID of the slideshow.
 * @param string|array $size    Optional. Registered image size to retrieve the source for
 *                              or a flat array of height and width dimensions. Default 'full'.
 * @param string       $name    Name of slideshow to use as identifier. Default is $post_id.
 */
function steel_slideshow( $post_id, $size = 'full', $name = '' ) {
  if ( 0 === $post_id ) {
    return;
  }

  $name = empty( $name ) ? $post_id : $name;

  $slides_media      = steel_slides_meta( 'media'     , $post_id );
  $slides_order      = steel_slides_meta( 'order'     , $post_id );
  $slides_media_url  = steel_slides_meta( 'media_url' , $post_id );
  $slides_skin       = steel_slides_meta( 'skin'      , $post_id );
  $slides_transition = steel_slides_meta( 'transition', $post_id );

  $slides_skin       = empty( $slides_skin )       ? 'Default' : $slides_skin;
  $slides_transition = empty( $slides_transition ) ? 'Default' : $slides_transition;

  $slides_class  = 'carousel slide';
  $slides_class .= ' carousel-' . strtolower( $slides_skin );
  $slides_class .= 'Default' !== $slides_transition ? ' carousel-' . strtolower( $slides_transition ) : '' ;

  $slides = explode( ',', $slides_order );

  $output     = '';
  $indicators = '';
  $items      = '';
  $controls   = '';
  $count      = -1;
  $i          = -1;
  $total      = -1;

  foreach ( $slides as $slide ) {
    if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
      $total += 1;
    }
  }

  $col_lg = floor( 12 / max( ( $total + 1 ), 1 ) );
  $rem_lg = 12 - ( $col_lg * ( $total + 1 ) );
  $spc_lg = floor( $rem_lg / 2 );

  if ( 'Gallery' !== $slides_skin ) {
    $carousel_div = '<div id="carousel_'.$name.'" class="'.$slides_class.'" data-ride="carousel">';

    foreach ( $slides as $slide ) {
      if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
        $image   = wp_get_attachment_image_src( $slide, $size );
        $title   = steel_slides_meta( 'title_'   . $slide, $post_id );
        $content = steel_slides_meta( 'content_' . $slide, $post_id );
        $link    = steel_slides_meta( 'link_'    . $slide, $post_id );
        $i += 1;

        $items .= $i >= 1 ? '<div class="item">' : '<div class="item active">';
        $items .= ! empty( $link ) ? '<a href="'.$link.'">' : '';
        $items .= '<img id="slide_img_'.$slide.'" src="'.$image[0].'" alt="'.$title.'">';
        $items .= ! empty( $link ) ? '</a>' : '';

        if ( ! empty( $title ) || ! empty( $content ) ) {
          $items .= '<div class="carousel-caption">';
          if ( 'Bar' !== $slides_skin ) {
            if ( ! empty( $title ) ) { $items .= '<h3 id="slides_title_'.$slide.'">' .$title  .'</h3>'; }
            if ( ! empty( $content ) ) { $items .= '<p class="hidden-xs" id="slides_content_'.$slide.'">'.$content.'</p>' ; }
          } else {
            if ( ! empty( $title ) ) { $items .= '<p id="slides_title_'.$slide.'">' .$title.'</p>'; }
          }
          $items .= '</div>';// .carousel-caption
        }
        $items .= '</div>';// .item
      }
    }
  } else {
    $carousel_div = '<div id="carousel_'.$name.'" class="row carousel-gallery">';

    foreach ( $slides as $slide ) {
      if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
        $count += 1;
        $image  = wp_get_attachment_image_src( $slide, 'full' );
        $title  = steel_slides_meta( 'title_' . $slide, $post_id );
        $link   = steel_slides_meta( 'link_'  . $slide, $post_id );

        $items .= '<span class="col-lg-' . $col_lg . ' col-md-' . $col_lg . '">';
        $items .= ! empty( $link ) ? '<a href="'.$link.'">' : '';
        $items .= '<img id="slide_thumb_' . $slide . '" src="' . $image[0] . '" alt="' . $title . '">';
        $items .= ! empty( $link ) ? '</a>' : '';
        $items .= '</span>';
      }
    }
  }

  if ( empty( $slides_skin ) || 'Default' === $slides_skin ) {
    $indicators .= '<ol class="carousel-indicators">';
    foreach ( $slides as $slide ) {
      if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
        $count += 1;
        $indicators .= $count >= 1 ? '<li data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"></li>' : '<li data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'" class="active"></li>';
      }
    }
    $indicators .= '</ol>';
  } elseif ( 'Tabs' === $slides_skin ) {
    $indicators .= '<ol class="nav nav-tabs carousel-indicators">';
    foreach ( $slides as $slide ) {
      if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
        $count += 1;
        $title   = steel_slides_meta( 'title_'  .$slide, $post_id );
        $indicators .= $count >= 1 ? '<li data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><a href="#carousel_'.$post_id.'">' . $title . '</a></li>' : '<li class="active" data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><a href="#carousel_'.$post_id.'">' . $title . '</a></li>';
      }
    }
    $indicators .= '</ol>';
  } elseif ( 'Thumbnails' === $slides_skin ) {
    $indicators .= '<div class="carousel-thumbs hidden-sm hidden-xs">';
    $indicators .= '<span class="col-lg-'.$spc_lg.' col-md-'.$spc_lg.'"></span>';
    foreach ( $slides as $slide ) {
      if ( ! empty( $slide ) && false !== get_post_status( $slide ) ) {
        $count += 1;
        $image   = wp_get_attachment_image_src( $slide, 'steel-slide-thumb' );
        $title   = steel_slides_meta( 'title_'  .$slide, $post_id );
        $indicators .= $count >= 1 ? '<span class="col-lg-'.$col_lg.' col-md-'.$col_lg.'" data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><img id="slide_thumb_'.$slide.'" src="'.$image[0].'" alt="'.$title.'"></span>' : '<span class="col-lg-'.$col_lg.' col-md-'.$col_lg.'" data-target="#carousel_'.$post_id.'" data-slide-to="'.$count.'"><img id="slide_thumb_'.$slide.'" src="'.$image[0].'" alt="'.$title.'"></span>';
      }
    }
    $indicators .= '<span class="col-lg-'.$spc_lg.' col-md-'.$spc_lg.'"></span>';
    $indicators .= '</div>';
  } elseif ( 'Gallery' === $slides_skin ) {
    $indicators = '';
  }

  $controls .= ( 'Simple' === $slides_skin ) ? '<div class="carousel-controls">' : '';
  $controls .= '<a class="left carousel-control" href="#carousel_'.$post_id.'" data-slide="prev"><span class="icon-prev"></span></a>';
  $controls .= '<a class="right carousel-control" href="#carousel_'.$post_id.'" data-slide="next"><span class="icon-next"></span></a>';
  $controls .= ( 'Simple' === $slides_skin ) ? '</div>' : '';

  $output .= 'Tabs' === $slides_skin ? $indicators : '';
  $output .= $carousel_div;
  $output .= empty( $slides_skin ) || 'Default' === $slides_skin ? $indicators : '';
  $output .= 'Gallery' === $slides_skin ? '' : '<div class="carousel-inner">';
  $output .= $items;
  $output .= 'Gallery' === $slides_skin ? '' : '</div>';
  $output .= 'Gallery' === $slides_skin ? '' : $controls;
  $output .= '</div>';
  $output .= 'Thumbnails' === $slides_skin ? $indicators : '';

  return $output;
}

/**
 * Builds the Slideshow shortcode output.
 *
 * This implements the functionality of the Slideshow Shortcode for displaying
 * a slideshow in a post.
 *
 * @see WordPress 4.3.1 add_shortcode()
 * @see WordPress 4.3.1 wp_video_shortcode()
 *
 * @param array  $attr Attributes of the shortcode.
 * @param string $content Shortcode content.
 * @return string|void HTML content to display slideshow.
 */
function steel_slideshow_shortcode( $attr, $content = '' ) {
  extract( shortcode_atts( array( 'id' => 0, 'name' => '', 'size' => 'full' ), $attr ) );

  if ( ! empty( $id ) ) {
    return steel_slideshow( $id, $size );
  } elseif ( ! empty( $name ) ) {
    $show = get_page_by_title( $name, OBJECT, 'steel_slides' );
    if ( ! empty( $show ) ) {
      return steel_slideshow( $show->ID, $size, $name );
    }
  } else {
    return;
  }
}
add_shortcode( 'steel_slideshow', 'steel_slideshow_shortcode' );

/**
 * Return IDs for all slideshows
 */
function steel_get_slides() {
  $args = array( 'post_type' => 'steel_slides', 'posts_per_page' => -1 );
  $slideshows = get_posts( $args );
  $slides = array();
  $slides[0] = 'None';
  if ( $slideshows ) {
    foreach ( $slideshows as $slideshow ) {
      $post_id = $slideshow->ID;
      $title = $slideshow->post_title;
      $slides[ $post_id ] = $title;
    }
    wp_reset_postdata();
  }
  return $slides;
}

/**
 * Sanitize options based on steel_get_slides
 *
 * @param mixed $input Unfiltered input.
 */
function steel_sanitize_get_slides( $input ) {
  $valid = steel_get_slides( 'options' );

  if ( array_key_exists( $input, $valid ) ) {
    return $input;
  } else {
    return;
  }
}
