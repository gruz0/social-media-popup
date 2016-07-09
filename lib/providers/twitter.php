<?php

class SCP_Twitter_Provider extends SCP_Provider {
	public static function is_active() {
		return ( self::$options[ self::$prefix . 'setting_use_twitter' ] === '1' );
	}

	public static function options() {
		return array(
			'tab_caption' => esc_attr( self::$options[ self::$prefix . 'setting_twitter_tab_caption'] ),
			'css_class'   => 'twitter-tab',
			'icon'        => 'fa-twitter',
			'url'         => '//twitter.com/' . self::$options[ self::$prefix . 'setting_twitter_username' ]
		);
	}

	public static function container() {
		// Для нормального отображения/скрытия полос прокрутки нужно задавать свойство overflow
		$twitter_chrome = self::$options[ self::$prefix . 'setting_twitter_chrome' ];
		$twitter_chrome = $twitter_chrome == '' ? array() : array_keys( (array) $twitter_chrome );
		$noscrollbars   = in_array( 'noscrollbars', $twitter_chrome );
		$overflow_css   = $noscrollbars ? 'hidden' : 'auto';

		$widget_height  = self::$options[ self::$prefix . 'setting_twitter_height' ];

		$content = '<div class="box" style="overflow:' . esc_attr( $overflow_css ) . ';height:' . esc_attr( ( $widget_height - 20 ) ) . 'px;">';

		if ( self::$options[ self::$prefix . 'setting_twitter_show_description' ] === '1' ) {
			$content .= '<p class="widget-description"><b>' . self::$options[ self::$prefix . 'setting_twitter_description' ] . '</b></p>';
		}

		// Показывать Twitter Follow Button или нет
		if ( self::$options[ self::$prefix . 'setting_twitter_use_follow_button' ] === '1' ) {
			$content .= self::render_follow_button();
		}

		// Показывать Twitter Timeline или нет
		if ( self::$options[ self::$prefix . 'setting_twitter_use_timeline' ] === '1' ) {
			$content .= self::render_timeline( $twitter_chrome, $widget_height );
		}

		$content .= self::render_javascript();
		$content .= '</div>';

		return $content;
	}

	private static function render_javascript() {
		return '<script type="text/javascript">
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

			var scp_Twitter_closeWindowAfterJoiningGroup = ' . ( (int) self::$options[ self::$prefix . 'setting_twitter_close_window_after_join' ] ) . ';

			function scp_followIntentToAnalytics(intentEvent) {
				if (!intentEvent) return;

				if ( scp_Twitter_closeWindowAfterJoiningGroup ) {
					scp_destroyPlugin(scp.showWindowAfterReturningNDays);
				}
			}

			twttr.ready(function(twttr) {
				twttr.events.bind("follow", scp_followIntentToAnalytics);
			});
		</script>';
	}

	private static function render_follow_button() {
		return '<div style="text-align:' . esc_attr( self::$options[ self::$prefix . 'setting_twitter_follow_button_align_by' ] ) . '">'
			. '<a class="twitter-follow-button" '
			. 'href="//twitter.com/'    . esc_attr( self::$options[ self::$prefix . 'setting_twitter_username' ] ) . '" '
			. 'data-show-count="'       . scp_to_bool( self::$options[ self::$prefix . 'setting_twitter_show_count' ] ) . '" '
			. 'data-show-screen-name="' . scp_to_bool( self::$options[ self::$prefix . 'setting_twitter_show_screen_name' ] ) . '" '
			. 'data-size="'             . ( self::$options[ self::$prefix . 'setting_twitter_follow_button_large_size' ] === '1' ? 'large' : '' ) . '" '
			. '>' . __( 'Follow', L10N_SCP_PREFIX ) . ' @' . esc_attr( self::$options[ self::$prefix . 'setting_twitter_username' ] ) . '</a></div>';
	}

	private static function render_timeline( $twitter_chrome, $widget_height ) {
		return '<a class="twitter-timeline" '
			. 'href="//twitter.com/' . esc_attr( self::$options[ self::$prefix . 'setting_twitter_username' ] ) . '" '
			. 'data-widget-id="' .     esc_attr( self::$options[ self::$prefix . 'setting_twitter_widget_id' ] ) . '" '
			. 'data-theme="' .         esc_attr( self::$options[ self::$prefix . 'setting_twitter_theme' ] ) . '" '
			. 'data-link-color="' .    esc_attr( self::$options[ self::$prefix . 'setting_twitter_link_color' ] ) . '" '
			. 'data-chrome="' .        esc_attr( join( " ", $twitter_chrome ) ) . '" '
			. 'data-tweet-limit="' .   esc_attr( self::$options[ self::$prefix . 'setting_twitter_tweet_limit' ] ) . '" '
			. 'data-show-replies="' .  esc_attr( self::$options[ self::$prefix . 'setting_twitter_show_replies' ] ) . '" '
			. 'width="' .              esc_attr( self::$options[ self::$prefix . 'setting_twitter_width' ] ) . '" '
			. 'height="' .             esc_attr( $widget_height ) . '"'
			. '>' . __( 'Tweets', L10N_SCP_PREFIX ) . ' @' . esc_attr( self::$options[ self::$prefix . 'setting_twitter_username' ] ) . '</a>';
	}
}

