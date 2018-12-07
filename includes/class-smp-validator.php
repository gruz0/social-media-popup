<?php
/**
 * Social Media Popup Settings Validator
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

/**
 * Validator
 *
 * @since 0.7.5
 */
class SMP_Validator {
	/**
	 * Options array
	 *
	 * @var $options
	 */
	private $options = array();

	/**
	 * CTOR
	 *
	 * @since 0.7.5
	 *
	 * @param array $options Options
	 */
	public function __construct( $options ) {
		$this->options = $options;
	}

	/**
	 * Validator
	 *
	 * @since 0.7.5
	 *
	 * @used_by Social_Media_Popup()->validate_settings()
	 *
	 * @return string
	 */
	public function validate() {
		// TODO: Добавить проверку размеров высоты и ширины виджетов, чтобы они не заходили за размеры контейнера

		$errors = array();

		//
		// Основные настройки
		//
		$errors['General'] = array();

		// Проверяем активность Режима отладки
		if ( 1 === absint( $this->options['setting_debug_mode'] ) ) {
			$errors['General'][] = __( 'Debug mode is activated', 'social-media-popup' );
		}

		// Проверяем активность хотя бы одного виджета
		if ( 0 === absint( $this->options['setting_use_facebook'] )
			&& 0 === absint( $this->options['setting_use_vkontakte'] )
			&& 0 === absint( $this->options['setting_use_odnoklassniki'] )
			&& 0 === absint( $this->options['setting_use_googleplus'] )
			&& 0 === absint( $this->options['setting_use_twitter'] )
			&& 0 === absint( $this->options['setting_use_pinterest'] ) ) {
			$errors['General'][] = __( 'No one of social networks are enabled', 'social-media-popup' );
		}

		//
		// Внешний вид (обычные браузеры)
		//
		$errors['View'] = array();

		// Проверяем возможность закрытия окна
		if ( 0 === absint( $this->options['setting_close_popup_by_clicking_anywhere'] )
			&& 0 === absint( $this->options['setting_close_popup_when_esc_pressed'] )
			&& 'none' === $this->options['setting_show_close_button_in']
			&& 0 === absint( $this->options['setting_show_button_to_close_widget'] ) ) {

			// Проверяем возможность закрытия окна хотя бы через одну социальную сеть
			$allowed_to_close_in = array();
			if ( 1 === absint( $this->options['setting_facebook_close_window_after_join'] ) ) {
				$allowed_to_close_in[] = __( 'Facebook', 'social-media-popup' );
			}

			if ( 1 === absint( $this->options['setting_vkontakte_close_window_after_join'] ) ) {
				$allowed_to_close_in[] = __( 'VKontakte', 'social-media-popup' );
			}

			if ( 1 === absint( $this->options['setting_twitter_close_window_after_join'] ) ) {
				$allowed_to_close_in[] = __( 'Twitter', 'social-media-popup' );
			}

			$message = __( 'The user will not be able to close the window', 'social-media-popup' );

			if ( 0 === count( $allowed_to_close_in ) ) {
				$errors['View'][] = $message;
			} else {
				$errors['View'][] = $message . __( '. It happens only if user will choose ', 'social-media-popup' )
					. join( $allowed_to_close_in, __( ' or ', 'social-media-popup' ) ) . __( ' widget(-s)', 'social-media-popup' );
			}
		}

		// Проверяем доступность фонового изображения виджета
		if ( '' !== $this->options['setting_background_image'] ) {
			$headers = wp_get_http_headers( $this->options['setting_background_image'] );

			if ( ! $headers ) {
				$errors['View'][] = __( 'Background image is not available by URL:', 'social-media-popup' ) . ' ' . $this->options['setting_background_image'];
			} else {
				$allowed_mime_types = array( 'image/jpeg', 'image/gif', 'image/png', 'image/bmp' );
				if ( ! in_array( $headers['content-type'], $allowed_mime_types, true ) ) {
					$errors['View'][] = __( 'Background image has incorrect type. Allowed only:', 'social-media-popup' ) . ' ' . join( $allowed_mime_types, ', ' );
				}
			}
		}

		//
		// Отслеживание
		//
		$errors['Tracking'] = array();

		if ( 1 === absint( $this->options['use_events_tracking'] ) ) {
			if ( empty( $this->options['google_analytics_tracking_id'] ) ) {
				$errors['Tracking'][] = __( 'Tracking is activated but Google Analytics tracking code is empty', 'social-media-popup' );
			}
		}

		//
		// Facebook
		//
		$errors['Facebook'] = array();

		if ( 1 === absint( $this->options['setting_use_facebook'] ) ) {
			// Facebook Application ID
			if ( empty( $this->options['setting_facebook_application_id'] ) ) {
				$errors['Facebook'][] = __( 'Application ID is empty', 'social-media-popup' );
			} else {
				if ( ! is_numeric( $this->options['setting_facebook_application_id'] ) ) {
					$errors['Facebook'][] = __( 'Application ID is not a number', 'social-media-popup' );
				}
			}

			// Facebook Page URL
			if ( empty( $this->options['setting_facebook_page_url'] ) ) {
				$errors['Facebook'][] = __( 'Page URL is empty', 'social-media-popup' );
			} else {
				if ( false === filter_var( $this->options['setting_facebook_page_url'], FILTER_VALIDATE_URL ) ) {
					$errors['Facebook'][] = __( 'Page URL is not a valid URL', 'social-media-popup' );
				}
			}
		}

		//
		// VK
		//
		$errors['VKontakte'] = array();

		if ( 1 === absint( $this->options['setting_use_vkontakte'] ) ) {
			// VK Application ID
			if ( empty( $this->options['setting_vkontakte_application_id'] ) ) {
				$errors['VKontakte'][] = __( 'Application ID is empty', 'social-media-popup' );
			} else {
				if ( ! is_numeric( $this->options['setting_vkontakte_application_id'] ) ) {
					$errors['VKontakte'][] = __( 'Application ID is not a number', 'social-media-popup' );
				}
			}

			// VK Page or Group ID
			if ( empty( $this->options['setting_vkontakte_page_or_group_id'] ) ) {
				$errors['VKontakte'][] = __( 'Page or Group ID is empty', 'social-media-popup' );
			} else {
				if ( ! is_numeric( $this->options['setting_vkontakte_page_or_group_id'] ) ) {
					$errors['VKontakte'][] = __( 'Page or Group ID is not a number', 'social-media-popup' );
				}
			}

			// VK Page URL
			if ( empty( $this->options['setting_vkontakte_page_url'] ) ) {
				$errors['VKontakte'][] = __( 'Page URL is empty', 'social-media-popup' );
			} else {
				if ( false === filter_var( $this->options['setting_vkontakte_page_url'], FILTER_VALIDATE_URL ) ) {
					$errors['VKontakte'][] = __( 'Page URL is not a valid URL', 'social-media-popup' );
				}
			}
		}

		//
		// Odnoklassniki
		//
		$errors['Odnoklassniki'] = array();

		if ( 1 === absint( $this->options['setting_use_odnoklassniki'] ) ) {
			// Odnoklassniki Application ID
			if ( empty( $this->options['setting_odnoklassniki_group_id'] ) ) {
				$errors['Odnoklassniki'][] = __( 'Group ID is empty', 'social-media-popup' );
			} else {
				if ( ! is_numeric( $this->options['setting_odnoklassniki_group_id'] ) ) {
					$errors['Odnoklassniki'][] = __( 'Group ID is not a number', 'social-media-popup' );
				}
			}

			// Odnoklassniki Group URL
			if ( empty( $this->options['setting_odnoklassniki_group_url'] ) ) {
				$errors['Odnoklassniki'][] = __( 'Group URL is empty', 'social-media-popup' );
			} else {
				if ( false === filter_var( $this->options['setting_odnoklassniki_group_url'], FILTER_VALIDATE_URL ) ) {
					$errors['Odnoklassniki'][] = __( 'Group URL is not a valid URL', 'social-media-popup' );
				}
			}
		}

		//
		// Google+
		//
		$errors['Google+'] = array();

		if ( 1 === absint( $this->options['setting_use_googleplus'] ) ) {
			// Google+ Page URL
			if ( empty( $this->options['setting_googleplus_page_url'] ) ) {
				$errors['Google+'][] = __( 'Group URL is empty', 'social-media-popup' );
			} else {
				// Адреса страниц в Google+ могут начинаться с двух слешей, поэтому требуется дополнительная проверка
				$googleplus_page_url = $this->options['setting_googleplus_page_url'];

				if ( '//' === mb_substr( $this->options['setting_googleplus_page_url'], 0, 2 ) ) {
					$googleplus_page_url = 'https:' . $googleplus_page_url;
				}

				if ( false === filter_var( $googleplus_page_url, FILTER_VALIDATE_URL ) ) {
					$errors['Google+'][] = __( 'Group URL is not a valid URL', 'social-media-popup' );
				}
			}
		}

		//
		// Twitter
		//
		$errors['Twitter'] = array();

		if ( 1 === absint( $this->options['setting_use_twitter'] ) ) {
			// Twitter Username
			if ( empty( $this->options['setting_twitter_username'] ) ) {
				$errors['Twitter'][] = __( 'Username is empty', 'social-media-popup' );
			}

			// Проверяем активность любого из виджетов
			if ( 0 === absint( $this->options['setting_twitter_use_follow_button'] ) && 0 === absint( $this->options['setting_twitter_use_timeline'] ) ) {
				$errors['Twitter'][] = __( 'No one of available Twitter widgets are selected', 'social-media-popup' );
			}
		}

		//
		// Pinterest
		//
		$errors['Pinterest'] = array();

		if ( 1 === absint( $this->options['setting_use_pinterest'] ) ) {
			// Twitter Username
			if ( empty( $this->options['setting_pinterest_profile_url'] ) ) {
				$errors['Pinterest'][] = __( 'Profile URL is empty', 'social-media-popup' );
			} else {
				if ( false === filter_var( $this->options['setting_pinterest_profile_url'], FILTER_VALIDATE_URL ) ) {
					$errors['Pinterest'][] = __( 'Profile URL is not a valid URL', 'social-media-popup' );
				}
			}
		}

		//
		// Формируем текст для рендеринга
		//

		$content = '';

		foreach ( $errors as $provider => $errors_values ) {
			if ( count( $errors_values ) ) {
				// @codingStandardsIgnoreLine
				$content .= '<h3>' . __( $provider, 'social-media-popup' ) . '</h3>';
				$content .= '<ul class="ul-disc">';

				for ( $idx = 0; $idx < count( $errors_values ); $idx++ ) {
					$content .= '<li>' . $errors_values[ $idx ] . '</li>';
				}

				$content .= '</ul>';
			}
		}

		if ( empty( $content ) ) {
			$content = __( 'No errors occurred!', 'social-media-popup' );
		}

		return $content;
	}
}
