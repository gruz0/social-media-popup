<?php defined( 'ABSPATH' ) or exit; ?>
<?php
$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

$subtab = isset( $_GET['section'] ) ? $_GET['section'] : '';
if ( ! empty( $subtab ) ) {
	$tab .= '-' . $subtab;
}

?>

<div class="wrap social-community-popup-settings">
	<h1><?php _e( 'Social Media Popup Options', L10N_SCP_PREFIX ); ?></h1>
	<?php echo scp_settings_tabs(); ?>
	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-' . $tab ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-' . $tab, SMP_PREFIX . '-group-' . $tab ); ?>
		<?php do_settings_sections( SMP_PREFIX . '-group-' . $tab ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( "%s/copyright.php", dirname( __FILE__ ) ) ); ?>
</div>

<?php
function scp_settings_tabs() {
	$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

	$tabs                = array();
	$tabs['general']     = __( 'General', L10N_SCP_PREFIX );
	$tabs['view']        = __( 'View (Desktop)', L10N_SCP_PREFIX );
	$tabs['view-mobile'] = __( 'View (Mobile Devices)', L10N_SCP_PREFIX );
	$tabs['events']      = __( 'Events', L10N_SCP_PREFIX );
	$tabs['tracking']    = __( 'Tracking', L10N_SCP_PREFIX );
	$tabs['management']  = __( 'Management', L10N_SCP_PREFIX );

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';

		switch ( $tab_key ) {
			case 'tracking':
				$tab_key .= '&section=general';
				break;
		}

		echo '<a class="nav-tab ' . $active . '" href="?page=' . SMP_PREFIX . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
	}

	echo '</h2>';

	switch ( $current_tab ) {
		case 'tracking':
			smp_tracking_menu( $current_tab );
			break;
	}
}

function smp_tracking_menu( $current_tab ) {
	$current_subtab = isset( $_GET['section'] ) ? $_GET['section'] : 'general';

	$subtabs                     = array();
	$subtabs['general']          = __( 'General', L10N_SCP_PREFIX );
	$subtabs['google-analytics'] = __( 'Google Analytics', L10N_SCP_PREFIX );
	$subtabs['window-events']    = __( 'Window Events', L10N_SCP_PREFIX );
	$subtabs['social-events']    = __( 'Social Events', L10N_SCP_PREFIX );

	$subtab_template = '<a class="nav-tab %s" href="?page=' . SMP_PREFIX . '&tab=%s&section=%s">%s</a>';

	echo '<h3 class="nav-tab-wrapper">';
	foreach ( $subtabs as $tab_key => $tab_caption ) {
		$active = $current_subtab == $tab_key ? 'nav-tab-active' : '';
		echo sprintf( $subtab_template, $active, $current_tab, $tab_key, $tab_caption );
	}

	echo '</h3>';
}
