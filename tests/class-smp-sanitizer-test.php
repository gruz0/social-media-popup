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
	const SECTION_COMMON_GENERAL      = SMP_PREFIX . '-section-common';
	const SECTION_COMMON_VIEW_DESKTOP = SMP_PREFIX . '-section-common-view';
	const SECTION_COMMON_VIEW_MOBILE  = SMP_PREFIX . '-section-common-view-mobile';

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
		$result = SMP_Sanitizer::sanitize( $section, array( $option_name => $value ) );
		$this->assertEquals( absint( $value ), $result[ $option_name ] );
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
}
