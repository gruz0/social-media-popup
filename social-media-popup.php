<?php
defined( 'ABSPATH' ) or exit;
/*
Plugin Name: Social Media Popup
Plugin URI: http://gruz0.ru/
Description: The plugin creates a popup window with most popular social media widgets
Author: Alexander Gruzov
Author URI: http://gruz0.ru/
Text Domain: social-community-popup
Version: 0.7.4
License: GPL2
*/

if ( ! array_key_exists( 'social-media-popup', $GLOBALS ) ) {
	if ( ! class_exists( 'Social_Media_Popup' ) ) {

		// Хуки для активации и деактивации плагина
		register_activation_hook( __FILE__, array( 'Social_Media_Popup', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'Social_Media_Popup', 'deactivate' ) );
		register_uninstall_hook( __FILE__, array('Social_Media_Popup', 'uninstall' ) );

		include sprintf( "%s/social-media-popup.class.php", dirname( __FILE__ ) );

		$social_media_popup = new Social_Media_Popup();

		// Uses by PHPUnit
		$GLOBALS['social-media-popup'] = $social_media_popup;

		if (isset( $social_media_popup) ) {
			// Добавляем пункт "Настройки" в раздел плагинов в WordPress
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
		 * @param string $variable
		 * @return string
		 */
		function scp_to_bool( $variable ) {
			return ( $variable === '1' ? 'true' : 'false' );
		}

		function scp_updater() {
			require_once( dirname( __FILE__ ) . '/updater.php' );

			if ( is_admin() ) {
				new GitHub_Updater( __FILE__, 'gruz0', 'social-community-popup' );
			}
		}

		scp_updater();
	}
}
