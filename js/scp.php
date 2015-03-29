<?php
$time_start = microtime( true );

header("Content-type: text/javascript; charset=utf-8");

require_once( dirname( __FILE__ ) . '/../../../../wp-load.php' );
require_once( dirname( __FILE__ ) . "/../functions.php" );

// Отключаем работу плагина на мобильных устройствах
if ( wp_is_mobile() && get_scp_option( 'setting_show_on_mobile_devices' ) === '0' ) return;

$debug_mode = is_user_logged_in() && (int) get_scp_option( 'setting_debug_mode' );

if ( $debug_mode ) {
	$after_n_days          = 1;
	$visit_n_pages         = 0;
	$cookie_popup_views    = 0;
	$delay_after_n_seconds = 1;

} else {
	if ( isset( $_COOKIE[ 'social-community-popup' ] ) ) return;

	$after_n_days          = (int) get_scp_option( 'setting_display_after_n_days' );
	$visit_n_pages         = (int) get_scp_option( 'setting_display_after_visiting_n_pages' );
	$cookie_popup_views    = isset( $_COOKIE[ 'social-community-popup-views' ] ) ? (int) $_COOKIE[ 'social-community-popup-views' ] : 0;
	$delay_after_n_seconds = (int) get_scp_option( 'setting_display_after_delay_of_n_seconds' );
}

$use_facebook               = get_scp_option( 'setting_use_facebook' )      === '1';
$use_vkontakte              = get_scp_option( 'setting_use_vkontakte' )     === '1';
$use_odnoklassniki          = get_scp_option( 'setting_use_odnoklassniki' ) === '1';
$use_googleplus             = get_scp_option( 'setting_use_googleplus' )    === '1';
$use_twitter                = get_scp_option( 'setting_use_twitter' )       === '1';

$tabs_order                 = explode(',', get_scp_option( 'setting_tabs_order' ) );

$container_width            = get_scp_option( 'setting_container_width' );
$container_height           = get_scp_option( 'setting_container_height' ) ;
$border_radius              = absint( get_scp_option( 'setting_border_radius' ) );
$close_by_clicking_anywhere = get_scp_option( 'setting_close_popup_by_clicking_anywhere' ) === '1';

ob_start();
require_once( sprintf( "%s/../templates/popup.php", dirname( __FILE__ ) ) );
$content = ob_get_contents();
ob_end_clean();

$encoded_content = preg_replace("~[\n\t]~", "", $content);
$encoded_content = base64_encode($encoded_content);
echo 'var scp_Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=scp_Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=scp_Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};';

echo 'document.write(scp_Base64.decode("' . $encoded_content . '"));';

$time_end = microtime( true );
$execution_time = $time_end - $time_start;

echo "\n// execution time = {$execution_time}";

