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
	const SECTION_COMMON_GENERAL = SMP_PREFIX . '-section-common';

	/**
	 * Checks if checkbox is not checked then it will return `1` otherwise `0`
	 *
	 * @param string $section     Section
	 * @param string $option_name Option name
	 */
	public function sanitizeCheckbox( $section, $option_name ) {
		$value    = rand( 2, 99 );
		$expected = 1;
		$result   = SMP_Sanitizer::sanitize( self::SECTION_COMMON_GENERAL, array( $option_name => $value ) );
		$this->assertEquals( 1, $result[ $option_name ] );
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
	 * Sanitize General > Desktop View section
	 */
	public function testCanBeSanitizedSectionCommonDesktop(): void {
		$values = array(
			'setting_plugin_title'                        => 'Title<script>alert("qwe");</script>',
			'setting_use_animation'                       => 2,
			'setting_animation_style'                     => 'qwe',
			'setting_use_icons_instead_of_labels_in_tabs' => 2,
			'setting_icons_size_on_desktop'               => 'qwe',
			'setting_hide_tabs_if_one_widget_is_active'   => 2,
			'setting_container_width'                     => '400',
			'setting_container_height'                    => '400',
			'setting_border_radius'                       => '30',
			'setting_show_close_button_in'                => 'qwe',
			'setting_show_button_to_close_widget'         => 2,
			'setting_button_to_close_widget_title'        => "Please, don't show me again!",
			'setting_button_to_close_widget_style'        => 'qwe',
			'setting_delay_before_show_bottom_button'     => '3',
			'setting_overlay_color'                       => 'qwe',
			'setting_overlay_opacity'                     => 105,
			'setting_align_tabs_to_center'                => 2,
			'setting_background_image'                    => 'localhost/image.png',
		);

		$result = SMP_Sanitizer::sanitize( SMP_PREFIX . '-section-common-view', $values );

		$this->assertEquals( 'Titlealert("qwe");', $result['setting_plugin_title'] );
		$this->assertEquals( 1, $result['setting_use_animation'] );
		$this->assertEquals( 'bounce', $result['setting_animation_style'] );
		$this->assertEquals( 1, $result['setting_use_icons_instead_of_labels_in_tabs'] );
		$this->assertEquals( 'lg', $result['setting_icons_size_on_desktop'] );
		$this->assertEquals( 1, $result['setting_hide_tabs_if_one_widget_is_active'] );
		$this->assertEquals( 400, $result['setting_container_width'] );
		$this->assertEquals( 400, $result['setting_container_height'] );
		$this->assertEquals( 30, $result['setting_border_radius'] );
		$this->assertEquals( 'inside', $result['setting_show_close_button_in'] );
		$this->assertEquals( 1, $result['setting_show_button_to_close_widget'] );
		$this->assertEquals( "Please, don't show me again!", $result['setting_button_to_close_widget_title'] );
		$this->assertEquals( 'link', $result['setting_button_to_close_widget_style'] );
		$this->assertEquals( 3, $result['setting_delay_before_show_bottom_button'] );
		$this->assertEquals( '#000000', $result['setting_overlay_color'] );
		$this->assertEquals( 80, $result['setting_overlay_opacity'] );
		$this->assertEquals( 1, $result['setting_align_tabs_to_center'] );
		$this->assertEquals( '', $result['setting_background_image'] );
	}

	/**
	 * Sanitize General > Mobile View section
	 */
	public function testCanBeSanitizedSectionCommonMobile(): void {
		$values = array(
			'setting_plugin_title_on_mobile_devices' => 'Title<script>alert("qwe");</script>',
			'setting_icons_size_on_mobile_devices'   => 'qwe',
		);

		$result = SMP_Sanitizer::sanitize( SMP_PREFIX . '-section-common-view-mobile', $values );

		$this->assertEquals( 'Titlealert("qwe");', $result['setting_plugin_title_on_mobile_devices'] );
		$this->assertEquals( 'lg', $result['setting_icons_size_on_mobile_devices'] );
	}
}
