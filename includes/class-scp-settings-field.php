<?php
/**
 * Settings Fields Class
 *
 * @package    Social_Media_Popup
 * @author     Alexander Kadyrov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       https://github.com/gruz0/social-media-popup
 */

/**
 * SCP_Settings_Field
 *
 * @since 0.7.6
 */
class SCP_Settings_Field {
	/**
	 * Callback-шаблон для формирования текстового поля на странице настроек
	 *
	 * @since 0.7.5 Add placeholder
	 *
	 * @param array $args Options
	 */
	public static function settings_field_input_text( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$placeholder = ( empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' );

		$html = sprintf( '<input type="text" name="%s" id="%s" value="%s"' . $placeholder . ' />', $field, $field, $value );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования чекбокса на странице настроек
	 *
	 * @param array $args Options
	 */
	public static function settings_field_checkbox( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$html = sprintf( '<input type="checkbox" name="%s" id="%s" value="1" %s />', $field, $field, checked( $value, 1, false ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования WYSIWYG-редактора на странице настроек
	 *
	 * @param array $args Options
	 */
	public static function settings_field_wysiwyg( $args ) {
		$field = esc_attr( $args['field'] );
		$value = get_option( $field );

		$settings = array(
			'wpautop'       => true,
			'media_buttons' => true,
			'quicktags'     => true,
			'textarea_rows' => '5',
			'teeny'         => true,
			'textarea_name' => $field,
		);

		wp_editor( wp_kses_post( $value, ENT_QUOTES, 'UTF-8' ), $field, $settings );
	}

	/**
	 * Callback-шаблон для сортировки табов социальных сетей
	 *
	 * @param array $args Options
	 *
	 * @uses Social_Media_Popup::get_scp_prefix()
	 */
	public static function settings_field_tabs_order( $args ) {
		$field = $args['field'];
		$value = get_option( $field );

		$values = ( $value ) ? explode( ',', $value ) : array();

		$scp_prefix = Social_Media_Popup::get_scp_prefix();

		$html = '<ul id="scp-sortable">';
		foreach ( $values as $key ) {
			$setting_value = get_option( $scp_prefix . 'setting_use_' . $key );
			$class         = $setting_value ? '' : ' disabled';

			$html .= '<li class="ui-state-default' . $class . '">' . $key . '</li>';
		}
		$html .= '</ul>';

		$html .= '<p>' . esc_attr( 'Disabled Social Networks Marked As Red', 'social-media-popup' ) . '</p>';
		$html .= '<input type="hidden" name="' . $field . '" id="' . $field . '" value="' . $value . '" />';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования комбобокса выбора стиля анимации
	 *
	 * @since 0.7.6
	 *
	 * @param array $args Options
	 */
	public static function settings_field_animation_style( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$attention_seekers = array(
			'optgroup'   => 'Attention Seekers',
			'bounce'     => 'Bounce',
			'rubberBand' => 'Rubber Band',
			'shake'      => 'Shake',
			'swing'      => 'Swing',
			'tada'       => 'TaDa',
			'wobble'     => 'Wobble',
			'jello'      => 'Jello',
		);

		$bouncing_entrances = array(
			'optgroup'      => 'Bouncing Entrances',
			'bounceIn'      => 'Bounce In',
			'bounceInUp'    => 'Bounce In Up',
			'bounceInRight' => 'Bounce In Right',
			'bounceInDown'  => 'Bounce In Down',
			'bounceInLeft'  => 'Bounce In Left',
		);

		$fading_entrances = array(
			'optgroup'    => 'Fading Entrances',
			'fadeIn'      => 'Fade In',
			'fadeInDown'  => 'Fade In Down',
			'fadeInLeft'  => 'Fade In Left',
			'fadeInRight' => 'Fade In Right',
			'fadeInUp'    => 'Fade In Up',
		);

		$flippers = array(
			'optgroup' => 'Flippers',
			'flip'     => 'Flip',
			'flipInX'  => 'Flip In X',
			'flipInY'  => 'Flip In Y',
		);

		$lightspeed = array(
			'optgroup'     => 'Lightspeed',
			'lightSpeedIn' => 'Light Speed In',
		);

		$rotating_entrances = array(
			'optgroup'          => 'Rotating Entrances',
			'rotateIn'          => 'Rotate In',
			'rotateInDownLeft'  => 'Rotate In Down Left',
			'rotateInDownRight' => 'Rotate In Down Right',
			'rotateInUpLeft'    => 'Rotate In Up Left',
			'rotateInUpRight'   => 'Rotate In Up Right',
		);

		$sliding_entrances = array(
			'optgroup'     => 'Sliding Entrances',
			'slideInDown'  => 'Slide In Down',
			'slideInLeft'  => 'Slide In Left',
			'slideInRight' => 'Slide In Right',
			'slideInUp'    => 'Slide In Up',
		);

		$zoom_entrances = array(
			'optgroup'    => 'Zoom Entrances',
			'zoomIn'      => 'Zoom In',
			'zoomInDown'  => 'Zoom In Down',
			'zoomInLeft'  => 'Zoom In Left',
			'zoomInRight' => 'Zoom In Right',
			'zoomInUp'    => 'Zoom In Up',
		);

		$specials = array(
			'optgroup' => 'Specials',
			'rollIn'   => 'Roll In',
		);

		$styles = array(
			$attention_seekers,
			$bouncing_entrances,
			$fading_entrances,
			$flippers,
			$lightspeed,
			$rotating_entrances,
			$sliding_entrances,
			$zoom_entrances,
			$specials,
		);

		$html   = '<select id="smp_animation_style" name="' . $field . '">';
		$format = '<option value="%s"%s>%s</option>';

		for ( $idx = 0; $idx < count( $styles ); $idx++ ) {
			$options        = '';
			$optgroup_label = '';

			foreach ( $styles[ $idx ] as $key => $label ) {
				if ( 'optgroup' === $key ) {
					$optgroup_label = $label;
					continue;
				}

				$options .= sprintf( $format, $key, selected( $value, $key, false ), $label );
			}

			$html .= '<optgroup label="' . $optgroup_label . '">' . $options . '</optgroup>';
		}

		$html .= '</select>';

		$html .= ' <input type="button" class="button" id="smp_play_animation" value="' . esc_attr( 'Play Animation', 'social-media-popup' ) . '">';
		$html .= '<br /><div id="smp_animation" class="animated notice-success">Social Media Popup</div>';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования комбобокса выбора размера иконок социальных сетей
	 *
	 * @since 0.7.4
	 *
	 * @param array $args Options
	 */
	public static function settings_field_icons_size( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$options       = array();
		$options['lg'] = esc_attr( 'Normal Size', 'social-media-popup' );
		$options['2x'] = '2x';
		$options['3x'] = '3x';
		$options['4x'] = '4x';
		$options['5x'] = '5x';

		$html   = '<select id="scp_icon_size" name="' . $field . '">';
		$format = '<option value="%s"%s>%s</option>';

		foreach ( $options as $option_name => $label ) {
			$html .= sprintf( $format, $option_name, selected( $value, $option_name, false ), $label );
		}

		$html .= '</select>';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора местоположения кнопки закрытия окна в заголовке
	 *
	 * @param array $args Options
	 */
	public static function settings_field_show_close_button_in( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'inside', checked( $value, 'inside', false ), $field . '_0', esc_attr( 'Inside Container', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'outside', checked( $value, 'outside', false ), $field . '_1', esc_attr( 'Outside Container', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'none', checked( $value, 'none', false ), $field . '_2', esc_attr( "Don't show", 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования стиля кнопки "Спасибо, я уже с вами"
	 *
	 * @param array $args Options
	 */
	public static function settings_field_button_to_close_widget_style( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'link', checked( $value, 'link', false ), $field . '_0', esc_attr( 'Link', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'green', checked( $value, 'green', false ), $field . '_1', esc_attr( 'Green button', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'blue', checked( $value, 'blue', false ), $field . '_2', esc_attr( 'Blue button', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_3', $field, 'red', checked( $value, 'red', false ), $field . '_3', esc_attr( 'Red button', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования поля и кнопки для загрузки фонового изображения виджета
	 *
	 * @param array $args Options
	 */
	public static function settings_field_background_image( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$html  = '<input type="text" id="scp_background_image" name="' . $field . '" value="' . $value . '" />';
		$html .= '<input id="scp_upload_background_image" type="button" class="button" value="' . esc_attr( 'Upload Image', 'social-media-popup' ) . '" /><br />';
		$html .= '<div class="scp-background-image">' . ( empty( $value ) ? '' : '<img src="' . $value . '" />' ) . '</div>';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для выбора событий, при которых показывается окно
	 *
	 * @param array $args Options
	 */
	public static function settings_field_when_should_the_popup_appear( $args ) {
		$options                                   = array();
		$options['after_n_seconds']                = esc_attr( 'Popup will appear after N second(s)', 'social-media-popup' );
		$options['after_clicking_on_element']      = esc_attr( 'Popup will appear after clicking on the given CSS selector', 'social-media-popup' );
		$options['after_scrolling_down_n_percent'] = esc_attr( 'Popup will appear after a visitor has scrolled on your page at least N percent', 'social-media-popup' );
		$options['on_exit_intent']                 = esc_attr( 'Popup will appear on exit-intent (when mouse has moved out from the page)', 'social-media-popup' );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::render_checkboxes_with_hidden_field( esc_attr( $args['field'] ), $options );
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для выбора кому показывать окно плагина
	 *
	 * @param array $args Options
	 */
	public static function settings_field_who_should_see_the_popup( $args ) {
		$options = array();
		$options['visitor_opened_at_least_n_number_of_pages'] = esc_attr( 'Visitor opened at least N number of page(s)', 'social-media-popup' );
		$options['visitor_registered_and_role_equals_to']     = esc_attr( 'Registered Users Who Should See the Popup', 'social-media-popup' );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::render_checkboxes_with_hidden_field( esc_attr( $args['field'] ), $options );
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для выбора каким пользовательским ролям показывать плагин
	 *
	 * @param array $args Options
	 */
	public static function settings_field_visitor_registered_and_role_equals_to( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$options                                        = array();
		$options['all_registered_users']                = esc_attr( 'All Registered Users', 'social-media-popup' );
		$options['exclude_administrators']              = esc_attr( 'All Registered Users Exclude Administrators', 'social-media-popup' );
		$options['exclude_administrators_and_managers'] = esc_attr( 'All Registered Users Exclude Administrators and Managers', 'social-media-popup' );

		$chains = preg_split( '/,/', $value );

		$format = '<option value="%s"%s>%s</option>';

		$html = sprintf( '<select name="%s" id="%s" class="%s">', $field, $field, $field );
		foreach ( $options as $option_name => $label ) {
			$html .= sprintf( $format, $option_name, selected( $value, $option_name, false ), $label );
			$html .= '<br />';
		}
		$html .= '</select>';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Facebook
	 *
	 * @param array $args Options
	 */
	public static function settings_field_facebook_locale( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'ru_RU', checked( $value, 'ru_RU', false ), $field . '_0', esc_attr( 'Russian', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en_US', checked( $value, 'en_US', false ), $field . '_1', esc_attr( 'English', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования табов с выбором типа загружаемого контента для Facebook
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public static function settings_field_facebook_tabs( $args ) {
		$options             = array();
		$options['timeline'] = esc_attr( 'Timelime', 'social-media-popup' );
		$options['messages'] = esc_attr( 'Messages', 'social-media-popup' );
		$options['events']   = esc_attr( 'Events', 'social-media-popup' );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::render_checkboxes_with_hidden_field( esc_attr( $args['field'] ), $options );
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета ВКонтакте
	 *
	 * @param array $args Options
	 */
	public static function settings_field_vkontakte_layout( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, '0', checked( $value, 0, false ), $field . '_0', esc_attr( 'Members', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, '2', checked( $value, 2, false ), $field . '_2', esc_attr( 'News', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, '1', checked( $value, 1, false ), $field . '_1', esc_attr( 'Name', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа страницы Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_page_type( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'person', checked( $value, 'person', false ), $field . '_0', esc_attr( 'Google+ Person', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'page', checked( $value, 'page', false ), $field . '_1', esc_attr( 'Google+ Page', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'community', checked( $value, 'community', false ), $field . '_2', esc_attr( 'Google+ Community', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_layout( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'portrait', checked( $value, 'portrait', false ), $field . '_0', esc_attr( 'Portrait', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'landscape', checked( $value, 'landscape', false ), $field . '_1', esc_attr( 'Landscape', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_locale( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'ru', checked( $value, 'ru', false ), $field . '_0', esc_attr( 'Russian', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en', checked( $value, 'en', false ), $field . '_1', esc_attr( 'English', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_theme( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', esc_attr( 'Light', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', esc_attr( 'Dark', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Twitter
	 *
	 * @param array $args Options
	 *
	 * @since 0.7.6
	 */
	public static function settings_field_twitter_locale( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'ru', checked( $value, 'ru', false ), $field . '_0', esc_attr( 'Russian', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en', checked( $value, 'en', false ), $field . '_1', esc_attr( 'English', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора положения Follow Button относительно виджета Timeline
	 *
	 * @param array $args Options
	 *
	 * @since 0.7.6
	 */
	public static function settings_field_twitter_first_widget( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'follow_button', checked( $value, 'follow_button', false ), $field . '_0', esc_attr( 'Follow Button', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'timeline', checked( $value, 'timeline', false ), $field . '_1', esc_attr( 'Timeline', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования комбобокса выравнивания кнопки Twitter Follow Button
	 *
	 * @since 0.7.5
	 *
	 * @param array $args Options
	 */
	public static function settings_field_twitter_follow_button_align_by( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$options           = array();
		$options['left']   = esc_attr( 'Left', 'social-media-popup' );
		$options['center'] = esc_attr( 'Center', 'social-media-popup' );
		$options['right']  = esc_attr( 'Right', 'social-media-popup' );

		$html   = '<select name="' . $field . '">';
		$format = '<option value="%s"%s>%s</option>';

		foreach ( $options as $option_name => $label ) {
			$html .= sprintf( $format, $option_name, selected( $value, $option_name, false ), $label );
		}

		$html .= '</select>';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Twitter
	 *
	 * @param array $args Options
	 */
	public static function settings_field_twitter_theme( $args ) {
		$field = esc_attr( $args['field'] );
		$value = esc_attr( get_option( $field ) );

		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html  = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', esc_attr( 'Light', 'social-media-popup' ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', esc_attr( 'Dark', 'social-media-popup' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора свойств виджета Twitter
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public static function settings_field_twitter_chrome( $args ) {
		$options                 = array();
		$options['noheader']     = esc_attr( 'No Header', 'social-media-popup' );
		$options['nofooter']     = esc_attr( 'No Footer', 'social-media-popup' );
		$options['noborders']    = esc_attr( 'No Borders', 'social-media-popup' );
		$options['noscrollbars'] = esc_attr( 'No Scrollbars', 'social-media-popup' );
		$options['transparent']  = esc_attr( 'Transparent (Removes the background color)', 'social-media-popup' );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::render_checkboxes_with_hidden_field( esc_attr( $args['field'] ), $options );
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Wrapper to render checkboxes with hidden field to use as list of values
	 *
	 * @param string $field   Field
	 * @param array  $options Options
	 *
	 * @since 0.7.6
	 * @used_by SCP_Settings_Field::settings_field_twitter_chrome()
	 * @used_by SCP_Settings_Field::settings_field_facebook_tabs()
	 */
	public static function render_checkboxes_with_hidden_field( $field, $options ) {
		$value = get_option( $field );

		$chains = preg_split( '/,/', $value );

		$format  = '<input type="checkbox" id="%s" class="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';

		$html = '';
		foreach ( $options as $option_name => $label ) {
			$checked = '';
			for ( $idx = 0, $size = count( $chains ); $idx < $size; $idx++ ) {
				$checked = checked( $chains[ $idx ], $option_name, false );
				if ( strlen( $checked ) ) break;
			}

			$html .= sprintf( $format, $option_name, $field, $option_name, $checked, $option_name, $label );
			$html .= '<br />';
		}

		$html .= '<input type="hidden" id="' . $field . '" name="' . $field . '" value="' . esc_attr( $value ) . '" />';
		return $html;
	}
}
