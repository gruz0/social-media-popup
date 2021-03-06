<?php
/**
 * Facebook Template
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

/**
 * SMP_Facebook_Provider
 */
class SMP_Facebook_Provider extends SMP_Provider {
	/**
	 * Facebook API Version
	 *
	 * @since 0.7.5
	 */
	const API_VERSION = '3.2';

	/**
	 * Return widget is active
	 *
	 * @since 0.7.5
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return self::get_option_as_boolean( 'setting_use_facebook' );
	}

	/**
	 * Return options as array
	 *
	 * @since 0.7.5
	 *
	 * @return array
	 */
	public static function options() {
		return array(
			'default_tab_caption' => __( 'Facebook', 'social-media-popup' ),
			'tab_caption'         => self::get_option_as_escaped_string( 'setting_facebook_tab_caption' ),
			'css_class'           => 'facebook-tab',
			'icon'                => 'fa-facebook',
			'url'                 => self::get_option_as_escaped_string( 'setting_facebook_page_url' ),
		);
	}

	/**
	 * Render widget container
	 *
	 * @uses smp_stringify_boolean()
	 * @uses SMP_Template()->use_events_tracking()
	 * @uses SMP_Template()->push_social_media_trigger_to_google_analytics()
	 * @uses SMP_Template()->push_social_network_and_action_to_google_analytics()
	 * @uses SMP_Facebook_Provider::prepare_facebook_widget()
	 * @uses SMP_Facebook_Provider::prepare_facebook_content()
	 * @uses SMP_Facebook_Provider::prepare_facebook_events()
	 *
	 * @uses_by SMP_Facebook_Provider::container();
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	public static function container() {
		$content = '<div class="box">';

		$content .= self::widget_description( 'setting_facebook_show_description', 'setting_facebook_description' );

		$content .= self::prepare_facebook_widget();

		$content .= '<script>function smp_prependFacebook($) {';

		$content .= self::prepare_facebook_content();
		$content .= self::prepare_facebook_events();

		$content .= '}</script>';

		$content .= '</div>';

		return $content;
	}

	/**
	 * Returns Facebook widget code
	 *
	 * @since 0.7.5
	 *
	 * @used_by SMP_Facebook_Provider::container();
	 *
	 * @return string
	 */
	private static function prepare_facebook_widget() {
		return '<div class="fb-page" '
			. 'data-href="'                  . self::get_option_as_escaped_string( 'setting_facebook_page_url' ) . '" '
			. 'data-width="'                 . self::get_option_as_integer( 'setting_facebook_width' ) . '" '
			. 'width="'                      . self::get_option_as_integer( 'setting_facebook_width' ) . '" '
			. 'data-height="'                . self::get_option_as_integer( 'setting_facebook_height' ) . '" '
			. 'height="'                     . self::get_option_as_integer( 'setting_facebook_height' ) . '" '
			. 'data-hide-cover="'            . smp_stringify_boolean( self::get_option_as_escaped_string( 'setting_facebook_hide_cover' ) ) . '" '
			. 'data-show-facepile="'         . smp_stringify_boolean( self::get_option_as_escaped_string( 'setting_facebook_show_facepile' ) ) . '" '
			. 'data-adapt-container-width="' . smp_stringify_boolean( self::get_option_as_escaped_string( 'setting_facebook_adapt_container_width' ) ) . '" '
			. 'data-small-header="'          . smp_stringify_boolean( self::get_option_as_escaped_string( 'setting_facebook_use_small_header' ) ) . '" '
			. 'data-tabs="'                  . self::get_option_as_escaped_string( 'setting_facebook_tabs' ) . '" '
			. '></div>';
	}

	/**
	 * Prepare Facebook container
	 *
	 * @since 0.7.5
	 *
	 * @used_by SMP_Facebook_Provider::container();
	 *
	 * @return string
	 */
	private static function prepare_facebook_content() {
		$prepend_facebook = '<div id="fb-root"></div><script>';

		// Подключаем сам Facebook
		$prepend_facebook .= '(function(d, s, id) {'
			. 'var js, fjs = d.getElementsByTagName(s)[0];'
			. 'if (d.getElementById(id)) return;'
			. 'js = d.createElement(s); js.id = id;'
			. 'js.src = "//connect.facebook.net/' . self::get_option_as_escaped_string( 'setting_facebook_locale' )
			. '/sdk.js#xfbml=1&appId=' .            self::get_option_as_escaped_string( 'setting_facebook_application_id' ) . '&version=v' . self::API_VERSION . '";'
			. 'fjs.parentNode.insertBefore(js, fjs);'
			. '}(document, "script", "facebook-jssdk"));</script>';

		// Удаляем переносы строк, иначе jQuery ниже не отработает
		$prepend_facebook = str_replace( "\n", '', $prepend_facebook );

		// Переводим код в сущности
		$prepend_facebook = htmlspecialchars( $prepend_facebook, ENT_QUOTES );

		return 'if ($("#fb-root").length == 0) {
			$("body").prepend($("<div/>").html("' . esc_attr( $prepend_facebook ) . '").text());
		}';
	}

	/**
	 * Prepare Facebook events HTML code
	 *
	 * @since 0.7.5
	 *
	 * @used_by SMP_Facebook_Provider::container();
	 *
	 * @return string
	 */
	private static function prepare_facebook_events() {
		$facebook_events = '<script>';

		// Формирует колбэк для обработки событий при закрытии окна и подписке на группу
		$facebook_events .= 'var smp_facebook_page_like_callback = function(url, html_element) {';

		if ( absint( self::$options['setting_facebook_close_window_after_join'] ) ) {
			$facebook_events .= 'smp_destroyPlugin(smp_cookies.showWindowAfterReturningNDays);';
		}

		// FIXME: Should be refactored with self::use_widget() and move second condition to it
		if ( self::$template->use_events_tracking() && self::get_option_as_boolean( 'tracking_use_facebook' ) ) {
			$facebook_events .= self::$template->push_social_media_trigger_to_google_analytics( self::get_option_as_escaped_string( 'tracking_facebook_subscribe_event' ) );
			$facebook_events .= self::$template->push_social_network_and_action_to_google_analytics( 'SMP Facebook', 'Subscribe' );
		}

		$facebook_events .= '};';

		// Формирует колбэк для обработки событий при отписке от группы
		$facebook_events .= 'var smp_facebook_page_unlike_callback = function(url, html_element) {';

		// FIXME: Should be refactored with self::use_widget() and move second condition to it
		if ( self::$template->use_events_tracking() && self::get_option_as_boolean( 'tracking_use_facebook' ) ) {
			$facebook_events .= self::$template->push_social_media_trigger_to_google_analytics( self::get_option_as_escaped_string( 'tracking_facebook_unsubscribe_event' ) );
			$facebook_events .= self::$template->push_social_network_and_action_to_google_analytics( 'SMP Facebook', 'Unsubscribe' );
		}

		$facebook_events .= '};';

		// $facebook_events = '<script>
		$facebook_events .= '
		if ( typeof window.fbAsyncInit == "undefined" ) {
			window.fbAsyncInit = function() {
				FB.init({
					appId  : "' . self::get_option_as_escaped_string( 'setting_facebook_application_id' ) . '",
					xfbml  : true,
					version: "v' . self::API_VERSION . '"
				});

				FB.Event.subscribe("edge.create", smp_facebook_page_like_callback);
				FB.Event.subscribe("edge.remove", smp_facebook_page_unlike_callback);
			};
		} else {
			smp_facebookInterval = setInterval(function() {
				if ( typeof FB === "object" ) {
					FB.Event.subscribe("edge.create", smp_facebook_page_like_callback);
					FB.Event.subscribe("edge.remove", smp_facebook_page_unlike_callback);
					clearInterval(smp_facebookInterval);
				}
			}, 1000);
		}
		</script>';

		$facebook_events = htmlspecialchars( $facebook_events, ENT_QUOTES );

		return '$("#fb-root").prepend($("<div/>").html("' . esc_attr( $facebook_events ) . '").text());';
	}
}

