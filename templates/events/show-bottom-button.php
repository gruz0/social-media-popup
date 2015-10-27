<?php if ( $delay_before_show_bottom_button > 0 ) { ?>
	setTimeout(function() { jQuery('.dont-show-widget').show(); }, <?php echo esc_attr( $delay_before_show_bottom_button ) * 1000; ?>);
<?php } else { ?>
	jQuery('.dont-show-widget').show();
<?php } ?>

