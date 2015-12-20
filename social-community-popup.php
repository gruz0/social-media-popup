<?php
defined( 'ABSPATH' ) or exit;
/*
Plugin Name: Social Community Popup
Plugin URI: http://gruz0.ru/
Description: Плагин всплывающего окна с виджетами групп популярных социальных сетей
Author: Alexander Gruzov
Author URI: http://gruz0.ru/
Text Domain: social-community-popup
Version: 0.7.2
License: GPL2
*/

if ( ! class_exists( 'Social_Community_Popup' ) ) {

	// Хуки для активации и деактивации плагина
	register_activation_hook( __FILE__, array( 'Social_Community_Popup', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Social_Community_Popup', 'deactivate' ) );
	register_uninstall_hook( __FILE__, array('Social_Community_Popup', 'uninstall' ) );

	include sprintf( "%s/social-community-popup.class.php", dirname( __FILE__ ) );

	$social_community_popup = new Social_Community_Popup();

	if (isset( $social_community_popup) ) {
		// Добавляем пункт "Настройки" в раздел плагинов в WordPress
		function social_community_popup_plugin_settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=social_community_popup">' . __( 'Settings', L10N_SCP_PREFIX ) . '</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		$plugin = plugin_basename( __FILE__ );
		add_filter( 'plugin_action_links_' . $plugin, 'social_community_popup_plugin_settings_link' );
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
		require_once( dirname( __FILE__ ) . "/includes/updater/updater.php" );

		// TODO: Возможно придётся это переписать на current_user_can( 'manage_options' )
		if ( is_admin() ) {
			$config = array(
				'slug' => plugin_basename(__FILE__),
				'proper_folder_name' => 'social-community-popup',
				'api_url' => 'https://api.github.com/repos/gruz0/social-community-popup',
				'raw_url' => 'https://raw.github.com/gruz0/social-community-popup/master',
				'github_url' => 'https://github.com/gruz0/social-community-popup',
				'zip_url' => 'https://github.com/gruz0/social-community-popup/zipball/master',
				'sslverify' => true,
				'requires' => '3.9',
				'tested' => '4.3.1',
				'readme' => 'readme.txt',
				'access_token' => '',
			);
			new WP_GitHub_Updater( $config );
		}
	}

	scp_updater();
}

