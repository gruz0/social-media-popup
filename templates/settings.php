<?php
/**
 * Settings
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

$available_tabs = array( 'general', 'view', 'events', 'management', 'view-mobile', 'tracking' );
$tab            = smp_validate_and_sanitize_tab( $available_tabs );

$subtab = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';
if ( ! empty( $subtab ) ) {
	$tab .= '-' . $subtab;
}
?>

<div class="wrap social-community-popup-settings">
	<h1><?php esc_attr_e( 'Social Media Popup Options', L10N_SCP_PREFIX ); ?></h1>

	<?php scp_settings_tabs(); ?>

	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-' . $tab ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-' . $tab, SMP_PREFIX . '-group-' . $tab ); ?>
		<?php do_settings_sections( SMP_PREFIX . '-group-' . $tab ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( '%s/copyright.php', dirname( __FILE__ ) ) ); ?>
</div>

<?php
/**
 * Render menu tabs
 */
function scp_settings_tabs() {
	$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';

	$tabs                = array();
	$tabs['general']     = __( 'General', L10N_SCP_PREFIX );
	$tabs['view']        = __( 'View (Desktop)', L10N_SCP_PREFIX );
	$tabs['view-mobile'] = __( 'View (Mobile Devices)', L10N_SCP_PREFIX );
	$tabs['events']      = __( 'Events', L10N_SCP_PREFIX );
	$tabs['tracking']    = __( 'Tracking', L10N_SCP_PREFIX );
	$tabs['management']  = __( 'Management', L10N_SCP_PREFIX );

	$tab_template = '<a class="nav-tab %s" href="?page=' . SMP_PREFIX . '&tab=%s">%s</a>';

	$content = '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab === $tab_key ? 'nav-tab-active' : '';

		switch ( $tab_key ) {
			case 'tracking':
				$tab_key .= '&section=general';
				break;
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		$content .= sprintf(
			$tab_template,
			esc_attr( $active ),
			esc_attr( $tab_key ),
			esc_attr( $tab_caption )
		);
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	$content .= '</h2>';

	switch ( $current_tab ) {
		case 'tracking':
			$content .= smp_tracking_menu( $current_tab );
			break;
	}

	echo $content; // WPCS: XSS OK.
}

/**
 * Render tracking menu items
 *
 * @param string $current_tab Current tab
 */
function smp_tracking_menu( $current_tab ) {
	$current_subtab = 'general';
	$available_tabs = array( 'general', 'google-analytics', 'window-events', 'social-events' );
	if ( ! empty( $_GET['section'] ) && in_array( wp_unslash( $_GET['section'] ), $available_tabs, true ) ) {
		$current_subtab = sanitize_text_field( wp_unslash( $_GET['section'] ) );
	}

	$subtabs                     = array();
	$subtabs['general']          = __( 'General', L10N_SCP_PREFIX );
	$subtabs['google-analytics'] = __( 'Google Analytics', L10N_SCP_PREFIX );
	$subtabs['window-events']    = __( 'Window Events', L10N_SCP_PREFIX );
	$subtabs['social-events']    = __( 'Social Events', L10N_SCP_PREFIX );

	$subtab_template = '<a class="nav-tab %s" href="?page=' . SMP_PREFIX . '&tab=%s&section=%s">%s</a>';

	$content = '<h3 class="nav-tab-wrapper">';
	foreach ( $subtabs as $tab_key => $tab_caption ) {
		$active = $current_subtab === $tab_key ? 'nav-tab-active' : '';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		$content .= sprintf(
			$subtab_template,
			esc_attr( $active ),
			esc_attr( $current_tab ),
			esc_attr( $tab_key ),
			esc_attr( $tab_caption )
		);
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	$content .= '</h3>';

	return $content; // WPCS: XSS OK.
}
