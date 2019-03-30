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
	const SECTION_COMMON_GENERAL          = SMP_PREFIX . '-section-common';
	const SECTION_COMMON_VIEW_DESKTOP     = SMP_PREFIX . '-section-common-view';
	const SECTION_COMMON_VIEW_MOBILE      = SMP_PREFIX . '-section-common-view-mobile';
	const SECTION_COMMON_EVENTS_GENERAL   = SMP_PREFIX . '-section-common-events-general';
	const SECTION_COMMON_EVENTS_WHO       = SMP_PREFIX . '-section-common-events-who';
	const SECTION_COMMON_TRACKING_GENERAL = SMP_PREFIX . '-section-common-tracking-general';

	/**
	 * Set default options
	 */
	public function setUp() {
		SMP_Options::set_default_options();
	}

	/**
	 * Checks if checkbox is not checked then it will return `1` otherwise `0`
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 */
	public function sanitizeCheckbox( $section, $option_name ) {
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
	public function sanitizeInteger( $section, $option_name, $value ) {
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
		$key      = 'setting_plugin_title';
		$value    = 'Title<script>alert("qwe");</script>';
		$expected = 'Titlealert("qwe");';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
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
		$key      = 'setting_animation_style';
		$value    = 'qwe';
		$expected = 'bounce';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
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
		$key      = 'setting_icons_size_on_desktop';
		$value    = 'qwe';
		$expected = 'lg';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
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
		$key      = 'setting_show_close_button_in';
		$value    = 'qwe';
		$expected = 'inside';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
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
		$key      = 'setting_button_to_close_widget_title';
		$value    = "<b><script></script>Please, don't show me again!</b>";
		$expected = "Please, don't show me again!";

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_button_to_close_widget_style
	 */
	public function testCanBeSanitizedSettingButtonToCloseWidgetStyle(): void {
		$key      = 'setting_button_to_close_widget_style';
		$value    = 'qwe';
		$expected = 'link';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_DESKTOP, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
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
		$key      = 'setting_plugin_title_on_mobile_devices';
		$value    = 'Title<script>alert("qwe");</script>';
		$expected = 'Titlealert("qwe");';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_MOBILE, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
	}

	/**
	 * Sanitize setting_icons_size_on_mobile_devices
	 */
	public function testCanBeSanitizedSettingIconsSizeOnMobileDevices(): void {
		$key      = 'setting_icons_size_on_mobile_devices';
		$value    = 'qwe';
		$expected = 'lg';

		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_VIEW_MOBILE, array( $key => $value ) );
		$this->assertEquals( $expected, $result[ $key ] );
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
			'popup_will_appear_after_clicking_on_element' => ' #my-button, .entry .button йцу123',
			'event_hide_element_after_click_on_it'        => 2,
			'do_not_use_cookies_after_click_on_element'   => 3,
		);
		$result = SMP_Sanitizer::sanitize( self::SECTION_COMMON_EVENTS_GENERAL, $values );
		$this->assertEquals( '#my-button, .entry .button 123', $result['popup_will_appear_after_clicking_on_element'] );
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
}
