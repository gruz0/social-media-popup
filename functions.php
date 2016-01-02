<?php
defined( 'ABSPATH' ) or exit;

define( 'L10N_SCP_PREFIX', 'social-community-popup' ); // textdomain

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

