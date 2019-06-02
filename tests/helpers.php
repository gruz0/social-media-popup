<?php
// :nodoc

/**
 * Returns WordPress core directory
 *
 * @return string
 */
function wp_core_directory() {
	return smp_is_dockerized() ? '/var/www/html' : getenv( 'TRAVIS_CI_WP_DIRECTORY' );
}

/**
 * Returns path to wp-load.php
 *
 * @return string
 */
function wp_load_php_path() {
	return wp_core_directory() . '/wp-load.php';
}
