<?php defined( 'ABSPATH' ) or exit; ?>
<?php $tab_index = 1; ?>
<?php
	// Google+
	if ( $use_googleplus ) :
?>
<script>
	jQuery(document).ready(function($) {
	<?php
		$prepend_googleplus = sprintf(
			file_get_contents( dirname( __FILE__ ) . '/partials/googleplus_prepend.php' ),
			get_scp_option( 'setting_googleplus_locale' )
		);

		// Удаляем переносы строк, иначе jQuery ниже не отработает
		$prepend_googleplus = str_replace("\n", '', $prepend_googleplus);

		// Переводим код в сущности
		$prepend_googleplus = htmlspecialchars( $prepend_googleplus, ENT_QUOTES );
	?>
		$("body").prepend( $("<div/>").html("<?php echo $prepend_googleplus; ?>").text());
    });
</script>
<?php endif; // use_googleplus ?>

<?php
if ( $cookie_popup_views == $visit_n_pages ) :
	if ( $use_facebook || $use_vkontakte || $use_odnoklassniki || $use_googleplus || $use_twitter ) :
?>
	<div id="social-community-popup">
		<div class="parent_popup"></div>

		<?php $border_radius_css = $border_radius > 0 ? "border-radius:{$border_radius}px !important;" : ""; ?>
		<div id="popup" style="width:<?php echo $container_width + 40; ?>px !important;height:<?php echo $container_height + 10; ?>px !important;<?php echo $border_radius_css; ?>">
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
									scp_tab_caption( 'setting_vkontakte_tab_caption', 'vk-tab' );
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
<?php endif; ?>

<?php
// Окно SCP выводим только после создания его в DOM-дереве
?>
<script>
	<?php if ( $use_facebook ) : ?>
	function scp_prependFacebook($) {
		<?php
			// Заменяем Application ID на наш из настроек
			$prepend_facebook = sprintf(
				file_get_contents( dirname( __FILE__ ) . '/partials/facebook_prepend.php' ),
				get_scp_option( 'setting_facebook_locale' ),
				get_scp_option( 'setting_facebook_application_id' )
			);

			// Удаляем переносы строк, иначе jQuery ниже не отработает
			$prepend_facebook = str_replace("\n", '', $prepend_facebook);

			// Переводим код в сущности
			$prepend_facebook = htmlspecialchars( $prepend_facebook, ENT_QUOTES );
		?>

		if ($("#fb-root").length == 0) {
			$("body").prepend($("<div/>").html("<?php echo $prepend_facebook; ?>").text());
		}
	}
	<?php endif; // use_facebook ?>

	<?php $calculated_delay = ( $delay_after_n_seconds > 0 ? $delay_after_n_seconds * 1000 : 1000 ); ?>

	jQuery(document).ready(function($) {
		scp_setCookie("social-community-popup-views", <?php echo $cookie_popup_views + 1; ?>, { "path": "/" } );

		<?php if ( $cookie_popup_views === $visit_n_pages ) : ?>
			setTimeout(function() {
				<?php if ( $use_facebook ) echo "scp_prependFacebook(\$);"; ?>
				jQuery('#social-community-popup').show();
			}, <?php echo $calculated_delay; ?>);
			scp_deleteCookie("social-community-popup-views");

			<?php if ( $close_by_clicking_anywhere ) : ?>
			$("#social-community-popup .parent_popup, #social-community-popup .close").click(function() {
			<?php else: ?>
			$("#social-community-popup .close").click(function() {
			<?php endif;  ?>
				var date = new Date( new Date().getTime() + <?php echo 1000 * 60 * 60 * 24 * $after_n_days; ?>);
				scp_setCookie("social-community-popup", "true", { "expires": date, "path": "/" } );
				scp_deleteCookie("social-community-popup-views");
				$("#social-community-popup").remove();
			});
		<?php endif; ?>
    });
</script>

<?php
function scp_tab_caption( $option, $css_class = '' ) {
	global $tab_index;
	printf( '<li data-index="' . $tab_index++ . '"' . ( empty( $css_class ) ? '' : " class='{$css_class}'" ) . '><span>%s</span></li>', get_scp_option( $option ) );
}

function scp_facebook_container() {
?>
	<div class="box">
		<?php if ( get_scp_option( 'setting_facebook_show_description' ) === '1' ) : ?>
			<p class="widget-description"><b><?php echo get_scp_option( 'setting_facebook_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			// Заменяем Application ID на наш из настроек
			$facebook_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/facebook_container.php' ),
				get_scp_option( 'setting_facebook_page_url' ),
				get_scp_option( 'setting_facebook_width' ),
				get_scp_option( 'setting_facebook_width' ),
				get_scp_option( 'setting_facebook_height' ),
				get_scp_option( 'setting_facebook_height' ),
				scp_to_bool( get_scp_option( 'setting_facebook_hide_cover' ) ),
				scp_to_bool( get_scp_option( 'setting_facebook_show_facepile' ) ),
				scp_to_bool( get_scp_option( 'setting_facebook_show_posts' ) )
			);
			echo $facebook_container;
		?>
	</div>
<?php
}

function scp_vkontakte_container() {
?>
	<div class="box">
		<?php if ( get_scp_option( 'setting_vkontakte_show_description' ) === '1' ) : ?>
			<p class="widget-description"><b><?php echo get_scp_option( 'setting_vkontakte_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			// Заменяем Application ID на наш из настроек
			$vkontakte_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/vkontakte_container.php' ),
				get_scp_option( 'setting_vkontakte_layout' ),
				get_scp_option( 'setting_vkontakte_width' ),
				get_scp_option( 'setting_vkontakte_height' ),
				get_scp_option( 'setting_vkontakte_color_background' ),
				get_scp_option( 'setting_vkontakte_color_text' ),
				get_scp_option( 'setting_vkontakte_color_button' ),
				get_scp_option( 'setting_vkontakte_page_or_group_id' )
			);
			echo $vkontakte_container;
		?>
	</div>
<?php
}

function scp_odnoklassniki_container() {
?>
	<div class="box">
		<?php if ( get_scp_option( 'setting_odnoklassniki_show_description' ) === '1' ) : ?>
			<p class="widget-description"><b><?php echo get_scp_option( 'setting_odnoklassniki_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			$odnoklassniki_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/odnoklassniki_container.php' ),
				get_scp_option( 'setting_odnoklassniki_group_id' ),
				get_scp_option( 'setting_odnoklassniki_width' ),
				get_scp_option( 'setting_odnoklassniki_height' )
			);
			echo $odnoklassniki_container;
		?>
	</div>
<?php
}

function scp_googleplus_container() {
?>
	<div class="box">
		<?php if ( get_scp_option( 'setting_googleplus_show_description' ) === '1' ) : ?>
			<p class="widget-description"><b><?php echo get_scp_option( 'setting_googleplus_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			$googleplus_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/googleplus_container.php' ),
				get_scp_option( 'setting_googleplus_page_type' ),
				get_scp_option( 'setting_googleplus_size' ),
				get_scp_option( 'setting_googleplus_page_url' ),
				get_scp_option( 'setting_googleplus_theme' ),
				get_scp_option( 'setting_googleplus_show_tagline' ),
				get_scp_option( 'setting_googleplus_show_cover_photo' ),
				google_plus_relation_from_page_type()
			);
			echo $googleplus_container;
		?>
	</div>
<?php
}

function google_plus_relation_from_page_type() {
	switch ( get_scp_option( 'setting_googleplus_page_type' ) ) {
		case 'page':
			return 'publisher';
		case 'person':
			return 'person';
		default:
			return '';
	}
}

function scp_twitter_container() {
	// Для нормального отображения/скрытия полос прокрутки нужно задавать свойство overflow
	$twitter_chrome = get_scp_option( 'setting_twitter_chrome' );
	$twitter_chrome = $twitter_chrome == '' ? array() : array_keys( (array) $twitter_chrome );
	$noscrollbars   = in_array( 'noscrollbars', $twitter_chrome );
	$overflow_css   = $noscrollbars ? 'hidden' : 'auto';

	$widget_height  = get_scp_option( 'setting_twitter_height' );
?>
	<div class="box" style="overflow:<?php echo $overflow_css; ?>;height:<?php echo ( $widget_height - 20 ); ?>px;">
		<?php if ( get_scp_option( 'setting_twitter_show_description' ) === '1' ) : ?>
			<p class="widget-description"><b><?php echo get_scp_option( 'setting_twitter_description' ); ?></b></p>
		<?php endif; ?>

		<?php
			$twitter_container = sprintf( 
				file_get_contents( dirname( __FILE__ ) . '/partials/twitter_container.php' ),
				get_scp_option( 'setting_twitter_username' ),
				get_scp_option( 'setting_twitter_widget_id' ),
				get_scp_option( 'setting_twitter_theme' ),
				get_scp_option( 'setting_twitter_link_color' ),
				join( " ", $twitter_chrome ),
				get_scp_option( 'setting_twitter_tweet_limit' ),
				get_scp_option( 'setting_twitter_show_replies' ),
				get_scp_option( 'setting_twitter_width' ),
				$widget_height,
				get_scp_option( 'setting_twitter_username' )
			);
			echo $twitter_container;
		?>
	</div>
<?php
}

