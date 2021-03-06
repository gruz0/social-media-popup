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

<div class="wrap social-media-popup-settings">
	<h2><?php esc_html_e( 'Debug', 'social-media-popup' ); ?></h2>

	<?php
	echo $content; // WPCS: XSS OK.
	?>

	<?php require( sprintf( '%s/../copyright.php', dirname( __FILE__ ) ) ); ?>
</div>
