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

/*
 * Register settings for options pages
 */
add_action('admin_init', 'steel_admin_init');
function steel_admin_init(){

  //Register Steel Options
  register_setting('steel_options', 'steel_options', 'steel_options_validate' );

  add_settings_section('steel_social', 'Social Media', 'steel_social_section', 'steel');

  add_settings_field('fb_app_id', 'Facebook App ID', 'fb_app_id_field', 'steel', 'steel_social' );

  add_settings_section('steel_mods', 'Modules', 'steel_mods_section', 'steel');
    add_settings_field('load_bootstrap'  , 'Bootstrap'  , 'load_bootstrap_field'   , 'steel', 'steel_mods' );
  //add_settings_field('load_podcast_mod', 'Podcast'    , 'load_podcast_load_field', 'steel', 'steel_mods' );
  //add_settings_field('load_quotes'     , 'Quotes'     , 'load_quotes_field'      , 'steel', 'steel_mods' );
  //add_settings_field('load_shortcodes' , 'Shortcodes' , 'load_shortcodes_field'  , 'steel', 'steel_mods' );
    add_settings_field('load_slides'     , 'Slides'     , 'load_slides_field'      , 'steel', 'steel_mods' );
    add_settings_field('load_teams'      , 'Teams'      , 'load_teams_field'       , 'steel', 'steel_mods' );
  //add_settings_field('load_widgets'    , 'Widgets'    , 'load_widgets_field'     , 'steel', 'steel_mods' );
}

/*
 * Callback settings for Sparks Options page
 */
function steel_social_section() { echo 'Social media profile information'; }
function fb_app_id_field() {
  $options = steel_get_options();

  $output  = '<input id="fb_app_id" name="steel_options[fb_app_id]" size="40" type="text" value="' . $options['fb_app_id'] . '">';
  echo $output;
}
function steel_mods_section() { echo 'Select which modules should be active.'; }
function load_bootstrap_field() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_bootstrap_css]"><input name="steel_options[load_bootstrap_css]" type="checkbox" value="true"  <?php checked( $options['load_bootstrap_css'], true  ) ?>>Load CSS&nbsp;&nbsp;</label>
    <label for="steel_options[load_bootstrap_js]"><input name="steel_options[load_bootstrap_js]" type="checkbox" value="true"   <?php checked( $options['load_bootstrap_js'], true   ) ?>>Load Javascript</label><br>
  </div>

  <?php
}
function load_podcast_load_field() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_podcast_mod]"><input name="steel_options[load_podcast_mod]" type="checkbox" value="true"  <?php checked( $options['load_podcast_mod'], true  ) ?>>Active</label>
  </div>
  <?php
}
function load_quotes_field() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_quotes]"><input name="steel_options[load_quotes]" type="checkbox" value="true"  <?php checked( $options['load_quotes'], true  ) ?>>Active</label>
  </div>
  <?php
}
function load_shortcodes_field() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_shortcodes]"><input name="steel_options[load_shortcodes]" type="checkbox" value="true"  <?php checked( $options['load_shortcodes'], true  ) ?>>Active</label>
  </div>
  <?php
}
function load_slides_field() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_slides]"><input name="steel_options[load_slides]" type="checkbox" value="true"  <?php checked( $options['load_slides'], true  ) ?>>Active</label>
  </div>
  <?php
}
function load_teams_field() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_teams]"><input name="steel_options[load_teams]" type="checkbox" value="true"  <?php checked( $options['load_teams'], true  ) ?>>Active</label>
  <div class="radio-group">
  <?php
}
function load_widgets_field() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_widgets]"><input name="steel_options[load_widgets]" type="checkbox" value="true"  <?php checked( $options['load_widgets'], true  ) ?>>Active</label>
  </div>
  <?php
}

/*
 * Validate settings for Steel Options page
 */
function steel_options_validate($raw) {
  $valid['fb_app_id'] = trim($raw['fb_app_id']);
  if (!preg_match('/^[0-9]{15}$/i', $valid['fb_app_id']) & !empty($valid['fb_app_id'])) { add_settings_error( 'fb_app_id', 'invalid', 'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>' ); }

  $valid['load_bootstrap_css'] = $raw['load_bootstrap_css'] == 'true' ? true : false;
  $valid['load_bootstrap_js' ] = $raw['load_bootstrap_js' ] == 'true' ? true : false;

  //$valid['load_podcast_mod'] = $raw['load_podcast_mod'] == 'true' ? true : false;
  //$valid['load_quotes'     ] = $raw['load_quotes'     ] == 'true' ? true : false;
  //$valid['load_shortcodes' ] = $raw['load_shortcodes' ] == 'true' ? true : false;
    $valid['load_slides'     ] = $raw['load_slides'     ] == 'true' ? true : false;
    $valid['load_teams'      ] = $raw['load_teams'      ] == 'true' ? true : false;
  //$valid['load_widgets'    ] = $raw['load_widgets'    ] == 'true' ? true : false;

  return apply_filters( 'steel_save_options', $valid, $raw );
}

function steel_get_option_defaults() {
  $defaults = array(
    'load_bootstrap_css' => true,
    'load_bootstrap_js'  => true,

    'load_facebook'      => true,
    'load_twitter'       => false,
    'load_pinterest'     => false,
    'load_linkedin'      => false,

    'fb_app_id'          => '',

    'load_podcast'       => false,
    'load_quotes'        => true,
    'load_shortcodes'    => true,
    'load_slides'        => false,
    'load_teams'         => false,
    'load_widgets'       => true,
  );

  //BEGIN - backwards compatibility
  $options = get_option( 'steel_options' );

  if (!empty($options['mod_bootstrap'])) {
    if ($options['mod_bootstrap'] == 'css' || $options['mod_bootstrap'] == 'both' )
      $defaults['load_bootstrap_css'] = true;
    if ($options['mod_bootstrap'] == 'js' || $options['mod_bootstrap'] == 'both' )
      $defaults['load_bootstrap_js'] = true;
  }

  if (!empty($options['mod_podcast'])) {
    if ($options['mod_podcast'] == 'true')
      $defaults['load_podcast_mod'] = true;
    elseif ($options['mod_podcast'] == 'false')
      $defaults['load_podcast_mod'] = false;
  }

  if (!empty($options['mod_quotes'])) {
    if ($options['mod_quotes'] == 'true')
      $defaults['load_quotes'] = true;
    elseif ($options['mod_quotes'] == 'false')
      $defaults['load_quotes'] = false;
  }

  if (!empty($options['mod_shortcodes'])) {
    if ($options['mod_shortcodes'] == 'true')
      $defaults['load_shortcodes'] = true;
    elseif ($options['mod_shortcodes'] == 'false')
      $defaults['load_shortcodes'] = false;
  }

  if (!empty($options['mod_slides'])) {
    if ($options['mod_slides'] == 'true')
      $defaults['load_slides'] = true;
    elseif ($options['mod_slides'] == 'false')
      $defaults['load_slides'] = false;
  }

  if (!empty($options['mod_teams'])) {
    if ($options['mod_teams'] == 'true')
      $defaults['load_teams'] = true;
    elseif ($options['mod_teams'] == 'false')
      $defaults['load_teams'] = false;
  }

  if (!empty($options['mod_widgets'])) {
    if ($options['mod_widgets'] == 'true')
      $defaults['load_widgets'] = true;
    elseif ($options['mod_widgets'] == 'false')
      $defaults['load_widgets'] = false;
  }
  //END - backwards compatibility

  return apply_filters('steel_option_defaults', $defaults);
}

/**
 * Gets array of plugin options
 */
function steel_get_options() {
  $defaults = steel_get_option_defaults();

  $steel_options = wp_parse_args( get_option( 'steel_options' ), $defaults );

  if (!empty($option)) {
    return $steel_options[$option];
  }
  else {
    return $steel_options;
  }
}
