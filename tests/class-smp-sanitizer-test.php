<?php
// :nodoc
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

include '../../../wp-load.php';

/**
 * SMP_Sanitizer Test
 */
final class SMP_Sanitizer_Test extends TestCase {
	/**
	 * Sanitize General > Common section
	 */
	public function testCanBeSanitizedSectionCommon(): void {
		$values = array(
			'setting_debug_mode'                       => 2,
			'setting_tabs_order'                       => "  vkontakte,facebook\"<a href='' />\", gooGleplus, twitter, ODNOklassniki , vk , fac3book, twitter",
			'setting_close_popup_by_clicking_anywhere' => 3,
			'setting_close_popup_when_esc_pressed'     => 4,
			'setting_show_on_mobile_devices'           => 5,
			'setting_show_admin_bar_menu'              => 6,
		);

		$result = SMP_Sanitizer::sanitize( SMP_PREFIX . '-section-common', $values );

		$this->assertEquals( 1, $result['setting_debug_mode'] );
		$this->assertEquals( 'vkontakte,googleplus,twitter,odnoklassniki', $result['setting_tabs_order'] );
		$this->assertEquals( 1, $result['setting_close_popup_by_clicking_anywhere'] );
		$this->assertEquals( 1, $result['setting_close_popup_when_esc_pressed'] );
		$this->assertEquals( 1, $result['setting_show_on_mobile_devices'] );
		$this->assertEquals( 1, $result['setting_show_admin_bar_menu'] );
	}
}
