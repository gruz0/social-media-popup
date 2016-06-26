<?php

class SCP_VK_Provider extends SCP_Provider {
	public static function is_active() {
		return ( self::$options[ self::$prefix . 'setting_use_vkontakte' ] === '1' );
	}

	public static function options() {
		return array(
			'tab_caption' => esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_tab_caption'] ),
			'css_class'   => 'vk-tab',
			'icon'        => 'fa-vk',
			'url'         => self::$options[ self::$prefix . 'setting_vkontakte_page_url' ]
		);
	}

	public static function container() {
		$default_vk_group_id = 1;

		$content = '<div class="box">';

		if ( self::$options[ self::$prefix . 'setting_vkontakte_show_description' ] === '1' ) {
			$content .= '<p class="widget-description"><b>' . self::$options[ self::$prefix . 'setting_vkontakte_description' ] . '</b></p>';
		}

		$application_id = esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_application_id' ] );
		if ( empty( $application_id ) ) {
			$application_id = 1;
		}

		$page_or_group_id = esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_page_or_group_id' ] );
		if ( empty( $page_or_group_id ) ) {
			$page_or_group_id = $default_vk_group_id;
		}

		$delay_before_render = esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_delay_before_render' ] );
		if ( empty( $delay_before_render ) ) {
			$delay_before_render = 0;
		}

		$content .= '<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
			<div id="scp_vk_groups" style="height:250px !important;"></div>
			<script type="text/javascript">
				var vk_initialized = 0;
				var scp_VK_closeWindowAfterJoiningGroup = ' . ( (int) self::$options[ self::$prefix . 'setting_vkontakte_close_window_after_join' ] ) . ';

				function initialize_VK_Widgets() {
					if ((typeof(VK) === "undefined") || !vk_initialized) {
						jQuery.getScript( "//vk.com/js/api/openapi.js?115", function(data, textStatus, jqxhr) {
							VK.init({apiId: ' . $application_id . ' });

							VK.Observer.subscribe("widgets.groups.joined", function f() {
								if ( scp_VK_closeWindowAfterJoiningGroup ) {
									scp_destroyPlugin(scp.showWindowAfterReturningNDays);
								}
							});

							VK.Observer.subscribe("widgets.groups.leaved", function f() {});

							VK.Widgets.Group("scp_vk_groups", {
								mode: ' . esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_layout' ] ) . ',
								width: "' . esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_width' ] ) . '",
								height: "' . esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_height' ] ) . '",
								color1: "' . esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_color_background' ] ) . '",
								color2: "' . esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_color_text' ] ) . '",
								color3: "' . esc_attr( self::$options[ self::$prefix . 'setting_vkontakte_color_button' ] ) . '"
							}, ' . $page_or_group_id . ');
							vk_initialized = 1;
						});
					}
				}

				function scp_prependVK($) {
					timeout = ' . $delay_before_render . ';

					$tabs   = $("' . self::$tabs_id . '");
					$vk_tab = $("' . self::$tabs_id . ' .vk-tab");

					if ($vk_tab.length && parseInt($vk_tab.data("index")) == 1) {
						setTimeout("initialize_VK_Widgets();", timeout);
					} else if ($tabs.length == 0) {
						setTimeout("initialize_VK_Widgets();", timeout);
					}

					$vk_tab.on("click", function() {
						if ((typeof(VK) !== "undefined") && !vk_initialized) {
							setTimeout("initialize_VK_Widgets();", timeout);
						}
					});
				}
			</script>';

		$content .= '</div>';

		return $content;
	}
}

