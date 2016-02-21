<?php
defined( 'ABSPATH' ) or exit;

require_once( dirname( __FILE__ ) . "/functions.php" );

class Social_Community_Popup {
	protected static $scp_version;

	/**
	 * Конструктор
	 */
	public function __construct() {

		// Register action
		add_action( 'init', array( & $this, 'localization' ) );
		add_action( 'admin_init', array( & $this, 'admin_init' ) );
		add_action( 'admin_menu', array( & $this, 'add_menu' ) );
		add_action( 'admin_bar_menu', array( & $this, 'admin_bar_menu' ), 999 );
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
			'setting_plugin_title',
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

			// События
			'when_should_the_popup_appear',
			'popup_will_appear_after_n_seconds',
			'popup_will_appear_after_clicking_on_element',
			'popup_will_appear_after_scrolling_down_n_percent',
			'popup_will_appear_on_exit_intent',

			// Кому показывать окно
			'who_should_see_the_popup',
			'visitor_opened_at_least_n_number_of_pages',

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
			'setting_vkontakte_delay_before_render',

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
			'setting_googleplus_page_type',

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
			'setting_twitter_chrome',

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

		for ( $idx = 0; $idx < count( $options ); $idx++ ) {
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
			update_option( $scp_prefix . 'setting_vkontakte_height',                 260 );
			update_option( $scp_prefix . 'setting_vkontakte_color_background',       '#FFFFFF' );
			update_option( $scp_prefix . 'setting_vkontakte_color_text',             '#2B587A' );
			update_option( $scp_prefix . 'setting_vkontakte_color_button',           '#5B7FA6' );

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
			update_option( $scp_prefix . 'setting_container_height',                 476 );

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

			for ( $idx = 0; $idx < count( $facebook_remove_options ); $idx++ ) {
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
				. __( '<p>Do You Like Our Site?</p><p>Follow Us on Social Networks!</p>', L10N_SCP_PREFIX )
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
			update_option( $scp_prefix . 'setting_pinterest_width',                    400 );
			update_option( $scp_prefix . 'setting_pinterest_height',                   200 );

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
		$this->init_settings_common_view( $prefix );
		$this->init_settings_common_events( $prefix );
		$this->init_settings_common_management( $prefix );

		$this->init_settings_facebook( $prefix );
		$this->init_settings_vkontakte( $prefix );
		$this->init_settings_odnoklassniki( $prefix );
		$this->init_settings_googleplus( $prefix );
		$this->init_settings_twitter( $prefix );
		$this->init_settings_pinterest( $prefix );
	}

	/**
	 * Общие настройки
	 */
	public function init_settings_common( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-general';

		// Используется в do_settings_section
		$options_page = $prefix . '-group-general';

		// ID секции
		$section = $prefix . '-section-common';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_debug_mode' );
		register_setting( $group, $scp_prefix . 'setting_tabs_order', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_close_popup_by_clicking_anywhere', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_close_popup_when_esc_pressed', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_show_on_mobile_devices', 'absint' );

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
				'field' => $scp_prefix . 'setting_debug_mode'
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
				'field' => $scp_prefix . 'setting_tabs_order'
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
				'field' => $scp_prefix . 'setting_close_popup_by_clicking_anywhere'
			)
		);

		// Скрывать окно при нажатии на Escape
		add_settings_field(
			$prefix . '-common-close-popup-when-esc-pressed',
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
			$prefix . '-common-show-on-mobile-devices',
			__( 'Show widget on mobile devices', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_checkbox' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_show_on_mobile_devices'
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Внешний вид")
	 */
	public function init_settings_common_view( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-view';

		// Используется в do_settings_section
		$options_page = $prefix . '-group-view';

		// ID секции
		$section = $prefix . '-section-common-view';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_plugin_title', 'wp_kses_post' );
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
			array( & $this, 'settings_section_common_view' ),
			$options_page
		);

		// Заголовок окна плагина
		add_settings_field(
			$prefix . '-common-plugin-title',
			__( 'Main Window Title', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_plugin_title'
			)
		);

		// Скрывать панель табов, если активна только одна соц. сеть
		add_settings_field(
			$prefix . '-common-hide-tabs-if-one-widget-is-active',
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
			$prefix . '-common-align-tabs-to-center',
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
			$prefix . '-common-show-close-button-in',
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
			$prefix . '-common-show-button-to-close-widget',
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
			$prefix . '-common-button-to-close-widget-title',
			__( 'Button to Close Widget Title', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_button_to_close_widget_title'
			)
		);

		// Стиль кнопки "Спасибо, я уже с вами"
		add_settings_field(
			$prefix . '-common-button-to-close-widget-style',
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
			$prefix . '-common-delay-before-show-button-to-close-widget',
			__( 'Delay Before Show Button to Close Widget (sec.)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_delay_before_show_bottom_button'
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
				'field' => $scp_prefix . 'setting_container_width'
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
				'field' => $scp_prefix . 'setting_container_height'
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
				'field' => $scp_prefix . 'setting_border_radius'
			)
		);

		// Цвет фоновой заливки родительского контейннера
		add_settings_field(
			$prefix . '-common-overlay-color',
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
			$prefix . '-common-overlay-opacity',
			__( 'Overlay Opacity', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_overlay_opacity'
			)
		);

		// Загрузка фонового изображения виджета
		add_settings_field(
			$prefix . '-common-background-image',
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
	 * Общие настройки (вкладка "События")
	 */
	public function init_settings_common_events( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-events';

		// Используется в do_settings_section
		$options_page = $prefix . '-group-events';

		// ID секций настроек
		$section_when_should_the_popup_appear = $prefix . '-section-when-should-the-popup-appear';
		$section_who_should_see_the_popup     = $prefix . '-section-who-should-see-the-popup';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'when_should_the_popup_appear', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_after_n_seconds', 'absint' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_after_clicking_on_element', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent', 'absint' );
		register_setting( $group, $scp_prefix . 'popup_will_appear_on_exit_intent', 'absint' );
		register_setting( $group, $scp_prefix . 'who_should_see_the_popup', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'visitor_opened_at_least_n_number_of_pages', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_display_after_n_days', 'absint' );

		add_settings_section(
			$section_when_should_the_popup_appear,
			__( 'When Should the Popup Appear?', L10N_SCP_PREFIX ),
			array( & $this, 'settings_section_when_should_the_popup_appear' ),
			$options_page
		);

		// При наступлении каких событий показывать окно
		add_settings_field(
			$prefix . '-common-when-should-the-popup-appear',
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
			$prefix . '-popup-will-appear-after-n-seconds',
			__( 'Popup Will Appear After N Second(s)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_n_seconds'
			)
		);

		// Отображение окна при клике на CSS-селектор
		add_settings_field(
			$prefix . '-popup-will-appear-after-clicking-on-element',
			__( 'Popup Will Appear After Clicking on the Given CSS Selector', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_clicking_on_element'
			)
		);

		// Отображение окна при прокрутке страницы на N процентов
		add_settings_field(
			$prefix . '-popup-will-appear-after-scrolling-down-n-percent',
			__( 'Popup Will Appear After Scrolling Down at Least N Percent', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_when_should_the_popup_appear,
			array(
				'field' => $scp_prefix . 'popup_will_appear_after_scrolling_down_n_percent'
			)
		);

		// Отображение окна при перемещении мыши за границы окна
		add_settings_field(
			$prefix . '-popup-will-appear-on-exit-intent',
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
			$prefix . '-who-should-see-the-popup',
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
			$prefix . '-visitor-opened-at-least-n-number-of-pages',
			__( 'Visitor Opened at Least N Number of Pages', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'visitor_opened_at_least_n_number_of_pages'
			)
		);

		// Повторный показ окна через N дней
		add_settings_field(
			$prefix . '-common-display-after-n-days',
			__( 'Display After N-days', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section_who_should_see_the_popup,
			array(
				'field' => $scp_prefix . 'setting_display_after_n_days'
			)
		);
	}

	/**
	 * Общие настройки (вкладка "Управление")
	 */
	public function init_settings_common_management( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-management';

		// Используется в do_settings_section
		$options_page = $prefix . '-group-management';

		// ID секции
		$section = $prefix . '-section-common-management';

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
			$prefix . '-common-remove-settings-on-uninstall',
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
	 * Настройки Facebook
	 */
	private function init_settings_facebook( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-facebook';

		// Используется в do_settings_section
		$options_page = $prefix . '_facebook_options';

		// ID секции
		$section = $prefix . '-section-facebook';

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
				'field' => $scp_prefix . 'setting_use_facebook',
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
				'field' => $scp_prefix . 'setting_facebook_tab_caption'
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
				'field' => $scp_prefix . 'setting_facebook_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-facebook-description',
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
			$prefix . '-facebook-application-id',
			__( 'Application ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_application_id'
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
				'field' => $scp_prefix . 'setting_facebook_page_url'
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
				'field' => $scp_prefix . 'setting_facebook_locale'
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
				'field' => $scp_prefix . 'setting_facebook_width'
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
				'field' => $scp_prefix . 'setting_facebook_height'
			)
		);

		// Адаптировать виджет под ширину контейнера
		add_settings_field(
			$prefix . '-facebook-adapt-container-width',
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
			$prefix . '-facebook-use-small-header',
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
			$prefix . '-facebook-hide-cover',
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
			$prefix . '-facebook-show-facepile',
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
			$prefix . '-facebook-tabs',
			__( 'Show Content from Tabs', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_facebook_tabs' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_facebook_tabs'
			)
		);
	}

	/**
	 * Настройки ВКонтакте
	 */
	private function init_settings_vkontakte( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-vkontakte';

		// Используется в do_settings_section
		$options_page = $prefix . '_vkontakte_options';

		// ID секции
		$section = $prefix . '-section-vkontakte';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_vkontakte' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_show_description' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_page_or_group_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_height', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_layout', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_color_background', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_color_text', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_color_button', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_vkontakte_delay_before_render', 'absint' );

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
				'field' => $scp_prefix . 'setting_use_vkontakte',
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
				'field' => $scp_prefix . 'setting_vkontakte_tab_caption'
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
				'field' => $scp_prefix . 'setting_vkontakte_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-vkontakte-description',
			__( 'Description Above The Widget', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_wysiwyg' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_description'
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
				'field' => $scp_prefix . 'setting_vkontakte_page_or_group_id'
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
				'field' => $scp_prefix . 'setting_vkontakte_width'
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
				'field' => $scp_prefix . 'setting_vkontakte_height'
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
				'field' => $scp_prefix . 'setting_vkontakte_layout'
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
				'field' => $scp_prefix . 'setting_vkontakte_color_background'
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
				'field' => $scp_prefix . 'setting_vkontakte_color_text'
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
				'field' => $scp_prefix . 'setting_vkontakte_color_button'
			)
		);

		// Задержка в ms перед отрисовкой виджета (при проблемах в Firefox)
		add_settings_field(
			$prefix . '-vkontakte-delay-before-render',
			__( 'Delay before render widget (in ms.)', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_vkontakte_delay_before_render'
			)
		);
	}

	/**
	 * Настройки Одноклассников
	 */
	private function init_settings_odnoklassniki( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-odnoklassniki';

		// Используется в do_settings_section
		$options_page = $prefix . '_odnoklassniki_options';

		// ID секции
		$section = $prefix . '-section-odnoklassniki';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_odnoklassniki' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_show_description' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_odnoklassniki_group_id', 'sanitize_text_field' );
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
			$prefix . '-use-odnoklassniki',
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
			$prefix . '-odnoklassniki-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_tab_caption'
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
				'field' => $scp_prefix . 'setting_odnoklassniki_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-odnoklassniki-description',
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
			$prefix . '-odnoklassniki-group-id',
			__( 'Odnoklassniki Group ID', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_odnoklassniki_group_id'
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
				'field' => $scp_prefix . 'setting_odnoklassniki_width'
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
				'field' => $scp_prefix . 'setting_odnoklassniki_height'
			)
		);
	}

	/**
	 * Настройки Google+
	 */
	private function init_settings_googleplus( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-googleplus';

		// Используется в do_settings_section
		$options_page = $prefix . '_googleplus_options';

		// ID секции
		$section = $prefix . '-section-googleplus';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_googleplus' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_show_description' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_googleplus_page_url', 'esc_url' );
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
			$prefix . '-use-googleplus',
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
			$prefix . '-googleplus-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_tab_caption'
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
				'field' => $scp_prefix . 'setting_googleplus_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-googleplus-description',
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
			$prefix . '-googleplus-page-type',
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
			$prefix . '-googleplus-page-url',
			__( 'Google+ Page URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_googleplus_page_url'
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
				'field' => $scp_prefix . 'setting_googleplus_locale'
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
				'field' => $scp_prefix . 'setting_googleplus_size'
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
				'field' => $scp_prefix . 'setting_googleplus_theme'
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
				'field' => $scp_prefix . 'setting_googleplus_show_cover_photo'
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
				'field' => $scp_prefix . 'setting_googleplus_show_tagline'
			)
		);
	}

	/**
	 * Настройки Twitter
	 */
	private function init_settings_twitter( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-twitter';

		// Используется в do_settings_section
		$options_page = $prefix . '_twitter_options';

		// ID секции
		$section = $prefix . '-section-twitter';

		// Не забывать добавлять новые опции в uninstall()
		register_setting( $group, $scp_prefix . 'setting_use_twitter' );
		register_setting( $group, $scp_prefix . 'setting_twitter_tab_caption', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_show_description' );
		register_setting( $group, $scp_prefix . 'setting_twitter_description', 'wp_kses_post' );
		register_setting( $group, $scp_prefix . 'setting_twitter_username', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_widget_id', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_theme', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_link_color', 'sanitize_text_field' );
		register_setting( $group, $scp_prefix . 'setting_twitter_tweet_limit', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_show_replies', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_width', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_height', 'absint' );
		register_setting( $group, $scp_prefix . 'setting_twitter_chrome' );

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
				'field' => $scp_prefix . 'setting_use_twitter',
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
				'field' => $scp_prefix . 'setting_twitter_tab_caption'
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
				'field' => $scp_prefix . 'setting_twitter_show_description'
			)
		);

		// Надпись над виджетом
		add_settings_field(
			$prefix . '-twitter-description',
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
			$prefix . '-twitter-username',
			__( '@Username', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_twitter_username'
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
				'field' => $scp_prefix . 'setting_twitter_widget_id'
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
				'field' => $scp_prefix . 'setting_twitter_theme'
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
				'field' => $scp_prefix . 'setting_twitter_link_color'
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
				'field' => $scp_prefix . 'setting_twitter_tweet_limit'
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
				'field' => $scp_prefix . 'setting_twitter_show_replies'
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
				'field' => $scp_prefix . 'setting_twitter_width'
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
				'field' => $scp_prefix . 'setting_twitter_height'
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
				'field' => $scp_prefix . 'setting_twitter_chrome'
			)
		);
	}

	/**
	 * Настройки Pinterest
	 */
	private function init_settings_pinterest( $prefix ) {
		$scp_prefix = self::get_scp_prefix();

		// Используется в settings_field и do_settings_field
		$group = $prefix . '-group-pinterest';

		// Используется в do_settings_section
		$options_page = $prefix . '_pinterest_options';

		// ID секции
		$section = $prefix . '-section-pinterest';

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
			$prefix . '-use-pinterest',
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
			$prefix . '-pinterest-tab-caption',
			__( 'Tab Caption', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_tab_caption'
			)
		);

		// Показывать надпись над виджетом?
		add_settings_field(
			$prefix . '-pinterest-show-description',
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
			$prefix . '-pinterest-description',
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
			$prefix . '-pinterest-profile-url',
			__( 'Profile URL', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_profile_url'
			)
		);

		// Ширина изображений
		add_settings_field(
			$prefix . '-pinterest-image-width',
			__( 'Image Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_image_width'
			)
		);

		// Ширина виджета
		add_settings_field(
			$prefix . '-pinterest-width',
			__( 'Widget Width', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_width'
			)
		);

		// Высота виджета
		add_settings_field(
			$prefix . '-pinterest-height',
			__( 'Widget Height', L10N_SCP_PREFIX ),
			array( & $this, 'settings_field_input_text' ),
			$options_page,
			$section,
			array(
				'field' => $scp_prefix . 'setting_pinterest_height'
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
	public function settings_section_common_view() {
		_e( 'In this section, you can customize the appearance of the plugin', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "События" — "Когда показывать окно")
	 */
	public function settings_section_when_should_the_popup_appear() {
		_e( 'In this section, you can customize the events when the plugin will shown', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "События" – "Кому показывать окно")
	 */
	public function settings_section_who_should_see_the_popup() {
		_e( 'In this section, you can customize the events who should see the popup', L10N_SCP_PREFIX );
	}

	/**
	 * Описание общих настроек (таб "Управление")
	 */
	public function settings_section_common_management() {
		_e( 'In this section, you can export, import and remove plugin settings', L10N_SCP_PREFIX );
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
	 * Описание настроек Pinterest
	 */
	public function settings_section_pinterest() {
		_e( 'In this section, you must fill out the data to display the Pinterest Profile Widget in a popup window', L10N_SCP_PREFIX );
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
			for ( $idx = 0; $idx < count( $chains ); $idx++ ) {
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

		$chains = preg_split( "/,/", $value );

		$format = '<input type="checkbox" id="%s" class="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html = '';
		foreach ( $options as $option_name => $label ) {
			$checked = '';
			for ( $idx = 0; $idx < count( $chains ); $idx++ ) {
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
			for ( $idx = 0; $idx < count( $chains ); $idx++ ) {
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

		$scp_prefix = self::get_scp_prefix();

		echo '<ul id="scp-sortable">';
		foreach ( $values as $key ) {
			$setting_value = get_option( $scp_prefix . 'setting_use_' . $key );
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

		// Pinterest
		add_submenu_page(
			'social_community_popup', // Родительский пункт меню
			__( 'Pinterest Options', L10N_SCP_PREFIX ), // Название пункта на его странице
			__( 'Pinterest', L10N_SCP_PREFIX ), // Пункт меню
			'administrator',
			'social_community_popup_pinterest_options',
			array( & $this, 'plugin_settings_page_pinterest_options' )
		);
	}

	/**
	 * Adds menu with submenus to WordPress Admin Bar
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 * @since 0.7.3
	 */
	public function admin_bar_menu( $wp_admin_bar ) {
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		$args = array(
			'id'     => 'scp-admin-bar',
			'title'  => 'Social Community Popup',
		);
		$wp_admin_bar->add_node( $args );

		$menu_scp_settings = array(
			'parent' => 'scp-admin-bar',
			'id'     => 'scp-settings',
			'title'  => __( 'Settings', L10N_SCP_PREFIX ),
			'href'   => admin_url( 'admin.php?page=social_community_popup' )
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
		remove_submenu_page( 'index.php', 'social_community_popup_about' );
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS на страницу настроек
	 */
	public function admin_enqueue_scripts() {
		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		wp_register_style( 'social-community-popup-admin-style', plugins_url( 'css/admin.css?' . $version, __FILE__ ) );
		wp_enqueue_style( 'social-community-popup-admin-style' );

		wp_register_style( 'jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui-css' );

		wp_enqueue_script( 'jquery-ui-draggable', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-sortable', array( 'jquery' ) );

		$this->add_cookies_script( $version );

		wp_register_script( 'social-community-popup-admin-script', plugins_url( 'js/admin.js?' . $version, __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'social-community-popup-admin-script' );

		if ( 'social_community_popup' == get_current_screen()->id ) {
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');

			wp_enqueue_script('media-upload');
		}
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
	 * Добавляем всплывающее окно в подвале сайта
	 */
	public function wp_footer() {
		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		echo "<script type='text/javascript' src='" . plugins_url( 'js/scp.php?' . $version, __FILE__ ) . "'></script>";
	}

	/**
	 * Добавляем свои скрипты и таблицы CSS
	 */
	public function enqueue_scripts() {
		$scp_prefix = self::get_scp_prefix();
		$version = get_option( $scp_prefix . 'version' );

		$this->add_cookies_script( $version );

		wp_register_script( 'social-community-popup-script', plugins_url( 'js/scripts.js?' . $version, __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'social-community-popup-script' );

		wp_register_style( 'social-community-popup-style', plugins_url( 'css/styles.css?' . $version, __FILE__ ) );
		wp_enqueue_style( 'social-community-popup-style' );
	}

	private function add_cookies_script( $version ) {
		wp_register_script( 'social-community-popup-cookies-script', plugins_url( 'js/cookies.js?' . $version, __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'social-community-popup-cookies-script', 'scp', array(
		   'clearCookiesMessage' => __( 'Page will be reload after clear cookies. Continue?', L10N_SCP_PREFIX )
		));
		wp_enqueue_script( 'social-community-popup-cookies-script' );
	}
}

