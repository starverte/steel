<?php
/**
 * This module allows you to easily create an ecommerce part of your WordPress site
 *
 * @package Steel/Marketplace
 * @TODO: Remove backwards compatibility with Sparks Store in Steel 1.4
 */

add_action( 'init', 'steel_marketplace_init', 0 );
function steel_marketplace_init() {
  $labels = array(
    'name'                => _x( 'Products', 'Post Type General Name', 'steel' ),
    'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'steel' ),
    'menu_name'           => __( 'Marketplace', 'steel' ),
    'all_items'           => __( 'All Products', 'steel' ),
    'product_view_item'           => __( 'View Product', 'steel' ),
    'add_new_item'        => __( 'Add New Product', 'steel' ),
    'add_new'             => __( 'New Product', 'steel' ),
    'edit_item'           => __( 'Edit Product', 'steel' ),
    'update_item'         => __( 'Update Product', 'steel' ),
    'search_items'        => __( 'Search products', 'steel' ),
    'not_found'           => __( 'No products found', 'steel' ),
    'not_found_in_trash'  => __( 'No products found in trash. Did you check recycling?', 'steel' ),
  );

  $rewrite = array(
    'slug'                => 'products',
    'with_front'          => true,
    'pages'               => false,
    'feeds'               => false,
  );

  $args = array(
    'label'               => __( 'steel_product', 'steel' ),
    'description'         => __( 'Products in a Marketplace', 'steel' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-cart',
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'page',
  );

  register_post_type( 'steel_product', $args );

  $labels2 = array(
    'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'steel' ),
    'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'steel' ),
    'menu_name'                  => __( 'Product Categories', 'steel' ),
    'all_items'                  => __( 'All Product Categories', 'steel' ),
    'parent_item'                => __( '', 'steel' ),
    'parent_item_colon'          => __( '', 'steel' ),
    'new_item_name'              => __( 'New Product Category Name', 'steel' ),
    'add_new_item'               => __( 'Add New Product Category', 'steel' ),
    'edit_item'                  => __( 'Edit Product Category', 'steel' ),
    'update_item'                => __( 'Update Product Category', 'steel' ),
    'separate_items_with_commas' => __( 'Separate categories with commas', 'steel' ),
    'search_items'               => __( 'Search categories', 'steel' ),
    'add_or_remove_items'        => __( 'Add or remove categories', 'steel' ),
    'choose_from_most_used'      => __( 'Choose from the most used categories', 'steel' ),
  );

  $rewrite2 = array(
    'slug'                       => 'browse',
    'with_front'                 => true,
    'hierarchical'               => true,
  );

  $args2 = array(
    'labels'                     => $labels2,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => false,
    'rewrite'                    => $rewrite2,
  );

  register_taxonomy( 'steel_product_category', 'steel_product', $args2 );
  register_taxonomy_for_object_type( 'post_tag', 'steel_product' );

  add_image_size( 'steel-product-view-thumb', 250, 155, true);
}

/*
 * Create custom meta boxes
 */
add_action( 'add_meta_boxes', 'steel_product_meta_boxes' );
function steel_product_meta_boxes() {
  add_meta_box('steel_product_details', 'Product Details', 'steel_product_details', 'steel_product', 'side'        );
  add_meta_box('steel_product_view_meta'  , 'Product Views'  , 'steel_product_view_meta'  , 'steel_product', 'side', 'high');
}
function steel_product_details() { ?>

  <p class="product_ref"><span class="form-addon-left">Ref #</span><input type="text" size="18" name="product_ref" placeholder="Ref #" value="<?php echo steel_product_meta ('ref'); ?>" /></p>
  <p class="product_price">
    <label>Base price</label><br />
    <span class="form-addon-left">$</span><input type="text" size="21" name="product_price" placeholder="Price" value="<?php echo steel_product_meta ('price'); ?>" />
  </p>
  <p class="product_shipping">
    <label>Additional shipping cost</label><br />
    <span class="form-addon-left">$</span><input type="text" size="21" name="product_shipping" value="<?php echo steel_product_meta('shipping'); ?>" />
  </p>

  <p class="product_dimensions">
    <label>Dimensions</label><br />
    <input type="text" size="5" name="product_width" placeholder="Width" value="<?php echo steel_product_meta('width'); ?>" /> x
    <input type="text" size="5" name="product_height" placeholder="Height" value="<?php echo steel_product_meta('height'); ?>" /> x
    <input type="text" size="5" name="product_depth" placeholder="Depth" value="<?php echo steel_product_meta('depth'); ?>" />
  </p>
  
<?php
}
function steel_product_view_meta() {
  global $post;
  $product_view_order = steel_product_meta( 'view_order' );
  
  //Backwards compatibility for Sparks Store
  if (has_post_thumbnail()) {
    $thumb_id = get_post_thumbnail_id();
    $product_view_order .= ','.$thumb_id;
    update_post_meta($post->ID, 'product_view_order'   , $product_view_order);
    delete_post_meta($post->ID, '_thumbnail_id');
  }

  $product_views = explode(',', $product_view_order);

  $output = '';
  $output .= '<a href="#" class="button add_product_view_media" id="btn_above" title="Add product_view to product_viewhow"><span class="steel-icon-cover-photo"></span> New product view</a>';
  $output .= '<div id="product_view">';
  foreach ($product_views as $product_view) {
    if (!empty($product_view)) {
      $image = wp_get_attachment_image_src( $product_view, 'steel-product-view-thumb' );
      $output .= '<div class="product-view" id="';
      $output .= $product_view;
      $output .= '">';
      $output .= '<div class="product-view-controls"><span id="controls_'.$product_view.'">'.steel_product_meta( 'view_title_'.$product_view ).'</span><a class="del-product-view" href="#" onclick="deleteView(\''.$product_view.'\')" title="Delete product view"><span class="steel-icon-dismiss" style="float:right"></span></a></div>';
      $output .= '<img id="product_view_img_'.$product_view.'" src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'">';
      $output .= '<span class="steel-icon-cover-photo" style="float:left;padding:5px;"></span><input class="product-view-title" type="text" size="23" name="product_view_title_';
      $output .= $product_view;
      $output .= '" id="product_view_title_'.$product_view.'" value="'.steel_product_meta( 'view_title_'.$product_view ).'" placeholder="Title (i.e. Front)" style="margin:0;" />';
      $output .= '</div>';
    }
  }
  $output .= '</div>';

  echo $output; ?>

  <input type="hidden" name="product_view_order" id="product_view_order" value="<?php echo $product_view_order; ?>">
  <div style="float:none; clear:both;"></div><?php
}

/*
 * Save data from meta boxes
 */
add_action('save_post', 'save_steel_product');
function save_steel_product() {
  global $post;
  if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && (isset($post_id))) { return $post_id; }
  if(defined('DOING_AJAX') && DOING_AJAX && (isset($post_id))) { return $post_id; } //Prevents the metaboxes from being overwritten while quick editing.
  if(preg_match('/\edit\.php/', $_SERVER['REQUEST_URI']) && (isset($post_id))) { return $post_id; } //Detects if the save action is coming from a quick edit/batch edit.
  if (isset($_POST['product_ref'     ])) { update_post_meta($post->ID, 'product_ref'     , $_POST['product_ref'     ]); }
  if (isset($_POST['product_price'   ])) { update_post_meta($post->ID, 'product_price'   , $_POST['product_price'   ]); }
  if (isset($_POST['product_shipping'])) { update_post_meta($post->ID, 'product_shipping', $_POST['product_shipping']); }
  if (isset($_POST['product_width'   ])) { update_post_meta($post->ID, 'product_width'   , $_POST['product_width'   ]); }
  if (isset($_POST['product_height'  ])) { update_post_meta($post->ID, 'product_height'  , $_POST['product_height'  ]); }
  if (isset($_POST['product_depth'   ])) { update_post_meta($post->ID, 'product_depth'   , $_POST['product_depth'   ]); }

  if (isset($_POST['product_view_order']   )) {
    update_post_meta($post->ID, 'product_view_order'   , $_POST['product_view_order']);
    $product_views = explode(',', get_post_meta($post->ID, 'product_view_order', true));
    foreach ($product_views as $product_view) {
      if (isset($_POST['product_view_title_'   . $product_view])) { update_post_meta($post->ID, 'product_view_title_'  . $product_view, $_POST['product_view_title_'   . $product_view]); }
    }
  }
}


/*
 * Display Product metadata
 */
function steel_product_meta( $key, $post_id = NULL ) {
  $meta = steel_meta( 'product', $key, $post_id );
  return $meta;
}

function steel_product_dimensions( $args = array(), $sep = ' x ' ) {
  $defaults = array (
    'sep1' => $sep,
    'sep2' => $sep,
    'dimensions' => 3,
    'unit' => ' in',
  );
  $args = wp_parse_args($args, $defaults);
  $args = (object) $args;

  $width  = steel_product_meta('width' );
  $height = steel_product_meta('height');
  $depth  = steel_product_meta('width' );

  if ( $dimensions = 3 && !empty($width) && !empty($height) && !empty($depth)) { printf( $product_width . $args->unit . $args->sep1 . $product_height . $args->unit . $args->sep2 . $product_depth . $args->unit ); }
    elseif ( !empty($width) && !empty($height) ) { printf( $product_width . $args->unit . $args->sep1 . $product_height . $args->unit ); }
}
?>
