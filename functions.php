<?php
defined( 'ABSPATH' ) or exit;

$scp_new_version = get_option( 'scp-version' );

if ( $scp_new_version && '0.7.1' <= $scp_new_version ) {
	define( 'SCP_PREFIX', 'scp-' );
} else {
	define( 'SCP_PREFIX', 'social-community-popup-' );
}

define( 'L10N_SCP_PREFIX', 'social-community-popup' ); // textdomain

// Одним запросом загружаем все настройки плагина
$all_options = wp_load_alloptions();
global $scp_options;
$scp_options = array();
foreach( $all_options as $name => $value ) {
	if ( stristr( $name, SCP_PREFIX ) ) $scp_options[$name] = $value;
}

// Сделаем wrapper для get_option, чтобы каждый раз не ходить в базу за настройками
function get_scp_option( $name ) {
	global $scp_options;
	$option_name = SCP_PREFIX . $name;
	return isset( $scp_options[$option_name] ) ? $scp_options[$option_name] : null;
}

// Преобразуем строку в массив для событий при наступлении которых появится окно
function split_string_by_comma( $value ) {
	return preg_split( '/,/', $value );
}

// Проверяем наличие нужного нам события в массиве активных событий появления окна
function when_should_the_popup_appear_has_event( $haystack, $needle ) {
	return ( array_search( $needle, $haystack ) !== false );
}

// Проверяем наличие нужного нам события в массиве активных событий кому показывать окно
function who_should_see_the_popup_has_event( $haystack, $needle ) {
	return ( array_search( $needle, $haystack ) !== false );
}

function is_scp_cookie_present() {
	return ( !empty( $_COOKIE['social-community-popup'] ) && $_COOKIE['social-community-popup'] == 'true' );
}
