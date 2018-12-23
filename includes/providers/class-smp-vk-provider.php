<?php
/**
 * VK.com Template
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

/**
 * SMP_VK_Provider
 */
class SMP_VK_Provider extends SMP_Provider {
	/**
	 * Return widget is active
	 *
	 * @since 0.7.5
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return self::get_option_as_boolean( 'setting_use_vkontakte' );
	}

	/**
	 * Return options as array
	 *
	 * @since 0.7.5
	 *
	 * @return array
	 */
	public static function options() {
		return array(
			'default_tab_caption' => __( 'VK', 'social-media-popup' ),
			'tab_caption'         => self::get_option_as_escaped_string( 'setting_vkontakte_tab_caption' ),
			'css_class'           => 'vk-tab',
			'icon'                => 'fa-vk',
			'url'                 => self::get_option_as_escaped_string( 'setting_vkontakte_page_url' ),
		);
	}

	/**
	 * Return widget container
	 *
	 * @uses SMP_Template()->use_events_tracking()
	 * @uses SMP_Template()->push_social_media_trigger_to_google_analytics()
	 * @uses SMP_Template()->push_social_network_and_action_to_google_analytics()
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	public static function container() {
		$default_vk_group_id = 1;

		$content = '<div class="box">';

		$content .= self::widget_description( 'setting_vkontakte_show_description', 'setting_vkontakte_description' );

		$application_id = self::get_option_as_escaped_string( 'setting_vkontakte_application_id' );
		if ( empty( $application_id ) ) {
			$application_id = 1;
		}

		$page_or_group_id = self::get_option_as_escaped_string( 'setting_vkontakte_page_or_group_id' );
		if ( empty( $page_or_group_id ) ) {
			$page_or_group_id = $default_vk_group_id;
		}

		$content .= '<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
			<div id="scp_vk_groups" style="height:250px !important;"></div>
			<script type="text/javascript">
				var vk_initialized = 0;
				var scp_vk_container_height  = parseInt("' . self::get_option_as_escaped_string( 'setting_vkontakte_height' ) . '");

				function initialize_VK_Widgets() {
					if (jQuery("#scp_vk_groups iframe").length && jQuery("#scp_vk_groups iframe").height() < scp_vk_container_height) {
						jQuery("#scp_vk_groups iframe").height(scp_vk_container_height);
					}

					if (vk_initialized) return;

					jQuery.getScript( "//vk.com/js/api/openapi.js?115", function(data, textStatus, jqxhr) {
						VK.init({apiId: ' . $application_id . ' });

						VK.Observer.subscribe("widgets.groups.joined", function f() {';

							if ( self::get_option_as_boolean( 'setting_vkontakte_close_window_after_join' ) ) {
								$content .= 'smp_destroyPlugin(scp.showWindowAfterReturningNDays);';
							}

							if ( self::$template->use_events_tracking() && self::get_option_as_boolean( 'tracking_use_vkontakte' ) ) {
								$content .= self::$template->push_social_media_trigger_to_google_analytics( self::get_option_as_escaped_string( 'tracking_vkontakte_subscribe_event' ) );
								$content .= self::$template->push_social_network_and_action_to_google_analytics( 'SMP VK', 'Subscribe' );
							}

						$content .= '});

						VK.Observer.subscribe("widgets.groups.leaved", function f() {';

							if ( self::$template->use_events_tracking() && self::get_option_as_boolean( 'tracking_use_vkontakte' ) ) {
								$content .= self::$template->push_social_media_trigger_to_google_analytics( self::get_option_as_escaped_string( 'tracking_vkontakte_unsubscribe_event' ) );
								$content .= self::$template->push_social_network_and_action_to_google_analytics( 'SMP VK', 'Unsubscribe' );
							}

						$content .= '});

						VK.Widgets.Group("scp_vk_groups", {
							mode: '    . self::get_option_as_escaped_string( 'setting_vkontakte_layout' ) . ',
							width: "'  . self::get_option_as_integer( 'setting_vkontakte_width' ) . '",
							height: "' . self::get_option_as_integer( 'setting_vkontakte_height' ) . '",
							color1: "' . self::get_option_as_escaped_string( 'setting_vkontakte_color_background' ) . '",
							color2: "' . self::get_option_as_escaped_string( 'setting_vkontakte_color_text' ) . '",
							color3: "' . self::get_option_as_escaped_string( 'setting_vkontakte_color_button' ) . '"
						}, ' . $page_or_group_id . ');

						vk_initialized = 1;
					});
				}

				function scp_prependVK($) {
					$vk_tab = $("' . self::$tabs_id . ' .vk-tab");

					var scp_vk_interval = setInterval(function() {
						var container_height_is_too_small = jQuery("#scp_vk_groups iframe").height() < scp_vk_container_height;

						if (jQuery("#scp_vk_groups iframe").length > 0 || container_height_is_too_small) {
							jQuery("#scp_vk_groups iframe").height(scp_vk_container_height);

							container_height_is_too_small = jQuery("#scp_vk_groups iframe").height() < scp_vk_container_height;
							if (!container_height_is_too_small) {
								setTimeout(function() { clearInterval(scp_vk_interval); }, 3000);
							}
						}
					}, 1000);

					initialize_VK_Widgets();

					$vk_tab.on("click", function() {
						initialize_VK_Widgets();
					});
				}
			</script>';

		$content .= '</div>';

		return $content;
	}
}

