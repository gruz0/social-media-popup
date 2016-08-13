<?php defined( 'ABSPATH' ) or exit; ?>
<?php
$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
?>

<div class="wrap social-community-popup-settings">
	<h2><?php _e( 'Facebook Options', L10N_SCP_PREFIX ); ?></h2>
	<?php echo scp_facebook_settings_tabs(); ?>
	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-facebook-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-facebook-' . $tab ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-facebook-' . $tab, SMP_PREFIX . '-group-facebook-' . $tab ); ?>
		<?php do_settings_sections( SMP_PREFIX . '-group-facebook-' . $tab ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( "%s/../copyright.php", dirname( __FILE__ ) ) ); ?>
</div>

<?php
function scp_facebook_settings_tabs() {
	$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

	$tabs                  = array();
	$tabs['general']       = __( 'General', L10N_SCP_PREFIX );
	$tabs['tracking']      = __( 'Tracking', L10N_SCP_PREFIX );

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
		echo '<a class="nav-tab ' . $active . '" href="?page=' . SMP_PREFIX . '_facebook_options&tab=' . $tab_key . '">' . $tab_caption . '</a>';
	}
	echo '</h2>';
}
