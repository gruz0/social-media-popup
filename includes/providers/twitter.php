<?php
/**
 * Twitter Template
 *
 * @package    Social_Media_Popup
 * @subpackage SCP_Template
 * @author     Alexander Gruzov
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       https://github.com/gruz0/social-media-popup
 */

/**
 * SCP_Twitter_Provider
 */
class SCP_Twitter_Provider extends SCP_Provider {
	/**
	 * Return widget is active
	 *
	 * @since 0.7.5
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return self::get_option_as_boolean( 'setting_use_twitter' );
	}

	/**
	 * Return options as array
	 *
	 * @since 0.7.5
	 *
	 * @return array
	 */
	public static function options() {
		return array(
			'default_tab_caption' => __( 'Twitter', L10N_SCP_PREFIX ),
			'tab_caption'         => self::get_option_as_escaped_string( 'setting_twitter_tab_caption' ),
			'css_class'           => 'twitter-tab',
			'icon'                => 'fa-twitter',
			'url'                 => '//twitter.com/' . self::get_option_as_escaped_string( 'setting_twitter_username' ),
		);
	}

	/**
	 * Render widget container
	 *
	 * @uses self::render_follow_button()
	 * @uses self::render_timeline()
	 * @uses self::render_javascript()
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	public static function container() {
		// Для нормального отображения/скрытия полос прокрутки нужно задавать свойство overflow
		$twitter_chrome = self::$options[ self::$prefix . 'setting_twitter_chrome' ];
		$twitter_chrome = '' === $twitter_chrome ? array() : array_keys( (array) $twitter_chrome );
		$noscrollbars   = in_array( 'noscrollbars', $twitter_chrome, true );
		$overflow_css   = $noscrollbars ? 'hidden' : 'auto';

		$widget_height  = self::get_option_as_integer( 'setting_twitter_height' );

		$content = '<div class="box" style="overflow:' . esc_attr( $overflow_css ) . ';height:' . esc_attr( ( $widget_height - 20 ) ) . 'px;">';

		if ( self::get_option_as_boolean( 'setting_twitter_show_description' ) ) {
			$content .= '<p class="widget-description"><b>' . self::$options[ self::$prefix . 'setting_twitter_description' ] . '</b></p>';
		}

		// Показывать Twitter Follow Button или нет
		if ( self::get_option_as_boolean( 'setting_twitter_use_follow_button' ) ) {
			$content .= self::render_follow_button();
		}

		// Показывать Twitter Timeline или нет
		if ( self::get_option_as_boolean( 'setting_twitter_use_timeline' ) ) {
			$content .= self::render_timeline( $twitter_chrome, $widget_height );
		}

		$content .= self::render_javascript();
		$content .= '</div>';

		return $content;
	}

	/**
	 * Return JavaScript
	 *
	 * @uses SCP_Template()->use_events_tracking()
	 * @uses SCP_Template()->push_social_media_trigger_to_google_analytics()
	 * @uses SCP_Template()->push_social_network_and_action_to_google_analytics()
	 * @used_by self::container()
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	private static function render_javascript() {
		$content = '<script type="text/javascript">
			window.twttr = (function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {};
				if (d.getElementById(id)) return;
				js = d.createElement(s);
				js.id = id;
				js.src = "//platform.twitter.com/widgets.js";
				fjs.parentNode.insertBefore(js, fjs);

				t._e = [];
				t.ready = function(f) {
					t._e.push(f);
			};

			return t;
			}(document, "script", "twitter-wjs"));

			var scp_Twitter_closeWindowAfterJoiningGroup = ' . self::get_option_as_integer( 'setting_twitter_close_window_after_join' ) . ';

			function scp_followIntentToAnalytics(intentEvent) {
				if (!intentEvent) return;';

				if ( (int) self::$options[ self::$prefix . 'setting_twitter_close_window_after_join' ] ) {
					$content .= 'scp_destroyPlugin(scp.showWindowAfterReturningNDays);';
				}

				if ( self::$template->use_events_tracking() && self::get_option_as_boolean( 'tracking_use_twitter' ) ) {
					$content .= self::$template->push_social_media_trigger_to_google_analytics( self::get_option_as_escaped_string( 'tracking_twitter_event' ) );
					$content .= self::$template->push_social_network_and_action_to_google_analytics( 'SMP Twitter', 'Follow' );
				}

			$content .= '}

			twttr.ready(function(twttr) {
				twttr.events.bind("follow", scp_followIntentToAnalytics);
			});
		</script>';

		return $content;
	}

	/**
	 * Return Follow Button container
	 *
	 * @uses scp_to_bool()
	 * @used_by self::container()
	 *
	 * @since 0.7.5
	 *
	 * @return string
	 */
	private static function render_follow_button() {
		return '<div style="text-align:' . self::get_option_as_escaped_string( 'setting_twitter_follow_button_align_by' ) . '">'
			. '<a class="twitter-follow-button" '
			. 'href="//twitter.com/'    . self::get_option_as_escaped_string( 'setting_twitter_username' ) . '" '
			. 'data-show-count="'       . scp_to_bool( self::get_option_as_escaped_string( 'setting_twitter_show_count' ) ) . '" '
			. 'data-show-screen-name="' . scp_to_bool( self::get_option_as_escaped_string( 'setting_twitter_show_screen_name' ) ) . '" '
			. 'data-size="'             . ( self::get_option_as_boolean( 'setting_twitter_follow_button_large_size' ) ? 'large' : '' ) . '" '
			. '>' . __( 'Follow', L10N_SCP_PREFIX ) . ' @' . self::get_option_as_escaped_string( 'setting_twitter_username' ) . '</a></div>';
	}

	/**
	 * Return Timeline container
	 *
	 * @used_by self::container()
	 *
	 * @since 0.7.5
	 *
	 * @param array   $twitter_chrome An array with chrome properties
	 * @param integer $widget_height Widget height
	 * @return string
	 */
	private static function render_timeline( $twitter_chrome, $widget_height ) {
		return '<a class="twitter-timeline" '
			. 'href="//twitter.com/' . self::get_option_as_escaped_string( 'setting_twitter_username' ) . '" '
			. 'data-screen-name="'   . self::get_option_as_escaped_string( 'setting_twitter_username' ) . '" '
			. 'data-theme="'         . self::get_option_as_escaped_string( 'setting_twitter_theme' ) . '" '
			. 'data-link-color="'    . self::get_option_as_escaped_string( 'setting_twitter_link_color' ) . '" '
			. 'data-chrome="'        . esc_attr( join( ' ', $twitter_chrome ) ) . '" '
			. 'data-tweet-limit="'   . self::get_option_as_integer( 'setting_twitter_tweet_limit' ) . '" '
			. 'data-show-replies="'  . self::get_option_as_escaped_string( 'setting_twitter_show_replies' ) . '" '
			. 'width="'              . self::get_option_as_integer( 'setting_twitter_width' ) . '" '
			. 'height="'             . $widget_height . '"'
			. ' rel="nofollow" target="_blank">' . __( 'Tweets', L10N_SCP_PREFIX ) . ' @' . self::get_option_as_escaped_string( 'setting_twitter_username' ) . '</a>';
	}
}

