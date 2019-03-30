<?php
/**
 * Social Media Popup Sanitizer
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

/**
 * SMP_Sanitizer
 *
 * @since 1.0.0
 */
class SMP_Sanitizer {
	/**
	 * Sanitizer
	 *
	 * @param string $section Section
	 * @param array  $input   Array of values
	 * @return array
	 */
	public static function sanitize( $section, $input ) {
		$values = array();

		switch ( $section ) {
			case SMP_PREFIX . '-section-common':
				$values['setting_debug_mode']                       = isset( $input['setting_debug_mode'] ) ? 1 : 0;
				$values['setting_tabs_order']                       = self::sanitize_tabs_order( $input['setting_tabs_order'] );
				$values['setting_close_popup_by_clicking_anywhere'] = isset( $input['setting_close_popup_by_clicking_anywhere'] ) ? 1 : 0;
				$values['setting_close_popup_when_esc_pressed']     = isset( $input['setting_close_popup_when_esc_pressed'] ) ? 1 : 0;
				$values['setting_show_on_mobile_devices']           = isset( $input['setting_show_on_mobile_devices'] ) ? 1 : 0;
				$values['setting_show_admin_bar_menu']              = isset( $input['setting_show_admin_bar_menu'] ) ? 1 : 0;

				break;

			case SMP_PREFIX . '-section-common-view':
				$values['setting_plugin_title']                        = wp_kses_post( $input['setting_plugin_title'] );
				$values['setting_use_animation']                       = isset( $input['setting_use_animation'] ) ? 1 : 0;
				$values['setting_animation_style']                     = self::sanitize_animation_style( $input['setting_animation_style'] );
				$values['setting_use_icons_instead_of_labels_in_tabs'] = isset( $input['setting_use_icons_instead_of_labels_in_tabs'] ) ? 1 : 0;
				$values['setting_icons_size_on_desktop']               = self::sanitize_icons_size( $input['setting_icons_size_on_desktop'] );
				$values['setting_hide_tabs_if_one_widget_is_active']   = isset( $input['setting_hide_tabs_if_one_widget_is_active'] ) ? 1 : 0;
				$values['setting_container_width']                     = absint( $input['setting_container_width'] );
				$values['setting_container_height']                    = absint( $input['setting_container_height'] );
				$values['setting_border_radius']                       = absint( $input['setting_border_radius'] );
				$values['setting_show_close_button_in']                = self::sanitize_show_close_button_in( $input['setting_show_close_button_in'] );
				$values['setting_show_button_to_close_widget']         = isset( $input['setting_show_button_to_close_widget'] ) ? 1 : 0;
				$values['setting_button_to_close_widget_title']        = sanitize_text_field( $input['setting_button_to_close_widget_title'] );
				$values['setting_button_to_close_widget_style']        = self::sanitize_button_to_close_widget_style( $input['setting_button_to_close_widget_style'] );
				$values['setting_delay_before_show_bottom_button']     = absint( $input['setting_delay_before_show_bottom_button'] );
				$values['setting_overlay_color']                       = self::sanitize_hex_color( $input['setting_overlay_color'] );
				$values['setting_overlay_opacity']                     = self::sanitize_overlay_opacity( $input['setting_overlay_opacity'] );
				$values['setting_align_tabs_to_center']                = isset( $input['setting_align_tabs_to_center'] ) ? 1 : 0;
				$values['setting_background_image']                    = self::sanitize_background_image( $input['setting_background_image'] );

				break;

			case SMP_PREFIX . '-section-common-view-mobile':
				$values['setting_plugin_title_on_mobile_devices'] = wp_kses_post( $input['setting_plugin_title_on_mobile_devices'] );
				$values['setting_icons_size_on_mobile_devices']   = self::sanitize_icons_size( $input['setting_icons_size_on_mobile_devices'] );

				break;

			case SMP_PREFIX . '-section-common-events-general':
				$when_should_the_popup_appear = self::sanitize_when_should_the_popup_appear( $input['when_should_the_popup_appear'] );

				// sanitize popup_will_appear_after_n_seconds
				if ( in_array( 'after_n_seconds', $when_should_the_popup_appear ) ) {
					$value = absint( $input['popup_will_appear_after_n_seconds'] );

					if ( $value > 0 ) {
						$values['popup_will_appear_after_n_seconds'] = $value;
					} else {
						$when_should_the_popup_appear = array_diff( $when_should_the_popup_appear, array( 'after_n_seconds' ) );
					}
				}

				// sanitize popup_will_appear_after_clicking_on_element
				if ( in_array( 'after_clicking_on_element', $when_should_the_popup_appear ) ) {
					$popup_will_appear_after_clicking_on_element = sanitize_text_field( $input['popup_will_appear_after_clicking_on_element'] );
					$event_hide_element_after_click_on_it        = self::sanitize_checkbox( $input['event_hide_element_after_click_on_it'] );
					$do_not_use_cookies_after_click_on_element   = self::sanitize_checkbox( $input['do_not_use_cookies_after_click_on_element'] );

					if ( empty( $popup_will_appear_after_clicking_on_element ) &&
						! $event_hide_element_after_click_on_it &&
						! $do_not_use_cookies_after_click_on_element ) {

						$when_should_the_popup_appear = array_diff( $when_should_the_popup_appear, array( 'after_clicking_on_element' ) );
					} else {
						if ( ! empty( $popup_will_appear_after_clicking_on_element ) ) {
							$values['popup_will_appear_after_clicking_on_element'] =
								preg_replace( '/[^a-z\d#,\.\- ]*/i', '', sanitize_text_field( $input['popup_will_appear_after_clicking_on_element'] ) );
						}

						$values['event_hide_element_after_click_on_it']      = $event_hide_element_after_click_on_it;
						$values['do_not_use_cookies_after_click_on_element'] = $do_not_use_cookies_after_click_on_element;
					}
				} else {
					$values['popup_will_appear_after_clicking_on_element'] = '';
					$values['event_hide_element_after_click_on_it']        = 0;
					$values['do_not_use_cookies_after_click_on_element']   = 0;
				}

				// sanitize popup_will_appear_after_scrolling_down_n_percent
				if ( in_array( 'after_scrolling_down_n_percent', $when_should_the_popup_appear ) ) {
					$value = absint( $input['popup_will_appear_after_scrolling_down_n_percent'] );
					$values['popup_will_appear_after_scrolling_down_n_percent'] = $value > 100 ? 70 : $value;
				} else {
					$values['popup_will_appear_after_scrolling_down_n_percent'] = 0;
				}

				// sanitize popup_will_appear_on_exit_intent
				if ( in_array( 'on_exit_intent', $when_should_the_popup_appear ) ) {
					$value = self::sanitize_checkbox( $input['popup_will_appear_on_exit_intent'] );

					if ( $value ) {
						$values['popup_will_appear_on_exit_intent'] = $value;
					} else {
						$when_should_the_popup_appear = array_diff( $when_should_the_popup_appear, array( 'on_exit_intent' ) );
					}
				} else {
					$values['popup_will_appear_on_exit_intent'] = 0;
				}

				$values['when_should_the_popup_appear'] = join( ',', $when_should_the_popup_appear );

				break;

			case SMP_PREFIX . '-section-common-events-who':
				$who_should_see_the_popup = self::sanitize_who_should_see_the_popup( $input['who_should_see_the_popup'] );

				// sanitize popup_will_appear_after_n_seconds
				if ( in_array( 'visitor_opened_at_least_n_number_of_pages', $who_should_see_the_popup ) ) {
					$value = absint( $input['visitor_opened_at_least_n_number_of_pages'] );

					if ( $value > 0 ) {
						$values['visitor_opened_at_least_n_number_of_pages'] = $value;
					} else {
						$who_should_see_the_popup = array_diff( $who_should_see_the_popup, array( 'visitor_opened_at_least_n_number_of_pages' ) );
					}
				}

				// sanitize visitor_registered_and_role_equals_to
				if ( in_array( 'visitor_registered_and_role_equals_to', $who_should_see_the_popup ) ) {
					$values['visitor_registered_and_role_equals_to'] = self::sanitize_visitor_registered_and_role_equals_to( $input['visitor_registered_and_role_equals_to'] );
				}

				$values['setting_display_after_n_days'] = absint( $input['setting_display_after_n_days'] );

				$values['who_should_see_the_popup'] = join( ',', $who_should_see_the_popup );

				break;

			case SMP_PREFIX . '-section-common-tracking-general':
				$values['use_events_tracking']               = self::sanitize_checkbox( $input['use_events_tracking'] );
				$values['do_not_use_tracking_in_debug_mode'] = self::sanitize_checkbox( $input['do_not_use_tracking_in_debug_mode'] );

				break;

			case SMP_PREFIX . '-section-common-tracking-google-analytics':
				$values['google_analytics_tracking_id']             = sanitize_text_field( $input['google_analytics_tracking_id'] );
				$values['push_events_to_aquisition_social_plugins'] = isset( $input['push_events_to_aquisition_social_plugins'] ) ? 1 : 0;

				break;

			case SMP_PREFIX . '-section-common-tracking-window-events':
				$values['push_events_when_displaying_window']                   = isset( $input['push_events_when_displaying_window'] ) ? 1 : 0;
				$values['tracking_event_label_window_showed_immediately']       = sanitize_text_field( $input['tracking_event_label_window_showed_immediately'] );
				$values['tracking_event_label_window_showed_with_delay']        = sanitize_text_field( $input['tracking_event_label_window_showed_with_delay'] );
				$values['tracking_event_label_window_showed_after_click']       = sanitize_text_field( $input['tracking_event_label_window_showed_after_click'] );
				$values['tracking_event_label_window_showed_on_scrolling_down'] = sanitize_text_field( $input['tracking_event_label_window_showed_on_scrolling_down'] );
				$values['tracking_event_label_window_showed_on_exit_intent']    = sanitize_text_field( $input['tracking_event_label_window_showed_on_exit_intent'] );

				break;

			case SMP_PREFIX . '-section-common-tracking-social-events':
				$values['push_events_when_subscribing_on_social_networks'] = isset( $input['push_events_when_subscribing_on_social_networks'] ) ? 1 : 0;
				$values['add_window_events_descriptions']                  = isset( $input['add_window_events_descriptions'] ) ? 1 : 0;
				$values['tracking_event_label_no_events_fired']            = sanitize_text_field( $input['tracking_event_label_no_events_fired'] );
				$values['tracking_event_label_on_delay']                   = sanitize_text_field( $input['tracking_event_label_on_delay'] );
				$values['tracking_event_label_after_click']                = sanitize_text_field( $input['tracking_event_label_after_click'] );
				$values['tracking_event_label_on_scrolling_down']          = sanitize_text_field( $input['tracking_event_label_on_scrolling_down'] );
				$values['tracking_event_label_on_exit_intent']             = sanitize_text_field( $input['tracking_event_label_on_exit_intent'] );

				break;

			case SMP_PREFIX . '-section-common-management':
				$values['setting_remove_settings_on_uninstall'] = isset( $input['setting_remove_settings_on_uninstall'] ) ? 1 : 0;

				break;

			case SMP_PREFIX . '-section-facebook-general':
				$values['setting_use_facebook']                     = isset( $input['setting_use_facebook'] ) ? 1 : 0;
				$values['setting_facebook_tab_caption']             = sanitize_text_field( $input['setting_facebook_tab_caption'] );
				$values['setting_facebook_show_description']        = isset( $input['setting_facebook_show_description'] ) ? 1 : 0;
				$values['setting_facebook_description']             = wp_kses_post( $input['setting_facebook_description'] );
				$values['setting_facebook_application_id']          = sanitize_text_field( $input['setting_facebook_application_id'] );
				$values['setting_facebook_page_url']                = esc_url( $input['setting_facebook_page_url'] );
				$values['setting_facebook_locale']                  = sanitize_text_field( $input['setting_facebook_locale'] );
				$values['setting_facebook_width']                   = absint( $input['setting_facebook_width'] );
				$values['setting_facebook_height']                  = absint( $input['setting_facebook_height'] );
				$values['setting_facebook_use_small_header']        = isset( $input['setting_facebook_use_small_header'] ) ? 1 : 0;
				$values['setting_facebook_hide_cover']              = isset( $input['setting_facebook_hide_cover'] ) ? 1 : 0;
				$values['setting_facebook_show_facepile']           = isset( $input['setting_facebook_show_facepile'] ) ? 1 : 0;
				$values['setting_facebook_tabs']                    = sanitize_text_field( $input['setting_facebook_tabs'] );
				$values['setting_facebook_adapt_container_width']   = isset( $input['setting_facebook_adapt_container_width'] ) ? 1 : 0;
				$values['setting_facebook_close_window_after_join'] = isset( $input['setting_facebook_close_window_after_join'] ) ? 1 : 0;

				break;

			case SMP_PREFIX . '-section-facebook-tracking':
				$values['tracking_use_facebook']               = isset( $input['tracking_use_facebook'] ) ? 1 : 0;
				$values['tracking_facebook_subscribe_event']   = sanitize_text_field( $input['tracking_facebook_subscribe_event'] );
				$values['tracking_facebook_unsubscribe_event'] = sanitize_text_field( $input['tracking_facebook_unsubscribe_event'] );

				break;

			case SMP_PREFIX . '-section-vkontakte-general':
				$values['setting_use_vkontakte']                     = isset( $input['setting_use_vkontakte'] ) ? 1 : 0;
				$values['setting_vkontakte_tab_caption']             = sanitize_text_field( $input['setting_vkontakte_tab_caption'] );
				$values['setting_vkontakte_show_description']        = isset( $input['setting_vkontakte_show_description'] ) ? 1 : 0;
				$values['setting_vkontakte_description']             = wp_kses_post( $input['setting_vkontakte_description'] );
				$values['setting_vkontakte_application_id']          = sanitize_text_field( $input['setting_vkontakte_application_id'] );
				$values['setting_vkontakte_page_or_group_id']        = sanitize_text_field( $input['setting_vkontakte_page_or_group_id'] );
				$values['setting_vkontakte_page_url']                = esc_url( $input['setting_vkontakte_page_url'] );
				$values['setting_vkontakte_width']                   = absint( $input['setting_vkontakte_width'] );
				$values['setting_vkontakte_height']                  = absint( $input['setting_vkontakte_height'] );
				$values['setting_vkontakte_layout']                  = absint( $input['setting_vkontakte_layout'] );
				$values['setting_vkontakte_color_background']        = sanitize_text_field( $input['setting_vkontakte_color_background'] );
				$values['setting_vkontakte_color_text']              = sanitize_text_field( $input['setting_vkontakte_color_text'] );
				$values['setting_vkontakte_color_button']            = sanitize_text_field( $input['setting_vkontakte_color_button'] );
				$values['setting_vkontakte_close_window_after_join'] = isset( $input['setting_vkontakte_close_window_after_join'] ) ? 1 : 0;

				break;

			case SMP_PREFIX . '-section-vkontakte-tracking':
				$values['tracking_use_vkontakte']               = isset( $input['tracking_use_vkontakte'] ) ? 1 : 0;
				$values['tracking_vkontakte_subscribe_event']   = sanitize_text_field( $input['tracking_vkontakte_subscribe_event'] );
				$values['tracking_vkontakte_unsubscribe_event'] = sanitize_text_field( $input['tracking_vkontakte_unsubscribe_event'] );

				break;

			case SMP_PREFIX . '-section-odnoklassniki':
				$values['setting_use_odnoklassniki']              = isset( $input['setting_use_odnoklassniki'] ) ? 1 : 0;
				$values['setting_odnoklassniki_tab_caption']      = sanitize_text_field( $input['setting_odnoklassniki_tab_caption'] );
				$values['setting_odnoklassniki_show_description'] = isset( $input['setting_odnoklassniki_show_description'] ) ? 1 : 0;
				$values['setting_odnoklassniki_description']      = wp_kses_post( $input['setting_odnoklassniki_description'] );
				$values['setting_odnoklassniki_group_id']         = sanitize_text_field( $input['setting_odnoklassniki_group_id'] );
				$values['setting_odnoklassniki_group_url']        = esc_url( $input['setting_odnoklassniki_group_url'] );
				$values['setting_odnoklassniki_width']            = absint( $input['setting_odnoklassniki_width'] );
				$values['setting_odnoklassniki_height']           = absint( $input['setting_odnoklassniki_height'] );

				break;

			case SMP_PREFIX . '-section-googleplus':
				$values['setting_use_googleplus']              = isset( $input['setting_use_googleplus'] ) ? 1 : 0;
				$values['setting_googleplus_tab_caption']      = sanitize_text_field( $input['setting_googleplus_tab_caption'] );
				$values['setting_googleplus_show_description'] = isset( $input['setting_googleplus_show_description'] ) ? 1 : 0;
				$values['setting_googleplus_description']      = wp_kses_post( $input['setting_googleplus_description'] );
				$values['setting_googleplus_page_url']         = esc_url( $input['setting_googleplus_page_url'] );
				$values['setting_googleplus_layout']           = sanitize_text_field( $input['setting_googleplus_layout'] );
				$values['setting_googleplus_locale']           = sanitize_text_field( $input['setting_googleplus_locale'] );
				$values['setting_googleplus_size']             = absint( $input['setting_googleplus_size'] );
				$values['setting_googleplus_theme']            = sanitize_text_field( $input['setting_googleplus_theme'] );
				$values['setting_googleplus_show_cover_photo'] = isset( $input['setting_googleplus_show_cover_photo'] ) ? 1 : 0;
				$values['setting_googleplus_show_tagline']     = isset( $input['setting_googleplus_show_tagline'] ) ? 1 : 0;
				$values['setting_googleplus_page_type']        = sanitize_text_field( $input['setting_googleplus_page_type'] );

				break;

			case SMP_PREFIX . '-section-twitter-general':
				$values['setting_use_twitter']                     = isset( $input['setting_use_twitter'] ) ? 1 : 0;
				$values['setting_twitter_tab_caption']             = sanitize_text_field( $input['setting_twitter_tab_caption'] );
				$values['setting_twitter_show_description']        = isset( $input['setting_twitter_show_description'] ) ? 1 : 0;
				$values['setting_twitter_description']             = wp_kses_post( $input['setting_twitter_description'] );
				$values['setting_twitter_username']                = sanitize_text_field( $input['setting_twitter_username'] );
				$values['setting_twitter_locale']                  = sanitize_text_field( $input['setting_twitter_locale'] );
				$values['setting_twitter_first_widget']            = sanitize_text_field( $input['setting_twitter_first_widget'] );
				$values['setting_twitter_close_window_after_join'] = isset( $input['setting_twitter_close_window_after_join'] ) ? 1 : 0;

				break;

			case SMP_PREFIX . '-section-twitter-follow-button':
				$values['setting_twitter_use_follow_button']        = isset( $input['setting_twitter_use_follow_button'] ) ? 1 : 0;
				$values['setting_twitter_show_count']               = isset( $input['setting_twitter_show_count'] ) ? 1 : 0;
				$values['setting_twitter_show_screen_name']         = isset( $input['setting_twitter_show_screen_name'] ) ? 1 : 0;
				$values['setting_twitter_follow_button_large_size'] = isset( $input['setting_twitter_follow_button_large_size'] ) ? 1 : 0;
				$values['setting_twitter_follow_button_align_by']   = sanitize_text_field( $input['setting_twitter_follow_button_align_by'] );

				break;

			case SMP_PREFIX . '-section-twitter-timeline':
				$values['setting_twitter_use_timeline'] = isset( $input['setting_twitter_use_timeline'] ) ? 1 : 0;
				$values['setting_twitter_theme']        = sanitize_text_field( $input['setting_twitter_theme'] );
				$values['setting_twitter_link_color']   = sanitize_text_field( $input['setting_twitter_link_color'] );
				$values['setting_twitter_tweet_limit']  = absint( $input['setting_twitter_tweet_limit'] );
				$values['setting_twitter_show_replies'] = isset( $input['setting_twitter_show_replies'] ) ? 1 : 0;
				$values['setting_twitter_width']        = absint( $input['setting_twitter_width'] );
				$values['setting_twitter_height']       = absint( $input['setting_twitter_height'] );
				$values['setting_twitter_chrome']       = sanitize_text_field( $input['setting_twitter_chrome'] );

				break;

			case SMP_PREFIX . '-section-twitter-tracking':
				$values['tracking_use_twitter']   = isset( $input['tracking_use_twitter'] ) ? 1 : 0;
				$values['tracking_twitter_event'] = sanitize_text_field( $input['tracking_twitter_event'] );

				break;

			case SMP_PREFIX . '-section-pinterest':
				$values['setting_use_pinterest']              = isset( $input['setting_use_pinterest'] ) ? 1 : 0;
				$values['setting_pinterest_tab_caption']      = sanitize_text_field( $input['setting_pinterest_tab_caption'] );
				$values['setting_pinterest_show_description'] = isset( $input['setting_pinterest_show_description'] ) ? 1 : 0;
				$values['setting_pinterest_description']      = wp_kses_post( $input['setting_pinterest_description'] );
				$values['setting_pinterest_profile_url']      = esc_url( $input['setting_pinterest_profile_url'] );
				$values['setting_pinterest_image_width']      = absint( $input['setting_pinterest_image_width'] );
				$values['setting_pinterest_width']            = absint( $input['setting_pinterest_width'] );
				$values['setting_pinterest_height']           = absint( $input['setting_pinterest_height'] );

				break;
		}

		return $values;
	}

	/**
	 * Sanitize field `setting_tabs_order`
	 *
	 * @param string $value Value
	 * @return string
	 */
	private static function sanitize_tabs_order( $value ) {
		$values = self::clean_array( explode( ',', $value ) );
		$diff   = array_diff( $values, SMP_Provider::AVAILABLE_PROVIDERS );

		return join( ',', array_diff( $values, $diff ) );
	}

	/**
	 * Sanitize field `setting_animation_style`
	 *
	 * @param string $value Value
	 * @return string
	 */
	private static function sanitize_animation_style( $value ) {
		$values = SMP_Settings_Field::get_animation_styles();

		foreach ( $values as $optgroup => $items ) {
			if ( isset( $items[ $value ] ) ) {
				return $value;
			}
		}

		return 'bounce';
	}

	/**
	 * Sanitize field `setting_icons_size`
	 *
	 * @param string $value Value
	 * @return string
	 */
	private static function sanitize_icons_size( $value ) {
		$values = SMP_Settings_Field::get_icons_sizes();

		if ( isset( $values[ $value ] ) ) {
			return $value;
		}

		return 'lg';
	}

	/**
	 * Sanitize field `setting_show_close_button_in`
	 *
	 * @param string $value Value
	 * @return string
	 */
	private static function sanitize_show_close_button_in( $value ) {
		$values = SMP_Settings_Field::get_close_button_in();

		if ( isset( $values[ $value ] ) ) {
			return $value;
		}

		return 'inside';
	}

	/**
	 * Sanitize field `setting_button_to_close_widget_style`
	 *
	 * @param string $value Value
	 * @return string
	 */
	private static function sanitize_button_to_close_widget_style( $value ) {
		$values = SMP_Settings_Field::get_close_button_styles();

		if ( isset( $values[ $value ] ) ) {
			return $value;
		}

		return 'link';
	}

	/**
	 * Sanitize field `setting_overlay_opacity`
	 *
	 * @param string $value Value
	 * @return integer
	 */
	private static function sanitize_overlay_opacity( $value ) {
		$value = absint( $value );

		return ( $value >= 0 && $value <= 100 ) ? $value : 80;
	}

	/**
	 * Sanitize field `when_should_the_popup_appear`
	 *
	 * @param array $values Values
	 * @return array
	 */
	private static function sanitize_when_should_the_popup_appear( $values ) {
		$when_should_the_popup_appear = SMP_Settings_Field::get_when_should_the_popup_appear();
		$result                       = [];

		$values = self::clean_array( explode( ',', $values ) );
		foreach ( $values as $value ) {
			if ( isset( $when_should_the_popup_appear[ $value ] ) ) {
				$result[] = $value;
			}
		}

		return $result;
	}

	/**
	 * Sanitize field `who_should_see_the_popup`
	 *
	 * @param array $values Values
	 * @return array
	 */
	private static function sanitize_who_should_see_the_popup( $values ) {
		$who_should_see_the_popup = SMP_Settings_Field::get_who_should_see_the_popup();
		$result                   = [];

		$values = self::clean_array( explode( ',', $values ) );
		foreach ( $values as $value ) {
			if ( isset( $who_should_see_the_popup[ $value ] ) ) {
				$result[] = $value;
			}
		}

		return $result;
	}

	/**
	 * Sanitize field `visitor_registered_and_role_equals_to`
	 *
	 * @param string $value Value
	 * @return string
	 */
	private static function sanitize_visitor_registered_and_role_equals_to( $value ) {
		$values = SMP_Settings_Field::get_visitor_registered_and_role_equals_to();

		if ( isset( $values[ $value ] ) ) {
			return $value;
		}

		return 'all_registered_users';
	}

	/**
	 * Sanitize field `setting_background_image`
	 *
	 * @param string $value Value
	 * @return string
	 */
	private static function sanitize_background_image( $value ) {
		return ( filter_var( $value, FILTER_VALIDATE_URL ) ) ? trim( $value ) : '';
	}

	/**
	 * Sanitize HEX colors
	 *
	 * @param string $value Value
	 * @param string $default Default value
	 * @return string
	 */
	private static function sanitize_hex_color( $value, $default = '#000000' ) {
		return preg_match( '/^#([[:xdigit:]]{3}){1,2}$/', $value ) ? $value : $default;
	}

	/**
	 * Lowercase and trim each array item.
	 * After that delete empty items and uniqueize array
	 *
	 * @param array $items Items
	 * @return array
	 */
	private static function clean_array( $items ) {
		$lowercase_and_trim = function( $item ) {
			return strtolower( trim( $item ) );
		};

		return array_unique( array_filter( array_map( $lowercase_and_trim, $items ) ) );
	}

	/**
	 * Checks if checkbox value is checked
	 *
	 * @param mixed $value Value
	 * @return boolean
	 */
	private static function sanitize_checkbox( $value ) {
		return ( isset( $value ) && 0 != absint( $value ) ) ? 1 : 0;
	}
}
