<?php
/**
 * Social Media Popup
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

/**
 * Plugin Name: Social Media Popup
 * Plugin URI: https://github.com/gruz0/social-media-popup
 * Description: The plugin creates a pop-up window with the most popular social media widgets
 * Author: Alexander Kadyrov
 * Author URI: http://gruz0.ru/
 * Text Domain: social-media-popup
 * Version: 1.0.0
 * License: GPL2
 * Minimum PHP: 5.3
 * Minimum WP: 3.5
 */

if ( ! array_key_exists( 'social-media-popup', $GLOBALS ) ) {
	if ( ! class_exists( 'Social_Media_Popup' ) ) {
		register_activation_hook( __FILE__, array( 'Social_Media_Popup', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'Social_Media_Popup', 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( 'Social_Media_Popup', 'uninstall' ) );

		define( 'SMP_PREFIX',        'social_media_popup' );
		define( 'SMP_DIR',            dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
		define( 'SMP_INCLUDES_DIR',   SMP_DIR . 'includes' . DIRECTORY_SEPARATOR );
		define( 'SMP_TEMPLATES_DIR',  SMP_DIR . 'templates' . DIRECTORY_SEPARATOR );
		define( 'SMP_PLUGIN_URL',     plugin_dir_url( __FILE__ ) );
		define( 'SMP_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) . DIRECTORY_SEPARATOR );
		define( 'SMP_ASSETS_URL',     plugin_dir_url( __FILE__ ) . 'dist/' );

		require_once( SMP_INCLUDES_DIR . 'functions.php' );
		require_once( SMP_INCLUDES_DIR . 'class-smp-options.php' );
		require_once( SMP_INCLUDES_DIR . 'class-smp-sanitizer.php' );
		require_once( SMP_INCLUDES_DIR . 'class-smp-settings-field.php' );
		require_once( SMP_INCLUDES_DIR . 'class-smp-template.php' );
		require_once( SMP_INCLUDES_DIR . 'class-smp-popup.php' );
		require_once( SMP_INCLUDES_DIR . 'class-smp-validator.php' );
		require_once( SMP_INCLUDES_DIR . 'providers/class-smp-provider.php' );
		require_once( SMP_INCLUDES_DIR . 'class-social-media-popup.php' );

		$social_media_popup = new Social_Media_Popup();

		// Uses by PHPUnit
		$GLOBALS['social-media-popup'] = $social_media_popup;

		if ( isset( $social_media_popup ) ) {
			/**
			 * Add Settings link to plugins list
			 *
			 * @param array $links Links
			 * @return array
			 */
			function social_media_popup_plugin_settings_link( $links ) {
				$settings_link = '<a href="admin.php?page=social_media_popup">' . esc_html( 'Settings', 'social-media-popup' ) . '</a>';
				array_unshift( $links, $settings_link );
				return $links;
			}

			$plugin = plugin_basename( __FILE__ );
			add_filter( 'plugin_action_links_' . $plugin, 'social_media_popup_plugin_settings_link' );
		}
	}
}

