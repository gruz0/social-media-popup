<?php
defined( 'ABSPATH' ) or exit;

define( 'SCP_PREFIX', 'social-community-popup-' );
define( 'L10N_SCP_PREFIX', 'social-community-popup' ); // textdomain

class Social_Community_Popup {
	
    /**
     * Конструктор
     */
    public function __construct() {

        // Register action
		add_action( 'init', array( & $this, 'localization' ) );
        add_action( 'admin_init', array( & $this, 'admin_init' ) );
        add_action( 'admin_menu', array( & $this, 'add_menu' ) );
        add_action( 'admin_head', array( & $this, 'admin_enqueue_scripts' ) );

        add_action( 'wp_footer', array( & $this, 'wp_footer' ) );

        add_action( 'wp_enqueue_scripts', array( & $this, 'enqueue_scripts' ) );
    }

    /**
     * Активация плагина
     */
    public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;

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
        	'setting_display_after_n_days',
        	'setting_display_after_visiting_n_pages',
        	'setting_display_after_delay_of_n_seconds',
        	'setting_tabs_order',
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
			'setting_facebook_show_header',
			'setting_facebook_show_border',
			'setting_facebook_show_faces',
			'setting_facebook_show_stream',

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

			// Одноклассники
			'setting_use_odnoklassniki',
			'setting_odnoklassniki_tab_caption',
			'setting_odnoklassniki_show_description',
			'setting_odnoklassniki_description',
			'setting_odnoklassniki_group_id',
			'setting_odnoklassniki_width',
			'setting_odnoklassniki_height'
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
			update_option( $version, '0.5' );
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
        register_setting( $group, SCP_PREFIX . 'setting_display_after_n_days' );
        register_setting( $group, SCP_PREFIX . 'setting_display_after_visiting_n_pages' );
        register_setting( $group, SCP_PREFIX . 'setting_display_after_delay_of_n_seconds' );
        register_setting( $group, SCP_PREFIX . 'setting_tabs_order' );
		register_setting( $group, SCP_PREFIX . 'setting_remove_settings_on_uninstall' );

        add_settings_section(
			$section,
            __( 'Common Settings', L10N_SCP_PREFIX ),
            array( & $this, 'settings_section_common' ),
			$options_page
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
        register_setting( $group, SCP_PREFIX . 'setting_facebook_show_header' );
        register_setting( $group, SCP_PREFIX . 'setting_facebook_show_border' );
        register_setting( $group, SCP_PREFIX . 'setting_facebook_show_faces' );
        register_setting( $group, SCP_PREFIX . 'setting_facebook_show_stream' );

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
            array( & $this, 'settings_field_input_text' ),
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

        // Показывать заголовок?
        add_settings_field(
            $prefix . '-facebook-show-header',
            __( 'Show Widget Header', L10N_SCP_PREFIX ),
            array( & $this, 'settings_field_checkbox' ),
            $options_page,
            $section,
            array(
                'field' => SCP_PREFIX . 'setting_facebook_show_header'
            )
        );

        // Показывать границу?
        add_settings_field(
            $prefix . '-facebook-show-border',
            __( 'Show Border', L10N_SCP_PREFIX ),
            array( & $this, 'settings_field_checkbox' ),
            $options_page,
            $section,
            array(
                'field' => SCP_PREFIX . 'setting_facebook_show_border'
            )
        );

        // Показывать лица?
        add_settings_field(
            $prefix . '-facebook-show-faces',
            __( 'Show Faces', L10N_SCP_PREFIX ),
            array( & $this, 'settings_field_checkbox' ),
            $options_page,
            $section,
            array(
                'field' => SCP_PREFIX . 'setting_facebook_show_faces'
            )
        );

        // Показывать записи со стены?
        add_settings_field(
            $prefix . '-facebook-show-stream',
            __( 'Show Stream', L10N_SCP_PREFIX ),
            array( & $this, 'settings_field_checkbox' ),
            $options_page,
            $section,
            array(
                'field' => SCP_PREFIX . 'setting_facebook_show_stream'
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
     * Callback-шаблон для формирования радио-кнопок для выбора типа макета 
	 * ВКонтакте
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
     * Добавление пункта меню
     */
    public function add_menu() {

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

        include( sprintf( "%s/templates/settings-facebook.php", dirname( __FILE__ ) ) );
    }

    /**
     * Страница настроек ВКонтакте
     */
    public function plugin_settings_page_vkontakte_options() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        include( sprintf( "%s/templates/settings-vkontakte.php", dirname( __FILE__ ) ) );
    }

    /**
     * Страница настроек Одноклассников
     */
    public function plugin_settings_page_odnoklassniki_options() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        include( sprintf( "%s/templates/settings-odnoklassniki.php", dirname( __FILE__ ) ) );
    }

    /**
     * Добавляем всплывающее окно в подвале сайта
     */
    public function wp_footer() {
		// Отключаем работу плагина на мобильных устройствах
		if ( wp_is_mobile() ) return;

		if ( isset( $_COOKIE[ 'social-community-popup' ] ) ) return;

		$after_n_days          = (int) get_option( SCP_PREFIX . 'setting_display_after_n_days' );
		$visit_n_pages         = (int) get_option( SCP_PREFIX . 'setting_display_after_visiting_n_pages' );
		$cookie_popup_views    = isset( $_COOKIE[ 'social-community-popup-views' ] ) 
			? (int) $_COOKIE[ 'social-community-popup-views' ] 
			: 0;
		$delay_after_n_seconds = (int) get_option( SCP_PREFIX . 'setting_display_after_delay_of_n_seconds' );

    	$use_facebook          = get_option( SCP_PREFIX . 'setting_use_facebook' )      === '1';
        $use_vkontakte         = get_option( SCP_PREFIX . 'setting_use_vkontakte' )     === '1';
        $use_odnoklassniki     = get_option( SCP_PREFIX . 'setting_use_odnoklassniki' ) === '1';

		$tabs_order            = explode(',', get_option( SCP_PREFIX . 'setting_tabs_order' ) );

		//TODO: Не забыть удалить!
		if ( ! is_user_logged_in() ) return;
		$after_n_days = 1;
		$visit_n_pages = 0;
		$cookie_popup_views = 0;
		$delay_after_n_seconds = 3;

		require( sprintf( "%s/templates/popup.php", dirname( __FILE__ ) ) );
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
