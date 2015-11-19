<?php
if ( $close_by_clicking_anywhere ) {
	$selector_to_close_widget = '#social-community-popup .parent_popup, #social-community-popup .close';
} else {
	$selector_to_close_widget = '#social-community-popup .close';
}
?>

$('<?php echo $selector_to_close_widget; ?>').click(function() { scp_destroyPlugin($, <?php echo esc_attr( $after_n_days ); ?>); });

