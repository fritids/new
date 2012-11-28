<?php
require('../../../wp-blog-header.php');
auth_redirect();

$current_user = wp_get_current_user();

if(isset($_REQUEST['submit'])) {
	// Save test information in database
	$query = "UPDATE ".ECP_MCT_TABLE_USER_ANSWERS." SET `answers` = %s, `end_time` = %s WHERE `id`= %d";
	$wpdb->get_results($wpdb->prepare($query, json_encode($_REQUEST['answers']), date("Y-m-d H:i:s"), $_REQUEST['answer_id']));
}

wp_redirect(get_option('home') . '/blog/test/test_'.$_REQUEST['test_id']);
?>