<?php defined( 'ABSPATH' ) or exit; ?>
<div class="wrap social-community-popup-settings">
	<h2><?php _e( 'Pinterest Options', L10N_SCP_PREFIX ); ?></h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-pinterest-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-pinterest' ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-pinterest', SMP_PREFIX . '-group-pinterest' ); ?>
		<?php do_settings_sections( SMP_PREFIX . '_pinterest_options' ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( "%s/../copyright.php", dirname( __FILE__ ) ) ); ?>
</div>
