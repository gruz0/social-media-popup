<?php
$time_start = microtime( true );

header("Content-type: text/javascript; charset=utf-8");

//FIXME: Тут возможна проблема с WP установленным не в корневую директорию сайта. В общем, оставляю на подумать.
require_once( dirname( __FILE__ ) . '/../../../../wp-load.php' );
require_once( dirname( __FILE__ ) . "/../social-community-popup.class.php" );

// Подключаем наш класс и выбираем префикс для нужной версии
$scp = new Social_Community_Popup();
$scp->set_scp_version( '0.7.2' );
$scp_prefix = $scp->get_scp_prefix();

// Загружаем все опции и формируем массив только тех, что подходят по префиксу
$all_options = wp_load_alloptions();
global $scp_options;
$scp_options = array();
foreach( $all_options as $name => $value ) {
	if ( stristr( $name, $scp_prefix ) ) $scp_options[$name] = $value;
}

// Сделаем wrapper для get_option, чтобы каждый раз не ходить в базу за настройками
function get_scp_option( $name ) {
	global $scp_prefix, $scp_options;
	$option_name = $scp_prefix . $name;
	return isset( $scp_options[$option_name] ) ? $scp_options[$option_name] : null;
}

// Отключаем работу плагина на мобильных устройствах
if ( wp_is_mobile() && get_scp_option( 'setting_show_on_mobile_devices' ) === '0' ) return;

$debug_mode                                       = intval( get_scp_option( 'setting_debug_mode' ) ) == 1;

$after_n_days                                     = (int) get_scp_option( 'setting_display_after_n_days' );

$when_should_the_popup_appear                     = split_string_by_comma( get_scp_option( 'when_should_the_popup_appear' ) );
$when_should_the_popup_appear_events              = array(
	'after_n_seconds',
	'after_clicking_on_element',
	'after_scrolling_down_n_percent',
	'on_exit_intent'
);

$popup_will_appear_after_n_seconds                = (int) get_scp_option( 'popup_will_appear_after_n_seconds' );
$popup_will_appear_after_clicking_on_element      = get_scp_option( 'popup_will_appear_after_clicking_on_element' );
$popup_will_appear_after_scrolling_down_n_percent = (int) get_scp_option( 'popup_will_appear_after_scrolling_down_n_percent' );
$popup_will_appear_on_exit_intent                 = get_scp_option( 'popup_will_appear_on_exit_intent' ) === '1';

$who_should_see_the_popup                         = split_string_by_comma( get_scp_option( 'who_should_see_the_popup' ) );
$visitor_opened_at_least_n_number_of_pages        = (int) get_scp_option( 'visitor_opened_at_least_n_number_of_pages' );

// При включённом режиме отладки плагин работает только для администратора сайта
if ( $debug_mode ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

// Если режим отладки выключен и есть кука закрытия окна или пользователь администратор — не показываем окно
} else {
	if ( is_scp_cookie_present() || current_user_can( 'manage_options' ) ) {
		return;
	}
}

// Обработка событий кому показывать окно плагина
$show_popup = false;

// Время жизни куки — 1 год
$cookie_lifetime = 31536000;

// Пользователь просмотрел больше N страниц сайта
if ( who_should_see_the_popup_has_event( $who_should_see_the_popup, 'visitor_opened_at_least_n_number_of_pages' ) ) {
	$page_views_cookie = 'scp-page-views';

	// Если окно не было закрыто другими событиями — начинаем проверку условий
	if ( ! is_scp_cookie_present() ) {

		// Если существует кука просмотренных страниц — обновляем её
		if ( isset( $_COOKIE[$page_views_cookie] ) ) {
			$page_views = intval( $_COOKIE[$page_views_cookie] ) + 1;
			setcookie( $page_views_cookie, $page_views );

			if ( $page_views > $visitor_opened_at_least_n_number_of_pages ) {
				$show_popup = true;
			}

		// Иначе создаём новую
		} else {
			setcookie( $page_views_cookie, 1, time() + $cookie_lifetime );
		}

	// Иначе удалим куку
	} else {
		setcookie( $page_views_cookie, 0, time() - 1 );
		unset( $_COOKIE[$page_views_cookie] );
	}
}

// Активна любая опция когда показывать окно
foreach ( $when_should_the_popup_appear_events as $event ) {
	if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, $event ) ) {
		$show_popup = true;
		break;
	}
}

// Если ни одно событие кому показывать окно не сработало — выходим
if ( ! $show_popup ) {
	return;
}

// Настройка плагина

$use_facebook               = get_scp_option( 'setting_use_facebook' )      === '1';
$use_vkontakte              = get_scp_option( 'setting_use_vkontakte' )     === '1';
$use_odnoklassniki          = get_scp_option( 'setting_use_odnoklassniki' ) === '1';
$use_googleplus             = get_scp_option( 'setting_use_googleplus' )    === '1';
$use_twitter                = get_scp_option( 'setting_use_twitter' )       === '1';
$use_pinterest              = get_scp_option( 'setting_use_pinterest' )     === '1';

$tabs_order                 = explode(',', get_scp_option( 'setting_tabs_order' ) );

$container_width            = get_scp_option( 'setting_container_width' );
$container_height           = get_scp_option( 'setting_container_height' ) ;
$border_radius              = absint( get_scp_option( 'setting_border_radius' ) );
$close_by_clicking_anywhere = get_scp_option( 'setting_close_popup_by_clicking_anywhere' ) === '1';
$close_when_esc_pressed     = get_scp_option( 'setting_close_popup_when_esc_pressed' ) === '1';
$show_close_button_in       = get_scp_option( 'setting_show_close_button_in' );
$overlay_color              = get_scp_option( 'setting_overlay_color' );
$overlay_opacity            = get_scp_option( 'setting_overlay_opacity' );
$align_tabs_to_center       = absint( get_scp_option( 'setting_align_tabs_to_center' ) );
$delay_before_show_bottom_button = absint( get_scp_option( 'setting_delay_before_show_bottom_button' ) );
$background_image           = get_scp_option( 'setting_background_image' );

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

