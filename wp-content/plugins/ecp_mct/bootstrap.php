<?php
/**
 * Bootstrap file for getting the ABSPATH constant to wp-load.php
 * This is requried when a plugin requires access not via the admin screen.
 *
 * If the wp-load.php file is not found, then an error will be displayed
 *
 * @package WordPress
 * @since Version 2.6
 */

$path  = ''; // It should be end with a trailing slash    

if (!defined('WP_LOAD_PATH')) {
	$classic_root = dirname(dirname(dirname(dirname(__FILE__)))).'/';
	if (file_exists($classic_root.'wp-load.php') ) {
		define('WP_LOAD_PATH', $classic_root);
	} else {
		if (file_exists($path.'wp-load.php')) {
			define('WP_LOAD_PATH', $path);
		} else {
			exit(__("Could not find wp-load.php", "wp_sh"));
		}
	}
}

//Load wp-load.php
require_once(WP_LOAD_PATH.'wp-load.php');
?>