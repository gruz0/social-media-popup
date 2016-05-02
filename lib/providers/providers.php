<?php

class SCP_Provider {
	static $prefix = null;
	static $options = null;

	/**
	 * Returns available providers
	 *
	 * @since 0.7.3
	 *
	 * @return array
	 */
	public static function available_providers() {
		return array( 'facebook', 'vkontakte', 'odnoklassniki', 'googleplus' ); //, 'twitter', 'pinterest' );
	}

	/**
	 * Checks if provider is available
	 *
	 * @since 0.7.3
	 *
	 * @return boolean
	 */
	public static function exists( $provider_name ) {
		$providers = self::available_providers();
		return in_array( $provider_name, $providers );
	}

	/**
	 * Instantiate a Social Network provider
	 *
	 * @since 0.7.3
	 *
	 * @param string $provider Provider name (ex. facebook, vkontakte, etc.)
	 * @param string $prefix SCP options prefix (default: 'scp-')
	 * @param array $options Options for specific provider
	 * @return SCP_Provider
	 */
	public static function create( $provider, $prefix, $options ) {
		self::$prefix = $prefix;
		self::$options = $options;

		// FIXME: Переписать на проверку провайдера в массиве available_providers()
		switch( $provider ) {
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
				require_once( dirname( __FILE__ ) . '/vkontakte.php' );
				return new SCP_VK_Provider();
			}
			break;

			case 'pinterest': {
				require_once( dirname( __FILE__ ) . '/vkontakte.php' );
				return new SCP_VK_Provider();
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
	 * @return boolean
	 */
	public static function is_active() {
		throw new Exception( "Not implemented!" );
	}

	/**
	 * Returns Tab Caption options
	 * Should be overridden in specific provider
	 *
	 * @since 0.7.3
	 *
	 * @return array
	 */
	public static function provide_options_to_tab_caption() {
		throw new Exception( "Not implemented!" );
	}

	/**
	 * Add Tab caption under widget title
	 *
	 * @since 0.7.3
	 *
	 * @param array @args
	 * @return string
	 */
	public static function tab_caption( $args ) {
		return '<li data-index="' . $args['index'] . '" class="' . $args['css_class'] . '"><span>' . $args['value'] . '</span></li>';
	}

	/**
	 * Abstract method returns a Social Network container
	 * Should be overridden in specific provider
	 *
	 * @since 0.7.3
	 *
	 * @param array @args
	 * @return string
	 */
	public static function container( $args ) {
		throw new Exception( "Not implemented!" );
	}
}
