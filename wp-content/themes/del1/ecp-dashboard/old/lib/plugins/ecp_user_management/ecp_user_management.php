<?php
add_action('admin_menu', 'ecp_user_management_menu');

function ecp_user_management_menu() {
	add_menu_page('User List', 'ECP Students', 'manage_options', 'student-list', 'ecp_user_management_list');
	add_submenu_page( 'student-list', 'User List', 'ECP Teachers', 'manage_options', 'teacher-list', 'ecp_user_management_list');
	add_submenu_page( 'student-list', 'User List', 'Create Users', 'manage_options', 'user-create', 'ecp_user_management_list');
	
}
function ecp_user_management_list() {
	$filename=dirname(__FILE__)."/pages/".$_GET["page"].".php";
	if(is_file($filename)){
		require_once($filename);
	}
}