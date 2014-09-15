<?php
defined( 'ABSPATH' ) or exit;
/*
Plugin Name: Social Community Popup
Plugin URI: http://gruz0.ru/
Description: Social Community Popup
Author: Alexander Gruzov
Author URI: http://gruz0.ru/
Text Domain: social-community-popup
Version: 0.1
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
}
