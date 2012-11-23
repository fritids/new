<?php
add_action('admin_menu', 'ecp_user_management_menu');
add_action("admin_init", "ecp_admin_init");

function ecp_user_management_menu()
{
	add_menu_page('User List', 'ECP Students', 'manage_options', 'student-list', 'ecp_user_management_list');
	add_submenu_page('student-list', 'Teacher List', 'ECP Teachers', 'manage_options', 'teacher-list', 'ecp_user_management_list');
	add_submenu_page('student-list', 'Create Student', 'Create Student', 'manage_options', 'student-create', 'ecp_user_management_list');
	add_submenu_page('student-list', 'Create Teacher', 'Create Teacher', 'manage_options', 'teacher-create', 'ecp_user_management_list');
	add_submenu_page('student-list', 'Import From CB', 'Import From CB', 'manage_options', 'import-from-clickbank', 'ecp_user_management_list');
	add_submenu_page('student-list', '', '', 'manage_options', 'create-user', 'ecp_user_management_list');
	add_submenu_page('student-list', '', '', 'manage_options', 'delete-user', 'ecp_user_management_list');
}

function ecp_user_management_list()
{
	$filename = dirname(__FILE__) . "/pages/" . $_GET["page"] . ".php";
	if(is_file($filename))
	{
		require_once ($filename);
	}
}

function ecp_admin_init()
{
	wp_register_script( 'table_sortable', get_bloginfo("template_directory")."/lib/js/jquery.tablesorter.js", __FILE__);
	wp_enqueue_script( 'table_sortable' );
	wp_enqueue_style('table_sortable_style', get_bloginfo('template_directory') . '/lib/js/sorter_css/style.css');
}
