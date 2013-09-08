<?php 
/**
 * Leftovers removed from Flint
 *
 * @package Sparks
 * @sub-package Steel
 */

/*
 * Create options page for metadata
 */
add_action('admin_menu', 'flint_menu');
function flint_menu() {
	add_theme_page('Schema', 'Schema', 'edit_theme_options', 'flint-schema', 'flint_schema');
}
function flint_schema() { ?>
	<div class="wrap">
	<?php echo '<img width="32" height="32" src="' . plugins_url( 'img/sparks.png' , __FILE__ ) . '" style="margin-right: 10px; float: left; margin-top: 7px;" /><h2>Schema</h2>'; ?>
	<form action="options.php" method="post">
		<?php settings_fields('flint_options'); ?>
		<?php do_settings_sections('flint'); ?>
		<?php settings_errors(); ?>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes', 'flint') ?>" /></p>
	</form>
	</div><?php
}

/*
 * Register settings for metadata options page
 */
add_action('admin_init', 'flint_admin_init');
function flint_admin_init(){
	register_setting('flint_options', 'flint_options', 'flint_options_validate' );
	add_settings_section('flint_schema_organization', 'Organization', 'flint_schema_org', 'flint');
	add_settings_field('org_tel', 'Telephone Number', 'org_tel_setting', 'flint', 'flint_schema_organization' );
	add_settings_field('org_fax', 'Fax Number', 'org_fax_setting', 'flint', 'flint_schema_organization' );
	add_settings_field('org_email', 'Email Address', 'org_email_setting', 'flint', 'flint_schema_organization' );
	add_settings_field('org_addr', 'Street Address', 'org_addr_setting', 'flint', 'flint_schema_organization' );
	add_settings_field('org_desc', 'Description', 'org_desc_setting', 'flint', 'flint_schema_organization' );
}
function flint_schema_org() { echo 'For search engine optimization. Must be used by theme to work.'; }
function org_desc_setting() {
	$options = get_option('flint_options');
	if (isset($options['org_desc'])) { echo '<textarea id="org_desc" name="flint_options[org_desc]" rows="5" cols="50">' . $options["org_desc"] . '</textarea>'; }
	else { echo '<textarea id="org_desc" name="flint_options[org_desc]" rows="5" cols="50"></textarea>'; }
}
function org_addr_setting() {
	$options = get_option('flint_options');
	if (isset($options['org_addr'])) { echo '<input type="text" id="org_addr" name="flint_options[org_addr]" value="' . $options["org_addr"] . '" size="45">'; }
	else { echo '<input type="tel" id="org_addr" name="flint_options[org_addr]" value="" size="45">'; }
}
function org_tel_setting() {
	$options = get_option('flint_options');
	if (isset($options['org_tel'])) { echo '<input type="text" id="org_tel" name="flint_options[org_tel]" value="' . $options["org_tel"] . '">'; }
	else { echo '<input type="tel" id="org_tel" name="flint_options[org_tel]" value="">'; }
}
function org_email_setting() {
	$options = get_option('flint_options');
	if (isset($options['org_email'])) { echo '<input type="text" id="org_email" name="flint_options[org_email]" value="' . $options["org_email"] . '">'; }
	else { echo '<input type="email" id="org_email" name="flint_options[org_email]" value="">'; }
}
function org_fax_setting() {
	$options = get_option('flint_options');
	if (isset($options['org_fax'])) { echo '<input type="text" id="org_fax" name="flint_options[org_fax]" value="' . $options["org_fax"] . '">'; }
	else { echo '<input type="tel" id="org_fax" name="flint_options[org_fax]" value="">'; }
}
function flint_options_validate($input) {
	global $newinput;
	$org_tel_digits = preg_match_all( "/[0-9]/", $input['org_tel'] );
	if ($org_tel_digits == 10) {$newinput['org_tel'] = preg_replace("|\b(\d{3})(\d{3})(\d{4})\b|", "$1.$2.$3", $input['org_tel']);}
	elseif ($org_tel_digits == 7){$newinput['org_tel'] = preg_replace("|\b(\d{3})(\d{4})\b|", "$1.$2", $input['org_tel']);}
	else { $newinput['org_tel'] = preg_replace("/[^A-Za-z0-9]/", '', $input['org_tel']); }
	$org_fax_digits = preg_match_all( "/[0-9]/", $input['org_fax'] );
	if ($org_fax_digits == 10) {$newinput['org_fax'] = preg_replace("|\b(\d{3})(\d{3})(\d{4})\b|", "$1.$2.$3", $input['org_fax']);}
	elseif ($org_fax_digits == 7){$newinput['org_fax'] = preg_replace("|\b(\d{3})(\d{4})\b|", "$1.$2", $input['org_fax']);}
	else { $newinput['org_fax'] = preg_replace("/[^A-Za-z0-9]/", '', $input['org_fax']); }
	$newinput['org_email'] = $input['org_email'];
	$newinput['org_addr'] = $input['org_addr'];
	$newinput['org_desc'] = $input['org_desc'];
	return $newinput;
}