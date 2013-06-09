<?php
/*
* Sparks Administration Page
*
* @package Sparks
* @sub-package Steel
*
* @since 0.2.0
*/ ?>

<div class="wrap">
  <h2>Sparks Options</h2>
  <form method="post" action="options.php">
    <?php settings_fields('sparks_options'); ?>
    <?php $options = get_option('steel_options'); ?>
    <table class="form-table">
    	<tr valign="top"><th scope="row">PayPal Merchant ID</th><td><input type="text" name="steel_options[merch_id]" value="<?php echo $options['merch_id']; ?>" /></td></tr>
    </table>
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
  </form>
</div>
