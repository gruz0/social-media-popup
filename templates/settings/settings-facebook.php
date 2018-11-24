<?php
/**
 * Facebook Settings
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

$available_tabs = array( 'general', 'tracking' );
$slug           = ! empty( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';
$tab            = smp_validate_and_sanitize_tab( $slug, $available_tabs );
?>

<div class="wrap social-community-popup-settings">
	<h2><?php esc_attr_e( 'Facebook Options', L10N_SCP_PREFIX ); ?></h2>

	<?php scp_facebook_settings_tabs( $tab ); ?>

	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-facebook-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-facebook-' . $tab ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-facebook-' . $tab, SMP_PREFIX . '-group-facebook-' . $tab ); ?>
		<?php do_settings_sections( SMP_PREFIX . '-group-facebook-' . $tab ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( '%s/../copyright.php', dirname( __FILE__ ) ) ); ?>
</div>

<?php
/**
 * Facebook Tabs
 *
 * @param string $current_tab Current tab slug
 */
function scp_facebook_settings_tabs( $current_tab ) {
	$tabs             = array();
	$tabs['general']  = __( 'General', L10N_SCP_PREFIX );
	$tabs['tracking'] = __( 'Tracking', L10N_SCP_PREFIX );

	$tab_template = '<a class="nav-tab %s" href="?page=' . SMP_PREFIX . '_facebook_options&tab=%s">%s</a>';

	$content = '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab === $tab_key ? 'nav-tab-active' : '';

		$content .= sprintf(
			$tab_template,
			esc_attr( $active ),
			esc_attr( $tab_key ),
			esc_attr( $tab_caption )
		);
	}
	$content .= '</h2>';

	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $content;
	// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
}
