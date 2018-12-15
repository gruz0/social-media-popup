<?php
/**
 * Google Plus Template
 *
 * @package    Social_Media_Popup
 * @subpackage SCP_Template
 * @author     Alexander Kadyrov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       https://github.com/gruz0/social-media-popup
 */

/**
 * SCP_GooglePlus_Provider
 */
class SCP_GooglePlus_Provider extends SCP_Provider {
	/**
	 * Return widget is active
	 *
	 * @since 0.7.5
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return self::get_option_as_boolean( 'setting_use_googleplus' );
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
			'default_tab_caption' => __( 'Google+', 'social-media-popup' ),
			'tab_caption'         => self::get_option_as_escaped_string( 'setting_googleplus_tab_caption' ),
			'css_class'           => 'google-plus-tab',
			'icon'                => 'fa-google-plus',
			'url'                 => self::get_option_as_escaped_string( 'setting_googleplus_page_url' ),
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
		$content = '<div class="box" id="scp_googleplus_container">';

		$content .= self::widget_description( 'setting_googleplus_show_description', 'setting_googleplus_description' );

		$content .= '<div class="g-'  . self::get_option_as_escaped_string( 'setting_googleplus_page_type' ) . '" '
			. 'data-width="'          . self::get_option_as_integer( 'setting_googleplus_size' ) . '" '
			. 'data-href="'           . self::get_option_as_escaped_string( 'setting_googleplus_page_url' ) . '" '
			. 'data-theme="'          . self::get_option_as_escaped_string( 'setting_googleplus_theme' ) . '" '
			. 'data-layout="'         . self::get_option_as_escaped_string( 'setting_googleplus_layout' ) . '" '
			. 'data-showtagline="'    . self::get_option_as_escaped_string( 'setting_googleplus_show_tagline' ) . '" '
			. 'data-showcoverphoto="' . self::get_option_as_escaped_string( 'setting_googleplus_show_cover_photo' ) . '" '
			. 'data-rel="'            . esc_attr( self::googleplus_relation_from_page_type() ) . '"'
			. '></div>';

		$content .= '<!-- Place this tag after the last widget tag. -->
			<script type="text/javascript">
				var google_plus_initialized = 0;
				var scp_google_plus_container_size = parseInt("' . self::get_option_as_escaped_string( 'setting_googleplus_size' ) . '");

				function initialize_GooglePlus_Widgets() {
					if (jQuery("#scp_googleplus_container div iframe").length && jQuery("#scp_googleplus_container div iframe").height() < scp_google_plus_container_size) {
						jQuery("#scp_googleplus_container div, #scp_googleplus_container div iframe").height(scp_google_plus_container_size);
					}

					if (google_plus_initialized) return;

					window.___gcfg = {lang: "' . self::get_option_as_escaped_string( 'setting_googleplus_locale' ) . '"};

					var po = document.createElement("script");
					po.type  = "text/javascript";
					po.async = true;
					po.src   = "https://apis.google.com/js/platform.js";
					var s    = document.getElementsByTagName("script")[0];
					s.parentNode.insertBefore(po, s);

					google_plus_initialized = 1;
				}

				function scp_prependGooglePlus($) {
					$google_plus_tab = $("' . self::$tabs_id . ' .google-plus-tab");

					var scp_googleplus_interval = setInterval(function() {
						var container_height_is_too_small = jQuery("#scp_googleplus_container div iframe").height() < scp_google_plus_container_size;

						if (jQuery("#scp_googleplus_container div iframe").length > 0 || container_height_is_too_small) {
							jQuery("#scp_googleplus_container div, #scp_googleplus_container div iframe").height(scp_google_plus_container_size);

							container_height_is_too_small = jQuery("#scp_googleplus_container div iframe").height() < scp_google_plus_container_size;
							if (!container_height_is_too_small) {
								setTimeout(function() { clearInterval(scp_googleplus_interval); }, 3000);
							}
						}
					}, 1000);

					initialize_GooglePlus_Widgets();

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
		switch ( self::get_option_as_escaped_string( 'setting_googleplus_page_type' ) ) {
			case 'page':
				return 'publisher';
			case 'person':
				return 'author';
			default:
				return '';
		}
	}
}

