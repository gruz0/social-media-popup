<?php

class SCP_Facebook_Provider extends SCP_Provider {
	/**
	 * Return widget is active
	 *
	 * @since 0.7.5
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return ( self::$options[ self::$prefix . 'setting_use_facebook' ] === '1' );
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
			'tab_caption' => esc_attr( self::$options[ self::$prefix . 'setting_facebook_tab_caption'] ),
			'css_class'   => 'facebook-tab',
			'icon'        => 'fa-facebook',
			'url'         => self::$options[ self::$prefix . 'setting_facebook_page_url' ]
		);
	}

	/**
	 * Render widget container
	 *
	 * @uses scp_to_bool()
	 * @uses SCP_Template()->use_events_tracking()
	 * @uses SCP_Template()->push_social_media_trigger_to_google_analytics()
	 * @uses SCP_Template()->push_social_network_and_action_to_google_analytics()
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	public static function container() {
		$close_window_after_join = ( (int) self::$options[ self::$prefix . 'setting_facebook_close_window_after_join' ] ) == 1;

		$content = '<div class="box">';

		if ( self::$options[ self::$prefix . 'setting_facebook_show_description' ] === '1' ) {
			$content .= '<p class="widget-description"><b>' . self::$options[ self::$prefix . 'setting_facebook_description' ] . '</b></p>';
		}

		$content .= '<div class="fb-page" '
			. 'data-href="' .                  esc_attr( self::$options[ self::$prefix . 'setting_facebook_page_url' ] ) . '" '
			. 'data-width="' .                 esc_attr( self::$options[ self::$prefix . 'setting_facebook_width' ] ) . '" '
			. 'width="' .                      esc_attr( self::$options[ self::$prefix . 'setting_facebook_width' ] ) . '" '
			. 'data-height="' .                esc_attr( self::$options[ self::$prefix . 'setting_facebook_height' ] ) . '" '
			. 'height="' .                     esc_attr( self::$options[ self::$prefix . 'setting_facebook_height' ] ) . '" '
			. 'data-hide-cover="' .            esc_attr( scp_to_bool( self::$options[ self::$prefix . 'setting_facebook_hide_cover' ] ) ) . '" '
			. 'data-show-facepile="' .         esc_attr( scp_to_bool( self::$options[ self::$prefix . 'setting_facebook_show_facepile' ] ) ) . '" '
			. 'data-adapt-container-width="' . esc_attr( scp_to_bool( self::$options[ self::$prefix . 'setting_facebook_adapt_container_width' ] ) ) . '" '
			. 'data-small-header="' .          esc_attr( scp_to_bool( self::$options[ self::$prefix . 'setting_facebook_use_small_header' ] ) ) . '" '
			. 'data-tabs="' .                  esc_attr( self::$options[ self::$prefix . 'setting_facebook_tabs' ] ) . '" '
			. '></div>';

		$content .= '</div>';

		$content .= '<script>function scp_prependFacebook($) {';

		$prepend_facebook = '<div id="fb-root"></div>'
			. '<script>';

		// Формирует колбэк для обработки событий при закрытии окна, подписке или отписке от группы
		$prepend_facebook .= 'var scp_facebook_page_like_or_unlike_callback = function(url, html_element) {';

		if ( (int) self::$options[ self::$prefix . 'setting_facebook_close_window_after_join' ] ) {
			$prepend_facebook .= 'scp_destroyPlugin(scp.showWindowAfterReturningNDays);';
		}

		if ( self::$template->use_events_tracking() && ( ( (int) self::$options[ self::$prefix . 'tracking_use_facebook' ] ) == 1 ) ) {
			$prepend_facebook .= self::$template->push_social_media_trigger_to_google_analytics( esc_attr( self::$options[ self::$prefix . 'tracking_facebook_event' ] ) );
			$prepend_facebook .= self::$template->push_social_network_and_action_to_google_analytics( 'SMP Facebook', 'Subscribe' );
		}

		$prepend_facebook .= '};

		window.fbAsyncInit = function() {
			FB.init({
				appId  : "' . esc_attr( self::$options[ self::$prefix . 'setting_facebook_application_id' ] ) . '",
				xfbml  : true,
				version: "v2.5"
			});

			FB.Event.subscribe("edge.create", scp_facebook_page_like_or_unlike_callback);
		};';

		// Подключаем сам Facebook
		$prepend_facebook .= '(function(d, s, id) {'
			. 'var js, fjs = d.getElementsByTagName(s)[0];'
			. 'if (d.getElementById(id)) return;'
			. 'js = d.createElement(s); js.id = id;'
			. 'js.src = "//connect.facebook.net/' . esc_attr( self::$options[ self::$prefix . 'setting_facebook_locale' ] )
			. '/sdk.js#xfbml=1&appId=' .            esc_attr( self::$options[ self::$prefix . 'setting_facebook_application_id' ] ) . '&version=v2.5";'
			. 'fjs.parentNode.insertBefore(js, fjs);'
			. '}(document, "script", "facebook-jssdk"));</script>';

		// Удаляем переносы строк, иначе jQuery ниже не отработает
		$prepend_facebook = str_replace("\n", '', $prepend_facebook);

		// Переводим код в сущности
		$prepend_facebook = htmlspecialchars( $prepend_facebook, ENT_QUOTES );

		$content .= 'if ($("#fb-root").length == 0) {
			$("body").prepend($("<div/>").html("' . esc_attr( $prepend_facebook ) . '").text());
		}';

		$content .= '}</script>'; // scp_prependFacebook

		return $content;
	}
}

