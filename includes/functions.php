<?php
/**
 * Functions
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

define( 'DEFAULT_TAB_SLUG', 'general' );

/**
 * Преобразуем строку в массив для событий при наступлении которых появится окно
 *
 * @param string $value String to split
 * @return array
 */
function split_string_by_comma( $value ) {
	return preg_split( '/,/', $value );
}

/**
 * Проверяем наличие нужного нам события в массиве активных событий появления окна
 *
 * @param string $haystack Haystack
 * @param string $needle Needle
 * @return boolean
 */
function when_should_the_popup_appear_has_event( $haystack, $needle ) {
	return ( array_search( $needle, $haystack, true ) !== false );
}

/**
 * Проверяем наличие нужного нам события в массиве активных событий кому показывать окно
 *
 * @param string $haystack Haystack
 * @param string $needle Needle
 * @return boolean
 */
function who_should_see_the_popup_has_event( $haystack, $needle ) {
	return ( array_search( $needle, $haystack, true ) !== false );
}

/**
 * Checks is cookie present
 *
 * @return boolean
 */
function is_scp_cookie_present() {
	return ( ! empty( $_COOKIE['social-community-popup'] ) && 'true' === $_COOKIE['social-community-popup'] );
}

/**
 * Checks if current tab in the array of available tabs
 *
 * @param string $slug           Tab slug
 * @param array  $available_tabs Available tabs
 * @param string $default_value  Default tab slug
 *
 * @return string
 */
function smp_validate_and_sanitize_tab( $slug, $available_tabs = array(), $default_value = DEFAULT_TAB_SLUG ) {
	$slug = wp_unslash( $slug );

	if ( empty( $slug ) ) return $default_value;
	if ( ! in_array( $slug, $available_tabs, true ) ) return $default_value;

	return sanitize_key( $slug );
}

// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_error_log
// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r
if ( ! function_exists( 'write_log' ) ) {
	/**
	 * Write debug message to wp-content/debug.log
	 *
	 * @param object $log Object or array or something else
	 * @return void
	 */
	function write_log( $log ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}
// phpcs:enable WordPress.PHP.DevelopmentFunctions.error_log_print_r
// phpcs:enable WordPress.PHP.DevelopmentFunctions.error_log_error_log
