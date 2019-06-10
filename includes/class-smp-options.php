<?php
/**
 * Social Media Popup Options
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

/**
 * SMP_Options
 *
 * @since 1.0.0
 */
class SMP_Options {
	/**
	 * Options array
	 *
	 * @var $options
	 */
	private static $_options = false;

	/**
	 * Get option by name
	 *
	 * @param string $name Name
	 * @return mixed
	 */
	public static function get_option( $name ) {
		if ( self::initialize_options() && isset( self::$_options[ $name ] ) ) {
			return self::$_options[ $name ];
		}

		return false;
	}

	/**
	 * Load options
	 *
	 * @return array
	 */
	public static function get_options() {
		return self::load_options();
	}

	/**
	 * Update option
	 *
	 * @param string $name Name
	 * @param mixed  $value Value
	 */
	public static function update_option( $name, $value ) {
		if ( empty( $name ) ) {
			return;
		}

		self::merge_options( array( $name => $value ) );
	}

	/**
	 * Merge option
	 *
	 * @param array $options Options
	 */
	public static function merge_options( $options ) {
		if ( count( $options ) === 0 ) {
			return;
		}

		$option = array_merge( self::load_options(), $options );
		update_option( 'social_media_popup', $option );
	}

	/**
	 * Delete options
	 */
	public static function delete_options() {
		delete_option( 'social_media_popup' );
	}

	/**
	 * Initialize options with default values
	 */
	public static function set_default_options() {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow
		$options = array(
			//
			// General
			//
			'setting_debug_mode'                       => 1,
			'setting_tabs_order'                       => 'facebook,vkontakte,odnoklassniki,googleplus,twitter,pinterest',
			'setting_close_popup_by_clicking_anywhere' => 0,
			'setting_close_popup_when_esc_pressed'     => 0,
			'setting_show_on_mobile_devices'           => 0,
			'setting_show_admin_bar_menu'              => 1,

			//
			// Desktop View
			//
			'setting_plugin_title'                        =>
				'<div style="text-align: center;font: bold normal 14pt/16pt Arial">'
				. esc_html( 'Follow Us on Social Media!', 'social-media-popup' )
				. '</div>',
			'setting_use_animation'                       => 1,
			'setting_animation_style'                     => 'bounce',
			'setting_use_icons_instead_of_labels_in_tabs' => 1,
			'setting_icons_size_on_desktop'               => 'lg',
			'setting_hide_tabs_if_one_widget_is_active'   => 1,
			'setting_align_tabs_to_center'                => 0,
			'setting_show_close_button_in'                => 'inside',
			'setting_show_button_to_close_widget'         => 1,
			'setting_button_to_close_widget_title'        => __( "Thanks! Please don't show me popup.", 'social-media-popup' ),
			'setting_button_to_close_widget_style'        => 'link',
			'setting_delay_before_show_bottom_button'     => 0,
			'setting_container_width'                     => 400,
			'setting_container_height'                    => 520,
			'setting_border_radius'                       => 10,
			'setting_overlay_color'                       => '#000000',
			'setting_overlay_opacity'                     => 80,
			'setting_background_image'                    => '',

			//
			// Mobile View
			//
			'setting_plugin_title_on_mobile_devices' => __( 'Follow Us on Social Media!', 'social-media-popup' ),
			'setting_icons_size_on_mobile_devices'   => '2x',

			//
			// Events (When)
			//
			'when_should_the_popup_appear'                     => '',
			'popup_will_appear_after_n_seconds'                => 3,
			'popup_will_appear_after_clicking_on_element'      => '',
			'event_hide_element_after_click_on_it'             => 0,
			'do_not_use_cookies_after_click_on_element'        => 1,
			'popup_will_appear_after_scrolling_down_n_percent' => 70,
			'popup_will_appear_on_exit_intent'                 => 0,

			//
			// Events (Who)
			//
			'who_should_see_the_popup'                  => '',
			'visitor_opened_at_least_n_number_of_pages' => 0,
			'visitor_registered_and_role_equals_to'     => 'all',
			'setting_display_after_n_days'              => 30,

			//
			// Tracking (General)
			//
			'use_events_tracking'               => 0,
			'do_not_use_tracking_in_debug_mode' => 1,

			//
			// Tracking (Google Analytics)
			//
			'google_analytics_tracking_id'             => '',
			'push_events_to_aquisition_social_plugins' => 1,

			//
			// Tracking (Window Events)
			//
			'push_events_when_displaying_window'                   => 1,
			'tracking_event_label_window_showed_immediately'       => __( 'Show immediately', 'social-media-popup' ),
			'tracking_event_label_window_showed_with_delay'        => __( 'Show after delay before it rendered', 'social-media-popup' ),
			'tracking_event_label_window_showed_after_click'       => __( 'Show after click on CSS-selector', 'social-media-popup' ),
			'tracking_event_label_window_showed_on_scrolling_down' => __( 'Show after scrolling down', 'social-media-popup' ),
			'tracking_event_label_window_showed_on_exit_intent'    => __( 'Show on exit intent', 'social-media-popup' ),

			//
			// Tracking (Social Events)
			//
			'push_events_when_subscribing_on_social_networks' => 1,
			'add_window_events_descriptions'                  => 1,
			'tracking_event_label_no_events_fired'            => __( '(no events fired)', 'social-media-popup' ),
			'tracking_event_label_on_delay'                   => __( 'After delay before show widget', 'social-media-popup' ),
			'tracking_event_label_after_click'                => __( 'After click on CSS-selector', 'social-media-popup' ),
			'tracking_event_label_on_scrolling_down'          => __( 'On scrolling down', 'social-media-popup' ),
			'tracking_event_label_on_exit_intent'             => __( 'On exit intent', 'social-media-popup' ),

			//
			// Facebook (General)
			//
			'setting_use_facebook'                     => 1,
			'setting_facebook_tab_caption'             => __( 'Facebook', 'social-media-popup' ),
			'setting_facebook_show_description'        => 0,
			'setting_facebook_description'             => '',
			'setting_facebook_application_id'          => '277165072394537',
			'setting_facebook_page_url'                => 'https://www.facebook.com/gruz0.ru',
			'setting_facebook_locale'                  => 'en_US',
			'setting_facebook_width'                   => 400,
			'setting_facebook_height'                  => 440,
			'setting_facebook_adapt_container_width'   => 1,
			'setting_facebook_use_small_header'        => 0,
			'setting_facebook_hide_cover'              => '1',
			'setting_facebook_show_facepile'           => 1,
			'setting_facebook_tabs'                    => '',
			'setting_facebook_close_window_after_join' => 0,

			//
			// Facebook (Tracking)
			//
			'tracking_use_facebook'               => 1,
			'tracking_facebook_subscribe_event'   => __( 'Subscribe on Facebook', 'social-media-popup' ),
			'tracking_facebook_unsubscribe_event' => __( 'Unsubscribe from Facebook', 'social-media-popup' ),

			//
			// VK (General)
			//
			'setting_use_vkontakte'                     => 1,
			'setting_vkontakte_tab_caption'             => __( 'VK', 'social-media-popup' ),
			'setting_vkontakte_show_description'        => 0,
			'setting_vkontakte_description'             => '',
			'setting_vkontakte_application_id'          => '',
			'setting_vkontakte_page_or_group_id'        => '64088617',
			'setting_vkontakte_page_url'                => 'https://vk.com/ru_wp',
			'setting_vkontakte_width'                   => 400,
			'setting_vkontakte_height'                  => 430,
			'setting_vkontakte_layout'                  => 0,
			'setting_vkontakte_color_background'        => '#FFFFFF',
			'setting_vkontakte_color_text'              => '#2B587A',
			'setting_vkontakte_color_button'            => '#5B7FA6',
			'setting_vkontakte_close_window_after_join' => 0,

			//
			// VK (General)
			//
			'tracking_use_vkontakte'               => 1,
			'tracking_vkontakte_subscribe_event'   => __( 'Subscribe on VK.com', 'social-media-popup' ),
			'tracking_vkontakte_unsubscribe_event' => __( 'Unsubscribe from VK.com', 'social-media-popup' ),

			//
			// Odnoklassniki
			//
			'setting_use_odnoklassniki'              => 0,
			'setting_odnoklassniki_tab_caption'      => __( 'Odnoklassniki', 'social-media-popup' ),
			'setting_odnoklassniki_show_description' => 0,
			'setting_odnoklassniki_description'      => '',
			'setting_odnoklassniki_group_id'         => '57122812461115',
			'setting_odnoklassniki_group_url'        => 'https://ok.ru/group/57122812461115',
			'setting_odnoklassniki_width'            => 400,
			'setting_odnoklassniki_height'           => 420,

			//
			// Google+
			//
			'setting_use_googleplus'              => 1,
			'setting_googleplus_tab_caption'      => __( 'Google+', 'social-media-popup' ),
			'setting_googleplus_show_description' => 0,
			'setting_googleplus_description'      => '',
			'setting_googleplus_page_type'        => 'person',
			'setting_googleplus_page_url'         => 'https://plus.google.com/+AlexanderKadyrov',
			'setting_googleplus_layout'           => 'portrait',
			'setting_googleplus_locale'           => 'en',
			'setting_googleplus_size'             => 400,
			'setting_googleplus_theme'            => 'light',
			'setting_googleplus_show_cover_photo' => 1,
			'setting_googleplus_show_tagline'     => 1,

			//
			// Twitter (General)
			//
			'setting_use_twitter'                     => 1,
			'setting_twitter_tab_caption'             => __( 'Twitter', 'social-media-popup' ),
			'setting_twitter_show_description'        => 0,
			'setting_twitter_description'             => '',
			'setting_twitter_username'                => 'gruz0',
			'setting_twitter_locale'                  => 'en',
			'setting_twitter_first_widget'            => 'follow_button',
			'setting_twitter_close_window_after_join' => 0,

			//
			// Twitter (Follow Button)
			//
			'setting_twitter_use_follow_button'        => 1,
			'setting_twitter_show_count'               => 1,
			'setting_twitter_show_screen_name'         => 1,
			'setting_twitter_follow_button_large_size' => 1,
			'setting_twitter_follow_button_align_by'   => 'center',

			//
			// Twitter (Timeline Widget)
			//
			'setting_twitter_use_timeline' => 1,
			'setting_twitter_theme'        => 'light',
			'setting_twitter_link_color'   => '#CC0000',
			'setting_twitter_tweet_limit'  => 5,
			'setting_twitter_show_replies' => 0,
			'setting_twitter_width'        => 400,
			'setting_twitter_height'       => 400,
			'setting_twitter_chrome'       => '',

			//
			// Twitter (Tracking)
			//
			'tracking_use_twitter'   => 1,
			'tracking_twitter_event' => __( 'Follow on Twitter', 'social-media-popup' ),

			//
			// Pinterest
			//
			'setting_use_pinterest'              => 1,
			'setting_pinterest_tab_caption'      => __( 'Pinterest', 'social-media-popup' ),
			'setting_pinterest_show_description' => 0,
			'setting_pinterest_description'      => '',
			'setting_pinterest_profile_url'      => 'http://pinterest.com/gruz0/',
			'setting_pinterest_image_width'      => 60,
			'setting_pinterest_width'            => 380,
			'setting_pinterest_height'           => 320,

			//
			// Version
			//
			'version' => '1.0.0',
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.LongIndexSpaceBeforeDoubleArrow

		self::update_options( $options );
	}

	/**
	 * Load options
	 *
	 * @return array
	 */
	private static function load_options() {
		$option = get_option( 'social_media_popup' );
		return ( false === $option ) ? array() : (array) $option;
	}

	/**
	 * Load options
	 *
	 * @return boolean
	 */
	private static function initialize_options() {
		if ( false === self::$_options || count( self::$_options ) === 0 ) {
			self::$_options = self::load_options();

			if ( count( self::$_options ) === 0 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Update options
	 *
	 * @param array $options Options
	 */
	private static function update_options( $options ) {
		update_option( 'social_media_popup', (array) $options );
	}
}
