<?php
/**
 * Social Media Popup
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

defined( 'ABSPATH' ) or exit;
?>

<div class="wrap social-community-popup-settings">
	<h2><?php _e( 'Debug', L10N_SCP_PREFIX ); ?></h2>

	<?php echo $content; ?>

	<?php require( sprintf( '%s/../copyright.php', dirname( __FILE__ ) ) ); ?>
</div>