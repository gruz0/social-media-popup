<?php if ( $close_when_esc_pressed ) { ?>
$(document).keydown(function(e) {
	if ( e.keyCode == 27 ) {
		scp_destroyPlugin($, <?php echo esc_attr( $after_n_days ); ?>);
	}
});
<?php } ?>

