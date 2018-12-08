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

// TODO: Rewrite with multi-dimensional array
$available_tabs = array( 'general', 'view', 'events', 'management', 'view-mobile', 'tracking' );
$slug           = ! empty( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';
$tab            = smp_validate_and_sanitize_tab( $slug, $available_tabs );
$render_tab     = $tab;

$available_subtabs = array( 'general', 'google-analytics', 'window-events', 'social-events' );
$subslug           = ! empty( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : '';
$subtab            = smp_validate_and_sanitize_tab( $subslug, $available_subtabs, '' );
if ( ! empty( $subtab ) ) {
	$render_tab .= '-' . $subtab;
}
?>

<div class="wrap social-community-popup-settings">
	<h2>Social Media Popup</h2>

	<?php scp_settings_tabs( $tab, $subtab ); ?>

	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-' . $render_tab ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-' . $render_tab, SMP_PREFIX . '-group-' . $render_tab ); ?>
		<?php do_settings_sections( SMP_PREFIX . '-group-' . $render_tab ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( '%s/copyright.php', dirname( __FILE__ ) ) ); ?>
</div>

<?php
/**
 * Render menu tabs
 *
 * @param string $current_tab Primary tab slug
 * @param string $subtab      Secondary tab slug
 */
function scp_settings_tabs( $current_tab, $subtab ) {
	$tabs                = array();
	$tabs['general']     = __( 'General', 'social-media-popup' );
	$tabs['view']        = __( 'View (Desktop)', 'social-media-popup' );
	$tabs['view-mobile'] = __( 'View (Mobile Devices)', 'social-media-popup' );
	$tabs['events']      = __( 'Events', 'social-media-popup' );
	$tabs['tracking']    = __( 'Tracking', 'social-media-popup' );
	$tabs['management']  = __( 'Management', 'social-media-popup' );

	$tab_template = '<a class="nav-tab %s" href="?page=%s&tab=%s">%s</a>';

	$content = '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab === $tab_key ? 'nav-tab-active' : '';

		switch ( $tab_key ) {
			case 'tracking':
				$tab_key .= '&section=general';
				break;
		}

		$content .= sprintf(
			$tab_template,
			esc_attr( $active ),
			SMP_PREFIX,
			esc_attr( $tab_key ),
			esc_html( $tab_caption )
		);
	}

	$content .= '</h2>';

	switch ( $current_tab ) {
		case 'tracking':
			$content .= smp_tracking_menu( $current_tab, $subtab );
			break;
	}

	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $content;
	// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Render tracking menu items
 *
 * @param string $current_tab    Current tab
 * @param string $current_subtab Secondary tab
 *
 * @return string
 */
function smp_tracking_menu( $current_tab, $current_subtab ) {
	$available_tabs = array( 'general', 'google-analytics', 'window-events', 'social-events' );
	$current_subtab = smp_validate_and_sanitize_tab( $current_subtab, $available_tabs );

	$subtabs                     = array();
	$subtabs['general']          = __( 'General', 'social-media-popup' );
	$subtabs['google-analytics'] = __( 'Google Analytics', 'social-media-popup' );
	$subtabs['window-events']    = __( 'Window Events', 'social-media-popup' );
	$subtabs['social-events']    = __( 'Social Events', 'social-media-popup' );

	$subtab_template = '<a class="nav-tab %s" href="?page=%s&tab=%s&section=%s">%s</a>';

	$content = '<h3 class="nav-tab-wrapper">';
	foreach ( $subtabs as $tab_key => $tab_caption ) {
		$active = $current_subtab === $tab_key ? 'nav-tab-active' : '';

		$content .= sprintf(
			$subtab_template,
			esc_attr( $active ),
			SMP_PREFIX,
			esc_attr( $current_tab ),
			esc_attr( $tab_key ),
			esc_html( $tab_caption )
		);
	}

	$content .= '</h3>';

	return $content;
}
