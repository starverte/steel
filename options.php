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
    add_settings_field('mod_bootstrap'  , 'Bootstrap'  , 'mod_bootstrap_field'  , 'steel', 'steel_mods' );
  //add_settings_field('mod_podcast'    , 'Podcast'    , 'mod_podcast_field'    , 'steel', 'steel_mods' );
  //add_settings_field('mod_quotes'     , 'Quotes'     , 'mod_quotes_field'     , 'steel', 'steel_mods' );
  //add_settings_field('mod_shortcodes' , 'Shortcodes' , 'mod_shortcodes_field' , 'steel', 'steel_mods' );
    add_settings_field('mod_slides'     , 'Slides'     , 'mod_slides_field'     , 'steel', 'steel_mods' );
    add_settings_field('mod_teams'      , 'Teams'      , 'mod_teams_field'      , 'steel', 'steel_mods' );
  //add_settings_field('mod_widgets'    , 'Widgets'    , 'mod_widgets_field'    , 'steel', 'steel_mods' );
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
 * Validate settings for Steel Options page
 */
function steel_options_validate($input) {
  global $newinput;

  $newinput['fb_app_id'] = trim($input['fb_app_id']);
  if (!preg_match('/^[0-9]{15}$/i', $newinput['fb_app_id']) & !empty($newinput['fb_app_id'])) { add_settings_error( 'fb_app_id', 'invalid', 'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>' ); }

    $newinput['mod_bootstrap'  ] = trim($input['mod_bootstrap'  ]);
  //$newinput['mod_podcast'    ] = trim($input['mod_podcast'    ]);
  //$newinput['mod_quotes'     ] = trim($input['mod_quotes'     ]);
  //$newinput['mod_shortcodes' ] = trim($input['mod_shortcodes' ]);
    $newinput['mod_slides'     ] = trim($input['mod_slides'     ]);
    $newinput['mod_teams'      ] = trim($input['mod_teams'      ]);
  //$newinput['mod_widgets'    ] = trim($input['mod_widgets'    ]);

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

?>
