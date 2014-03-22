<?php
/**
 * Options pages for various modules
 *
 * @package Steel
 */

/*
 * Add options pages
 */
add_action('admin_menu', 'steel_admin_menu');
function steel_admin_menu() {
  add_menu_page('Steel', 'Steel', 'manage_options', 'steel', 'steel_menu_page', 'none');
  if (is_module_active('marketplace')) {
    add_submenu_page( 'steel', 'Marketplace Options', 'Marketplace', 'manage_options', 'steel_marketplace', 'marketplace_submenu_page' );
  }
}
function steel_menu_page() {
  ?>
  <div class="wrap">
    <h2>Steel Options</h2>
    <form action="options.php" method="post">
      <?php
      settings_fields('steel_options');
      do_settings_sections('steel');
      settings_errors();
      ?>
      <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>
  </div>
  <?php 
}
function marketplace_submenu_page() {
  ?>
  <div class="wrap">
    <h2>Marketplace Options</h2>
    <form action="options.php" method="post">
      <?php
      settings_fields('marketplace_options');
      do_settings_sections('steel_marketplace');
      settings_errors();
      ?>
      <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>
  </div>
  <?php 
}

/*
 * Register settings for options pages
 */
add_action('admin_init', 'steel_admin_init');
function steel_admin_init(){
  $options = get_option('marketplace_options');
  $colors1 = !empty($options['product_colors_1_name']) ? $options['product_colors_1_name'] : 'Color Set 1';
  $colors2 = !empty($options['product_colors_2_name']) ? $options['product_colors_2_name'] : 'Color Set 2';
  
  //Register Steel Options
  register_setting('steel_options', 'steel_options', 'steel_options_validate' );

  add_settings_section('steel_social', 'Social Media', 'steel_social_section', 'steel');

  add_settings_field('fb_app_id', 'Facebook App ID', 'fb_app_id_field', 'steel', 'steel_social' );

  add_settings_section('steel_mods', 'Modules', 'steel_mods_section', 'steel');
    add_settings_field('mod_bootstrap'  , 'Bootstrap'  , 'mod_bootstrap_field'  , 'steel', 'steel_mods' );
    add_settings_field('mod_marketplace', 'Marketplace', 'mod_marketplace_field', 'steel', 'steel_mods' );
  //add_settings_field('mod_podcast'    , 'Podcast'    , 'mod_podcast_field'    , 'steel', 'steel_mods' );
  //add_settings_field('mod_quotes'     , 'Quotes'     , 'mod_quotes_field'     , 'steel', 'steel_mods' );
  //add_settings_field('mod_shortcodes' , 'Shortcodes' , 'mod_shortcodes_field' , 'steel', 'steel_mods' );
    add_settings_field('mod_slides'     , 'Slides'     , 'mod_slides_field'     , 'steel', 'steel_mods' );
    add_settings_field('mod_teams'      , 'Teams'      , 'mod_teams_field'      , 'steel', 'steel_mods' );
  //add_settings_field('mod_widgets'    , 'Widgets'    , 'mod_widgets_field'    , 'steel', 'steel_mods' );
  
  //Register Marketplace Options
  register_setting('marketplace_options', 'marketplace_options', 'marketplace_options_validate' );
      
  add_settings_section('paypal', 'PayPal', 'paypal_section', 'steel_marketplace');
    add_settings_field('paypal_merch_id', 'Merchant ID', 'paypal_merch_id_field', 'steel_marketplace', 'paypal' );

  add_settings_section('product_details', 'Product Details', 'product_details_section', 'steel_marketplace');
    add_settings_field('product_ref'       , 'Reference Number'        , 'product_ref_field'       , 'steel_marketplace', 'product_details' );
    add_settings_field('product_price'     , 'Product Price'           , 'product_price_field'     , 'steel_marketplace', 'product_details' );
    add_settings_field('product_shipping'  , 'Additional shipping cost', 'product_shipping_field'  , 'steel_marketplace', 'product_details' );
    add_settings_field('product_dimensions', 'Dimensions'              , 'product_dimensions_field', 'steel_marketplace', 'product_details' );
    
  add_settings_section('product_options', 'Product Options', 'product_options_section', 'steel_marketplace');
    add_settings_field('product_color_option' , 'Color options'   , 'product_color_option_field' , 'steel_marketplace', 'product_options' );
    add_settings_field('product_colors_1_name', 'Color Set 1 Name', 'product_colors_1_name_field', 'steel_marketplace', 'product_options' );
    add_settings_field('product_colors_1'     , $colors1          , 'product_colors_1_field'     , 'steel_marketplace', 'product_options' );
    add_settings_field('product_colors_2_name', 'Color Set 2 Name', 'product_colors_2_name_field', 'steel_marketplace', 'product_options' );
    add_settings_field('product_colors_2'     , $colors2          , 'product_colors_2_field'     , 'steel_marketplace', 'product_options' );
}

/*
 * Callback settings for Sparks Options page
 */
function steel_social_section() { echo 'Social media profile information'; }
function fb_app_id_field() {
  $options = get_option('steel_options');

  $output  = '<input id="fb_app_id" name="steel_options[fb_app_id]" size="40" type="text" value="';
  $output .= !empty($options["fb_app_id"]) ? $options["fb_app_id"] : '';
  $output .= '">';
  echo $output;
}
function steel_mods_section() { echo 'Activate and deactivate modules within Steel'; }
function mod_bootstrap_field() {
  $options = get_option('steel_options');

  $bootstrap = !empty($options['mod_bootstrap']) ? $options['mod_bootstrap'] : 'both';
  steel_options('mod_bootstrap'); ?>
  
  <div class="radio-group">
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="css"  <?php checked( $bootstrap, 'css'  ) ?>>CSS&nbsp;&nbsp;</label>
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="js"   <?php checked( $bootstrap, 'js'   ) ?>>Javascript</label><br>
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="both" <?php checked( $bootstrap, 'both' ) ?>>Both</label>
    <label for="steel_options[mod_bootstrap]"><input name="steel_options[mod_bootstrap]" type="radio" value="none" <?php checked( $bootstrap, 'none' ) ?>>None</label>
  </div>

  <?php
}
function mod_marketplace_field() {
  $options = get_option('steel_options');

  $marketplace = !empty($options['mod_marketplace']) ? $options['mod_marketplace'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_marketplace]"><input name="steel_options[mod_marketplace]" type="radio" value="true"  <?php checked( $marketplace, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_marketplace]"><input name="steel_options[mod_marketplace]" type="radio" value="false" <?php checked( $marketplace, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_podcast_field() {
  $options = get_option('steel_options');

  $podcast = !empty($options['mod_podcast']) ? $options['mod_podcast'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_podcast]"><input name="steel_options[mod_podcast]" type="radio" value="true"  <?php checked( $podcast, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_podcast]"><input name="steel_options[mod_podcast]" type="radio" value="false" <?php checked( $podcast, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_quotes_field() {
  $options = get_option('steel_options');

  $quotes = !empty($options['mod_quotes']) ? $options['mod_quotes'] : 'true'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_quotes]"><input name="steel_options[mod_quotes]" type="radio" value="true"  <?php checked( $quotes, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_quotes]"><input name="steel_options[mod_quotes]" type="radio" value="false" <?php checked( $quotes, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_shortcodes_field() {
  $options = get_option('steel_options');

  $shortcodes = !empty($options['mod_shortcodes']) ? $options['mod_shortcodes'] : 'true'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_shortcodes]"><input name="steel_options[mod_shortcodes]" type="radio" value="true"  <?php checked( $shortcodes, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_shortcodes]"><input name="steel_options[mod_shortcodes]" type="radio" value="false" <?php checked( $shortcodes, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_slides_field() {
  $options = get_option('steel_options');

  $slides = !empty($options['mod_slides']) ? $options['mod_slides'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_slides]"><input name="steel_options[mod_slides]" type="radio" value="true"  <?php checked( $slides, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_slides]"><input name="steel_options[mod_slides]" type="radio" value="false" <?php checked( $slides, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}
function mod_teams_field() {
  $options = get_option('steel_options');

  $teams = !empty($options['mod_teams']) ? $options['mod_teams'] : 'false'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_teams]"><input name="steel_options[mod_teams]" type="radio" value="true"  <?php checked( $teams, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_teams]"><input name="steel_options[mod_teams]" type="radio" value="false" <?php checked( $teams, 'false' ) ?>>Not Active</label>
  <div class="radio-group">
  <?php
}
function mod_widgets_field() {
  $options = get_option('steel_options');

  $widgets = !empty($options['mod_widgets']) ? $options['mod_widgets'] : 'true'; ?>

  <div class="radio-group">
    <label for="steel_options[mod_widgets]"><input name="steel_options[mod_widgets]" type="radio" value="true"  <?php checked( $widgets, 'true'  ) ?>>Active</label>
    <label for="steel_options[mod_widgets]"><input name="steel_options[mod_widgets]" type="radio" value="false" <?php checked( $widgets, 'false' ) ?>>Not Active</label>
  </div>
  <?php
}

/*
 * Callback settings for Marketplace Options page
 */
function paypal_section() { echo ''; }
function paypal_merch_id_field() {
  $options = get_option('marketplace_options');

  $output  = '<input id="paypal_merch_id" name="marketplace_options[paypal_merch_id]" size="40" type="text" value="';
  $output .= !empty($options["paypal_merch_id"]) ? $options["paypal_merch_id"] : '';
  $output .= '">';
  echo $output;
}
function product_details_section() { echo 'Select the details you would like to be able to define within the product administration screen'; }
function product_ref_field() {
  $options = get_option('marketplace_options');

  $details = !empty($options['product_ref']) ? $options['product_ref'] : 'true'; ?>

  <div class="radio-group">
    <label for="marketplace_options[product_ref]"><input name="marketplace_options[product_ref]" type="radio" value="true"  <?php checked( $details, 'true'  ) ?>>Show</label>
    <label for="marketplace_options[product_ref]"><input name="marketplace_options[product_ref]" type="radio" value="false" <?php checked( $details, 'false' ) ?>>Hide</label>
  </div>
  <?php
}
function product_price_field() {
  $options = get_option('marketplace_options');

  $details = !empty($options['product_price']) ? $options['product_price'] : 'true'; ?>

  <div class="radio-group">
    <label for="marketplace_options[product_price]"><input name="marketplace_options[product_price]" type="radio" value="true"  <?php checked( $details, 'true'  ) ?>>Show</label>
    <label for="marketplace_options[product_price]"><input name="marketplace_options[product_price]" type="radio" value="false" <?php checked( $details, 'false' ) ?>>Hide</label>
  </div>
  <?php
}
function product_shipping_field() {
  $options = get_option('marketplace_options');

  $details = !empty($options['product_shipping']) ? $options['product_shipping'] : 'true'; ?>

  <div class="radio-group">
    <label for="marketplace_options[product_shipping]"><input name="marketplace_options[product_shipping]" type="radio" value="true"  <?php checked( $details, 'true'  ) ?>>Show</label>
    <label for="marketplace_options[product_shipping]"><input name="marketplace_options[product_shipping]" type="radio" value="false" <?php checked( $details, 'false' ) ?>>Hide</label>
  </div>
  <?php
}
function product_dimensions_field() {
  $options = get_option('marketplace_options');

  $details = !empty($options['product_dimensions']) ? $options['product_dimensions'] : 'false'; ?>

  <div class="radio-group">
    <label for="marketplace_options[product_dimensions]"><input name="marketplace_options[product_dimensions]" type="radio" value="true"  <?php checked( $details, 'true'  ) ?>>Show</label>
    <label for="marketplace_options[product_dimensions]"><input name="marketplace_options[product_dimensions]" type="radio" value="false" <?php checked( $details, 'false' ) ?>>Hide</label>
  </div>
  <?php
}
function product_options_section() { echo 'Define global options for all products that have no cost variance'; }
function product_color_option_field() {
  $options = get_option('marketplace_options');

  $details = !empty($options['product_color_option']) ? $options['product_color_option'] : '0'; ?>

  <div class="radio-group">
    <label for="marketplace_options[product_color_option]"><input name="marketplace_options[product_color_option]" type="radio" value="0" <?php checked( $details, '0' ) ?>>Hide</label>
    <label for="marketplace_options[product_color_option]"><input name="marketplace_options[product_color_option]" type="radio" value="1" <?php checked( $details, '1' ) ?>>One color</label>
    <label for="marketplace_options[product_color_option]"><input name="marketplace_options[product_color_option]" type="radio" value="2" <?php checked( $details, '2' ) ?>>Two colors</label>
  </div>
  <?php
}
function product_colors_1_name_field() {
  $options = get_option('marketplace_options');

  $output  = '<label for="marketplace_options[product_colors_1_name]">';
  $output .= '<input id="product_colors_1_name" name="marketplace_options[product_colors_1_name]" size="40" type="text" value="';
  $output .= !empty($options["product_colors_1_name"]) ? $options["product_colors_1_name"] : '';
  $output .= '">';
  $output .= ' i.e. Base color</label>';
  echo $output;
}
function product_colors_1_field() {
  $options = get_option('marketplace_options');

  $output  = '<label for="marketplace_options[product_colors_1]">';
  $output .= '<input id="product_colors_1" name="marketplace_options[product_colors_1]" size="40" type="text" value="';
  $output .= !empty($options["product_colors_1"]) ? $options["product_colors_1"] : '';
  $output .= '">';
  $output .= ' Seperate with commas</label>';
  echo $output;
}
function product_colors_2_name_field() {
  $options = get_option('marketplace_options');

  $output  = '<label for="marketplace_options[product_colors_2_name]">';
  $output .= '<input id="product_colors_2_name" name="marketplace_options[product_colors_2_name]" size="40" type="text" value="';
  $output .= !empty($options["product_colors_2_name"]) ? $options["product_colors_2_name"] : '';
  $output .= '">';
  $output .= ' i.e. Accent color</label>';
  echo $output;
}
function product_colors_2_field() {
  $options = get_option('marketplace_options');

  $output  = '<label for="marketplace_options[product_colors_2]">';
  $output .= '<input id="product_colors_2" name="marketplace_options[product_colors_2]" size="40" type="text" value="';
  $output .= !empty($options["product_colors_2"]) ? $options["product_colors_2"] : '';
  $output .= '">';
  $output .= ' Seperate with commas</label>';
  echo $output;
}

/*
 * Validate settings for Steel Options page
 */
function steel_options_validate($input) {
  global $newinput;

  $newinput['fb_app_id'] = trim($input['fb_app_id']);
  if (!preg_match('/^[0-9]{15}$/i', $newinput['fb_app_id']) & !empty($newinput['fb_app_id'])) { add_settings_error( 'fb_app_id', 'invalid', 'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>' ); }

    $newinput['mod_bootstrap'  ] = trim($input['mod_bootstrap'  ]);
    $newinput['mod_marketplace'] = trim($input['mod_marketplace']);
  //$newinput['mod_podcast'    ] = trim($input['mod_podcast'    ]);
  //$newinput['mod_quotes'     ] = trim($input['mod_quotes'     ]);
  //$newinput['mod_shortcodes' ] = trim($input['mod_shortcodes' ]);
    $newinput['mod_slides'     ] = trim($input['mod_slides'     ]);
    $newinput['mod_teams'      ] = trim($input['mod_teams'      ]);
  //$newinput['mod_widgets'    ] = trim($input['mod_widgets'    ]);

  return $newinput;
}

/*
 * Validate settings for Marketplace Options page
 */
function marketplace_options_validate($input) {
  global $newinput;

    $newinput['paypal_merch_id'] = trim($input['paypal_merch_id']);
    if(!preg_match('/^[a-z0-9]{13}$/i', $newinput['paypal_merch_id']) & !empty($newinput['paypal_merch_id'])) { add_settings_error( 'paypal_merch_id', 'invalid', 'Invalid PayPal Merchant ID. <span style="font-weight:normal;display:block;">A PayPal Merchant ID consists of 13 alphanumeric characters.</span>' ); }
    $newinput['paypal_merch_id'] = trim($input['paypal_merch_id']);
    
    $newinput['product_ref'          ] = trim($input['product_ref'          ]);
    $newinput['product_price'        ] = trim($input['product_price'        ]);
    $newinput['product_shipping'     ] = trim($input['product_shipping'     ]);
    $newinput['product_dimensions'   ] = trim($input['product_dimensions'   ]);
    $newinput['product_color_option' ] = trim($input['product_color_option' ]);
    $newinput['product_colors_1_name'] = trim($input['product_colors_1_name']);
    $newinput['product_colors_1'     ] = trim($input['product_colors_1'     ]);
    $newinput['product_colors_2_name'] = trim($input['product_colors_2_name']);
    $newinput['product_colors_2'     ] = trim($input['product_colors_2'     ]);

  return $newinput;
}

/*
 * Add function steel_options
 */
function steel_options( $key ) {
  $options = get_option('steel_options');
  if (empty($options[ $key ])) :
    return false;
  else :
    return $options[ $key ];
  endif;
}

/*
 * Add function marketplace_options
 */
function marketplace_options( $key ) {
  $options = get_option('marketplace_options');
  if (empty($options[ $key ])) :
    return false;
  else :
    return $options[ $key ];
  endif;
}
?>
