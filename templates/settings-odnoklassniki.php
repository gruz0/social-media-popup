<?php defined( 'ABSPATH' ) or exit; ?>
<div class="wrap social-community-popup-settings">
    <h2><?php _e( 'Odnoklassniki Options', L10N_SCP_PREFIX ); ?></h2>
    <form method="post" action="options.php">
        <?php @settings_fields( 'social_community_popup-group-odnoklassniki' ); ?>
        <?php @do_settings_fields( 'social_community_popup-group-odnoklassniki' ); ?>
        <?php do_settings_sections( 'social_community_popup_odnoklassniki_options' ); ?>
        <?php @submit_button(); ?>
    </form>
	<?php require( sprintf( "%s/copyright.php", dirname( __FILE__ ) ) ); ?>
</div>
