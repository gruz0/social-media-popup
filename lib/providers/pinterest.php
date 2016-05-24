<?php

class SCP_Pinterest_Provider extends SCP_Provider {
	public static function is_active() {
		return ( self::$options[ self::$prefix . 'setting_use_pinterest' ] === '1' );
	}

	public static function provide_options_to_tab_caption() {
		return array(
			'value'     => esc_attr( self::$options[ self::$prefix . 'setting_pinterest_tab_caption'] ),
			'css_class' => 'pinterest-tab',
			'icon'      => 'fa-pinterest'
		);
	}

	public static function container() {

		$content = '<div class="box">';

		if ( self::$options[ self::$prefix . 'setting_pinterest_show_description' ] === '1' ) {
			$content .= '<p class="widget-description"><b>' . self::$options[ self::$prefix . 'setting_pinterest_description' ] . '</b></p>';
		}

		$content .= '<a data-pin-do="embedUser" '
			. 'href="' . self::$options[ self::$prefix . 'setting_pinterest_profile_url' ] . '" '
			. 'data-pin-scale-width="' . self::$options[ self::$prefix . 'setting_pinterest_image_width' ] . '" '
			. 'data-pin-board-width="' . self::$options[ self::$prefix . 'setting_pinterest_width' ] . '" '
			. 'data-pin-scale-height="' . self::$options[ self::$prefix . 'setting_pinterest_height' ] . '"'
			. '></a>';

		$content .= '<script type="text/javascript">
			var pinterest_initialized = 0;

			function initialize_Pinterest_Widgets() {
				if (pinterest_initialized) return;

				var d = document;
				var f = d.getElementsByTagName("SCRIPT")[0], p = d.createElement("SCRIPT");
				p.type = "text/javascript";
				p.async = true;
				p.src = "//assets.pinterest.com/js/pinit.js";
				f.parentNode.insertBefore(p, f);

				pinterest_initialized = 1;
			}

			function scp_prependPinterest($) {
				$tabs          = $("#social-community-popup .tabs");
				$pinterest_tab = $("#social-community-popup .pinterest-tab");

				if ($pinterest_tab.length && parseInt($pinterest_tab.data("index")) == 1) {
					initialize_Pinterest_Widgets();
				} else if ($tabs.length == 0) {
					initialize_Pinterest_Widgets();
				}

				$pinterest_tab.on("click", function() {
					initialize_Pinterest_Widgets();
				});
			}
		</script>';

		$content .= '</div>';

		return $content;
	}
}

