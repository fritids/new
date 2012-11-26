<?php
require('../../../wp-blog-header.php');
auth_redirect();


if(isset($_REQUEST['submit'])) {
	
	// Save test information in database
	$query = "INSERT INTO ".ECP_MCT_TABLE_TESTS." (`name`, `options_num`) VALUES(%s,%d)";
	$wpdb->get_results($wpdb->prepare($query, $_REQUEST['test_title'], $_REQUEST['options_num']));
	$test_id = $wpdb->insert_id;
	
	$sections = json_decode(str_replace(array("\\\"","\\'"), array("\"","'"), $_REQUEST['sections']), true);
	
	foreach ($sections as $k=>$section) {
		$query = "INSERT INTO ".ECP_MCT_TABLE_SECTIONS." (`test_id`, `name`, `order`) VALUES(%d,%s,%d)";
		$wpdb->get_results($wpdb->prepare($query, $test_id, $section['name'], $k));
		$section_id = $wpdb->insert_id;
		
		foreach($section['questions'] as $j=>$question) {
			$query = "INSERT INTO ".ECP_MCT_TABLE_QUESTIONS." (`section_id`, `type`, `order`, `options`) VALUES(%d,%s,%d,%s)";
			$wpdb->get_results($wpdb->prepare($query, $section_id, $question['type'], $j, json_encode($question['options'])));
		}
	}
	
	// Create new Test post object
	$current_user = wp_get_current_user();
	
	$my_test = array(
		'post_title'    => $_REQUEST['test_title'],
		'post_content'  => '[ECP_MCT '.$test_id.']',
		'post_name' => 'test_'.$test_id,
		'post_status'   => 'publish',
		'post_type'  => 'test',
		'post_author'   => $current_user->ID,
		'comment_status' => 'closed'
	);

	// Insert the test into the database
	wp_insert_post($my_test);
	
	wp_redirect(get_option('home') . '/wp-admin/admin.php?page=ecp_mct/pages/admin/test-new.php&message=new_test&action=edit&test='.$test_id);
}
exit;
