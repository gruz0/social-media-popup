<?php
defined( 'ABSPATH' ) or exit;

define( 'SCP_PREFIX', 'social-community-popup-' );
define( 'L10N_SCP_PREFIX', 'social-community-popup' ); // textdomain

// Одним запросом загружаем все настройки плагина
$all_options = wp_load_alloptions();
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

