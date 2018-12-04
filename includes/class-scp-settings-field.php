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

		$html .= '<p>' . esc_attr( 'Disabled Social Networks Marked As Red', L10N_SCP_PREFIX ) . '</p>';
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
			'optgroup'   => esc_attr( 'Attention Seekers', L10N_SCP_PREFIX ),
			'bounce'     => esc_attr( 'Bounce', L10N_SCP_PREFIX ),
			'rubberBand' => esc_attr( 'Rubber Band', L10N_SCP_PREFIX ),
			'shake'      => esc_attr( 'Shake', L10N_SCP_PREFIX ),
			'swing'      => esc_attr( 'Swing', L10N_SCP_PREFIX ),
			'tada'       => esc_attr( 'TaDa', L10N_SCP_PREFIX ),
			'wobble'     => esc_attr( 'Wobble', L10N_SCP_PREFIX ),
			'jello'      => esc_attr( 'Jello', L10N_SCP_PREFIX ),
		);

		$bouncing_entrances = array(
			'optgroup'      => esc_attr( 'Bouncing Entrances', L10N_SCP_PREFIX ),
			'bounceIn'      => esc_attr( 'Bounce In', L10N_SCP_PREFIX ),
			'bounceInUp'    => esc_attr( 'Bounce In Up', L10N_SCP_PREFIX ),
			'bounceInRight' => esc_attr( 'Bounce In Right', L10N_SCP_PREFIX ),
			'bounceInDown'  => esc_attr( 'Bounce In Down', L10N_SCP_PREFIX ),
			'bounceInLeft'  => esc_attr( 'Bounce In Left', L10N_SCP_PREFIX ),
		);

		$fading_entrances = array(
			'optgroup'    => esc_attr( 'Fading Entrances', L10N_SCP_PREFIX ),
			'fadeIn'      => esc_attr( 'Fade In', L10N_SCP_PREFIX ),
			'fadeInDown'  => esc_attr( 'Fade In Down', L10N_SCP_PREFIX ),
			'fadeInLeft'  => esc_attr( 'Fade In Left', L10N_SCP_PREFIX ),
			'fadeInRight' => esc_attr( 'Fade In Right', L10N_SCP_PREFIX ),
			'fadeInUp'    => esc_attr( 'Fade In Up', L10N_SCP_PREFIX ),
		);

		$flippers = array(
			'optgroup' => esc_attr( 'Flippers', L10N_SCP_PREFIX ),
			'flip'     => esc_attr( 'Flip', L10N_SCP_PREFIX ),
			'flipInX'  => esc_attr( 'Flip In X', L10N_SCP_PREFIX ),
			'flipInY'  => esc_attr( 'Flip In Y', L10N_SCP_PREFIX ),
		);

		$lightspeed = array(
			'optgroup'     => esc_attr( 'Lightspeed', L10N_SCP_PREFIX ),
			'lightSpeedIn' => esc_attr( 'Light Speed In', L10N_SCP_PREFIX ),
		);

		$rotating_entrances = array(
			'optgroup'          => esc_attr( 'Rotating Entrances', L10N_SCP_PREFIX ),
			'rotateIn'          => esc_attr( 'Rotate In', L10N_SCP_PREFIX ),
			'rotateInDownLeft'  => esc_attr( 'Rotate In Down Left', L10N_SCP_PREFIX ),
			'rotateInDownRight' => esc_attr( 'Rotate In Down Right', L10N_SCP_PREFIX ),
			'rotateInUpLeft'    => esc_attr( 'Rotate In Up Left', L10N_SCP_PREFIX ),
			'rotateInUpRight'   => esc_attr( 'Rotate In Up Right', L10N_SCP_PREFIX ),
		);

		$sliding_entrances = array(
			'optgroup'     => esc_attr( 'Sliding Entrances', L10N_SCP_PREFIX ),
			'slideInDown'  => esc_attr( 'Slide In Down', L10N_SCP_PREFIX ),
			'slideInLeft'  => esc_attr( 'Slide In Left', L10N_SCP_PREFIX ),
			'slideInRight' => esc_attr( 'Slide In Right', L10N_SCP_PREFIX ),
			'slideInUp'    => esc_attr( 'Slide In Up', L10N_SCP_PREFIX ),
		);

		$zoom_entrances = array(
			'optgroup'    => esc_attr( 'Zoom Entrances', L10N_SCP_PREFIX ),
			'zoomIn'      => esc_attr( 'Zoom In', L10N_SCP_PREFIX ),
			'zoomInDown'  => esc_attr( 'Zoom In Down', L10N_SCP_PREFIX ),
			'zoomInLeft'  => esc_attr( 'Zoom In Left', L10N_SCP_PREFIX ),
			'zoomInRight' => esc_attr( 'Zoom In Right', L10N_SCP_PREFIX ),
			'zoomInUp'    => esc_attr( 'Zoom In Up', L10N_SCP_PREFIX ),
		);

		$specials = array(
			'optgroup' => esc_attr( 'Specials', L10N_SCP_PREFIX ),
			'rollIn'   => esc_attr( 'Roll In', L10N_SCP_PREFIX ),
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

		$html .= ' <input type="button" class="button" id="smp_play_animation" value="' . esc_attr( 'Play Animation', L10N_SCP_PREFIX ) . '">';
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
		$options['lg'] = esc_attr( 'Normal Size', L10N_SCP_PREFIX );
		$options['2x'] = esc_attr( '2x', L10N_SCP_PREFIX );
		$options['3x'] = esc_attr( '3x', L10N_SCP_PREFIX );
		$options['4x'] = esc_attr( '4x', L10N_SCP_PREFIX );
		$options['5x'] = esc_attr( '5x', L10N_SCP_PREFIX );

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

		$html  = sprintf( $format, $field . '_0', $field, 'inside', checked( $value, 'inside', false ), $field . '_0', esc_attr( 'Inside Container', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'outside', checked( $value, 'outside', false ), $field . '_1', esc_attr( 'Outside Container', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'none', checked( $value, 'none', false ), $field . '_2', esc_attr( 'Don\'t show', L10N_SCP_PREFIX ) );

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

		$html  = sprintf( $format, $field . '_0', $field, 'link', checked( $value, 'link', false ), $field . '_0', esc_attr( 'Link', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'green', checked( $value, 'green', false ), $field . '_1', esc_attr( 'Green button', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'blue', checked( $value, 'blue', false ), $field . '_2', esc_attr( 'Blue button', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_3', $field, 'red', checked( $value, 'red', false ), $field . '_3', esc_attr( 'Red button', L10N_SCP_PREFIX ) );

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
		$html .= '<input id="scp_upload_background_image" type="button" class="button" value="' . esc_attr( 'Upload Image', L10N_SCP_PREFIX ) . '" /><br />';
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
		$options['after_n_seconds']                = esc_attr( 'Popup will appear after N second(s)', L10N_SCP_PREFIX );
		$options['after_clicking_on_element']      = esc_attr( 'Popup will appear after clicking on the given CSS selector', L10N_SCP_PREFIX );
		$options['after_scrolling_down_n_percent'] = esc_attr( 'Popup will appear after a visitor has scrolled on your page at least N percent', L10N_SCP_PREFIX );
		$options['on_exit_intent']                 = esc_attr( 'Popup will appear on exit-intent (when mouse has moved out from the page)', L10N_SCP_PREFIX );

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
		$options['visitor_opened_at_least_n_number_of_pages'] = esc_attr( 'Visitor opened at least N number of page(s)', L10N_SCP_PREFIX );
		$options['visitor_registered_and_role_equals_to']     = esc_attr( 'Registered Users Who Should See the Popup', L10N_SCP_PREFIX );

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
		$options['all_registered_users']                = esc_attr( 'All Registered Users', L10N_SCP_PREFIX );
		$options['exclude_administrators']              = esc_attr( 'All Registered Users Exclude Administrators', L10N_SCP_PREFIX );
		$options['exclude_administrators_and_managers'] = esc_attr( 'All Registered Users Exclude Administrators and Managers', L10N_SCP_PREFIX );

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

		$html  = sprintf( $format, $field . '_0', $field, 'ru_RU', checked( $value, 'ru_RU', false ), $field . '_0', esc_attr( 'Russian', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en_US', checked( $value, 'en_US', false ), $field . '_1', esc_attr( 'English', L10N_SCP_PREFIX ) );

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
		$options['timeline'] = esc_attr( 'Timelime', L10N_SCP_PREFIX );
		$options['messages'] = esc_attr( 'Messages', L10N_SCP_PREFIX );
		$options['events']   = esc_attr( 'Events', L10N_SCP_PREFIX );

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

		$html  = sprintf( $format, $field . '_0', $field, '0', checked( $value, 0, false ), $field . '_0', esc_attr( 'Members', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, '2', checked( $value, 2, false ), $field . '_2', esc_attr( 'News', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, '1', checked( $value, 1, false ), $field . '_1', esc_attr( 'Name', L10N_SCP_PREFIX ) );

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

		$html  = sprintf( $format, $field . '_0', $field, 'person', checked( $value, 'person', false ), $field . '_0', esc_attr( 'Google+ Person', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'page', checked( $value, 'page', false ), $field . '_1', esc_attr( 'Google+ Page', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'community', checked( $value, 'community', false ), $field . '_2', esc_attr( 'Google+ Community', L10N_SCP_PREFIX ) );

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

		$html  = sprintf( $format, $field . '_0', $field, 'portrait', checked( $value, 'portrait', false ), $field . '_0', esc_attr( 'Portrait', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'landscape', checked( $value, 'landscape', false ), $field . '_1', esc_attr( 'Landscape', L10N_SCP_PREFIX ) );

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

		$html  = sprintf( $format, $field . '_0', $field, 'ru', checked( $value, 'ru', false ), $field . '_0', esc_attr( 'Russian', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en', checked( $value, 'en', false ), $field . '_1', esc_attr( 'English', L10N_SCP_PREFIX ) );

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

		$html  = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', esc_attr( 'Light', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', esc_attr( 'Dark', L10N_SCP_PREFIX ) );

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

		$html  = sprintf( $format, $field . '_0', $field, 'ru', checked( $value, 'ru', false ), $field . '_0', esc_attr( 'Russian', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en', checked( $value, 'en', false ), $field . '_1', esc_attr( 'English', L10N_SCP_PREFIX ) );

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

		$html  = sprintf( $format, $field . '_0', $field, 'follow_button', checked( $value, 'follow_button', false ), $field . '_0', esc_attr( 'Follow Button', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'timeline', checked( $value, 'timeline', false ), $field . '_1', esc_attr( 'Timeline', L10N_SCP_PREFIX ) );

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
		$options['left']   = esc_attr( 'Left', L10N_SCP_PREFIX );
		$options['center'] = esc_attr( 'Center', L10N_SCP_PREFIX );
		$options['right']  = esc_attr( 'Right', L10N_SCP_PREFIX );

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

		$html  = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', esc_attr( 'Light', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', esc_attr( 'Dark', L10N_SCP_PREFIX ) );

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
		$options['noheader']     = esc_attr( 'No Header', L10N_SCP_PREFIX );
		$options['nofooter']     = esc_attr( 'No Footer', L10N_SCP_PREFIX );
		$options['noborders']    = esc_attr( 'No Borders', L10N_SCP_PREFIX );
		$options['noscrollbars'] = esc_attr( 'No Scrollbars', L10N_SCP_PREFIX );
		$options['transparent']  = esc_attr( 'Transparent (Removes the background color)', L10N_SCP_PREFIX );

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
