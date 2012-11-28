<?php
/*
Plugin Name: ECP Multiple Choice Tests
Description: Provides multiple choice tests functionality to ECP Site
Version: 1.0
License: GPLv2
Author: David Bergmann
 */

global $wpdb;

define( 'ECP_MCT_TABLE_TESTS' , $wpdb->get_blog_prefix().'ecp_mct_tests' );
define( 'ECP_MCT_TABLE_SECTIONS' , $wpdb->get_blog_prefix().'ecp_mct_sections' );
define( 'ECP_MCT_TABLE_QUESTIONS' , $wpdb->get_blog_prefix().'ecp_mct_questions' );
define( 'ECP_MCT_TABLE_USER_ANSWERS' , $wpdb->get_blog_prefix().'ecp_mct_user_answers' );

define( 'PLUGIN_DIR' , plugin_dir_url( __FILE__ ) );
define( 'FILE_DIR' , dirname(__FILE__).'/' );

// Call Wpsqt_Installer Class to write in WPSQT tables on activation 
register_activation_hook ( __FILE__, 'ecp_mct_main_install' );
register_deactivation_hook ( __FILE__, 'ecp_mct_main_uninstall' );

/**
 * Function to create db tables on activation
 */
function ecp_mct_main_install(){

	global $wpdb;
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".ECP_MCT_TABLE_TESTS."` (
				  `id` INT(11) NOT NULL AUTO_INCREMENT,
				  `name` VARCHAR(512) NOT NULL,
				  `options_num` INT NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".ECP_MCT_TABLE_SECTIONS."` (
				  `id` INT(11) NOT NULL AUTO_INCREMENT,
				  `test_id` INT(11) NOT NULL,
				  `name` VARCHAR(255) NOT NULL,
				  `duration` INT NOT NULL,
				  `order` INT NOT NULL,
				  UNIQUE KEY `id` (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".ECP_MCT_TABLE_QUESTIONS."` (
				  `id` INT(11) NOT NULL AUTO_INCREMENT,
				  `section_id` INT(11) NOT NULL,
				  `type` VARCHAR(255) NOT NULL,
				  `order` INT NOT NULL,
				  `options` TEXT DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".ECP_MCT_TABLE_USER_ANSWERS."` (
				  `id` INT(11) NOT NULL AUTO_INCREMENT,
				  `section_id` INT(11) NOT NULL,
				  `user_id` INT(11) NOT NULL,
				  `answers` TEXT DEFAULT NULL,
				  `start_time` datetime DEFAULT NULL,
				  `end_time` datetime DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
}

/**
 * Function to delete db tables on deactivation
 */
function ecp_mct_main_uninstall(){
	
	global $wpdb;
	
	$wpdb->query("DROP TABLE `".ECP_MCT_TABLE_TESTS.";");
	$wpdb->query("DROP TABLE `".ECP_MCT_TABLE_SECTIONS.";");
	$wpdb->query("DROP TABLE `".ECP_MCT_TABLE_QUESTIONS.";");
	$wpdb->query("DROP TABLE `".ECP_MCT_TABLE_USER_ANSWERS.";");
	$wpdb->query("DELETE FROM `wp_posts` WHERE `post_type` = 'test';");
}

// Add a new submenu under Options:
add_action('admin_menu', 'ecp_mct_menu');

function ecp_mct_menu() {
	add_menu_page( 'Multiple Choice Tests', 'Multiple Choice Tests', 'administrator', 'ecp_mct/pages/admin/test-list.php', null, PLUGIN_DIR."images/icon.png", 100);
	add_submenu_page('ecp_mct/pages/admin/test-list.php', 'New Test', 'New Test', 'administrator', 'ecp_mct/pages/admin/test-new.php');
}

// Create tests post type
add_action( 'init', 'post_type_tests' );
function post_type_tests() {
	register_post_type(
		'test',
		array(
			'label' => 'Test',
			'public' => true,
			'show_ui' => false,
			'show_in_menu' => false,
			'supports' => array(
				'title',
			)
		)
	);
}

/**
 * This will scan all the content pages that wordpress outputs for our special code.
 * If the code is found, it will replace the requested test.
 */
add_shortcode( 'ECP_MCT', 'ecp_mct_shortcode' );
function ecp_mct_shortcode( $attr ) {
	$test_id = $attr[0];
	
	$contents = '';
	if(is_numeric($test_id)) {
		ob_start();
		include(ABSPATH . 'wp-content/plugins/ecp_mct/pages/site/show_test.php');
		$contents = ob_get_contents();
		ob_end_clean();
	}
	return $contents;
}