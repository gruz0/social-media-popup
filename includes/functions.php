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
function is_smp_cookie_present() {
	return ( ! empty( $_COOKIE['social-media-popup'] ) && 'true' === $_COOKIE['social-media-popup'] );
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

/**
 * Helper to render settings tabs
 *
 * @param array  $tabs        Tabs array (key => slug, value => label)
 * @param string $current_tab Current tab slug
 * @param string $page_suffix Additional suffix to tab slug
 */
function smp_render_settings_tabs( $tabs, $current_tab, $page_suffix = '' ) {
	$tab_template = '<a class="nav-tab %s" href="?page=%s%s&tab=%s">%s</a>';

	$content = '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab === $tab_key ? 'nav-tab-active' : '';

		$content .= sprintf(
			$tab_template,
			esc_attr( $active ),
			SMP_PREFIX,
			$page_suffix,
			esc_attr( $tab_key ),
			esc_html( $tab_caption )
		);
	}
	$content .= '</h2>';

	// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $content;
	// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Convert integer value to stringified boolean
 *
 * @param string $variable Value
 * @return string
 */
function smp_stringify_boolean( $variable ) {
	return ( '1' === $variable ? 'true' : 'false' );
}
