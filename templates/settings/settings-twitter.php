<?php
/**
 * Twitter Settings
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

$available_tabs = array( 'general', 'follow-button', 'timeline', 'tracking' );
$tab            = smp_validate_and_sanitize_tab( $available_tabs );
?>

<div class="wrap social-community-popup-settings">
	<h2><?php esc_attr_e( 'Twitter Options', L10N_SCP_PREFIX ); ?></h2>

	<?php echo esc_html( scp_twitter_settings_tabs() ); ?>

	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-twitter-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-twitter-' . $tab ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-twitter-' . $tab, SMP_PREFIX . '-group-twitter-' . $tab ); ?>
		<?php do_settings_sections( SMP_PREFIX . '-group-twitter-' . $tab ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( '%s/../copyright.php', dirname( __FILE__ ) ) ); ?>
</div>

<?php
/**
 * Render settings tabs
 */
function scp_twitter_settings_tabs() {
	$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';

	$tabs                  = array();
	$tabs['general']       = __( 'General', L10N_SCP_PREFIX );
	$tabs['follow-button'] = __( 'Follow Button Widget', L10N_SCP_PREFIX );
	$tabs['timeline']      = __( 'Timeline Widget', L10N_SCP_PREFIX );
	$tabs['tracking']      = __( 'Tracking', L10N_SCP_PREFIX );

	$tab_template = '<a class="nav-tab %s" href="?page=' . SMP_PREFIX . '_twitter_options&tab=%s">%s</a>';

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab === $tab_key ? 'nav-tab-active' : '';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sprintf(
			$tab_template,
			esc_attr( $active ),
			esc_attr( $tab_key ),
			esc_attr( $tab_caption )
		);
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</h2>';
}
