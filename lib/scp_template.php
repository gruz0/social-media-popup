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
		if ( wp_is_mobile() ) {
			return 'jQuery("#scp_mobile").show();';
		} else {
			return 'jQuery("#social-community-popup").show();';
		}
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
		return 'jQuery("' . $selector_to_close_widget . '").on("click", function() {
			scp_destroyPlugin(' . $after_n_days . ');
			return false;
		});';
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

		return 'jQuery("' . $selector_to_close_widget . '").on("click", function() {
			scp_destroyPlugin(' . $after_n_days . ', "#scp_mobile");
			return false;
		});';
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
			$content .= 'jQuery(document).on("keydown", function(e) {
				if ( e.keyCode == 27 ) {
					scp_destroyPlugin(' . $after_n_days . ');
				}
			});';
		}

		return $content;
	}

	/**
	 * Popup will appear after visitor stays on page about N seconds
	 *
	 * @since 0.7.4
	 *
	 * @param array $when_should_the_popup_appear Events list
	 * @param int $popup_will_appear_after_n_seconds Event value
	 * @param int $delay_before_show_bottom_button
	 * @param boolean $any_event_active
	 * @return string
	 */
	function render_when_popup_will_appear_after_n_seconds(
		$when_should_the_popup_appear,
		$popup_will_appear_after_n_seconds,
		$delay_before_show_bottom_button,
		& $any_event_active,
		$after_n_days = 0) {

		$content = '';

		// Отображение плагина после просмотра страницы N секунд
		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_n_seconds' ) ) {
			$any_event_active = true;

			$calculated_delay = ( $popup_will_appear_after_n_seconds > 0 ? $popup_will_appear_after_n_seconds * 1000 : 1000 );

			$content .= 'setTimeout(function() {
				if (is_scp_cookie_present()) return false;';

				$content .= $this->render_show_window();

				if ( wp_is_mobile() ) {
					$content .= $this->render_close_widget_on_mobile( $after_n_days );
				} else {
					$content .= $this->render_show_bottom_button( $delay_before_show_bottom_button );
				}

			$content .= '}, ' . esc_attr( $calculated_delay ) . ');';
		}

		return $content;
	}

	/**
	 * Popup will appear when visitor clicks on element
	 *
	 * @since 0.7.4
	 *
	 * @param array $when_should_the_popup_appear Events list
	 * @param int $popup_will_appear_after_clicking_on_element Event value
	 * @param int $delay_before_show_bottom_button
	 * @param boolean $any_event_active
	 * @return string
	 */
	function render_when_popup_will_appear_after_clicking_on_element(
		$when_should_the_popup_appear,
		$popup_will_appear_after_clicking_on_element,
		$event_hide_element_after_click_on_it,
		$delay_before_show_bottom_button,
		& $any_event_active,
		$after_n_days = 0) {

		$content = '';

		// Отображение плагина после клика по указанному селектору
		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_clicking_on_element' ) ) {
			$any_event_active = true;

			if ( ! empty( $popup_will_appear_after_clicking_on_element ) ) {
				$content .= 'jQuery("' . $popup_will_appear_after_clicking_on_element . '").on("click", function() {
					if (is_scp_cookie_present()) return false;';

					$content .= $this->render_show_window();

					if ( wp_is_mobile() ) {
						$content .= $this->render_close_widget_on_mobile( $after_n_days );
					} else {
						$content .= $this->render_show_bottom_button( $delay_before_show_bottom_button );
					}

					// Если активна опция "Удалять элемент после клика по нему" — удалим его из DOM
					if ( $event_hide_element_after_click_on_it ) {
						$content .= 'jQuery(this).remove();';
					}

					$content .= 'return false;';
				$content .= '});';
			} else {
				$content .= 'alert("' . __( "You must add a selector element for the plugin Social Community Popup. Otherwise it won't be work.", L10N_SCP_PREFIX ) . '");';
			}
		}

		return $content;
	}

	/**
	 * Popup will appear when visitor scrolls window
	 *
	 * @since 0.7.4
	 *
	 * @param array $when_should_the_popup_appear Events list
	 * @param int $popup_will_appear_after_scrolling_down_n_percent Event value
	 * @param int $delay_before_show_bottom_button
	 * @param boolean $any_event_active
	 * @return string
	 */
	function render_when_popup_will_appear_after_scrolling_down_n_percent(
		$when_should_the_popup_appear,
		$popup_will_appear_after_scrolling_down_n_percent,
		$delay_before_show_bottom_button,
		& $any_event_active,
		$after_n_days = 0) {

		$content = '';

		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_scrolling_down_n_percent' ) ) {
			$any_event_active = true;

			$content .= 'var showWindowAgain = true;
			jQuery(window).scroll(function() {
				if (is_scp_cookie_present()) return false;
				var bodyScrollTop = document.documentElement.scrollTop || document.body.scrollTop;

				value = parseInt(Math.abs(bodyScrollTop / (document.body.clientHeight - window.innerHeight) * 100));
				if (showWindowAgain && value >= ' . $popup_will_appear_after_scrolling_down_n_percent . ') {';
					$content .= $this->render_show_window();

					if ( wp_is_mobile() ) {
						$content .= $this->render_close_widget_on_mobile( $after_n_days );
					} else {
						$content .= $this->render_show_bottom_button( $delay_before_show_bottom_button );
					}

					$content .= 'showWindowAgain = false;
				}
			});';
		}

		return $content;
	}

	/**
	 * Popup will appear on exit intent
	 *
	 * @since 0.7.4
	 *
	 * @param array $when_should_the_popup_appear Events list
	 * @param boolean $popup_will_appear_on_exit_intent Event value
	 * @param int $delay_before_show_bottom_button
	 * @param boolean $any_event_active
	 * @return string
	 */
	function render_when_popup_will_appear_on_exit_intent(
		$when_should_the_popup_appear,
		$popup_will_appear_on_exit_intent,
		$delay_before_show_bottom_button,
		& $any_event_active) {

		$content = '';

		// Отображение плагина при попытке увести мышь за пределы окна
		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'on_exit_intent' ) && $popup_will_appear_on_exit_intent ) {
			$any_event_active = true;

			$content .= 'jQuery(document).on("mouseleave", function(e) {
				if (is_scp_cookie_present()) return;

				var scroll = window.pageYOffset || document.documentElement.scrollTop;
				if((e.pageY - scroll) < 7) {';
					$content .= $this->render_show_window();
					$content .= $this->render_show_bottom_button( $delay_before_show_bottom_button );
				$content .= '}
			});';
		}

		return $content;
	}
}

