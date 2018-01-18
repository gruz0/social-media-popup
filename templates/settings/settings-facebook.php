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

$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
?>
<div class="wrap social-community-popup-settings">
	<h2><?php _e( 'Facebook Options', L10N_SCP_PREFIX ); ?></h2>
	<?php echo scp_facebook_settings_tabs( $tab ); ?>
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
 * @param string $current_tab Current tab
 */
function scp_facebook_settings_tabs( $current_tab ) {
	$tabs                  = array();
	$tabs['general']       = __( 'General', L10N_SCP_PREFIX );
	$tabs['tracking']      = __( 'Tracking', L10N_SCP_PREFIX );

	echo '<h2 class="nav-tab-wrapper">';
	$template = '<a class="nav-tab %s" href="?page=%s_facebook_options&tab=%s">%s</a>';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
		echo sprintf( $template, $active, SMP_PREFIX, $tab_key, $tab_caption );
	}
	echo '</h2>';
}
