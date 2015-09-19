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

  add_settings_section('steel_analytics', 'Website Analytics', 'steel_analytics_section', 'steel');

  add_settings_field('ga_id', 'Google Analytics Property ID', 'steel_settings_field_ga_id', 'steel', 'steel_analytics' );

  add_settings_section('steel_social', 'Social Media', 'steel_social_section', 'steel');

  add_settings_field('fb_app_id', 'Facebook App ID', 'steel_settings_field_fb_app_id', 'steel', 'steel_social' );

  add_settings_section('steel_mods', 'Modules', 'steel_mods_section', 'steel');
    add_settings_field('load_bootstrap'   , 'Bootstrap'   , 'steel_settings_field_bootstrap'   , 'steel', 'steel_mods' );
  //add_settings_field('load_podcast_mod' , 'Podcast'     , 'steel_settings_field_podcast'     , 'steel', 'steel_mods' );
  //add_settings_field('load_quotes'      , 'Quotes'      , 'steel_settings_field_quotes'      , 'steel', 'steel_mods' );
  //add_settings_field('load_shortcodes'  , 'Shortcodes'  , 'steel_settings_field_shortcodes'  , 'steel', 'steel_mods' );
  //add_settings_field('load_social_media', 'Social Media', 'steel_settings_field_social_media', 'steel', 'steel_mods' );
    add_settings_field('load_slides'      , 'Slides'      , 'steel_settings_field_slides'      , 'steel', 'steel_mods' );
    add_settings_field('load_teams'       , 'Teams'       , 'steel_settings_field_teams'       , 'steel', 'steel_mods' );
  //add_settings_field('load_widgets'     , 'Widgets'     , 'steel_settings_field_widgets'     , 'steel', 'steel_mods' );
}

/*
 * Callback settings for Steel Options page
 */
function steel_analytics_section() {}
function steel_settings_field_ga_id() {
  $options = steel_get_options();

  $output  = '<input id="ga_id" name="steel_options[ga_id]" size="40" type="text" value="' . $options['ga_id'] . '" placeholder="UA-XXXXX-X">';
  echo $output;
}
function steel_social_section() { echo 'Social media profile information'; }
function steel_settings_field_fb_app_id() {
  $options = steel_get_options();

  $output  = '<input id="fb_app_id" name="steel_options[fb_app_id]" size="40" type="text" value="' . $options['fb_app_id'] . '">';
  echo $output;
}
function steel_mods_section() { echo 'Select which modules should be active.'; }
function steel_settings_field_bootstrap() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_bootstrap_css]"><input name="steel_options[load_bootstrap_css]" type="checkbox" value="true"  <?php checked( $options['load_bootstrap_css'], true  ) ?>>Load CSS&nbsp;&nbsp;</label>
    <label for="steel_options[load_bootstrap_js]"><input name="steel_options[load_bootstrap_js]" type="checkbox" value="true"   <?php checked( $options['load_bootstrap_js'], true   ) ?>>Load Javascript</label><br>
  </div>

  <?php
}
function steel_settings_field_podcast() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_podcast_mod]"><input name="steel_options[load_podcast_mod]" type="checkbox" value="true"  <?php checked( $options['load_podcast_mod'], true  ) ?>>Active</label>
  </div>
  <?php
}
function steel_settings_field_quotes() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_quotes]"><input name="steel_options[load_quotes]" type="checkbox" value="true"  <?php checked( $options['load_quotes'], true  ) ?>>Active</label>
  </div>
  <?php
}
function steel_settings_field_shortcodes() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_shortcodes]"><input name="steel_options[load_shortcodes]" type="checkbox" value="true"  <?php checked( $options['load_shortcodes'], true  ) ?>>Active</label>
  </div>
  <?php
}
function steel_settings_field_social_media() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_social_media]"><input name="steel_options[load_social_media]" type="checkbox" value="true"  <?php checked( $options['load_social_media'], true  ) ?>>Active</label>
  </div>
  <?php
}
function steel_settings_field_slides() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_slides]"><input name="steel_options[load_slides]" type="checkbox" value="true"  <?php checked( $options['load_slides'], true  ) ?>>Active</label>
  </div>
  <?php
}
function steel_settings_field_teams() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_teams]"><input name="steel_options[load_teams]" type="checkbox" value="true"  <?php checked( $options['load_teams'], true  ) ?>>Active</label>
  <div class="radio-group">
  <?php
}
function steel_settings_field_widgets() {
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
  $valid['ga_id'] = trim($raw['ga_id']);
  if (!preg_match('/^UA-\d{4,}-\d+$/', $valid['ga_id']) & !empty($valid['ga_id'])) { add_settings_error( 'ga_id', 'invalid', 'Invalid Google Analytics Property ID. <span style="font-weight:normal;display:block;">A Google Analtyics Property ID is in the format UA-########-#.</span>' ); }

  $valid['fb_app_id'] = trim($raw['fb_app_id']);
  if (!preg_match('/^[0-9]{15}$/i', $valid['fb_app_id']) & !empty($valid['fb_app_id'])) { add_settings_error( 'fb_app_id', 'invalid', 'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>' ); }

  $valid['load_bootstrap_css'] = 'true' === $raw['load_bootstrap_css'] ? true : false;
  $valid['load_bootstrap_js' ] = 'true' === $raw['load_bootstrap_js' ] ? true : false;

  //$valid['load_podcast_mod' ] = 'true' === $raw['load_podcast_mod' ] ? true : false;
  //$valid['load_quotes'      ] = 'true' === $raw['load_quotes'      ] ? true : false;
  //$valid['load_shortcodes'  ] = 'true' === $raw['load_shortcodes'  ] ? true : false;
  //$valid['load_social_media'] = 'true' === $raw['load_social_media'] ? true : false;
    $valid['load_slides'      ] = 'true' === $raw['load_slides'      ] ? true : false;
    $valid['load_teams'       ] = 'true' === $raw['load_teams'       ] ? true : false;
  //$valid['load_widgets'     ] = 'true' === $raw['load_widgets'     ] ? true : false;

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

    'ga_id'              => '',

    'fb_app_id'          => '',

    'load_podcast'       => false,
    'load_quotes'        => true,
    'load_shortcodes'    => true,
    'load_social_media'  => true,
    'load_slides'        => false,
    'load_teams'         => false,
    'load_widgets'       => true,
  );

  //BEGIN - backwards compatibility
  $options = get_option( 'steel_options' );

  if (!empty($options['mod_bootstrap'])) {
    if ('css' === $options['mod_bootstrap'] || 'both' === $options['mod_bootstrap'] )
      $defaults['load_bootstrap_css'] = true;
    if ('js' === $options['mod_bootstrap'] || 'both' === $options['mod_bootstrap'] )
      $defaults['load_bootstrap_js'] = true;
  }

  if (!empty($options['mod_podcast'])) {
    if ('true' === $options['mod_podcast'])
      $defaults['load_podcast_mod'] = true;
    elseif ('false' === $options['mod_podcast'])
      $defaults['load_podcast_mod'] = false;
  }

  if (!empty($options['mod_quotes'])) {
    if ('true' === $options['mod_quotes'])
      $defaults['load_quotes'] = true;
    elseif ('false' === $options['mod_quotes'])
      $defaults['load_quotes'] = false;
  }

  if (!empty($options['mod_shortcodes'])) {
    if ('true' === $options['mod_shortcodes'])
      $defaults['load_shortcodes'] = true;
    elseif ('false' === $options['mod_shortcodes'])
      $defaults['load_shortcodes'] = false;
  }

  if (!empty($options['mod_slides'])) {
    if ('true' === $options['mod_slides'])
      $defaults['load_slides'] = true;
    elseif ('false' === $options['mod_slides'])
      $defaults['load_slides'] = false;
  }

  if (!empty($options['mod_teams'])) {
    if ('true' === $options['mod_teams'])
      $defaults['load_teams'] = true;
    elseif ('false' === $options['mod_teams'])
      $defaults['load_teams'] = false;
  }

  if (!empty($options['mod_widgets'])) {
    if ('true' === $options['mod_widgets'])
      $defaults['load_widgets'] = true;
    elseif ('false' === $options['mod_widgets'])
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
