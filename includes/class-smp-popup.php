<?php
/**
 * Popup Class
 *
 * @package Social_Media_Popup
 * @author  Alexander Kadyrov
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    https://github.com/gruz0/social-media-popup
 */

/**
 * SMP_Popup
 *
 * @since 0.7.6
 */
class SMP_Popup {
	/**
	 * Prepare popup HTML
	 *
	 * @param string $prefix Plugin prefix
	 * @return string
	 */
	public static function render( $prefix ) {
		$options     = array();
		$all_options = wp_load_alloptions();

		foreach ( $all_options as $name => $value ) {
			if ( stristr( $name, $prefix ) ) {
				$name             = str_replace( $prefix, '', $name );
				$options[ $name ] = $value;
			}
		}

		$events_descriptions = array(
			'window_showed_immediately'       => $options['tracking_event_label_window_showed_immediately'],
			'window_showed_with_delay'        => $options['tracking_event_label_window_showed_with_delay'],
			'window_showed_after_click'       => $options['tracking_event_label_window_showed_after_click'],
			'window_showed_on_scrolling_down' => $options['tracking_event_label_window_showed_on_scrolling_down'],
			'window_showed_on_exit_intent'    => $options['tracking_event_label_window_showed_on_exit_intent'],
			'no_events_fired'                 => $options['tracking_event_label_no_events_fired'],
			'on_delay'                        => $options['tracking_event_label_on_delay'],
			'after_click'                     => $options['tracking_event_label_after_click'],
			'on_scrolling_down'               => $options['tracking_event_label_on_scrolling_down'],
			'on_exit_intent'                  => $options['tracking_event_label_on_exit_intent'],
		);

		$debug_mode = '1' === $options['setting_debug_mode'];

		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		$template_options = array(
			'use_events_tracking'                             => '1' === $options['use_events_tracking'],
			'do_not_use_tracking_in_debug_mode'               => ( $debug_mode && '1' === $options['do_not_use_tracking_in_debug_mode'] ),
			'push_events_to_aquisition_social_plugins'        => '1' === $options['push_events_to_aquisition_social_plugins'],
			'push_events_when_displaying_window'              => '1' === $options['push_events_when_displaying_window'],
			'push_events_when_subscribing_on_social_networks' => '1' === $options['push_events_when_subscribing_on_social_networks'],
			'add_window_events_descriptions'                  => '1' === $options['add_window_events_descriptions'],
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned

		$template = new SMP_Template(
			$template_options,
			$events_descriptions
		);

		// При включённом режиме отладки плагин работает только для администратора сайта
		if ( $debug_mode ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

		// Если режим отладки выключен и есть кука закрытия окна или пользователь администратор — не показываем окно
		} else {
			// Проверяем, что текущий пользователь залогинен в админку и затем проверяем его роль
			if ( is_user_logged_in() ) {
				switch ( $options['visitor_registered_and_role_equals_to'] ) {
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

		$show_on_mobile = '1' === $options['setting_show_on_mobile_devices'];
		$wp_is_mobile   = wp_is_mobile();

		// Отключаем работу плагина на мобильных устройствах
		if ( $wp_is_mobile && ! $show_on_mobile ) return;

		$after_n_days = absint( $options['setting_display_after_n_days'] );

		//
		// Когда показывать окно
		//
		$when_should_the_popup_appear        = split_string_by_comma( $options['when_should_the_popup_appear'] );
		$when_should_the_popup_appear_events = array(
			'after_n_seconds',
			'after_clicking_on_element',
			'after_scrolling_down_n_percent',
			'on_exit_intent',
		);

		$popup_will_appear_after_n_seconds                = absint( $options['popup_will_appear_after_n_seconds'] );
		$popup_will_appear_after_clicking_on_element      = $options['popup_will_appear_after_clicking_on_element'];
		$popup_will_appear_after_scrolling_down_n_percent = absint( $options['popup_will_appear_after_scrolling_down_n_percent'] );
		$popup_will_appear_on_exit_intent                 = '1' === $options['popup_will_appear_on_exit_intent'];

		// Дополнительные события
		$event_hide_element_after_click_on_it      = '1' === $options['event_hide_element_after_click_on_it'];
		$do_not_use_cookies_after_click_on_element = '1' === $options['do_not_use_cookies_after_click_on_element'];

		//
		// Кому показывать окно
		//
		$who_should_see_the_popup        = split_string_by_comma( $options['who_should_see_the_popup'] );
		$who_should_see_the_popup_events = array(
			'visitor_opened_at_least_n_number_of_pages',
		);

		$visitor_opened_at_least_n_number_of_pages = absint( $options['visitor_opened_at_least_n_number_of_pages'] );

		// Если true, тогда окно будет показываться с учётом событий "Каким посетителям показывать окно"
		$who_should_see_the_popup_fired = false;

		// Используется при обработке событий "Когда показывать окно" и только вместе с $who_should_see_the_popup_fired
		// Значение примет true только в том случае, если хотя бы одно из событий "Кому показывать окно" активно
		$who_should_see_the_popup_present = false;

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
				if ( isset( $_COOKIE[ $page_views_cookie ] ) ) {
					$page_views = absint( $_COOKIE[ $page_views_cookie ] ) + 1;
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

		// Социальные сети
		$use_facebook      = '1' === $options['setting_use_facebook'];
		$use_vkontakte     = '1' === $options['setting_use_vkontakte'];
		$use_odnoklassniki = '1' === $options['setting_use_odnoklassniki'];
		$use_googleplus    = '1' === $options['setting_use_googleplus'];
		$use_twitter       = '1' === $options['setting_use_twitter'];
		$use_pinterest     = '1' === $options['setting_use_pinterest'];

		// Настройка плагина
		$tabs_order                      = explode( ',', $options['setting_tabs_order'] );
		$container_width                 = absint( $options['setting_container_width'] );
		$container_height                = absint( $options['setting_container_height'] );
		$border_radius                   = absint( $options['setting_border_radius'] );
		$close_by_clicking_anywhere      = '1' === $options['setting_close_popup_by_clicking_anywhere'];
		$close_when_esc_pressed          = '1' === $options['setting_close_popup_when_esc_pressed'];
		$show_close_button_in            = $options['setting_show_close_button_in'];
		$overlay_color                   = $options['setting_overlay_color'];
		$overlay_opacity                 = absint( $options['setting_overlay_opacity'] );
		$align_tabs_to_center            = absint( $options['setting_align_tabs_to_center'] );
		$delay_before_show_bottom_button = absint( $options['setting_delay_before_show_bottom_button'] );
		$background_image                = esc_url( $options['setting_background_image'] );
		$use_animation                   = '1' === $options['setting_use_animation'];
		$animation_style                 = esc_attr( $options['setting_animation_style'] );

		//
		// START RENDER
		//

		$content = '';

		SMP_Provider::set_template( $template );

		$active_providers = array();
		foreach ( SMP_Provider::available_providers() as $provider_name ) {
			$provider = SMP_Provider::create( $provider_name, $options );

			if ( $provider->is_active() ) {
				$active_providers[ $provider_name ] = $provider;
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

				$content .= '<div class="scp-mobile-title">' . $options['setting_plugin_title_on_mobile_devices'] . '</div>';

				$content .= '<ul class="scp-icons">';

				$icon_size = 'fa-' . esc_attr( $options['setting_icons_size_on_mobile_devices'] );

				for ( $idx = 0, $size = count( $tabs_order ); $idx < $size; $idx++ ) {
					$provider_name = $tabs_order[ $idx ];

					// Выходим, если текущий провайдер из списка не выбран используемым
					if ( ! isset( $active_providers[ $provider_name ] ) ) continue;

					$provider = $active_providers[ $provider_name ];

					$width = $tab_index === $active_providers_count ? $last_tab_width : $tab_width;

					$args = array(
						'index'     => $tab_index++,
						'width'     => $width,
						'icon_size' => $icon_size,
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

				$border_radius_css    = $border_radius > 0 ? "border-radius:{$border_radius}px !important;" : '';
				$background_image_css = empty( $background_image ) ? '' : "background:#fff url('{$background_image}') center center no-repeat;";

				$popup_css  = '';
				$popup_css .= 'width:' . ( $container_width + 40 ) . 'px !important;height:' . ( $container_height + 10 ) . 'px !important;';
				$popup_css .= $border_radius_css;
				$popup_css .= $background_image_css;

				$scp_plugin_title  = trim( str_replace( "\r\n", '<br />', $options['setting_plugin_title'] ) );
				$show_plugin_title = mb_strlen( $scp_plugin_title ) > 0;

				$animation_class = $use_animation ? ' class="animated ' . $animation_style . '"' : '';
				$content        .= '<div id="popup" style="' . esc_attr( $popup_css ) . '"' . $animation_class . '>';

				if ( $show_plugin_title && 'inside' === $show_close_button_in ) {
					$content .= '<div class="top-close">';
					$content .= '<span class="close" title="' . esc_attr( 'Close Modal Dialog', 'social-media-popup' ) . '">&times;</span>';
					$content .= '</div>';
				}

				if ( 'outside' === $show_close_button_in ) {
					$content .= '<a href="#" class="close close-outside" title="' . esc_attr( 'Close Modal Dialog', 'social-media-popup' ) . '">&times;</a>';
				}

				$content .= '<div class="section" style="width:' . esc_attr( $container_width ) . 'px !important;height:' . esc_attr( $container_height ) . 'px !important;">';

				if ( $show_plugin_title ) {
					$content .= '<div class="plugin-title">' . $scp_plugin_title . '</div>';
				}

				if ( 1 === $active_providers_count && '1' === $options['setting_hide_tabs_if_one_widget_is_active'] ) {

				} else {
					$use_icons_instead_of_labels = '1' === $options['setting_use_icons_instead_of_labels_in_tabs'];
					$icon_size                   = 'fa-' . $options['setting_icons_size_on_desktop'];

					if ( $use_icons_instead_of_labels ) {
						$content .= '<ul class="scp-icons scp-icons-desktop">';
					} else {
						$content .= '<ul class="tabs"' . ( $align_tabs_to_center ? 'style="text-align:center;"' : '' ) . '>';
					}

					for ( $idx = 0, $size = count( $tabs_order ); $idx < $size; $idx++ ) {
						$provider_name = $tabs_order[ $idx ];

						// Выходим, если текущий провайдер из списка не выбран используемым
						if ( ! isset( $active_providers[ $provider_name ] ) ) continue;

						$provider = $active_providers[ $provider_name ];

						$width = $tab_index === $active_providers_count ? $last_tab_width : $tab_width;

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
						if ( ! $show_plugin_title && 'inside' === $show_close_button_in ) {
							$content .= '<li class="last-item"><span class="close" title="' . esc_attr( 'Close Modal Dialog', 'social-media-popup' ) . '">&times;</span></li>';
						}
					}

					$content .= '</ul>';
				}

				for ( $idx = 0, $size = count( $tabs_order ); $idx < $size; $idx++ ) {
					$provider_name = $tabs_order[ $idx ];

					// Выходим, если текущий провайдер из списка не выбран используемым
					if ( ! isset( $active_providers[ $provider_name ] ) ) continue;

					$provider = $active_providers[ $provider_name ];
					$content .= $provider->container();
				}
			}

			$content .= '</div>';

			if ( ! $wp_is_mobile ) {
				if ( '1' === $options['setting_show_button_to_close_widget'] ) {
					$button_to_close_widget_style = $options['setting_button_to_close_widget_style'];
					$button_to_close_widget_class = 'link' === $button_to_close_widget_style ? '' : 'scp-' . $button_to_close_widget_style . '-button';

					$content .= '<div class="dont-show-widget scp-button ' . esc_attr( $button_to_close_widget_class ) . '">';
					$content .= '<a href="#" class="close">' . esc_html( $options['setting_button_to_close_widget_title'] ) . '</a>';
					$content .= '</div>';
				}
			}

			$content .= '</div>';
		}

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
						$do_not_use_cookies_after_click_on_element,
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
				jQuery(document).ready(function($) {';

					// Проверяем событие "Не учитывать куки при клике на CSS-селектор"
					if ( when_should_the_popup_appear_has_event( $when_should_the_popup_appear, 'after_clicking_on_element' ) ) {
						if ( empty( $popup_will_appear_after_clicking_on_element ) || ! $do_not_use_cookies_after_click_on_element ) {
							$content .= 'if (is_scp_cookie_present()) return;';
						}
					} else {
						$content .= 'if (is_scp_cookie_present()) return;';
					}

					if ( $use_facebook ) {
						$content .= 'scp_prependFacebook($);';
					}

					if ( $use_vkontakte ) {
						$content .= 'scp_prependVK($);';
					}

					if ( $use_googleplus ) {
						$content .= 'scp_prependGooglePlus($);';
					}

					if ( $use_pinterest ) {
						$content .= 'scp_prependPinterest($);';
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
						$do_not_use_cookies_after_click_on_element,
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
}
