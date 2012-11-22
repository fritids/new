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

define( 'PLUGIN_DIR' , plugin_dir_url( __FILE__ ) );
define( 'FILE_DIR' , dirname(__FILE__).'/' );

// Call Wpsqt_Installer Class to write in WPSQT tables on activation 
register_activation_hook ( __FILE__, 'ecp_mct_main_install' );

/**
 * Function to create db tables on activation
 */
function ecp_mct_main_install(){

	global $wpdb;
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".ECP_MCT_TABLE_TESTS."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(512) NOT NULL,
				  `questions_num` INT NOT NULL,
				  `options_num` INT NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".ECP_MCT_TABLE_SECTIONS."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `test_id` int(11) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `order` INT NOT NULL,
				  UNIQUE KEY `id` (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".ECP_MCT_TABLE_QUESTIONS."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `section_id` int(11) NOT NULL,
				  `order` INT NOT NULL,
				  `options` TEXT DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
}

// Add a new submenu under Options:
add_action('admin_menu', 'ecp_mct_menu');

function ecp_mct_menu() {
	add_menu_page( 'Multiple Choice Tests', 'Multiple Choice Tests', 'administrator', 'ecp_mct/pages/admin/test-list.php', null, PLUGIN_DIR."images/icon.png", 100);
	add_submenu_page('ecp_mct/pages/admin/test-list.php', 'New Test', 'New Test', 'administrator', 'ecp_mct/pages/admin/test-new.php');
}