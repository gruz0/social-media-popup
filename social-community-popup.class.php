<?php
defined( 'ABSPATH' ) or exit;

require_once( dirname( __FILE__ ) . "/functions.php" );

class Social_Community_Popup {

	/**
	 * Конструктор
	 */
	public function __construct() {

		// Register action
		add_action( 'init', array( & $this, 'localization' ) );
		add_action( 'admin_init', array( & $this, 'admin_init' ) );
		add_action( 'admin_menu', array( & $this, 'add_menu' ) );
		add_action( 'admin_head', array( & $this, 'admin_head' ) );
		add_action( 'admin_head', array( & $this, 'admin_enqueue_scripts' ) );

		add_action( 'wp_footer', array( & $this, 'wp_footer' ) );

		add_action( 'wp_enqueue_scripts', array( & $this, 'enqueue_scripts' ) );
	}

	/**
	 * Активация плагина
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		set_transient( '_scp_welcome_screen', true, 30 );

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
		if ( ! get_option( SCP_PREFIX . 'setting_remove_settings_on_uninstall' ) ) return;

		$options = array(
			// Очищаем версию плагина
			'version',

			// Общие настройки
			'setting_debug_mode',
			'setting_display_after_n_days',
			'setting_display_after_visiting_n_pages',
			'setting_display_after_delay_of_n_seconds',
			'setting_tabs_order',
			'setting_container_width',
			'setting_container_height',
			'setting_border_radius',
			'setting_remove_settings_on_uninstall',

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

			// ВКонтакте
			'setting_use_vkontakte',
			'setting_vkontakte_tab_caption',
			'setting_vkontakte_show_description',
			'setting_vkontakte_description',
			'setting_vkontakte_page_or_group_id',
			'setting_vkontakte_width',
			'setting_vkontakte_height',
			'setting_vkontakte_layout',
			'setting_vkontakte_color_background',
			'setting_vkontakte_color_text',
			'setting_vkontakte_color_button',
			'setting_vkontakte_widget_delay_display',

			// Одноклассники
			'setting_use_odnoklassniki',
			'setting_odnoklassniki_tab_caption',
			'setting_odnoklassniki_show_description',
			'setting_odnoklassniki_description',
			'setting_odnoklassniki_group_id',
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

			// Twitter
			'setting_use_twitter',
			'setting_twitter_tab_caption',
			'setting_twitter_show_description',
			'setting_twitter_description',
			'setting_twitter_username',
			'setting_twitter_widget_id',
			'setting_twitter_theme',
			'setting_twitter_link_color',
			'setting_twitter_tweet_limit',
			'setting_twitter_show_replies',
			'setting_twitter_width',
			'setting_twitter_height',
			'setting_twitter_chrome'
		);

		for ( $idx = 0; $idx < count( $options ); $idx++ ) {
			delete_option( SCP_PREFIX . $options[ $idx ] );
		}
	}

	/**
	 * Обновление плагина
	 */
	public static function upgrade() {
		$version = SCP_PREFIX . 'version';

		// Срабатывает только при инсталляции плагина
		if ( ! get_option( $version ) ) update_option( $version, '0.1' );

		if ( '0.2' > get_option( $version ) ) {
			update_option( SCP_PREFIX . 'setting_display_after_n_days',             30 );
			update_option( SCP_PREFIX . 'setting_display_after_visiting_n_pages',   0 );
			update_option( SCP_PREFIX . 'setting_display_after_delay_of_n_seconds', 3 );

			update_option( SCP_PREFIX . 'setting_facebook_tab_caption',             'Facebook' );
			update_option( SCP_PREFIX . 'setting_facebook_application_id',          '277165072394537' );
			update_option( SCP_PREFIX . 'setting_facebook_page_url',                'https://www.facebook.com/AlexanderGruzov' );
			update_option( SCP_PREFIX . 'setting_facebook_locale',                  'ru_RU' );
			update_option( SCP_PREFIX . 'setting_facebook_width',                   400 );
			update_option( SCP_PREFIX . 'setting_facebook_height',                  300 );
			update_option( SCP_PREFIX . 'setting_facebook_show_faces',              1 );

			update_option( SCP_PREFIX . 'setting_vkontakte_tab_caption',            'ВКонтакте' );
			update_option( SCP_PREFIX . 'setting_vkontakte_page_or_group_id',       '64088617' );
			update_option( SCP_PREFIX . 'setting_vkontakte_width',                  400 );
			update_option( SCP_PREFIX . 'setting_vkontakte_height',                 260 );
			update_option( SCP_PREFIX . 'setting_vkontakte_color_background',       '#FFFFFF' );
			update_option( SCP_PREFIX . 'setting_vkontakte_color_text',             '#2B587A' );
			update_option( SCP_PREFIX . 'setting_vkontakte_color_button',           '#5B7FA6' );

			update_option( SCP_PREFIX . 'setting_odnoklassniki_tab_caption',        'Одноклассники' );
			update_option( SCP_PREFIX . 'setting_odnoklassniki_group_id',           '57122812461115' );
			update_option( SCP_PREFIX . 'setting_odnoklassniki_width',              400 );
			update_option( SCP_PREFIX . 'setting_odnoklassniki_height',             260 );

			update_option( $version, '0.2' );
		}

		if ( '0.3' > get_option( $version ) ) {
			update_option( SCP_PREFIX . 'setting_tabs_order', 'vkontakte,facebook,odnoklassniki' );
			update_option( $version, '0.3' );
		}

		if ( '0.4' > get_option( $version ) ) {
			update_option( $version, '0.4' );
		}

		if ( '0.5' > get_option( $version ) ) {
			// Добавляем Google+ в таблицу сортировки
			$tabs_order = get_option( SCP_PREFIX . 'setting_tabs_order' );
			$tabs_order = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'googleplus';
			$tabs_order = array_unique( $tabs_order );

			update_option( SCP_PREFIX . 'setting_tabs_order', join( ',', $tabs_order ) );

			// Добавляем новые системные опции
			update_option( SCP_PREFIX . 'setting_debug_mode',                       1 );
			update_option( SCP_PREFIX . 'setting_container_width',                  400 );
			update_option( SCP_PREFIX . 'setting_container_height',                 476 );

			// Добавляем настройки Google+
			update_option( SCP_PREFIX . 'setting_use_googleplus',                   0 );
			update_option( SCP_PREFIX . 'setting_googleplus_tab_caption',           'Google+' );
			update_option( SCP_PREFIX . 'setting_googleplus_show_description',      0 );
			update_option( SCP_PREFIX . 'setting_googleplus_description',           '' );
			update_option( SCP_PREFIX . 'setting_googleplus_page_url',              '//plus.google.com/u/0/117676776729232885815' );
			update_option( SCP_PREFIX . 'setting_googleplus_locale',                'ru' );
			update_option( SCP_PREFIX . 'setting_googleplus_size',                  400 );
			update_option( SCP_PREFIX . 'setting_googleplus_theme',                 'light' );
			update_option( SCP_PREFIX . 'setting_googleplus_show_cover_photo',      1 );
			update_option( SCP_PREFIX . 'setting_googleplus_show_tagline',          1 );

			// Обновим высоту контейнеров других социальных сетей
			update_option( SCP_PREFIX . 'setting_facebook_height',                  400 );
			update_option( SCP_PREFIX . 'setting_vkontakte_height',                 400 );
			update_option( SCP_PREFIX . 'setting_odnoklassniki_height',             400 );

			update_option( $version, '0.5' );
		}

		if ( '0.6' > get_option( $version ) ) {
			// Добавляем Twitter в таблицу сортировки
			$tabs_order = get_option( SCP_PREFIX . 'setting_tabs_order' );
			$tabs_order = ( $tabs_order ) ? explode( ',', $tabs_order ) : array();
			$tabs_order[] = 'twitter';
			$tabs_order = array_unique( $tabs_order );

			update_option( SCP_PREFIX . 'setting_tabs_order', join( ',', $tabs_order ) );

			// Добавляем настройки Twitter
			update_option( SCP_PREFIX . 'setting_use_twitter',                       0 );
			update_option( SCP_PREFIX . 'setting_twitter_tab_caption',               'Twitter' );
			update_option( SCP_PREFIX . 'setting_twitter_show_description',          0 );
			update_option( SCP_PREFIX . 'setting_twitter_description',               '' );
			update_option( SCP_PREFIX . 'setting_twitter_username',                  '' );
			update_option( SCP_PREFIX . 'setting_twitter_widget_id',                 '' );
			update_option( SCP_PREFIX . 'setting_twitter_theme',                     'light' );
			update_option( SCP_PREFIX . 'setting_twitter_link_color',                '#CC0000' );
			update_option( SCP_PREFIX . 'setting_twitter_tweet_limit',               5 );
			update_option( SCP_PREFIX . 'setting_twitter_show_replies',              0 );
			update_option( SCP_PREFIX . 'setting_twitter_width',                     400 );
			update_option( SCP_PREFIX . 'setting_twitter_height',                    400 );
			update_option( SCP_PREFIX . 'setting_twitter_chrome',                    '' );

			update_option( $version, '0.6' );
		}

		if ( '0.6.1' > get_option( $version ) ) {
			// Добавлена настройка радиуса угла скругления границ
			update_option( SCP_PREFIX . 'setting_border_radius',                     10 );

			update_option( $version, '0.6.1' );
		}

		if ( '0.6.2' > get_option( $version ) ) {
			// Добавлена настройка закрытия окна при нажатии на любой области экрана
			update_option( SCP_PREFIX . 'setting_close_popup_by_clicking_anywhere',  0 );

			// Добавлена настройка показывать виджет на мобильных устройствах или нет
			update_option( SCP_PREFIX . 'setting_show_on_mobile_devices',            0 );

			update_option( $version, '0.6.2' );
		}

		if ( '0.6.3' > get_option( $version ) ) {
			// У виджета LikeBox в Facebook обновился интерфейс создания, поэтому адаптируем настройки
			$facebook_show_header = absint( get_option( SCP_PREFIX . 'setting_facebook_show_header' ) );
			update_option( SCP_PREFIX . 'setting_facebook_hide_cover', ( $facebook_show_header ? "1" : "" ) );
			unset( $facebook_show_header );

			$facebook_show_faces = get_option( SCP_PREFIX . 'setting_facebook_show_faces' );
			update_option( SCP_PREFIX . 'setting_facebook_show_facepile', $facebook_show_faces );
			unset( $facebook_show_faces );

			$facebook_show_stream = get_option( SCP_PREFIX . 'setting_facebook_show_stream' );
			update_option( SCP_PREFIX . 'setting_facebook_show_posts', $facebook_show_stream );
			unset( $facebook_show_stream );

			$facebook_remove_options = array(
				'setting_facebook_show_header',
				'setting_facebook_show_faces',
				'setting_facebook_show_stream',
				'setting_facebook_show_border'
			);

			for ( $idx = 0; $idx < count( $facebook_remove_options ); $idx++ ) {
				delete_option( SCP_PREFIX . $facebook_remove_options[ $idx ] );
			}

			unset( $facebook_remove_options );

			update_option( $version, '0.6.3' );
		}

		if ( '0.6.4' > get_option( $version ) ) {
			// Пришлось добавить дополнительную опцию для задержки времени отображения виджета ВКонтакте.
			// Прошлая захардкоденная задержка 500 ms была слишком маленькой для Firefox.
			update_option( SCP_PREFIX . 'setting_vkontakte_widget_delay_display', 2000 );

			update_option( $version, '0.6.4' );
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
		wp_safe_redirect( add_query_arg( array( 'page' => 'social_community_popup_about' ), admin_url( 'index.php' ) ) );
	}

	/**
	 * Управление настройками плагина: генерация формы, создание полей
	 */
	public function init_settings() {
		$prefix = 'social_community_popup'; // Желательно чтобы совпадал со slug из add_menu

		$this->init_settings_common( $prefix );
		$this->init_settings_facebook( $prefix );
		$this->init_settings_vkontakte( $prefix );
		$this->init_settings_odnoklassniki( $prefix );
		$this->init_settings_googleplus( $prefix );
		$this->init_settings_twitter( $prefix );
	}

	/**
	 * Общие настройки
	 */
	public function init_settings_common( $prefix ) {

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group';

		// Используется в do_settings_section
		$options_page = $prefix;

		// ID секции
		$section = $prefix . '-section-common';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, SCP_PREFIX . 'setting_debug_mode' );
		register_setting( $group, SCP_PREFIX . 'setting_display_after_n_days', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_display_after_visiting_n_pages', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_display_after_delay_of_n_seconds', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_tabs_order', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_container_width', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_container_height', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_border_radius', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_close_popup_by_clicking_anywhere', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_show_on_mobile_devices', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_remove_settings_on_uninstall' );

		add_settings_section(
			$section,
			__( 'Common Settings', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_common' ),
			$options_page
		);

		// Активен плагин или нет
		add_settings_field(
			$prefix . '-common-debug-mode',
			__( 'Debug Mode', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_debug_mode'
			)
		);

		// Повторный показ окна через N дней
		add_settings_field(
			$prefix . '-common-display-after-n-days',
			__( 'Display After N-days', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_display_after_n_days'
			)
		);

		// Отображение окна после просмотра N страниц на сайте
		add_settings_field(
			$prefix . '-common-display-after-visiting-n-pages',
			__( 'Display After Visiting N-pages', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_display_after_visiting_n_pages'
			)
		);

		// Отображение окна после задержки N секунд
		add_settings_field(
			$prefix . '-common-display-after-delay-of-n-seconds',
			__( 'Display After Delay of N-seconds', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_display_after_delay_of_n_seconds'
			)
		);

		// Порядок вывода закладок соц. сетей
		add_settings_field(
			$prefix . '-common-tabs-order',
			__( 'Tabs Order', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_tabs_order' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_tabs_order'
			)
		);

		// Ширина основного контейнера
		add_settings_field(
			$prefix . '-common-container-width',
			__( 'Container Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_container_width'
			)
		);

		// Высота основного контейнера
		add_settings_field(
			$prefix . '-common-container-height',
			__( 'Container Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_container_height'
			)
		);

		// Радиус скругления границ
		add_settings_field(
			$prefix . '-common-border-radius',
			__( 'Border Radius', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_border_radius'
			)
		);

		// Скрывать окно при нажатии на любой области экрана
		add_settings_field(
			$prefix . '-common-close-popup-by-clicking-anywhere',
			__( 'Close the popup by clicking anywhere on the screen', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_close_popup_by_clicking_anywhere'
			)
		);

		// Показывать виджет на мобильных устройствах
		add_settings_field(
			$prefix . '-common-show-on-mobile-devices',
			__( 'Show widget on mobile devices', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_show_on_mobile_devices'
			)
		);

		// Удалять все настройки плагина при удалении
		add_settings_field(
			$prefix . '-common-remove-settings-on-uninstall',
			__( 'Remove Settings On Uninstall Plugin', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_remove_settings_on_uninstall'
			)
		);
	}

	/**
	 * Настройки Facebook
	 */
	private function init_settings_facebook( $prefix ) {

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-facebook';

		// Используется в do_settings_section
		$options_page = $prefix . '_facebook_options';

		// ID секции
		$section = $prefix . '-section-facebook';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, SCP_PREFIX . 'setting_use_facebook' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_tab_caption', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_show_description' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_description', 'wp_kses_post' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_application_id', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_page_url', 'esc_url' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_locale', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_width', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_height', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_hide_cover' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_show_facepile' );
		register_setting( $group, SCP_PREFIX . 'setting_facebook_show_posts' );

		add_settings_section(
			$section,
			__( 'Facebook Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_facebook' ),
			$options_page
		);

		// Используем Facebook или нет
		add_settings_field(
			$prefix . '-use-facebook',
			__( 'Use Facebook', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_use_facebook',
			)
		);

		// Название вкладки
		add_settings_field(
			$prefix . '-facebook-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_tab_caption'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			$prefix . '-facebook-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-facebook-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_description'
			)
		);

		// ID приложения Facebook
		add_settings_field(
			$prefix . '-facebook-application-id',
			__( 'Application ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_application_id'
			)
		);

		// URL страницы или группы Facebook
		add_settings_field(
			$prefix . '-facebook-page-url',
			__( 'Facebook Page URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_page_url'
			)
		);

		// Локаль, например ru_RU, en_US
		add_settings_field(
			$prefix . '-facebook-locale',
			__( 'Facebook Locale', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_facebook_locale' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_locale'
			)
		);

		// Ширина виджета
		add_settings_field(
			$prefix . '-facebook-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_width'
			)
		);

		// Высота виджета
		add_settings_field(
			$prefix . '-facebook-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_height'
			)
		);

		// Скрывать обложку группы в заголовке виджета
		add_settings_field(
			$prefix . '-facebook-hide-cover',
			__( 'Hide cover photo in the header', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_hide_cover'
			)
		);

		// Показывать лица друзей когда страница отмечается понравившейся
		add_settings_field(
			$prefix . '-facebook-show-facepile',
			__( 'Show profile photos when friends like this', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_show_facepile'
			)
		);

		// Показывать записи со стены
		add_settings_field(
			$prefix . '-facebook-show-posts',
			__( 'Show posts from the Page\'s timeline', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_facebook_show_posts'
			)
		);
	}

	/**
	 * Настройки ВКонтакте
	 */
	private function init_settings_vkontakte( $prefix ) {

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-vkontakte';

		// Используется в do_settings_section
		$options_page = $prefix . '_vkontakte_options';

		// ID секции
		$section = $prefix . '-section-vkontakte';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, SCP_PREFIX . 'setting_use_vkontakte' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_tab_caption', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_show_description' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_description', 'wp_kses_post' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_page_or_group_id', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_width', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_height', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_layout', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_color_background', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_color_text', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_color_button', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_vkontakte_widget_delay_display', 'absint' );

		add_settings_section(
			$section,
			__( 'VKontakte Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_vkontakte' ),
			$options_page
		);

		// Используем ВКонтакте или нет
		add_settings_field(
			$prefix . '-use-vkontakte',
			__( 'Use VKontakte', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_use_vkontakte',
			)
		);

		// Название вкладки
		add_settings_field(
			$prefix . '-vkontakte-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_tab_caption'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			$prefix . '-vkontakte-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-vkontakte-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_description'
			)
		);

		// URL страницы или группы ВКонтакте
		add_settings_field(
			$prefix . '-vkontakte-page-or-group-id',
			__( 'VKontakte Page or Group ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_page_or_group_id'
			)
		);

		// Ширина виджета
		add_settings_field(
			$prefix . '-vkontakte-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_width'
			)
		);

		// Высота виджета
		add_settings_field(
			$prefix . '-vkontakte-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_height'
			)
		);

		// Макет (участники, новости или имена)
		add_settings_field(
			$prefix . '-vkontakte-layout',
			__( 'Layout', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_vkontakte_layout' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_layout'
			)
		);

		// Цвет фона
		add_settings_field(
			$prefix . '-vkontakte-color-background',
			__( 'Background Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_color_background'
			)
		);

		// Цвет текста
		add_settings_field(
			$prefix . '-vkontakte-color-text',
			__( 'Text Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_color_text'
			)
		);

		// Цвет кнопки
		add_settings_field(
			$prefix . '-vkontakte-color-button',
			__( 'Button Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_color_button'
			)
		);

		// Задержка отрисовки виджета (для корректной работы плагина в Firefox)
		add_settings_field(
			$prefix . '-vkontakte-widget-delay-display',
			__( 'Widget Delay Display Time (in ms.)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_vkontakte_widget_delay_display'
			)
		);
	}

	/**
	 * Настройки Одноклассников
	 */
	private function init_settings_odnoklassniki( $prefix ) {

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-odnoklassniki';

		// Используется в do_settings_section
		$options_page = $prefix . '_odnoklassniki_options';

		// ID секции
		$section = $prefix . '-section-odnoklassniki';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, SCP_PREFIX . 'setting_use_odnoklassniki' );
		register_setting( $group, SCP_PREFIX . 'setting_odnoklassniki_tab_caption', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_odnoklassniki_show_description' );
		register_setting( $group, SCP_PREFIX . 'setting_odnoklassniki_description', 'wp_kses_post' );
		register_setting( $group, SCP_PREFIX . 'setting_odnoklassniki_group_id', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_odnoklassniki_width', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_odnoklassniki_height', 'absint' );

		add_settings_section(
			$section,
			__( 'Odnoklassniki Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_odnoklassniki' ),
			$options_page
		);

		// Используем Одноклассники или нет
		add_settings_field(
			$prefix . '-use-odnoklassniki',
			__( 'Use Odnoklassniki', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_use_odnoklassniki',
			)
		);

		// Название вкладки
		add_settings_field(
			$prefix . '-odnoklassniki-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_odnoklassniki_tab_caption'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			$prefix . '-odnoklassniki-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_odnoklassniki_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-odnoklassniki-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_odnoklassniki_description'
			)
		);

		// ID группы Одноклассников
		add_settings_field(
			$prefix . '-odnoklassniki-group-id',
			__( 'Odnoklassniki Group ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_odnoklassniki_group_id'
			)
		);

		// Ширина виджета
		add_settings_field(
			$prefix . '-odnoklassniki-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_odnoklassniki_width'
			)
		);

		// Высота виджета
		add_settings_field(
			$prefix . '-odnoklassniki-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_odnoklassniki_height'
			)
		);
	}

	/**
	 * Настройки Google+
	 */
	private function init_settings_googleplus( $prefix ) {

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-googleplus';

		// Используется в do_settings_section
		$options_page = $prefix . '_googleplus_options';

		// ID секции
		$section = $prefix . '-section-googleplus';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, SCP_PREFIX . 'setting_use_googleplus' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_tab_caption', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_show_description' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_description', 'wp_kses_post' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_page_url', 'esc_url' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_locale', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_size', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_theme', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_show_cover_photo' );
		register_setting( $group, SCP_PREFIX . 'setting_googleplus_show_tagline' );

		add_settings_section(
			$section,
			__( 'Google+ Community Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_googleplus' ),
			$options_page
		);

		// Используем Google+ или нет
		add_settings_field(
			$prefix . '-use-googleplus',
			__( 'Use Google+', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_use_googleplus',
			)
		);

		// Название вкладки
		add_settings_field(
			$prefix . '-googleplus-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_tab_caption'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			$prefix . '-googleplus-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-googleplus-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_description'
			)
		);

		// URL страницы или группы Google+
		add_settings_field(
			$prefix . '-googleplus-page-url',
			__( 'Google+ Page URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_page_url'
			)
		);

		// Локаль, например ru, en
		add_settings_field(
			$prefix . '-googleplus-locale',
			__( 'Google+ Locale', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_googleplus_locale' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_locale'
			)
		);

		// Размер виджета
		add_settings_field(
			$prefix . '-googleplus-size',
			__( 'Widget Size', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_size'
			)
		);

		// Цветовая схема
		add_settings_field(
			$prefix . '-googleplus-theme',
			__( 'Google+ Theme', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_googleplus_theme' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_theme'
			)
		);

		// Показывать обложку?
		add_settings_field(
			$prefix . '-googleplus-show-cover-photo',
			__( 'Show Cover Photo', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_show_cover_photo'
			)
		);

		// В двух словах
		add_settings_field(
			$prefix . '-googleplus-show-tagline',
			__( 'Show Tagline', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_googleplus_show_tagline'
			)
		);
	}

	/**
	 * Настройки Twitter
	 */
	private function init_settings_twitter( $prefix ) {

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-twitter';

		// Используется в do_settings_section
		$options_page = $prefix . '_twitter_options';

		// ID секции
		$section = $prefix . '-section-twitter';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, SCP_PREFIX . 'setting_use_twitter' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_tab_caption', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_show_description' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_description', 'wp_kses_post' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_username', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_widget_id', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_theme', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_link_color', 'sanitize_text_field' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_tweet_limit', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_show_replies', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_width', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_height', 'absint' );
		register_setting( $group, SCP_PREFIX . 'setting_twitter_chrome' );

		add_settings_section(
			$section,
			__( 'Twitter Timeline Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_twitter' ),
			$options_page
		);

		// Используем Twitter или нет
		add_settings_field(
			$prefix . '-use-twitter',
			__( 'Use Twitter', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_use_twitter',
			)
		);

		// Название вкладки
		add_settings_field(
			$prefix . '-twitter-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_tab_caption'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			$prefix . '-twitter-show-description',
			__( 'Show Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-twitter-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_description'
			)
		);

		// Логин пользователя
		add_settings_field(
			$prefix . '-twitter-username',
			__( '@Username', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_username'
			)
		);

		// ID виджета
		add_settings_field(
			$prefix . '-twitter-widget-id',
			__( 'Widget ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_widget_id'
			)
		);

		// Тема оформления
		add_settings_field(
			$prefix . '-twitter-theme',
			__( 'Theme', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_twitter_theme' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_theme'
			)
		);

		// Цвет ссылок
		add_settings_field(
			$prefix . '-twitter-link-color',
			__( 'Link Color', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_link_color'
			)
		);

		// Количество выводимых твитов
		add_settings_field(
			$prefix . '-twitter-tweet-limit',
			__( 'Tweet Limit', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_tweet_limit'
			)
		);

		// Показывать реплаи или нет
		add_settings_field(
			$prefix . '-twitter-show-replies',
			__( 'Show Replies', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_show_replies'
			)
		);

		// Ширина виджета
		add_settings_field(
			$prefix . '-twitter-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_width'
			)
		);

		// Высота виджета
		add_settings_field(
			$prefix . '-twitter-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_height'
			)
		);

		// Свойства виджета
		add_settings_field(
			$prefix . '-twitter-chrome',
			__( 'Chrome', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_twitter_chrome' ),
			$options_page,
			$section,
			array(
				'field' => SCP_PREFIX . 'setting_twitter_chrome'
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
	 * Описание настроек Facebook
	 */
	public function settings_section_facebook() {
		_e( 'In this section, you must fill out the data to display the Facebook page in a popup window', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек ВКонтакте
	 */
	public function settings_section_vkontakte() {
		_e( 'In this section, you must fill out the data to display the VK.com page in a popup window', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек Одноклассников
	 */
	public function settings_section_odnoklassniki() {
		_e( 'In this section, you must fill out the data to display the Odnoklassniki group in a popup window', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек Google+
	 */
	public function settings_section_googleplus() {
		_e( 'In this section, you must fill out the data to display the Google+ page in a popup window', L10N_SCP_PREFIX );
	}

	/**
	 * Описание настроек Twitter
	 */
	public function settings_section_twitter() {
		_e( 'In this section, you must fill out the data to display the Twitter timeline in a popup window', L10N_SCP_PREFIX );
	}

	/**
	 * Callback-шаблон для формирования текстового поля на странице настроек
	 */
	public function settings_field_input_text( $args ) {
		$field = esc_attr( $args[ 'field' ] );
		$value = get_option( $field );
		echo sprintf( '<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value );
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
			'textarea_rows' => '10',
			'textarea_name' => $field
		);
		wp_editor( wp_kses_post( $value , ENT_QUOTES, 'UTF-8' ), $field, $settings );
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
		for ( $idx = 0; $idx < count($allowed_values); $idx++ ) {
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
	 * Callback-шаблон для сортировки табов социальных сетей
	 */
	public function settings_field_tabs_order( $args ) {
		$field = $args[ 'field' ];
		$value = get_option( $field );

		$values = ( $value ) ? explode( ',', $value ) : array();

		echo '<ul id="scp-sortable">';
		foreach ( $values as $key ) {
			$setting_value = get_option( SCP_PREFIX . 'setting_use_' . $key );
			$class = $setting_value ? '' : ' disabled';
			echo '<li class="ui-state-default' . $class . '">' . $key . '</li>';
		}
		echo '</ul>';

		echo '<p>' . __( 'Closed Social Networks Marked As Red', L10N_SCP_PREFIX ) . '</p>';
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
	 * Добавление пункта меню
	 */
	public function add_menu() {
		add_dashboard_page(
			__( 'Welcome To Social Community Popup Welcome Screen', L10N_SCP_PREFIX ),
			__( 'Welcome To Social Community Popup Welcome Screen', L10N_SCP_PREFIX ),
			'read',
			'social_community_popup_about',
			array( & $this, 'plugin_welcome_screen' )
		);

		add_menu_page(
			__( 'Social Community Popup Options', L10N_SCP_PREFIX ),
			__( 'SCP Options', L10N_SCP_PREFIX ),
			'administrator',
			'social_community_popup',
			array( & $this, 'plugin_settings_page' ),
			'dashicons-format-image'
		);

		// Facebook
		add_submenu_page(
			'social_community_popup', // Родительский пункт меню
			__( 'Facebook Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Facebook', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			'social_community_popup_facebook_options',
			array( & $this, 'plugin_settings_page_facebook_options' )
		);

		// ВКонтакте
		add_submenu_page(
			'social_community_popup', // Родительский пункт меню
			__( 'VKontakte Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'VKontakte', L10N_SCP_PREFIX), // Пункт меню
			'administrator',
			'social_community_popup_vkontakte_options',
			array( & $this, 'plugin_settings_page_vkontakte_options' )
		);

		// Одноклассники
		add_submenu_page(
			'social_community_popup', // Родительский пункт меню
			__( 'Odnoklassniki Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Odnoklassniki', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			'social_community_popup_odnoklassniki_options',
			array( & $this, 'plugin_settings_page_odnoklassniki_options' )
		);

		// Google+
		add_submenu_page(
			'social_community_popup', // Родительский пункт меню
			__( 'Google+ Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Google+', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			'social_community_popup_googleplus_options',
			array( & $this, 'plugin_settings_page_googleplus_options' )
		);

		// Twitter
		add_submenu_page(
			'social_community_popup', // Родительский пункт меню
			__( 'Twitter Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Twitter', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			'social_community_popup_twitter_options',
			array( & $this, 'plugin_settings_page_twitter_options' )
		);
	}

	public function admin_head() {
		remove_submenu_page( 'index.php', 'social_community_popup_about' );
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS на страницу настроек
	 */
	public function admin_enqueue_scripts() {
		wp_register_style( 'social-community-popup-admin-style', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_style( 'social-community-popup-admin-style' );

		wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-css' );

		wp_enqueue_script( 'jquery-ui-draggable', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-sortable', array( 'jquery' ) );

		wp_register_script( 'social-community-popup-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'social-community-popup-admin-script' );
	}

	/**
	 * Страница приветствия после установки плагина
	 */
	public function plugin_welcome_screen() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$version = get_option( SCP_PREFIX . 'version'  );

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
	 * Добавляем всплывающее окно в подвале сайта
	 */
	public function wp_footer() {
		echo "<script type='text/javascript' src='" . plugins_url( 'js/scp.php?' . rand(), __FILE__ ) . "'></script>";
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS
	 */
	public function enqueue_scripts() {
		wp_register_script( 'social-community-popup-script', plugins_url( 'js/scripts.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'social-community-popup-script' );

		wp_register_style( 'social-community-popup-style', plugins_url( 'css/styles.css', __FILE__ ) );
		wp_enqueue_style( 'social-community-popup-style' );
	}
}

