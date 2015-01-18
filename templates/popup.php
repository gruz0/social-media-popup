<?php defined( 'ABSPATH' ) or exit; ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {

		scp_setCookie('social-community-popup-views', <?php echo $cookie_popup_views + 1; ?>, { 'path': '/' } );

	<?php if ( $cookie_popup_views === $visit_n_pages ) : ?>
		<?php if ( $delay_after_n_seconds > 0 ) : ?>
			setTimeout("jQuery('#social-community-popup').show();", <?php echo $delay_after_n_seconds * 1000; ?>);
		<?php else: ?>
			$('#social-community-popup').show();
		<?php endif; ?>
		scp_deleteCookie('social-community-popup-views');

		$('#social-community-popup .close').click(function() {
			var date = new Date( new Date().getTime() + <?php echo 1000 * 60 * 60 * 24 * $after_n_days; ?>);
			scp_setCookie('social-community-popup', 'true', { 'expires': date, 'path': '/' } );
			scp_deleteCookie('social-community-popup-views');
			$('#social-community-popup').remove();
		});

		<?php // Facebook
			if ( $use_facebook ) :

				// Заменяем Application ID на наш из настроек
				$prepend_facebook = sprintf( 
					file_get_contents( dirname( __FILE__ ) . '/partials/facebook_prepend.php' ),
					get_option( SCP_PREFIX . 'setting_facebook_locale' ),
					get_option( SCP_PREFIX . 'setting_facebook_application_id' )
				);

				// Удаляем переносы строк, иначе jQuery ниже не отработает
				$prepend_facebook = str_replace("\n", '', $prepend_facebook);

				// Переводим код в сущности
				$prepend_facebook = htmlspecialchars( $prepend_facebook, ENT_QUOTES );
			?>
				if ($('#fb-root').length == 0) {
					$('body').prepend( $("<div/>").html('<?php echo $prepend_facebook; ?>').text());
				}
			<?php
			endif; // use_facebook

			// Google+
			if ( $use_googleplus ) :

				$prepend_googleplus = sprintf(
					file_get_contents( dirname( __FILE__ ) . '/partials/googleplus_prepend.php' ),
					get_option( SCP_PREFIX . 'setting_googleplus_locale' )
				);

				// Удаляем переносы строк, иначе jQuery ниже не отработает
				$prepend_googleplus = str_replace("\n", '', $prepend_googleplus);

				// Переводим код в сущности
				$prepend_googleplus = htmlspecialchars( $prepend_googleplus, ENT_QUOTES );
			?>
				$('body').prepend( $("<div/>").html('<?php echo $prepend_googleplus; ?>').text());
			<?php
			endif; // use_googleplus

		endif;
		?>
    });
</script>

<?php 
	if ( $cookie_popup_views < $visit_n_pages ) {
		return;
	}

	if ( $use_facebook || $use_vkontakte || $use_odnoklassniki || $use_googleplus || $use_twitter ) :
?>
<div id="social-community-popup">
	<div class="parent_popup"></div>

	<div id="popup" style="width:<?php echo $container_width + 60; ?>px !important;height:<?php echo $container_height + 10; ?>px !important;">
		<div class="section" style="width:<?php echo $container_width; ?>px !important;height:<?php echo $container_height; ?>px !important;">
			<span class="close"><?php _e( 'Close', L10N_SCP_PREFIX ); ?></span>
			<ul class="tabs">

			<?php
				for ( $idx = 0; $idx < count( $tabs_order ); $idx++ ) {
					switch ( $tabs_order[ $idx ] ) {
						case 'facebook':
							if ( $use_facebook )
								scp_tab_caption( 'setting_facebook_tab_caption' );
							break;

						case 'vkontakte':
							if ( $use_vkontakte )
								scp_tab_caption( 'setting_vkontakte_tab_caption' );
							break;

						case 'odnoklassniki':
							if ( $use_odnoklassniki )
								scp_tab_caption( 'setting_odnoklassniki_tab_caption' );
							break;

						case 'googleplus':
							if ( $use_googleplus )
								scp_tab_caption( 'setting_googleplus_tab_caption' );
							break;

						case 'twitter':
							if ( $use_twitter )
								scp_tab_caption( 'setting_twitter_tab_caption' );
							break;
					}
				}
			?>
			</ul>

			<?php
				for ( $idx = 0; $idx < count( $tabs_order ); $idx++ ) {
					switch ( $tabs_order[ $idx ] ) {
						case 'facebook':
							if ( $use_facebook )
								scp_facebook_container();
							break;

						case 'vkontakte':
							if ( $use_vkontakte )
								scp_vkontakte_container();
							break;

						case 'odnoklassniki':
							if ( $use_odnoklassniki )
								scp_odnoklassniki_container();
							break;

						case 'googleplus':
							if ( $use_googleplus )
								scp_googleplus_container();
							break;

						case 'twitter':
							if ( $use_twitter )
								scp_twitter_container();
							break;
					}
				}
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php

function scp_tab_caption( $option ) {
	printf( '<li><span>%s</span></li>', get_option( SCP_PREFIX . $option ) );
}

function scp_facebook_container() {
?>
	<div class="box">
		<?php if ( get_option( SCP_PREFIX . 'setting_facebook_show_description' ) === '1' ) : ?>
			<p><b><?php echo get_option( SCP_PREFIX . 'setting_facebook_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			// Заменяем Application ID на наш из настроек
			$facebook_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/facebook_container.php' ),
				get_option( SCP_PREFIX . 'setting_facebook_page_url' ),
				get_option( SCP_PREFIX . 'setting_facebook_width' ),
				get_option( SCP_PREFIX . 'setting_facebook_height' ),
				scp_to_bool( get_option( SCP_PREFIX . 'setting_facebook_show_header' ) ),
				scp_to_bool( get_option( SCP_PREFIX . 'setting_facebook_show_border' ) ),
				scp_to_bool( get_option( SCP_PREFIX . 'setting_facebook_show_faces' ) ),
				scp_to_bool( get_option( SCP_PREFIX . 'setting_facebook_show_stream' ) )
			);
			echo $facebook_container;
		?>
	</div>
<?php
}

function scp_vkontakte_container() {
?>
	<div class="box">
		<?php if ( get_option( SCP_PREFIX . 'setting_vkontakte_show_description' ) === '1' ) : ?>
			<p><b><?php echo get_option( SCP_PREFIX . 'setting_vkontakte_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			// Заменяем Application ID на наш из настроек
			$vkontakte_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/vkontakte_container.php' ),
				get_option( SCP_PREFIX . 'setting_vkontakte_layout' ),
				get_option( SCP_PREFIX . 'setting_vkontakte_width' ),
				get_option( SCP_PREFIX . 'setting_vkontakte_height' ),
				get_option( SCP_PREFIX . 'setting_vkontakte_color_background' ),
				get_option( SCP_PREFIX . 'setting_vkontakte_color_text' ),
				get_option( SCP_PREFIX . 'setting_vkontakte_color_button' ),
				get_option( SCP_PREFIX . 'setting_vkontakte_page_or_group_id' )
			);
			echo $vkontakte_container;
		?>
	</div>
<?php
}

function scp_odnoklassniki_container() {
?>
	<div class="box">
		<?php if ( get_option( SCP_PREFIX . 'setting_odnoklassniki_show_description' ) === '1' ) : ?>
			<p><b><?php echo get_option( SCP_PREFIX . 'setting_odnoklassniki_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			$odnoklassniki_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/odnoklassniki_container.php' ),
				get_option( SCP_PREFIX . 'setting_odnoklassniki_group_id' ),
				get_option( SCP_PREFIX . 'setting_odnoklassniki_width' ),
				get_option( SCP_PREFIX . 'setting_odnoklassniki_height' )
			);
			echo $odnoklassniki_container;
		?>
	</div>
<?php
}

function scp_googleplus_container() {
?>
	<div class="box">
		<?php if ( get_option( SCP_PREFIX . 'setting_googleplus_show_description' ) === '1' ) : ?>
			<p><b><?php echo get_option( SCP_PREFIX . 'setting_googleplus_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			$googleplus_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/googleplus_container.php' ),
				get_option( SCP_PREFIX . 'setting_googleplus_size' ),
				get_option( SCP_PREFIX . 'setting_googleplus_page_url' ),
				get_option( SCP_PREFIX . 'setting_googleplus_theme' ),
				get_option( SCP_PREFIX . 'setting_googleplus_show_tagline' ),
				get_option( SCP_PREFIX . 'setting_googleplus_show_cover_photo' )
			);
			echo $googleplus_container;
		?>
	</div>
<?php
}

function scp_twitter_container() {
?>
	<div class="box">
		<?php if ( get_option( SCP_PREFIX . 'setting_twitter_show_description' ) === '1' ) : ?>
			<p><b><?php echo get_option( SCP_PREFIX . 'setting_twitter_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			$twitter_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/twitter_container.php' ),
				get_option( SCP_PREFIX . 'setting_twitter_username' ),
				get_option( SCP_PREFIX . 'setting_twitter_widget_id' ),
				get_option( SCP_PREFIX . 'setting_twitter_theme' ),
				get_option( SCP_PREFIX . 'setting_twitter_link_color' ),
				join( " ", array_keys( (array)get_option( SCP_PREFIX . 'setting_twitter_chrome' ) ) ),
				get_option( SCP_PREFIX . 'setting_twitter_tweet_limit' ),
				get_option( SCP_PREFIX . 'setting_twitter_show_replies' ),
				get_option( SCP_PREFIX . 'setting_twitter_width' ),
				get_option( SCP_PREFIX . 'setting_twitter_height' ),
				get_option( SCP_PREFIX . 'setting_twitter_username' )
			);
			echo $twitter_container;
		?>
	</div>
<?php
}

