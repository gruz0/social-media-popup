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
		$field       = $args['field'];
		$value       = esc_attr( get_option( $field ) );
		$placeholder = empty( $args['placeholder'] ) ? '' : esc_attr( $args['placeholder'] );
		$required    = empty( $args['required'] ) ? '' : ' required';
		$format      = '<input type="text" name="%s" id="%s" value="%s" placeholder="%s"%s />';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sprintf( $format, $field, $field, $value, $placeholder, $required );
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования чекбокса на странице настроек
	 *
	 * @param array $args Options
	 */
	public static function settings_field_checkbox( $args ) {
		$field  = $args['field'];
		$value  = esc_attr( get_option( $field ) );
		$format = '<input type="checkbox" name="%s" id="%s" value="1" %s />';

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sprintf( $format, $field, $field, checked( $value, 1, false ) );
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для формирования WYSIWYG-редактора на странице настроек
	 *
	 * @param array $args Options
	 */
	public static function settings_field_wysiwyg( $args ) {
		$field = $args['field'];
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
		$value = esc_attr( get_option( $field ) );

		$values = ( $value ) ? explode( ',', $value ) : array();

		$scp_prefix = Social_Media_Popup::get_scp_prefix();

		$html = '<ul id="scp-sortable">';
		foreach ( $values as $key ) {
			$setting_value = get_option( $scp_prefix . 'setting_use_' . $key );
			$class         = $setting_value ? '' : ' disabled';

			$html .= '<li class="ui-state-default' . $class . '">' . esc_html( $key ) . '</li>';
		}
		$html .= '</ul>';

		$html .= '<p>' . esc_html( 'Disabled Widgets Marked As Red', 'social-media-popup' ) . '</p>';
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
		$field = $args['field'];
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

				$options .= sprintf( $format, $key, selected( $value, $key, false ), esc_html( $label ) );
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
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'lg' => __( 'Normal Size', 'social-media-popup' ),
			'2x' => '2x',
			'3x' => '3x',
			'4x' => '4x',
			'5x' => '5x',
		);

		self::render_select_with_options( $items, $value, $field, 'scp_icon_size' );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора местоположения кнопки закрытия окна в заголовке
	 *
	 * @param array $args Options
	 */
	public static function settings_field_show_close_button_in( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'inside'  => __( 'Inside Container', 'social-media-popup' ),
			'outside' => __( 'Outside Container', 'social-media-popup' ),
			'none'    => __( "Don't show", 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования стиля кнопки "Спасибо, я уже с вами"
	 *
	 * @param array $args Options
	 */
	public static function settings_field_button_to_close_widget_style( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'link'  => __( 'Link', 'social-media-popup' ),
			'green' => __( 'Green Button', 'social-media-popup' ),
			'blue'  => __( 'Blue Button', 'social-media-popup' ),
			'red'   => __( 'Red Button', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования поля и кнопки для загрузки фонового изображения виджета
	 *
	 * @param array $args Options
	 */
	public static function settings_field_background_image( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$html  = '<input type="text" id="scp_background_image" name="' . $field . '" value="' . $value . '" />';
		$html .= '<input id="scp_upload_background_image" type="button" class="button" value="' . esc_attr( 'Upload Image', 'social-media-popup' ) . '" /><br />';

		if ( ! empty( $value ) ) {
			$html .= '<div class="scp-background-image"><img src="' . $value . '" /></div>';
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Callback-шаблон для выбора событий, при которых показывается окно
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public static function settings_field_when_should_the_popup_appear( $args ) {
		$options = array(
			'after_n_seconds'                => __( 'Popup will appear after N second(s)', 'social-media-popup' ),
			'after_clicking_on_element'      => __( 'Popup will appear after clicking on the given CSS selector', 'social-media-popup' ),
			'after_scrolling_down_n_percent' => __( 'Popup will appear after a visitor has scrolled on your page at least N percent', 'social-media-popup' ),
			'on_exit_intent'                 => __( 'Popup will appear on exit-intent (when mouse has moved out from the page)', 'social-media-popup' ),
		);

		self::render_checkboxes_with_hidden_field( $args['field'], $options );
	}

	/**
	 * Callback-шаблон для выбора кому показывать окно плагина
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public static function settings_field_who_should_see_the_popup( $args ) {
		$options = array(
			'visitor_opened_at_least_n_number_of_pages' => __( 'Visitor opened at least N number of page(s)', 'social-media-popup' ),
			'visitor_registered_and_role_equals_to'     => __( 'Registered Users Who Should See the Popup', 'social-media-popup' ),
		);

		self::render_checkboxes_with_hidden_field( $args['field'], $options );
	}

	/**
	 * Callback-шаблон для выбора каким пользовательским ролям показывать плагин
	 *
	 * @param array $args Options
	 */
	public static function settings_field_visitor_registered_and_role_equals_to( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'all_registered_users'                => __( 'All Registered Users', 'social-media-popup' ),
			'exclude_administrators'              => __( 'All Registered Users Exclude Administrators', 'social-media-popup' ),
			'exclude_administrators_and_managers' => __( 'All Registered Users Exclude Administrators and Managers', 'social-media-popup' ),
		);

		self::render_select_with_options( $items, $value, $field, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Facebook
	 *
	 * @param array $args Options
	 */
	public static function settings_field_facebook_locale( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'ru_RU' => __( 'Russian', 'social-media-popup' ),
			'en_US' => __( 'English', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования табов с выбором типа загружаемого контента для Facebook
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public static function settings_field_facebook_tabs( $args ) {
		$options = array(
			'timeline' => __( 'Timelime', 'social-media-popup' ),
			'messages' => __( 'Messages', 'social-media-popup' ),
			'events'   => __( 'Events', 'social-media-popup' ),
		);

		self::render_checkboxes_with_hidden_field( $args['field'], $options );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета ВКонтакте
	 *
	 * @param array $args Options
	 */
	public static function settings_field_vkontakte_layout( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'0' => __( 'Members', 'social-media-popup' ),
			'1' => __( 'Name', 'social-media-popup' ),
			'2' => __( 'News', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа страницы Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_page_type( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'person'    => __( 'Google+ Person', 'social-media-popup' ),
			'page'      => __( 'Google+ Page', 'social-media-popup' ),
			'community' => __( 'Google+ Community', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора типа макета Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_layout( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'portrait'  => __( 'Portrait', 'social-media-popup' ),
			'landscape' => __( 'Landscape', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_locale( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'ru' => __( 'Russian', 'social-media-popup' ),
			'en' => __( 'English', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Google+
	 *
	 * @param array $args Options
	 */
	public static function settings_field_googleplus_theme( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'light' => __( 'Light', 'social-media-popup' ),
			'dark'  => __( 'Dark', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора локали Twitter
	 *
	 * @param array $args Options
	 *
	 * @since 0.7.6
	 */
	public static function settings_field_twitter_locale( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'ru' => __( 'Russian', 'social-media-popup' ),
			'en' => __( 'English', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора положения Follow Button относительно виджета Timeline
	 *
	 * @param array $args Options
	 *
	 * @since 0.7.6
	 */
	public static function settings_field_twitter_first_widget( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'follow_button' => __( 'Follow Button', 'social-media-popup' ),
			'timeline'      => __( 'Timeline', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования комбобокса выравнивания кнопки Twitter Follow Button
	 *
	 * @since 0.7.5
	 *
	 * @param array $args Options
	 */
	public static function settings_field_twitter_follow_button_align_by( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'left'   => __( 'Left', 'social-media-popup' ),
			'center' => __( 'Center', 'social-media-popup' ),
			'right'  => __( 'Right', 'social-media-popup' ),
		);

		self::render_select_with_options( $items, $value, $field, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора темы Twitter
	 *
	 * @param array $args Options
	 */
	public static function settings_field_twitter_theme( $args ) {
		$field = $args['field'];
		$value = esc_attr( get_option( $field ) );

		$items = array(
			'light' => __( 'Light', 'social-media-popup' ),
			'dark'  => __( 'Dark', 'social-media-popup' ),
		);

		self::render_radio_buttons( $items, $value, $field );
	}

	/**
	 * Callback-шаблон для формирования радио-кнопок для выбора свойств виджета Twitter
	 *
	 * @param array $args Options
	 *
	 * @uses SCP_Settings_Field::render_checkboxes_with_hidden_field()
	 */
	public static function settings_field_twitter_chrome( $args ) {
		$options = array(
			'noheader'     => __( 'No Header', 'social-media-popup' ),
			'nofooter'     => __( 'No Footer', 'social-media-popup' ),
			'noborders'    => __( 'No Borders', 'social-media-popup' ),
			'noscrollbars' => __( 'No Scrollbars', 'social-media-popup' ),
			'transparent'  => __( 'Transparent (Removes the background color)', 'social-media-popup' ),
		);

		self::render_checkboxes_with_hidden_field( $args['field'], $options );
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
	private static function render_checkboxes_with_hidden_field( $field, $options ) {
		$value = esc_attr( get_option( $field ) );

		$chains = preg_split( '/,/', $value );

		$checkbox_format  = '<input type="checkbox" id="%s" class="%s" value="%s"%s />';
		$checkbox_format .= '<label for="%s">%s</label>';
		$checkbox_format .= '<br />';

		$hidden_field_format = '<input type="hidden" id="%s" name="%s" value="%s" />';

		$html = '';
		foreach ( $options as $key => $label ) {
			$checked = '';
			for ( $idx = 0, $size = count( $chains ); $idx < $size; $idx++ ) {
				$checked = checked( $chains[ $idx ], $key, false );
				if ( strlen( $checked ) ) break;
			}

			$html .= sprintf( $checkbox_format, $key, $field, $key, $checked, $key, esc_html( $label ) );
		}

		$html .= sprintf( $hidden_field_format, $field, $field, $value );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Helper to render radio inputs
	 *
	 * @param array  $items Array of items
	 * @param string $value Current value
	 * @param string $name  Name attribute
	 */
	private static function render_radio_buttons( $items, $value, $name ) {
		$format  = '<input type="radio" id="%s" name="%s" value="%s"%s />';
		$format .= '<label for="%s">%s</label>';
		$format .= '<br />';

		$html = '';
		foreach ( $items as $key => $label ) {
			$input_id = "${name}_${key}";

			$html .= sprintf(
				$format,
				$input_id,                      // Radio ID
				$name,                          // Radio name
				$key,                           // Radio value
				checked( $value, $key, false ), // Radio "checked" attribute
				$input_id,                      // Label for Radio ID
				esc_html( $label )              // Label text
			);
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $html;
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Helper to render <select> with options
	 *
	 * @param array  $items Array of items
	 * @param string $value Current value
	 * @param string $name  Name attribute
	 * @param string $id    ID attribute
	 * @param string $class Class attribute
	 */
	private static function render_select_with_options( $items, $value, $name, $id, $class = '' ) {
		$select_format = '<select name="%s" id="%s" class="%s">%s</select>';
		$option_format = '<option value="%s"%s>%s</option>';

		$html = '';
		foreach ( $items as $key => $label ) {
			$html .= sprintf( $option_format, $key, selected( $value, $key, false ), esc_html( $label ) );
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sprintf(
			$select_format,
			$name,  // Select name
			$id,    // Select ID
			$class, // Select class
			$html
		);
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
