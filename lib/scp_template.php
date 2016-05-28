<?php

class SCP_Template {
	/**
	 * Returns JS code to show SCP window by jQuery
	 *
	 * @since 0.7.3
	 *
	 * @return string
	 */
	function render_show_window() {
		return 'jQuery("#social-community-popup").show();';
	}

	/**
	 * Returns JS code to render bottom button with text 'Please don't show widget again'
	 *
	 * @since 0.7.3
	 *
	 * @param string $delay_before_show_bottom_button Delay before show bottom button in sec.
	 * @return string
	 */
	function render_show_bottom_button( $delay_before_show_bottom_button ) {
		$content = '';
		$delay_before_show_bottom_button = absint( esc_attr( $delay_before_show_bottom_button ) );
		if ( $delay_before_show_bottom_button > 0 ) {
			$content = 'setTimeout(function() { jQuery(".dont-show-widget").show(); }, ' . ( $delay_before_show_bottom_button * 1000 ) . ');';
		} else {
			$content = 'jQuery(".dont-show-widget").show();';
		}

		return $content;
	}

	/**
	 * Returns JS code to render button to close widget
	 *
	 * @since 0.7.3
	 *
	 * @param boolean $close_by_clicking_anywhere If it is equals to true then window will close by click outside container
	 * @param string $after_n_days Timeout to show SCP window again
	 * @return string
	 */
	function render_close_widget( $close_by_clicking_anywhere, $after_n_days ) {
		if ( $close_by_clicking_anywhere ) {
			$selector_to_close_widget = '#social-community-popup .parent_popup, #social-community-popup .close';
		} else {
			$selector_to_close_widget = '#social-community-popup .close';
		}

		$after_n_days = absint( esc_attr( $after_n_days ) );
		return '$("' . $selector_to_close_widget . '").click(function() { scp_destroyPlugin($, ' . $after_n_days . '); });';
	}

	/**
	 * Returns JS code to render button to close widget on mobile devices
	 *
	 * @since 0.7.4
	 *
	 * @param string $after_n_days Timeout to show SCP window again
	 * @return string
	 */
	function render_close_widget_on_mobile( $after_n_days ) {
		$selector_to_close_widget = '#scp_mobile .scp-close a';
		$after_n_days = absint( esc_attr( $after_n_days ) );

		return '$("' . $selector_to_close_widget . '").click(function() { scp_destroyPlugin($, ' . $after_n_days . ', "#scp_mobile"); });';
	}

	/**
	 * Returns JS code to close widget when ESC button was pressed
	 *
	 * @since 0.7.3
	 *
	 * @param boolean $close_when_esc_pressed If it is equals to true then SCP window will close by ESC pressed
	 * @param string $after_n_days Timeout to show SCP window again
	 * @return string
	 */
	function render_close_widget_when_esc_pressed( $close_when_esc_pressed, $after_n_days ) {
		$content = '';

		if ( $close_when_esc_pressed ) {
			$after_n_days = absint( esc_attr( $after_n_days ) );
			$content .= '$(document).keydown(function(e) {
				if ( e.keyCode == 27 ) {
					scp_destroyPlugin($, ' . $after_n_days . ');
				}
			});';
		}

		return $content;
	}

	function render_when_popup_will_appear_after_n_seconds(
		$when_should_the_popup_appear,
		$popup_will_appear_after_n_seconds,
		$delay_before_show_bottom_button,
		& $any_event_active) {

		$content = '';

		// Отображение плагина после просмотра страницы N секунд
		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_n_seconds' ) ) {
			$any_event_active = true;

			$calculated_delay = ( $popup_will_appear_after_n_seconds > 0 ? $popup_will_appear_after_n_seconds * 1000 : 1000 );

			$content .= 'setTimeout(function() {
				if (!is_scp_cookie_present()) {';
					$content .= $this->render_show_window();
					$content .= $this->render_show_bottom_button( $delay_before_show_bottom_button );
				$content .= '}
			}, ' . esc_attr( $calculated_delay ) . ');';
		}

		return $content;
	}
}

