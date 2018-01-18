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
require_once( SMP_INCLUDES_DIR . 'settings-field.php' );
require_once( SMP_INCLUDES_DIR . 'scp-template.php' );
require_once( SMP_INCLUDES_DIR . 'popup.php' );
require_once( SMP_INCLUDES_DIR . 'validator.php' );
require_once( SMP_INCLUDES_DIR . 'providers/providers.php' );

/**
 * Social Media Popup class
 */
class Social_Media_Popup {
	/**
	 * Plugin version
	 *
	 * @var string $scp_version
	 */
	protected static $scp_version;

	/**
	 * Конструктор
	 *
	 * @since 0.7.3 Changed action to wp_enqueue_scripts to add admin scripts
	 */
	public function __construct() {

		// Register action
		add_action( 'init', array( & $this, 'localization' ) );
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

		set_transient( '_scp_welcome_screen', true, 30 );

		self::upgrade();

		// Send message after activating plugin
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';
		wp_mail( 'gruz0.mail@gmail.com', 'SMP has been activated on ' . get_site_url() . '. Ver: ' . get_option( $version ), 'SMP Activated' );
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
		$scp_prefix = self::get_scp_prefix();

		if ( ! current_user_can( 'activate_plugins' ) ) return;
		if ( ! get_option( $scp_prefix . 'setting_remove_settings_on_uninstall' ) ) return;

		$options = array(
			// Очищаем версию плагина
			'version',

			// Общие настройки
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

			// Десктопные настройки
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

			// Мобильные настройки
			'setting_plugin_title_on_mobile_devices',
			'setting_icons_size_on_mobile_devices',

			// События
			'when_should_the_popup_appear',
			'popup_will_appear_after_n_seconds',
			'popup_will_appear_after_clicking_on_element',
			'popup_will_appear_after_scrolling_down_n_percent',
			'popup_will_appear_on_exit_intent',

			// Дополнительные опции событий
			'event_hide_element_after_click_on_it',
			'do_not_use_cookies_after_click_on_element',

			// Кому показывать окно
			'who_should_see_the_popup',
			'visitor_opened_at_least_n_number_of_pages',
			'visitor_registered_and_role_equals_to',

			// Отслеживание событий
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

			// ВКонтакте
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

			// Одноклассники
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
			delete_option( $scp_prefix . $options[ $idx ] );
		}
	}

	/**
	 * Set plugin version
	 *
	 * @param string $version Plugin version
	 */
	public static function set_scp_version( $version ) {
		self::$scp_version = $version;
	}

	/**
	 * Returns plugin prefix based on version
	 *
	 * @return string
	 */
	public static function get_scp_prefix() {
		if ( empty( self::$scp_version ) ) {
			$version = get_option( 'scp-version' );
			if ( empty( $version ) ) {
				$version = get_option( 'social-community-popup-version' );
				if ( empty( $version ) ) {
					self::set_scp_version( '0.1' );
				} else {
					self::set_scp_version( $version );
				}
			} else {
				self::set_scp_version( $version );
			}
		}

		if ( version_compare( self::$scp_version, '0.7.1', '>=' ) ) {
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
		update_option( self::get_scp_prefix() . 'setting_debug_mode', 1 );
	}

	/**
	 * Show admin notice if Debug mode is activated
	 *
	 * @since 0.7.6
	 */
	public function add_debug_mode_notice() {
	?>
		<div class="notice notice-warning">
			<p><?php
				echo __( 'Social Media Popup Debug Mode is activated!', L10N_SCP_PREFIX )
					. ' <a href="' . admin_url( 'admin.php?page=' . SMP_PREFIX ) . '">' . __( 'Deactivate Debug Mode', L10N_SCP_PREFIX ) . '</a>';
			?></p>
		</div>
	<?php
	}

	/**
	 * Reset SCP version
	 * Don't forget to reactivate all providers in plugin settings to prevent show PHP notices
	 *
	 * @since 0.7.4
	 */
	private static function reset_scp_version() {
		update_option( 'scp-version', '' );
		update_option( 'social-community-popup-version', '' );
	}

	/**
	 * Upgrade plugin to version 0.1
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_1() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		// Срабатывает только при инсталляции плагина
		if ( ! get_option( $version ) ) {
			update_option( $version, '0.1' );
			self::set_scp_version( '0.1' );
		}
	}

	/**
	 * Upgrade plugin to version 0.2
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_2() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.2' > get_option( $version ) ) {
			update_option( $scp_prefix . 'setting_display_after_n_days',             30 );
			update_option( $scp_prefix . 'setting_display_after_visiting_n_pages',   0 );
			update_option( $scp_prefix . 'setting_display_after_delay_of_n_seconds', 3 );

			update_option( $scp_prefix . 'setting_facebook_tab_caption',             __( 'Facebook', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'setting_facebook_application_id',          '277165072394537' );
			update_option( $scp_prefix . 'setting_facebook_page_url',                'https://www.facebook.com/AlexanderGruzov' );
			update_option( $scp_prefix . 'setting_facebook_locale',                  'ru_RU' );
			update_option( $scp_prefix . 'setting_facebook_width',                   400 );
			update_option( $scp_prefix . 'setting_facebook_height',                  300 );
			update_option( $scp_prefix . 'setting_facebook_show_header',             1 );
			update_option( $scp_prefix . 'setting_facebook_show_faces',              1 );
			update_option( $scp_prefix . 'setting_facebook_show_stream',             0 );

			update_option( $scp_prefix . 'setting_vkontakte_tab_caption',            __( 'VK', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'setting_vkontakte_page_or_group_id',       '64088617' );
			update_option( $scp_prefix . 'setting_vkontakte_width',                  400 );
			update_option( $scp_prefix . 'setting_vkontakte_height',                 400 );
			update_option( $scp_prefix . 'setting_vkontakte_color_background',       '#FFFFFF' );
			update_option( $scp_prefix . 'setting_vkontakte_color_text',             '#2B587A' );
			update_option( $scp_prefix . 'setting_vkontakte_color_button',           '#5B7FA6' );
			update_option( $scp_prefix . 'setting_vkontakte_close_window_after_join', 0 );

			update_option( $scp_prefix . 'setting_odnoklassniki_tab_caption',        __( 'Odnoklassniki', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'setting_odnoklassniki_group_id',           '57122812461115' );
			update_option( $scp_prefix . 'setting_odnoklassniki_width',              400 );
			update_option( $scp_prefix . 'setting_odnoklassniki_height',             260 );

			update_option( $version, '0.2' );
			self::set_scp_version( '0.2' );
		}
	}

	/**
	 * Upgrade plugin to version 0.3
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_3() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.3' > get_option( $version ) ) {
			update_option( $scp_prefix . 'setting_tabs_order', 'vkontakte,facebook,odnoklassniki' );
			update_option( $version, '0.3' );
			self::set_scp_version( '0.3' );
		}
	}

	/**
	 * Upgrade plugin to version 0.4
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_4() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.4' > get_option( $version ) ) {
			update_option( $version, '0.4' );
			self::set_scp_version( '0.4' );
		}
	}

	/**
	 * Upgrade plugin to version 0.5
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_5() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.5' > get_option( $version ) ) {
			// Добавляем Google+ в таблицу сортировки
			$tabs_order = get_option( $scp_prefix . 'setting_tabs_order' );
			$tabs_order = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'googleplus';
			$tabs_order = array_unique( $tabs_order );

			update_option( $scp_prefix . 'setting_tabs_order', join( ',', $tabs_order ) );

			// Добавляем новые системные опции
			update_option( $scp_prefix . 'setting_debug_mode',                       1 );
			update_option( $scp_prefix . 'setting_container_width',                  400 );
			update_option( $scp_prefix . 'setting_container_height',                 480 );

			// Добавляем настройки Google+
			update_option( $scp_prefix . 'setting_use_googleplus',                   0 );
			update_option( $scp_prefix . 'setting_googleplus_tab_caption',           __( 'Google+', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'setting_googleplus_show_description',      0 );
			update_option( $scp_prefix . 'setting_googleplus_description',           '' );
			update_option( $scp_prefix . 'setting_googleplus_page_url',              '//plus.google.com/u/0/117676776729232885815' );
			update_option( $scp_prefix . 'setting_googleplus_locale',                'ru' );
			update_option( $scp_prefix . 'setting_googleplus_size',                  400 );
			update_option( $scp_prefix . 'setting_googleplus_theme',                 'light' );
			update_option( $scp_prefix . 'setting_googleplus_show_cover_photo',      1 );
			update_option( $scp_prefix . 'setting_googleplus_show_tagline',          1 );

			// Обновим высоту контейнеров других социальных сетей
			update_option( $scp_prefix . 'setting_facebook_height',                  400 );
			update_option( $scp_prefix . 'setting_vkontakte_height',                 400 );
			update_option( $scp_prefix . 'setting_odnoklassniki_height',             400 );

			update_option( $version, '0.5' );
			self::set_scp_version( '0.5' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6' > get_option( $version ) ) {
			// Добавляем Twitter в таблицу сортировки
			$tabs_order = get_option( $scp_prefix . 'setting_tabs_order' );
			$tabs_order = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'twitter';
			$tabs_order = array_unique( $tabs_order );

			update_option( $scp_prefix . 'setting_tabs_order', join( ',', $tabs_order ) );

			// Добавляем настройки Twitter
			update_option( $scp_prefix . 'setting_use_twitter',                       0 );
			update_option( $scp_prefix . 'setting_twitter_tab_caption',               __( 'Twitter', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'setting_twitter_show_description',          0 );
			update_option( $scp_prefix . 'setting_twitter_description',               '' );
			update_option( $scp_prefix . 'setting_twitter_username',                  '' );
			update_option( $scp_prefix . 'setting_twitter_widget_id',                 '' );
			update_option( $scp_prefix . 'setting_twitter_theme',                     'light' );
			update_option( $scp_prefix . 'setting_twitter_link_color',                '#CC0000' );
			update_option( $scp_prefix . 'setting_twitter_tweet_limit',               5 );
			update_option( $scp_prefix . 'setting_twitter_show_replies',              0 );
			update_option( $scp_prefix . 'setting_twitter_width',                     400 );
			update_option( $scp_prefix . 'setting_twitter_height',                    400 );
			update_option( $scp_prefix . 'setting_twitter_chrome',                    '' );

			update_option( $version, '0.6' );
			self::set_scp_version( '0.6' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.1
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_1() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.1' > get_option( $version ) ) {
			// Добавлена настройка радиуса угла скругления границ
			update_option( $scp_prefix . 'setting_border_radius',                     10 );

			update_option( $version, '0.6.1' );
			self::set_scp_version( '0.6.1' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.2
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_2() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.2' > get_option( $version ) ) {
			// Добавлена настройка закрытия окна при нажатии на любой области экрана
			update_option( $scp_prefix . 'setting_close_popup_by_clicking_anywhere',  0 );

			// Добавлена настройка показывать виджет на мобильных устройствах или нет
			update_option( $scp_prefix . 'setting_show_on_mobile_devices',            0 );

			update_option( $version, '0.6.2' );
			self::set_scp_version( '0.6.2' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.3
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_3() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.3' > get_option( $version ) ) {
			// У виджета LikeBox в Facebook обновился интерфейс создания, поэтому адаптируем настройки
			$facebook_show_header = absint( get_option( $scp_prefix . 'setting_facebook_show_header' ) );
			update_option( $scp_prefix . 'setting_facebook_hide_cover', ( $facebook_show_header ? '1' : '' ) );
			unset( $facebook_show_header );

			$facebook_show_faces = get_option( $scp_prefix . 'setting_facebook_show_faces' );
			update_option( $scp_prefix . 'setting_facebook_show_facepile', $facebook_show_faces );
			unset( $facebook_show_faces );

			$facebook_show_stream = get_option( $scp_prefix . 'setting_facebook_show_stream' );
			update_option( $scp_prefix . 'setting_facebook_show_posts', $facebook_show_stream );
			unset( $facebook_show_stream );

			$facebook_remove_options = array(
				'setting_facebook_show_header',
				'setting_facebook_show_faces',
				'setting_facebook_show_stream',
				'setting_facebook_show_border',
			);

			for ( $idx = 0, $size = count( $facebook_remove_options ); $idx < $size; $idx++ ) {
				delete_option( $scp_prefix . $facebook_remove_options[ $idx ] );
			}

			unset( $facebook_remove_options );

			update_option( $version, '0.6.3' );
			self::set_scp_version( '0.6.3' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.4
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_4() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.4' > get_option( $version ) ) {
			update_option( $version, '0.6.4' );
			self::set_scp_version( '0.6.4' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.5
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_5() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.5' > get_option( $version ) ) {
			update_option( $scp_prefix . 'setting_googleplus_page_type',              'person' );

			update_option( $version, '0.6.5' );
			self::set_scp_version( '0.6.5' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.6
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_6() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.6' > get_option( $version ) ) {
			// Скрывать виджет при нажатии на Esc или нет
			update_option( $scp_prefix . 'setting_close_popup_when_esc_pressed',      0 );

			// Добавляем кастомную задержку перед отрисовкой виджета ВКонтакте
			update_option( $scp_prefix . 'setting_vkontakte_delay_before_render',     500 );

			update_option( $version, '0.6.6' );
			self::set_scp_version( '0.6.6' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.7
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_7() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.7' > get_option( $version ) ) {
			// Надпись над табами плагина
			update_option( $scp_prefix . 'setting_plugin_title',
				'<div style="text-align: center;font: bold normal 14pt/16pt Arial">'
				. __( 'Follow Us on Social Media!', L10N_SCP_PREFIX )
				. '</div>'
			);

			// Скрывать панель табов, если выбрана только одна соц. сеть
			update_option( $scp_prefix . 'setting_hide_tabs_if_one_widget_is_active',  1 );

			// Кнопка "Спасибо, я уже с вами"
			update_option( $scp_prefix . 'setting_show_button_to_close_widget',        1 );
			update_option( $scp_prefix . 'setting_button_to_close_widget_title',       __( "Thanks! Please don't show me popup.", L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'setting_button_to_close_widget_style',       'link' );

			update_option( $version, '0.6.7' );
			self::set_scp_version( '0.6.7' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.8
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_8() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.8' > get_option( $version ) ) {
			// Добавляем Pinterest в таблицу сортировки
			$tabs_order = get_option( $scp_prefix . 'setting_tabs_order' );
			$tabs_order = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'pinterest';
			$tabs_order = array_unique( $tabs_order );

			update_option( $scp_prefix . 'setting_tabs_order', join( ',', $tabs_order ) );

			// Добавляем виджет Pinterest Profile
			update_option( $scp_prefix . 'setting_use_pinterest',                      0 );
			update_option( $scp_prefix . 'setting_pinterest_tab_caption',              __( 'Pinterest', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'setting_pinterest_show_description',         0 );
			update_option( $scp_prefix . 'setting_pinterest_description',              '' );
			update_option( $scp_prefix . 'setting_pinterest_profile_url',              'http://ru.pinterest.com/gruz0/' );
			update_option( $scp_prefix . 'setting_pinterest_image_width',              60 );
			update_option( $scp_prefix . 'setting_pinterest_width',                    380 );
			update_option( $scp_prefix . 'setting_pinterest_height',                   300 );

			update_option( $version, '0.6.8' );
			self::set_scp_version( '0.6.8' );
		}
	}

	/**
	 * Upgrade plugin to version 0.6.9
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_6_9() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.9' > get_option( $version ) ) {
			// Добавляем цвет фоновой заливки родительского контейнера
			update_option( $scp_prefix . 'setting_overlay_color',                      '#000000' );

			// Добавляем степень прозрачности фоновой заливки родительского контейнера
			update_option( $scp_prefix . 'setting_overlay_opacity',                    80 );

			// Добавляем опцию выбора местоположения верхней кнопки закрытия окна: внутри или вне контейнера
			update_option( $scp_prefix . 'setting_show_close_button_in',               'inside' );

			// Добавляем опцию выравнивания табов по центру (было только слева)
			update_option( $scp_prefix . 'setting_align_tabs_to_center',               0 );

			// Добавляем опцию задержки перед показом кнопки закрытия виджета в подвале
			update_option( $scp_prefix . 'setting_delay_before_show_bottom_button',    0 );

			// Добавляем возможность загрузки фонового изображения для виджета
			update_option( $scp_prefix . 'setting_background_image',                   '' );

			update_option( $version, '0.6.9' );
			self::set_scp_version( '0.6.9' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.0
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_0() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.7.0' > get_option( $version ) ) {
			// При наступлении каких событий показывать всплывающее окно
			add_option( $scp_prefix . 'when_should_the_popup_appear',               '' );

			// Сохраним старое значение и переименуем опцию в более читаемый вариант
			$old_value = get_option( $scp_prefix . 'setting_display_after_delay_of_n_seconds' );
			delete_option( $scp_prefix . 'setting_display_after_delay_of_n_seconds' );
			add_option( $scp_prefix . 'popup_will_appear_after_n_seconds',          $old_value );

			// Отображение окна при клике на CSS-селектор
			add_option( $scp_prefix . 'popup_will_appear_after_clicking_on_element', '' );

			update_option( $version, '0.7.0' );
			self::set_scp_version( '0.7.0' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.1
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_1() {
		$old_scp_prefix = self::get_scp_prefix();
		$old_version    = $old_scp_prefix . 'version';
		$new_scp_prefix = 'scp-';

		if ( '0.7.1' > get_option( $old_version ) ) {
			$scp_options = array();

			$all_options = wp_load_alloptions();
			foreach ( $all_options as $name => $value ) {
				if ( preg_match( '/^' . $old_scp_prefix . '/', $name ) ) $scp_options[ $name ] = $value;
			}

			// Укоротим префикс опций до четырёх символов, иначе длинные названия опций не вмещаются в таблицу
			foreach ( $scp_options as $option_name => $value ) {
				$new_option_name = preg_replace( '/^' . $old_scp_prefix . '/', '', $option_name );

				delete_option( $option_name );
				delete_option( $new_scp_prefix . $new_option_name );

				if ( ! add_option( $new_scp_prefix . $new_option_name, $value ) ) {
					var_dump( $new_scp_prefix . $new_option_name );
					var_dump( $value );
					die();
				}
			}

			// Переименуем опцию в правильное название, т.к. из-за длинного прошлого префикса были ошибки
			$old_value  = get_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_eleme' );
			$old_value2 = get_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_element' );
			delete_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_eleme' );
			delete_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_element' );

			if ( ! add_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_element', ( $old_value ? $old_value : $old_value2 ) ) ) {
				var_dump( $new_scp_prefix . 'popup_will_appear_after_clicking_on_eleme' );
				var_dump( $value );
				die();
			}

			// Отображение окна при прокрутке документа на N процентов
			add_option( $new_scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent', '70' );

			// Отображение окна при перемещении мыши за пределы окна
			add_option( $new_scp_prefix . 'popup_will_appear_on_exit_intent',                  0 );

			// Кому показывать окно плагина
			add_option( $new_scp_prefix . 'who_should_see_the_popup',                          '' );

			// Сохраним старое значение и переименуем опцию в более читаемый вариант
			$old_value = get_option( $new_scp_prefix . 'setting_display_after_visiting_n_pages' );
			delete_option( $new_scp_prefix . 'setting_display_after_visiting_n_pages' );
			add_option( $new_scp_prefix . 'visitor_opened_at_least_n_number_of_pages',          $old_value );

			update_option( $new_scp_prefix . 'version', '0.7.1' );
			self::set_scp_version( '0.7.1' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.2
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_2() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.7.2' > get_option( $version ) ) {
			// Добавляем новое свойство "Adapt to plugin container width" в виджет Facebook
			add_option( $scp_prefix . 'setting_facebook_adapt_container_width',            1 );

			// Добавляем новое свойство "Use small header" в виджет Facebook
			add_option( $scp_prefix . 'setting_facebook_use_small_header',                 0 );

			// Сохраним старое значение "Show Posts" и используем его в новой опции "Tabs"
			$old_value = get_option( $scp_prefix . 'setting_facebook_show_posts' );
			$new_value = '1' === $old_value ? 'timeline' : '';

			delete_option( $scp_prefix . 'setting_facebook_show_posts' );
			add_option( $scp_prefix . 'setting_facebook_tabs',                             $new_value );

			update_option( $version, '0.7.2' );
			self::set_scp_version( '0.7.2' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.3
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_3() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.7.3' > get_option( $version ) ) {
			// Добавляем новое свойство "ВКонтакте ID приложения" в виджет ВКонтакте
			add_option( $scp_prefix . 'setting_vkontakte_application_id',                  '' );

			// Добавляем новое свойство "Закрывать окно после вступления в группу" в виджет ВКонтакте
			add_option( $scp_prefix . 'setting_vkontakte_close_window_after_join',         0 );

			update_option( $version, '0.7.3' );
			self::set_scp_version( '0.7.3' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.4
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_4() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.7.4' > get_option( $version ) ) {
			// Добавляем новое свойство "Пользователям с какими ролями показывать виджет"
			add_option( $scp_prefix . 'visitor_registered_and_role_equals_to',              'all' );

			// Добавляем новое свойство "Закрывать окно после вступления в группу" в виджет Facebook
			add_option( $scp_prefix . 'setting_facebook_close_window_after_join',           0 );

			// Добавляем новое свойство "Адрес группы ВКонтакте" в виджет ВКонтакте
			add_option( $scp_prefix . 'setting_vkontakte_page_url',                         'https://vk.com/ru_wp' );

			// Добавляем новое свойство "Адрес группы Одноклассники" в виджет Одноклассников
			add_option( $scp_prefix . 'setting_odnoklassniki_group_url',                    'https://ok.ru/group/57122812461115' );

			// Добавляем новое свойство "Заголовок главного окна" для мобильных устройств
			add_option( $scp_prefix . 'setting_plugin_title_on_mobile_devices',             __( 'Follow Us on Social Media!', L10N_SCP_PREFIX ) );

			// Добавляем новое свойство "Скрывать элемент после клика на него"
			add_option( $scp_prefix . 'event_hide_element_after_click_on_it',               0 );

			// Добавляем новое свойство "Размер иконок" для мобильных устройств
			add_option( $scp_prefix . 'setting_icons_size_on_mobile_devices',               '2x' );

			// Добавляем новое свойство "Использовать иконки вместо надписей" для десктопов
			add_option( $scp_prefix . 'setting_use_icons_instead_of_labels_in_tabs',        0 );

			// Добавляем новое свойство "Отображать меню быстрого доступа"
			add_option( $scp_prefix . 'setting_show_admin_bar_menu',                        1 );

			// Добавляем новое свойство "Размер иконок" для десктопов
			add_option( $scp_prefix . 'setting_icons_size_on_desktop',                      '2x' );

			update_option( $version, '0.7.4' );
			self::set_scp_version( '0.7.4' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.5
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_5() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.7.5' > get_option( $version ) ) {
			// Добавляем свойство "Макет" в виджет Google+
			update_option( $scp_prefix . 'setting_googleplus_layout',                       'portrait' );

			// Добавляем свойство "Закрывать окно после подписки" для Twitter
			update_option( $scp_prefix . 'setting_twitter_close_window_after_join',         0 );

			update_option( $scp_prefix . 'setting_twitter_use_follow_button',               1 );
			update_option( $scp_prefix . 'setting_twitter_show_count',                      1 );
			update_option( $scp_prefix . 'setting_twitter_show_screen_name',                1 );
			update_option( $scp_prefix . 'setting_twitter_follow_button_large_size',        1 );
			update_option( $scp_prefix . 'setting_twitter_follow_button_align_by',          'center' );
			update_option( $scp_prefix . 'setting_twitter_use_timeline',                    1 );
			delete_option( $scp_prefix . 'setting_twitter_widget_id' );

			// Убираем кастомную задержку перед отрисовкой виджета ВКонтакте
			delete_option( $scp_prefix . 'setting_vkontakte_delay_before_render' );

			// Опции трекинга событий
			update_option( $scp_prefix . 'use_events_tracking',                             1 );
			update_option( $scp_prefix . 'do_not_use_tracking_in_debug_mode',               1 );
			update_option( $scp_prefix . 'google_analytics_tracking_id',                    '' );
			update_option( $scp_prefix . 'push_events_to_aquisition_social_plugins',        1 );
			update_option( $scp_prefix . 'push_events_when_displaying_window',              1 );
			update_option( $scp_prefix . 'push_events_when_subscribing_on_social_networks', 1 );
			update_option( $scp_prefix . 'add_window_events_descriptions',                  1 );

			// Трекинг событий социальных сетей
			update_option( $scp_prefix . 'tracking_use_twitter',                            1 );
			update_option( $scp_prefix . 'tracking_twitter_event',                          __( 'Follow on Twitter', L10N_SCP_PREFIX ) );

			update_option( $scp_prefix . 'tracking_use_vkontakte',                          1 );
			update_option( $scp_prefix . 'tracking_vkontakte_subscribe_event',              __( 'Subscribe on VK.com', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_vkontakte_unsubscribe_event',            __( 'Unsubscribe from VK.com', L10N_SCP_PREFIX ) );

			update_option( $scp_prefix . 'tracking_use_facebook',                           1 );
			update_option( $scp_prefix . 'tracking_facebook_subscribe_event',               __( 'Subscribe on Facebook', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_facebook_unsubscribe_event',             __( 'Unsubscribe from Facebook', L10N_SCP_PREFIX ) );

			// Описания событий для отправки в Google Analytics
			update_option( $scp_prefix . 'tracking_event_label_window_showed_immediately',       __( 'Show immediately', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_window_showed_with_delay',        __( 'Show after delay before it rendered', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_window_showed_after_click',       __( 'Show after click on CSS-selector', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_window_showed_on_scrolling_down', __( 'Show after scrolling down', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_window_showed_on_exit_intent',    __( 'Show on exit intent', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_no_events_fired',                 __( '(no events fired)', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_on_delay',                        __( 'After delay before show widget', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_after_click',                     __( 'After click on CSS-selector', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_on_scrolling_down',               __( 'On scrolling down', L10N_SCP_PREFIX ) );
			update_option( $scp_prefix . 'tracking_event_label_on_exit_intent',                  __( 'On exit intent', L10N_SCP_PREFIX ) );

			// Добавляем новое свойство "Не учитывать куки при клике на элемент"
			update_option( $scp_prefix . 'do_not_use_cookies_after_click_on_element',            1 );

			update_option( $version, '0.7.5' );
			self::set_scp_version( '0.7.5' );
		}
	}

	/**
	 * Upgrade plugin to version 0.7.6
	 *
	 * @uses self::get_scp_prefix()
	 * @uses self::set_scp_version()
	 * @used_by self::upgrade()
	 */
	public static function upgrade_to_0_7_6() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.7.6' > get_option( $version ) ) {
			// Twitter
			update_option( $scp_prefix . 'setting_twitter_locale',       'ru' );
			update_option( $scp_prefix . 'setting_twitter_first_widget', 'follow_button' );

			// Animation
			update_option( $scp_prefix . 'setting_use_animation',        1 );
			update_option( $scp_prefix . 'setting_animation_style',      'bounce' );

			update_option( $version, '0.7.6' );
			self::set_scp_version( '0.7.6' );
		}
	}

	/**
	 * Подключаем локализацию к плагину
	 */
	public function localization() {
		load_plugin_textdomain( L10N_SCP_PREFIX, false, SMP_PLUGIN_DIRNAME . 'languages' );
	}

	/**
	 * Hook into WP's admin_init action hook
	 *
	 * @uses $this->init_settings()
	 */
	public function admin_init() {
		$this->init_settings();

		$scp_prefix = self::get_scp_prefix();
		if ( 1 === absint( get_option( $scp_prefix . 'setting_debug_mode' ) ) ) {
			add_action( 'admin_notices', array( $this, 'add_debug_mode_notice' ) );
		}

		if ( ! get_transient( '_scp_welcome_screen' ) ) return;
		delete_transient( '_scp_welcome_screen' );
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
	 * @uses self::get_scp_prefix()
	 */
	public function init_settings_common() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-general';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-general';

		// ID секции
		$section = SMP_PREFIX . '-section-common';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_debug_mode' );
		register_setting( $group, $scp_prefix . 'setting_tabs_order', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_close_popup_by_clicking_anywhere', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_close_popup_when_esc_pressed', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_show_on_mobile_devices', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_show_admin_bar_menu', 'absint' );

		add_settings_section(
			$section,
			__( 'Common Settings', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common' ),
			$options_page
		);

		// Активен плагин или нет
		add_settings_field(
			SMP_PREFIX . '-common-debug-mode',
			__( 'Debug Mode', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_debug_mode',
			)
		);

		// Порядок вывода закладок соц. сетей
		add_settings_field(
			SMP_PREFIX . '-common-tabs-order',
			__( 'Tabs Order', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_tabs_order' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_tabs_order',
			)
		);

		// Скрывать окно при нажатии на любой области экрана
		add_settings_field(
			SMP_PREFIX . '-common-close-popup-by-clicking-anywhere',
			__( 'Close the popup by clicking anywhere on the screen', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_close_popup_by_clicking_anywhere',
			)
		);

		// Скрывать окно при нажатии на Escape
		add_settings_field(
			SMP_PREFIX . '-common-close-popup-when-esc-pressed',
			__( 'Close the popup when ESC pressed', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_close_popup_when_esc_pressed',
			)
		);

		// Показывать виджет на мобильных устройствах
		add_settings_field(
			SMP_PREFIX . '-common-show-on-mobile-devices',
			__( 'Show widget on mobile devices', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_on_mobile_devices',
			)
		);

		// Показывать меню в Админ баре
		add_settings_field(
			SMP_PREFIX . '-common-show-admin-bar-menu',
			__( 'Show Plugin Menu in Admin Bar', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_admin_bar_menu',
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид")
	 *
	 * @uses self::get_scp_prefix()
	 */
	public function init_settings_common_view_on_deskop() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-view';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-view';

		// ID секции
		$section = SMP_PREFIX . '-section-common-view';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_plugin_title', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_use_animation', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_animation_style', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_use_icons_instead_of_labels_in_tabs', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_icons_size_on_desktop', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_hide_tabs_if_one_widget_is_active', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_container_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_container_height', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_border_radius', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_show_close_button_in', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_show_button_to_close_widget', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_button_to_close_widget_title', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_button_to_close_widget_style', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_delay_before_show_bottom_button', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_overlay_color', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_overlay_opacity', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_align_tabs_to_center', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_background_image', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'View', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_view_on_desktop' ),
			$options_page
		);

		// Заголовок окна плагина
		add_settings_field(
			SMP_PREFIX . '-common-plugin-title',
			__( 'Widget Title', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_plugin_title',
			)
		);

		// Использовать анимацию окна или нет
		add_settings_field(
			SMP_PREFIX . '-common-use-animation',
			__( 'Use Animation', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_animation',
			)
		);

		// Стиль анимации окна
		add_settings_field(
			SMP_PREFIX . '-common-animation-style',
			__( 'Animation Style', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_animation_style' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_animation_style',
			)
		);

		// Использовать иконки вместо надписей на табах
		add_settings_field(
			SMP_PREFIX . '-common-use-icons-instead-of-labels-in-tabs',
			__( 'Use Icons Instead of Labels in Tabs', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_icons_instead_of_labels_in_tabs',
			)
		);

		// Размер иконок социальных сетей
		add_settings_field(
			SMP_PREFIX . '-common-icons-size-on-desktop',
			__( 'Icons Size', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_icons_size_on_desktop',
			)
		);

		// Скрывать панель табов, если активна только одна соц. сеть
		add_settings_field(
			SMP_PREFIX . '-common-hide-tabs-if-one-widget-is-active',
			__( 'Hide Tabs if One Widget is Active', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_hide_tabs_if_one_widget_is_active',
			)
		);

		// Отцентрировать табы
		add_settings_field(
			SMP_PREFIX . '-common-align-tabs-to-center',
			__( 'Align Tabs to Center', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_align_tabs_to_center',
			)
		);

		// Показывать кнопку закрытия окна в заголовке в контейнере или вне его
		add_settings_field(
			SMP_PREFIX . '-common-show-close-button-in',
			__( 'Show Close Button in Title', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_show_close_button_in' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_close_button_in',
			)
		);

		// Показывать кнопку "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-show-button-to-close-widget',
			__( 'Show Button to Close Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_button_to_close_widget',
			)
		);

		// Надпись на кнопке "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-button-to-close-widget-title',
			__( 'Button to Close Widget Title', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_button_to_close_widget_title',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( "Thanks! Please don't show me popup.", L10N_SCP_PREFIX ),
			)
		);

		// Стиль кнопки "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-button-to-close-widget-style',
			__( 'Button to Close Widget Style', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_button_to_close_widget_style' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_button_to_close_widget_style',
			)
		);

		// Задержка перед отображением кнопки "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-delay-before-show-button-to-close-widget',
			__( 'Delay Before Show Button to Close Widget (sec.)', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_delay_before_show_bottom_button',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '10',
			)
		);

		// Ширина основного контейнера
		add_settings_field(
			SMP_PREFIX . '-common-container-width',
			__( 'Container Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_container_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Высота основного контейнера
		add_settings_field(
			SMP_PREFIX . '-common-container-height',
			__( 'Container Height', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_container_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Радиус скругления границ
		add_settings_field(
			SMP_PREFIX . '-common-border-radius',
			__( 'Border Radius', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_border_radius',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '10',
			)
		);

		// Цвет фоновой заливки родительского контейннера
		add_settings_field(
			SMP_PREFIX . '-common-overlay-color',
			__( 'Overlay Color', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_overlay_color',
			)
		);

		// Уровень прозрачности фоновой заливки родительского контейннера
		add_settings_field(
			SMP_PREFIX . '-common-overlay-opacity',
			__( 'Overlay Opacity', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_overlay_opacity',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '80',
			)
		);

		// Загрузка фонового изображения виджета
		add_settings_field(
			SMP_PREFIX . '-common-background-image',
			__( 'Widget Background Image', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_background_image' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_background_image',
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид (мобильные устройства)")
	 *
	 * @since 0.7.4
	 *
	 * @uses self::get_scp_prefix()
	 *
	 * @return void
	 */
	public function init_settings_common_view_on_mobile_devices() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-view-mobile';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-view-mobile';

		// ID секции
		$section = SMP_PREFIX . '-section-common-view-mobile';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_plugin_title_on_mobile_devices', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_icons_size_on_mobile_devices', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'View (Mobile Devices)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_view_on_mobile_devices' ),
			$options_page
		);

		// Заголовок окна плагина
		add_settings_field(
			SMP_PREFIX . '-common-plugin-title-on-mobile-devices',
			__( 'Widget Title', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_plugin_title_on_mobile_devices',
			)
		);

		// Размер иконок социальных сетей
		add_settings_field(
			SMP_PREFIX . '-common-icons-size-on-mobile-devices',
			__( 'Icons Size', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_icons_size_on_mobile_devices',
			)
		);
	}

	/**
	 * Общие настройки (вкладка "События")
	 *
	 * @uses self::get_scp_prefix()
	 */
	public function init_settings_common_events() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-events';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-events';

		// ID секций настроек
		$section_when_should_the_popup_appear = SMP_PREFIX . '-section-when-should-the-popup-appear';
		$section_who_should_see_the_popup     = SMP_PREFIX . '-section-who-should-see-the-popup';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'when_should_the_popup_appear', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_after_n_seconds', 'absint' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_after_clicking_on_element', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent', 'absint' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_on_exit_intent', 'absint' );
		register_setting( $group, $scp_prefix . 'who_should_see_the_popup', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'visitor_opened_at_least_n_number_of_pages', 'absint' );
		register_setting( $group, $scp_prefix . 'visitor_registered_and_role_equals_to', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_display_after_n_days', 'absint' );
		register_setting( $group, $scp_prefix . 'event_hide_element_after_click_on_it', 'absint' );
		register_setting( $group, $scp_prefix . 'do_not_use_cookies_after_click_on_element', 'absint' );

		add_settings_section(
			$section_when_should_the_popup_appear,
			__( 'When Should the Popup Appear?', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_when_should_the_popup_appear' ),
			$options_page
		);

		// При наступлении каких событий показывать окно
		add_settings_field(
			SMP_PREFIX . '-common-when-should-the-popup-appear',
			__( 'Select Events for Customizing', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_when_should_the_popup_appear' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'when_should_the_popup_appear',
			)
		);

		// Отображение окна после задержки N секунд
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-n-seconds',
			__( 'Popup Will Appear After N Second(s)', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_n_seconds',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '5',
			)
		);

		// Отображение окна при клике на CSS-селектор
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-clicking-on-element',
			__( 'Popup Will Appear After Clicking on the Given CSS Selector', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_clicking_on_element',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '#my-button, .entry .button',
			)
		);

		// Скрывать элемент, вызвавший открытие окна, после клика на него
		add_settings_field(
			SMP_PREFIX . '-event-hide-element-after-click-on-it',
			__( 'Hide Element After Click on It', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'event_hide_element_after_click_on_it',
			)
		);

		// Не учитывать куки при клике на элемент
		add_settings_field(
			SMP_PREFIX . '-do-not-use-cookies-after-click-on-element',
			__( 'Do not Use Cookies After Click on Element', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'do_not_use_cookies_after_click_on_element',
			)
		);

		// Отображение окна при прокрутке страницы на N процентов
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-scrolling-down-n-percent',
			__( 'Popup Will Appear After Scrolling Down at Least N Percent', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '70',
			)
		);

		// Отображение окна при перемещении мыши за границы окна
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-on-exit-intent',
			__( 'Popup Will Appear On Exit-Intent', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_on_exit_intent',
			)
		);

		add_settings_section(
			$section_who_should_see_the_popup,
			__( 'Who Should See the Popup?', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_who_should_see_the_popup' ),
			$options_page
		);

		// Кому показывать окно плагина
		add_settings_field(
			SMP_PREFIX . '-who-should-see-the-popup',
			__( 'Select Events for Customizing', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_who_should_see_the_popup' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'who_should_see_the_popup',
			)
		);

		// Отображение окна после просмотра N страниц на сайте
		add_settings_field(
			SMP_PREFIX . '-visitor-opened-at-least-n-number-of-pages',
			__( 'Visitor Opened at Least N Number of Pages', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'visitor_opened_at_least_n_number_of_pages',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '2',
			)
		);

		// Отображение окна авторизованным пользователям
		add_settings_field(
			SMP_PREFIX . '-visitor-registered-and-role-equals-to',
			__( 'Registered Users Who Should See the Popup', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_visitor_registered_and_role_equals_to' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'visitor_registered_and_role_equals_to',
			)
		);

		// Повторный показ окна через N дней
		add_settings_field(
			SMP_PREFIX . '-common-display-after-n-days',
			__( 'Display After N-days', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'setting_display_after_n_days',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '15',
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
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_general() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-tracking-general';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-tracking-general';

		// ID секции
		$section = SMP_PREFIX . '-section-common-tracking-general';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'use_events_tracking', 'absint' );
		register_setting( $group, $scp_prefix . 'do_not_use_tracking_in_debug_mode', 'absint' );

		add_settings_section(
			$section,
			__( 'Events Tracking', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_events_tracking' ),
			$options_page
		);

		// Use Events Tracking checkbox
		add_settings_field(
			SMP_PREFIX . '-common-use-events-tracking',
			__( 'Use Events Tracking', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'use_events_tracking',
			)
		);

		// Don't use tracking in debug mode
		add_settings_field(
			SMP_PREFIX . '-common-do-not-use-tracking-in-debug-mode',
			__( 'Do not use tracking in Debug mode', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'do_not_use_tracking_in_debug_mode',
			)
		);
	}

	/**
	 * Events tracking – Google Analytics
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_google_analytics() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-tracking-google-analytics';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-tracking-google-analytics';

		// ID секции
		$section = SMP_PREFIX . '-section-common-tracking-google-analytics';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'google_analytics_tracking_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'push_events_to_aquisition_social_plugins', 'absint' );

		add_settings_section(
			$section,
			__( 'Google Analytics', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_google_analytics' ),
			$options_page
		);

		// Google Analytics Tracking ID
		add_settings_field(
			SMP_PREFIX . '-common-tracking-google-analytics-tracking-id',
			__( 'Google Analytics Tracking ID', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'google_analytics_tracking_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'UA-12345678-0',
			)
		);

		// Отправка событий в "Источники трафика" > "Соцфункции" > "Плагины"
		add_settings_field(
			SMP_PREFIX . '-push-events-to-aquisition-social-plugins',
			__( 'Push events to Aquisition > Social > Plugins', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'push_events_to_aquisition_social_plugins',
			)
		);
	}

	/**
	 * Events tracking – Windows Events
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_window_events() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-tracking-window-events';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-tracking-window-events';

		// ID секции
		$section = SMP_PREFIX . '-section-common-tracking-window-events';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'push_events_when_displaying_window', 'absint' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_immediately', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_with_delay', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_after_click', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_on_scrolling_down', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_on_exit_intent', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Window Events Descriptions', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_window_events_descriptions' ),
			$options_page
		);

		// Отправка событий при отображении окна
		add_settings_field(
			SMP_PREFIX . '-push-events-when-displaying-the-window',
			__( 'Push events when displaying the window', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'push_events_when_displaying_window',
			)
		);

		// Label for window showed immediately
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-immediately',
			__( 'Window showed immediately', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_immediately',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show immediately', L10N_SCP_PREFIX ),
			)
		);

		// Label for window showed after N seconds
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-after-n-seconds',
			__( 'Window showed after N seconds', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_with_delay',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show after delay before it rendered', L10N_SCP_PREFIX ),
			)
		);

		// Label for window showed after click on CSS-selector
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-after-click',
			__( 'Window showed after click on CSS-selector', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_after_click',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show after click on CSS-selector', L10N_SCP_PREFIX ),
			)
		);

		// Label for window showed on scrolling down
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-on-scrolling-down',
			__( 'Window showed on scrolling down', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_on_scrolling_down',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show after scrolling down', L10N_SCP_PREFIX ),
			)
		);

		// Label for window showed on exit intent
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-on-exit-intent',
			__( 'Window showed on exit intent', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_on_exit_intent',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show on exit intent', L10N_SCP_PREFIX ),
			)
		);
	}

	/**
	 * Events tracking – Social Events
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by Social_Media_Popup::init_settings_common_tracking()
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking_social_events() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-tracking-social-events';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-tracking-social-events';

		// ID секции
		$section = SMP_PREFIX . '-section-common-tracking-social-events';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'push_events_when_subscribing_on_social_networks', 'absint' );
		register_setting( $group, $scp_prefix . 'add_window_events_descriptions', 'absint' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_no_events_fired', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_on_delay', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_after_click', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_on_scrolling_down', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_on_exit_intent', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Social Networks Events Descriptions', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_multiple_events_descriptions' ),
			$options_page
		);

		// Отправка событий при подписке на соц. сети
		add_settings_field(
			SMP_PREFIX . '-push-events-when-subscribing-on-social-networks',
			__( 'Push events when subscribing on social networks', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'push_events_when_subscribing_on_social_networks',
			)
		);

		// Добавление к событиям подписки на соц. сети описания событий появления окна
		add_settings_field(
			SMP_PREFIX . '-use-window-events-when-subscribing-on-social-networks',
			__( 'Add window events descriptions', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'add_window_events_descriptions',
			)
		);

		// Label for no events fired
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-no-events-fired',
			__( 'If no events fired', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_no_events_fired',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( '(no events fired)', L10N_SCP_PREFIX ),
			)
		);

		// Label after delay before show widget
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-after-delay-before-show-widget',
			__( 'When popup will appear after delay before show widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_on_delay',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'After delay before show widget', L10N_SCP_PREFIX ),
			)
		);

		// Label after click on CSS-selector
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-after-click-on-css-selector',
			__( 'On click on CSS-selector', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_after_click',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'After click on CSS-selector', L10N_SCP_PREFIX ),
			)
		);

		// Label on window scrolling down
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-on-scrolling-down',
			__( 'On window scrolling down', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_on_scrolling_down',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'On window scrolling down', L10N_SCP_PREFIX ),
			)
		);

		// Label on exit intent
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-on-exit-intent',
			__( 'On exit intent', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_on_exit_intent',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'On exit intent', L10N_SCP_PREFIX ),
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Управление")
	 */
	public function init_settings_common_management() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-management';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-management';

		// ID секции
		$section = SMP_PREFIX . '-section-common-management';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_remove_settings_on_uninstall' );

		add_settings_section(
			$section,
			__( 'Management', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_management' ),
			$options_page
		);

		// Удалять все настройки плагина при удалении
		add_settings_field(
			SMP_PREFIX . '-common-remove-settings-on-uninstall',
			__( 'Remove Settings On Uninstall Plugin', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_remove_settings_on_uninstall',
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
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-facebook-general';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-facebook-general';

		// ID секции
		$section = SMP_PREFIX . '-section-facebook-general';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_facebook' );
		register_setting( $group, $scp_prefix . 'setting_facebook_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_facebook_show_description' );
		register_setting( $group, $scp_prefix . 'setting_facebook_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_facebook_application_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_facebook_page_url', 'esc_url' );
		register_setting( $group, $scp_prefix . 'setting_facebook_locale', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_facebook_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_facebook_height', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_facebook_use_small_header', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_facebook_hide_cover' );
		register_setting( $group, $scp_prefix . 'setting_facebook_show_facepile' );
		register_setting( $group, $scp_prefix . 'setting_facebook_tabs', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_facebook_adapt_container_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_facebook_close_window_after_join', 'absint' );

		add_settings_section(
			$section,
			__( 'Facebook Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_facebook' ),
			$options_page
		);

		// Используем Facebook или нет
		add_settings_field(
			SMP_PREFIX . '-use-facebook',
			__( 'Use Facebook', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_facebook',
			)
		);

		// Название вкладки
		add_settings_field(
			SMP_PREFIX . '-facebook-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'Facebook',
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-facebook-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_show_description',
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-facebook-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_description',
			)
		);

		// ID приложения Facebook
		add_settings_field(
			SMP_PREFIX . '-facebook-application-id',
			__( 'Application ID', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_application_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '123456789012345',
			)
		);

		// URL страницы или группы Facebook
		add_settings_field(
			SMP_PREFIX . '-facebook-page-url',
			__( 'Facebook Page URL', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_page_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'https://www.facebook.com/AlexanderGruzov/',
			)
		);

		// Локаль, например ru_RU, en_US
		add_settings_field(
			SMP_PREFIX . '-facebook-locale',
			__( 'Facebook Locale', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_facebook_locale' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_locale',
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Адаптировать виджет под ширину контейнера
		add_settings_field(
			SMP_PREFIX . '-facebook-adapt-container-width',
			__( 'Adapt to Plugin Container Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_adapt_container_width',
			)
		);

		// Выводить уменьшенный заголовок виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-use-small-header',
			__( 'Use Small Header', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_use_small_header',
			)
		);

		// Скрывать обложку группы в заголовке виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-hide-cover',
			__( 'Hide cover photo in the header', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_hide_cover',
			)
		);

		// Показывать лица друзей когда страница отмечается понравившейся
		add_settings_field(
			SMP_PREFIX . '-facebook-show-facepile',
			__( 'Show profile photos when friends like this', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_show_facepile',
			)
		);

		// Типы записей (Timeline, Messages, Events)
		add_settings_field(
			SMP_PREFIX . '-facebook-tabs',
			__( 'Show Content from Tabs', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_facebook_tabs' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_tabs',
			)
		);

		// Закрывать окно виджета после вступления в группу
		add_settings_field(
			SMP_PREFIX . '-facebook-close-window-after-join',
			__( 'Close Plugin Window After Joining the Group', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_close_window_after_join',
			)
		);
	}

	/**
	 * Facebook Tracking settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by $this->init_settings_facebook()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_facebook_tracking() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-facebook-tracking';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-facebook-tracking';

		// ID секции
		$section = SMP_PREFIX . '-section-facebook-tracking';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'tracking_use_facebook', 'absint' );
		register_setting( $group, $scp_prefix . 'tracking_facebook_subscribe_event', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_facebook_unsubscribe_event', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Tracking', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_facebook_tracking' ),
			$options_page
		);

		// Использовать трекинг или нет
		add_settings_field(
			SMP_PREFIX . '-tracking-use-facebook',
			__( 'Use Tracking', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_use_facebook',
			)
		);

		// Надпись события в Google Analytics при подписке
		add_settings_field(
			SMP_PREFIX . '-tracking-facebook-subscribe-event',
			__( 'Subscribe Event Label', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_facebook_subscribe_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Subscribe on Facebook', L10N_SCP_PREFIX ),
			)
		);

		// Надпись события в Google Analytics при отписке
		add_settings_field(
			SMP_PREFIX . '-tracking-facebook-unsubscribe-event',
			__( 'Unsubscribe Event Label', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_facebook_unsubscribe_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Unsubscribe from Facebook', L10N_SCP_PREFIX ),
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
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-vkontakte-general';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-vkontakte-general';

		// ID секции
		$section = SMP_PREFIX . '-section-vkontakte-general';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_vkontakte' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_show_description' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_application_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_page_or_group_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_page_url', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_height', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_layout', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_color_background', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_color_text', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_color_button', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_close_window_after_join', 'absint' );

		add_settings_section(
			$section,
			__( 'VKontakte Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_vkontakte' ),
			$options_page
		);

		// Используем ВКонтакте или нет
		add_settings_field(
			SMP_PREFIX . '-use-vkontakte',
			__( 'Use VKontakte', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_vkontakte',
			)
		);

		// Название вкладки
		add_settings_field(
			SMP_PREFIX . '-vkontakte-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'VK', L10N_SCP_PREFIX ),
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-vkontakte-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_show_description',
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-vkontakte-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_description',
			)
		);

		// ID приложения ВКонтакте
		add_settings_field(
			SMP_PREFIX . '-vkontakte-application-id',
			__( 'VKontakte Application ID', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_application_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '1234567',
			)
		);

		// ID страницы или группы ВКонтакте
		add_settings_field(
			SMP_PREFIX . '-vkontakte-page-or-group-id',
			__( 'VKontakte Page or Group ID', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_page_or_group_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '12345678',
			)
		);

		// URL страницы или группы ВКонтакте
		add_settings_field(
			SMP_PREFIX . '-vkontakte-page-url',
			__( 'VKontakte Page URL', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_page_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'https://vk.com/blogsonwordpress_new',
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-vkontakte-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-vkontakte-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Макет (участники, новости или имена)
		add_settings_field(
			SMP_PREFIX . '-vkontakte-layout',
			__( 'Layout', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_vkontakte_layout' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_layout',
			)
		);

		// Цвет фона
		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-background',
			__( 'Background Color', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_color_background',
			)
		);

		// Цвет текста
		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-text',
			__( 'Text Color', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_color_text',
			)
		);

		// Цвет кнопки
		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-button',
			__( 'Button Color', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_color_button',
			)
		);

		// Закрывать окно виджета после вступления в группу
		add_settings_field(
			SMP_PREFIX . '-vkontakte-close-window-after-join',
			__( 'Close Plugin Window After Joining the Group', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_close_window_after_join',
			)
		);
	}

	/**
	 * VK.com Tracking settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by $this->init_settings_vkontakte()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_vkontakte_tracking() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-vkontakte-tracking';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-vkontakte-tracking';

		// ID секции
		$section = SMP_PREFIX . '-section-vkontakte-tracking';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'tracking_use_vkontakte', 'absint' );
		register_setting( $group, $scp_prefix . 'tracking_vkontakte_subscribe_event', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_vkontakte_unsubscribe_event', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Tracking', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_vkontakte_tracking' ),
			$options_page
		);

		// Использовать трекинг или нет
		add_settings_field(
			SMP_PREFIX . '-tracking-use-vkontakte',
			__( 'Use Tracking', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_use_vkontakte',
			)
		);

		// Надпись события в Google Analytics при подписке
		add_settings_field(
			SMP_PREFIX . '-tracking-vkontakte-subscribe-event',
			__( 'Subscribe Event Label', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_vkontakte_subscribe_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Subscribe on VK', L10N_SCP_PREFIX ),
			)
		);

		// Надпись события в Google Analytics при отписке
		add_settings_field(
			SMP_PREFIX . '-tracking-vkontakte-unsubscribe-event',
			__( 'Unsubscribe Event Label', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_vkontakte_unsubscribe_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Unsubscribe from VK', L10N_SCP_PREFIX ),
			)
		);
	}

	/**
	 * Настройки Одноклассников
	 */
	private function init_settings_odnoklassniki() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-odnoklassniki';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '_odnoklassniki_options';

		// ID секции
		$section = SMP_PREFIX . '-section-odnoklassniki';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_odnoklassniki' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_show_description' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_group_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_group_url', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_height', 'absint' );

		add_settings_section(
			$section,
			__( 'Odnoklassniki Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_odnoklassniki' ),
			$options_page
		);

		// Используем Одноклассники или нет
		add_settings_field(
			SMP_PREFIX . '-use-odnoklassniki',
			__( 'Use Odnoklassniki', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_odnoklassniki',
			)
		);

		// Название вкладки
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Odnoklassniki', L10N_SCP_PREFIX ),
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_show_description',
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_description',
			)
		);

		// ID группы Одноклассников
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-group-id',
			__( 'Odnoklassniki Group ID', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_group_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '12345678901234',
			)
		);

		// URL группы Одноклассников
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-group-url',
			__( 'Odnoklassniki Group URL', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_group_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'https://ok.ru/group/57122812461115',
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);
	}

	/**
	 * Настройки Google+
	 */
	private function init_settings_googleplus() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-googleplus';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '_googleplus_options';

		// ID секции
		$section = SMP_PREFIX . '-section-googleplus';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_googleplus' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_show_description' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_page_url', 'esc_url' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_layout', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_locale', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_size', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_theme', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_show_cover_photo' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_show_tagline' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_page_type', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Google+ Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_googleplus' ),
			$options_page
		);

		// Используем Google+ или нет
		add_settings_field(
			SMP_PREFIX . '-use-googleplus',
			__( 'Use Google+', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_googleplus',
			)
		);

		// Название вкладки
		add_settings_field(
			SMP_PREFIX . '-googleplus-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'Google+',
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-googleplus-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_show_description',
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-googleplus-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_description',
			)
		);

		// Тип профиля Google Plus
		add_settings_field(
			SMP_PREFIX . '-googleplus-page-type',
			__( 'Google+ Page Type', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_googleplus_page_type' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_page_type',
			)
		);

		// URL страницы или группы Google+
		add_settings_field(
			SMP_PREFIX . '-googleplus-page-url',
			__( 'Google+ Page URL', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_page_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '//plus.google.com/u/0/117676776729232885815',
			)
		);

		// Макет Google+
		add_settings_field(
			SMP_PREFIX . '-googleplus-layout',
			__( 'Layout', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_googleplus_layout' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_layout',
			)
		);

		// Локаль, например ru, en
		add_settings_field(
			SMP_PREFIX . '-googleplus-locale',
			__( 'Google+ Locale', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_googleplus_locale' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_locale',
			)
		);

		// Размер виджета
		add_settings_field(
			SMP_PREFIX . '-googleplus-size',
			__( 'Widget Size', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_size',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Цветовая схема
		add_settings_field(
			SMP_PREFIX . '-googleplus-theme',
			__( 'Google+ Theme', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_googleplus_theme' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_theme',
			)
		);

		// Показывать обложку?
		add_settings_field(
			SMP_PREFIX . '-googleplus-show-cover-photo',
			__( 'Show Cover Photo', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_show_cover_photo',
			)
		);

		// В двух словах
		add_settings_field(
			SMP_PREFIX . '-googleplus-show-tagline',
			__( 'Show Tagline', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_show_tagline',
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
	 * @uses Social_Media_Popup::get_scp_prefix()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_general() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-twitter-general';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-twitter-general';

		// ID секции
		$section = SMP_PREFIX . '-section-twitter-general';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_twitter' );
		register_setting( $group, $scp_prefix . 'setting_twitter_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_show_description' );
		register_setting( $group, $scp_prefix . 'setting_twitter_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_twitter_username', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_locale', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_first_widget', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_close_window_after_join', 'absint' );

		add_settings_section(
			$section,
			__( 'Common Settings', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_twitter' ),
			$options_page
		);

		// Используем Twitter или нет
		add_settings_field(
			SMP_PREFIX . '-use-twitter',
			__( 'Use Twitter', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_twitter',
			)
		);

		// Название вкладки
		add_settings_field(
			SMP_PREFIX . '-twitter-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Twitter', L10N_SCP_PREFIX ),
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-twitter-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_description',
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-twitter-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_description',
			)
		);

		// Логин пользователя
		add_settings_field(
			SMP_PREFIX . '-twitter-username',
			__( '@Username', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_username',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'gruz0',
			)
		);

		// Локаль, например ru или en
		add_settings_field(
			SMP_PREFIX . '-twitter-locale',
			__( 'Twitter Locale', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_twitter_locale' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_locale',
			)
		);

		// Какой виджет показывать первым
		add_settings_field(
			SMP_PREFIX . '-twitter-first-widget',
			__( 'First widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_twitter_first_widget' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_first_widget',
			)
		);

		// Закрывать окно виджета после вступления в группу
		add_settings_field(
			SMP_PREFIX . '-twitter-close-window-after-join',
			__( 'Close Plugin Window After Joining the Group', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_close_window_after_join',
			)
		);
	}

	/**
	 * Twitter Follow Button settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_follow_button() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-twitter-follow-button';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-twitter-follow-button';

		// ID секции
		$section = SMP_PREFIX . '-section-twitter-follow-button';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_twitter_use_follow_button', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_show_count', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_show_screen_name', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_follow_button_large_size', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_follow_button_align_by', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Follow Button Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_twitter_follow_button' ),
			$options_page
		);

		// Показывать или нет виджет Follow Button
		add_settings_field(
			SMP_PREFIX . '-twitter-use-follow-button',
			__( 'Use Follow Button Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_use_follow_button',
			)
		);

		// Показывать количество фолловеров
		add_settings_field(
			SMP_PREFIX . '-twitter-show-count',
			__( 'Show Followers Count', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_count',
			)
		);

		// Показывать логин
		add_settings_field(
			SMP_PREFIX . '-twitter-show-screen-name',
			__( 'Show Username', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_screen_name',
			)
		);

		// Размер виджета Follow Button
		add_settings_field(
			SMP_PREFIX . '-twitter-follow-button-large-size',
			__( 'Follow Button Large Size', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_follow_button_large_size',
			)
		);

		// Выравнивание кнопки Follow Button
		add_settings_field(
			SMP_PREFIX . '-twitter-follow-button-align-by',
			__( 'Follow Button Align', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_twitter_follow_button_align_by' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_follow_button_align_by',
			)
		);
	}

	/**
	 * Twitter Timeline settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_timeline() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-twitter-timeline';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-twitter-timeline';

		// ID секции
		$section = SMP_PREFIX . '-section-twitter-timeline';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_twitter_use_timeline', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_theme', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_link_color', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_tweet_limit', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_show_replies', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_height', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_chrome', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Timeline Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_twitter_timeline' ),
			$options_page
		);

		// Показывать или нет виджет Timeline
		add_settings_field(
			SMP_PREFIX . '-twitter-use-timeline',
			__( 'Use Timeline Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_use_timeline',
			)
		);

		// Тема оформления
		add_settings_field(
			SMP_PREFIX . '-twitter-theme',
			__( 'Theme', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_twitter_theme' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_theme',
			)
		);

		// Цвет ссылок
		add_settings_field(
			SMP_PREFIX . '-twitter-link-color',
			__( 'Link Color', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_link_color',
			)
		);

		// Количество выводимых твитов
		add_settings_field(
			SMP_PREFIX . '-twitter-tweet-limit',
			__( 'Tweet Limit', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_tweet_limit',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '3',
			)
		);

		// Показывать реплаи или нет
		add_settings_field(
			SMP_PREFIX . '-twitter-show-replies',
			__( 'Show Replies', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_replies',
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-twitter-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-twitter-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400',
			)
		);

		// Свойства виджета
		add_settings_field(
			SMP_PREFIX . '-twitter-chrome',
			__( 'Chrome', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_twitter_chrome' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_chrome',
			)
		);
	}

	/**
	 * Twitter Tracking settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @used_by Social_Media_Popup::init_settings_twitter()
	 *
	 * @since 0.7.5
	 */
	private function init_settings_twitter_tracking() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-twitter-tracking';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-twitter-tracking';

		// ID секции
		$section = SMP_PREFIX . '-section-twitter-tracking';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'tracking_use_twitter', 'absint' );
		register_setting( $group, $scp_prefix . 'tracking_twitter_event', 'sanitize_text_field' );

		add_settings_section(
			$section,
			__( 'Tracking', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_twitter_tracking' ),
			$options_page
		);

		// Использовать трекинг или нет
		add_settings_field(
			SMP_PREFIX . '-tracking-use-twitter',
			__( 'Use Tracking', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_use_twitter',
			)
		);

		// Надпись события в Google Analytics
		add_settings_field(
			SMP_PREFIX . '-tracking-twitter-event',
			__( 'Event Label', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_twitter_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Follow on Twitter', L10N_SCP_PREFIX ),
			)
		);
	}

	/**
	 * Настройки Pinterest
	 */
	private function init_settings_pinterest() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-pinterest';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '_pinterest_options';

		// ID секции
		$section = SMP_PREFIX . '-section-pinterest';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_pinterest' );
		register_setting( $group, $scp_prefix . 'setting_pinterest_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_pinterest_show_description' );
		register_setting( $group, $scp_prefix . 'setting_pinterest_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_pinterest_profile_url', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_pinterest_image_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_pinterest_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_pinterest_height', 'absint' );

		add_settings_section(
			$section,
			__( 'Pinterest Profile Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_pinterest' ),
			$options_page
		);

		// Используем Pinterest или нет
		add_settings_field(
			SMP_PREFIX . '-use-pinterest',
			__( 'Use Pinterest', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_pinterest',
			)
		);

		// Название вкладки
		add_settings_field(
			SMP_PREFIX . '-pinterest-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Pinterest', L10N_SCP_PREFIX ),
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-pinterest-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_show_description',
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-pinterest-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_description',
			)
		);

		// Ссылка на профиль пользователя
		add_settings_field(
			SMP_PREFIX . '-pinterest-profile-url',
			__( 'Profile URL', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_profile_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'http://ru.pinterest.com/gruz0/',
			)
		);

		// Ширина изображений
		add_settings_field(
			SMP_PREFIX . '-pinterest-image-width',
			__( 'Image Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_image_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '60',
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-pinterest-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '380',
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-pinterest-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( 'SCP_Settings_Field', 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '300',
			)
		);
	}

	/**
	 * Описание общих настроек
	 */
	public function settings_section_common() {
		_e( 'Common settings', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "Внешний вид")
	 */
	public function settings_section_common_view_on_desktop() {
		_e( 'Plugin appearance on desktop devices can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "Внешний вид (мобильные устройства)")
	 */
	public function settings_section_common_view_on_mobile_devices() {
		_e( 'Plugin appearance on mobile devices can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "События" — "Когда показывать окно")
	 */
	public function settings_section_when_should_the_popup_appear() {
		_e( 'Plugin events "When should the popup will appear" can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "События" – "Кому показывать окно")
	 */
	public function settings_section_who_should_see_the_popup() {
		_e( 'Plugin events "Who should see the popup" can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Events Tracking tab description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_events_tracking() {
		_e( 'Events tracking can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Window Events Tracking events description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_window_events_descriptions() {
		_e( 'Window rendering uses these events when social networks are disabled.', L10N_SCP_PREFIX );
	}

	/**
	 * Google Analytics Events Tracking description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_google_analytics() {
		_e( 'Google Analytics settings.', L10N_SCP_PREFIX );
	}

	/**
	 * Multiple Events Tracking events description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_multiple_events_descriptions() {
		_e( 'This descriptions will concatenate with social networks events descriptions. Example: [Subscribe on Facebook] + [no events fired].', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "Управление")
	 */
	public function settings_section_common_management() {
		_e( 'Management settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек Facebook
	 */
	public function settings_section_facebook() {
		_e( 'Facebook settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Facebook tracking settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_facebook_tracking() {
		_e( 'Facebook tracking settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек ВКонтакте
	 */
	public function settings_section_vkontakte() {
		_e( 'VK.com settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * VK.com tracking settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_vkontakte_tracking() {
		_e( 'VK.com tracking settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек Одноклассников
	 */
	public function settings_section_odnoklassniki() {
		_e( 'Odnoklassniki.ru settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек Google+
	 */
	public function settings_section_googleplus() {
		_e( 'Google+ settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Twitter general settings
	 *
	 * @since 0.6
	 */
	public function settings_section_twitter() {
		_e( 'Twitter settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Twitter Follow Button settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_twitter_follow_button() {
		_e( 'Twitter Follow Button widget settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Twitter Timeline settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_twitter_timeline() {
		_e( 'Twitter Timeline widget settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Twitter tracking settings description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_twitter_tracking() {
		_e( 'Twitter tracking settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек Pinterest
	 */
	public function settings_section_pinterest() {
		_e( 'Pinterest settings can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Добавление пункта меню
	 */
	public function add_menu() {
		add_dashboard_page(
			__( 'Welcome To Social Media Popup Welcome Screen', L10N_SCP_PREFIX ),
			__( 'Welcome To Social Media Popup Welcome Screen', L10N_SCP_PREFIX ),
			'read',
			SMP_PREFIX . '_about',
			array( & $this, 'plugin_welcome_screen' )
		);

		add_menu_page(
			__( 'Social Media Popup Options', L10N_SCP_PREFIX ),
			__( 'SMP Options', L10N_SCP_PREFIX ),
			'administrator',
			SMP_PREFIX,
			array( & $this, 'plugin_settings_page' ),
			'dashicons-format-image'
		);

		// Facebook
		add_submenu_page(
			SMP_PREFIX, // Родительский пункт меню
			__( 'Facebook Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Facebook', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			SMP_PREFIX . '_facebook_options',
			array( & $this, 'plugin_settings_page_facebook_options' )
		);

		// ВКонтакте
		add_submenu_page(
			SMP_PREFIX, // Родительский пункт меню
			__( 'VKontakte Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'VKontakte', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			SMP_PREFIX . '_vkontakte_options',
			array( & $this, 'plugin_settings_page_vkontakte_options' )
		);

		// Одноклассники
		add_submenu_page(
			SMP_PREFIX, // Родительский пункт меню
			__( 'Odnoklassniki Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Odnoklassniki', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			SMP_PREFIX . '_odnoklassniki_options',
			array( & $this, 'plugin_settings_page_odnoklassniki_options' )
		);

		// Google+
		add_submenu_page(
			SMP_PREFIX, // Родительский пункт меню
			__( 'Google+ Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Google+', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			SMP_PREFIX . '_googleplus_options',
			array( & $this, 'plugin_settings_page_googleplus_options' )
		);

		// Twitter
		add_submenu_page(
			SMP_PREFIX, // Родительский пункт меню
			__( 'Twitter Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Twitter', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			SMP_PREFIX . '_twitter_options',
			array( & $this, 'plugin_settings_page_twitter_options' )
		);

		// Pinterest
		add_submenu_page(
			SMP_PREFIX, // Родительский пункт меню
			__( 'Pinterest Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Pinterest', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			SMP_PREFIX . '_pinterest_options',
			array( & $this, 'plugin_settings_page_pinterest_options' )
		);

		// Debug
		add_submenu_page(
			SMP_PREFIX, // Родительский пункт меню
			__( 'Debug', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Debug', L10N_SCP_PREFIX ), // Пункт меню
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

		if ( absint( get_option( self::get_scp_prefix() . 'setting_show_admin_bar_menu' ) ) !== 1 ) return;

		$args = array(
			'id'     => 'scp-admin-bar',
			'title'  => 'Social Media Popup',
		);

		if ( absint( get_option( self::get_scp_prefix() . 'setting_debug_mode' ) ) === 1 ) {
			$args['title'] .= ' – ' . __( 'Debug Mode', L10N_SCP_PREFIX );
			$args['meta']['class'] = 'smp-debug-mode';
		}

		$wp_admin_bar->add_node( $args );

		$menu_scp_settings = array(
			'parent' => 'scp-admin-bar',
			'id'     => 'scp-settings',
			'title'  => __( 'Settings', L10N_SCP_PREFIX ),
			'href'   => admin_url( 'admin.php?page=' . SMP_PREFIX ),
		);
		$wp_admin_bar->add_node( $menu_scp_settings );

		$menu_clear_cookies = array(
			'parent' => 'scp-admin-bar',
			'id'     => 'scp-clear-cookies',
			'title'  => __( 'Clear Cookies', L10N_SCP_PREFIX ),
			'href'   => '#',
			'meta'   => array(
				'onclick' => 'scp_clearAllPluginCookies();return false;',
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
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @uses $this->add_cookies_script()
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$this->add_custom_css();

		if ( ! is_admin() ) return;

		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		wp_register_style( SMP_PREFIX . '-admin-css', SMP_ASSETS_URL . 'css/admin.min.css?' . $version );
		wp_enqueue_style( SMP_PREFIX . '-admin-css' );

		wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-css' );

		wp_enqueue_script( 'jquery-ui-draggable', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-sortable', array( 'jquery' ) );

		$this->add_cookies_script( $version, $scp_prefix );

		if ( SMP_PREFIX === get_current_screen()->id ) {
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );

			wp_enqueue_script( 'media-upload' );
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_register_script( SMP_PREFIX . '-admin-js', SMP_ASSETS_URL . 'js/admin.js?' . $version,
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
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @uses SCP_Template::render_google_analytics_tracking_code()
	 *
	 * @return mixed
	 */
	public function add_events_tracking_code() {
		$scp_prefix = self::get_scp_prefix();

		$use_events_tracking          = esc_attr( get_option( $scp_prefix . 'use_events_tracking' ) ) === '1';
		$google_analytics_tracking_id = esc_attr( get_option( $scp_prefix . 'google_analytics_tracking_id' ) );

		if ( ! $use_events_tracking ) {
			return false;
		}

		$content = '';

		$template = new SCP_Template();

		if ( ! empty( $google_analytics_tracking_id ) ) {
			$content .= $template->render_google_analytics_tracking_code( $google_analytics_tracking_id );
		}

		echo $content;
	}

	/**
	 * Страница приветствия после установки плагина
	 */
	public function plugin_welcome_screen() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		include( SMP_TEMPLATES_DIR . 'welcome-screen.php' );
	}

	/**
	 * Страница общих настроек плагина
	 */
	public function plugin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings.php' );
	}

	/**
	 * Страница настроек Facebook
	 */
	public function plugin_settings_page_facebook_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-facebook.php' );
	}

	/**
	 * Страница настроек ВКонтакте
	 */
	public function plugin_settings_page_vkontakte_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-vkontakte.php' );
	}

	/**
	 * Страница настроек Одноклассников
	 */
	public function plugin_settings_page_odnoklassniki_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-odnoklassniki.php' );
	}

	/**
	 * Страница настроек Google+
	 */
	public function plugin_settings_page_googleplus_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-googleplus.php' );
	}

	/**
	 * Страница настроек Twitter
	 */
	public function plugin_settings_page_twitter_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-twitter.php' );
	}

	/**
	 * Страница настроек Pinterest
	 */
	public function plugin_settings_page_pinterest_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( SMP_TEMPLATES_DIR . 'settings/settings-pinterest.php' );
	}

	/**
	 * Страница отладки плагина
	 */
	public function plugin_settings_page_debug() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
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
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @uses $this->add_cookies_script()
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			$this->add_custom_css();
		}

		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		$this->add_cookies_script( $version, $scp_prefix );
		if ( is_scp_cookie_present() ) {
			$when_should_the_popup_appear = split_string_by_comma( get_option( $scp_prefix . 'when_should_the_popup_appear' ) );

			if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_clicking_on_element' ) ) {
				$popup_will_appear_after_clicking_on_element = get_option( $scp_prefix . 'popup_will_appear_after_clicking_on_element' );
				$do_not_use_cookies_after_click_on_element   = get_option( $scp_prefix . 'do_not_use_cookies_after_click_on_element' );

				if ( empty( $popup_will_appear_after_clicking_on_element ) || 0 === absint( $do_not_use_cookies_after_click_on_element ) ) {
					return;
				}
			} else {
				return;
			}
		}

		$this->render_popup_window( $version, $scp_prefix );

		wp_register_style( SMP_PREFIX . '-css', SMP_ASSETS_URL . 'css/styles.min.css?' . $version );
		wp_enqueue_style( SMP_PREFIX . '-css' );

		wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );

		if ( '1' === get_option( $scp_prefix . 'setting_use_animation' ) ) {
			wp_enqueue_style( 'animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css' );
		}
	}

	/**
	 * Render popup
	 *
	 * @param string $version Plugin version
	 * @param string $scp_prefix Plugin prefix
	 */
	private function render_popup_window( $version, $scp_prefix ) {
		$content = SCP_Popup::render( $scp_prefix );

		$encoded_content = preg_replace( "~[\n\r\t]~", '', $content );
		$encoded_content = base64_encode( $encoded_content );

		wp_register_script( SMP_PREFIX . '-js', SMP_ASSETS_URL . 'js/scripts.js?' . $version, array( 'jquery' ) );
		wp_localize_script( SMP_PREFIX . '-js', 'scp', array(
			'encodedContent' => htmlspecialchars( $encoded_content ),
		));
		wp_enqueue_script( SMP_PREFIX . '-js' );
	}

	/**
	 * Adds cookies script
	 *
	 * @since 0.7.3
	 *
	 * @param string $version Plugin version
	 * @param string $scp_prefix Plugin prefix
	 * @return void
	 */
	private function add_cookies_script( $version, $scp_prefix ) {
		wp_register_script( SMP_PREFIX . '-cookies', SMP_ASSETS_URL . 'js/cookies.js?' . $version, array( 'jquery' ) );
		wp_localize_script( SMP_PREFIX . '-cookies', 'scp_cookies', array(
			'clearCookiesMessage'           => __( 'Page will be reload after clear cookies. Continue?', L10N_SCP_PREFIX ),
			'showWindowAfterReturningNDays' => absint( get_option( $scp_prefix . 'setting_display_after_n_days' ) ),
		));
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
		$scp_prefix = self::get_scp_prefix();
		$options = array();

		$all_options = wp_load_alloptions();
		foreach ( $all_options as $name => $value ) {
			if ( preg_match( '/^' . $scp_prefix . '/', $name ) ) {
				$name = str_replace( $scp_prefix, '', $name );
				$options[ $name ] = $value;
			}
		}

		$validator = new SMP_Validator( $options );
		return $validator->validate();
	}
}
