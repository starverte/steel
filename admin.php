<div class="wrap">
<?php screen_icon(); ?>
<h2>Sparks Options</h2>
<form method="post" action="options.php">
<?php settings_fields( 'myoption-group' );
do_settings_fields( 'myoption-group' ); ?>
<?php submit_button(); ?>
</form>
</div>
