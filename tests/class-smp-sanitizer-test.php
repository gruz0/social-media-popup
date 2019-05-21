<?php
// :nodoc
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// FIXME: It should be wrapped with is_dockerized() to run tests inside Travis CI
include '../../../wp-load.php';

/**
 * SMP_Sanitizer Test
 */
final class SMP_Sanitizer_Test extends TestCase {
	const SECTION_COMMON_GENERAL                   = SMP_PREFIX . '-section-common';
	const SECTION_COMMON_VIEW_DESKTOP              = SMP_PREFIX . '-section-common-view';
	const SECTION_COMMON_VIEW_MOBILE               = SMP_PREFIX . '-section-common-view-mobile';
	const SECTION_COMMON_EVENTS_GENERAL            = SMP_PREFIX . '-section-common-events-general';
	const SECTION_COMMON_EVENTS_WHO                = SMP_PREFIX . '-section-common-events-who';
	const SECTION_COMMON_TRACKING_GENERAL          = SMP_PREFIX . '-section-common-tracking-general';
	const SECTION_COMMON_TRACKING_GOOGLE_ANALYTICS = SMP_PREFIX . '-section-common-tracking-google-analytics';
	const SECTION_COMMON_TRACKING_WINDOW_EVENTS    = SMP_PREFIX . '-section-common-tracking-window-events';
	const SECTION_COMMON_TRACKING_SOCIAL_EVENTS    = SMP_PREFIX . '-section-common-tracking-social-events';
	const SECTION_COMMON_MANAGEMENT                = SMP_PREFIX . '-section-common-management';
	const SECTION_FACEBOOK_GENERAL                 = SMP_PREFIX . '-section-facebook-general';
	const SECTION_FACEBOOK_TRACKING                = SMP_PREFIX . '-section-facebook-tracking';
	const SECTION_VK_GENERAL                       = SMP_PREFIX . '-section-vkontakte-general';
	const SECTION_VK_TRACKING                      = SMP_PREFIX . '-section-vkontakte-tracking';
	const SECTION_OK_GENERAL                       = SMP_PREFIX . '-section-odnoklassniki';
	const SECTION_TWITTER_GENERAL                  = SMP_PREFIX . '-section-twitter-general';
	const SECTION_TWITTER_FOLLOW_BUTTON            = SMP_PREFIX . '-section-twitter-follow-button';
	const SECTION_TWITTER_TIMELINE                 = SMP_PREFIX . '-section-twitter-timeline';
	const SECTION_TWITTER_TRACKING                 = SMP_PREFIX . '-section-twitter-tracking';
	const SECTION_PINTEREST_GENERAL                = SMP_PREFIX . '-section-pinterest';

	/**
	 * Set default options
	 */
	public function setUp() {
		SMP_Options::set_default_options();
	}

	/**
	 * Sanitize setting_debug_mode
	 */
	public function testCanBeSanitizedSettingDebugMode(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_GENERAL, 'setting_debug_mode' );
	}

	/**
	 * Sanitize setting_tabs_order
	 */
	public function testCanBeSanitizedSettingTabsOrder(): void {
		$key      = 'setting_tabs_order';
		$value    = "  vkontakte,facebook\"<a href                = '' />\", gooGleplus, twitter, ODNOklassniki , vk , fac3book, twitter";
		$expected = 'vkontakte,googleplus,twitter,odnoklassniki';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_GENERAL, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_close_popup_by_clicking_anywhere
	 */
	public function testCanBeSanitizedSettingClosePopupByClickingAnywhere(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_GENERAL, 'setting_close_popup_by_clicking_anywhere' );
	}

	/**
	 * Sanitize setting_close_popup_when_esc_pressed
	 */
	public function testCanBeSanitizedSettingClosePopupWhenEscPressed(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_GENERAL, 'setting_close_popup_when_esc_pressed' );
	}

	/**
	 * Sanitize setting_show_on_mobile_devices
	 */
	public function testCanBeSanitizedSettingShowOnMobileDevices(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_GENERAL, 'setting_show_on_mobile_devices' );
	}

	/**
	 * Sanitize setting_show_admin_bar_menu
	 */
	public function testCanBeSanitizedSettingShowAdminBarMenu(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_GENERAL, 'setting_show_admin_bar_menu' );
	}

	/**
	 * Sanitize setting_plugin_title
	 */
	public function testCanBeSanitizedSettingPluginTitle(): void {
		$this->sanitizeKses( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_plugin_title' );
	}

	/**
	 * Sanitize setting_use_animation
	 */
	public function testCanBeSanitizedSettingUseAnimation(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_use_animation' );
	}

	/**
	 * Sanitize setting_animation_style
	 */
	public function testCanBeSanitizedSettingAnimationStyle(): void {
		$this->sanitizeDictionary( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_animation_style', 'bounce' );
	}

	/**
	 * Sanitize setting_use_icons_instead_of_labels_in_tabs
	 */
	public function testCanBeSanitizedSettingUseIconsInsteadOfLabelsInTabs(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_use_icons_instead_of_labels_in_tabs' );
	}

	/**
	 * Sanitize setting_icons_size_on_desktop
	 */
	public function testCanBeSanitizedSettingIconsSizeOnDesktop(): void {
		$this->sanitizeDictionary( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_icons_size_on_desktop', 'lg' );
	}

	/**
	 * Sanitize setting_hide_tabs_if_one_widget_is_active
	 */
	public function testCanBeSanitizedSettingHideTabsIfOneWidgetIsActive(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_hide_tabs_if_one_widget_is_active' );
	}

	/**
	 * Sanitize setting_container_width
	 */
	public function testCanBeSanitizedSettingContainerWidth(): void {
		$this->sanitizeInteger( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_container_width', '400' );
	}

	/**
	 * Sanitize setting_container_height
	 */
	public function testCanBeSanitizedSettingContainerHeight(): void {
		$this->sanitizeInteger( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_container_height', '400' );
	}

	/**
	 * Sanitize setting_border_radius
	 */
	public function testCanBeSanitizedSettingBorderRadius(): void {
		$this->sanitizeInteger( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_border_radius', '30' );
	}

	/**
	 * Sanitize setting_show_close_button_in
	 */
	public function testCanBeSanitizedSettingShowCloseButtonIn(): void {
		$this->sanitizeDictionary( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_show_close_button_in', 'inside' );
	}

	/**
	 * Sanitize setting_show_button_to_close_widget
	 */
	public function testCanBeSanitizedSettingShowButtonToCloseWidget(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_show_button_to_close_widget' );
	}

	/**
	 * Sanitize setting_button_to_close_widget_title
	 */
	public function testCanBeSanitizedSettingButtonToCloseWidgetTitle(): void {
		$this->sanitizeText( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_button_to_close_widget_title' );
	}

	/**
	 * Sanitize setting_button_to_close_widget_style
	 */
	public function testCanBeSanitizedSettingButtonToCloseWidgetStyle(): void {
		$this->sanitizeDictionary( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_button_to_close_widget_style', 'link' );
	}

	/**
	 * Sanitize setting_delay_before_show_bottom_button
	 */
	public function testCanBeSanitizedSettingDelayBeforeShowBottomButton(): void {
		$this->sanitizeInteger( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_delay_before_show_bottom_button', '3' );
	}

	/**
	 * Sanitize setting_overlay_color
	 */
	public function testCanBeSanitizedSettingOverlayColor(): void {
		$key      = 'setting_overlay_color';
		$value    = 'qwe';
		$expected = '#000000';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_overlay_opacity
	 */
	public function testCanBeSanitizedSettingOverlayOpacity(): void {
		$key      = 'setting_overlay_opacity';
		$value    = 105;
		$expected = 80;

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_align_tabs_to_center
	 */
	public function testCanBeSanitizedSettingAlignTabsToCenter(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_VIEW_DESKTOP, 'setting_align_tabs_to_center' );
	}

	/**
	 * Sanitize setting_background_image
	 */
	public function testCanBeSanitizedSettingBackgroundImage(): void {
		$key      = 'setting_background_image';
		$value    = 'localhost/image.png';
		$expected = '';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_plugin_title_on_mobile_devices
	 */
	public function testCanBeSanitizedSettingPluginTitleOnMobileDevices(): void {
		$this->sanitizeKses( self::SECTION_COMMON_VIEW_MOBILE, 'setting_plugin_title_on_mobile_devices' );
	}

	/**
	 * Sanitize setting_icons_size_on_mobile_devices
	 */
	public function testCanBeSanitizedSettingIconsSizeOnMobileDevices(): void {
		$this->sanitizeDictionary( self::SECTION_COMMON_VIEW_MOBILE, 'setting_icons_size_on_mobile_devices', 'lg' );
	}

	/**
	 * Sanitize when_should_the_popup_appear
	 */
	public function testCanBeSanitizedSettingWhenShouldThePopupAppear(): void {
		$values = array(
			'when_should_the_popup_appear'                     => ' after_n_seconds, after_clicking_on_element  ,after_n_seconds,after_scrolling_down_n_percent, on_exit_intent, ,',
			'popup_will_appear_after_n_seconds'                => '-30',
			'popup_will_appear_after_clicking_on_element'      => ' #my-button, .entry .button йцу123',
			'event_hide_element_after_click_on_it'             => 2,
			'do_not_use_cookies_after_click_on_element'        => 3,
			'popup_will_appear_after_scrolling_down_n_percent' => '-70',
			'popup_will_appear_on_exit_intent'                 => 4,
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals(
			'after_n_seconds,after_clicking_on_element,after_scrolling_down_n_percent,on_exit_intent',
			$result['when_should_the_popup_appear']
		);
	}

	/**
	 * Sanitize popup_will_appear_after_n_seconds
	 */
	public function testCanBeSanitizedSettingPopupWillAppearAfterNSeconds(): void {
		// If the key exists in the array then apply `absint` to the value
		$values = array(
			'when_should_the_popup_appear'      => 'after_n_seconds,',
			'popup_will_appear_after_n_seconds' => '-30',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( 30, $result['popup_will_appear_after_n_seconds'] );

		// If the key does not exist in the array then return 0
		$values = array(
			'when_should_the_popup_appear' => '',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( 0, $result['popup_will_appear_after_n_seconds'] );

		// If the key exists but dependent option is empty then delete key from array
		$values = array(
			'when_should_the_popup_appear'      => 'after_n_seconds,',
			'popup_will_appear_after_n_seconds' => ' ',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( '', $result['when_should_the_popup_appear'] );
		$this->assertEquals( 0, $result['popup_will_appear_after_n_seconds'] );
	}

	/**
	 * Sanitize popup_will_appear_after_clicking_on_element
	 */
	public function testCanBeSanitizedSettingPopupWillAppearAfterClickingOnElement(): void {
		// If the key exists in the array then clean up the values of dependent options
		$values = array(
			'when_should_the_popup_appear'                => 'after_clicking_on_element,',
			'popup_will_appear_after_clicking_on_element' => ' #my-button_new, .entry .button йцу123',
			'event_hide_element_after_click_on_it'        => 2,
			'do_not_use_cookies_after_click_on_element'   => 3,
		);
		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( '#my-button_new, .entry .button 123', $result['popup_will_appear_after_clicking_on_element'] );
		$this->assertEquals( 1, $result['event_hide_element_after_click_on_it'] );
		$this->assertEquals( 1, $result['do_not_use_cookies_after_click_on_element'] );

		// If the key does not exist in the array then clean values of dependent options
		$values = array( 'when_should_the_popup_appear' => '' );
		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( '', $result['popup_will_appear_after_clicking_on_element'] );
		$this->assertEquals( 0, $result['event_hide_element_after_click_on_it'] );
		$this->assertEquals( 0, $result['do_not_use_cookies_after_click_on_element'] );

		// If the key exists but all values of dependent options are empty or not checked then delete key from array
		$values = array(
			'when_should_the_popup_appear'                => 'after_clicking_on_element,',
			'popup_will_appear_after_clicking_on_element' => ' ',
			'event_hide_element_after_click_on_it'        => 0,
			'do_not_use_cookies_after_click_on_element'   => 0,
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( '', $result['when_should_the_popup_appear'] );
		$this->assertEquals( '', $result['popup_will_appear_after_clicking_on_element'] );
		$this->assertEquals( 0, $result['event_hide_element_after_click_on_it'] );
		$this->assertEquals( 0, $result['do_not_use_cookies_after_click_on_element'] );
	}

	/**
	 * Sanitize popup_will_appear_after_scrolling_down_n_percent
	 */
	public function testCanBeSanitizedSettingPopupWillAppearAfterScrollingDownNPercent(): void {
		// If the key exists in the array then apply `absint` to the value
		$values = array(
			'when_should_the_popup_appear'                     => 'after_scrolling_down_n_percent,',
			'popup_will_appear_after_scrolling_down_n_percent' => '-70',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( 70, $result['popup_will_appear_after_scrolling_down_n_percent'] );

		// If the dependent option value is lower than 100 then return default value
		$values = array(
			'when_should_the_popup_appear'                     => 'after_scrolling_down_n_percent,',
			'popup_will_appear_after_scrolling_down_n_percent' => '110',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( 70, $result['popup_will_appear_after_scrolling_down_n_percent'] );

		// If the key does not exist in the array then return 0
		$values = array(
			'when_should_the_popup_appear' => '',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( 0, $result['popup_will_appear_after_scrolling_down_n_percent'] );
	}

	/**
	 * Sanitize popup_will_appear_on_exit_intent
	 */
	public function testCanBeSanitizedSettingPopupWillAppearAfterOnExitIntent(): void {
		// If the key exists in the array then clean up the values of dependent options
		$values = array(
			'when_should_the_popup_appear'     => 'on_exit_intent,',
			'popup_will_appear_on_exit_intent' => 2,
		);
		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( 1, $result['popup_will_appear_on_exit_intent'] );

		// If the key does not exist in the array then clean values of dependent options
		$values = array( 'when_should_the_popup_appear' => '' );
		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( 0, $result['popup_will_appear_on_exit_intent'] );

		// If the key exists but dependent option is not checked then delete key from array
		$values = array(
			'when_should_the_popup_appear'     => 'on_exit_intent,',
			'popup_will_appear_on_exit_intent' => 0,
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( '', $result['when_should_the_popup_appear'] );
		$this->assertEquals( 0, $result['popup_will_appear_on_exit_intent'] );
	}

	/**
	 * Sanitize who_should_see_the_popup
	 */
	public function testCanBeSanitizedSettingWhoShouldSeeThePopup(): void {
		$values = array(
			'who_should_see_the_popup'                  => ' visitor_opened_at_least_n_number_of_pages, visitor_registered_and_role_equals_to, ',
			'visitor_opened_at_least_n_number_of_pages' => '-3',
			'visitor_registered_and_role_equals_to'     => 'all_registered_users',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_WHO, $values );
		$this->assertEquals(
			'visitor_opened_at_least_n_number_of_pages,visitor_registered_and_role_equals_to',
			$result['who_should_see_the_popup']
		);
	}

	/**
	 * Sanitize visitor_opened_at_least_n_number_of_pages
	 */
	public function testCanBeSanitizedSettingVisitorOpenedAtLeastNNumberOfPages(): void {
		// If the key exists in the array then apply `absint` to the value
		$values = array(
			'who_should_see_the_popup'                  => 'visitor_opened_at_least_n_number_of_pages,',
			'visitor_opened_at_least_n_number_of_pages' => '-2',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_WHO, $values );
		$this->assertEquals( 2, $result['visitor_opened_at_least_n_number_of_pages'] );

		// If the key does not exist in the array then return 0
		$values = array(
			'who_should_see_the_popup' => '',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_WHO, $values );
		$this->assertEquals( 0, $result['visitor_opened_at_least_n_number_of_pages'] );

		// If the key exists but dependent option is empty then delete key from array
		$values = array(
			'who_should_see_the_popup' => 'visitor_opened_at_least_n_number_of_pages,',
			'visitor_opened_at_least_n_number_of_pages' => ' ',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_WHO, $values );
		$this->assertEquals( '', $result['who_should_see_the_popup'] );
		$this->assertEquals( 0, $result['visitor_opened_at_least_n_number_of_pages'] );
	}

	/**
	 * Sanitize visitor_registered_and_role_equals_to
	 */
	public function testCanBeSanitizedSettingVisitorRegisteredAndRoleEqualsTo(): void {
		$expected = 'all_registered_users';

		// If the key does not exist in the array then return default value
		$values = array(
			'who_should_see_the_popup'              => 'visitor_registered_and_role_equals_to',
			'visitor_registered_and_role_equals_to' => 'test',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_WHO, $values );
		$this->assertEquals( $expected, $result['visitor_registered_and_role_equals_to'] );

		// If the value is empty then return default value
		$values = array(
			'who_should_see_the_popup' => 'visitor_registered_and_role_equals_to',
			'visitor_registered_and_role_equals_to' => '',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_WHO, $values );
		$this->assertEquals( $expected, $result['visitor_registered_and_role_equals_to'] );
	}

	/**
	 * Sanitize setting_display_after_n_days
	 */
	public function testCanBeSanitizedSettingDisplayAfterNDays(): void {
		$this->sanitizeInteger( self::SECTION_COMMON_EVENTS_WHO, 'setting_display_after_n_days', '70' );
	}

	/**
	 * Sanitize use_events_tracking
	 */
	public function testCanBeSanitizedSettingUseEventsTracking(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_TRACKING_GENERAL, 'use_events_tracking' );
	}

	/**
	 * Sanitize do_not_use_tracking_in_debug_mode
	 */
	public function testCanBeSanitizedSettingDoNotUseTrackingInDebugMode(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_TRACKING_GENERAL, 'do_not_use_tracking_in_debug_mode' );
	}

	/**
	 * Sanitize google_analytics_tracking_id
	 */
	public function testCanBeSanitizedSettingGoogleAnalyticsTrackingId(): void {
		$key      = 'google_analytics_tracking_id';
		$value    = '   UA-123456-111йцукен! ';
		$expected = 'UA-123456-111';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_TRACKING_GOOGLE_ANALYTICS, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize push_events_to_aquisition_social_plugins
	 */
	public function testCanBeSanitizedSettingPushEventsToAquisitionSocialPlugins(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_TRACKING_GOOGLE_ANALYTICS, 'push_events_to_aquisition_social_plugins' );
	}

	/**
	 * Sanitize push_events_when_displaying_window
	 */
	public function testCanBeSanitizedSettingPushEventsWhenDisplayingWindow(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_TRACKING_WINDOW_EVENTS, 'push_events_when_displaying_window' );
	}

	/**
	 * Sanitize tracking_event_label_window_showed_immediately
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelWindowShowedImmediately(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_WINDOW_EVENTS, 'tracking_event_label_window_showed_immediately' );
	}

	/**
	 * Sanitize tracking_event_label_window_showed_with_delay
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelWindowShowedWithDelay(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_WINDOW_EVENTS, 'tracking_event_label_window_showed_with_delay' );
	}

	/**
	 * Sanitize tracking_event_label_window_showed_after_click
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelWindowShowedAfterClick(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_WINDOW_EVENTS, 'tracking_event_label_window_showed_after_click' );
	}

	/**
	 * Sanitize tracking_event_label_window_showed_on_scrolling_down
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelWindowShowedOnScrollingDown(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_WINDOW_EVENTS, 'tracking_event_label_window_showed_on_scrolling_down' );
	}

	/**
	 * Sanitize tracking_event_label_window_showed_on_exit_intent
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelWindowShowedOnExitIntent(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_WINDOW_EVENTS, 'tracking_event_label_window_showed_on_exit_intent' );
	}

	/**
	 * Sanitize push_events_when_subscribing_on_social_networks
	 */
	public function testCanBeSanitizedSettingPushEventsWhenSubscribingOnSocialNetworks(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_TRACKING_SOCIAL_EVENTS, 'push_events_when_subscribing_on_social_networks' );
	}

	/**
	 * Sanitize add_window_events_descriptions
	 */
	public function testCanBeSanitizedSettingAddWindowEventsDescriptions(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_TRACKING_SOCIAL_EVENTS, 'add_window_events_descriptions' );
	}

	/**
	 * Sanitize tracking_event_label_no_events_fired
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelNoEventsFired(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_SOCIAL_EVENTS, 'tracking_event_label_no_events_fired' );
	}

	/**
	 * Sanitize tracking_event_label_on_delay
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelOnDelay(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_SOCIAL_EVENTS, 'tracking_event_label_on_delay' );
	}

	/**
	 * Sanitize tracking_event_label_after_click
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelAfterClick(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_SOCIAL_EVENTS, 'tracking_event_label_after_click' );
	}

	/**
	 * Sanitize tracking_event_label_on_scrolling_down
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelOnScrollingDown(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_SOCIAL_EVENTS, 'tracking_event_label_on_scrolling_down' );
	}

	/**
	 * Sanitize tracking_event_label_on_exit_intent
	 */
	public function testCanBeSanitizedSettingTrackingEventLabelOnExitIntent(): void {
		$this->sanitizeText( self::SECTION_COMMON_TRACKING_SOCIAL_EVENTS, 'tracking_event_label_on_exit_intent' );
	}

	/**
	 * Sanitize setting_remove_settings_on_uninstall
	 */
	public function testCanBeSanitizedSettingRemoveSettingsOnUninstall(): void {
		$this->sanitizeCheckbox( self::SECTION_COMMON_MANAGEMENT, 'setting_remove_settings_on_uninstall' );
	}

	/**
	 * Sanitize setting_use_facebook
	 */
	public function testCanBeSanitizedSettingUseFacebook(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_GENERAL, 'setting_use_facebook' );
	}

	/**
	 * Sanitize setting_facebook_tab_caption
	 */
	public function testCanBeSanitizedSettingFacebookTabCaption(): void {
		$this->sanitizeText( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_tab_caption' );
	}

	/**
	 * Sanitize setting_facebook_show_description
	 */
	public function testCanBeSanitizedSettingFacebookShowDescription(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_show_description' );
	}

	/**
	 * Sanitize setting_facebook_description
	 */
	public function testCanBeSanitizedSettingFacebookDescription(): void {
		$this->sanitizeKses( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_description' );
	}

	/**
	 * Sanitize setting_facebook_application_id
	 */
	public function testCanBeSanitizedSettingFacebookApplicationId(): void {
		$this->sanitizeInteger( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_application_id', PHP_INT_MAX );
	}

	/**
	 * Sanitize setting_facebook_page_url
	 */
	public function testCanBeSanitizedSettingFacebookPageUrl(): void {
		$this->sanitizeUrl( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_page_url' );
	}

	/**
	 * Sanitize setting_facebook_locale
	 */
	public function testCanBeSanitizedSettingFacebookLocale(): void {
		$this->sanitizeDictionary( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_locale', 'en_US' );
	}

	/**
	 * Sanitize setting_facebook_width
	 */
	public function testCanBeSanitizedSettingFacebookWidth(): void {
		$this->sanitizeInteger( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_width', '400' );
	}

	/**
	 * Sanitize setting_facebook_height
	 */
	public function testCanBeSanitizedSettingFacebookHeight(): void {
		$this->sanitizeInteger( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_height', '400' );
	}

	/**
	 * Sanitize setting_facebook_use_small_header
	 */
	public function testCanBeSanitizedSettingFacebookUseSmallHeader(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_use_small_header' );
	}

	/**
	 * Sanitize setting_facebook_hide_cover
	 */
	public function testCanBeSanitizedSettingFacebookHideCover(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_hide_cover' );
	}

	/**
	 * Sanitize setting_facebook_show_facepile
	 */
	public function testCanBeSanitizedSettingFacebookShowFacepile(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_show_facepile' );
	}

	/**
	 * Sanitize setting_facebook_tabs
	 */
	public function testCanBeSanitizedSettingFacebookTabs(): void {
		$values = array(
			'setting_facebook_tabs' => ' timeline, messages, test, events ',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_FACEBOOK_GENERAL, $values );
		$this->assertEquals( 'timeline,messages,events', $result['setting_facebook_tabs'] );
	}

	/**
	 * Sanitize setting_facebook_adapt_container_width
	 */
	public function testCanBeSanitizedSettingFacebookAdaptContainerWidth(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_adapt_container_width' );
	}

	/**
	 * Sanitize setting_facebook_close_window_after_join
	 */
	public function testCanBeSanitizedSettingFacebookCloseWindowAfterJoin(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_GENERAL, 'setting_facebook_close_window_after_join' );
	}

	/**
	 * Sanitize tracking_use_facebook
	 */
	public function testCanBeSanitizedSettingTrackingUseFacebook(): void {
		$this->sanitizeCheckbox( self::SECTION_FACEBOOK_TRACKING, 'tracking_use_facebook' );
	}

	/**
	 * Sanitize tracking_facebook_subscribe_event
	 */
	public function testCanBeSanitizedSettingTrackingFacebookSubscribeEvent(): void {
		$this->sanitizeText( self::SECTION_FACEBOOK_TRACKING, 'tracking_facebook_subscribe_event' );
	}

	/**
	 * Sanitize tracking_facebook_unsubscribe_event
	 */
	public function testCanBeSanitizedSettingTrackingFacebookUnsubscribeEvent(): void {
		$this->sanitizeText( self::SECTION_FACEBOOK_TRACKING, 'tracking_facebook_unsubscribe_event' );
	}

	/**
	 * Sanitize setting_use_vkontakte
	 */
	public function testCanBeSanitizedSettingUseVkontakte(): void {
		$this->sanitizeCheckbox( self::SECTION_VK_GENERAL, 'setting_use_vkontakte' );
	}

	/**
	 * Sanitize setting_vkontakte_tab_caption
	 */
	public function testCanBeSanitizedSettingVkontakteTabCaption(): void {
		$this->sanitizeText( self::SECTION_VK_GENERAL, 'setting_vkontakte_tab_caption' );
	}

	/**
	 * Sanitize setting_vkontakte_show_description
	 */
	public function testCanBeSanitizedSettingVkontakteShowDescription(): void {
		$this->sanitizeCheckbox( self::SECTION_VK_GENERAL, 'setting_vkontakte_show_description' );
	}

	/**
	 * Sanitize setting_vkontakte_description
	 */
	public function testCanBeSanitizedSettingVkontakteDescription(): void {
		$this->sanitizeKses( self::SECTION_VK_GENERAL, 'setting_vkontakte_description' );
	}

	/**
	 * Sanitize setting_vkontakte_application_id
	 */
	public function testCanBeSanitizedSettingVkontakteApplicationId(): void {
		$this->sanitizeInteger( self::SECTION_VK_GENERAL, 'setting_vkontakte_application_id', PHP_INT_MAX );
	}

	/**
	 * Sanitize setting_vkontakte_page_or_group_id
	 */
	public function testCanBeSanitizedSettingVkontaktePageOrGroupId(): void {
		$this->sanitizeInteger( self::SECTION_VK_GENERAL, 'setting_vkontakte_page_or_group_id', PHP_INT_MAX );
	}

	/**
	 * Sanitize setting_vkontakte_page_url
	 */
	public function testCanBeSanitizedSettingVkontaktePageUrl(): void {
		$this->sanitizeUrl( self::SECTION_VK_GENERAL, 'setting_vkontakte_page_url' );
	}

	/**
	 * Sanitize setting_vkontakte_width
	 */
	public function testCanBeSanitizedSettingVkontakteWidth(): void {
		$this->sanitizeInteger( self::SECTION_VK_GENERAL, 'setting_vkontakte_width', '400' );
	}

	/**
	 * Sanitize setting_vkontakte_height
	 */
	public function testCanBeSanitizedSettingVkontakteHeight(): void {
		$this->sanitizeInteger( self::SECTION_VK_GENERAL, 'setting_vkontakte_height', '400' );
	}

	/**
	 * Sanitize setting_vkontakte_layout
	 */
	public function testCanBeSanitizedSettingVkontakteLayout(): void {
		$this->sanitizeDictionary( self::SECTION_VK_GENERAL, 'setting_vkontakte_layout', '0' );
	}

	/**
	 * Sanitize setting_vkontakte_color_background
	 */
	public function testCanBeSanitizedSettingVkontakteColorBackground(): void {
		$key      = 'setting_vkontakte_color_background';
		$value    = 'qwe';
		$expected = '#000000';

		$result = SMP_Sanitizer::sanitize( self::SECTION_VK_GENERAL, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_vkontakte_color_text
	 */
	public function testCanBeSanitizedSettingVkontakteColorText(): void {
		$key      = 'setting_vkontakte_color_text';
		$value    = 'qwe';
		$expected = '#000000';

		$result = SMP_Sanitizer::sanitize( self::SECTION_VK_GENERAL, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_vkontakte_color_button
	 */
	public function testCanBeSanitizedSettingVkontakteColorButton(): void {
		$key      = 'setting_vkontakte_color_button';
		$value    = 'qwe';
		$expected = '#000000';

		$result = SMP_Sanitizer::sanitize( self::SECTION_VK_GENERAL, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_vkontakte_close_window_after_join
	 */
	public function testCanBeSanitizedSettingVkontakteCloseWindowAfterJoin(): void {
		$this->sanitizeCheckbox( self::SECTION_VK_GENERAL, 'setting_vkontakte_close_window_after_join' );
	}

	/**
	 * Sanitize tracking_use_vkontakte
	 */
	public function testCanBeSanitizedSettingTrackingUseVkontakte(): void {
		$this->sanitizeCheckbox( self::SECTION_VK_TRACKING, 'tracking_use_vkontakte' );
	}

	/**
	 * Sanitize tracking_vkontakte_subscribe_event
	 */
	public function testCanBeSanitizedSettingTrackingVkontakteSubscribeEvent(): void {
		$this->sanitizeText( self::SECTION_VK_TRACKING, 'tracking_vkontakte_subscribe_event' );
	}

	/**
	 * Sanitize tracking_vkontakte_unsubscribe_event
	 */
	public function testCanBeSanitizedSettingTrackingVkontakteUnsubscribeEvent(): void {
		$this->sanitizeText( self::SECTION_VK_TRACKING, 'tracking_vkontakte_unsubscribe_event' );
	}

	/**
	 * Sanitize setting_use_odnoklassniki
	 */
	public function testCanBeSanitizedSettingUseOdnoklassniki(): void {
		$this->sanitizeCheckbox( self::SECTION_OK_GENERAL, 'setting_use_odnoklassniki' );
	}

	/**
	 * Sanitize setting_odnoklassniki_tab_caption
	 */
	public function testCanBeSanitizedSettingOdnoklassnikiTabCaption(): void {
		$this->sanitizeText( self::SECTION_OK_GENERAL, 'setting_odnoklassniki_tab_caption' );
	}

	/**
	 * Sanitize setting_odnoklassniki_show_description
	 */
	public function testCanBeSanitizedSettingOdnoklassnikiShowDescription(): void {
		$this->sanitizeCheckbox( self::SECTION_OK_GENERAL, 'setting_odnoklassniki_show_description' );
	}

	/**
	 * Sanitize setting_odnoklassniki_description
	 */
	public function testCanBeSanitizedSettingOdnoklassnikiDescription(): void {
		$this->sanitizeKses( self::SECTION_OK_GENERAL, 'setting_odnoklassniki_description' );
	}

	/**
	 * Sanitize setting_odnoklassniki_page_or_group_id
	 */
	public function testCanBeSanitizedSettingOdnoklassnikiGroupId(): void {
		$this->sanitizeInteger( self::SECTION_OK_GENERAL, 'setting_odnoklassniki_group_id', PHP_INT_MAX );
	}

	/**
	 * Sanitize setting_odnoklassniki_page_url
	 */
	public function testCanBeSanitizedSettingOdnoklassnikiGroupUrl(): void {
		$this->sanitizeUrl( self::SECTION_OK_GENERAL, 'setting_odnoklassniki_group_url' );
	}

	/**
	 * Sanitize setting_odnoklassniki_width
	 */
	public function testCanBeSanitizedSettingOdnoklassnikiWidth(): void {
		$this->sanitizeInteger( self::SECTION_OK_GENERAL, 'setting_odnoklassniki_width', '400' );
	}

	/**
	 * Sanitize setting_odnoklassniki_height
	 */
	public function testCanBeSanitizedSettingOdnoklassnikiHeight(): void {
		$this->sanitizeInteger( self::SECTION_OK_GENERAL, 'setting_odnoklassniki_height', '400' );
	}

	/**
	 * Sanitize setting_use_twitter
	 */
	public function testCanBeSanitizedSettingUseTwitter(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_GENERAL, 'setting_use_twitter' );
	}

	/**
	 * Sanitize setting_twitter_tab_caption
	 */
	public function testCanBeSanitizedSettingTwitterTabCaption(): void {
		$this->sanitizeText( self::SECTION_TWITTER_GENERAL, 'setting_twitter_tab_caption' );
	}

	/**
	 * Sanitize setting_twitter_show_description
	 */
	public function testCanBeSanitizedSettingTwitterShowDescription(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_GENERAL, 'setting_twitter_show_description' );
	}

	/**
	 * Sanitize setting_twitter_description
	 */
	public function testCanBeSanitizedSettingTwitterDescription(): void {
		$this->sanitizeKses( self::SECTION_TWITTER_GENERAL, 'setting_twitter_description' );
	}

	/**
	 * Sanitize setting_twitter_username
	 */
	public function testCanBeSanitizedSettingTwitterUsername(): void {
		$key      = 'setting_twitter_username';
		$value    = '@gruz0-Я';
		$expected = 'gruz0';

		$result = SMP_Sanitizer::sanitize( self::SECTION_TWITTER_GENERAL, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_twitter_locale
	 */
	public function testCanBeSanitizedSettingTwitterLocale(): void {
		$this->sanitizeDictionary( self::SECTION_TWITTER_GENERAL, 'setting_twitter_locale', 'en' );
	}

	/**
	 * Sanitize setting_twitter_first_widget
	 */
	public function testCanBeSanitizedSettingTwitterFirstWidget(): void {
		$this->sanitizeDictionary( self::SECTION_TWITTER_GENERAL, 'setting_twitter_first_widget', 'follow_button' );
	}

	/**
	 * Sanitize setting_twitter_close_window_after_join
	 */
	public function testCanBeSanitizedSettingTwitterCloseWindowAfterJoin(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_GENERAL, 'setting_twitter_close_window_after_join' );
	}

	/**
	 * Sanitize setting_twitter_use_follow_button
	 */
	public function testCanBeSanitizedSettingTwitterUseFollowButton(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_FOLLOW_BUTTON, 'setting_twitter_use_follow_button' );
	}

	/**
	 * Sanitize setting_twitter_show_count
	 */
	public function testCanBeSanitizedSettingTwitterShowCount(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_FOLLOW_BUTTON, 'setting_twitter_show_count' );
	}

	/**
	 * Sanitize setting_twitter_show_screen_name
	 */
	public function testCanBeSanitizedSettingTwitterShowScreenName(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_FOLLOW_BUTTON, 'setting_twitter_show_screen_name' );
	}

	/**
	 * Sanitize setting_twitter_follow_button_large_size
	 */
	public function testCanBeSanitizedSettingTwitterFollowButtonLargeSize(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_FOLLOW_BUTTON, 'setting_twitter_follow_button_large_size' );
	}

	/**
	 * Sanitize setting_twitter_follow_button_align_by
	 */
	public function testCanBeSanitizedSettingTwitterFollowButtonAlignBy(): void {
		$this->sanitizeDictionary( self::SECTION_TWITTER_FOLLOW_BUTTON, 'setting_twitter_follow_button_align_by', 'left' );
	}

	/**
	 * Sanitize setting_twitter_use_timeline
	 */
	public function testCanBeSanitizedSettingTwitterUseTimeline(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_TIMELINE, 'setting_twitter_use_timeline' );
	}

	/**
	 * Sanitize setting_twitter_theme
	 */
	public function testCanBeSanitizedSettingTwitterTheme(): void {
		$this->sanitizeDictionary( self::SECTION_TWITTER_TIMELINE, 'setting_twitter_theme', 'light' );
	}

	/**
	 * Sanitize setting_twitter_link_color
	 */
	public function testCanBeSanitizedSettingTwitterLinkColor(): void {
		$key      = 'setting_twitter_link_color';
		$value    = 'qwe';
		$expected = '#000000';

		$result = SMP_Sanitizer::sanitize( self::SECTION_TWITTER_TIMELINE, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_twitter_tweet_limit
	 */
	public function testCanBeSanitizedSettingTwitterTweetLimit(): void {
		$this->sanitizeInteger( self::SECTION_TWITTER_TIMELINE, 'setting_twitter_tweet_limit', '5' );
	}

	/**
	 * Sanitize setting_twitter_show_replies
	 */
	public function testCanBeSanitizedSettingTwitterShowReplies(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_TIMELINE, 'setting_twitter_show_replies' );
	}

	/**
	 * Sanitize setting_twitter_width
	 */
	public function testCanBeSanitizedSettingTwitterWidth(): void {
		$this->sanitizeInteger( self::SECTION_TWITTER_TIMELINE, 'setting_twitter_width', '400' );
	}

	/**
	 * Sanitize setting_twitter_height
	 */
	public function testCanBeSanitizedSettingTwitterHeight(): void {
		$this->sanitizeInteger( self::SECTION_TWITTER_TIMELINE, 'setting_twitter_height', '400' );
	}

	/**
	 * Sanitize setting_twitter_chrome
	 */
	public function testCanBeSanitizedSettingTwitterChrome(): void {
		$values = array(
			'setting_twitter_chrome' => ' noheader, nofooter ,noborders ,noscrollbars   , transparent',
		);

		$result = SMP_Sanitizer::sanitize( self::SECTION_TWITTER_TIMELINE, $values );
		$this->assertEquals( 'noheader,nofooter,noborders,noscrollbars,transparent', $result['setting_twitter_chrome'] );
	}

	/**
	 * Sanitize tracking_use_twitter
	 */
	public function testCanBeSanitizedSettingTrackingUseTwitter(): void {
		$this->sanitizeCheckbox( self::SECTION_TWITTER_TRACKING, 'tracking_use_twitter' );
	}

	/**
	 * Sanitize tracking_twitter_subscribe_event
	 */
	public function testCanBeSanitizedSettingTrackingTwitterEvent(): void {
		$this->sanitizeText( self::SECTION_TWITTER_TRACKING, 'tracking_twitter_event' );
	}

	/**
	 * Sanitize setting_use_pinterest
	 */
	public function testCanBeSanitizedSettingUsePinterest(): void {
		$this->sanitizeCheckbox( self::SECTION_PINTEREST_GENERAL, 'setting_use_pinterest' );
	}

	/**
	 * Sanitize setting_pinterest_tab_caption
	 */
	public function testCanBeSanitizedSettingPinterestTabCaption(): void {
		$this->sanitizeText( self::SECTION_PINTEREST_GENERAL, 'setting_pinterest_tab_caption' );
	}

	/**
	 * Sanitize setting_pinterest_show_description
	 */
	public function testCanBeSanitizedSettingPinterestShowDescription(): void {
		$this->sanitizeCheckbox( self::SECTION_PINTEREST_GENERAL, 'setting_pinterest_show_description' );
	}

	/**
	 * Sanitize setting_pinterest_description
	 */
	public function testCanBeSanitizedSettingPinterestDescription(): void {
		$this->sanitizeKses( self::SECTION_PINTEREST_GENERAL, 'setting_pinterest_description' );
	}

	/**
	 * Sanitize setting_pinterest_profile_url
	 */
	public function testCanBeSanitizedSettingPinterestProfileUrl(): void {
		$this->sanitizeUrl( self::SECTION_PINTEREST_GENERAL, 'setting_pinterest_profile_url' );
	}

	/**
	 * Sanitize setting_pinterest_image_width
	 */
	public function testCanBeSanitizedSettingPinterestImageWidth(): void {
		$this->sanitizeInteger( self::SECTION_PINTEREST_GENERAL, 'setting_pinterest_image_width', '400' );
	}

	/**
	 * Sanitize setting_pinterest_width
	 */
	public function testCanBeSanitizedSettingPinterestWidth(): void {
		$this->sanitizeInteger( self::SECTION_PINTEREST_GENERAL, 'setting_pinterest_width', '400' );
	}

	/**
	 * Sanitize setting_pinterest_height
	 */
	public function testCanBeSanitizedSettingPinterestHeight(): void {
		$this->sanitizeInteger( self::SECTION_PINTEREST_GENERAL, 'setting_pinterest_height', '400' );
	}

	/**
	 * Checks if checkbox is not checked then it will return `1` otherwise `0`
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 */
	private function sanitizeCheckbox( $section, $option_name ) {
		$value  = rand( 2, 99 );
		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => $value ) );
		$this->assertEquals( 1, $result[ $option_name ] );
	}

	/**
	 * Sanitize option's integer value
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 * @param string $value       Value
	 */
	private function sanitizeInteger( $section, $option_name, $value ) {
		// Is should converts to a positive value
		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => $value ) );
		$this->assertEquals( absint( $value ), $result[ $option_name ] );

		// Check for a value below zero
		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => -absint( $value ) ) );
		$this->assertEquals( absint( $value ), $result[ $option_name ] );

		// Check for incorrect value
		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => 'abc' ) );
		$this->assertEquals( 0, $result[ $option_name ] );
	}

	/**
	 * Sanitize option's text value from HTML tags
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 */
	private function sanitizeText( $section, $option_name ): void {
		$value       = 'TextBox Value';
		$dirty_value = "<b><script></script>${value}</b>";

		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => $dirty_value ) );
		$this->assertEquals( $value, $result[ $option_name ] );
	}

	/**
	 * Sanitize option's text value with wp_kses_post function
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 */
	private function sanitizeKses( $section, $option_name ): void {
		$value    = 'Title<script>alert("qwe");</script>';
		$expected = 'Titlealert("qwe");';

		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => $value ) );
		$this->assertEquals( $expected, $result[ $option_name ] );
	}

	/**
	 * Sanitize option's text value with esc_url function
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 */
	private function sanitizeUrl( $section, $option_name ): void {
		$value    = 'example.com/page';
		$expected = 'http://example.com/page';

		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => $value ) );
		$this->assertEquals( $expected, $result[ $option_name ] );
	}

	/**
	 * Sanitize dictionary
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 * @param string $expected    Expected value
	 */
	private function sanitizeDictionary( $section, $option_name, $expected ): void {
		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => 'qwe' ) );
		$this->assertEquals( $expected, $result[ $option_name ] );
	}
}
