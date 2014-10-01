<?php
/**
 * Info Options Page
 *
 * @package Steel
 * @since 1.2.0
 */

$steel_siteinfo = array();

  add_action( 'admin_menu', 'steel_siteinfo_page' );
  function steel_siteinfo_page() {
    add_options_page( 'Info', 'Info', 'manage_options', 'siteinfo', 'steel_geninfo_options' );
  }

  add_action( 'admin_init', 'steel_gi_admin_init' );
  function steel_gi_admin_init() {
    register_setting( 'steel_siteinfo_section' , 'steel_siteinfo' , 'steel_siteinfo_validate' );
  }

  function steel_geninfo_options() {
    global $steel_siteinfo; ?>

    <h2>General Info</h2>

    <h3>Site Info</h3>

    <form method="post" action="options.php">

      <?php
        $options = get_option( 'steel_siteinfo', $steel_siteinfo );
        $blogname          = get_bloginfo( 'name' )       ;
        $blogdescription  = get_bloginfo( 'description' );
        $description      = !empty($options['description']) ? $options['description'] : '';

        $company     = !empty($options['company'])     ? $options['company']            : '';
        $tel         = !empty($options['tel'])         ? $options['tel']                : '';
        $email       = !empty($options['email'])       ? $options['email']              : '';
        $fax         = !empty($options['fax'])         ? $options['fax']                : '';
        $address     = !empty($options['address'])     ? $options['address']            : '';
        $locality    = !empty($options['locality'])    ? $options['locality']           : '';
        $postal_code = !empty($options['postal_code']) ? $options['postal_code']        : '';
      ?>

      <?php settings_fields( 'steel_siteinfo_section' ); ?>

      <table class="form-table">

        <tr valign="top"><th scope="row"><?php _e( 'Site Title', 'flint' ); ?></th>
          <td>
            <span id="blogname" name="blogname"><?php echo $blogname ?></span>
            <p class="description">To change, go to Settings -> General</p>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'Tagline', 'flint' ); ?></th>
          <td>
            <span id="blogdescription" name="blogdescription"><?php echo $blogdescription ?></span>
            <p class="description">To change, go to Settings -> General</p>
          </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'Description', 'flint' ); ?></th>
          <td><textarea id="steel_siteinfo[description]" class="text-field" name="steel_siteinfo[description]" rows="5" style="width:80%;max-width:400px;"><?php echo $description; ?></textarea></td>
        </tr>

       </table>

    <h3>Publisher Info</h3>

      <table class="form-table">

        <tr valign="top"><th scope="row"><?php _e( 'Company Name', 'flint' ); ?></th>
          <td><input id="steel_siteinfo[company]" class="regular-text" type="text" name="steel_siteinfo[company]" value="<?php echo $company ?>" /></td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'Street Address', 'flint' ); ?></th>
          <td><input id="steel_siteinfo[address]" class="regular-text" type="text" name="steel_siteinfo[address]" value="<?php echo $address ?>" /></td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'City, State', 'flint' ); ?></th>
          <td><input id="steel_siteinfo[locality]" class="regular-text" type="text" name="steel_siteinfo[locality]" value="<?php echo $locality ?>" /></td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'Zip Code', 'flint' ); ?></th>
          <td><input id="steel_siteinfo[postal_code]" class="regular-text" type="text" name="steel_siteinfo[postal_code]" value="<?php echo $postal_code ?>" /></td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'Phone Number', 'flint' ); ?></th>
          <td><input id="steel_siteinfo[tel]" class="regular-text" type="text" name="steel_siteinfo[tel]" value="<?php echo $tel ?>" /></td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'Fax Number', 'flint' ); ?></th>
          <td><input id="steel_siteinfo[fax]" class="regular-text" type="text" name="steel_siteinfo[fax]" value="<?php echo $fax ?>" /></td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e( 'Email Address', 'flint' ); ?></th>
          <td><input id="steel_siteinfo[email]" class="regular-text" type="text" name="steel_siteinfo[email]" value="<?php echo $email ?>" /></td>
        </tr>

       </table>

      <p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>

    </form>

    <?php
  }

  function steel_siteinfo_validate( $input ) {
    global $steel_siteinfo;
    $options = get_option( 'steel_siteinfo', $steel_siteinfo );

    $input['description'] = wp_filter_post_kses( $input['description'] );
    return $input;
  }

function steel_get_siteinfo( $key, $section = 'steel_siteinfo' ) {
  $options = get_option( 'steel_siteinfo' );
  $val = !empty($options[$key]) ? $options[$key] : false;
  return $val;
}
function steel_get_gi_address( $schema = true, $args = array() ) {
  $options = get_option( 'steel_siteinfo' );

  $address     = $options['address'];
  $locality    = $options['locality'];
  $postal_code = $options['postal_code'];

  $defaults = array(
    'before' => '<span id="street" itemprop="streetAddress">',
    'item1'  => $address,
    'sep1'   => '</span>, <span id="locality" itemprop="addressLocality">',
    'item2'  => $locality,
    'sep2'   => '</span> <span id="postal-code" itemprop="postalCode">',
    'item3'  => $postal_code,
    'after'  => '</span>',
    'open'   => '<div id="org" itemscope itemtype="http://schema.org/Organization"><span id="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">',
    'close'  => '</span></div>',
  );
  $alts = array(
    'before' => '',
    'item1'  => $address,
    'sep1'   => ', ',
    'item2'  => $locality,
    'sep2'   => ' ',
    'item3'  => $postal_code,
    'after'  => '',
    'open'   => '',
    'close'  => '',
  );

  $args = $schema == true ? wp_parse_args( $args, $defaults ) : wp_parse_args( $args, $alts );
  $output = $args['open'] . $args['before'] . $args['item1'] . $args['sep1'] . $args['item2'] . $args['sep2'] . $args['item3'] . $args['after'] . $args['close'];

  echo $output;
}