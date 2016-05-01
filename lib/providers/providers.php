<?php

class SCP_Provider {
	static $prefix = null;
	static $options = null;

	public static function create( $provider, $prefix, $options ) {
		self::$prefix = $prefix;
		self::$options = $options;

		switch( $provider ) {
			case 'facebook': {
				require_once( dirname( __FILE__ ) . '/vkontakte.php' );
				return new SCP_VK_Provider();
			}
			break;

			case 'vkontakte': {
				require_once( dirname( __FILE__ ) . '/vkontakte.php' );
				return new SCP_VK_Provider();
			}
			break;

			case 'odnoklassniki': {
				require_once( dirname( __FILE__ ) . '/vkontakte.php' );
				return new SCP_VK_Provider();
			}
			break;

			case 'googleplus': {
				require_once( dirname( __FILE__ ) . '/vkontakte.php' );
				return new SCP_VK_Provider();
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

	public static function container( $args ) {
		throw new Exception( "Not implemented!" );
	}
}

