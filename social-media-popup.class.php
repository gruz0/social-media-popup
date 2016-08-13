<?php
defined( 'ABSPATH' ) or exit;

/**
 * Uses in menu, tabs, copyright and other links
 *
 * @since 0.7.4
 */
define( 'SMP_PREFIX', 'social_media_popup' );

require_once( dirname( __FILE__ ) . "/functions.php" );
require_once( dirname( __FILE__ ) . "/lib/scp_template.php" );
require_once( dirname( __FILE__ ) . "/lib/providers/providers.php" );

class Social_Media_Popup {
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

			// Кому показывать окно
			'who_should_see_the_popup',
			'visitor_opened_at_least_n_number_of_pages',
			'visitor_registered_and_role_equals_to',

			// Отслеживание событий
			'use_events_tracking',
			'google_analytics_tracking_id',
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
			'tracking_facebook_event',

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
			'tracking_vkontakte_event',

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
			'setting_twitter_use_follow_button',
			'setting_twitter_show_count',
			'setting_twitter_show_screen_name',
			'setting_twitter_follow_button_large_size',
			'setting_twitter_follow_button_align_by',
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
			'setting_pinterest_height'
		);

		for ( $idx = 0, $size = count( $options ); $idx < $size; $idx++ ) {
			delete_option( $scp_prefix . $options[ $idx ] );
		}
	}

	public static function set_scp_version( $version ) {
		self::$scp_version = $version;
	}

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

		// Automatically set debug mode on after reactivating plugin
		update_option( self::get_scp_prefix() . 'setting_debug_mode', 1 );
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

	public static function upgrade_to_0_1() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		// Срабатывает только при инсталляции плагина
		if ( ! get_option( $version ) ) {
			update_option( $version, '0.1' );
			self::set_scp_version( '0.1' );
		}
	}

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

	public static function upgrade_to_0_3() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.3' > get_option( $version ) ) {
			update_option( $scp_prefix . 'setting_tabs_order', 'vkontakte,facebook,odnoklassniki' );
			update_option( $version, '0.3' );
			self::set_scp_version( '0.3' );
		}
	}

	public static function upgrade_to_0_4() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.4' > get_option( $version ) ) {
			update_option( $version, '0.4' );
			self::set_scp_version( '0.4' );
		}
	}

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

	public static function upgrade_to_0_6_3() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.3' > get_option( $version ) ) {
			// У виджета LikeBox в Facebook обновился интерфейс создания, поэтому адаптируем настройки
			$facebook_show_header = absint( get_option( $scp_prefix . 'setting_facebook_show_header' ) );
			update_option( $scp_prefix . 'setting_facebook_hide_cover', ( $facebook_show_header ? "1" : "" ) );
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
				'setting_facebook_show_border'
			);

			for ( $idx = 0, $size = count( $facebook_remove_options ); $idx < $size; $idx++ ) {
				delete_option( $scp_prefix . $facebook_remove_options[ $idx ] );
			}

			unset( $facebook_remove_options );

			update_option( $version, '0.6.3' );
			self::set_scp_version( '0.6.3' );
		}
	}

	public static function upgrade_to_0_6_4() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.4' > get_option( $version ) ) {
			update_option( $version, '0.6.4' );
			self::set_scp_version( '0.6.4' );
		}
	}

	public static function upgrade_to_0_6_5() {
		$scp_prefix = self::get_scp_prefix();
		$version    = $scp_prefix . 'version';

		if ( '0.6.5' > get_option( $version ) ) {
			update_option( $scp_prefix . 'setting_googleplus_page_type',              'person' );

			update_option( $version, '0.6.5' );
			self::set_scp_version( '0.6.5' );
		}
	}

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

	public static function upgrade_to_0_7_1() {
		$old_scp_prefix = self::get_scp_prefix();
		$old_version    = $old_scp_prefix . 'version';
		$new_scp_prefix = 'scp-';

		if ( '0.7.1' > get_option( $old_version ) ) {
			$scp_options = array();

			$all_options = wp_load_alloptions();
			foreach( $all_options as $name => $value ) {
				if ( preg_match( "/^" . $old_scp_prefix . "/", $name ) ) $scp_options[$name] = $value;
			}

			// Укоротим префикс опций до четырёх символов, иначе длинные названия опций не вмещаются в таблицу
			foreach ( $scp_options as $option_name => $value ) {
				$new_option_name = preg_replace( "/^" . $old_scp_prefix . "/", '', $option_name );

				delete_option( $option_name );
				delete_option( $new_scp_prefix . $new_option_name );

				if ( ! add_option( $new_scp_prefix . $new_option_name, $value ) ) {
					var_dump( $new_scp_prefix . $new_option_name );
					var_dump( $value );
					die();
				}
			}

			// Переименуем опцию в правильное название, т.к. из-за длинного прошлого префикса были ошибки
			$old_value = get_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_eleme' );
			delete_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_eleme' );
			delete_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_element' );

			if ( ! add_option( $new_scp_prefix . 'popup_will_appear_after_clicking_on_element', $old_value ) ) {
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
			$new_value = $old_value === '1' ? 'timeline' : '';

			delete_option( $scp_prefix . 'setting_facebook_show_posts' );
			add_option( $scp_prefix . 'setting_facebook_tabs',                             $new_value );

			update_option( $version, '0.7.2' );
			self::set_scp_version( '0.7.2' );
		}
	}

	/**
	 * Upgrade to 0.7.3
	 *
	 * @since 0.7.3
	 * @return void
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
	 * Upgrade to 0.7.4
	 *
	 * @since 0.7.4
	 * @return void
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
			add_option( $scp_prefix . 'setting_vkontakte_page_url',                         'https://vk.com/blogsonwordpress_new' );

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
	 * Upgrade to 0.7.5
	 *
	 * @since 0.7.5
	 * @return void
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

			// Трекинг событий социальных сетей
			update_option( $scp_prefix . 'tracking_use_twitter',                            1 );
			update_option( $scp_prefix . 'tracking_twitter_event',                          __( 'Follow on Twitter', L10N_SCP_PREFIX ) );

			update_option( $scp_prefix . 'tracking_use_vkontakte',                          1 );
			update_option( $scp_prefix . 'tracking_vkontakte_event',                        __( 'Subscribe on VK.com', L10N_SCP_PREFIX ) );

			update_option( $scp_prefix . 'tracking_use_facebook',                           1 );
			update_option( $scp_prefix . 'tracking_facebook_event',                         __( 'Subscribe on Facebook', L10N_SCP_PREFIX ) );

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

			update_option( $version, '0.7.5' );
			self::set_scp_version( '0.7.5' );
		}
	}

	/**
	 * Подключаем локализацию к плагину
	 */
	public function localization() {
		load_plugin_textdomain( L10N_SCP_PREFIX, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Hook into WP's admin_init action hook
	 */
	public function admin_init() {
		$this->init_settings();

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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_debug_mode'
			)
		);

		// Порядок вывода закладок соц. сетей
		add_settings_field(
			SMP_PREFIX . '-common-tabs-order',
			__( 'Tabs Order', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_tabs_order' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_tabs_order'
			)
		);

		// Скрывать окно при нажатии на любой области экрана
		add_settings_field(
			SMP_PREFIX . '-common-close-popup-by-clicking-anywhere',
			__( 'Close the popup by clicking anywhere on the screen', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_close_popup_by_clicking_anywhere'
			)
		);

		// Скрывать окно при нажатии на Escape
		add_settings_field(
			SMP_PREFIX . '-common-close-popup-when-esc-pressed',
			__( 'Close the popup when ESC pressed', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_close_popup_when_esc_pressed'
			)
		);

		// Показывать виджет на мобильных устройствах
		add_settings_field(
			SMP_PREFIX . '-common-show-on-mobile-devices',
			__( 'Show widget on mobile devices', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_on_mobile_devices'
			)
		);

		// Показывать меню в Админ баре
		add_settings_field(
			SMP_PREFIX . '-common-show-admin-bar-menu',
			__( 'Show Plugin Menu in Admin Bar', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_admin_bar_menu'
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид")
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
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_plugin_title'
			)
		);

		// Использовать иконки вместо надписей на табах
		add_settings_field(
			SMP_PREFIX . '-common-use-icons-instead-of-labels-in-tabs',
			__( 'Use Icons Instead of Labels in Tabs', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_use_icons_instead_of_labels_in_tabs'
			)
		);

		// Размер иконок социальных сетей
		add_settings_field(
			SMP_PREFIX . '-common-icons-size-on-desktop',
			__( 'Icons Size', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_icons_size_on_desktop'
			)
		);

		// Скрывать панель табов, если активна только одна соц. сеть
		add_settings_field(
			SMP_PREFIX . '-common-hide-tabs-if-one-widget-is-active',
			__( 'Hide Tabs if One Widget is Active', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_hide_tabs_if_one_widget_is_active'
			)
		);

		// Отцентрировать табы
		add_settings_field(
			SMP_PREFIX . '-common-align-tabs-to-center',
			__( 'Align Tabs to Center', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_align_tabs_to_center'
			)
		);

		// Показывать кнопку закрытия окна в заголовке в контейнере или вне его
		add_settings_field(
			SMP_PREFIX . '-common-show-close-button-in',
			__( 'Show Close Button in Title', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_show_close_button_in' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_close_button_in'
			)
		);

		// Показывать кнопку "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-show-button-to-close-widget',
			__( 'Show Button to Close Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_button_to_close_widget'
			)
		);

		// Надпись на кнопке "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-button-to-close-widget-title',
			__( 'Button to Close Widget Title', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_button_to_close_widget_title',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( "Thanks! Please don't show me popup.", L10N_SCP_PREFIX )
			)
		);

		// Стиль кнопки "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-button-to-close-widget-style',
			__( 'Button to Close Widget Style', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_button_to_close_widget_style' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_button_to_close_widget_style'
			)
		);

		// Задержка перед отображением кнопки "Спасибо, я уже с вами"
		add_settings_field(
			SMP_PREFIX . '-common-delay-before-show-button-to-close-widget',
			__( 'Delay Before Show Button to Close Widget (sec.)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_delay_before_show_bottom_button',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '10'
			)
		);

		// Ширина основного контейнера
		add_settings_field(
			SMP_PREFIX . '-common-container-width',
			__( 'Container Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_container_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Высота основного контейнера
		add_settings_field(
			SMP_PREFIX . '-common-container-height',
			__( 'Container Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_container_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Радиус скругления границ
		add_settings_field(
			SMP_PREFIX . '-common-border-radius',
			__( 'Border Radius', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_border_radius',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '10'
			)
		);

		// Цвет фоновой заливки родительского контейннера
		add_settings_field(
			SMP_PREFIX . '-common-overlay-color',
			__( 'Overlay Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_overlay_color'
			)
		);

		// Уровень прозрачности фоновой заливки родительского контейннера
		add_settings_field(
			SMP_PREFIX . '-common-overlay-opacity',
			__( 'Overlay Opacity', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_overlay_opacity',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '80'
			)
		);

		// Загрузка фонового изображения виджета
		add_settings_field(
			SMP_PREFIX . '-common-background-image',
			__( 'Widget Background Image', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_background_image' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_background_image'
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
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_plugin_title_on_mobile_devices'
			)
		);

		// Размер иконок социальных сетей
		add_settings_field(
			SMP_PREFIX . '-common-icons-size-on-mobile-devices',
			__( 'Icons Size', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_icons_size' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_icons_size_on_mobile_devices'
			)
		);
	}

	/**
	 * Общие настройки (вкладка "События")
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
			array( & $this, 'settings_field_when_should_the_popup_appear' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'when_should_the_popup_appear'
			)
		);

		// Отображение окна после задержки N секунд
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-n-seconds',
			__( 'Popup Will Appear After N Second(s)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_n_seconds',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '5'
			)
		);

		// Отображение окна при клике на CSS-селектор
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-clicking-on-element',
			__( 'Popup Will Appear After Clicking on the Given CSS Selector', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_clicking_on_element',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '#my-button, .entry .button'
			)
		);

		// Скрывать элемент, вызвавший открытие окна, после клика на него
		add_settings_field(
			SMP_PREFIX . '-event-hide-element-after-click-on-it',
			__( 'Hide Element After Click on It', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'event_hide_element_after_click_on_it'
			)
		);

		// Отображение окна при прокрутке страницы на N процентов
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-after-scrolling-down-n-percent',
			__( 'Popup Will Appear After Scrolling Down at Least N Percent', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '70'
			)
		);

		// Отображение окна при перемещении мыши за границы окна
		add_settings_field(
			SMP_PREFIX . '-popup-will-appear-on-exit-intent',
			__( 'Popup Will Appear On Exit-Intent', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_on_exit_intent'
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
			array( & $this, 'settings_field_who_should_see_the_popup' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'who_should_see_the_popup'
			)
		);

		// Отображение окна после просмотра N страниц на сайте
		add_settings_field(
			SMP_PREFIX . '-visitor-opened-at-least-n-number-of-pages',
			__( 'Visitor Opened at Least N Number of Pages', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'visitor_opened_at_least_n_number_of_pages',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '2'
			)
		);

		// Отображение окна авторизованным пользователям
		add_settings_field(
			SMP_PREFIX . '-visitor-registered-and-role-equals-to',
			__( 'Registered Users Who Should See the Popup', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_visitor_registered_and_role_equals_to' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'visitor_registered_and_role_equals_to'
			)
		);

		// Повторный показ окна через N дней
		add_settings_field(
			SMP_PREFIX . '-common-display-after-n-days',
			__( 'Display After N-days', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'setting_display_after_n_days',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '15'
			)
		);
	}

	/**
	 * Events tracking
	 *
	 * @since 0.7.5
	 */
	public function init_settings_common_tracking() {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = SMP_PREFIX . '-group-tracking';

		// Используется в do_settings_section
		$options_page = SMP_PREFIX . '-group-tracking';

		// ID секции
		$section = SMP_PREFIX . '-section-common-tracking';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'use_events_tracking', 'absint' );
		register_setting( $group, $scp_prefix . 'google_analytics_tracking_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_immediately', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_with_delay', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_after_click', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_on_scrolling_down', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_window_showed_on_exit_intent', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_no_events_fired', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_on_delay', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_after_click', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_on_scrolling_down', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'tracking_event_label_on_exit_intent', 'sanitize_text_field' );

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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'use_events_tracking'
			)
		);

		// Google Analytics Tracking ID
		add_settings_field(
			SMP_PREFIX . '-common-tracking-google-analytics-tracking-id',
			__( 'Google Analytics Tracking ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'google_analytics_tracking_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'UA-12345678-0'
			)
		);

		// ID секции для описания событий
		$section = SMP_PREFIX . '-section-common-tracking-events-simple-labels';

		add_settings_section(
			$section,
			__( 'Window Events Descriptions', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_window_events_descriptions' ),
			$options_page
		);

		// Label for window showed immediately
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-immediately',
			__( 'Window showed immediately', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_immediately',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show immediately', L10N_SCP_PREFIX )
			)
		);

		// Label for window showed after N seconds
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-after-n-seconds',
			__( 'Window showed after N seconds', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_with_delay',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show after delay before it rendered', L10N_SCP_PREFIX )
			)
		);

		// Label for window showed after click on CSS-selector
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-after-click',
			__( 'Window showed after click on CSS-selector', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_after_click',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show after click on CSS-selector', L10N_SCP_PREFIX )
			)
		);

		// Label for window showed on scrolling down
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-on-scrolling-down',
			__( 'Window showed on scrolling down', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_on_scrolling_down',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show after scrolling down', L10N_SCP_PREFIX )
			)
		);

		// Label for window showed on exit intent
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-window-showed-on-exit-intent',
			__( 'Window showed on exit intent', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_window_showed_on_exit_intent',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Show on exit intent', L10N_SCP_PREFIX )
			)
		);

		// ID секции для описания событий
		$section = SMP_PREFIX . '-section-common-tracking-events-multiple-labels';

		add_settings_section(
			$section,
			__( 'Social Networks Events Descriptions', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common_multiple_events_descriptions' ),
			$options_page
		);

		// Label for no events fired
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-no-events-fired',
			__( 'If no events fired', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_no_events_fired',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( '(no events fired)', L10N_SCP_PREFIX )
			)
		);

		// Label after delay before show widget
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-after-delay-before-show-widget',
			__( 'When popup will appear after delay before show widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_on_delay',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'After delay before show widget', L10N_SCP_PREFIX )
			)
		);

		// Label after click on CSS-selector
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-after-click-on-css-selector',
			__( 'On click on CSS-selector', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_after_click',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'After click on CSS-selector', L10N_SCP_PREFIX )
			)
		);

		// Label on window scrolling down
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-on-scrolling-down',
			__( 'On window scrolling down', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_on_scrolling_down',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'On window scrolling down', L10N_SCP_PREFIX )
			)
		);

		// Label on exit intent
		add_settings_field(
			SMP_PREFIX . '-tracking-event-label-on-exit-intent',
			__( 'On exit intent', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_event_label_on_exit_intent',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'On exit intent', L10N_SCP_PREFIX )
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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_remove_settings_on_uninstall'
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
			array( & $this, 'settings_field_checkbox' ),
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
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'Facebook'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-facebook-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-facebook-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_description'
			)
		);

		// ID приложения Facebook
		add_settings_field(
			SMP_PREFIX . '-facebook-application-id',
			__( 'Application ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_application_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '123456789012345'
			)
		);

		// URL страницы или группы Facebook
		add_settings_field(
			SMP_PREFIX . '-facebook-page-url',
			__( 'Facebook Page URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_page_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'https://www.facebook.com/AlexanderGruzov/'
			)
		);

		// Локаль, например ru_RU, en_US
		add_settings_field(
			SMP_PREFIX . '-facebook-locale',
			__( 'Facebook Locale', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_facebook_locale' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_locale'
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Адаптировать виджет под ширину контейнера
		add_settings_field(
			SMP_PREFIX . '-facebook-adapt-container-width',
			__( 'Adapt to Plugin Container Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_adapt_container_width'
			)
		);

		// Выводить уменьшенный заголовок виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-use-small-header',
			__( 'Use Small Header', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_use_small_header'
			)
		);

		// Скрывать обложку группы в заголовке виджета
		add_settings_field(
			SMP_PREFIX . '-facebook-hide-cover',
			__( 'Hide cover photo in the header', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_hide_cover'
			)
		);

		// Показывать лица друзей когда страница отмечается понравившейся
		add_settings_field(
			SMP_PREFIX . '-facebook-show-facepile',
			__( 'Show profile photos when friends like this', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_show_facepile'
			)
		);

		// Типы записей (Timeline, Messages, Events)
		add_settings_field(
			SMP_PREFIX . '-facebook-tabs',
			__( 'Show Content from Tabs', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_facebook_tabs' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_tabs'
			)
		);

		// Закрывать окно виджета после вступления в группу
		add_settings_field(
			SMP_PREFIX . '-facebook-close-window-after-join',
			__( 'Close Plugin Window After Joining the Group', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_close_window_after_join'
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
		register_setting( $group, $scp_prefix . 'tracking_facebook_event', 'sanitize_text_field' );

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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_use_facebook'
			)
		);

		// Надпись события в Google Analytics
		add_settings_field(
			SMP_PREFIX . '-tracking-facebook-event',
			__( 'Event Label', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_facebook_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Subscribe on Facebook', L10N_SCP_PREFIX )
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
		register_setting( $group, $scp_prefix . 'setting_vkontakte_delay_before_render', 'absint' );

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
			array( & $this, 'settings_field_checkbox' ),
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
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'VK', L10N_SCP_PREFIX )
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-vkontakte-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-vkontakte-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_description'
			)
		);

		// ID приложения ВКонтакте
		add_settings_field(
			SMP_PREFIX . '-vkontakte-application-id',
			__( 'VKontakte Application ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_application_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '1234567'
			)
		);

		// ID страницы или группы ВКонтакте
		add_settings_field(
			SMP_PREFIX . '-vkontakte-page-or-group-id',
			__( 'VKontakte Page or Group ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_page_or_group_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '12345678'
			)
		);

		// URL страницы или группы ВКонтакте
		add_settings_field(
			SMP_PREFIX . '-vkontakte-page-url',
			__( 'VKontakte Page URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_page_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'https://vk.com/blogsonwordpress_new'
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-vkontakte-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-vkontakte-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Макет (участники, новости или имена)
		add_settings_field(
			SMP_PREFIX . '-vkontakte-layout',
			__( 'Layout', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_vkontakte_layout' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_layout'
			)
		);

		// Цвет фона
		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-background',
			__( 'Background Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_color_background'
			)
		);

		// Цвет текста
		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-text',
			__( 'Text Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_color_text'
			)
		);

		// Цвет кнопки
		add_settings_field(
			SMP_PREFIX . '-vkontakte-color-button',
			__( 'Button Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_color_button'
			)
		);

		// Закрывать окно виджета после вступления в группу
		add_settings_field(
			SMP_PREFIX . '-vkontakte-close-window-after-join',
			__( 'Close Plugin Window After Joining the Group', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_close_window_after_join'
			)
		);

		// Задержка в ms перед отрисовкой виджета (при проблемах в Firefox)
		add_settings_field(
			SMP_PREFIX . '-vkontakte-delay-before-render',
			__( 'Delay before render widget (in ms.)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_delay_before_render',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '500'
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
		register_setting( $group, $scp_prefix . 'tracking_vkontakte_event', 'sanitize_text_field' );

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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_use_vkontakte'
			)
		);

		// Надпись события в Google Analytics
		add_settings_field(
			SMP_PREFIX . '-tracking-vkontakte-event',
			__( 'Event Label', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_vkontakte_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Subscribe on VK', L10N_SCP_PREFIX )
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
			array( & $this, 'settings_field_checkbox' ),
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
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Odnoklassniki', L10N_SCP_PREFIX )
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_description'
			)
		);

		// ID группы Одноклассников
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-group-id',
			__( 'Odnoklassniki Group ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_group_id',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '12345678901234'
			)
		);

		// URL группы Одноклассников
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-group-url',
			__( 'Odnoklassniki Group URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_group_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'https://ok.ru/group/57122812461115'
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-odnoklassniki-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
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
			array( & $this, 'settings_field_checkbox' ),
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
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'Google+'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-googleplus-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-googleplus-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_description'
			)
		);

		// Тип профиля Google Plus
		add_settings_field(
			SMP_PREFIX . '-googleplus-page-type',
			__( 'Google+ Page Type', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_googleplus_page_type' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_page_type'
			)
		);

		// URL страницы или группы Google+
		add_settings_field(
			SMP_PREFIX . '-googleplus-page-url',
			__( 'Google+ Page URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_page_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '//plus.google.com/u/0/117676776729232885815'
			)
		);

		// Макет Google+
		add_settings_field(
			SMP_PREFIX . '-googleplus-layout',
			__( 'Layout', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_googleplus_layout' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_layout'
			)
		);

		// Локаль, например ru, en
		add_settings_field(
			SMP_PREFIX . '-googleplus-locale',
			__( 'Google+ Locale', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_googleplus_locale' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_locale'
			)
		);

		// Размер виджета
		add_settings_field(
			SMP_PREFIX . '-googleplus-size',
			__( 'Widget Size', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_size',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Цветовая схема
		add_settings_field(
			SMP_PREFIX . '-googleplus-theme',
			__( 'Google+ Theme', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_googleplus_theme' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_theme'
			)
		);

		// Показывать обложку?
		add_settings_field(
			SMP_PREFIX . '-googleplus-show-cover-photo',
			__( 'Show Cover Photo', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_show_cover_photo'
			)
		);

		// В двух словах
		add_settings_field(
			SMP_PREFIX . '-googleplus-show-tagline',
			__( 'Show Tagline', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_show_tagline'
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
			array( & $this, 'settings_field_checkbox' ),
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
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Twitter', L10N_SCP_PREFIX )
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-twitter-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-twitter-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_description'
			)
		);

		// Логин пользователя
		add_settings_field(
			SMP_PREFIX . '-twitter-username',
			__( '@Username', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_username',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'gruz0'
			)
		);

		// Закрывать окно виджета после вступления в группу
		add_settings_field(
			SMP_PREFIX . '-twitter-close-window-after-join',
			__( 'Close Plugin Window After Joining the Group', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_close_window_after_join'
			)
		);
	}

	/**
	 * Twitter Follow Button settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_use_follow_button'
			)
		);

		// Показывать количество фолловеров
		add_settings_field(
			SMP_PREFIX . '-twitter-show-count',
			__( 'Show Followers Count', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_count'
			)
		);

		// Показывать логин
		add_settings_field(
			SMP_PREFIX . '-twitter-show-screen-name',
			__( 'Show Username', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_screen_name'
			)
		);

		// Размер виджета Follow Button
		add_settings_field(
			SMP_PREFIX . '-twitter-follow-button-large-size',
			__( 'Follow Button Large Size', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_follow_button_large_size'
			)
		);

		// Выравнивание кнопки Follow Button
		add_settings_field(
			SMP_PREFIX . '-twitter-follow-button-align-by',
			__( 'Follow Button Align', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_twitter_follow_button_align_by' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_follow_button_align_by'
			)
		);
	}

	/**
	 * Twitter Timeline settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_use_timeline'
			)
		);

		// Тема оформления
		add_settings_field(
			SMP_PREFIX . '-twitter-theme',
			__( 'Theme', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_twitter_theme' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_theme'
			)
		);

		// Цвет ссылок
		add_settings_field(
			SMP_PREFIX . '-twitter-link-color',
			__( 'Link Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_link_color'
			)
		);

		// Количество выводимых твитов
		add_settings_field(
			SMP_PREFIX . '-twitter-tweet-limit',
			__( 'Tweet Limit', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_tweet_limit',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '3'
			)
		);

		// Показывать реплаи или нет
		add_settings_field(
			SMP_PREFIX . '-twitter-show-replies',
			__( 'Show Replies', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_show_replies'
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-twitter-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-twitter-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '400'
			)
		);

		// Свойства виджета
		add_settings_field(
			SMP_PREFIX . '-twitter-chrome',
			__( 'Chrome', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_twitter_chrome' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_chrome'
			)
		);
	}

	/**
	 * Twitter Tracking settings
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
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
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_use_twitter'
			)
		);

		// Надпись события в Google Analytics
		add_settings_field(
			SMP_PREFIX . '-tracking-twitter-event',
			__( 'Event Label', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'tracking_twitter_event',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Follow on Twitter', L10N_SCP_PREFIX )
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
			array( & $this, 'settings_field_checkbox' ),
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
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_tab_caption',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . __( 'Pinterest', L10N_SCP_PREFIX )
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			SMP_PREFIX . '-pinterest-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			SMP_PREFIX . '-pinterest-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_description'
			)
		);

		// Ссылка на профиль пользователя
		add_settings_field(
			SMP_PREFIX . '-pinterest-profile-url',
			__( 'Profile URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_profile_url',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . 'http://ru.pinterest.com/gruz0/'
			)
		);

		// Ширина изображений
		add_settings_field(
			SMP_PREFIX . '-pinterest-image-width',
			__( 'Image Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_image_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '60'
			)
		);

		// Ширина виджета
		add_settings_field(
			SMP_PREFIX . '-pinterest-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_width',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '380'
			)
		);

		// Высота виджета
		add_settings_field(
			SMP_PREFIX . '-pinterest-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_height',
				'placeholder' => __( 'Example: ', L10N_SCP_PREFIX ) . '300'
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
	 * Events Tracking events description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_events_descriptions() {
		_e( 'Events descriptions can be set in this section', L10N_SCP_PREFIX );
	}

	/**
	 * Window Events Tracking events description
	 *
	 * @since 0.7.5
	 */
	public function settings_section_common_window_events_descriptions() {
		_e( 'This events using on window rendering depends on event type and used when/or social networks events are disabled.', L10N_SCP_PREFIX );
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
	 * Callback-шаблон для формирования текстового поля на странице настроек
	 *
	 * @since 0.7.5 Add placeholder
	 */
	public function settings_field_input_text( $args ) {
		$field = esc_attr( $args[ 'field' ] );
		$value = get_option( $field );

		$placeholder = ( empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' );

		echo sprintf( '<input type="text" name="%s" id="%s" value="%s"' . $placeholder . ' />', $field, $field, $value );
	}

	/**
	 * Callback-шаблон для формирования textarea на странице настроек
	 */
	public function settings_field_textarea( $args ) {
		$field = esc_attr( $args[ 'field' ] );
		$value = get_option( $field );
		echo sprintf( '<textarea name="%s" id="%s">%s</textarea>', $field, $field, $value );
	}

	/**
	 * Callback-шаблон для формирования чекбокса на странице настроек
	 */
	public function settings_field_checkbox( $args ) {
		$field = esc_attr( $args[ 'field' ] );
		$value = get_option( $field );
		echo sprintf( '<input type="checkbox" name="%s" id="%s" value="1" %s />', $field, $field, checked( $value, 1, false ) );
	}

	/**
	 * Callback-шаблон для формирования WYSIWYG-редактора на странице настроек
	 */
	public function settings_field_wysiwyg( $args ) {
		$field = esc_attr( $args[ 'field' ] );
		$value = get_option( $field );
		$settings = array(
			'wpautop' => true,
			'media_buttons' => true,
			'quicktags' => true,
			'textarea_rows' => '5',
			'teeny' => true,
			'textarea_name' => $field
		);
		wp_editor( wp_kses_post( $value , ENT_QUOTES, 'UTF-8' ), $field, $settings );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора местоположения кнопки закрытия окна в заголовке
	 */
	public function settings_field_show_close_button_in( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'inside', checked( $value, 'inside', false ), $field . '_0', __( 'Inside Container', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'outside', checked( $value, 'outside', false ), $field . '_1', __( 'Outside Container', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'none', checked( $value, 'none', false ), $field . '_2', __( 'Don\'t show', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования стиля кнопки "Спасибо, я уже с вами"
	 */
	public function settings_field_button_to_close_widget_style( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'link', checked( $value, 'link', false ), $field . '_0', __( 'Link', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'green', checked( $value, 'green', false ), $field . '_1', __( 'Green button', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'blue', checked( $value, 'blue', false ), $field . '_2', __( 'Blue button', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_3', $field, 'red', checked( $value, 'red', false ), $field . '_3', __( 'Red button', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования поля и кнопки для загрузки фонового изображения виджета
	 */
	public function settings_field_background_image( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$html = '<input type="text" id="scp_background_image" name="' . $field . '" value="' . $value . '" />';
		$html .= '<input id="scp_upload_background_image" type="button" class="button" value="' . __( 'Upload Image', L10N_SCP_PREFIX ) . '" /><br />';
		$html .= '<div class="scp-background-image">' . ( empty( $value ) ? '' : '<img src="' . $value . '" />' ) . '</div>';
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования комбобокса выбора размера иконок социальных сетей
	 *
	 * @since 0.7.4
	 *
	 * @param array $args
	 * @return string
	 */
	public function settings_field_icons_size( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$options = array();
		$options['lg'] = __( 'Normal Size', L10N_SCP_PREFIX );
		$options['2x'] = __( '2x', L10N_SCP_PREFIX );
		$options['3x'] = __( '3x', L10N_SCP_PREFIX );
		$options['4x'] = __( '4x', L10N_SCP_PREFIX );
		$options['5x'] = __( '5x', L10N_SCP_PREFIX );

		$html   = '<select id="scp_icon_size" name="' . $field . '">';
		$format = '<option value="%s"%s>%s</option>';

		foreach ( $options as $option_name => $label ) {
			$html .= sprintf( $format, $option_name, selected( $value, $option_name, false ), $label );
		}

		$html .= '</select>';

		echo $html;
	}

	/**
	 * Callback-шаблон для выбора событий, при которых показывается окно
	 */
	public function settings_field_when_should_the_popup_appear( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$options = array();
		$options['after_n_seconds']           = __( 'Popup will appear after N second(s)', L10N_SCP_PREFIX );
		$options['after_clicking_on_element'] = __( 'Popup will appear after clicking on the given CSS selector', L10N_SCP_PREFIX );
		$options['after_scrolling_down_n_percent'] = __( 'Popup will appear after a visitor has scrolled on your page at least N percent', L10N_SCP_PREFIX );
		$options['on_exit_intent']                 = __( 'Popup will appear on exit-intent (when mouse has moved out from the page)', L10N_SCP_PREFIX );

		$chains = preg_split( "/,/", $value );

		$format = '<input type="checkbox" id="%s" class="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html = '';
		foreach ( $options as $option_name => $label ) {
			$checked = '';
			for ( $idx = 0, $size = count( $chains ); $idx < $size; $idx++ ) {
				$checked = checked( $chains[$idx], $option_name, false );
				if ( strlen( $checked ) ) break;
			}

			$html .= sprintf( $format, $option_name, $field, $option_name, $checked, $option_name, $label );
			$html .= '<br />';
		}

		$html .= '<input type="hidden" id="' . $field . '" name="' . $field . '" value="' . esc_attr( $value ) . '" />';
		echo $html;
	}

	/**
	 * Callback-шаблон для выбора кому показывать окно плагина
	 */
	public function settings_field_who_should_see_the_popup( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$options = array();
		$options['visitor_opened_at_least_n_number_of_pages'] = __( 'Visitor opened at least N number of page(s)', L10N_SCP_PREFIX );
		$options['visitor_registered_and_role_equals_to']     = __( 'Registered Users Who Should See the Popup', L10N_SCP_PREFIX );

		$chains = preg_split( "/,/", $value );

		$format = '<input type="checkbox" id="%s" class="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html = '';
		foreach ( $options as $option_name => $label ) {
			$checked = '';
			for ( $idx = 0, $size = count( $chains ); $idx < $size; $idx++ ) {
				$checked = checked( $chains[$idx], $option_name, false );
				if ( strlen( $checked ) ) break;
			}

			$html .= sprintf( $format, $option_name, $field, $option_name, $checked, $option_name, $label );
			$html .= '<br />';
		}

		$html .= '<input type="hidden" id="' . $field . '" name="' . $field . '" value="' . esc_attr( $value ) . '" />';
		echo $html;
	}

	/**
	 * Callback-шаблон для выбора каким пользовательским ролям показывать плагин
	 */
	public function settings_field_visitor_registered_and_role_equals_to( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$options = array();
		$options['all_registered_users']                = __( 'All Registered Users', L10N_SCP_PREFIX );
		$options['exclude_administrators']              = __( 'All Registered Users Exclude Administrators', L10N_SCP_PREFIX );
		$options['exclude_administrators_and_managers'] = __( 'All Registered Users Exclude Administrators and Managers', L10N_SCP_PREFIX );

		$chains = preg_split( "/,/", $value );

		$format = '<option value="%s"%s>%s</option>';

		$html = sprintf( '<select name="%s" id="%s" class="%s">', $field, $field, $field );
		foreach ( $options as $option_name => $label ) {
			$html .= sprintf( $format, $option_name, selected( $value, $option_name, false ), $label );
			$html .= '<br />';
		}
		$html .= '</select>';

		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Facebook
	 */
	public function settings_field_facebook_locale( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'ru_RU', checked( $value, 'ru_RU', false ), $field . '_0', __( 'Russian', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en_US', checked( $value, 'en_US', false ), $field . '_1', __( 'English', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования табов с выбором типа загружаемого контента для Facebook
	 */
	public function settings_field_facebook_tabs( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$options = array();
		$options['timeline'] = __( 'Timelime', L10N_SCP_PREFIX );
		$options['messages'] = __( 'Messages', L10N_SCP_PREFIX );
		$options['events']   = __( 'Events', L10N_SCP_PREFIX );

		$chains = preg_split( "/,/", $value );

		$format = '<input type="checkbox" id="%s" class="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html = '';
		foreach ( $options as $option_name => $label ) {
			$checked = '';
			for ( $idx = 0, $size = count( $chains ); $idx < $size; $idx++ ) {
				$checked = checked( $chains[$idx], $option_name, false );
				if ( strlen( $checked ) ) break;
			}

			$html .= sprintf( $format, $option_name, $field, $option_name, $checked, $option_name, $label );
			$html .= '<br />';
		}

		$html .= '<input type="hidden" id="' . $field . '" name="' . $field . '" value="' . esc_attr( $value ) . '" />';
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета ВКонтакте
	 */
	public function settings_field_vkontakte_layout( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, '0', checked( $value, 0, false ), $field . '_0', __( 'Members', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, '2', checked( $value, 2, false ), $field . '_2', __( 'News', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, '1', checked( $value, 1, false ), $field . '_1', __( 'Name', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора свойств виджета Twitter
	 */
	public function settings_field_twitter_chrome( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		// Проверяем наличие всех нужных нам ключей в массиве. Если нет — инициализируем "выключенным" значением.
		$allowed_values = array( 'noheader', 'nofooter', 'noborders', 'noscrollbars', 'transparent' );
		for ( $idx = 0, $size = count( $allowed_values ); $idx < $size; $idx++ ) {
			if ( !isset( $value[$allowed_values[$idx]] ) ) $value[$allowed_values[$idx]] = 0;
		}

		$format = '<input type="checkbox" id="%s" name="%s[%s]" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_noheader', $field, 'noheader', '1', checked( $value['noheader'], 1, false ), $field . '_noheader', __( 'No Header', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_nofooter', $field, 'nofooter', '1', checked( $value['nofooter'], 1, false ), $field . '_nofooter', __( 'No Footer', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_noborders', $field, 'noborders', '1', checked( $value['noborders'], 1, false ), $field . '_noborders', __( 'No Borders', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_noscrollbars', $field, 'noscrollbars', '1', checked( $value['noscrollbars'], 1, false ), $field . '_noscrollbars', __( 'No Scrollbars', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_transparent', $field, 'transparent', '1', checked( $value['transparent'], 1, false ), $field . '_transparent', __( 'Transparent (Removes the background color)', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Twitter
	 */
	public function settings_field_twitter_theme( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', __( 'Light', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', __( 'Dark', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования комбобокса выравнивания кнопки Twitter Follow Button
	 *
	 * @since 0.7.5
	 *
	 * @param array $args
	 * @return string
	 */
	public function settings_field_twitter_follow_button_align_by( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$options = array();
		$options['left']   = __( 'Left', L10N_SCP_PREFIX );
		$options['center'] = __( 'Center', L10N_SCP_PREFIX );
		$options['right']  = __( 'Right', L10N_SCP_PREFIX );

		$html   = '<select name="' . $field . '">';
		$format = '<option value="%s"%s>%s</option>';

		foreach ( $options as $option_name => $label ) {
			$html .= sprintf( $format, $option_name, selected( $value, $option_name, false ), $label );
		}

		$html .= '</select>';

		echo $html;
	}

	/**
	 * Callback-шаблон для сортировки табов социальных сетей
	 */
	public function settings_field_tabs_order( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$values = ( $value ) ? explode( ',', $value ) : array();

		$scp_prefix = self::get_scp_prefix();

		echo '<ul id="scp-sortable">';
		foreach ( $values as $key ) {
			$setting_value = get_option( $scp_prefix . 'setting_use_' . $key );
			$class = $setting_value ? '' : ' disabled';
			echo '<li class="ui-state-default' . $class . '">' . $key . '</li>';
		}
		echo '</ul>';

		echo '<p>' . __( 'Disabled Social Networks Marked As Red', L10N_SCP_PREFIX ) . '</p>';
		echo '<input type="hidden" name="' . $field . '" id="' . $field . '" value="' . $value . '" />';
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Google+
	 */
	public function settings_field_googleplus_theme( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', __( 'Light', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', __( 'Dark', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Google+
	 */
	public function settings_field_googleplus_locale( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'ru', checked( $value, 'ru', false ), $field . '_0', __( 'Russian', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en', checked( $value, 'en', false ), $field . '_1', __( 'English', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа страницы Google+
	 */
	public function settings_field_googleplus_page_type( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'person', checked( $value, 'person', false ), $field . '_0', __( 'Google+ Person', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'page', checked( $value, 'page', false ), $field . '_1', __( 'Google+ Page', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'community', checked( $value, 'community', false ), $field . '_1', __( 'Google+ Community', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета Google+
	 */
	public function settings_field_googleplus_layout( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'portrait', checked( $value, 'portrait', false ), $field . '_0', __( 'Portrait', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'landscape', checked( $value, 'landscape', false ), $field . '_1', __( 'Landscape', L10N_SCP_PREFIX ) );
		echo $html;
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
			__( 'VKontakte', L10N_SCP_PREFIX), // Пункт меню
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
	}

	/**
	 * Adds menu with submenus to WordPress Admin Bar
	 *
	 * @since 0.7.3
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 * @return void
	 */
	public function admin_bar_menu( $wp_admin_bar ) {
		$user = wp_get_current_user();

		if ( ! ( $user instanceOf WP_User ) ) return;
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		if ( absint( get_option( self::get_scp_prefix(). 'setting_show_admin_bar_menu' ) ) != 1 ) return;

		$args = array(
			'id'     => 'scp-admin-bar',
			'title'  => 'Social Media Popup',
		);

		if ( absint( get_option( self::get_scp_prefix() . 'setting_debug_mode' ) ) == 1 ) {
			$args['title'] .= ' – ' . __( 'Debug Mode', L10N_SCP_PREFIX );
			$args['meta']['class'] = 'smp-debug-mode';
		}

		$wp_admin_bar->add_node( $args );

		$menu_scp_settings = array(
			'parent' => 'scp-admin-bar',
			'id'     => 'scp-settings',
			'title'  => __( 'Settings', L10N_SCP_PREFIX ),
			'href'   => admin_url( 'admin.php?page=' . SMP_PREFIX )
		);
		$wp_admin_bar->add_node( $menu_scp_settings );

		$menu_clear_cookies = array(
			'parent' => 'scp-admin-bar',
			'id'     => 'scp-clear-cookies',
			'title'  => __( 'Clear Cookies', L10N_SCP_PREFIX ),
			'href'   => '#',
			'meta'   => array(
				'onclick' => 'scp_clearAllPluginCookies();return false;'
			)
		);
		$wp_admin_bar->add_node( $menu_clear_cookies );
	}

	public function admin_head() {
		remove_submenu_page( 'index.php', SMP_PREFIX . '_about' );
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS на страницу настроек
	 *
	 * @since 0.7.3 Added add_cookies_script()
	 * @since 0.7.3 Added WP Color Picker script
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @uses $this->add_cookies_script()
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		if ( ! is_admin() ) return;

		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		wp_register_style( SMP_PREFIX . '-admin-css', plugins_url( 'css/admin.css?' . $version, __FILE__ ) );
		wp_enqueue_style( SMP_PREFIX . '-admin-css' );

		wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-css' );

		wp_enqueue_script( 'jquery-ui-draggable', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-sortable', array( 'jquery' ) );

		$this->add_cookies_script( $version, $scp_prefix );

		if ( SMP_PREFIX == get_current_screen()->id ) {
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');

			wp_enqueue_script('media-upload');
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_register_script( SMP_PREFIX . '-admin-js', plugins_url( 'js/admin.js?' . $version, __FILE__ ),
			array( 'jquery', 'wp-color-picker' )
		);
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
	 * @return void
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
		$version = get_option( $scp_prefix . 'version'  );

		include( sprintf( "%s/templates/welcome-screen.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Страница общих настроек плагина
	 */
	public function plugin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( sprintf( "%s/templates/settings.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Страница настроек Facebook
	 */
	public function plugin_settings_page_facebook_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( sprintf( "%s/templates/settings/settings-facebook.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Страница настроек ВКонтакте
	 */
	public function plugin_settings_page_vkontakte_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( sprintf( "%s/templates/settings/settings-vkontakte.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Страница настроек Одноклассников
	 */
	public function plugin_settings_page_odnoklassniki_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( sprintf( "%s/templates/settings/settings-odnoklassniki.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Страница настроек Google+
	 */
	public function plugin_settings_page_googleplus_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( sprintf( "%s/templates/settings/settings-googleplus.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Страница настроек Twitter
	 */
	public function plugin_settings_page_twitter_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( sprintf( "%s/templates/settings/settings-twitter.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Страница настроек Pinterest
	 */
	public function plugin_settings_page_pinterest_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include( sprintf( "%s/templates/settings/settings-pinterest.php", dirname( __FILE__ ) ) );
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS
	 *
	 * @since 0.7.3 Added add_cookies_script()
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 * @uses $this->add_cookies_script()
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		$this->add_cookies_script( $version, $scp_prefix );
		if ( is_scp_cookie_present() ) return;

		$this->render_popup_window( $version, $scp_prefix );

		if ( wp_is_mobile() ) {
			wp_register_style( SMP_PREFIX . '-css', plugins_url( 'css/mobile.css?' . $version, __FILE__ ) );
		} else {
			wp_register_style( SMP_PREFIX . '-css', plugins_url( 'css/styles.css?' . $version, __FILE__ ) );
		}
		wp_enqueue_style( SMP_PREFIX . '-css' );

		wp_register_style( 'font-awesome', plugins_url( 'vendor/font-awesome-4.6.3/css/font-awesome.min.css?' . $version, __FILE__ ) );
		wp_enqueue_style( 'font-awesome' );

		wp_register_style( SMP_PREFIX . '-icons', plugins_url( 'css/icons.css?' . $version, __FILE__ ) );
		wp_enqueue_style( SMP_PREFIX . '-icons' );
	}

	private function render_popup_window( $version, $scp_prefix ) {
		$content = $this->scp_php( $scp_prefix );

		$encoded_content = preg_replace("~[\n\r\t]~", "", $content);
		$encoded_content = base64_encode($encoded_content);

		wp_register_script( SMP_PREFIX . '-js', plugins_url( 'js/scripts.js?' . $version, __FILE__ ), array( 'jquery' ) );
		wp_localize_script( SMP_PREFIX . '-js', 'scp', array(
			'encodedContent' => htmlspecialchars( $encoded_content ),
		));
		wp_enqueue_script( SMP_PREFIX . '-js' );
	}

	private function scp_php( $scp_prefix ) {
		$use_events_tracking = esc_attr( get_option( $scp_prefix . 'use_events_tracking' ) ) === '1';

		$scp_options = array();
		$all_options = wp_load_alloptions();
		foreach( $all_options as $name => $value ) {
			// TODO: Возможно есть смысл сделать esc_attr именно здесь, но надо проверить
			if ( stristr( $name, $scp_prefix ) ) $scp_options[$name] = $value;
		}

		$events_descriptions = array(
			'window_showed_immediately'       => $scp_options[ $scp_prefix . 'tracking_event_label_window_showed_immediately' ],
			'window_showed_with_delay'        => $scp_options[ $scp_prefix . 'tracking_event_label_window_showed_with_delay' ],
			'window_showed_after_click'       => $scp_options[ $scp_prefix . 'tracking_event_label_window_showed_after_click' ],
			'window_showed_on_scrolling_down' => $scp_options[ $scp_prefix . 'tracking_event_label_window_showed_on_scrolling_down' ],
			'window_showed_on_exit_intent'    => $scp_options[ $scp_prefix . 'tracking_event_label_window_showed_on_exit_intent' ],
			'no_events_fired'                 => $scp_options[ $scp_prefix . 'tracking_event_label_no_events_fired' ],
			'on_delay'                        => $scp_options[ $scp_prefix . 'tracking_event_label_on_delay' ],
			'after_click'                     => $scp_options[ $scp_prefix . 'tracking_event_label_after_click' ],
			'on_scrolling_down'               => $scp_options[ $scp_prefix . 'tracking_event_label_on_scrolling_down' ],
			'on_exit_intent'                  => $scp_options[ $scp_prefix . 'tracking_event_label_on_exit_intent' ]
		);

		$template = new SCP_Template( $use_events_tracking, $events_descriptions );

		$debug_mode = ( (int) $scp_options[ $scp_prefix . 'setting_debug_mode' ] ) == 1;

		// При включённом режиме отладки плагин работает только для администратора сайта
		if ( $debug_mode ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

		// Если режим отладки выключен и есть кука закрытия окна или пользователь администратор — не показываем окно
		} else {
			// Проверяем, что текущий пользователь залогинен в админку и затем проверяем его роль
			if ( is_user_logged_in() ) {
				switch ( $scp_options[ $scp_prefix . 'visitor_registered_and_role_equals_to' ] ) {
					case 'all_registered_users':

					break;

					case 'exclude_administrators':
						if ( current_user_can( 'manage_options' ) ) {
							return;
						}

					break;

					case 'exclude_administrators_and_managers':
						if ( current_user_can( 'publish_pages' ) || current_user_can( 'publish_posts' ) ) {
							return;
						}

					break;
				}
			}
		}

		$show_on_mobile = $scp_options[ $scp_prefix .  'setting_show_on_mobile_devices' ] === '1';
		$wp_is_mobile   = wp_is_mobile();

		// Отключаем работу плагина на мобильных устройствах
		if ( $wp_is_mobile && ! $show_on_mobile ) return;

		$after_n_days                                     = (int) $scp_options[ $scp_prefix . 'setting_display_after_n_days' ];

		/////////////////////////////////////////////////
		// Когда показывать окно
		/////////////////////////////////////////////////
		$when_should_the_popup_appear                     = split_string_by_comma( $scp_options[ $scp_prefix . 'when_should_the_popup_appear' ] );
		$when_should_the_popup_appear_events              = array(
			'after_n_seconds',
			'after_clicking_on_element',
			'after_scrolling_down_n_percent',
			'on_exit_intent'
		);

		$popup_will_appear_after_n_seconds                = (int) $scp_options[ $scp_prefix . 'popup_will_appear_after_n_seconds' ];
		$popup_will_appear_after_clicking_on_element      = $scp_options[ $scp_prefix . 'popup_will_appear_after_clicking_on_element' ];
		$popup_will_appear_after_scrolling_down_n_percent = (int) $scp_options[ $scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent' ];
		$popup_will_appear_on_exit_intent                 = $scp_options[ $scp_prefix . 'popup_will_appear_on_exit_intent' ] === '1';

		// Дополнительные события
		$event_hide_element_after_click_on_it             = $scp_options[ $scp_prefix . 'event_hide_element_after_click_on_it' ] === '1';

		/////////////////////////////////////////////////
		// Кому показывать окно
		/////////////////////////////////////////////////
		$who_should_see_the_popup                         = split_string_by_comma( $scp_options[ $scp_prefix . 'who_should_see_the_popup' ] );
		$who_should_see_the_popup_events                  = array(
			'visitor_opened_at_least_n_number_of_pages'
		);

		$visitor_opened_at_least_n_number_of_pages        = (int) $scp_options[ $scp_prefix . 'visitor_opened_at_least_n_number_of_pages' ];

		// Если true, тогда окно будет показываться с учётом событий "Каким посетителям показывать окно"
		$who_should_see_the_popup_fired                   = false;

		// Используется при обработке событий "Когда показывать окно" и только вместе с $who_should_see_the_popup_fired
		// Значение примет true только в том случае, если хотя бы одно из событий "Кому показывать окно" активно
		$who_should_see_the_popup_present                 = false;

		// Обработка событий кому показывать окно плагина
		$show_popup = false;

		// Время жизни куки — 1 год
		$cookie_lifetime = 31536000;

		// Проверяем активность любого из события "Кому показывать окно"
		foreach ( $who_should_see_the_popup_events as $event ) {
			if ( who_should_see_the_popup_has_event( $who_should_see_the_popup, $event ) ) {
				$who_should_see_the_popup_present = true;
				break;
			}
		}

		// Если хотя бы одна опция "Кому показывать окно" активна, тогда пройдёмся итератором по всем событиям
		if ( $who_should_see_the_popup_present ) {

			// Пользователь просмотрел больше N страниц сайта
			if ( who_should_see_the_popup_has_event( $who_should_see_the_popup, 'visitor_opened_at_least_n_number_of_pages' ) ) {
				$page_views_cookie = 'scp-page-views';

				// Если существует кука просмотренных страниц — обновляем её
				if ( isset( $_COOKIE[$page_views_cookie] ) ) {
					$page_views = ( (int) $_COOKIE[$page_views_cookie] ) + 1;
					setcookie( $page_views_cookie, $page_views, time() + $cookie_lifetime, '/' );

					if ( $page_views > $visitor_opened_at_least_n_number_of_pages ) {
						$who_should_see_the_popup_fired = true;
					}

				// Иначе создаём новую
				} else {
					setcookie( $page_views_cookie, 1, time() + $cookie_lifetime, '/' );
				}
			}

			if ( $who_should_see_the_popup_fired ) {
				$show_popup = true;
			}

		// Иначе всегда показываем окно
		} else {
			$show_popup = true;
		}

		// Если ни одно событие кому показывать окно не сработало — выходим
		if ( ! $show_popup ) {
			return;
		}

		// Настройка плагина
		$use_facebook               = $scp_options[ $scp_prefix . 'setting_use_facebook' ]      === '1';
		$use_vkontakte              = $scp_options[ $scp_prefix . 'setting_use_vkontakte' ]     === '1';
		$use_odnoklassniki          = $scp_options[ $scp_prefix . 'setting_use_odnoklassniki' ] === '1';
		$use_googleplus             = $scp_options[ $scp_prefix . 'setting_use_googleplus' ]    === '1';
		$use_twitter                = $scp_options[ $scp_prefix . 'setting_use_twitter' ]       === '1';
		$use_pinterest              = $scp_options[ $scp_prefix . 'setting_use_pinterest' ]     === '1';

		$tabs_order                 = explode( ',', $scp_options[ $scp_prefix . 'setting_tabs_order' ] );

		$container_width            = $scp_options[ $scp_prefix . 'setting_container_width' ];
		$container_height           = $scp_options[ $scp_prefix . 'setting_container_height' ];
		$border_radius              = absint( $scp_options[ $scp_prefix . 'setting_border_radius' ] );
		$close_by_clicking_anywhere = $scp_options[ $scp_prefix . 'setting_close_popup_by_clicking_anywhere' ] === '1';
		$close_when_esc_pressed     = $scp_options[ $scp_prefix . 'setting_close_popup_when_esc_pressed' ] === '1';
		$show_close_button_in       = $scp_options[ $scp_prefix . 'setting_show_close_button_in' ];
		$overlay_color              = $scp_options[ $scp_prefix . 'setting_overlay_color' ];
		$overlay_opacity            = $scp_options[ $scp_prefix . 'setting_overlay_opacity' ];
		$align_tabs_to_center       = (int) $scp_options[ $scp_prefix . 'setting_align_tabs_to_center' ];
		$delay_before_show_bottom_button = abs( (int) $scp_options[ $scp_prefix . 'setting_delay_before_show_bottom_button' ] );
		$background_image           = $scp_options[ $scp_prefix . 'setting_background_image' ];

		////////////////////////////////////////
		// START RENDER
		////////////////////////////////////////

		$content = '';

		SCP_Provider::set_template( $template );

		$active_providers = array();
		foreach ( SCP_Provider::available_providers() as $provider_name ) {
			$provider = SCP_Provider::create($provider_name, $scp_prefix, $scp_options);

			if ( $provider->is_active() ) {
				$active_providers[$provider_name] = $provider;
			}
		}

		if ( count( $active_providers ) ) {
			$active_providers_count = count( $active_providers );
			$tab_index              = 1;
			$tab_width              = sprintf( '%0.2f', floatval( 100 / $active_providers_count ) );
			$last_tab_width         = 100 - $tab_width * ( $active_providers_count - 1 );

			if ( $wp_is_mobile ) {
				$content .= '<div id="scp_mobile">';

				$content .= '<div class="scp-close"><a href="#">&times;</a></div>';

				$content .= '<div class="scp-mobile-title">' . $scp_options[ $scp_prefix . 'setting_plugin_title_on_mobile_devices' ] . '</div>';

				$content .= '<ul class="scp-icons">';

				$icon_size = 'fa-' . $scp_options[ $scp_prefix . 'setting_icons_size_on_mobile_devices' ];

				for ( $idx = 0, $size = count( $tabs_order ); $idx < $size; $idx++ ) {
					$provider_name = $tabs_order[$idx];

					// Выходим, если текущий провайдер из списка не выбран используемым
					if ( ! isset( $active_providers[$provider_name] ) ) continue;

					$provider = $active_providers[$provider_name];

					$width = $tab_index == $active_providers_count ? $last_tab_width : $tab_width;

					$args = array(
						'index' => $tab_index++,
						'width' => $width,
						'icon_size' => $icon_size
					);

					$args = array_merge( $args, $provider->options() );

					$content .= $provider->tab_caption_mobile( $args );
				}

				$content .= '</ul>';

			} else {
				$content .= '<div id="social-community-popup">';

				$parent_popup_styles                  = '';
				$parent_popup_css                     = array();
				$parent_popup_css['background-color'] = $overlay_color;
				$parent_popup_css['opacity']          = '0.' . ( absint( $overlay_opacity ) / 10.0 );

				foreach ( $parent_popup_css as $selector => $value ) {
					$parent_popup_styles .= "${selector}: ${value}; ";
				}
				$content .= '<div class="parent_popup" style="' . esc_attr( $parent_popup_styles ) . '"></div>';

				$border_radius_css    = $border_radius > 0 ? "border-radius:{$border_radius}px !important;" : "";
				$background_image_css = empty( $background_image ) ? '' : "background:#fff url('{$background_image}') center center no-repeat;";

				$popup_css = '';
				$popup_css .= 'width:' . ( $container_width + 40 ) . 'px !important;height:' . ( $container_height + 10 ) . 'px !important;';
				$popup_css .= $border_radius_css;
				$popup_css .= $background_image_css;

				$scp_plugin_title  = trim( str_replace( "\r\n", "<br />", $scp_options[ $scp_prefix . 'setting_plugin_title' ] ) );
				$show_plugin_title = mb_strlen( $scp_plugin_title ) > 0;

				$content .= '<div id="popup" style="' . esc_attr( $popup_css ) . '">';

				if ( $show_plugin_title && $show_close_button_in === 'inside' ) {
					$content .= '<div class="top-close">';
						$content .= '<span class="close" title="' . __( 'Close Modal Dialog', L10N_SCP_PREFIX ) . '">&times;</span>';
					$content .= '</div>';
				}

				if ( $show_close_button_in === 'outside' ) {
					$content .= '<a href="#" class="close close-outside" title="' . __( 'Close Modal Dialog', L10N_SCP_PREFIX ) . '">&times;</a>';
				}

				$content .= '<div class="section" style="width:' . esc_attr( $container_width ) . 'px !important;height:' . esc_attr( $container_height ) . 'px !important;">';

				if ( $show_plugin_title ) {
					$content .= '<div class="plugin-title">' . $scp_plugin_title . '</div>';
				}

				if ( $active_providers_count == 1 && $scp_options[ $scp_prefix . 'setting_hide_tabs_if_one_widget_is_active' ] == 1 ) {

				} else {
					$use_icons_instead_of_labels = $scp_options[ $scp_prefix . 'setting_use_icons_instead_of_labels_in_tabs' ] == 1;
					$icon_size = 'fa-' . $scp_options[ $scp_prefix . 'setting_icons_size_on_desktop' ];

					if ( $use_icons_instead_of_labels ) {
						$content .= '<ul class="scp-icons scp-icons-desktop">';
					} else {
						$content .= '<ul class="tabs"' . ( $align_tabs_to_center ? 'style="text-align:center;"' : '' ) . '>';
					}

					for ( $idx = 0, $size = count( $tabs_order ); $idx < $size; $idx++ ) {
						$provider_name = $tabs_order[$idx];

						// Выходим, если текущий провайдер из списка не выбран используемым
						if ( ! isset( $active_providers[$provider_name] ) ) continue;

						$provider = $active_providers[$provider_name];

						$width = $tab_index == $active_providers_count ? $last_tab_width : $tab_width;

						$args = array(
							'index'     => $tab_index++,
							'width'     => $width,
							'icon_size' => $icon_size,
						);

						$args = array_merge( $args, $provider->options() );

						if ( $use_icons_instead_of_labels ) {
							$content .= $provider->tab_caption_desktop_icons( $args );
						} else {
							$content .= $provider->tab_caption( $args );
						}
					}

					// Не показываем кнопку закрытия в случае выбора иконок в табах
					if ( ! $use_icons_instead_of_labels ) {
						if ( ! $show_plugin_title && $show_close_button_in === 'inside' ) {
							$content .= '<li class="last-item"><span class="close" title="' . __( 'Close Modal Dialog', L10N_SCP_PREFIX ) . '">&times;</span></li>';
						}
					}

					$content .= '</ul>';
				}

				for ( $idx = 0, $size = count( $tabs_order ); $idx < $size; $idx++ ) {
					$provider_name = $tabs_order[$idx];

					// Выходим, если текущий провайдер из списка не выбран используемым
					if ( ! isset( $active_providers[$provider_name] ) ) continue;

					$provider = $active_providers[$provider_name];
					$content .= $provider->container();
				}
			}

			$content .= '</div>';

			if ( ! $wp_is_mobile ) {
				if ( $scp_options[ $scp_prefix . 'setting_show_button_to_close_widget' ] == '1' ) {
					$button_to_close_widget_style = $scp_options[ $scp_prefix . 'setting_button_to_close_widget_style' ];
					$button_to_close_widget_class = $button_to_close_widget_style == 'link' ? '' : 'scp-' . $button_to_close_widget_style . '-button';
					$content .= '<div class="dont-show-widget scp-button ' . esc_attr( $button_to_close_widget_class ) . '">';
						$content .= '<a href="#" class="close">' . esc_attr( $scp_options[ $scp_prefix . 'setting_button_to_close_widget_title' ] ) . '</a>';
					$content .= '</div>';
				}
			}

			$content .= '</div>';
		}

		// Окно SCP выводим только после создания его в DOM-дереве

		if ( $wp_is_mobile ) {
			$content .= '<script>
				jQuery(document).ready(function($) {
					if (is_scp_cookie_present()) return;';

					$any_event_active = false;

					// Отображение плагина после просмотра страницы N секунд
					$content .= $template->render_when_popup_will_appear_after_n_seconds(
						$when_should_the_popup_appear,
						$popup_will_appear_after_n_seconds,
						$delay_before_show_bottom_button,
						$any_event_active,
						$after_n_days
					);

					// Отображение плагина после клика по указанному селектору
					$content .= $template->render_when_popup_will_appear_after_clicking_on_element(
						$when_should_the_popup_appear,
						$popup_will_appear_after_clicking_on_element,
						$event_hide_element_after_click_on_it,
						$delay_before_show_bottom_button,
						$any_event_active,
						$after_n_days
					);

					// Отображение плагина после прокрутки страницы на N процентов
					$content .= $template->render_when_popup_will_appear_after_scrolling_down_n_percent(
						$when_should_the_popup_appear,
						$popup_will_appear_after_scrolling_down_n_percent,
						$delay_before_show_bottom_button,
						$any_event_active,
						$after_n_days
					);

					// Если ни одно из событий когда показывать окно не выбрано — показываем окно сразу и без задержки
					if ( ! $any_event_active ) {
						$content .= $template->render_show_window();
						$content .= $template->render_close_widget_on_mobile( $after_n_days );
					}

			$content .= '});';
			$content .= '</script>';

		} else {
			$content .= '<script>
				jQuery(document).ready(function($) {
					if (is_scp_cookie_present()) return;';

					if ( $use_facebook ) {
						$content .= "scp_prependFacebook(\$);";
					}

					if ( $use_vkontakte ) {
						$content .= "scp_prependVK(\$);";
					}

					if ( $use_googleplus ) {
						$content .= "scp_prependGooglePlus(\$);";
					}

					if ( $use_pinterest ) {
						$content .= "scp_prependPinterest(\$);";
					}

					$any_event_active = false;

					// Отображение плагина после просмотра страницы N секунд
					$content .= $template->render_when_popup_will_appear_after_n_seconds(
						$when_should_the_popup_appear,
						$popup_will_appear_after_n_seconds,
						$delay_before_show_bottom_button,
						$any_event_active
					);

					// Отображение плагина после клика по указанному селектору
					$content .= $template->render_when_popup_will_appear_after_clicking_on_element(
						$when_should_the_popup_appear,
						$popup_will_appear_after_clicking_on_element,
						$event_hide_element_after_click_on_it,
						$delay_before_show_bottom_button,
						$any_event_active
					);

					// Отображение плагина после прокрутки страницы на N процентов
					$content .= $template->render_when_popup_will_appear_after_scrolling_down_n_percent(
						$when_should_the_popup_appear,
						$popup_will_appear_after_scrolling_down_n_percent,
						$delay_before_show_bottom_button,
						$any_event_active
					);

					// Отображение плагина при попытке увести мышь за пределы окна
					$content .= $template->render_when_popup_will_appear_on_exit_intent(
						$when_should_the_popup_appear,
						$popup_will_appear_on_exit_intent,
						$delay_before_show_bottom_button,
						$any_event_active
					);

					// Если ни одно из событий когда показывать окно не выбрано — показываем окно сразу и без задержки
					if ( ! $any_event_active ) {
						$content .= $template->render_show_window();
						$content .= $template->render_show_bottom_button( $delay_before_show_bottom_button );
					}

					$content .= $template->render_close_widget( $close_by_clicking_anywhere, $after_n_days );
					$content .= $template->render_close_widget_when_esc_pressed( $close_when_esc_pressed, $after_n_days );
				$content .= '});
			</script>';
		}

		$content = "jQuery('body').prepend('" . $content . "');";

		return $content;
	}

	/**
	 * Adds cookies script
	 *
	 * @since 0.7.3
	 *
	 * @param string $version Plugin version
	 * @return void
	 */
	private function add_cookies_script( $version, $scp_prefix ) {
		wp_register_script( SMP_PREFIX . '-cookies', plugins_url( 'js/cookies.js?' . $version, __FILE__ ), array( 'jquery' ) );
		wp_localize_script( SMP_PREFIX . '-cookies', 'scp_cookies', array(
			'clearCookiesMessage'           => __( 'Page will be reload after clear cookies. Continue?', L10N_SCP_PREFIX ),
			'showWindowAfterReturningNDays' => (int) get_option( $scp_prefix . 'setting_display_after_n_days' ),
		));
		wp_enqueue_script( SMP_PREFIX . '-cookies' );
	}
}
