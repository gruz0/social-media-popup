<?php
/**
 * PHPUnit tests
 *
 * @package  Social_Media_Popup
 * @author   Alexander Kadyrov
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/gruz0/social-media-popup
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

define( 'PLUGIN_NAME', 'social-media-popup.php' );
define( 'PLUGIN_FOLDER', basename( dirname( __DIR__ ) ) );
define( 'PLUGIN_PATH', PLUGIN_FOLDER . '/' . PLUGIN_NAME );

// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array( PLUGIN_PATH ),
);

require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load plugin
 */
function _manually_load_plugin() {
	require dirname( __DIR__ ) . '/'.PLUGIN_NAME;
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

