<?php

class SCP_GooglePlus_Provider extends SCP_Provider {
	/**
	 * Return widget is active
	 *
	 * @since 0.7.5
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return ( self::$options[ self::$prefix . 'setting_use_googleplus' ] === '1' );
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
			'tab_caption' => esc_attr( self::$options[ self::$prefix . 'setting_googleplus_tab_caption'] ),
			'css_class'   => 'google-plus-tab',
			'icon'        => 'fa-google-plus',
			'url'         => self::$options[ self::$prefix . 'setting_googleplus_page_url' ]
		);
	}

	/**
	 * Return widget container
	 *
	 * @uses self::googleplus_relation_from_page_type()
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	public static function container() {
		$content = '<div class="box">';

		if ( self::$options[ self::$prefix . 'setting_googleplus_show_description' ] === '1' ) {
			$content .= '<p class="widget-description"><b>' . self::$options[ self::$prefix . 'setting_googleplus_description' ] . '</b></p>';
		}

		$content .= '<div class="g-' . esc_attr( self::$options[ self::$prefix . 'setting_googleplus_page_type' ] ) . '" '
			. 'data-width="' .esc_attr( self::$options[ self::$prefix . 'setting_googleplus_size' ] ) . '" '
			. 'data-href="' .esc_attr( self::$options[ self::$prefix . 'setting_googleplus_page_url' ] ) . '" '
			. 'data-theme="' . esc_attr( self::$options[ self::$prefix . 'setting_googleplus_theme' ] ) . '" '
			. 'data-layout="' . esc_attr( self::$options[ self::$prefix . 'setting_googleplus_layout' ] ) . '" '
			. 'data-showtagline="' . esc_attr( self::$options[ self::$prefix . 'setting_googleplus_show_tagline' ] ) . '" '
			. 'data-showcoverphoto="' . esc_attr( self::$options[ self::$prefix . 'setting_googleplus_show_cover_photo' ] ) . '" '
			. 'data-rel="' . esc_attr( self::googleplus_relation_from_page_type() ) . '"'
			. '></div>';

		$content .= '<!-- Place this tag after the last widget tag. -->
			<script type="text/javascript">
				var google_plus_initialized = 0;

				function initialize_GooglePlus_Widgets() {
					if (google_plus_initialized) return;

					window.___gcfg = {lang: "' . esc_attr( self::$options[ self::$prefix . 'setting_googleplus_locale' ] ) . '"};

					var po = document.createElement("script");
					po.type  = "text/javascript";
					po.async = true;
					po.src   = "https://apis.google.com/js/platform.js";
					var s    = document.getElementsByTagName("script")[0];
					s.parentNode.insertBefore(po, s);

					google_plus_initialized = 1;
				}

				function scp_prependGooglePlus($) {
					$tabs            = $("' . self::$tabs_id . '");
					$google_plus_tab = $("' . self::$tabs_id . ' .google-plus-tab");

					if ($google_plus_tab.length && parseInt($google_plus_tab.data("index")) == 1) {
						initialize_GooglePlus_Widgets();
					} else if ($tabs.length == 0) {
						initialize_GooglePlus_Widgets();
					}

					$google_plus_tab.on("click", function() {
						initialize_GooglePlus_Widgets();
					});
				}
			</script>';

		$content .= '</div>';

		return $content;
	}

	/**
	 * Return Google+ relation type depends on page type
	 *
	 * @used_by self::container()
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	private static function googleplus_relation_from_page_type() {
		switch ( self::$options[ self::$prefix . 'setting_googleplus_page_type' ] ) {
			case 'page':
				return 'publisher';
			case 'person':
				return 'author';
			default:
				return '';
		}
	}
}

