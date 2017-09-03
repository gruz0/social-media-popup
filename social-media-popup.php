<?php
/**
 * Social Media Popup
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://smp-plugin.com/
 */

defined( 'ABSPATH' ) or exit;

/**
 * Plugin Name: Social Media Popup
 * Plugin URI: http://smp-plugin.com/
 * Description: The plugin creates a popup window with most popular social media widgets
 * Author: Alexander Kadyrov
 * Author URI: http://gruz0.ru/
 * Text Domain: social-community-popup
 * Version: 0.7.6
 * License: GPL2
 * Minimum PHP: 5.3
 * Minimum WP: 3.5
 */

/**
 * Create a helper function for easy SDK access.
 */
function smp_fs() {
	global $smp_fs;

	if ( ! isset( $smp_fs ) ) {
		// Include Freemius SDK.
		require_once dirname( __FILE__ ) . '/freemius/start.php';

		$smp_fs = fs_dynamic_init( array(
			'id'                  => '1336',
			'slug'                => 'social-media-popup',
			'type'                => 'plugin',
			'public_key'          => 'pk_0d3379a2980288a952b63df8bfcec',
			'is_premium'          => true,
			// If your plugin is a serviceware, set this option to false.
			'has_premium_version' => true,
			'has_addons'          => false,
			'has_paid_plans'      => true,
			'trial'               => array(
				'days'               => 14,
				'is_require_payment' => true,
			),
			'menu'                => array(
				'slug'           => 'social_media_popup',
				'first-path'     => 'index.php?page=social_media_popup_about',
				'contact'        => false,
				'support'        => false,
			),
			// Set the SDK to work in a sandbox mode (for development & testing).
			// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
			'secret_key'          => 'sk_9<-[S^=J+lNvu%j-a2B=dfl:WB:6:',
		) );
	}

	return $smp_fs;
}

// Init Freemius.
smp_fs();

// Signal that SDK was initiated.
do_action( 'smp_fs_loaded' );

if ( ! array_key_exists( 'social-media-popup', $GLOBALS ) ) {
	if ( ! class_exists( 'Social_Media_Popup' ) ) {

		// Хуки для активации и деактивации плагина
		register_activation_hook( __FILE__, array( 'Social_Media_Popup', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'Social_Media_Popup', 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( 'Social_Media_Popup', 'uninstall' ) );

		define( 'SMP_DIR',            dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
		define( 'SMP_INCLUDES_DIR',   SMP_DIR . 'includes' . DIRECTORY_SEPARATOR );
		define( 'SMP_TEMPLATES_DIR',  SMP_DIR . 'templates' . DIRECTORY_SEPARATOR );
		define( 'SMP_PLUGIN_URL',     plugin_dir_url( __FILE__ ) );
		define( 'SMP_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) . DIRECTORY_SEPARATOR );
		define( 'SMP_ASSETS_URL',     plugin_dir_url( __FILE__ ) . 'assets/' );

		include( SMP_INCLUDES_DIR . 'social-media-popup.class.php' );

		$social_media_popup = new Social_Media_Popup();

		// Uses by PHPUnit
		$GLOBALS['social-media-popup'] = $social_media_popup;

		if ( isset( $social_media_popup ) ) {
			/**
			 * Добавляет пункт "Настройки" в раздел плагинов в WordPress
			 *
			 * @param array $links Links
			 * @return array
			 */
			function social_media_popup_plugin_settings_link( $links ) {
				$settings_link = '<a href="admin.php?page=social_media_popup">' . __( 'Settings', L10N_SCP_PREFIX ) . '</a>';
				array_unshift( $links, $settings_link );
				return $links;
			}

			$plugin = plugin_basename( __FILE__ );
			add_filter( 'plugin_action_links_' . $plugin, 'social_media_popup_plugin_settings_link' );
		}

		/**
		 * Переводит текстовое значение get_option для чекбокса в булевое
		 *
		 * @param string $variable Value
		 * @return string
		 */
		function scp_to_bool( $variable ) {
			return ( '1' === $variable ? 'true' : 'false' );
		}
	}
}

