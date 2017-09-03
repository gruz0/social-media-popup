<?php
/**
 * Settings Fields Class
 *
 * @package    Social_Media_Popup
 * @author     Alexander Kadyrov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://smp-plugin.com/
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
	public function settings_field_input_text( $args ) {
		$field = esc_attr( $args['field'] );
		$value = get_option( $field );

		$placeholder = ( empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' );

		echo sprintf( '<input type="text" name="%s" id="%s" value="%s"' . $placeholder . ' />', $field, $field, $value );
	}

	/**
	 * Callback-шаблон для формирования чекбокса на странице настроек
	 *
	 * @param array $args Options
	 */
	public function settings_field_checkbox( $args ) {
		$field = esc_attr( $args['field'] );
		$value = get_option( $field );
		echo sprintf( '<input type="checkbox" name="%s" id="%s" value="1" %s />', $field, $field, checked( $value, 1, false ) );
	}

	/**
	 * Callback-шаблон для формирования WYSIWYG-редактора на странице настроек
	 *
	 * @param array $args Options
	 */
	public function settings_field_wysiwyg( $args ) {
		$field = esc_attr( $args['field'] );
		$value = get_option( $field );
		$settings = array(
			'wpautop' => true,
			'media_buttons' => true,
			'quicktags' => true,
			'textarea_rows' => '5',
			'teeny' => true,
			'textarea_name' => $field,
		);

		wp_editor( wp_kses_post( $value , ENT_QUOTES, 'UTF-8' ), $field, $settings );
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
	 * Callback-шаблон для формирования комбобокса выбора стиля анимации
	 *
	 * @since 0.7.6
	 *
	 * @param array $args Options
	 */
	public function settings_field_animation_style( $args ) {
		$field = $args['field'];
		$value = get_option( $field );

		$attention_seekers = array(
			'optgroup'   => __( 'Attention Seekers', L10N_SCP_PREFIX ),
			'bounce'     => __( 'Bounce', L10N_SCP_PREFIX ),
			'rubberBand' => __( 'Rubber Band', L10N_SCP_PREFIX ),
			'shake'      => __( 'Shake', L10N_SCP_PREFIX ),
			'swing'      => __( 'Swing', L10N_SCP_PREFIX ),
			'tada'       => __( 'TaDa', L10N_SCP_PREFIX ),
			'wobble'     => __( 'Wobble', L10N_SCP_PREFIX ),
			'jello'      => __( 'Jello', L10N_SCP_PREFIX ),
		);

		$bouncing_entrances = array(
			'optgroup'      => __( 'Bouncing Entrances', L10N_SCP_PREFIX ),
			'bounceIn'      => __( 'Bounce In', L10N_SCP_PREFIX ),
			'bounceInUp'    => __( 'Bounce In Up', L10N_SCP_PREFIX ),
			'bounceInRight' => __( 'Bounce In Right', L10N_SCP_PREFIX ),
			'bounceInDown'  => __( 'Bounce In Down', L10N_SCP_PREFIX ),
			'bounceInLeft'  => __( 'Bounce In Left', L10N_SCP_PREFIX ),
		);

		$fading_entrances = array(
			'optgroup'    => __( 'Fading Entrances', L10N_SCP_PREFIX ),
			'fadeIn'      => __( 'Fade In', L10N_SCP_PREFIX ),
			'fadeInDown'  => __( 'Fade In Down', L10N_SCP_PREFIX ),
			'fadeInLeft'  => __( 'Fade In Left', L10N_SCP_PREFIX ),
			'fadeInRight' => __( 'Fade In Right', L10N_SCP_PREFIX ),
			'fadeInUp'    => __( 'Fade In Up', L10N_SCP_PREFIX ),
		);

		$flippers = array(
			'optgroup' => __( 'Flippers', L10N_SCP_PREFIX ),
			'flip'     => __( 'Flip', L10N_SCP_PREFIX ),
			'flipInX'  => __( 'Flip In X', L10N_SCP_PREFIX ),
			'flipInY'  => __( 'Flip In Y', L10N_SCP_PREFIX ),
		);

		$lightspeed = array(
			'optgroup'     => __( 'Lightspeed', L10N_SCP_PREFIX ),
			'lightSpeedIn' => __( 'Light Speed In', L10N_SCP_PREFIX ),
		);

		$rotating_entrances = array(
			'optgroup'          => __( 'Rotating Entrances', L10N_SCP_PREFIX ),
			'rotateIn'          => __( 'Rotate In', L10N_SCP_PREFIX ),
			'rotateInDownLeft'  => __( 'Rotate In Down Left', L10N_SCP_PREFIX ),
			'rotateInDownRight' => __( 'Rotate In Down Right', L10N_SCP_PREFIX ),
			'rotateInUpLeft'    => __( 'Rotate In Up Left', L10N_SCP_PREFIX ),
			'rotateInUpRight'   => __( 'Rotate In Up Right', L10N_SCP_PREFIX ),
		);

		$sliding_entrances = array(
			'optgroup'     => __( 'Sliding Entrances', L10N_SCP_PREFIX ),
			'slideInDown'  => __( 'Slide In Down', L10N_SCP_PREFIX ),
			'slideInLeft'  => __( 'Slide In Left', L10N_SCP_PREFIX ),
			'slideInRight' => __( 'Slide In Right', L10N_SCP_PREFIX ),
			'slideInUp'    => __( 'Slide In Up', L10N_SCP_PREFIX ),
		);

		$zoom_entrances = array(
			'optgroup'    => __( 'Zoom Entrances', L10N_SCP_PREFIX ),
			'zoomIn'      => __( 'Zoom In', L10N_SCP_PREFIX ),
			'zoomInDown'  => __( 'Zoom In Down', L10N_SCP_PREFIX ),
			'zoomInLeft'  => __( 'Zoom In Left', L10N_SCP_PREFIX ),
			'zoomInRight' => __( 'Zoom In Right', L10N_SCP_PREFIX ),
			'zoomInUp'    => __( 'Zoom In Up', L10N_SCP_PREFIX ),
		);

		$specials = array(
			'optgroup' => __( 'Specials', L10N_SCP_PREFIX ),
			'rollIn'   => __( 'Roll In', L10N_SCP_PREFIX ),
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

		$html .= ' <input type="button" class="button" id="smp_play_animation" value="' . __( 'Play Animation', L10N_SCP_PREFIX ) . '">';
		$html .= '<br /><div id="smp_animation" class="animated notice-success">Social Media Popup</div>';
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования комбобокса выбора размера иконок социальных сетей
	 *
	 * @since 0.7.4
	 *
	 * @param array $args Options
	 */
	public function settings_field_icons_size( $args ) {
		$field = $args['field'];
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
	 * Callback-шаблон для формирования радио-кнопок для выбора местоположения кнопки закрытия окна в заголовке
	 *
	 * @param array $args Options
	 */
	public function settings_field_show_close_button_in( $args ) {
		$field = $args['field'];
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
	 *
	 * @param array $args Options
	 */
	public function settings_field_button_to_close_widget_style( $args ) {
		$field = $args['field'];
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
	 *
	 * @param array $args Options
	 */
	public function settings_field_background_image( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$html = '<input type="text" id="scp_background_image" name="' . $field . '" value="' . $value . '" />';
		$html .= '<input id="scp_upload_background_image" type="button" class="button" value="' . __( 'Upload Image', L10N_SCP_PREFIX ) . '" /><br />';
		$html .= '<div class="scp-background-image">' . ( empty( $value ) ? '' : '<img src="' . $value . '" />' ) . '</div>';
		echo $html;
	}

	/**
	 * Callback-шаблон для выбора событий, при которых показывается окно
	 *
	 * @param array $args Options
	 */
	public function settings_field_when_should_the_popup_appear( $args ) {
		$options = array();
		$options['after_n_seconds']                = __( 'Popup will appear after N second(s)', L10N_SCP_PREFIX );
		$options['after_clicking_on_element']      = __( 'Popup will appear after clicking on the given CSS selector', L10N_SCP_PREFIX );
		$options['after_scrolling_down_n_percent'] = __( 'Popup will appear after a visitor has scrolled on your page at least N percent', L10N_SCP_PREFIX );
		$options['on_exit_intent']                 = __( 'Popup will appear on exit-intent (when mouse has moved out from the page)', L10N_SCP_PREFIX );

		echo self::render_checkboxes_with_hidden_field( $args['field'], $options );
	}

	/**
	 * Callback-шаблон для выбора кому показывать окно плагина
	 *
	 * @param array $args Options
	 */
	public function settings_field_who_should_see_the_popup( $args ) {
		$options = array();
		$options['visitor_opened_at_least_n_number_of_pages'] = __( 'Visitor opened at least N number of page(s)', L10N_SCP_PREFIX );
		$options['visitor_registered_and_role_equals_to']     = __( 'Registered Users Who Should See the Popup', L10N_SCP_PREFIX );

		echo self::render_checkboxes_with_hidden_field( $args['field'], $options );
	}

	/**
	 * Callback-шаблон для выбора каким пользовательским ролям показывать плагин
	 *
	 * @param array $args Options
	 */
	public function settings_field_visitor_registered_and_role_equals_to( $args ) {
		$field = $args['field'];
		$value = get_option( $field );

		$options = array();
		$options['all_registered_users']                = __( 'All Registered Users', L10N_SCP_PREFIX );
		$options['exclude_administrators']              = __( 'All Registered Users Exclude Administrators', L10N_SCP_PREFIX );
		$options['exclude_administrators_and_managers'] = __( 'All Registered Users Exclude Administrators and Managers', L10N_SCP_PREFIX );

		$chains = preg_split( '/,/', $value );

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
	 *
	 * @param array $args Options
	 */
	public function settings_field_facebook_locale( $args ) {
		$field = $args['field'];
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
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public function settings_field_facebook_tabs( $args ) {
		$options = array();
		$options['timeline'] = __( 'Timelime', L10N_SCP_PREFIX );
		$options['messages'] = __( 'Messages', L10N_SCP_PREFIX );
		$options['events']   = __( 'Events', L10N_SCP_PREFIX );

		echo self::render_checkboxes_with_hidden_field( $args['field'], $options );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета ВКонтакте
	 *
	 * @param array $args Options
	 */
	public function settings_field_vkontakte_layout( $args ) {
		$field = $args['field'];
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
	 * Callback-шаблон для формирования радио-кнопок для выбора типа страницы Google+
	 *
	 * @param array $args Options
	 */
	public function settings_field_googleplus_page_type( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'person', checked( $value, 'person', false ), $field . '_0', __( 'Google+ Person', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'page', checked( $value, 'page', false ), $field . '_1', __( 'Google+ Page', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_2', $field, 'community', checked( $value, 'community', false ), $field . '_2', __( 'Google+ Community', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета Google+
	 *
	 * @param array $args Options
	 */
	public function settings_field_googleplus_layout( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'portrait', checked( $value, 'portrait', false ), $field . '_0', __( 'Portrait', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'landscape', checked( $value, 'landscape', false ), $field . '_1', __( 'Landscape', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Google+
	 *
	 * @param array $args Options
	 */
	public function settings_field_googleplus_locale( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'ru', checked( $value, 'ru', false ), $field . '_0', __( 'Russian', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en', checked( $value, 'en', false ), $field . '_1', __( 'English', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Google+
	 *
	 * @param array $args Options
	 */
	public function settings_field_googleplus_theme( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', __( 'Light', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', __( 'Dark', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Twitter
	 *
	 * @param array $args Options
	 *
	 * @since 0.7.6
	 */
	public function settings_field_twitter_locale( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'ru', checked( $value, 'ru', false ), $field . '_0', __( 'Russian', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'en', checked( $value, 'en', false ), $field . '_1', __( 'English', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора положения Follow Button относительно виджета Timeline
	 *
	 * @param array $args Options
	 *
	 * @since 0.7.6
	 */
	public function settings_field_twitter_first_widget( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'follow_button', checked( $value, 'follow_button', false ), $field . '_0', __( 'Follow Button', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'timeline', checked( $value, 'timeline', false ), $field . '_1', __( 'Timeline', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования комбобокса выравнивания кнопки Twitter Follow Button
	 *
	 * @since 0.7.5
	 *
	 * @param array $args Options
	 */
	public function settings_field_twitter_follow_button_align_by( $args ) {
		$field = $args['field'];
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
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Twitter
	 *
	 * @param array $args Options
	 */
	public function settings_field_twitter_theme( $args ) {
		$field = $args['field'];
		$value = get_option( $field );
		$format = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$html = sprintf( $format, $field . '_0', $field, 'light', checked( $value, 'light', false ), $field . '_0', __( 'Light', L10N_SCP_PREFIX ) );
		$html .= '<br />';
		$html .= sprintf( $format, $field . '_1', $field, 'dark', checked( $value, 'dark', false ), $field . '_1', __( 'Dark', L10N_SCP_PREFIX ) );
		echo $html;
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора свойств виджета Twitter
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public function settings_field_twitter_chrome( $args ) {
		$options                 = array();
		$options['noheader']     = __( 'No Header', L10N_SCP_PREFIX );
		$options['nofooter']     = __( 'No Footer', L10N_SCP_PREFIX );
		$options['noborders']    = __( 'No Borders', L10N_SCP_PREFIX );
		$options['noscrollbars'] = __( 'No Scrollbars', L10N_SCP_PREFIX );
		$options['transparent']  = __( 'Transparent (Removes the background color)', L10N_SCP_PREFIX );

		echo self::render_checkboxes_with_hidden_field( $args['field'], $options );
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
	public function render_checkboxes_with_hidden_field( $field, $options ) {
		$value = get_option( $field );

		$chains = preg_split( '/,/', $value );

		$format = '<input type="checkbox" id="%s" class="%s" value="%s"%s />';
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
