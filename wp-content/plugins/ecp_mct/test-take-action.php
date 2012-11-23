<?php
require('../../../wp-blog-header.php');
auth_redirect();

$current_user = wp_get_current_user();

if(isset($_REQUEST['submit'])) {
	// Save test information in database
	$query = "INSERT INTO ".ECP_MCT_TABLE_USER_ANSWERS." (`test_id`, `user_id`, `answers`) VALUES(%d,%d,%s)";
	$wpdb->get_results($wpdb->prepare($query, $_REQUEST['test_id'], $current_user->ID, json_encode($_REQUEST['answers'])));
	$test_id = $wpdb->insert_id;
}

wp_redirect(get_option('home') . '/blog/test/test_'.$_REQUEST['test_id']);
?>