<?php
/**
 * Social Media Popup tests
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

/**
 * Social_Media_Popup_Tests
 */
class Social_Media_Popup_Tests extends PHPUnit_Framework_TestCase {
	/**
	 * Plugin instance
	 *
	 * @var Social_Media_Popup $plugin
	 */
	protected static $plugin;

	/**
	 * Set up plugin
	 */
	function setUp() {
		parent::setUp();
	}

	/**
	 * Setup before
	 */
	public static function setUpBeforeClass() {
		self::$plugin = $GLOBALS['social-media-popup'];
	}

	/**
	 * Check that that activation doesn't break
	 */
	function testPluginActivated() {
		$this->assertTrue( is_plugin_active( PLUGIN_PATH ) );
	}

	/**
	 * Check that plugin initialized
	 */
	function testPluginInitialization() {
		$this->assertFalse( null == self::$plugin );
	}

	/**
	 * Tests
	 */
	function testUpgrade() {
		$this->upgradeTo01();
		$this->upgradeTo02();
		$this->upgradeTo03();
		$this->upgradeTo04();
		$this->upgradeTo05();
		$this->upgradeTo06();
		$this->upgradeTo061();
		$this->upgradeTo062();
		$this->upgradeTo063();
		$this->upgradeTo064();
		$this->upgradeTo065();
		$this->upgradeTo066();
		$this->upgradeTo067();
		$this->upgradeTo068();
		$this->upgradeTo069();
		$this->upgradeTo070();
		$this->upgradeTo071();
		$this->upgradeTo072();
		$this->upgradeTo073();
		$this->upgradeTo074();
		$this->upgradeTo075();
		$this->checkDebugModeIsOn();
	}

	/**
	 * Upgrade to 0.1
	 */
	private function upgradeTo01() {
		self::$plugin->upgrade_to_0_1();

		$this->assertTrue( '0.1' === get_option( self::$plugin->get_scp_prefix() . 'version' ) );
	}

	/**
	 * Upgrade to 0.2
	 */
	private function upgradeTo02() {
		self::$plugin->upgrade_to_0_2();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.2' === get_option( $scp_prefix . 'version' ) );

		// Common
		$this->assertTrue( 30 === get_option( $scp_prefix . 'setting_display_after_n_days' ) );
		$this->assertTrue( 0  === get_option( $scp_prefix . 'setting_display_after_visiting_n_pages' ) );
		$this->assertTrue( 3  === get_option( $scp_prefix . 'setting_display_after_delay_of_n_seconds' ) );

		// Facebook
		$this->assertTrue( __( 'Facebook', L10N_SCP_PREFIX )          === get_option( $scp_prefix . 'setting_facebook_tab_caption' ) );
		$this->assertTrue( '277165072394537'                          === get_option( $scp_prefix . 'setting_facebook_application_id' ) );
		$this->assertTrue( 'https://www.facebook.com/AlexanderGruzov' === get_option( $scp_prefix . 'setting_facebook_page_url' ) );
		$this->assertTrue( 'ru_RU'                                    === get_option( $scp_prefix . 'setting_facebook_locale' ) );
		$this->assertTrue( 400                                        === get_option( $scp_prefix . 'setting_facebook_width' ) );
		$this->assertTrue( 300                                        === get_option( $scp_prefix . 'setting_facebook_height' ) );
		$this->assertTrue( 1                                          === get_option( $scp_prefix . 'setting_facebook_show_faces' ) );

		// VK
		$this->assertTrue( __( 'VK', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'setting_vkontakte_tab_caption' ) );
		$this->assertTrue( '64088617'                  === get_option( $scp_prefix . 'setting_vkontakte_page_or_group_id' ) );
		$this->assertTrue( 400                         === get_option( $scp_prefix . 'setting_vkontakte_width' ) );
		$this->assertTrue( 400                         === get_option( $scp_prefix . 'setting_vkontakte_height' ) );
		$this->assertTrue( '#FFFFFF'                   === get_option( $scp_prefix . 'setting_vkontakte_color_background' ) );
		$this->assertTrue( '#2B587A'                   === get_option( $scp_prefix . 'setting_vkontakte_color_text' ) );
		$this->assertTrue( '#5B7FA6'                   === get_option( $scp_prefix . 'setting_vkontakte_color_button' ) );

		// Odnoklassniki
		$this->assertTrue( __( 'Odnoklassniki', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'setting_odnoklassniki_tab_caption' ) );
		$this->assertTrue( '57122812461115'                       === get_option( $scp_prefix . 'setting_odnoklassniki_group_id' ) );
		$this->assertTrue( 400                                    === get_option( $scp_prefix . 'setting_odnoklassniki_width' ) );
		$this->assertTrue( 260                                    === get_option( $scp_prefix . 'setting_odnoklassniki_height' ) );
	}

	/**
	 * Upgrade to 0.3
	 */
	private function upgradeTo03() {
		self::$plugin->upgrade_to_0_3();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.3'                              === get_option( $scp_prefix . 'version' ) );
		$this->assertTrue( 'vkontakte,facebook,odnoklassniki' === get_option( $scp_prefix . 'setting_tabs_order' ) );
	}

	/**
	 * Upgrade to 0.4
	 */
	private function upgradeTo04() {
		self::$plugin->upgrade_to_0_4();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.4' === get_option( $scp_prefix . 'version' ) );
	}

	/**
	 * Upgrade to 0.5
	 */
	private function upgradeTo05() {
		self::$plugin->upgrade_to_0_5();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.5' === get_option( $scp_prefix . 'version' ) );

		// Common
		$this->assertTrue( 'vkontakte,facebook,odnoklassniki,googleplus' === get_option( $scp_prefix . 'setting_tabs_order' ) );
		$this->assertTrue( 1                                             === get_option( $scp_prefix . 'setting_debug_mode' ) );
		$this->assertTrue( 400                                           === get_option( $scp_prefix . 'setting_container_width' ) );
		$this->assertTrue( 480                                           === get_option( $scp_prefix . 'setting_container_height' ) );

		// Google+
		$this->assertTrue( 0                                             === get_option( $scp_prefix . 'setting_use_googleplus' ) );
		$this->assertTrue( __( 'Google+', L10N_SCP_PREFIX )              === get_option( $scp_prefix . 'setting_googleplus_tab_caption' ) );
		$this->assertTrue( 0                                             === get_option( $scp_prefix . 'setting_googleplus_show_description' ) );
		$this->assertTrue( ''                                            === get_option( $scp_prefix . 'setting_googleplus_description' ) );
		$this->assertTrue( '//plus.google.com/u/0/117676776729232885815' === get_option( $scp_prefix . 'setting_googleplus_page_url' ) );
		$this->assertTrue( 'ru'                                          === get_option( $scp_prefix . 'setting_googleplus_locale' ) );
		$this->assertTrue( 400                                           === get_option( $scp_prefix . 'setting_googleplus_size' ) );
		$this->assertTrue( 'light'                                       === get_option( $scp_prefix . 'setting_googleplus_theme' ) );
		$this->assertTrue( 1                                             === get_option( $scp_prefix . 'setting_googleplus_show_cover_photo' ) );
		$this->assertTrue( 1                                             === get_option( $scp_prefix . 'setting_googleplus_show_tagline' ) );

		// Facebook, VK, Odnoklassniki
		$this->assertTrue( 400 === get_option( $scp_prefix . 'setting_facebook_height' ) );
		$this->assertTrue( 400 === get_option( $scp_prefix . 'setting_vkontakte_height' ) );
		$this->assertTrue( 400 === get_option( $scp_prefix . 'setting_odnoklassniki_height' ) );
	}

	/**
	 * Upgrade to 0.6
	 */
	private function upgradeTo06() {
		self::$plugin->upgrade_to_0_6();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6' === get_option( $scp_prefix . 'version' ) );

		// Common
		$this->assertTrue( 'vkontakte,facebook,odnoklassniki,googleplus,twitter' === get_option( $scp_prefix . 'setting_tabs_order' ) );

		// Twitter
		$this->assertTrue( 0                                === get_option( $scp_prefix . 'setting_use_twitter' ) );
		$this->assertTrue( __( 'Twitter', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'setting_twitter_tab_caption' ) );
		$this->assertTrue( 0                                === get_option( $scp_prefix . 'setting_twitter_show_description' ) );
		$this->assertTrue( ''                               === get_option( $scp_prefix . 'setting_twitter_description' ) );
		$this->assertTrue( ''                               === get_option( $scp_prefix . 'setting_twitter_username' ) );
		$this->assertTrue( ''                               === get_option( $scp_prefix . 'setting_twitter_widget_id' ) );
		$this->assertTrue( 'light'                          === get_option( $scp_prefix . 'setting_twitter_theme' ) );
		$this->assertTrue( '#CC0000'                        === get_option( $scp_prefix . 'setting_twitter_link_color' ) );
		$this->assertTrue( 5                                === get_option( $scp_prefix . 'setting_twitter_tweet_limit' ) );
		$this->assertTrue( 0                                === get_option( $scp_prefix . 'setting_twitter_show_replies' ) );
		$this->assertTrue( 400                              === get_option( $scp_prefix . 'setting_twitter_width' ) );
		$this->assertTrue( 400                              === get_option( $scp_prefix . 'setting_twitter_height' ) );
		$this->assertTrue( ''                               === get_option( $scp_prefix . 'setting_twitter_chrome' ) );
	}

	/**
	 * Upgrade to 0.6.1
	 */
	private function upgradeTo061() {
		self::$plugin->upgrade_to_0_6_1();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.1' === get_option( $scp_prefix . 'version' ) );
		$this->assertTrue( 10      === get_option( $scp_prefix . 'setting_border_radius' ) );
	}

	/**
	 * Upgrade to 0.6.2
	 */
	private function upgradeTo062() {
		self::$plugin->upgrade_to_0_6_2();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.2' === get_option( $scp_prefix . 'version' ) );
		$this->assertTrue( 0       === get_option( $scp_prefix . 'setting_close_popup_by_clicking_anywhere' ) );
		$this->assertTrue( 0       === get_option( $scp_prefix . 'setting_show_on_mobile_devices' ) );
	}

	/**
	 * Upgrade to 0.6.3
	 */
	private function upgradeTo063() {
		$scp_prefix = self::$plugin->get_scp_prefix();

		// Предварительно сохраним значения тех полей, которые надо будет проверять при обновлении новых полей
		$facebook_show_header = get_option( $scp_prefix . 'setting_facebook_show_header' );
		$facebook_show_faces  = get_option( $scp_prefix . 'setting_facebook_show_faces' );
		$facebook_show_stream = get_option( $scp_prefix . 'setting_facebook_show_stream' );

		self::$plugin->upgrade_to_0_6_3();

		$this->assertTrue( '0.6.3' === get_option( $scp_prefix . 'version' ) );

		// Facebook
		$this->assertTrue( null == get_option( $scp_prefix . 'setting_facebook_show_header' ) );
		$this->assertTrue( ( $facebook_show_header ? '1' : '' ) === get_option( $scp_prefix . 'setting_facebook_hide_cover' ) );

		$this->assertTrue( null == get_option( $scp_prefix . 'setting_facebook_show_faces' ) );
		$this->assertTrue( $facebook_show_faces === get_option( $scp_prefix . 'setting_facebook_show_facepile' ) );

		$this->assertTrue( null == get_option( $scp_prefix . 'setting_facebook_show_stream' ) );
		$this->assertTrue( $facebook_show_stream === get_option( $scp_prefix . 'setting_facebook_show_posts' ) );
	}

	/**
	 * Upgrade to 0.6.4
	 */
	private function upgradeTo064() {
		self::$plugin->upgrade_to_0_6_4();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.4' === get_option( $scp_prefix . 'version' ) );
	}

	/**
	 * Upgrade to 0.6.5
	 */
	private function upgradeTo065() {
		self::$plugin->upgrade_to_0_6_5();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.5' === get_option( $scp_prefix . 'version' ) );
		$this->assertTrue( 'person' === get_option( $scp_prefix . 'setting_googleplus_page_type' ) );
	}

	/**
	 * Upgrade to 0.6.6
	 */
	private function upgradeTo066() {
		self::$plugin->upgrade_to_0_6_6();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.6' === get_option( $scp_prefix . 'version' ) );
		$this->assertTrue( 0       === get_option( $scp_prefix . 'setting_close_popup_when_esc_pressed' ) );
		$this->assertTrue( 500     === get_option( $scp_prefix . 'setting_vkontakte_delay_before_render' ) );
	}

	/**
	 * Upgrade to 0.6.7
	 */
	private function upgradeTo067() {
		self::$plugin->upgrade_to_0_6_7();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.7' === get_option( $scp_prefix . 'version' ) );

		$this->assertTrue(
			'<div style="text-align: center;font: bold normal 14pt/16pt Arial">'
			. __( 'Follow Us on Social Media!', L10N_SCP_PREFIX )
			. '</div>' === get_option( $scp_prefix . 'setting_plugin_title' )
		);

		$this->assertTrue( 1                                                            === get_option( $scp_prefix . 'setting_hide_tabs_if_one_widget_is_active' ) );
		$this->assertTrue( 1                                                            === get_option( $scp_prefix . 'setting_show_button_to_close_widget' ) );
		$this->assertTrue( __( "Thanks! Please don't show me popup.", L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'setting_button_to_close_widget_title' ) );
		$this->assertTrue( 'link'                                                       === get_option( $scp_prefix . 'setting_button_to_close_widget_style' ) );
	}

	/**
	 * Upgrade to 0.6.8
	 */
	private function upgradeTo068() {
		self::$plugin->upgrade_to_0_6_8();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.8' === get_option( $scp_prefix . 'version' ) );

		// Common
		$this->assertTrue( 'vkontakte,facebook,odnoklassniki,googleplus,twitter,pinterest' === get_option( $scp_prefix . 'setting_tabs_order' ) );

		// Pinterest
		$this->assertTrue( 0                                  === get_option( $scp_prefix . 'setting_use_pinterest' ) );
		$this->assertTrue( __( 'Pinterest', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'setting_pinterest_tab_caption' ) );
		$this->assertTrue( 0                                  === get_option( $scp_prefix . 'setting_pinterest_show_description' ) );
		$this->assertTrue( ''                                 === get_option( $scp_prefix . 'setting_pinterest_description' ) );
		$this->assertTrue( 'http://ru.pinterest.com/gruz0/'   === get_option( $scp_prefix . 'setting_pinterest_profile_url' ) );
		$this->assertTrue( 60                                 === get_option( $scp_prefix . 'setting_pinterest_image_width' ) );
		$this->assertTrue( 380                                === get_option( $scp_prefix . 'setting_pinterest_width' ) );
		$this->assertTrue( 300                                === get_option( $scp_prefix . 'setting_pinterest_height' ) );
	}

	/**
	 * Upgrade to 0.6.9
	 */
	private function upgradeTo069() {
		self::$plugin->upgrade_to_0_6_9();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.6.9' === get_option( $scp_prefix . 'version' ) );

		// Common
		$this->assertTrue( '#000000' === get_option( $scp_prefix . 'setting_overlay_color' ) );
		$this->assertTrue( 80        === get_option( $scp_prefix . 'setting_overlay_opacity' ) );
		$this->assertTrue( 'inside'  === get_option( $scp_prefix . 'setting_show_close_button_in' ) );
		$this->assertTrue( 0         === get_option( $scp_prefix . 'setting_align_tabs_to_center' ) );
		$this->assertTrue( 0         === get_option( $scp_prefix . 'setting_delay_before_show_bottom_button' ) );
		$this->assertTrue( ''        === get_option( $scp_prefix . 'setting_background_image' ) );
	}

	/**
	 * Upgrade to 0.7.0
	 */
	private function upgradeTo070() {
		$scp_prefix = self::$plugin->get_scp_prefix();

		// Предварительно сохраним значения тех полей, которые надо будет проверять при обновлении новых полей
		$display_after_delay_of_n_seconds = get_option( $scp_prefix . 'setting_display_after_delay_of_n_seconds' );

		self::$plugin->upgrade_to_0_7_0();

		$this->assertTrue( '0.7.0' === get_option( $scp_prefix . 'version' ) );

		// Common
		$this->assertTrue( '' === get_option( $scp_prefix . 'when_should_the_popup_appear' ) );
		$this->assertTrue( '' === get_option( $scp_prefix . 'popup_will_appear_after_clicking_on_element' ) );

		$this->assertTrue( null == get_option( $scp_prefix . 'setting_display_after_delay_of_n_seconds' ) );
		$this->assertTrue( $display_after_delay_of_n_seconds === get_option( $scp_prefix . 'popup_will_appear_after_n_seconds' ) );
	}

	/**
	 * Upgrade to 0.7.1
	 */
	private function upgradeTo071() {
		$old_scp_prefix = self::$plugin->get_scp_prefix();

		// Предварительно сохраним значения тех полей, которые надо будет проверять при обновлении новых полей
		$display_after_visiting_n_pages = get_option( $old_scp_prefix . 'setting_display_after_visiting_n_pages' );

		self::$plugin->upgrade_to_0_7_1();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.7.1' === get_option( $scp_prefix . 'version' ) );

		// Загрузим все существующие опции и найдём только те, которые уже с новым префиксом плагина
		$scp_options = array();
		$all_options = wp_load_alloptions();
		foreach ( $all_options as $name => $value ) {
			if ( preg_match( '/^' . $scp_prefix . '/', $name ) ) $scp_options[ $name ] = $value;
		}

		// Проверим, что все опции со старым префиксом не существуют
		foreach ( $scp_options as $option_name => $value ) {
			$old_option_name = preg_replace( '/^' . $scp_prefix . '/', '', $option_name );
			$this->assertTrue( null == get_option( $old_scp_prefix . $old_option_name ) );
		}

		$this->assertTrue( null == get_option( $old_scp_prefix . 'popup_will_appear_after_clicking_on_eleme' ) );
		$this->assertTrue( null == get_option( $scp_prefix . 'popup_will_appear_after_clicking_on_eleme' ) );

		$this->assertTrue( '' === get_option( $scp_prefix . 'popup_will_appear_after_clicking_on_element' ) );
		$this->assertTrue( '70' === get_option( $scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent' ) );
		$this->assertTrue( 0  === get_option( $scp_prefix . 'popup_will_appear_on_exit_intent' ) );
		$this->assertTrue( '' === get_option( $scp_prefix . 'who_should_see_the_popup' ) );

		$this->assertTrue( null == get_option( $old_scp_prefix . 'setting_display_after_visiting_n_pages' ) );
		$this->assertTrue( $display_after_visiting_n_pages === get_option( $scp_prefix . 'visitor_opened_at_least_n_number_of_pages' ) );
	}

	/**
	 * Upgrade to 0.7.2
	 */
	private function upgradeTo072() {
		$old_scp_prefix = self::$plugin->get_scp_prefix();

		// Предварительно сохраним значения тех полей, которые надо будет проверять при обновлении новых полей
		$facebook_show_posts = get_option( $old_scp_prefix . 'setting_facebook_show_posts' );

		self::$plugin->upgrade_to_0_7_2();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.7.2' === get_option( $scp_prefix . 'version' ) );

		// Facebook
		$this->assertTrue( 1    === get_option( $scp_prefix . 'setting_facebook_adapt_container_width' ) );
		$this->assertTrue( 0    === get_option( $scp_prefix . 'setting_facebook_use_small_header' ) );
		$this->assertTrue( null ==  get_option( $scp_prefix . 'setting_facebook_show_posts' ) );
		$this->assertTrue( ( $facebook_show_posts === '1' ? 'timeline' : '' ) === get_option( $scp_prefix . 'setting_facebook_tabs' ) );
	}

	/**
	 * Upgrade to 0.7.3
	 */
	private function upgradeTo073() {
		self::$plugin->upgrade_to_0_7_3();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.7.3' === get_option( $scp_prefix . 'version' ) );

		// VK
		$this->assertTrue( '' === get_option( $scp_prefix . 'setting_vkontakte_application_id' ) );
		$this->assertTrue( 0  === get_option( $scp_prefix . 'setting_vkontakte_close_window_after_join' ) );
	}

	/**
	 * Upgrade to 0.7.4
	 */
	private function upgradeTo074() {
		self::$plugin->upgrade_to_0_7_4();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.7.4' === get_option( $scp_prefix . 'version' ) );

		// Common
		$this->assertTrue( 'all'                        === get_option( $scp_prefix . 'visitor_registered_and_role_equals_to' ) );
		$this->assertTrue( 0                            === get_option( $scp_prefix . 'setting_use_icons_instead_of_labels_in_tabs' ) );
		$this->assertTrue( '2x'                         === get_option( $scp_prefix . 'setting_icons_size_on_desktop' ) );

		$this->assertTrue( 'Follow Us on Social Media!' === get_option( $scp_prefix . 'setting_plugin_title_on_mobile_devices' ) );
		$this->assertTrue( '2x'                         === get_option( $scp_prefix . 'setting_icons_size_on_mobile_devices' ) );
		$this->assertTrue( 0                            === get_option( $scp_prefix . 'event_hide_element_after_click_on_it' ) );
		$this->assertTrue( 1                            === get_option( $scp_prefix . 'setting_show_admin_bar_menu' ) );

		// Facebook
		$this->assertTrue( 0                            === get_option( $scp_prefix . 'setting_facebook_close_window_after_join' ) );

		// VK
		$this->assertTrue( 'https://vk.com/blogsonwordpress_new' === get_option( $scp_prefix . 'setting_vkontakte_page_url' ) );

		// Odnoklassniki
		$this->assertTrue( 'https://ok.ru/group/57122812461115' === get_option( $scp_prefix . 'setting_odnoklassniki_group_url' ) );
	}

	/**
	 * Upgrade to 0.7.5
	 */
	private function upgradeTo075() {
		self::$plugin->upgrade_to_0_7_5();

		$scp_prefix = self::$plugin->get_scp_prefix();

		$this->assertTrue( '0.7.5' === get_option( $scp_prefix . 'version' ) );

		// Google+
		$this->assertTrue( 'portrait' === get_option( $scp_prefix . 'setting_googleplus_layout' ) );

		// Twitter
		$this->assertTrue( 0        === get_option( $scp_prefix . 'setting_twitter_close_window_after_join' ) );
		$this->assertTrue( 1        === get_option( $scp_prefix . 'setting_twitter_use_follow_button' ) );
		$this->assertTrue( 1        === get_option( $scp_prefix . 'setting_twitter_show_count' ) );
		$this->assertTrue( 1        === get_option( $scp_prefix . 'setting_twitter_show_screen_name' ) );
		$this->assertTrue( 1        === get_option( $scp_prefix . 'setting_twitter_follow_button_large_size' ) );
		$this->assertTrue( 'center' === get_option( $scp_prefix . 'setting_twitter_follow_button_align_by' ) );
		$this->assertTrue( 1        === get_option( $scp_prefix . 'setting_twitter_use_timeline' ) );
		$this->assertTrue( false    === get_option( $scp_prefix . 'setting_twitter_widget_id' ) );

		// VK
		$this->assertTrue( false === get_option( $scp_prefix . 'setting_vkontakte_delay_before_render' ) );

		// Опции трекинга событий
		$this->assertTrue( 1  === get_option( $scp_prefix . 'use_events_tracking' ) );
		$this->assertTrue( 1  === get_option( $scp_prefix . 'do_not_use_tracking_in_debug_mode' ) );
		$this->assertTrue( '' === get_option( $scp_prefix . 'google_analytics_tracking_id' ) );
		$this->assertTrue( 1  === get_option( $scp_prefix . 'push_events_to_aquisition_social_plugins' ) );
		$this->assertTrue( 1  === get_option( $scp_prefix . 'push_events_when_displaying_window' ) );
		$this->assertTrue( 1  === get_option( $scp_prefix . 'push_events_when_subscribing_on_social_networks' ) );
		$this->assertTrue( 1  === get_option( $scp_prefix . 'add_window_events_descriptions' ) );

		// Трекинг событий социальных сетей
		$this->assertTrue( 1 === get_option( $scp_prefix . 'tracking_use_twitter' ) );
		$this->assertTrue( __( 'Follow on Twitter', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'tracking_twitter_event' ) );

		$this->assertTrue( 1 === get_option( $scp_prefix . 'tracking_use_vkontakte' ) );
		$this->assertTrue( __( 'Subscribe on VK.com', L10N_SCP_PREFIX )     === get_option( $scp_prefix . 'tracking_vkontakte_subscribe_event' ) );
		$this->assertTrue( __( 'Unsubscribe from VK.com', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'tracking_vkontakte_unsubscribe_event' ) );

		$this->assertTrue( 1 === get_option( $scp_prefix . 'tracking_use_facebook' ) );
		$this->assertTrue( __( 'Subscribe on Facebook', L10N_SCP_PREFIX )     === get_option( $scp_prefix . 'tracking_facebook_subscribe_event' ) );
		$this->assertTrue( __( 'Unsubscribe from Facebook', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'tracking_facebook_unsubscribe_event' ) );

		// Описания событий для отправки в Google Analytics
		$this->assertTrue( __( 'Show immediately', L10N_SCP_PREFIX )                    === get_option( $scp_prefix . 'tracking_event_label_window_showed_immediately' ) );
		$this->assertTrue( __( 'Show after delay before it rendered', L10N_SCP_PREFIX ) === get_option( $scp_prefix . 'tracking_event_label_window_showed_with_delay' ) );
		$this->assertTrue( __( 'Show after click on CSS-selector', L10N_SCP_PREFIX )    === get_option( $scp_prefix . 'tracking_event_label_window_showed_after_click' ) );
		$this->assertTrue( __( 'Show after scrolling down', L10N_SCP_PREFIX )           === get_option( $scp_prefix . 'tracking_event_label_window_showed_on_scrolling_down' ) );
		$this->assertTrue( __( 'Show on exit intent', L10N_SCP_PREFIX )                 === get_option( $scp_prefix . 'tracking_event_label_window_showed_on_exit_intent' ) );
		$this->assertTrue( __( '(no events fired)', L10N_SCP_PREFIX )                   === get_option( $scp_prefix . 'tracking_event_label_no_events_fired' ) );
		$this->assertTrue( __( 'After delay before show widget', L10N_SCP_PREFIX )      === get_option( $scp_prefix . 'tracking_event_label_on_delay' ) );
		$this->assertTrue( __( 'After click on CSS-selector', L10N_SCP_PREFIX )         === get_option( $scp_prefix . 'tracking_event_label_after_click' ) );
		$this->assertTrue( __( 'On scrolling down', L10N_SCP_PREFIX )                   === get_option( $scp_prefix . 'tracking_event_label_on_scrolling_down' ) );
		$this->assertTrue( __( 'On exit intent', L10N_SCP_PREFIX )                      === get_option( $scp_prefix . 'tracking_event_label_on_exit_intent' ) );

		// Прочие опции
		$this->assertTrue( 1 === get_option( $scp_prefix . 'do_not_use_cookies_after_click_on_element' ) );
	}

	/**
	 * Check Debug Mode is on
	 */
	private function checkDebugModeIsOn() {
		$scp_prefix = self::$plugin->get_scp_prefix();
		$this->assertTrue( 1 === get_option( $scp_prefix . 'setting_debug_mode' ) );
	}
}

