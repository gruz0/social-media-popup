<?php
/**
 * Providers base class
 *
 * @package    Social_Media_Popup
 * @subpackage SCP_Template
 * @author     Alexander Kadyrov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       https://github.com/gruz0/social-media-popup
 */

/**
 * SCP_Provider
 */
class SCP_Provider {
	/**
	 * Option name prefix
	 *
	 * @var string $prefix
	 */
	static $prefix = null;

	/**
	 * Plugin options
	 *
	 * @var array $options
	 */
	static $options = null;

	/**
	 * Tabs UL identifier to use in providers JS-prepend* functions
	 *
	 * @var string $tabs_id
	 */
	static $tabs_id = null;

	/**
	 * An instance of SCP_Template class
	 *
	 * @var SCP_Template $template
	 */
	static $template = null;

	/**
	 * Using for cached options values
	 *
	 * @var array $cached_option_values
	 */
	protected static $cached_option_values = array();

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
	 *
	 * @param string $provider Provider name (ex. facebook, vkontakte, etc.)
	 * @param string $prefix SCP options prefix (default: 'scp-')
	 * @param array  $options Plugin options
	 * @throws Exception Throws Exception if the provided $provider is not exist
	 * @return SCP_Provider
	 */
	public static function create( $provider, $prefix, $options ) {
		self::$prefix = $prefix;
		self::$options = $options;
		self::$tabs_id = self::tabs_id();

		// FIXME: Переписать на проверку провайдера в массиве available_providers()
		switch ( $provider ) {
			case 'facebook': {
				require_once( dirname( __FILE__ ) . '/facebook.php' );
				return new SCP_Facebook_Provider();
			}
			break;

			case 'vkontakte': {
				require_once( dirname( __FILE__ ) . '/vkontakte.php' );
				return new SCP_VK_Provider();
			}
			break;

			case 'odnoklassniki': {
				require_once( dirname( __FILE__ ) . '/odnoklassniki.php' );
				return new SCP_Odnoklassniki_Provider();
			}
			break;

			case 'googleplus': {
				require_once( dirname( __FILE__ ) . '/googleplus.php' );
				return new SCP_GooglePlus_Provider();
			}
			break;

			case 'twitter': {
				require_once( dirname( __FILE__ ) . '/twitter.php' );
				return new SCP_Twitter_Provider();
			}
			break;

			case 'pinterest': {
				require_once( dirname( __FILE__ ) . '/pinterest.php' );
				return new SCP_Pinterest_Provider();
			}
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
		return '<li '
			. 'data-index="' . $args['index'] . '" '
			. 'class="' . $args['css_class'] . '" '
			. '><span>' . ( empty( $args['tab_caption'] ) ? $args['default_tab_caption'] : $args['tab_caption'] ) . '</span></li>';
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
		return '<li '
			. 'data-index="' . $args['index'] . '" '
			. 'class="' . $args['css_class'] . '" '
			. 'style="width:' . sprintf( '%0.2f', floatval( $args['width'] ) ) . '%;" '
			. '><a href="#" title="' . self::clean_tab_caption( $args['tab_caption'] ) . '">'
			. '<i class="fa ' . $args['icon'] . ' ' . $args['icon_size'] . '"></i></a></li>';
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
		return '<li '
			. 'class="' . $args['css_class'] . '" '
			. 'style="width:' . sprintf( '%0.2f', floatval( $args['width'] ) ) . '%;" '
			. '><a href="' . $args['url'] . '" target="_blank" rel="nofollow" title="' . self::clean_tab_caption( $args['tab_caption'] ) . '">'
			. '<i class="fa ' . $args['icon'] . ' ' . $args['icon_size'] . '"></i></a></li>';
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
			return '#scp_mobile .scp-icons';
		} else {
			if ( '1' === self::$options[ self::$prefix . 'setting_use_icons_instead_of_labels_in_tabs' ] ) {
				return '#social-community-popup .scp-icons';
			} else {
				return '#social-community-popup .tabs';
			}
		}
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
	 * Setter for self::$template to access Events Tracker in SCP_Template
	 *
	 * @since 0.7.5
	 *
	 * @param SCP_Template $template SCP_Template instance to get access to Events Tracker
	 */
	public static function set_template( $template ) {
		self::$template = $template;
	}

	/**
	 * Returns option by short name as escaped string
	 *
	 * @since 0.7.5
	 *
	 * @param string $option_name Option name
	 * @return string
	 */
	protected static function get_option_as_escaped_string( $option_name ) {
		if ( ! isset( self::$cached_option_values[ $option_name ] ) ) {
			self::$cached_option_values[ $option_name ] = esc_attr( self::$options[ self::$prefix . $option_name ] );
		}

		return self::$cached_option_values[ $option_name ];
	}

	/**
	 * Returns option by short name as absolute integer
	 *
	 * @since 0.7.5
	 *
	 * @param string $option_name Option name
	 * @return integer
	 */
	protected static function get_option_as_integer( $option_name ) {
		if ( ! isset( self::$cached_option_values[ $option_name ] ) ) {
			self::$cached_option_values[ $option_name ] = absint( self::$options[ self::$prefix . $option_name ] );
		}

		return self::$cached_option_values[ $option_name ];
	}

	/**
	 * Returns option by short name as boolean
	 *
	 * @since 0.7.5
	 *
	 * @param string $option_name Option name
	 * @return boolean
	 */
	protected static function get_option_as_boolean( $option_name ) {
		if ( ! isset( self::$cached_option_values[ $option_name ] ) ) {
			self::$cached_option_values[ $option_name ] = absint( self::$options[ self::$prefix . $option_name ] ) === 1;
		}

		return self::$cached_option_values[ $option_name ];
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

