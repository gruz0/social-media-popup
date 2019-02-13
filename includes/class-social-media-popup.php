<?php
/**
 * Social Media Popup
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;

/**
 * Social Media Popup class
 */
class Social_Media_Popup {
	/**
	 * Конструктор
	 *
	 * @since 0.7.3 Changed action to wp_enqueue_scripts to add admin scripts
	 */
	public function __construct() {
		add_action( 'admin_init', array( & $this, 'admin_init' ) );
		add_action( 'admin_menu', array( & $this, 'add_menu' ) );
		add_action( 'admin_bar_menu', array( & $this, 'admin_bar_menu' ), 999 );
		add_action( 'wp_footer', array( & $this, 'add_events_tracking_code' ) );

		add_action( 'admin_enqueue_scripts', array( & $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( & $this, 'enqueue_scripts' ) );
	}

	/**
	 * Активация плагина
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		set_transient( '_smp_redirect', true, 30 );

		self::upgrade();
	}

	/**
	 * Деактивация плагина
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;
	}

	/**
	 * Деинсталляция плагина
	 */
	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;
		if ( ! SMP_Options::get_option( 'setting_remove_settings_on_uninstall' ) ) return;

		SMP_Options::delete_options();
	}

	/**
	 * Обновление плагина
	 */
	public static function upgrade() {
		if ( false === SMP_Options::get_option( 'version' ) ) {
			SMP_Options::initialize_options();
		}

		// Automatically activate debug mode after reactivating plugin
		SMP_Options::update_option( 'setting_debug_mode', 1 );
	}

	/**
	 * Show admin notice if Debug mode is activated
	 *
	 * @since 0.7.6
	 */
	public function add_debug_mode_notice() {
	?>
		<div class="notice notice-warning">
			<p>
			<?php
				$url = add_query_arg( array( 'page' => SMP_PREFIX ), admin_url( 'admin.php' ) );
				echo esc_html( 'Social Media Popup Debug Mode is activated!', 'social-media-popup' )
					. ' <a href="' . esc_url( $url ) . '">' . esc_html( 'Deactivate Debug Mode', 'social-media-popup' ) . '</a>';
			?>
			</p>
		</div>
	<?php
	}

	/**
	 * Hook into WP's admin_init action hook
	 *
	 * @uses $this->init_settings()
	 */
	public function admin_init() {
		$this->init_settings();

		if ( SMP_Options::get_option( 'setting_debug_mode' ) ) {
			add_action( 'admin_notices', array( $this, 'add_debug_mode_notice' ) );
		}

		if ( ! get_transient( '_smp_redirect' ) ) return;
		delete_transient( '_smp_redirect' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) return;

		wp_safe_redirect( add_query_arg( array( 'page' => SMP_PREFIX ), admin_url( 'admin.php' ) ) );
	}

	/**
	 * Управление настройками плагина: генерация формы, создание полей
	 */
	public function init_settings() {
		$this->init_settings_common();
		$this->init_settings_common_view_on_desktop();
		$this->init_settings_common_view_on_mobile_devices();
		$this->init_settings_common_events();
		$this->init_settings_common_tracking();
		$this->init_settings_common_management();

		$this->init_settings_facebook();
		$this->init_settings_vkontakte();
		$this->init_settings_odnoklassniki();
		$this->init_settings_googleplus();
		$this->init_settings_twitter();
		$this->init_settings_pinterest();
	}

	/**
	 * Общие настройки
	 */
	public function init_settings_common() {
		$group        = SMP_PREFIX . '-group-general';
		$options_page = SMP_PREFIX . '-group-general';
		$section      = SMP_PREFIX . '-section-common';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Common Settings', 'social-media-popup' ),
			array( & $this, 'settings_section_common' ),
			$options_page
		);

		add_settings_field(
			'setting_debug_mode',
			esc_html( 'Debug Mode', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_debug_mode',
			)
		);

		add_settings_field(
			'setting_tabs_order',
			esc_html( 'Tabs Order', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_tabs_order' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_tabs_order',
			)
		);

		add_settings_field(
			'setting_close_popup_by_clicking_anywhere',
			esc_html( 'Close the popup by clicking anywhere on the screen', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_close_popup_by_clicking_anywhere',
			)
		);

		add_settings_field(
			'setting_close_popup_when_esc_pressed',
			esc_html( 'Close the popup when ESC pressed', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_close_popup_when_esc_pressed',
			)
		);

		add_settings_field(
			'setting_show_on_mobile_devices',
			esc_html( 'Show widget on mobile devices', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_show_on_mobile_devices',
			)
		);

		add_settings_field(
			'setting_show_admin_bar_menu',
			esc_html( 'Show Plugin Menu in Admin Bar', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_show_admin_bar_menu',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид")
	 */
	public function init_settings_common_view_on_desktop() {
		$group        = SMP_PREFIX . '-group-view';
		$options_page = SMP_PREFIX . '-group-view';
		$section      = SMP_PREFIX . '-section-common-view';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'View', 'social-media-popup' ),
			array( & $this, 'settings_section_common_view_on_desktop' ),
			$options_page
		);

		add_settings_field(
			'setting_plugin_title',
			esc_html( 'Widget Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_plugin_title',
			)
		);

		add_settings_field(
			'setting_use_animation',
			esc_html( 'Use Animation', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_animation',
			)
		);

		add_settings_field(
			'setting_animation_style',
			esc_html( 'Animation Style', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_animation_style' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_animation_style',
			)
		);

		add_settings_field(
			'setting_use_icons_instead_of_labels_in_tabs',
			esc_html( 'Use Icons Instead of Labels in Tabs', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_icons_instead_of_labels_in_tabs',
			)
		);

		add_settings_field(
			'setting_icons_size_on_desktop',
			esc_html( 'Icons Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_icons_size_on_desktop',
			)
		);

		add_settings_field(
			'setting_hide_tabs_if_one_widget_is_active',
			esc_html( 'Hide Tabs if One Widget is Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_hide_tabs_if_one_widget_is_active',
			)
		);

		add_settings_field(
			'setting_align_tabs_to_center',
			esc_html( 'Align Tabs to Center', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_align_tabs_to_center',
			)
		);

		add_settings_field(
			'setting_show_close_button_in',
			esc_html( 'Show Close Button in Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_show_close_button_in' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_show_close_button_in',
			)
		);

		add_settings_field(
			'setting_show_button_to_close_widget',
			esc_html( 'Show Button to Close Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_show_button_to_close_widget',
			)
		);

		add_settings_field(
			'setting_button_to_close_widget_title',
			esc_html( 'Button to Close Widget Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_button_to_close_widget_title',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( "Thanks! Please don't show me popup.", 'social-media-popup' ),
			)
		);

		add_settings_field(
			'setting_button_to_close_widget_style',
			esc_html( 'Button to Close Widget Style', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_button_to_close_widget_style' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_button_to_close_widget_style',
			)
		);

		add_settings_field(
			'setting_delay_before_show_bottom_button',
			esc_html( 'Delay Before Show Button to Close Widget (sec.)', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_delay_before_show_bottom_button',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '10',
			)
		);

		add_settings_field(
			'setting_container_width',
			esc_html( 'Container Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_container_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_container_height',
			esc_html( 'Container Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_container_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_border_radius',
			esc_html( 'Border Radius', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_border_radius',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '10',
			)
		);

		add_settings_field(
			'setting_overlay_color',
			esc_html( 'Overlay Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_overlay_color',
			)
		);

		add_settings_field(
			'setting_overlay_opacity',
			esc_html( 'Overlay Opacity', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_overlay_opacity',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '80',
			)
		);

		add_settings_field(
			'setting_background_image',
			esc_html( 'Widget Background Image', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_background_image' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_background_image',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид (мобильные устройства)")
	 *
	 * @since 0.7.4
	 *
	 * @return void
	 */
	public function init_settings_common_view_on_mobile_devices() {
		$group        = SMP_PREFIX . '-group-view-mobile';
		$options_page = SMP_PREFIX . '-group-view-mobile';
		$section      = SMP_PREFIX . '-section-common-view-mobile';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'View (Mobile Devices)', 'social-media-popup' ),
			array( & $this, 'settings_section_common_view_on_mobile_devices' ),
			$options_page
		);

		add_settings_field(
			'setting_plugin_title_on_mobile_devices',
			esc_html( 'Widget Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_plugin_title_on_mobile_devices',
			)
		);

		add_settings_field(
			'setting_icons_size_on_mobile_devices',
			esc_html( 'Icons Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_icons_size_on_mobile_devices',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Общие настройки (вкладка "События")
	 */
	public function init_settings_common_events() {
		$this->init_settings_common_events_when();
		$this->init_settings_common_events_who();
	}

	/**
	 * When the popup will appear
	 */
	public function init_settings_common_events_when() {
		$group        = SMP_PREFIX . '-group-events-general';
		$options_page = SMP_PREFIX . '-group-events-general';
		$section      = SMP_PREFIX . '-section-common-events-general';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'When Should the Popup Appear?', 'social-media-popup' ),
			array( & $this, 'settings_section_when_should_the_popup_appear' ),
			$options_page
		);

		add_settings_field(
			'when_should_the_popup_appear',
			esc_html( 'Select Events for Customizing', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_when_should_the_popup_appear' ),
			$options_page,
			$section,
			array(
				'field' => 'when_should_the_popup_appear',
			)
		);

		add_settings_field(
			'popup_will_appear_after_n_seconds',
			esc_html( 'Popup Will Appear After N Second(s)', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'popup_will_appear_after_n_seconds',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '5',
				'required' => true,
			)
		);

		add_settings_field(
			'popup_will_appear_after_clicking_on_element',
			esc_html( 'Popup Will Appear After Clicking on the Given CSS Selector', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'popup_will_appear_after_clicking_on_element',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '#my-button, .entry .button',
				'required' => true,
			)
		);

		add_settings_field(
			'event_hide_element_after_click_on_it',
			esc_html( 'Hide Element After Click on It', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'event_hide_element_after_click_on_it',
			)
		);

		add_settings_field(
			'do_not_use_cookies_after_click_on_element',
			esc_html( 'Do not Use Cookies After Click on Element', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'do_not_use_cookies_after_click_on_element',
			)
		);

		add_settings_field(
			'popup_will_appear_after_scrolling_down_n_percent',
			esc_html( 'Popup Will Appear After Scrolling Down at Least N Percent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'popup_will_appear_after_scrolling_down_n_percent',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '70',
				'required' => true,
			)
		);

		add_settings_field(
			'popup_will_appear_on_exit_intent',
			esc_html( 'Popup Will Appear On Exit-Intent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'popup_will_appear_on_exit_intent',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Who should see the popup
	 */
	public function init_settings_common_events_who() {
		$group        = SMP_PREFIX . '-group-events-who';
		$options_page = SMP_PREFIX . '-group-events-who';
		$section      = SMP_PREFIX . '-section-common-events-who';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Who Should See the Popup?', 'social-media-popup' ),
			array( & $this, 'settings_section_who_should_see_the_popup' ),
			$options_page
		);

		add_settings_field(
			'who_should_see_the_popup',
			esc_html( 'Select Events for Customizing', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_who_should_see_the_popup' ),
			$options_page,
			$section,
			array(
				'field' => 'who_should_see_the_popup',
			)
		);

		add_settings_field(
			'visitor_opened_at_least_n_number_of_pages',
			esc_html( 'Visitor Opened at Least N Number of Pages', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'visitor_opened_at_least_n_number_of_pages',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '2',
				'required' => true,
			)
		);

		add_settings_field(
			'visitor_registered_and_role_equals_to',
			esc_html( 'Registered Users Who Should See the Popup', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_visitor_registered_and_role_equals_to' ),
			$options_page,
			$section,
			array(
				'field' => 'visitor_registered_and_role_equals_to',
			)
		);

		add_settings_field(
			'setting_display_after_n_days',
			esc_html( 'Display After N-days', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_display_after_n_days',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '15',
				'required' => true,
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Events tracking
	 *
	 * @uses $this->init_settings_common_tracking_general()
	 * @uses $this->init_settings_common_tracking_google_analytics()
	 * @uses $this->init_settings_common_tracking_window_events()
	 * @uses $this->init_settings_common_tracking_social_events()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking() {
		$this->init_settings_common_tracking_general();
		$this->init_settings_common_tracking_google_analytics();
		$this->init_settings_common_tracking_window_events();
		$this->init_settings_common_tracking_social_events();
	}

	/**
	 * Events tracking – General
	 *
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_general() {
		$group        = SMP_PREFIX . '-group-tracking-general';
		$options_page = SMP_PREFIX . '-group-tracking-general';
		$section      = SMP_PREFIX . '-section-common-tracking-general';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Events Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_common_events_tracking' ),
			$options_page
		);

		add_settings_field(
			'use_events_tracking',
			esc_html( 'Use Events Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'use_events_tracking',
			)
		);

		add_settings_field(
			'do_not_use_tracking_in_debug_mode',
			esc_html( 'Do not use tracking in Debug mode', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'do_not_use_tracking_in_debug_mode',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Events tracking – Google Analytics
	 *
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_google_analytics() {
		$group        = SMP_PREFIX . '-group-tracking-google-analytics';
		$options_page = SMP_PREFIX . '-group-tracking-google-analytics';
		$section      = SMP_PREFIX . '-section-common-tracking-google-analytics';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Google Analytics', 'social-media-popup' ),
			array( & $this, 'settings_section_common_google_analytics' ),
			$options_page
		);

		add_settings_field(
			'google_analytics_tracking_id',
			esc_html( 'Google Analytics Tracking ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'google_analytics_tracking_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'UA-12345678-0',
				'required' => true,
			)
		);

		add_settings_field(
			'push_events_to_aquisition_social_plugins',
			esc_html( 'Push events to Aquisition > Social > Plugins', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'push_events_to_aquisition_social_plugins',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Events tracking – Windows Events
	 *
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_window_events() {
		$group        = SMP_PREFIX . '-group-tracking-window-events';
		$options_page = SMP_PREFIX . '-group-tracking-window-events';
		$section      = SMP_PREFIX . '-section-common-tracking-window-events';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Window Events Descriptions', 'social-media-popup' ),
			array( & $this, 'settings_section_common_window_events_descriptions' ),
			$options_page
		);

		add_settings_field(
			'push_events_when_displaying_window',
			esc_html( 'Push events when displaying the window', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'push_events_when_displaying_window',
			)
		);

		add_settings_field(
			'tracking_event_label_window_showed_immediately',
			esc_html( 'Window showed immediately', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_window_showed_immediately',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show immediately', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_window_showed_with_delay',
			esc_html( 'Window showed after N seconds', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_window_showed_with_delay',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show after delay before it rendered', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_window_showed_after_click',
			esc_html( 'Window showed after click on CSS-selector', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_window_showed_after_click',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show after click on CSS-selector', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_window_showed_on_scrolling_down',
			esc_html( 'Window showed on scrolling down', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_window_showed_on_scrolling_down',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show after scrolling down', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_window_showed_on_exit_intent',
			esc_html( 'Window showed on exit intent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_window_showed_on_exit_intent',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show on exit intent', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Events tracking – Social Events
	 *
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_social_events() {
		$group        = SMP_PREFIX . '-group-tracking-social-events';
		$options_page = SMP_PREFIX . '-group-tracking-social-events';
		$section      = SMP_PREFIX . '-section-common-tracking-social-events';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Social Networks Events Descriptions', 'social-media-popup' ),
			array( & $this, 'settings_section_common_multiple_events_descriptions' ),
			$options_page
		);

		add_settings_field(
			'push_events_when_subscribing_on_social_networks',
			esc_html( 'Push events when subscribing on social networks', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'push_events_when_subscribing_on_social_networks',
			)
		);

		add_settings_field(
			'add_window_events_descriptions',
			esc_html( 'Add window events descriptions', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'add_window_events_descriptions',
			)
		);

		add_settings_field(
			'tracking_event_label_no_events_fired',
			esc_html( 'If no events fired', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_no_events_fired',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( '(no events fired)', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_on_delay',
			esc_html( 'When popup will appear after delay before show widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_on_delay',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'After delay before show widget', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_after_click',
			esc_html( 'On click on CSS-selector', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_after_click',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'After click on CSS-selector', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_on_scrolling_down',
			esc_html( 'On window scrolling down', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_on_scrolling_down',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'On window scrolling down', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_event_label_on_exit_intent',
			esc_html( 'On exit intent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_event_label_on_exit_intent',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'On exit intent', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Управление")
	 */
	public function init_settings_common_management() {
		$group        = SMP_PREFIX . '-group-management';
		$options_page = SMP_PREFIX . '-group-management';
		$section      = SMP_PREFIX . '-section-common-management';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Management', 'social-media-popup' ),
			array( & $this, 'settings_section_common_management' ),
			$options_page
		);

		add_settings_field(
			'setting_remove_settings_on_uninstall',
			esc_html( 'Remove Settings On Uninstall Plugin', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_remove_settings_on_uninstall',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Facebook Settings
	 *
	 * @uses $this->init_settings_facebook_general()
	 * @uses $this->init_settings_facebook_tracking()
	 *
	 * @since 0.7.5 Add settings tabs
	 */
	private function init_settings_facebook() {
		$this->init_settings_facebook_general();
		$this->init_settings_facebook_tracking();
	}

	/**
	 * Facebook general settings
	 *
	 * @used_by $this->init_settings_facebook()
	 *
	 * @return void
	 */
	private function init_settings_facebook_general() {
		$group        = SMP_PREFIX . '-group-facebook-general';
		$options_page = SMP_PREFIX . '-group-facebook-general';
		$section      = SMP_PREFIX . '-section-facebook-general';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Facebook Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_facebook' ),
			$options_page
		);

		add_settings_field(
			'setting_use_facebook',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_facebook',
			)
		);

		add_settings_field(
			'setting_facebook_tab_caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'Facebook',
			)
		);

		add_settings_field(
			'setting_facebook_show_description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_show_description',
			)
		);

		add_settings_field(
			'setting_facebook_description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_description',
			)
		);

		add_settings_field(
			'setting_facebook_application_id',
			esc_html( 'Application ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_application_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '123456789012345',
			)
		);

		add_settings_field(
			'setting_facebook_page_url',
			esc_html( 'Facebook Page URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_page_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'https://www.facebook.com/gruz0.ru/',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_facebook_locale',
			esc_html( 'Facebook Locale', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_facebook_locale' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_locale',
			)
		);

		add_settings_field(
			'setting_facebook_width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_facebook_height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_facebook_adapt_container_width',
			esc_html( 'Adapt to Plugin Container Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_adapt_container_width',
			)
		);

		add_settings_field(
			'setting_facebook_use_small_header',
			esc_html( 'Use Small Header', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_use_small_header',
			)
		);

		add_settings_field(
			'setting_facebook_hide_cover',
			esc_html( 'Hide cover photo in the header', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_hide_cover',
			)
		);

		add_settings_field(
			'setting_facebook_show_facepile',
			esc_html( 'Show profile photos when friends like this', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_show_facepile',
			)
		);

		add_settings_field(
			'setting_facebook_tabs',
			esc_html( 'Show Content from Tabs', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_facebook_tabs' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_tabs',
			)
		);

		add_settings_field(
			'setting_facebook_close_window_after_join',
			esc_html( 'Close Plugin Window After Joining the Group', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_facebook_close_window_after_join',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Facebook Tracking settings
	 *
	 * @used_by $this->init_settings_facebook()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_facebook_tracking() {
		$group        = SMP_PREFIX . '-group-facebook-tracking';
		$options_page = SMP_PREFIX . '-group-facebook-tracking';
		$section      = SMP_PREFIX . '-section-facebook-tracking';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_facebook_tracking' ),
			$options_page
		);

		add_settings_field(
			'tracking_use_facebook',
			esc_html( 'Use Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_use_facebook',
			)
		);

		add_settings_field(
			'tracking_facebook_subscribe_event',
			esc_html( 'Subscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_facebook_subscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Subscribe on Facebook', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_facebook_unsubscribe_event',
			esc_html( 'Unsubscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_facebook_unsubscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Unsubscribe from Facebook', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * VK.com Settings
	 *
	 * @uses $this->init_settings_vkontakte_general()
	 * @uses $this->init_settings_vkontakte_tracking()
	 *
	 * @since 0.7.5 Add settings tabs
	 */
	private function init_settings_vkontakte() {
		$this->init_settings_vkontakte_general();
		$this->init_settings_vkontakte_tracking();
	}

	/**
	 * VK.com general settings
	 *
	 * @used_by $this->init_settings_vkontakte()
	 *
	 * @return void
	 */
	private function init_settings_vkontakte_general() {
		$group        = SMP_PREFIX . '-group-vkontakte-general';
		$options_page = SMP_PREFIX . '-group-vkontakte-general';
		$section      = SMP_PREFIX . '-section-vkontakte-general';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'VKontakte Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_vkontakte' ),
			$options_page
		);

		add_settings_field(
			'setting_use_vkontakte',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_vkontakte',
			)
		);

		add_settings_field(
			'setting_vkontakte_tab_caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'VK', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'setting_vkontakte_show_description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_show_description',
			)
		);

		add_settings_field(
			'setting_vkontakte_description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_description',
			)
		);

		add_settings_field(
			'setting_vkontakte_application_id',
			esc_html( 'VKontakte Application ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_application_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '1234567',
			)
		);

		add_settings_field(
			'setting_vkontakte_page_or_group_id',
			esc_html( 'VKontakte Page or Group ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_page_or_group_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '12345678',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_vkontakte_page_url',
			esc_html( 'VKontakte Page URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_page_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'https://vk.com/ru_wp',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_vkontakte_width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_vkontakte_height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_vkontakte_layout',
			esc_html( 'Layout', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_vkontakte_layout' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_layout',
			)
		);

		add_settings_field(
			'setting_vkontakte_color_background',
			esc_html( 'Background Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_color_background',
			)
		);

		add_settings_field(
			'setting_vkontakte_color_text',
			esc_html( 'Text Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_color_text',
			)
		);

		add_settings_field(
			'setting_vkontakte_color_button',
			esc_html( 'Button Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_color_button',
			)
		);

		add_settings_field(
			'setting_vkontakte_close_window_after_join',
			esc_html( 'Close Plugin Window After Joining the Group', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_vkontakte_close_window_after_join',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * VK.com Tracking settings
	 *
	 * @used_by $this->init_settings_vkontakte()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_vkontakte_tracking() {
		$group        = SMP_PREFIX . '-group-vkontakte-tracking';
		$options_page = SMP_PREFIX . '-group-vkontakte-tracking';
		$section      = SMP_PREFIX . '-section-vkontakte-tracking';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_vkontakte_tracking' ),
			$options_page
		);

		add_settings_field(
			'tracking_use_vkontakte',
			esc_html( 'Use Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'section' => $section,
				'field' => 'tracking_use_vkontakte',
			)
		);

		add_settings_field(
			'tracking_vkontakte_subscribe_event',
			esc_html( 'Subscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'section' => $section,
				'field' => 'tracking_vkontakte_subscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Subscribe on VK', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'tracking_vkontakte_unsubscribe_event',
			esc_html( 'Unsubscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_vkontakte_unsubscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Unsubscribe from VK', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Настройки Одноклассников
	 */
	private function init_settings_odnoklassniki() {
		$group        = SMP_PREFIX . '-group-odnoklassniki';
		$options_page = SMP_PREFIX . '_odnoklassniki_options';
		$section      = SMP_PREFIX . '-section-odnoklassniki';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Odnoklassniki Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_odnoklassniki' ),
			$options_page
		);

		add_settings_field(
			'setting_use_odnoklassniki',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_odnoklassniki',
			)
		);

		add_settings_field(
			'setting_odnoklassniki_tab_caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_odnoklassniki_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Odnoklassniki', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'setting_odnoklassniki_show_description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_odnoklassniki_show_description',
			)
		);

		add_settings_field(
			'setting_odnoklassniki_description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_odnoklassniki_description',
			)
		);

		add_settings_field(
			'setting_odnoklassniki_group_id',
			esc_html( 'Odnoklassniki Group ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_odnoklassniki_group_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '12345678901234',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_odnoklassniki_group_url',
			esc_html( 'Odnoklassniki Group URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_odnoklassniki_group_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'https://ok.ru/group/57122812461115',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_odnoklassniki_width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_odnoklassniki_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_odnoklassniki_height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_odnoklassniki_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Настройки Google+
	 */
	private function init_settings_googleplus() {
		$group        = SMP_PREFIX . '-group-googleplus';
		$options_page = SMP_PREFIX . '_googleplus_options';
		$section      = SMP_PREFIX . '-section-googleplus';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Google+ Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_googleplus' ),
			$options_page
		);

		add_settings_field(
			'setting_use_googleplus',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_googleplus',
			)
		);

		add_settings_field(
			'setting_googleplus_tab_caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'Google+',
			)
		);

		add_settings_field(
			'setting_googleplus_show_description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_show_description',
			)
		);

		add_settings_field(
			'setting_googleplus_description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_description',
			)
		);

		add_settings_field(
			'setting_googleplus_page_type',
			esc_html( 'Google+ Page Type', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_page_type' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_page_type',
			)
		);

		add_settings_field(
			'setting_googleplus_page_url',
			esc_html( 'Google+ Page URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_page_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '//plus.google.com/u/0/117676776729232885815',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_googleplus_layout',
			esc_html( 'Layout', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_layout' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_layout',
			)
		);

		add_settings_field(
			'setting_googleplus_locale',
			esc_html( 'Google+ Locale', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_locale' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_locale',
			)
		);

		add_settings_field(
			'setting_googleplus_size',
			esc_html( 'Widget Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_size',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_googleplus_theme',
			esc_html( 'Google+ Theme', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_theme' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_theme',
			)
		);

		add_settings_field(
			'setting_googleplus_show_cover_photo',
			esc_html( 'Show Cover Photo', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_show_cover_photo',
			)
		);

		add_settings_field(
			'setting_googleplus_show_tagline',
			esc_html( 'Show Tagline', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_googleplus_show_tagline',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Twitter Settings
	 *
	 * @uses $this->init_settings_twitter_general()
	 * @uses $this->init_settings_twitter_follow_button()
	 * @uses $this->init_settings_twitter_timeline()
	 *
	 * @since 0.6
	 */
	private function init_settings_twitter() {
		$this->init_settings_twitter_general();
		$this->init_settings_twitter_follow_button();
		$this->init_settings_twitter_timeline();
		$this->init_settings_twitter_tracking();
	}

	/**
	 * Twitter general settings
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_general() {
		$group        = SMP_PREFIX . '-group-twitter-general';
		$options_page = SMP_PREFIX . '-group-twitter-general';
		$section      = SMP_PREFIX . '-section-twitter-general';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Common Settings', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter' ),
			$options_page
		);

		add_settings_field(
			'setting_use_twitter',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_twitter',
			)
		);

		add_settings_field(
			'setting_twitter_tab_caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Twitter', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'setting_twitter_show_description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_show_description',
			)
		);

		add_settings_field(
			'setting_twitter_description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_description',
			)
		);

		add_settings_field(
			'setting_twitter_username',
			'@username',
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_username',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'gruz0',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_twitter_locale',
			esc_html( 'Twitter Locale', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_locale' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_locale',
			)
		);

		add_settings_field(
			'setting_twitter_first_widget',
			esc_html( 'First widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_first_widget' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_first_widget',
			)
		);

		add_settings_field(
			'setting_twitter_close_window_after_join',
			esc_html( 'Close Plugin Window After Joining the Group', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_close_window_after_join',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Twitter Follow Button settings
	 *
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_follow_button() {
		$group        = SMP_PREFIX . '-group-twitter-follow-button';
		$options_page = SMP_PREFIX . '-group-twitter-follow-button';
		$section      = SMP_PREFIX . '-section-twitter-follow-button';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Follow Button Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter_follow_button' ),
			$options_page
		);

		add_settings_field(
			'setting_twitter_use_follow_button',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_use_follow_button',
			)
		);

		add_settings_field(
			'setting_twitter_show_count',
			esc_html( 'Show Followers Count', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_show_count',
			)
		);

		add_settings_field(
			'setting_twitter_show_screen_name',
			esc_html( 'Show Username', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_show_screen_name',
			)
		);

		add_settings_field(
			'setting_twitter_follow_button_large_size',
			esc_html( 'Follow Button Large Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_follow_button_large_size',
			)
		);

		add_settings_field(
			'setting_twitter_follow_button_align_by',
			esc_html( 'Follow Button Align', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_follow_button_align_by' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_follow_button_align_by',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Twitter Timeline settings
	 *
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_timeline() {
		$group        = SMP_PREFIX . '-group-twitter-timeline';
		$options_page = SMP_PREFIX . '-group-twitter-timeline';
		$section      = SMP_PREFIX . '-section-twitter-timeline';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Timeline Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter_timeline' ),
			$options_page
		);

		add_settings_field(
			'setting_twitter_use_timeline',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_use_timeline',
			)
		);

		add_settings_field(
			'setting_twitter_theme',
			esc_html( 'Theme', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_theme' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_theme',
			)
		);

		add_settings_field(
			'setting_twitter_link_color',
			esc_html( 'Link Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_link_color',
			)
		);

		add_settings_field(
			'setting_twitter_tweet_limit',
			esc_html( 'Tweet Limit', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_tweet_limit',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '3',
			)
		);

		add_settings_field(
			'setting_twitter_show_replies',
			esc_html( 'Show Replies', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_show_replies',
			)
		);

		add_settings_field(
			'setting_twitter_width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_twitter_height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_twitter_chrome',
			esc_html( 'Chrome', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_chrome' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_twitter_chrome',
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Twitter Tracking settings
	 *
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_tracking() {
		$group        = SMP_PREFIX . '-group-twitter-tracking';
		$options_page = SMP_PREFIX . '-group-twitter-tracking';
		$section      = SMP_PREFIX . '-section-twitter-tracking';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter_tracking' ),
			$options_page
		);

		add_settings_field(
			'tracking_use_twitter',
			esc_html( 'Use Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_use_twitter',
			)
		);

		add_settings_field(
			'tracking_twitter_event',
			esc_html( 'Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'tracking_twitter_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Follow on Twitter', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Настройки Pinterest
	 */
	private function init_settings_pinterest() {
		$group        = SMP_PREFIX . '-group-pinterest';
		$options_page = SMP_PREFIX . '_pinterest_options';
		$section      = SMP_PREFIX . '-section-pinterest';

		register_setting( $group, $options_page, array( 'sanitize_callback' => array( & $this, 'sanitize_option' ) ) );

		add_settings_section(
			$section,
			esc_html( 'Pinterest Profile Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_pinterest' ),
			$options_page
		);

		add_settings_field(
			'setting_use_pinterest',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_use_pinterest',
			)
		);

		add_settings_field(
			'setting_pinterest_tab_caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_pinterest_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Pinterest', 'social-media-popup' ),
			)
		);

		add_settings_field(
			'setting_pinterest_show_description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_pinterest_show_description',
			)
		);

		add_settings_field(
			'setting_pinterest_description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_pinterest_description',
			)
		);

		add_settings_field(
			'setting_pinterest_profile_url',
			esc_html( 'Profile URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_pinterest_profile_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'http://ru.pinterest.com/gruz0/',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_pinterest_image_width',
			esc_html( 'Image Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_pinterest_image_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '60',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_pinterest_width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_pinterest_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '380',
				'required' => true,
			)
		);

		add_settings_field(
			'setting_pinterest_height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => 'setting_pinterest_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '300',
				'required' => true,
			)
		);

		add_settings_field(
			'required',
			'',
			array( 'SMP_Settings_Field', 'settings_field_hidden_section' ),
			$options_page,
			$section,
			array(
				'section' => $section,
			)
		);
	}

	/**
	 * Описание общих настроек
	 */
	public function settings_section_common() {
		esc_html_e( 'Common settings', 'social-media-popup' );
	}

	/**
	 * Описание общих настроек (таб "Внешний вид")
	 */
	public function settings_section_common_view_on_desktop() {
		esc_html_e( 'Plugin appearance on desktop devices can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание общих настроек (таб "Внешний вид (мобильные устройства)")
	 */
	public function settings_section_common_view_on_mobile_devices() {
		esc_html_e( 'Plugin appearance on mobile devices can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание общих настроек (таб "События" — "Когда показывать окно")
	 */
	public function settings_section_when_should_the_popup_appear() {
		esc_html_e( 'Plugin events "When should the popup will appear" can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание общих настроек (таб "События" – "Кому показывать окно")
	 */
	public function settings_section_who_should_see_the_popup() {
		esc_html_e( 'Plugin events "Who should see the popup" can be set in this section', 'social-media-popup' );
	}

	/**
	 * Events Tracking tab description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_events_tracking() {
		esc_html_e( 'Events tracking can be set in this section', 'social-media-popup' );
	}

	/**
	 * Window Events Tracking events description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_window_events_descriptions() {
		esc_html_e( 'Window rendering uses these events when social networks are disabled', 'social-media-popup' );
	}

	/**
	 * Google Analytics Events Tracking description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_google_analytics() {
		esc_html_e( 'Google Analytics settings', 'social-media-popup' );
	}

	/**
	 * Multiple Events Tracking events description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_multiple_events_descriptions() {
		esc_html_e( 'These descriptions will concatenate with social networks events descriptions. Example: [Subscribe on Facebook] + [no events fired]', 'social-media-popup' );
	}

	/**
	 * Описание общих настроек (таб "Управление")
	 */
	public function settings_section_common_management() {
		esc_html_e( 'Management settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание настроек Facebook
	 */
	public function settings_section_facebook() {
		esc_html_e( 'Facebook settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Facebook tracking settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_facebook_tracking() {
		esc_html_e( 'Facebook tracking settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание настроек ВКонтакте
	 */
	public function settings_section_vkontakte() {
		esc_html_e( 'VK.com settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * VK.com tracking settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_vkontakte_tracking() {
		esc_html_e( 'VK.com tracking settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание настроек Одноклассников
	 */
	public function settings_section_odnoklassniki() {
		esc_html_e( 'Odnoklassniki.ru settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание настроек Google+
	 */
	public function settings_section_googleplus() {
		esc_html_e( 'Google+ settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Twitter general settings
	 *
	 * @since 0.6
	 */
	public function settings_section_twitter() {
		esc_html_e( 'Twitter settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Twitter Follow Button settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_twitter_follow_button() {
		esc_html_e( 'Twitter Follow Button widget settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Twitter Timeline settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_twitter_timeline() {
		esc_html_e( 'Twitter Timeline widget settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Twitter tracking settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_twitter_tracking() {
		esc_html_e( 'Twitter tracking settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Описание настроек Pinterest
	 */
	public function settings_section_pinterest() {
		esc_html_e( 'Pinterest settings can be set in this section', 'social-media-popup' );
	}

	/**
	 * Добавление пункта меню
	 */
	public function add_menu() {
		add_menu_page(
			esc_html( 'Social Media Popup Options', 'social-media-popup' ),
			esc_html( 'SMP Options', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX,
			array( & $this, 'plugin_settings_page' ),
			'dashicons-format-image'
		);

		add_submenu_page(
			SMP_PREFIX,
			esc_html( 'Facebook Options', 'social-media-popup' ),
			esc_html( 'Facebook', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX . '_facebook_options',
			array( & $this, 'plugin_settings_page_facebook_options' )
		);

		add_submenu_page(
			SMP_PREFIX,
			esc_html( 'VKontakte Options', 'social-media-popup' ),
			esc_html( 'VKontakte', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX . '_vkontakte_options',
			array( & $this, 'plugin_settings_page_vkontakte_options' )
		);

		add_submenu_page(
			SMP_PREFIX,
			esc_html( 'Odnoklassniki Options', 'social-media-popup' ),
			esc_html( 'Odnoklassniki', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX . '_odnoklassniki_options',
			array( & $this, 'plugin_settings_page_odnoklassniki_options' )
		);

		add_submenu_page(
			SMP_PREFIX,
			esc_html( 'Google+ Options', 'social-media-popup' ),
			esc_html( 'Google+', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX . '_googleplus_options',
			array( & $this, 'plugin_settings_page_googleplus_options' )
		);

		add_submenu_page(
			SMP_PREFIX,
			esc_html( 'Twitter Options', 'social-media-popup' ),
			esc_html( 'Twitter', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX . '_twitter_options',
			array( & $this, 'plugin_settings_page_twitter_options' )
		);

		add_submenu_page(
			SMP_PREFIX,
			esc_html( 'Pinterest Options', 'social-media-popup' ),
			esc_html( 'Pinterest', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX . '_pinterest_options',
			array( & $this, 'plugin_settings_page_pinterest_options' )
		);

		add_submenu_page(
			SMP_PREFIX,
			esc_html( 'Debug', 'social-media-popup' ),
			esc_html( 'Debug', 'social-media-popup' ),
			'administrator',
			SMP_PREFIX . '_debug',
			array( & $this, 'plugin_settings_page_debug' )
		);
	}

	/**
	 * Adds menu with submenus to WordPress Admin Bar
	 *
	 * @since 0.7.3
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar object
	 * @return void
	 */
	public function admin_bar_menu( $wp_admin_bar ) {
		$user = wp_get_current_user();

		if ( ! ( $user instanceof WP_User ) ) return;
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		if ( ! SMP_Options::get_option( 'setting_show_admin_bar_menu' ) ) return;

		$args = array(
			'id'    => 'smp-admin-bar',
			'title' => 'Social Media Popup',
		);

		if ( SMP_Options::get_option( 'setting_debug_mode' ) ) {
			$args['title']        .= ' – ' . esc_html( 'Debug Mode', 'social-media-popup' );
			$args['meta']['class'] = 'smp-debug-mode';
		}

		$wp_admin_bar->add_node( $args );

		$menu_smp_settings = array(
			'parent' => 'smp-admin-bar',
			'id'     => 'smp-settings',
			'title'  => esc_html( 'Settings', 'social-media-popup' ),
			'href'   => admin_url( 'admin.php?page=' . SMP_PREFIX ),
		);
		$wp_admin_bar->add_node( $menu_smp_settings );

		$menu_clear_cookies = array(
			'parent' => 'smp-admin-bar',
			'id'     => 'smp-clear-cookies',
			'title'  => esc_html( 'Clear Cookies', 'social-media-popup' ),
			'href'   => '#',
			'meta'   => array(
				'onclick' => 'smp_clearAllPluginCookies();return false;',
			),
		);
		$wp_admin_bar->add_node( $menu_clear_cookies );
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS на страницу настроек
	 *
	 * @since 0.7.3 Added add_cookies_script()
	 * @since 0.7.3 Added WP Color Picker script
	 * @since 0.7.5 Added custom CSS for quick-access menu
	 *
	 * @uses $this->add_cookies_script()
	 * @uses $this->js_asset_filename()
	 * @uses $this->css_asset_filename()
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$this->add_custom_css();

		$version = SMP_Options::get_option( 'version' );

		wp_register_style( SMP_PREFIX . '-admin-css', $this->css_asset_filename( 'admin', $version ) );
		wp_enqueue_style( SMP_PREFIX . '-admin-css' );

		wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-css' );

		wp_enqueue_script( 'jquery-ui-draggable', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-sortable', array( 'jquery' ) );

		$this->add_cookies_script( $version );

		if ( SMP_PREFIX === get_current_screen()->id ) {
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );

			wp_enqueue_script( 'media-upload' );
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_register_script(
			SMP_PREFIX . '-admin-js',
			$this->js_asset_filename( 'admin', $version ),
			array( 'jquery', 'wp-color-picker' )
		);

		wp_enqueue_script( SMP_PREFIX . '-admin-js' );
	}

	/**
	 * Add events tracking code to wp_footer()
	 *
	 * @since 0.7.5
	 *
	 * @uses SMP_Template::render_google_analytics_tracking_code()
	 *
	 * @return mixed
	 */
	public function add_events_tracking_code() {
		if ( ! SMP_Options::get_option( 'use_events_tracking' ) ) {
			return false;
		}

		$content  = '';
		$template = new SMP_Template();

		$google_analytics_tracking_id = SMP_Options::get_option( 'google_analytics_tracking_id' );
		if ( ! empty( $google_analytics_tracking_id ) ) {
			$content .= $template->render_google_analytics_tracking_code( $google_analytics_tracking_id );
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $content;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Страница общих настроек плагина
	 */
	public function plugin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings.php' );
	}

	/**
	 * Страница настроек Facebook
	 */
	public function plugin_settings_page_facebook_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-facebook.php' );
	}

	/**
	 * Страница настроек ВКонтакте
	 */
	public function plugin_settings_page_vkontakte_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-vkontakte.php' );
	}

	/**
	 * Страница настроек Одноклассников
	 */
	public function plugin_settings_page_odnoklassniki_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-odnoklassniki.php' );
	}

	/**
	 * Страница настроек Google+
	 */
	public function plugin_settings_page_googleplus_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-googleplus.php' );
	}

	/**
	 * Страница настроек Twitter
	 */
	public function plugin_settings_page_twitter_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-twitter.php' );
	}

	/**
	 * Страница настроек Pinterest
	 */
	public function plugin_settings_page_pinterest_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-pinterest.php' );
	}

	/**
	 * Страница отладки плагина
	 */
	public function plugin_settings_page_debug() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		$content = $this->validate_settings();
		include( SMP_TEMPLATES_DIR . 'settings/settings-debug.php' );
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS
	 *
	 * @since 0.7.3 Added add_cookies_script()
	 * @since 0.7.5 Added custom CSS for quick-access menu
	 *
	 * @uses $this->add_cookies_script()
	 * @uses $this->css_asset_filename()
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			$this->add_custom_css();
		}

		$version = SMP_Options::get_option( 'version' );

		$this->add_cookies_script( $version );
		if ( is_smp_cookie_present() ) {
			$when_should_the_popup_appear = split_string_by_comma( SMP_Options::get_option( 'when_should_the_popup_appear' ) );

			if ( ! when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_clicking_on_element' ) ) {
				return;
			}

			$popup_will_appear_after_clicking_on_element = SMP_Options::get_option( 'popup_will_appear_after_clicking_on_element' );
			$do_not_use_cookies_after_click_on_element   = SMP_Options::get_option( 'do_not_use_cookies_after_click_on_element' );

			if ( empty( $popup_will_appear_after_clicking_on_element ) || ! $do_not_use_cookies_after_click_on_element ) {
				return;
			}
		}

		$this->render_popup_window( $version );

		wp_register_style( SMP_PREFIX . '-css', $this->css_asset_filename( 'bundle', $version ) );
		wp_enqueue_style( SMP_PREFIX . '-css' );
	}

	/**
	 * Render popup
	 *
	 * @uses $this->js_asset_filename()
	 *
	 * @param string $version Plugin version
	 */
	private function render_popup_window( $version ) {
		$content = SMP_Popup::render();

		$encoded_content = preg_replace( "~[\n\r\t]~", '', $content );
		$encoded_content = base64_encode( $encoded_content );

		wp_register_script( SMP_PREFIX . '-js', $this->js_asset_filename( 'bundle', $version ), array( 'jquery' ) );
		wp_localize_script(
			SMP_PREFIX . '-js',
			'smp',
			array( 'encodedContent' => htmlspecialchars( $encoded_content ) )
		);
		wp_enqueue_script( SMP_PREFIX . '-js' );
	}

	/**
	 * Adds cookies script
	 *
	 * @uses $this->js_asset_filename()
	 *
	 * @since 0.7.3
	 *
	 * @param string $version Plugin version
	 * @return void
	 */
	private function add_cookies_script( $version ) {
		$messages = array(
			'clearCookiesMessage'           => esc_html( 'Page will be reload after clear cookies. Continue?', 'social-media-popup' ),
			'showWindowAfterReturningNDays' => absint( SMP_Options::get_option( 'setting_display_after_n_days' ) ),
		);

		wp_register_script( SMP_PREFIX . '-cookies', $this->js_asset_filename( 'cookies', $version ), array( 'jquery' ) );
		wp_localize_script( SMP_PREFIX . '-cookies', 'smp_cookies', $messages );
		wp_enqueue_script( SMP_PREFIX . '-cookies' );
	}

	/**
	 * Adds custom CSS
	 *
	 * @since 0.7.5
	 */
	private function add_custom_css() {
		$css = '.smp-debug-mode {background: rgba(159, 0, 0, 1) !important;}';

		wp_enqueue_style( SMP_PREFIX . '-custom-css', get_template_directory_uri() );
		wp_add_inline_style( SMP_PREFIX . '-custom-css', $css );
	}

	/**
	 * Validate settings
	 *
	 * @since 0.7.5
	 *
	 * @uses SMP_Validator
	 *
	 * @return string
	 */
	private function validate_settings() {
		$validator = new SMP_Validator( SMP_Options::get_options() );
		return $validator->validate();
	}

	/**
	 * Generate JS filename
	 *
	 * @used_by $this->add_cookies_script()
	 * @used_by $this->render_popup_window()
	 * @used_by $this->admin_enqueue_scripts()
	 *
	 * @since 0.7.6
	 *
	 * @param string $part    Filename's part (eg. admin, cookies, etc.)
	 * @param string $version Plugin's version
	 *
	 * @return string
	 */
	private function js_asset_filename( $part, $version ) {
		return SMP_ASSETS_URL . "js/${part}.js?" . ( $this->is_dockerized() ? rand() : $version );
	}

	/**
	 * Generate CSS filename
	 *
	 * @used_by $this->enqueue_scripts()
	 * @used_by $this->admin_enqueue_scripts()
	 *
	 * @since 0.7.6
	 *
	 * @param string $part    Filename's part (eg. admin, bundle, etc.)
	 * @param string $version Plugin's version
	 *
	 * @return string
	 */
	private function css_asset_filename( $part, $version ) {
		return SMP_ASSETS_URL . "css/${part}.css?" . ( $this->is_dockerized() ? rand() : $version );
	}

	/**
	 * Check if plugin running inside Docker container
	 *
	 * @return boolean
	 */
	private function is_dockerized() {
		return 1 == $_ENV['DOCKERIZED'];
	}

	/**
	 * Sanitize option
	 */
	public function sanitize_option() {
		if ( empty( $_POST['smp_section'] ) ) {
			$version     = SMP_Options::get_option( 'version' );
			$option_page = $_POST['option_page'];
			$subject     = "SMP ${version}, page: ${option_page}, description: Hidden field was not found";

			add_settings_error(
				'smp_options',
				'',
				'Внимание! Данная страница не содержит обязательного скрытого поля smp_section!<br />' .
				'Возможны проблемы с сохранением настроек плагина.<br /><br />' .
				"Сообщите разработчику плагина (<a href='mailto:support@gruz0.ru?subject=${subject}'>support@gruz0.ru</a>) следующую информацию:<br />" .
				"* Версия плагина: ${version}<br />* Страница: ${option_page}"
			);

			return;
		}

		$values = SMP_Sanitizer::sanitize( $_POST['smp_section'], $_POST['smp_options'] );
		SMP_Options::merge_options( $values );
	}
}
