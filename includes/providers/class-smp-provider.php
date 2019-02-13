<?php
/**
 * Providers base class
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

/**
 * SMP_Provider
 */
class SMP_Provider {
	/**
	 * Tabs UL identifier to use in providers JS-prepend* functions
	 *
	 * @var string $tabs_id
	 */
	static $tabs_id = null;

	/**
	 * An instance of SMP_Template class
	 *
	 * @var SMP_Template $template
	 */
	static $template = null;

	/**
	 * Returns available providers
	 *
	 * @since 0.7.3
	 *
	 * @return array
	 */
	public static function available_providers() {
		return array( 'facebook', 'vkontakte', 'odnoklassniki', 'googleplus', 'twitter', 'pinterest' );
	}

	/**
	 * Checks if provider is available
	 *
	 * @since 0.7.3
	 *
	 * @param string $provider_name Provider name
	 * @return boolean
	 */
	public static function exists( $provider_name ) {
		$providers = self::available_providers();
		return in_array( $provider_name, $providers, true );
	}

	/**
	 * Instantiate a Social Network provider
	 *
	 * @since 0.7.3
	 * @since 0.7.5 Delete $prefix argument
	 *
	 * @param string $provider Provider name (ex. facebook, vkontakte, etc.)
	 * @throws Exception Throws Exception if the provided $provider is not exist
	 * @return SMP_Provider
	 */
	public static function create( $provider ) {
		self::$tabs_id = self::tabs_id();

		// FIXME: It should be rewritten with available_providers()
		switch ( $provider ) {
			case 'facebook':
				require_once( dirname( __FILE__ ) . '/class-smp-facebook-provider.php' );
				return new SMP_Facebook_Provider();
				break;

			case 'vkontakte':
				require_once( dirname( __FILE__ ) . '/class-smp-vk-provider.php' );
				return new SMP_VK_Provider();
				break;

			case 'odnoklassniki':
				require_once( dirname( __FILE__ ) . '/class-smp-odnoklassniki-provider.php' );
				return new SMP_Odnoklassniki_Provider();
				break;

			case 'googleplus':
				require_once( dirname( __FILE__ ) . '/class-smp-googleplus-provider.php' );
				return new SMP_GooglePlus_Provider();
				break;

			case 'twitter':
				require_once( dirname( __FILE__ ) . '/class-smp-twitter-provider.php' );
				return new SMP_Twitter_Provider();
				break;

			case 'pinterest':
				require_once( dirname( __FILE__ ) . '/class-smp-pinterest-provider.php' );
				return new SMP_Pinterest_Provider();
				break;

			default:
				throw new Exception( "Provider {$provider} is not implemented!" );
		}
	}

	/**
	 * Returns provider status
	 * Should be overridden in specific provider
	 *
	 * @since 0.7.3
	 *
	 * @throws Exception Throws Exception if function called directly
	 */
	public static function is_active() {
		throw new Exception( 'Not implemented!' );
	}

	/**
	 * Returns provider options
	 * Should be overridden in specific provider
	 *
	 * @since 0.7.3
	 *
	 * @throws Exception Throws Exception if function called directly
	 */
	public static function options() {
		throw new Exception( 'Not implemented!' );
	}

	/**
	 * Add Tab caption under widget title
	 *
	 * @since 0.7.3
	 *
	 * @param array $args Options
	 * @return string
	 */
	public static function tab_caption( $args ) {
		$format  = '<li data-index="%s" class="%s"><span>%s</span></li>';
		$caption = empty( $args['tab_caption'] ) ? $args['default_tab_caption'] : $args['tab_caption'];

		return sprintf(
			$format,
			esc_attr( $args['index'] ),
			esc_attr( $args['css_class'] ),
			esc_html( $caption )
		);
	}

	/**
	 * Add Tab caption under widget title with icons for desktop devices
	 *
	 * @since 0.7.4
	 *
	 * @param array $args Options
	 * @return string
	 */
	public static function tab_caption_desktop_icons( $args ) {
		$format = '<li data-index="%s" class="%s" style="width:%s%%;"><a href="#" title="%s"><i class="fa %s %s"></i></a></li>';

		return sprintf(
			$format,
			esc_attr( $args['index'] ),
			esc_attr( $args['css_class'] ),
			sprintf( '%0.2f', floatval( $args['width'] ) ),
			self::clean_tab_caption( $args['tab_caption'] ),
			esc_attr( $args['icon'] ),
			esc_attr( $args['icon_size'] )
		);
	}

	/**
	 * Add Tab caption under widget title for mobile devices
	 *
	 * @since 0.7.4
	 *
	 * @param array $args Options
	 * @return string
	 */
	public static function tab_caption_mobile( $args ) {
		$format = '<li class="%s" style="width:%s%%;><a href="%s" target="_blank" rel="nofollow" title="%s"><i class="fa %s %s"></i></a></li>';

		return sprintf(
			$format,
			esc_attr( $args['css_class'] ),
			sprintf( '%0.2f', floatval( $args['width'] ) ),
			esc_url( $args['url'] ),
			self::clean_tab_caption( $args['tab_caption'] ),
			esc_attr( $args['icon'] ),
			esc_attr( $args['icon_size'] )
		);
	}

	/**
	 * Returns tabs UL identifier to use in providers JS-prepend* functions
	 *
	 * @since 0.7.4
	 *
	 * @return string
	 */
	private static function tabs_id() {
		if ( wp_is_mobile() ) {
			return '#smp_mobile .smp-icons';
		}

		if ( SMP_Options::get_option( 'setting_use_icons_instead_of_labels_in_tabs' ) ) {
			return '#social_media_popup .smp-icons';
		}

		return '#social_media_popup .tabs';
	}

	/**
	 * Abstract method returns a Social Network container
	 * Should be overridden in specific provider
	 *
	 * @since 0.7.3
	 *
	 * @throws Exception Throws Exception if function called directly
	 */
	public static function container() {
		throw new Exception( 'Not implemented!' );
	}

	/**
	 * Setter for self::$template to access Events Tracker in SMP_Template
	 *
	 * @since 0.7.5
	 *
	 * @param SMP_Template $template SMP_Template instance to get access to Events Tracker
	 */
	public static function set_template( $template ) {
		self::$template = $template;
	}

	/**
	 * Returns widget's description
	 *
	 * @since 0.7.6
	 *
	 * @param string $show_description_option_name Option name
	 * @param string $description_option_name      Description
	 *
	 * @return string
	 */
	protected static function widget_description( $show_description_option_name, $description_option_name ) {
		if ( SMP_Options::get_option( $show_description_option_name ) ) {
			return '<p class="widget-description"><b>' . SMP_Options::get_option( $description_option_name ) . '</b></p>';
		}

		return '';
	}

	/**
	 * Removes new lines and uneccessary chars from tab caption
	 *
	 * @since 0.7.4
	 *
	 * @param string $tab_caption Tab caption
	 * @return string
	 */
	private static function clean_tab_caption( $tab_caption ) {
		return trim( str_replace( "\r\n", '', $tab_caption ) );
	}
}

