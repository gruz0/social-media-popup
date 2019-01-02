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
 * Uses in menu, tabs, copyright and other links
 *
 * @since 0.7.4
 */
define( 'SMP_PREFIX', 'social_media_popup' );

require_once( SMP_INCLUDES_DIR . 'functions.php' );
require_once( SMP_INCLUDES_DIR . 'class-smp-settings-field.php' );
require_once( SMP_INCLUDES_DIR . 'class-smp-template.php' );
require_once( SMP_INCLUDES_DIR . 'class-smp-popup.php' );
require_once( SMP_INCLUDES_DIR . 'class-smp-validator.php' );
require_once( SMP_INCLUDES_DIR . 'providers/class-smp-provider.php' );

/**
 * Social Media Popup class
 */
class Social_Media_Popup {
	/**
	 * Plugin version
	 *
	 * @var string $smp_version
	 */
	protected static $smp_version;

	/**
	 * Конструктор
	 *
	 * @since 0.7.3 Changed action to wp_enqueue_scripts to add admin scripts
	 */
	public function __construct() {
		add_action( 'admin_init', array( & $this, 'admin_init' ) );
		add_action( 'admin_menu', array( & $this, 'add_menu' ) );
		add_action( 'admin_bar_menu', array( & $this, 'admin_bar_menu' ), 999 );
		add_action( 'admin_head', array( & $this, 'admin_head' ) );
		add_action( 'wp_footer', array( & $this, 'add_events_tracking_code' ) );

		add_action( 'admin_enqueue_scripts', array( & $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( & $this, 'enqueue_scripts' ) );
	}

	/**
	 * Активация плагина
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		set_transient( '_smp_welcome_screen', true, 30 );

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
		$prefix = self::get_prefix();

		if ( ! current_user_can( 'activate_plugins' ) ) return;
		if ( ! get_option( $prefix . 'setting_remove_settings_on_uninstall' ) ) return;

		$options = array(
			'version',

			// General settings
			'setting_debug_mode',
			'setting_display_after_n_days',
			'setting_display_after_visiting_n_pages',
			'setting_tabs_order',
			'setting_container_width',
			'setting_container_height',
			'setting_border_radius',
			'setting_remove_settings_on_uninstall',
			'setting_close_popup_when_esc_pressed',
			'setting_close_popup_by_clicking_anywhere',
			'setting_show_on_mobile_devices',
			'setting_show_admin_bar_menu',

			// Desktop settings
			'setting_plugin_title',
			'setting_use_animation',
			'setting_animation_style',
			'setting_use_icons_instead_of_labels_in_tabs',
			'setting_icons_size_on_desktop',
			'setting_hide_tabs_if_one_widget_is_active',
			'setting_align_tabs_to_center',
			'setting_show_button_to_close_widget',
			'setting_show_close_button_in',
			'setting_button_to_close_widget_title',
			'setting_button_to_close_widget_style',
			'setting_delay_before_show_bottom_button',
			'setting_overlay_color',
			'setting_overlay_opacity',
			'setting_background_image',

			// Mobile settings
			'setting_plugin_title_on_mobile_devices',
			'setting_icons_size_on_mobile_devices',

			// Events
			'when_should_the_popup_appear',
			'popup_will_appear_after_n_seconds',
			'popup_will_appear_after_clicking_on_element',
			'popup_will_appear_after_scrolling_down_n_percent',
			'popup_will_appear_on_exit_intent',

			// Events additional settings
			'event_hide_element_after_click_on_it',
			'do_not_use_cookies_after_click_on_element',

			// Who should see the popup
			'who_should_see_the_popup',
			'visitor_opened_at_least_n_number_of_pages',
			'visitor_registered_and_role_equals_to',

			// Events tracking
			'use_events_tracking',
			'do_not_use_tracking_in_debug_mode',
			'google_analytics_tracking_id',
			'push_events_to_aquisition_social_plugins',
			'push_events_when_displaying_window',
			'push_events_when_subscribing_on_social_networks',
			'add_window_events_descriptions',
			'tracking_event_label_window_showed_immediately',
			'tracking_event_label_window_showed_with_delay',
			'tracking_event_label_window_showed_after_click',
			'tracking_event_label_window_showed_on_scrolling_down',
			'tracking_event_label_window_showed_on_exit_intent',
			'tracking_event_label_no_events_fired',
			'tracking_event_label_on_delay',
			'tracking_event_label_after_click',
			'tracking_event_label_on_scrolling_down',
			'tracking_event_label_on_exit_intent',

			// Facebook
			'setting_use_facebook',
			'setting_facebook_tab_caption',
			'setting_facebook_show_description',
			'setting_facebook_description',
			'setting_facebook_application_id',
			'setting_facebook_page_url',
			'setting_facebook_locale',
			'setting_facebook_width',
			'setting_facebook_height',
			'setting_facebook_hide_cover',
			'setting_facebook_show_facepile',
			'setting_facebook_show_posts',
			'setting_facebook_adapt_container_width',
			'setting_facebook_use_small_header',
			'setting_facebook_tabs',
			'setting_facebook_close_window_after_join',
			'tracking_use_facebook',
			'tracking_facebook_subscribe_event',
			'tracking_facebook_unsubscribe_event',

			// VK.com
			'setting_use_vkontakte',
			'setting_vkontakte_tab_caption',
			'setting_vkontakte_show_description',
			'setting_vkontakte_description',
			'setting_vkontakte_application_id',
			'setting_vkontakte_page_or_group_id',
			'setting_vkontakte_page_url',
			'setting_vkontakte_width',
			'setting_vkontakte_height',
			'setting_vkontakte_layout',
			'setting_vkontakte_color_background',
			'setting_vkontakte_color_text',
			'setting_vkontakte_color_button',
			'setting_vkontakte_delay_before_render',
			'setting_vkontakte_close_window_after_join',
			'tracking_use_vkontakte',
			'tracking_vkontakte_subscribe_event',
			'tracking_vkontakte_unsubscribe_event',

			// Odnoklassniki
			'setting_use_odnoklassniki',
			'setting_odnoklassniki_tab_caption',
			'setting_odnoklassniki_show_description',
			'setting_odnoklassniki_description',
			'setting_odnoklassniki_group_id',
			'setting_odnoklassniki_group_url',
			'setting_odnoklassniki_width',
			'setting_odnoklassniki_height',

			// Google+
			'setting_use_googleplus',
			'setting_googleplus_tab_caption',
			'setting_googleplus_show_description',
			'setting_googleplus_description',
			'setting_googleplus_page_url',
			'setting_googleplus_locale',
			'setting_googleplus_size',
			'setting_googleplus_theme',
			'setting_googleplus_show_cover_photo',
			'setting_googleplus_show_tagline',
			'setting_googleplus_page_type',
			'setting_googleplus_layout',

			// Twitter
			'setting_use_twitter',
			'setting_twitter_tab_caption',
			'setting_twitter_show_description',
			'setting_twitter_description',
			'setting_twitter_username',
			'setting_twitter_locale',
			'setting_twitter_use_follow_button',
			'setting_twitter_show_count',
			'setting_twitter_show_screen_name',
			'setting_twitter_follow_button_large_size',
			'setting_twitter_follow_button_align_by',
			'setting_twitter_first_widget',
			'setting_twitter_use_timeline',
			'setting_twitter_widget_id',
			'setting_twitter_theme',
			'setting_twitter_link_color',
			'setting_twitter_tweet_limit',
			'setting_twitter_show_replies',
			'setting_twitter_width',
			'setting_twitter_height',
			'setting_twitter_chrome',
			'setting_twitter_close_window_after_join',
			'tracking_use_twitter',
			'tracking_twitter_event',

			// Pinterest
			'setting_use_pinterest',
			'setting_pinterest_tab_caption',
			'setting_pinterest_show_description',
			'setting_pinterest_description',
			'setting_pinterest_profile_url',
			'setting_pinterest_image_width',
			'setting_pinterest_width',
			'setting_pinterest_height',
		);

		for ( $idx = 0, $size = count( $options ); $idx < $size; $idx++ ) {
			delete_option( $prefix . $options[ $idx ] );
		}
	}

	/**
	 * Set plugin version
	 *
	 * @param string $version Plugin version
	 */
	public static function set_version( $version ) {
		self::$smp_version = $version;
	}

	/**
	 * Returns plugin prefix based on version
	 *
	 * @return string
	 */
	public static function get_prefix() {
		if ( empty( self::$smp_version ) ) {
			$version = get_option( 'scp-version' );
			if ( empty( $version ) ) {
				$version = get_option( 'social-community-popup-version' );
				if ( empty( $version ) ) {
					self::set_version( '0.1' );
				} else {
					self::set_version( $version );
				}
			} else {
				self::set_version( $version );
			}
		}

		if ( version_compare( self::$smp_version, '0.7.1', '>=' ) ) {
			return 'scp-';
		} else {
			return 'social-community-popup-';
		}
	}

	/**
	 * Обновление плагина
	 */
	public static function upgrade() {
		self::upgrade_to_0_1();
		self::upgrade_to_0_2();
		self::upgrade_to_0_3();
		self::upgrade_to_0_4();
		self::upgrade_to_0_5();
		self::upgrade_to_0_6();
		self::upgrade_to_0_6_1();
		self::upgrade_to_0_6_2();
		self::upgrade_to_0_6_3();
		self::upgrade_to_0_6_4();
		self::upgrade_to_0_6_5();
		self::upgrade_to_0_6_6();
		self::upgrade_to_0_6_7();
		self::upgrade_to_0_6_8();
		self::upgrade_to_0_6_9();
		self::upgrade_to_0_7_0();
		self::upgrade_to_0_7_1();
		self::upgrade_to_0_7_2();
		self::upgrade_to_0_7_3();
		self::upgrade_to_0_7_4();
		self::upgrade_to_0_7_5();
		self::upgrade_to_0_7_6();

		// Automatically set debug mode on after reactivating plugin
		update_option( self::get_prefix() . 'setting_debug_mode', 1 );
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
	 * Reset SMP version
	 * Don't forget to reactivate all providers in plugin settings to prevent show PHP notices
	 *
	 * @since 0.7.4
	 */
	private static function reset_version() {
		update_option( 'scp-version', '' );
		update_option( 'social-community-popup-version', '' );
	}

	/**
	 * Upgrade plugin to version 0.1
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_1() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( ! get_option( $version ) ) {
			update_option( $version, '0.1' );
			self::set_version( '0.1' );
		}
	}

	/**
	 * Upgrade plugin to version 0.2
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_2() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.2' > get_option( $version ) ) {
			update_option( $prefix . 'setting_display_after_n_days',             30 );
			update_option( $prefix . 'setting_display_after_visiting_n_pages',   0 );
			update_option( $prefix . 'setting_display_after_delay_of_n_seconds', 3 );

			update_option( $prefix . 'setting_use_facebook',                     0 );
			update_option( $prefix . 'setting_facebook_tab_caption',             __( 'Facebook', 'social-media-popup' ) );
			update_option( $prefix . 'setting_facebook_application_id',          '277165072394537' );
			update_option( $prefix . 'setting_facebook_page_url',                'https://www.facebook.com/gruz0.ru' );
			update_option( $prefix . 'setting_facebook_locale',                  'ru_RU' );
			update_option( $prefix . 'setting_facebook_width',                   400 );
			update_option( $prefix . 'setting_facebook_height',                  300 );
			update_option( $prefix . 'setting_facebook_show_header',             1 );
			update_option( $prefix . 'setting_facebook_show_faces',              1 );
			update_option( $prefix . 'setting_facebook_show_stream',             0 );

			update_option( $prefix . 'setting_use_vkontakte',                    0 );
			update_option( $prefix . 'setting_vkontakte_tab_caption',            __( 'VK', 'social-media-popup' ) );
			update_option( $prefix . 'setting_vkontakte_page_or_group_id',       '64088617' );
			update_option( $prefix . 'setting_vkontakte_width',                  400 );
			update_option( $prefix . 'setting_vkontakte_height',                 400 );
			update_option( $prefix . 'setting_vkontakte_color_background',       '#FFFFFF' );
			update_option( $prefix . 'setting_vkontakte_color_text',             '#2B587A' );
			update_option( $prefix . 'setting_vkontakte_color_button',           '#5B7FA6' );
			update_option( $prefix . 'setting_vkontakte_close_window_after_join', 0 );

			update_option( $prefix . 'setting_use_odnoklassniki',                0 );
			update_option( $prefix . 'setting_odnoklassniki_tab_caption',        __( 'Odnoklassniki', 'social-media-popup' ) );
			update_option( $prefix . 'setting_odnoklassniki_group_id',           '57122812461115' );
			update_option( $prefix . 'setting_odnoklassniki_width',              400 );
			update_option( $prefix . 'setting_odnoklassniki_height',             260 );

			update_option( $version, '0.2' );
			self::set_version( '0.2' );
		}
	}

	/**
	 * Upgrade plugin to version 0.3
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_3() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.3' > get_option( $version ) ) {
			update_option( $prefix . 'setting_tabs_order', 'vkontakte,facebook,odnoklassniki' );
			update_option( $version, '0.3' );
			self::set_version( '0.3' );
		}
	}

	/**
	 * Upgrade plugin to version 0.4
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_4() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.4' > get_option( $version ) ) {
			update_option( $version, '0.4' );
			self::set_version( '0.4' );
		}
	}

	/**
	 * Upgrade plugin to version 0.5
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_5() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.5' > get_option( $version ) ) {
			$tabs_order   = get_option( $prefix . 'setting_tabs_order' );
			$tabs_order   = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'googleplus';
			$tabs_order   = array_unique( $tabs_order );

			update_option( $prefix . 'setting_tabs_order', join( ',', $tabs_order ) );

			update_option( $prefix . 'setting_debug_mode',                       1 );
			update_option( $prefix . 'setting_container_width',                  400 );
			update_option( $prefix . 'setting_container_height',                 480 );

			update_option( $prefix . 'setting_use_googleplus',                   0 );
			update_option( $prefix . 'setting_googleplus_tab_caption',           __( 'Google+', 'social-media-popup' ) );
			update_option( $prefix . 'setting_googleplus_show_description',      0 );
			update_option( $prefix . 'setting_googleplus_description',           '' );
			update_option( $prefix . 'setting_googleplus_page_url',              '//plus.google.com/u/0/117676776729232885815' );
			update_option( $prefix . 'setting_googleplus_locale',                'ru' );
			update_option( $prefix . 'setting_googleplus_size',                  400 );
			update_option( $prefix . 'setting_googleplus_theme',                 'light' );
			update_option( $prefix . 'setting_googleplus_show_cover_photo',      1 );
			update_option( $prefix . 'setting_googleplus_show_tagline',          1 );

			update_option( $prefix . 'setting_facebook_height',                  400 );
			update_option( $prefix . 'setting_vkontakte_height',                 400 );
			update_option( $prefix . 'setting_odnoklassniki_height',             400 );

			update_option( $version, '0.5' );
			self::set_version( '0.5' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6' > get_option( $version ) ) {
			$tabs_order   = get_option( $prefix . 'setting_tabs_order' );
			$tabs_order   = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'twitter';
			$tabs_order   = array_unique( $tabs_order );

			update_option( $prefix . 'setting_tabs_order', join( ',', $tabs_order ) );

			update_option( $prefix . 'setting_use_twitter',                       0 );
			update_option( $prefix . 'setting_twitter_tab_caption',               __( 'Twitter', 'social-media-popup' ) );
			update_option( $prefix . 'setting_twitter_show_description',          0 );
			update_option( $prefix . 'setting_twitter_description',               '' );
			update_option( $prefix . 'setting_twitter_username',                  '' );
			update_option( $prefix . 'setting_twitter_widget_id',                 '' );
			update_option( $prefix . 'setting_twitter_theme',                     'light' );
			update_option( $prefix . 'setting_twitter_link_color',                '#CC0000' );
			update_option( $prefix . 'setting_twitter_tweet_limit',               5 );
			update_option( $prefix . 'setting_twitter_show_replies',              0 );
			update_option( $prefix . 'setting_twitter_width',                     400 );
			update_option( $prefix . 'setting_twitter_height',                    400 );
			update_option( $prefix . 'setting_twitter_chrome',                    '' );

			update_option( $version, '0.6' );
			self::set_version( '0.6' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.1
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_1() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.1' > get_option( $version ) ) {
			update_option( $prefix . 'setting_border_radius',                     10 );

			update_option( $version, '0.6.1' );
			self::set_version( '0.6.1' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.2
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_2() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.2' > get_option( $version ) ) {
			update_option( $prefix . 'setting_close_popup_by_clicking_anywhere',  0 );
			update_option( $prefix . 'setting_show_on_mobile_devices',            0 );

			update_option( $version, '0.6.2' );
			self::set_version( '0.6.2' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.3
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_3() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.3' > get_option( $version ) ) {
			$facebook_show_header = absint( get_option( $prefix . 'setting_facebook_show_header' ) );
			update_option( $prefix . 'setting_facebook_hide_cover', ( $facebook_show_header ? '1' : '' ) );
			unset( $facebook_show_header );

			$facebook_show_faces = get_option( $prefix . 'setting_facebook_show_faces' );
			update_option( $prefix . 'setting_facebook_show_facepile', $facebook_show_faces );
			unset( $facebook_show_faces );

			$facebook_show_stream = get_option( $prefix . 'setting_facebook_show_stream' );
			update_option( $prefix . 'setting_facebook_show_posts', $facebook_show_stream );
			unset( $facebook_show_stream );

			$facebook_remove_options = array(
				'setting_facebook_show_header',
				'setting_facebook_show_faces',
				'setting_facebook_show_stream',
				'setting_facebook_show_border',
			);

			for ( $idx = 0, $size = count( $facebook_remove_options ); $idx < $size; $idx++ ) {
				delete_option( $prefix . $facebook_remove_options[ $idx ] );
			}

			unset( $facebook_remove_options );

			update_option( $version, '0.6.3' );
			self::set_version( '0.6.3' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.4
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_4() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.4' > get_option( $version ) ) {
			update_option( $version, '0.6.4' );
			self::set_version( '0.6.4' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.5
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_5() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.5' > get_option( $version ) ) {
			update_option( $prefix . 'setting_googleplus_page_type',              'person' );

			update_option( $version, '0.6.5' );
			self::set_version( '0.6.5' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.6
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_6() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.6' > get_option( $version ) ) {
			update_option( $prefix . 'setting_close_popup_when_esc_pressed',      0 );
			update_option( $prefix . 'setting_vkontakte_delay_before_render',     500 );

			update_option( $version, '0.6.6' );
			self::set_version( '0.6.6' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.7
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_7() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.7' > get_option( $version ) ) {
			update_option(
				$prefix . 'setting_plugin_title',
				'<div style="text-align: center;font: bold normal 14pt/16pt Arial">'
				. esc_html( 'Follow Us on Social Media!', 'social-media-popup' )
				. '</div>'
			);

			update_option( $prefix . 'setting_hide_tabs_if_one_widget_is_active',  1 );

			update_option( $prefix . 'setting_show_button_to_close_widget',        1 );
			update_option( $prefix . 'setting_button_to_close_widget_title',       __( "Thanks! Please don't show me popup.", 'social-media-popup' ) );
			update_option( $prefix . 'setting_button_to_close_widget_style',       'link' );

			update_option( $version, '0.6.7' );
			self::set_version( '0.6.7' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.8
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_8() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.8' > get_option( $version ) ) {
			$tabs_order   = get_option( $prefix . 'setting_tabs_order' );
			$tabs_order   = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'pinterest';
			$tabs_order   = array_unique( $tabs_order );

			update_option( $prefix . 'setting_tabs_order', join( ',', $tabs_order ) );

			update_option( $prefix . 'setting_use_pinterest',                      0 );
			update_option( $prefix . 'setting_pinterest_tab_caption',              __( 'Pinterest', 'social-media-popup' ) );
			update_option( $prefix . 'setting_pinterest_show_description',         0 );
			update_option( $prefix . 'setting_pinterest_description',              '' );
			update_option( $prefix . 'setting_pinterest_profile_url',              'http://ru.pinterest.com/gruz0/' );
			update_option( $prefix . 'setting_pinterest_image_width',              60 );
			update_option( $prefix . 'setting_pinterest_width',                    380 );
			update_option( $prefix . 'setting_pinterest_height',                   300 );

			update_option( $version, '0.6.8' );
			self::set_version( '0.6.8' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.9
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_9() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.6.9' > get_option( $version ) ) {
			update_option( $prefix . 'setting_overlay_color',                      '#000000' );
			update_option( $prefix . 'setting_overlay_opacity',                    80 );
			update_option( $prefix . 'setting_show_close_button_in',               'inside' );
			update_option( $prefix . 'setting_align_tabs_to_center',               0 );
			update_option( $prefix . 'setting_delay_before_show_bottom_button',    0 );
			update_option( $prefix . 'setting_background_image',                   '' );

			update_option( $version, '0.6.9' );
			self::set_version( '0.6.9' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.0
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_0() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.7.0' > get_option( $version ) ) {
			add_option( $prefix . 'when_should_the_popup_appear',               '' );

			$old_value = get_option( $prefix . 'setting_display_after_delay_of_n_seconds' );
			delete_option( $prefix . 'setting_display_after_delay_of_n_seconds' );
			add_option( $prefix . 'popup_will_appear_after_n_seconds',          $old_value );

			add_option( $prefix . 'popup_will_appear_after_clicking_on_element', '' );

			update_option( $version, '0.7.0' );
			self::set_version( '0.7.0' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.1
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_1() {
		$old_prefix  = self::get_prefix();
		$old_version = $old_prefix . 'version';
		$new_prefix  = 'scp-';

		if ( '0.7.1' > get_option( $old_version ) ) {
			$scp_options = array();

			$all_options = wp_load_alloptions();
			foreach ( $all_options as $name => $value ) {
				if ( preg_match( '/^' . $old_prefix . '/', $name ) ) $scp_options[ $name ] = $value;
			}

			foreach ( $scp_options as $option_name => $value ) {
				$new_option_name = preg_replace( '/^' . $old_prefix . '/', '', $option_name );

				delete_option( $option_name );
				delete_option( $new_prefix . $new_option_name );

				if ( ! add_option( $new_prefix . $new_option_name, $value ) ) {
					// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_var_dump
					var_dump( $new_prefix . $new_option_name );
					var_dump( $value );
					// phpcs:enable WordPress.PHP.DevelopmentFunctions.error_log_var_dump
					die();
				}
			}

			$old_value  = get_option( $new_prefix . 'popup_will_appear_after_clicking_on_eleme' );
			$old_value2 = get_option( $new_prefix . 'popup_will_appear_after_clicking_on_element' );
			delete_option( $new_prefix . 'popup_will_appear_after_clicking_on_eleme' );
			delete_option( $new_prefix . 'popup_will_appear_after_clicking_on_element' );

			if ( ! add_option( $new_prefix . 'popup_will_appear_after_clicking_on_element', ( $old_value ? $old_value : $old_value2 ) ) ) {
				// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_var_dump
				var_dump( $new_prefix . 'popup_will_appear_after_clicking_on_eleme' );
				var_dump( $value );
				// phpcs:enable WordPress.PHP.DevelopmentFunctions.error_log_var_dump
				die();
			}

			add_option( $new_prefix . 'popup_will_appear_after_scrolling_down_n_percent', '70' );
			add_option( $new_prefix . 'popup_will_appear_on_exit_intent',                  0 );

			add_option( $new_prefix . 'who_should_see_the_popup',                          '' );

			$old_value = get_option( $new_prefix . 'setting_display_after_visiting_n_pages' );
			delete_option( $new_prefix . 'setting_display_after_visiting_n_pages' );
			add_option( $new_prefix . 'visitor_opened_at_least_n_number_of_pages',          $old_value );

			update_option( $new_prefix . 'version', '0.7.1' );
			self::set_version( '0.7.1' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.2
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_2() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.7.2' > get_option( $version ) ) {
			add_option( $prefix . 'setting_facebook_adapt_container_width',            1 );
			add_option( $prefix . 'setting_facebook_use_small_header',                 0 );

			$old_value = get_option( $prefix . 'setting_facebook_show_posts' );
			$new_value = '1' === $old_value ? 'timeline' : '';

			delete_option( $prefix . 'setting_facebook_show_posts' );
			add_option( $prefix . 'setting_facebook_tabs',                             $new_value );

			update_option( $version, '0.7.2' );
			self::set_version( '0.7.2' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.3
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_3() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.7.3' > get_option( $version ) ) {
			add_option( $prefix . 'setting_vkontakte_application_id',                  '' );
			add_option( $prefix . 'setting_vkontakte_close_window_after_join',         0 );

			update_option( $version, '0.7.3' );
			self::set_version( '0.7.3' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.4
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_4() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.7.4' > get_option( $version ) ) {
			add_option( $prefix . 'visitor_registered_and_role_equals_to',              'all' );
			add_option( $prefix . 'setting_facebook_close_window_after_join',           0 );
			add_option( $prefix . 'setting_vkontakte_page_url',                         'https://vk.com/ru_wp' );
			add_option( $prefix . 'setting_odnoklassniki_group_url',                    'https://ok.ru/group/57122812461115' );
			add_option( $prefix . 'setting_plugin_title_on_mobile_devices',             __( 'Follow Us on Social Media!', 'social-media-popup' ) );
			add_option( $prefix . 'event_hide_element_after_click_on_it',               0 );
			add_option( $prefix . 'setting_icons_size_on_mobile_devices',               '2x' );
			add_option( $prefix . 'setting_use_icons_instead_of_labels_in_tabs',        0 );
			add_option( $prefix . 'setting_show_admin_bar_menu',                        1 );
			add_option( $prefix . 'setting_icons_size_on_desktop',                      '2x' );

			update_option( $version, '0.7.4' );
			self::set_version( '0.7.4' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.5
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_5() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.7.5' > get_option( $version ) ) {
			update_option( $prefix . 'setting_googleplus_layout',                       'portrait' );

			update_option( $prefix . 'setting_twitter_close_window_after_join',         0 );

			update_option( $prefix . 'setting_twitter_use_follow_button',               1 );
			update_option( $prefix . 'setting_twitter_show_count',                      1 );
			update_option( $prefix . 'setting_twitter_show_screen_name',                1 );
			update_option( $prefix . 'setting_twitter_follow_button_large_size',        1 );
			update_option( $prefix . 'setting_twitter_follow_button_align_by',          'center' );
			update_option( $prefix . 'setting_twitter_use_timeline',                    1 );
			delete_option( $prefix . 'setting_twitter_widget_id' );

			delete_option( $prefix . 'setting_vkontakte_delay_before_render' );

			update_option( $prefix . 'use_events_tracking',                             1 );
			update_option( $prefix . 'do_not_use_tracking_in_debug_mode',               1 );
			update_option( $prefix . 'google_analytics_tracking_id',                    '' );
			update_option( $prefix . 'push_events_to_aquisition_social_plugins',        1 );
			update_option( $prefix . 'push_events_when_displaying_window',              1 );
			update_option( $prefix . 'push_events_when_subscribing_on_social_networks', 1 );
			update_option( $prefix . 'add_window_events_descriptions',                  1 );

			update_option( $prefix . 'tracking_use_twitter',                            1 );
			update_option( $prefix . 'tracking_twitter_event',                          __( 'Follow on Twitter', 'social-media-popup' ) );

			update_option( $prefix . 'tracking_use_vkontakte',                          1 );
			update_option( $prefix . 'tracking_vkontakte_subscribe_event',              __( 'Subscribe on VK.com', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_vkontakte_unsubscribe_event',            __( 'Unsubscribe from VK.com', 'social-media-popup' ) );

			update_option( $prefix . 'tracking_use_facebook',                           1 );
			update_option( $prefix . 'tracking_facebook_subscribe_event',               __( 'Subscribe on Facebook', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_facebook_unsubscribe_event',             __( 'Unsubscribe from Facebook', 'social-media-popup' ) );

			update_option( $prefix . 'tracking_event_label_window_showed_immediately',       __( 'Show immediately', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_window_showed_with_delay',        __( 'Show after delay before it rendered', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_window_showed_after_click',       __( 'Show after click on CSS-selector', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_window_showed_on_scrolling_down', __( 'Show after scrolling down', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_window_showed_on_exit_intent',    __( 'Show on exit intent', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_no_events_fired',                 __( '(no events fired)', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_on_delay',                        __( 'After delay before show widget', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_after_click',                     __( 'After click on CSS-selector', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_on_scrolling_down',               __( 'On scrolling down', 'social-media-popup' ) );
			update_option( $prefix . 'tracking_event_label_on_exit_intent',                  __( 'On exit intent', 'social-media-popup' ) );

			update_option( $prefix . 'do_not_use_cookies_after_click_on_element',            1 );

			update_option( $version, '0.7.5' );
			self::set_version( '0.7.5' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.6
	 *
	 * @uses self::get_prefix()
	 * @uses self::set_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_6() {
		$prefix  = self::get_prefix();
		$version = $prefix . 'version';

		if ( '0.7.6' > get_option( $version ) ) {
			update_option( $prefix . 'setting_twitter_locale',       'ru' );
			update_option( $prefix . 'setting_twitter_first_widget', 'follow_button' );

			update_option( $prefix . 'setting_use_animation',        1 );
			update_option( $prefix . 'setting_animation_style',      'bounce' );

			update_option( $version, '0.7.6' );
			self::set_version( '0.7.6' );
		}
	}

	/**
	 * Hook into WP's admin_init action hook
	 *
	 * @uses $this->init_settings()
	 */
	public function admin_init() {
		$this->init_settings();

		$prefix = self::get_prefix();
		if ( 1 === absint( get_option( $prefix . 'setting_debug_mode' ) ) ) {
			add_action( 'admin_notices', array( $this, 'add_debug_mode_notice' ) );
		}

		if ( ! get_transient( '_smp_welcome_screen' ) ) return;
		delete_transient( '_smp_welcome_screen' );
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) return;
		wp_safe_redirect( add_query_arg( array( 'page' => SMP_PREFIX . '_about' ), admin_url( 'index.php' ) ) );
	}

	/**
	 * Управление настройками плагина: генерация формы, создание полей
	 */
	public function init_settings() {
		$this->init_settings_common();
		$this->init_settings_common_view_on_deskop();
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
	 *
	 * @uses self::get_prefix()
	 */
	public function init_settings_common() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-general';
		$options_page = SMP_PREFIX . '-group-general';
		$section      = SMP_PREFIX . '-section-common';

		register_setting( $group, $prefix . 'setting_debug_mode' );
		register_setting( $group, $prefix . 'setting_tabs_order', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_close_popup_by_clicking_anywhere', 'absint' );
		register_setting( $group, $prefix . 'setting_close_popup_when_esc_pressed', 'absint' );
		register_setting( $group, $prefix . 'setting_show_on_mobile_devices', 'absint' );
		register_setting( $group, $prefix . 'setting_show_admin_bar_menu', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'Common Settings', 'social-media-popup' ),
			array( & $this, 'settings_section_common' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-common-debug-mode',
			esc_html( 'Debug Mode', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_debug_mode',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-tabs-order',
			esc_html( 'Tabs Order', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_tabs_order' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_tabs_order',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-close-popup-by-clicking-anywhere',
			esc_html( 'Close the popup by clicking anywhere on the screen', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_close_popup_by_clicking_anywhere',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-close-popup-when-esc-pressed',
			esc_html( 'Close the popup when ESC pressed', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_close_popup_when_esc_pressed',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-show-on-mobile-devices',
			esc_html( 'Show widget on mobile devices', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_show_on_mobile_devices',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-show-admin-bar-menu',
			esc_html( 'Show Plugin Menu in Admin Bar', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_show_admin_bar_menu',
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид")
	 *
	 * @uses self::get_prefix()
	 */
	public function init_settings_common_view_on_deskop() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-view';
		$options_page = SMP_PREFIX . '-group-view';
		$section      = SMP_PREFIX . '-section-common-view';

		register_setting( $group, $prefix . 'setting_plugin_title', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_use_animation', 'absint' );
		register_setting( $group, $prefix . 'setting_animation_style', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_use_icons_instead_of_labels_in_tabs', 'absint' );
		register_setting( $group, $prefix . 'setting_icons_size_on_desktop', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_hide_tabs_if_one_widget_is_active', 'absint' );
		register_setting( $group, $prefix . 'setting_container_width', 'absint' );
		register_setting( $group, $prefix . 'setting_container_height', 'absint' );
		register_setting( $group, $prefix . 'setting_border_radius', 'absint' );
		register_setting( $group, $prefix . 'setting_show_close_button_in', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_show_button_to_close_widget', 'absint' );
		register_setting( $group, $prefix . 'setting_button_to_close_widget_title', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_button_to_close_widget_style', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_delay_before_show_bottom_button', 'absint' );
		register_setting( $group, $prefix . 'setting_overlay_color', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_overlay_opacity', 'absint' );
		register_setting( $group, $prefix . 'setting_align_tabs_to_center', 'absint' );
		register_setting( $group, $prefix . 'setting_background_image', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'View', 'social-media-popup' ),
			array( & $this, 'settings_section_common_view_on_desktop' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-common-plugin-title',
			esc_html( 'Widget Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_plugin_title',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-use-animation',
			esc_html( 'Use Animation', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_animation',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-animation-style',
			esc_html( 'Animation Style', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_animation_style' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_animation_style',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-use-icons-instead-of-labels-in-tabs',
			esc_html( 'Use Icons Instead of Labels in Tabs', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_icons_instead_of_labels_in_tabs',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-icons-size-on-desktop',
			esc_html( 'Icons Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_icons_size_on_desktop',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-hide-tabs-if-one-widget-is-active',
			esc_html( 'Hide Tabs if One Widget is Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_hide_tabs_if_one_widget_is_active',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-align-tabs-to-center',
			esc_html( 'Align Tabs to Center', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_align_tabs_to_center',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-show-close-button-in',
			esc_html( 'Show Close Button in Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_show_close_button_in' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_show_close_button_in',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-show-button-to-close-widget',
			esc_html( 'Show Button to Close Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_show_button_to_close_widget',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-button-to-close-widget-title',
			esc_html( 'Button to Close Widget Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_button_to_close_widget_title',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( "Thanks! Please don't show me popup.", 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-button-to-close-widget-style',
			esc_html( 'Button to Close Widget Style', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_button_to_close_widget_style' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_button_to_close_widget_style',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-delay-before-show-button-to-close-widget',
			esc_html( 'Delay Before Show Button to Close Widget (sec.)', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_delay_before_show_bottom_button',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '10',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-container-width',
			esc_html( 'Container Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_container_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-container-height',
			esc_html( 'Container Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_container_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
				'required' => true,
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-border-radius',
			esc_html( 'Border Radius', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_border_radius',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '10',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-overlay-color',
			esc_html( 'Overlay Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_overlay_color',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-overlay-opacity',
			esc_html( 'Overlay Opacity', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_overlay_opacity',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '80',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-background-image',
			esc_html( 'Widget Background Image', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_background_image' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_background_image',
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид (мобильные устройства)")
	 *
	 * @since 0.7.4
	 *
	 * @uses self::get_prefix()
	 *
	 * @return void
	 */
	public function init_settings_common_view_on_mobile_devices() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-view-mobile';
		$options_page = SMP_PREFIX . '-group-view-mobile';
		$section      = SMP_PREFIX . '-section-common-view-mobile';

		register_setting( $group, $prefix . 'setting_plugin_title_on_mobile_devices', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_icons_size_on_mobile_devices', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'View (Mobile Devices)', 'social-media-popup' ),
			array( & $this, 'settings_section_common_view_on_mobile_devices' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-common-plugin-title-on-mobile-devices',
			esc_html( 'Widget Title', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_plugin_title_on_mobile_devices',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-icons-size-on-mobile-devices',
			esc_html( 'Icons Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_icons_size_on_mobile_devices',
			)
		);
	}

	/**
	 * Общие настройки (вкладка "События")
	 *
	 * @uses self::get_prefix()
	 */
	public function init_settings_common_events() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-events';
		$options_page = SMP_PREFIX . '-group-events';

		$section_when_should_the_popup_appear = SMP_PREFIX . '-section-when-should-the-popup-appear';
		$section_who_should_see_the_popup     = SMP_PREFIX . '-section-who-should-see-the-popup';

		register_setting( $group, $prefix . 'when_should_the_popup_appear', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'popup_will_appear_after_n_seconds', 'absint' );
		register_setting( $group, $prefix . 'popup_will_appear_after_clicking_on_element', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'popup_will_appear_after_scrolling_down_n_percent', 'absint' );
		register_setting( $group, $prefix . 'popup_will_appear_on_exit_intent', 'absint' );
		register_setting( $group, $prefix . 'who_should_see_the_popup', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'visitor_opened_at_least_n_number_of_pages', 'absint' );
		register_setting( $group, $prefix . 'visitor_registered_and_role_equals_to', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_display_after_n_days', 'absint' );
		register_setting( $group, $prefix . 'event_hide_element_after_click_on_it', 'absint' );
		register_setting( $group, $prefix . 'do_not_use_cookies_after_click_on_element', 'absint' );

		add_settings_section(
			$section_when_should_the_popup_appear,
			esc_html( 'When Should the Popup Appear?', 'social-media-popup' ),
			array( & $this, 'settings_section_when_should_the_popup_appear' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-common-when-should-the-popup-appear',
			esc_html( 'Select Events for Customizing', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_when_should_the_popup_appear' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $prefix . 'when_should_the_popup_appear',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-n-seconds',
			esc_html( 'Popup Will Appear After N Second(s)', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $prefix . 'popup_will_appear_after_n_seconds',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '5',
				'required' => true,
			)
		);

		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-clicking-on-element',
			esc_html( 'Popup Will Appear After Clicking on the Given CSS Selector', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $prefix . 'popup_will_appear_after_clicking_on_element',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '#my-button, .entry .button',
				'required' => true,
			)
		);

		add_settings_field(
			SMP_PREFIX . '-event-hide-element-after-click-on-it',
			esc_html( 'Hide Element After Click on It', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $prefix . 'event_hide_element_after_click_on_it',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-do-not-use-cookies-after-click-on-element',
			esc_html( 'Do not Use Cookies After Click on Element', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $prefix . 'do_not_use_cookies_after_click_on_element',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-scrolling-down-n-percent',
			esc_html( 'Popup Will Appear After Scrolling Down at Least N Percent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $prefix . 'popup_will_appear_after_scrolling_down_n_percent',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '70',
				'required' => true,
			)
		);

		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-on-exit-intent',
			esc_html( 'Popup Will Appear On Exit-Intent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $prefix . 'popup_will_appear_on_exit_intent',
			)
		);

		add_settings_section(
			$section_who_should_see_the_popup,
			esc_html( 'Who Should See the Popup?', 'social-media-popup' ),
			array( & $this, 'settings_section_who_should_see_the_popup' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-who-should-see-the-popup',
			esc_html( 'Select Events for Customizing', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_who_should_see_the_popup' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $prefix . 'who_should_see_the_popup',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-visitor-opened-at-least-n-number-of-pages',
			esc_html( 'Visitor Opened at Least N Number of Pages', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $prefix . 'visitor_opened_at_least_n_number_of_pages',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '2',
				'required' => true,
			)
		);

		add_settings_field(
			SMP_PREFIX . '-visitor-registered-and-role-equals-to',
			esc_html( 'Registered Users Who Should See the Popup', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_visitor_registered_and_role_equals_to' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $prefix . 'visitor_registered_and_role_equals_to',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-display-after-n-days',
			esc_html( 'Display After N-days', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $prefix . 'setting_display_after_n_days',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '15',
				'required' => true,
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
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_general() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-tracking-general';
		$options_page = SMP_PREFIX . '-group-tracking-general';
		$section      = SMP_PREFIX . '-section-common-tracking-general';

		register_setting( $group, $prefix . 'use_events_tracking', 'absint' );
		register_setting( $group, $prefix . 'do_not_use_tracking_in_debug_mode', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'Events Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_common_events_tracking' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-common-use-events-tracking',
			esc_html( 'Use Events Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'use_events_tracking',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-common-do-not-use-tracking-in-debug-mode',
			esc_html( 'Do not use tracking in Debug mode', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'do_not_use_tracking_in_debug_mode',
			)
		);
	}

	/**
	 * Events tracking – Google Analytics
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_google_analytics() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-tracking-google-analytics';
		$options_page = SMP_PREFIX . '-group-tracking-google-analytics';
		$section      = SMP_PREFIX . '-section-common-tracking-google-analytics';

		register_setting( $group, $prefix . 'google_analytics_tracking_id', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'push_events_to_aquisition_social_plugins', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'Google Analytics', 'social-media-popup' ),
			array( & $this, 'settings_section_common_google_analytics' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-common-tracking-google-analytics-tracking-id',
			esc_html( 'Google Analytics Tracking ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'google_analytics_tracking_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'UA-12345678-0',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-push-events-to-aquisition-social-plugins',
			esc_html( 'Push events to Aquisition > Social > Plugins', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'push_events_to_aquisition_social_plugins',
			)
		);
	}

	/**
	 * Events tracking – Windows Events
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_window_events() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-tracking-window-events';
		$options_page = SMP_PREFIX . '-group-tracking-window-events';
		$section      = SMP_PREFIX . '-section-common-tracking-window-events';

		register_setting( $group, $prefix . 'push_events_when_displaying_window', 'absint' );
		register_setting( $group, $prefix . 'tracking_event_label_window_showed_immediately', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_window_showed_with_delay', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_window_showed_after_click', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_window_showed_on_scrolling_down', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_window_showed_on_exit_intent', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Window Events Descriptions', 'social-media-popup' ),
			array( & $this, 'settings_section_common_window_events_descriptions' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-push-events-when-displaying-the-window',
			esc_html( 'Push events when displaying the window', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'push_events_when_displaying_window',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-immediately',
			esc_html( 'Window showed immediately', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_window_showed_immediately',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show immediately', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-after-n-seconds',
			esc_html( 'Window showed after N seconds', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_window_showed_with_delay',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show after delay before it rendered', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-after-click',
			esc_html( 'Window showed after click on CSS-selector', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_window_showed_after_click',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show after click on CSS-selector', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-on-scrolling-down',
			esc_html( 'Window showed on scrolling down', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_window_showed_on_scrolling_down',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show after scrolling down', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-on-exit-intent',
			esc_html( 'Window showed on exit intent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_window_showed_on_exit_intent',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Show on exit intent', 'social-media-popup' ),
			)
		);
	}

	/**
	 * Events tracking – Social Events
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_social_events() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-tracking-social-events';
		$options_page = SMP_PREFIX . '-group-tracking-social-events';
		$section      = SMP_PREFIX . '-section-common-tracking-social-events';

		register_setting( $group, $prefix . 'push_events_when_subscribing_on_social_networks', 'absint' );
		register_setting( $group, $prefix . 'add_window_events_descriptions', 'absint' );
		register_setting( $group, $prefix . 'tracking_event_label_no_events_fired', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_on_delay', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_after_click', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_on_scrolling_down', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_event_label_on_exit_intent', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Social Networks Events Descriptions', 'social-media-popup' ),
			array( & $this, 'settings_section_common_multiple_events_descriptions' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-push-events-when-subscribing-on-social-networks',
			esc_html( 'Push events when subscribing on social networks', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'push_events_when_subscribing_on_social_networks',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-use-window-events-when-subscribing-on-social-networks',
			esc_html( 'Add window events descriptions', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'add_window_events_descriptions',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-no-events-fired',
			esc_html( 'If no events fired', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_no_events_fired',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( '(no events fired)', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-after-delay-before-show-widget',
			esc_html( 'When popup will appear after delay before show widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_on_delay',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'After delay before show widget', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-after-click-on-css-selector',
			esc_html( 'On click on CSS-selector', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_after_click',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'After click on CSS-selector', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-on-scrolling-down',
			esc_html( 'On window scrolling down', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_on_scrolling_down',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'On window scrolling down', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-on-exit-intent',
			esc_html( 'On exit intent', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_event_label_on_exit_intent',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'On exit intent', 'social-media-popup' ),
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Управление")
	 */
	public function init_settings_common_management() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-management';
		$options_page = SMP_PREFIX . '-group-management';
		$section      = SMP_PREFIX . '-section-common-management';

		register_setting( $group, $prefix . 'setting_remove_settings_on_uninstall' );

		add_settings_section(
			$section,
			esc_html( 'Management', 'social-media-popup' ),
			array( & $this, 'settings_section_common_management' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-common-remove-settings-on-uninstall',
			esc_html( 'Remove Settings On Uninstall Plugin', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_remove_settings_on_uninstall',
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
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-facebook-general';
		$options_page = SMP_PREFIX . '-group-facebook-general';
		$section      = SMP_PREFIX . '-section-facebook-general';

		register_setting( $group, $prefix . 'setting_use_facebook' );
		register_setting( $group, $prefix . 'setting_facebook_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_facebook_show_description' );
		register_setting( $group, $prefix . 'setting_facebook_description', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_facebook_application_id', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_facebook_page_url', 'esc_url' );
		register_setting( $group, $prefix . 'setting_facebook_locale', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_facebook_width', 'absint' );
		register_setting( $group, $prefix . 'setting_facebook_height', 'absint' );
		register_setting( $group, $prefix . 'setting_facebook_use_small_header', 'absint' );
		register_setting( $group, $prefix . 'setting_facebook_hide_cover' );
		register_setting( $group, $prefix . 'setting_facebook_show_facepile' );
		register_setting( $group, $prefix . 'setting_facebook_tabs', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_facebook_adapt_container_width', 'absint' );
		register_setting( $group, $prefix . 'setting_facebook_close_window_after_join', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'Facebook Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_facebook' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-use-facebook',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_facebook',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-tab-caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'Facebook',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-show-description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_show_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-application-id',
			esc_html( 'Application ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_application_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '123456789012345',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-page-url',
			esc_html( 'Facebook Page URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_page_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'https://www.facebook.com/gruz0.ru/',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-locale',
			esc_html( 'Facebook Locale', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_facebook_locale' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_locale',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-adapt-container-width',
			esc_html( 'Adapt to Plugin Container Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_adapt_container_width',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-use-small-header',
			esc_html( 'Use Small Header', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_use_small_header',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-hide-cover',
			esc_html( 'Hide cover photo in the header', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_hide_cover',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-show-facepile',
			esc_html( 'Show profile photos when friends like this', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_show_facepile',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-tabs',
			esc_html( 'Show Content from Tabs', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_facebook_tabs' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_tabs',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-facebook-close-window-after-join',
			esc_html( 'Close Plugin Window After Joining the Group', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_facebook_close_window_after_join',
			)
		);
	}

	/**
	 * Facebook Tracking settings
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by $this->init_settings_facebook()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_facebook_tracking() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-facebook-tracking';
		$options_page = SMP_PREFIX . '-group-facebook-tracking';
		$section      = SMP_PREFIX . '-section-facebook-tracking';

		register_setting( $group, $prefix . 'tracking_use_facebook', 'absint' );
		register_setting( $group, $prefix . 'tracking_facebook_subscribe_event', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_facebook_unsubscribe_event', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_facebook_tracking' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-use-facebook',
			esc_html( 'Use Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_use_facebook',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-facebook-subscribe-event',
			esc_html( 'Subscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_facebook_subscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Subscribe on Facebook', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-facebook-unsubscribe-event',
			esc_html( 'Unsubscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_facebook_unsubscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Unsubscribe from Facebook', 'social-media-popup' ),
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
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-vkontakte-general';
		$options_page = SMP_PREFIX . '-group-vkontakte-general';
		$section      = SMP_PREFIX . '-section-vkontakte-general';

		register_setting( $group, $prefix . 'setting_use_vkontakte' );
		register_setting( $group, $prefix . 'setting_vkontakte_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_vkontakte_show_description' );
		register_setting( $group, $prefix . 'setting_vkontakte_description', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_vkontakte_application_id', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_vkontakte_page_or_group_id', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_vkontakte_page_url', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_vkontakte_width', 'absint' );
		register_setting( $group, $prefix . 'setting_vkontakte_height', 'absint' );
		register_setting( $group, $prefix . 'setting_vkontakte_layout', 'absint' );
		register_setting( $group, $prefix . 'setting_vkontakte_color_background', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_vkontakte_color_text', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_vkontakte_color_button', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_vkontakte_close_window_after_join', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'VKontakte Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_vkontakte' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-use-vkontakte',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_vkontakte',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-tab-caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'VK', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-show-description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_show_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-application-id',
			esc_html( 'VKontakte Application ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_application_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '1234567',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-page-or-group-id',
			esc_html( 'VKontakte Page or Group ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_page_or_group_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '12345678',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-page-url',
			esc_html( 'VKontakte Page URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_page_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'https://vk.com/ru_wp',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-layout',
			esc_html( 'Layout', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_vkontakte_layout' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_layout',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-background',
			esc_html( 'Background Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_color_background',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-text',
			esc_html( 'Text Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_color_text',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-button',
			esc_html( 'Button Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_color_button',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-vkontakte-close-window-after-join',
			esc_html( 'Close Plugin Window After Joining the Group', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_vkontakte_close_window_after_join',
			)
		);
	}

	/**
	 * VK.com Tracking settings
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by $this->init_settings_vkontakte()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_vkontakte_tracking() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-vkontakte-tracking';
		$options_page = SMP_PREFIX . '-group-vkontakte-tracking';
		$section      = SMP_PREFIX . '-section-vkontakte-tracking';

		register_setting( $group, $prefix . 'tracking_use_vkontakte', 'absint' );
		register_setting( $group, $prefix . 'tracking_vkontakte_subscribe_event', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'tracking_vkontakte_unsubscribe_event', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_vkontakte_tracking' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-use-vkontakte',
			esc_html( 'Use Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_use_vkontakte',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-vkontakte-subscribe-event',
			esc_html( 'Subscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_vkontakte_subscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Subscribe on VK', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-vkontakte-unsubscribe-event',
			esc_html( 'Unsubscribe Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_vkontakte_unsubscribe_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Unsubscribe from VK', 'social-media-popup' ),
			)
		);
	}

	/**
	 * Настройки Одноклассников
	 */
	private function init_settings_odnoklassniki() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-odnoklassniki';
		$options_page = SMP_PREFIX . '_odnoklassniki_options';
		$section      = SMP_PREFIX . '-section-odnoklassniki';

		register_setting( $group, $prefix . 'setting_use_odnoklassniki' );
		register_setting( $group, $prefix . 'setting_odnoklassniki_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_odnoklassniki_show_description' );
		register_setting( $group, $prefix . 'setting_odnoklassniki_description', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_odnoklassniki_group_id', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_odnoklassniki_group_url', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_odnoklassniki_width', 'absint' );
		register_setting( $group, $prefix . 'setting_odnoklassniki_height', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'Odnoklassniki Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_odnoklassniki' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-use-odnoklassniki',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_odnoklassniki',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-tab-caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_odnoklassniki_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Odnoklassniki', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-show-description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_odnoklassniki_show_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_odnoklassniki_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-group-id',
			esc_html( 'Odnoklassniki Group ID', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_odnoklassniki_group_id',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '12345678901234',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-group-url',
			esc_html( 'Odnoklassniki Group URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_odnoklassniki_group_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'https://ok.ru/group/57122812461115',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_odnoklassniki_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_odnoklassniki_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);
	}

	/**
	 * Настройки Google+
	 */
	private function init_settings_googleplus() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-googleplus';
		$options_page = SMP_PREFIX . '_googleplus_options';
		$section      = SMP_PREFIX . '-section-googleplus';

		register_setting( $group, $prefix . 'setting_use_googleplus' );
		register_setting( $group, $prefix . 'setting_googleplus_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_googleplus_show_description' );
		register_setting( $group, $prefix . 'setting_googleplus_description', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_googleplus_page_url', 'esc_url' );
		register_setting( $group, $prefix . 'setting_googleplus_layout', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_googleplus_locale', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_googleplus_size', 'absint' );
		register_setting( $group, $prefix . 'setting_googleplus_theme', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_googleplus_show_cover_photo' );
		register_setting( $group, $prefix . 'setting_googleplus_show_tagline' );
		register_setting( $group, $prefix . 'setting_googleplus_page_type', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Google+ Community Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_googleplus' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-use-googleplus',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_googleplus',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-tab-caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'Google+',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-show-description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_show_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-page-type',
			esc_html( 'Google+ Page Type', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_page_type' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_page_type',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-page-url',
			esc_html( 'Google+ Page URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_page_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '//plus.google.com/u/0/117676776729232885815',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-layout',
			esc_html( 'Layout', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_layout' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_layout',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-locale',
			esc_html( 'Google+ Locale', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_locale' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_locale',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-size',
			esc_html( 'Widget Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_size',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-theme',
			esc_html( 'Google+ Theme', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_googleplus_theme' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_theme',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-show-cover-photo',
			esc_html( 'Show Cover Photo', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_show_cover_photo',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-googleplus-show-tagline',
			esc_html( 'Show Tagline', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_googleplus_show_tagline',
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
	 * @uses Social_Media_Popup::get_prefix()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_general() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-twitter-general';
		$options_page = SMP_PREFIX . '-group-twitter-general';
		$section      = SMP_PREFIX . '-section-twitter-general';

		register_setting( $group, $prefix . 'setting_use_twitter' );
		register_setting( $group, $prefix . 'setting_twitter_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_twitter_show_description' );
		register_setting( $group, $prefix . 'setting_twitter_description', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_twitter_username', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_twitter_locale', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_twitter_first_widget', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_twitter_close_window_after_join', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'Common Settings', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-use-twitter',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_twitter',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-tab-caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Twitter', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-show-description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_show_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-username',
			'@username',
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_username',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'gruz0',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-locale',
			esc_html( 'Twitter Locale', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_locale' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_locale',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-first-widget',
			esc_html( 'First widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_first_widget' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_first_widget',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-close-window-after-join',
			esc_html( 'Close Plugin Window After Joining the Group', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_close_window_after_join',
			)
		);
	}

	/**
	 * Twitter Follow Button settings
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_follow_button() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-twitter-follow-button';
		$options_page = SMP_PREFIX . '-group-twitter-follow-button';
		$section      = SMP_PREFIX . '-section-twitter-follow-button';

		register_setting( $group, $prefix . 'setting_twitter_use_follow_button', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_show_count', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_show_screen_name', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_follow_button_large_size', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_follow_button_align_by', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Follow Button Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter_follow_button' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-use-follow-button',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_use_follow_button',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-show-count',
			esc_html( 'Show Followers Count', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_show_count',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-show-screen-name',
			esc_html( 'Show Username', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_show_screen_name',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-follow-button-large-size',
			esc_html( 'Follow Button Large Size', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_follow_button_large_size',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-follow-button-align-by',
			esc_html( 'Follow Button Align', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_follow_button_align_by' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_follow_button_align_by',
			)
		);
	}

	/**
	 * Twitter Timeline settings
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_timeline() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-twitter-timeline';
		$options_page = SMP_PREFIX . '-group-twitter-timeline';
		$section      = SMP_PREFIX . '-section-twitter-timeline';

		register_setting( $group, $prefix . 'setting_twitter_use_timeline', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_theme', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_twitter_link_color', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_twitter_tweet_limit', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_show_replies', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_width', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_height', 'absint' );
		register_setting( $group, $prefix . 'setting_twitter_chrome', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Timeline Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter_timeline' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-use-timeline',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_use_timeline',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-theme',
			esc_html( 'Theme', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_theme' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_theme',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-link-color',
			esc_html( 'Link Color', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_link_color',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-tweet-limit',
			esc_html( 'Tweet Limit', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_tweet_limit',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '3',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-show-replies',
			esc_html( 'Show Replies', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_show_replies',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '400',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-twitter-chrome',
			esc_html( 'Chrome', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_twitter_chrome' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_twitter_chrome',
			)
		);
	}

	/**
	 * Twitter Tracking settings
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_tracking() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-twitter-tracking';
		$options_page = SMP_PREFIX . '-group-twitter-tracking';
		$section      = SMP_PREFIX . '-section-twitter-tracking';

		register_setting( $group, $prefix . 'tracking_use_twitter', 'absint' );
		register_setting( $group, $prefix . 'tracking_twitter_event', 'sanitize_text_field' );

		add_settings_section(
			$section,
			esc_html( 'Tracking', 'social-media-popup' ),
			array( & $this, 'settings_section_twitter_tracking' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-use-twitter',
			esc_html( 'Use Tracking', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_use_twitter',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-tracking-twitter-event',
			esc_html( 'Event Label', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'tracking_twitter_event',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Follow on Twitter', 'social-media-popup' ),
			)
		);
	}

	/**
	 * Настройки Pinterest
	 */
	private function init_settings_pinterest() {
		$prefix = self::get_prefix();

		$group        = SMP_PREFIX . '-group-pinterest';
		$options_page = SMP_PREFIX . '_pinterest_options';
		$section      = SMP_PREFIX . '-section-pinterest';

		register_setting( $group, $prefix . 'setting_use_pinterest' );
		register_setting( $group, $prefix . 'setting_pinterest_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_pinterest_show_description' );
		register_setting( $group, $prefix . 'setting_pinterest_description', 'wp_kses_post' );
		register_setting( $group, $prefix . 'setting_pinterest_profile_url', 'sanitize_text_field' );
		register_setting( $group, $prefix . 'setting_pinterest_image_width', 'absint' );
		register_setting( $group, $prefix . 'setting_pinterest_width', 'absint' );
		register_setting( $group, $prefix . 'setting_pinterest_height', 'absint' );

		add_settings_section(
			$section,
			esc_html( 'Pinterest Profile Widget', 'social-media-popup' ),
			array( & $this, 'settings_section_pinterest' ),
			$options_page
		);

		add_settings_field(
			SMP_PREFIX . '-use-pinterest',
			esc_html( 'Active', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_use_pinterest',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-pinterest-tab-caption',
			esc_html( 'Tab Caption', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_pinterest_tab_caption',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . __( 'Pinterest', 'social-media-popup' ),
			)
		);

		add_settings_field(
			SMP_PREFIX . '-pinterest-show-description',
			esc_html( 'Show Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_pinterest_show_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-pinterest-description',
			esc_html( 'Description Above The Widget', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_pinterest_description',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-pinterest-profile-url',
			esc_html( 'Profile URL', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_pinterest_profile_url',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . 'http://ru.pinterest.com/gruz0/',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-pinterest-image-width',
			esc_html( 'Image Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_pinterest_image_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '60',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-pinterest-width',
			esc_html( 'Widget Width', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_pinterest_width',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '380',
			)
		);

		add_settings_field(
			SMP_PREFIX . '-pinterest-height',
			esc_html( 'Widget Height', 'social-media-popup' ),
			array( 'SMP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $prefix . 'setting_pinterest_height',
				'placeholder' => __( 'Example: ', 'social-media-popup' ) . '300',
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
		add_dashboard_page(
			esc_html( 'Welcome To Social Media Popup Welcome Screen', 'social-media-popup' ),
			esc_html( 'Welcome To Social Media Popup Welcome Screen', 'social-media-popup' ),
			'read',
			SMP_PREFIX . '_about',
			array( & $this, 'plugin_welcome_screen' )
		);

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

		if ( absint( get_option( self::get_prefix() . 'setting_show_admin_bar_menu' ) ) !== 1 ) return;

		$args = array(
			'id'    => 'scp-admin-bar',
			'title' => 'Social Media Popup',
		);

		if ( absint( get_option( self::get_prefix() . 'setting_debug_mode' ) ) === 1 ) {
			$args['title']        .= ' – ' . esc_html( 'Debug Mode', 'social-media-popup' );
			$args['meta']['class'] = 'smp-debug-mode';
		}

		$wp_admin_bar->add_node( $args );

		$menu_scp_settings = array(
			'parent' => 'scp-admin-bar',
			'id'     => 'scp-settings',
			'title'  => esc_html( 'Settings', 'social-media-popup' ),
			'href'   => admin_url( 'admin.php?page=' . SMP_PREFIX ),
		);
		$wp_admin_bar->add_node( $menu_scp_settings );

		$menu_clear_cookies = array(
			'parent' => 'scp-admin-bar',
			'id'     => 'scp-clear-cookies',
			'title'  => esc_html( 'Clear Cookies', 'social-media-popup' ),
			'href'   => '#',
			'meta'   => array(
				'onclick' => 'smp_clearAllPluginCookies();return false;',
			),
		);
		$wp_admin_bar->add_node( $menu_clear_cookies );
	}

	/**
	 * Admin Head actions
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', SMP_PREFIX . '_about' );
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS на страницу настроек
	 *
	 * @since 0.7.3 Added add_cookies_script()
	 * @since 0.7.3 Added WP Color Picker script
	 * @since 0.7.5 Added custom CSS for quick-access menu
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @uses $this->add_cookies_script()
	 * @uses $this->js_asset_filename()
	 * @uses $this->css_asset_filename()
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$this->add_custom_css();

		if ( ! is_admin() ) return;

		$prefix  = self::get_prefix();
		$version = get_option( $prefix . 'version' );

		wp_register_style( SMP_PREFIX . '-admin-css', $this->css_asset_filename( 'admin', $version ) );
		wp_enqueue_style( SMP_PREFIX . '-admin-css' );

		wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-css' );

		wp_enqueue_script( 'jquery-ui-draggable', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-sortable', array( 'jquery' ) );

		$this->add_cookies_script( $version, $prefix );

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

		wp_enqueue_style( 'animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css' );
		wp_enqueue_script( SMP_PREFIX . '-admin-js' );
	}

	/**
	 * Add events tracking code to wp_footer()
	 *
	 * @since 0.7.5
	 *
	 * @uses Social_Media_Popup::get_prefix()
	 * @uses SMP_Template::render_google_analytics_tracking_code()
	 *
	 * @return mixed
	 */
	public function add_events_tracking_code() {
		$prefix = self::get_prefix();

		$use_events_tracking          = esc_attr( get_option( $prefix . 'use_events_tracking' ) ) === '1';
		$google_analytics_tracking_id = esc_attr( get_option( $prefix . 'google_analytics_tracking_id' ) );

		if ( ! $use_events_tracking ) {
			return false;
		}

		$content = '';

		$template = new SMP_Template();

		if ( ! empty( $google_analytics_tracking_id ) ) {
			$content .= $template->render_google_analytics_tracking_code( $google_analytics_tracking_id );
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $content;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Страница приветствия после установки плагина
	 */
	public function plugin_welcome_screen() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'social-media-popup' ) );
		}

		$prefix  = self::get_prefix();
		$version = get_option( $prefix . 'version' );

		include( SMP_TEMPLATES_DIR . 'welcome-screen.php' );
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
	 * @uses Social_Media_Popup::get_prefix()
	 * @uses $this->add_cookies_script()
	 * @uses $this->css_asset_filename()
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			$this->add_custom_css();
		}

		$prefix  = self::get_prefix();
		$version = get_option( $prefix . 'version' );

		$this->add_cookies_script( $version, $prefix );
		if ( is_smp_cookie_present() ) {
			$when_should_the_popup_appear = split_string_by_comma( get_option( $prefix . 'when_should_the_popup_appear' ) );

			if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_clicking_on_element' ) ) {
				$popup_will_appear_after_clicking_on_element = get_option( $prefix . 'popup_will_appear_after_clicking_on_element' );
				$do_not_use_cookies_after_click_on_element   = get_option( $prefix . 'do_not_use_cookies_after_click_on_element' );

				if ( empty( $popup_will_appear_after_clicking_on_element ) || 0 === absint( $do_not_use_cookies_after_click_on_element ) ) {
					return;
				}
			} else {
				return;
			}
		}

		$this->render_popup_window( $version, $prefix );

		wp_register_style( SMP_PREFIX . '-css', $this->css_asset_filename( 'styles', $version ) );
		wp_enqueue_style( SMP_PREFIX . '-css' );

		wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );

		if ( '1' === get_option( $prefix . 'setting_use_animation' ) ) {
			wp_enqueue_style( 'animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css' );
		}
	}

	/**
	 * Render popup
	 *
	 * @uses $this->js_asset_filename()
	 *
	 * @param string $version Plugin version
	 * @param string $prefix Plugin prefix
	 */
	private function render_popup_window( $version, $prefix ) {
		$content = SMP_Popup::render( $prefix );

		$encoded_content = preg_replace( "~[\n\r\t]~", '', $content );
		$encoded_content = base64_encode( $encoded_content );

		wp_register_script( SMP_PREFIX . '-js', $this->js_asset_filename( 'scripts', $version ), array( 'jquery' ) );
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
	 * @param string $prefix Plugin prefix
	 * @return void
	 */
	private function add_cookies_script( $version, $prefix ) {
		$messages = array(
			'clearCookiesMessage'           => esc_html( 'Page will be reload after clear cookies. Continue?', 'social-media-popup' ),
			'showWindowAfterReturningNDays' => absint( get_option( $prefix . 'setting_display_after_n_days' ) ),
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
		$prefix  = self::get_prefix();
		$options = array();

		$all_options = wp_load_alloptions();
		foreach ( $all_options as $name => $value ) {
			if ( preg_match( '/^' . $prefix . '/', $name ) ) {
				$name             = str_replace( $prefix, '', $name );
				$options[ $name ] = $value;
			}
		}

		$validator = new SMP_Validator( $options );
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
		return SMP_ASSETS_URL . "js/${part}.min.js?" . $version;
	}

	/**
	 * Generate CSS filename
	 *
	 * @used_by $this->enqueue_scripts()
	 * @used_by $this->admin_enqueue_scripts()
	 *
	 * @since 0.7.6
	 *
	 * @param string $part    Filename's part (eg. admin, styles, etc.)
	 * @param string $version Plugin's version
	 *
	 * @return string
	 */
	private function css_asset_filename( $part, $version ) {
		return SMP_ASSETS_URL . "css/${part}.min.css?" . $version;
	}
}
