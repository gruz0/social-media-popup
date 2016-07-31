<?php

class SCP_Template {
	/**
	 * Use events tracking or not
	 *
	 * @since 0.7.5
	 *
	 * @var boolean $_use_events_tracking
	 */
	private $_use_events_tracking = false;

	/**
	 * Constructor
	 *
	 * @since 0.7.5
	 *
	 * @param boolean $use_events_tracking
	 */
	public function __construct( $use_events_tracking = false ) {
		$this->_use_events_tracking = $use_events_tracking;
	}

	/**
	 * Returns JS code to show SCP window by jQuery
	 *
	 * @since 0.7.3
	 * @since 0.7.5 Add using event tracking
	 *
	 * @uses $this->prepare_google_analytics_event()
	 * @uses $this->popup_platform_title()
	 *
	 * @return string
	 */
	function render_show_window() {
		$content = '';

		if ( $this->_use_events_tracking ) {
			$content .= $this->prepare_google_analytics_event( "show immediately", $this->popup_platform_title() );
		}

		if ( wp_is_mobile() ) {
			$content .= 'jQuery("#scp_mobile").show();';
		} else {
			$content .= 'jQuery("#social-community-popup").show();';
		}

		return $content;
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
	 * @since 0.7.5 Add push event to Google Analytics
	 *
	 * @uses $this->prepare_google_analytics_event()
	 * @uses $this->popup_platform_title()
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

				if ( $this->_use_events_tracking ) {
					$content .= $this->prepare_google_analytics_event( "show after " . ( $calculated_delay / 1000 ) . " seconds", $this->popup_platform_title() );
				}

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
	 * @since 0.7.5 Add push event to Google Analytics
	 *
	 * @uses $this->prepare_google_analytics_event()
	 * @uses $this->popup_platform_title()
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

					if ( $this->_use_events_tracking ) {
						$content .= $this->prepare_google_analytics_event( "show after click on " . $popup_will_appear_after_clicking_on_element, $this->popup_platform_title() );
					}

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
				$content .= 'alert("' . __( "You should to add a CSS selector in the plugin settings. Otherwise it won't be work.", L10N_SCP_PREFIX ) . '");';
			}
		}

		return $content;
	}

	/**
	 * Popup will appear when visitor scrolls window
	 *
	 * @since 0.7.4
	 * @since 0.7.5 Add push event to Google Analytics
	 *
	 * @uses $this->prepare_google_analytics_event()
	 * @uses $this->popup_platform_title()
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

					if ( $this->_use_events_tracking ) {
						$content .= $this->prepare_google_analytics_event( "show after scrolling down on " . $popup_will_appear_after_scrolling_down_n_percent . '%', $this->popup_platform_title() );
					}

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

	/**
	 * Render Google Analytics Tracking Code
	 *
	 * @since 0.7.5
	 *
	 * @used_by Social_Media_Popup::add_events_tracking_code()
	 *
	 * @param string $tracking_id Example: UA-12345678-0
	 * @return string
	 */
	function render_google_analytics_tracking_code( $tracking_id ) {
	?>
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', '<?php echo $tracking_id; ?>', 'auto');
		ga('send', 'pageview');
		</script>
	<?php
	}

	/**
	 * Helper for Google Analytics tracking code
	 *
	 * @since 0.7.5
	 *
	 * @used_by $this->render_show_window()
	 * @used_by $this->render_when_popup_will_appear_after_n_seconds()
	 * @used_by $this->render_when_popup_will_appear_after_clicking_on_element()
	 * @used_by $this->render_when_popup_will_appear_after_scrolling_down_n_percent()
	 *
	 * @param string $action Action to send. Example: show, subscribe, etc.
	 * @param string $label Source, example: "Popup Desktop", "Facebook", etc.
	 * @return string
	 */
	function prepare_google_analytics_event( $action, $label ) {
		$content = 'if (!smp_eventFired) {
			ga("send", "event", {
				eventCategory: "Social Media Popup",
				eventAction:   "' . $action . '",
				eventLabel:    "' . $label . '"
			});

			smp_eventFired = true;
		}';

		return $content;
	}

	/**
	 * Helper to prepare event label to show popup title depends on wp_is_mobile()
	 *
	 * @since 0.7.5
	 *
	 * @used_by $this->render_show_window()
	 * @used_by $this->render_when_popup_will_appear_after_n_seconds()
	 * @used_by $this->render_when_popup_will_appear_after_clicking_on_element()
	 * @used_by $this->render_when_popup_will_appear_after_scrolling_down_n_percent()
	 *
	 * @return string
	 */
	private function popup_platform_title() {
		return "Popup " . ( wp_is_mobile() ? "Mobile" : "Desktop" );
	}
}

