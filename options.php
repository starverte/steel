<?php
/**
 * Steel Options page
 *
 * @package Steel
 */

/**
 * Add Steel options menu page
 */
function steel_admin_menu() {
  add_menu_page( 'Steel', 'Steel', 'manage_options', 'steel', 'steel_menu_page', 'none' );
}
add_action( 'admin_menu', 'steel_admin_menu' );

/**
 * Display the content for the Steel options menu page
 */
function steel_menu_page() {
  ?>
  <div class="wrap">
    <h2>Steel Options</h2>
    <form action="options.php" method="post">
    <?php
      settings_fields( 'steel_options' );
      do_settings_sections( 'steel' );
      settings_errors();
      ?>
      <p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
    </form>
  </div>
  <?php
}

/**
 * Register settings for Steel options menu page
 */
function steel_admin_init() {

  register_setting( 'steel_options', 'steel_options', 'steel_options_validate' );

  add_settings_section( 'steel_analytics', 'Website Analytics', 'steel_analytics_section', 'steel' );

  add_settings_field(
    'ga_id',
    'Google Analytics Property ID',
    'steel_settings_field_ga_id',
    'steel',
    'steel_analytics'
  );

  add_settings_section( 'steel_social', 'Social Media', 'steel_social_section', 'steel' );

  add_settings_field(
    'fb_app_id',
    'Facebook App ID',
    'steel_settings_field_fb_app_id',
    'steel',
    'steel_social'
  );

  add_settings_section( 'steel_mods', 'Modules', 'steel_mods_section', 'steel' );

  add_settings_field(
    'load_bootstrap',
    'Bootstrap',
    'steel_settings_field_bootstrap',
    'steel',
    'steel_mods'
  );

  add_settings_field(
    'load_broadcast',
    'Broadcast',
    'steel_settings_field_broadcast',
    'steel',
    'steel_mods'
  );

  add_settings_field(
    'load_slides',
    'Slides',
    'steel_settings_field_slides',
    'steel',
    'steel_mods'
  );

  add_settings_field(
    'load_teams',
    'Teams',
    'steel_settings_field_teams',
    'steel',
    'steel_mods'
  );
}
add_action( 'admin_init', 'steel_admin_init' );

/**
 * Display analytics section title
 */
function steel_analytics_section() {}

/**
 * Display Google Analytics ID field
 */
function steel_settings_field_ga_id() {
  $options = steel_get_options(); ?>

  <input id="ga_id" name="steel_options[ga_id]" size="40" type="text" value="<?php echo $options['ga_id']; ?>" placeholder="UA-XXXXX-X"><?php
}

/**
 * Display social section title
 */
function steel_social_section() {
  echo 'Social media profile information';
}

/**
 * Display Facebook App ID field
 */
function steel_settings_field_fb_app_id() {
  $options = steel_get_options(); ?>

  <input id="fb_app_id" name="steel_options[fb_app_id]" size="40" type="text" value="<?php echo $options['fb_app_id']; ?>"><?php
}

/**
 * Display modules section title
 */
function steel_mods_section() {
  echo 'Select which modules should be active.';
}

/**
 * Display Bootstrap module field
 */
function steel_settings_field_bootstrap() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_bootstrap_css]">
      <input name="steel_options[load_bootstrap_css]" type="checkbox" value="true" <?php checked( $options['load_bootstrap_css'] ); ?>>Load CSS&nbsp;&nbsp;
    </label>
    <label for="steel_options[load_bootstrap_js]">
      <input name="steel_options[load_bootstrap_js]" type="checkbox" value="true" <?php checked( $options['load_bootstrap_js'] ); ?>>Load Javascript
    </label>
  </div>

  <?php
}

/**
 * Display Broadcast module field
 */
function steel_settings_field_broadcast() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_broadcast]">
      <input name="steel_options[load_broadcast]" type="checkbox" value="true" <?php checked( $options['load_broadcast'] ); ?>>Active
    </label>
  </div>
  <?php
}

/**
 * Display Slides module field
 */
function steel_settings_field_slides() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_slides]">
      <input name="steel_options[load_slides]" type="checkbox" value="true"  <?php checked( $options['load_slides'], true ); ?>>Active
    </label>
  </div>
  <?php
}

/**
 * Display Teams module field
 */
function steel_settings_field_teams() {
  $options = steel_get_options(); ?>

  <div class="radio-group">
    <label for="steel_options[load_teams]">
      <input name="steel_options[load_teams]" type="checkbox" value="true"  <?php checked( $options['load_teams'], true ); ?>>Active
    </label>
  <div class="radio-group">
  <?php
}

/**
 * Validate settings for Steel Options page
 *
 * @param mixed $raw The raw, unfiltered, form data.
 */
function steel_options_validate( $raw ) {
  $valid['ga_id'] = trim( $raw['ga_id'] );
  if ( ! preg_match( '/^UA-\d{4,}-\d+$/', $valid['ga_id'] ) & ! empty( $valid['ga_id'] ) ) {
    add_settings_error(
      'ga_id',
      'invalid',
      'Invalid Google Analytics Property ID. <span style="font-weight:normal;display:block;">A Google Analytics Property ID is in the format UA-########-#.</span>'
    );
  }

  $valid['fb_app_id'] = trim( $raw['fb_app_id'] );

  if ( ! preg_match( '/^[0-9]{15}$/i', $valid['fb_app_id'] ) & ! empty( $valid['fb_app_id'] ) ) {
    add_settings_error(
      'fb_app_id',
      'invalid',
      'Invalid Facebook App ID. <span style="font-weight:normal;display:block;">A Facebook App ID consists of 15 digits.</span>'
    );
  }

  $valid['load_bootstrap_css'] = ! empty( $raw['load_bootstrap_css'] ) ? true : false;
  $valid['load_bootstrap_js'] = ! empty( $raw['load_bootstrap_js'] ) ? true : false;

  $valid['load_broadcast'] = ! empty( $raw['load_broadcast'] ) ? true : false;
  $valid['load_slides'] = ! empty( $raw['load_slides'] ) ? true : false;
  $valid['load_teams'] = ! empty( $raw['load_teams'] ) ? true : false;

  return apply_filters( 'steel_save_options', $valid, $raw );
}

/**
 * Retrieve option defaults
 */
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

    'load_broadcast'     => false,
    'load_quotes'        => true,
    'load_shortcodes'    => true,
    'load_social_media'  => true,
    'load_slides'        => false,
    'load_teams'         => false,
    'load_widgets'       => true,
  );

  // BEGIN - backwards compatibility.
  $options = get_option( 'steel_options' );

  if ( ! empty( $options['mod_bootstrap'] ) ) {
    if ( 'css' === $options['mod_bootstrap'] || 'both' === $options['mod_bootstrap'] ) {
      $defaults['load_bootstrap_css'] = true;
    }

    if ( 'js' === $options['mod_bootstrap'] || 'both' === $options['mod_bootstrap'] ) {
      $defaults['load_bootstrap_js'] = true;
    }
  }

  if ( ! empty( $options['mod_podcast'] ) ) {
    if ( 'true' === $options['mod_podcast'] ) {
      $defaults['load_broadcast'] = true;
    } elseif ( 'false' === $options['mod_podcast'] ) {
      $defaults['load_broadcast'] = false;
    }
  }

  if ( ! empty( $options['mod_quotes'] ) ) {
    if ( 'true' === $options['mod_quotes'] ) {
      $defaults['load_quotes'] = true;
    } elseif ( 'false' === $options['mod_quotes'] ) {
      $defaults['load_quotes'] = false;
    }
  }

  if ( ! empty( $options['mod_shortcodes'] ) ) {
    if ( 'true' === $options['mod_shortcodes'] ) {
      $defaults['load_shortcodes'] = true;
    } elseif ( 'false' === $options['mod_shortcodes'] ) {
      $defaults['load_shortcodes'] = false;
    }
  }

  if ( ! empty( $options['mod_slides'] ) ) {
    if ( 'true' === $options['mod_slides'] ) {
      $defaults['load_slides'] = true;
    } elseif ( 'false' === $options['mod_slides'] ) {
      $defaults['load_slides'] = false;
    }
  }

  if ( ! empty( $options['mod_teams'] ) ) {
    if ( 'true' === $options['mod_teams'] ) {
      $defaults['load_teams'] = true;
    } elseif ( 'false' === $options['mod_teams'] ) {
      $defaults['load_teams'] = false;
    }
  }

  if ( ! empty( $options['mod_widgets'] ) ) {
    if ( 'true' === $options['mod_widgets'] ) {
      $defaults['load_widgets'] = true;
    } elseif ( 'false' === $options['mod_widgets'] ) {
      $defaults['load_widgets'] = false;
    }
  }
  // END - backwards compatibility.
  return apply_filters( 'steel_option_defaults', $defaults );
}

/**
 * Gets array of plugin options
 */
function steel_get_options() {
  $defaults = steel_get_option_defaults();

  $steel_options = wp_parse_args( get_option( 'steel_options' ), $defaults );

  if ( ! empty( $option ) ) {
    return $steel_options[ $option ];
  } else {
    return $steel_options;
  }
}
