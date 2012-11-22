<?php
require('../../../wp-blog-header.php');
auth_redirect();

// I could have put this in the quiz_form.php - but the redirect will not work.
if(isset($_REQUEST['submit'])) {
	
	$query = "INSERT INTO ".ECP_MCT_TABLE_TESTS." (`name`, `questions_num`, `options_num`) VALUES(%s,%d,%d)";
	$wpdb->get_results($wpdb->prepare($query, $_REQUEST['test_title'], $_REQUEST['questions_num'], $_REQUEST['options_num']));
	$test_id = $wpdb->insert_id;
	
	$sections = json_decode(str_replace(array("\\\"","\\'"), array("\"","'"), $_REQUEST['sections']), true);
	
	foreach ($sections as $section) {
		$query = "INSERT INTO ".ECP_MCT_TABLE_SECTIONS." (`test_id`, `name`, `order`) VALUES(%d,%s,%d)";
		$wpdb->get_results($wpdb->prepare($query, $test_id, $section['name'], $section['order']));
		$section_id = $wpdb->insert_id;
		
		foreach($section['questions'] as $question) {
			$query = "INSERT INTO ".ECP_MCT_TABLE_QUESTIONS." (`section_id`, `order`, `options`) VALUES(%d,%d,%s)";
			$wpdb->get_results($wpdb->prepare($query, $section_id, $question['order'], json_encode($question['options'])));
			//$section_id = $wpdb->insert_id;
		}
	}
	
	wp_redirect(get_option('home') . '/wp-admin/admin.php?page=ecp_mct/pages/admin/test-list.php');
}
exit;
