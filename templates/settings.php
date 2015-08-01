<?php defined( 'ABSPATH' ) or exit; ?>
<div class="wrap social-community-popup-settings">
	<h2><?php _e( 'Social Community Popup Options', L10N_SCP_PREFIX ); ?></h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field( 'scp-update-options' ); ?>
		<?php settings_fields( 'social_community_popup-group' ); ?>
		<?php do_settings_fields( 'social_community_popup-group', 'social_community_popup-group' ); ?>
		<?php do_settings_sections( 'social_community_popup' ); ?>
		<?php submit_button(); ?>
	</form>
	<?php require( sprintf( "%s/copyright.php", dirname( __FILE__ ) ) ); ?>
</div>

<script>
	jQuery(document).ready(function($) {
		// Сортировка табов соц. сетей
		$( "#scp-sortable" ).sortable({
			revert: true,
			update: function( event, ui ) {
				var networks = [];
				$('#scp-sortable li').each(function() {
					networks.push($(this).text());
				});
				$('#social-community-popup-setting_tabs_order').val(networks.join(','));
			}
		});
		$( "ul, li" ).disableSelection();
	});
</script>
