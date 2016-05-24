<?php

class SCP_Twitter_Provider extends SCP_Provider {
	public static function is_active() {
		return ( self::$options[ self::$prefix . 'setting_use_twitter' ] === '1' );
	}

	public static function provide_options_to_tab_caption() {
		return array(
			'value'     => esc_attr( self::$options[ self::$prefix . 'setting_twitter_tab_caption'] ),
			'css_class' => 'twitter-tab',
			'icon'      => 'fa-twitter'
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

		if ( self::$options[ self::$prefix . 'setting_vkontakte_show_description' ] === '1' ) {
			$content .= '<p class="widget-description"><b>' . self::$options[ self::$prefix . 'setting_vkontakte_description' ] . '</b></p>';
		}

		$content .= '<a class="twitter-timeline" '
			. 'href="https://twitter.com/' . esc_attr( self::$options[ self::$prefix . 'setting_twitter_username' ] ) . '" '
			. 'data-widget-id="' .           esc_attr( self::$options[ self::$prefix . 'setting_twitter_widget_id' ] ) . '" '
			. 'data-theme="' .               esc_attr( self::$options[ self::$prefix . 'setting_twitter_theme' ] ) . '" '
			. 'data-link-color="' .          esc_attr( self::$options[ self::$prefix . 'setting_twitter_link_color' ] ) . '" '
			. 'data-chrome="' .              esc_attr( join( " ", $twitter_chrome ) ) . '" '
			. 'data-tweet-limit="' .         esc_attr( self::$options[ self::$prefix . 'setting_twitter_tweet_limit' ] ) . '" '
			. 'data-show-replies="' .        esc_attr( self::$options[ self::$prefix . 'setting_twitter_show_replies' ] ) . '" '
			. 'width="' .                    esc_attr( self::$options[ self::$prefix . 'setting_twitter_width' ] ) . '" '
			. 'height="' .                   esc_attr( $widget_height ) . '"'
			. '>Tweets by @' .               esc_attr( self::$options[ self::$prefix . 'setting_twitter_username' ] ) . '</a>';

		$content .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';

		$content .= '</div>';

		return $content;
	}
}

