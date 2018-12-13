<?php
/**
 * Social Media Popup Template
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

/**
 * SCP_Template class
 */
class SCP_Template {
	/**
	 * Template options
	 *
	 * @since 0.7.5
	 *
	 * @var array $_options
	 */
	private $_options = array();

	/**
	 * Events descriptions
	 *
	 * @since 0.7.5
	 *
	 * @var array $_events_descriptions
	 */
	private $_events_descriptions = array();

	/**
	 * Constructor
	 *
	 * @since 0.7.5
	 *
	 * @param array $options Options
	 * @param array $events_descriptions Events descriptions
	 */
	public function __construct( $options = array(), $events_descriptions = array() ) {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		$default_options = array(
			'use_events_tracking'                             => false,
			'do_not_use_tracking_in_debug_mode'               => true,
			'push_events_to_aquisition_social_plugins'        => true,
			'push_events_when_displaying_window'              => true,
			'push_events_when_subscribing_on_social_networks' => true,
			'add_window_events_descriptions'                  => true,
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned

		$this->_options = wp_parse_args( $options, $default_options );

		$default_events_descriptions = array(
			'window_showed_immediately'       => __( 'Show immediately', 'social-media-popup' ),
			'window_showed_with_delay'        => __( 'Show after delay before it rendered', 'social-media-popup' ),
			'window_showed_after_click'       => __( 'Show after click on CSS-selector', 'social-media-popup' ),
			'window_showed_on_scrolling_down' => __( 'Show after scrolling down', 'social-media-popup' ),
			'window_showed_on_exit_intent'    => __( 'Show on exit intent', 'social-media-popup' ),
			'no_events_fired'                 => __( '(no events fired)', 'social-media-popup' ),
			'on_delay'                        => __( 'After delay before show widget', 'social-media-popup' ),
			'after_click'                     => __( 'After click on CSS-selector', 'social-media-popup' ),
			'on_scrolling_down'               => __( 'On scrolling down', 'social-media-popup' ),
			'on_exit_intent'                  => __( 'On exit intent', 'social-media-popup' ),
		);

		$this->_events_descriptions = wp_parse_args( $events_descriptions, $default_events_descriptions );
	}

	/**
	 * Getter for $this->_options['use_events_tracking']
	 *
	 * @since 0.7.5
	 *
	 * @return boolean
	 */
	public function use_events_tracking() {
		return $this->_options['use_events_tracking'];
	}

	/**
	 * Returns JS code to show SCP window by jQuery
	 *
	 * @since 0.7.3
	 * @since 0.7.5 Add using event tracking
	 *
	 * @uses $this->push_google_analytics_event_on_show_window()
	 *
	 * @return string
	 */
	function render_show_window() {
		$content = '';

		if ( $this->_options['use_events_tracking'] ) {
			$content .= $this->push_google_analytics_event_on_show_window(
				$this->_events_descriptions['window_showed_immediately'],
				$this->_events_descriptions['no_events_fired']
			);
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
	 * @param string  $after_n_days Timeout to show SCP window again
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
		$after_n_days             = absint( esc_attr( $after_n_days ) );

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
	 * @param string  $after_n_days Timeout to show SCP window again
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
	 * @uses $this->push_google_analytics_event_on_show_window()
	 * @uses $this->render_show_window()
	 * @uses $this->render_close_widget_on_mobile()
	 * @uses $this->render_show_bottom_button()
	 *
	 * @param array   $when_should_the_popup_appear Events list
	 * @param int     $popup_will_appear_after_n_seconds Event value
	 * @param int     $delay_before_show_bottom_button Delay before show bottom button in seconds
	 * @param boolean $any_event_active Changed by function if event is active
	 * @param int     $after_n_days Show window again after N days
	 * @return string
	 */
	function render_when_popup_will_appear_after_n_seconds(
		$when_should_the_popup_appear,
		$popup_will_appear_after_n_seconds,
		$delay_before_show_bottom_button,
		& $any_event_active,
		$after_n_days = 0 ) {

		$content = '';

		// Отображение плагина после просмотра страницы N секунд
		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_n_seconds' ) ) {
			$any_event_active = true;

			$calculated_delay = ( $popup_will_appear_after_n_seconds > 0 ? $popup_will_appear_after_n_seconds * 1000 : 1000 );

			$content .= 'setTimeout(function() {
				if (is_scp_cookie_present()) return false;';

				if ( $this->_options['use_events_tracking'] ) {
					$content .= $this->push_google_analytics_event_on_show_window(
						$this->_events_descriptions['window_showed_with_delay'],
						$this->_events_descriptions['on_delay']
					);
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
	 * @uses $this->push_google_analytics_event_on_show_window()
	 * @uses $this->render_show_window()
	 * @uses $this->render_close_widget_on_mobile()
	 * @uses $this->render_show_bottom_button()
	 *
	 * @param array   $when_should_the_popup_appear Events list
	 * @param int     $popup_will_appear_after_clicking_on_element Event value
	 * @param boolean $event_hide_element_after_click_on_it Hide element after click on it
	 * @param boolean $do_not_use_cookies_after_click_on_element Do not use cookies after click on element
	 * @param int     $delay_before_show_bottom_button Delay before show bottom button in seconds
	 * @param boolean $any_event_active Changed by function if event is active
	 * @param int     $after_n_days Show window again after N days
	 * @return string
	 */
	function render_when_popup_will_appear_after_clicking_on_element(
		$when_should_the_popup_appear,
		$popup_will_appear_after_clicking_on_element,
		$event_hide_element_after_click_on_it,
		$do_not_use_cookies_after_click_on_element,
		$delay_before_show_bottom_button,
		& $any_event_active,
		$after_n_days = 0 ) {

		$content = '';

		// Отображение плагина после клика по указанному селектору
		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_clicking_on_element' ) ) {
			$any_event_active = true;

			if ( ! empty( $popup_will_appear_after_clicking_on_element ) ) {
				$content .= 'jQuery("' . $popup_will_appear_after_clicking_on_element . '").on("click", function() {';

					if ( ! $do_not_use_cookies_after_click_on_element ) {
						$content .= 'if (is_scp_cookie_present()) return false;';
					}

					if ( $this->_options['use_events_tracking'] ) {
						$content .= $this->push_google_analytics_event_on_show_window(
							$this->_events_descriptions['window_showed_after_click'],
							$this->_events_descriptions['after_click']
						);
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
				$content .= 'alert("' . esc_html( 'You should to add a CSS selector in the plugin settings. Otherwise it will not be work.', 'social-media-popup' ) . '");';
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
	 * @uses $this->push_google_analytics_event_on_show_window()
	 * @uses $this->render_show_window()
	 * @uses $this->render_close_widget_on_mobile()
	 * @uses $this->render_show_bottom_button()
	 *
	 * @param array   $when_should_the_popup_appear Events list
	 * @param int     $popup_will_appear_after_scrolling_down_n_percent Event value
	 * @param int     $delay_before_show_bottom_button Delay before show botton button in seconds
	 * @param boolean $any_event_active Changed by function if event is active
	 * @param int     $after_n_days Show window again after N days
	 * @return string
	 */
	function render_when_popup_will_appear_after_scrolling_down_n_percent(
		$when_should_the_popup_appear,
		$popup_will_appear_after_scrolling_down_n_percent,
		$delay_before_show_bottom_button,
		& $any_event_active,
		$after_n_days = 0 ) {

		$content = '';

		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_scrolling_down_n_percent' ) ) {
			$any_event_active = true;

			$content .= 'var showWindowAgain = true;
			jQuery(window).scroll(function() {
				if (is_scp_cookie_present()) return false;

				if (showWindowAgain && scp_getScrollPercentage() >= ' . $popup_will_appear_after_scrolling_down_n_percent . ') {';

					if ( $this->_options['use_events_tracking'] ) {
						$content .= $this->push_google_analytics_event_on_show_window(
							$this->_events_descriptions['window_showed_on_scrolling_down'],
							$this->_events_descriptions['on_scrolling_down']
						);
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
	 * @since 0.7.5 Add push event to Google Analytics
	 *
	 * @uses $this->push_google_analytics_event_on_show_window()
	 * @uses $this->render_show_window()
	 * @uses $this->render_show_bottom_button()
	 *
	 * @param array   $when_should_the_popup_appear Events list
	 * @param boolean $popup_will_appear_on_exit_intent Event value
	 * @param int     $delay_before_show_bottom_button Delay in seconds
	 * @param boolean $any_event_active Changed by function if event is active
	 * @return string
	 */
	function render_when_popup_will_appear_on_exit_intent(
		$when_should_the_popup_appear,
		$popup_will_appear_on_exit_intent,
		$delay_before_show_bottom_button,
		& $any_event_active ) {

		$content = '';

		// Отображение плагина при попытке увести мышь за пределы окна
		if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'on_exit_intent' ) && $popup_will_appear_on_exit_intent ) {
			$any_event_active = true;

			$content .= 'jQuery(document).on("mouseleave", function(e) {
				if (is_scp_cookie_present()) return;

				var scroll = window.pageYOffset || document.documentElement.scrollTop;
				if((e.pageY - scroll) < 7) {';
					if ( $this->_options['use_events_tracking'] ) {
						$content .= $this->push_google_analytics_event_on_show_window(
							$this->_events_descriptions['window_showed_on_exit_intent'],
							$this->_events_descriptions['on_exit_intent']
						);
					}

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
	 */
	function render_google_analytics_tracking_code( $tracking_id ) {
	?>
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', '<?php echo esc_attr( $tracking_id ); ?>', 'auto');
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
	 * @used_by $this->render_when_popup_will_appear_on_exit_intent()
	 *
	 * @uses $this->popup_platform_title()
	 *
	 * @param string $action Action to send. Example: show, destroy, etc.
	 * @param string $event_description Description to extend Google Analytics event
	 * @return string
	 */
	function push_google_analytics_event_on_show_window( $action, $event_description = '' ) {
		if ( $this->_options['do_not_use_tracking_in_debug_mode'] ) {
			return '';
		}

		$action            = esc_html( $action );
		$event_description = esc_html( $event_description );

		$content = '';

		if ( $this->_options['push_events_when_displaying_window'] ) {
			$content = 'if (!smp_eventFired ) {
				ga("send", "event", {
					eventCategory: "Social Media Popup",
					eventAction:   "' . $action . '",
					eventLabel:    "' . $this->popup_platform_title() . '"
				});

				smp_eventFired = true;
				smp_firedEventDescription = "' . ( empty( $event_description ) ? $action : $event_description ) . '";
			}';

		} else {
			if ( $this->_options['add_window_events_descriptions'] ) {
				$content = 'if (!smp_eventFired ) {
					smp_eventFired = true;
					smp_firedEventDescription = "' . ( empty( $event_description ) ? $action : $event_description ) . '";
				}';
			}
		}

		return $content;
	}

	/**
	 * Helper for push social media triggers to Google Analytics
	 *
	 * @since 0.7.5
	 *
	 * @used_by SCP_Facebook_Provider::container()
	 * @used_by SCP_VK_Provider::container()
	 * @used_by SCP_Twitter_Provider::container()
	 *
	 * @param string $action Action to send. Example: subscribe, unsubscribe, etc.
	 * @return string
	 */
	function push_social_media_trigger_to_google_analytics( $action ) {
		if ( $this->_options['do_not_use_tracking_in_debug_mode'] ) {
			return '';
		}

		if ( ! $this->_options['push_events_when_subscribing_on_social_networks'] ) {
			return '';
		}

		$content = 'ga("send", "event", {
				eventCategory: "Social Media Popup",
				eventAction:   "' . esc_html( $action ) . '" + " " + smp_firedEventDescription
			});';

		return $content;
	}

	/**
	 * Helper for push social network and action to Google Analytics
	 * It should be viewed in Acquisition > Social > Plugins in GA
	 *
	 * @since 0.7.5
	 *
	 * @used_by SCP_Facebook_Provider::container()
	 * @used_by SCP_VK_Provider::container()
	 * @used_by SCP_Twitter_Provider::container()
	 *
	 * @param string $network Social network name (Facebook, VK, Twitter, etc.)
	 * @param string $event_type Type description (like, unlike, etc.)
	 * @return string
	 */
	function push_social_network_and_action_to_google_analytics( $network, $event_type ) {
		if ( ! $this->_options['push_events_to_aquisition_social_plugins'] ) {
			return '';
		}

		$content = 'ga("send", {
			hitType:       "social",
			socialNetwork: "' . esc_html( $network ) . '",
			socialAction:  "' . esc_html( $event_type ) . '",
			socialTarget:  "' . get_permalink() . '"
		});';
		return $content;
	}

	/**
	 * Helper to prepare event label to show popup title depends on wp_is_mobile()
	 *
	 * @since 0.7.5
	 *
	 * @used_by $this->push_google_analytics_event_on_show_window()
	 *
	 * @return string
	 */
	private function popup_platform_title() {
		return 'Popup ' . ( wp_is_mobile() ? 'Mobile' : 'Desktop' );
	}
}

