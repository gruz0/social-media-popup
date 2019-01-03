<?php
/**
 * Google Plus Settings
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;
?>

<div class="wrap social-community-popup-settings">
	<h2><?php esc_html_e( 'Google+', 'social-media-popup' ); ?></h2>

	<form method="post" action="options.php">
		<?php wp_nonce_field( 'smp-update-googleplus-options' ); ?>
		<?php settings_fields( SMP_PREFIX . '-group-googleplus' ); ?>
		<?php do_settings_fields( SMP_PREFIX . '-group-googleplus', SMP_PREFIX . '-group-googleplus' ); ?>
		<?php do_settings_sections( SMP_PREFIX . '_googleplus_options' ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( '%s/../copyright.php', dirname( __FILE__ ) ) ); ?>
</div>
