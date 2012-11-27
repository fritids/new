<?php
require('../../../wp-blog-header.php');
auth_redirect();


if(isset($_REQUEST['submit'])) {
	if($_REQUEST['action'] == 'edit') {
		$test_id = $_REQUEST['test_id'];
		
		// Save test information in database
		$query = "UPDATE ".ECP_MCT_TABLE_TESTS." SET `name`=%s, `options_num`=%d WHERE `id`=%d";
		$wpdb->get_results($wpdb->prepare($query, $_REQUEST['test_title'], $_REQUEST['options_num'], $test_id));
		
		// Delete from db sections that where deleted by the user
		$deleted_sections = json_decode(str_replace(array("\\\"","\\'"), array("\"","'"), $_REQUEST['deleted_sections']), true);
		if(count($deleted_sections)) {
			$query = "DELETE FROM ".ECP_MCT_TABLE_SECTIONS." WHERE ID IN (".implode(",", $deleted_sections).")";
			$wpdb->get_results($query);
		}
		
		$sections = json_decode(str_replace(array("\\\"","\\'"), array("\"","'"), $_REQUEST['sections']), true);
		
		foreach ($sections as $k=>$section) {
			// Delete from db questions that where deleted by the user
			if(count($section['deleted_questions'])) {
				$query = "DELETE FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE ID IN (".implode(",", $section['deleted_questions']).")";
				$wpdb->get_results($query);
			}
			
			if(isset($section['id'])) { // if section exists update
				$query = "UPDATE ".ECP_MCT_TABLE_SECTIONS." SET `name` = %s, `order` = %d WHERE `id` = %d";
				$wpdb->get_results($wpdb->prepare($query, $section['name'], $k, $section['id']));
				
				foreach($section['questions'] as $j=>$question) {
					if(isset($question['id'])) { // if $question exists update
						$query = "UPDATE ".ECP_MCT_TABLE_QUESTIONS." SET `options` = %s, `order` = %d WHERE `id` = %d";
						$wpdb->get_results($wpdb->prepare($query, json_encode($question['options']), $j, $question['id']));
					} else { // if question doesn't exists update
						$query = "INSERT INTO ".ECP_MCT_TABLE_QUESTIONS." (`section_id`, `type`, `order`, `options`) VALUES(%d,%s,%d,%s)";
						$wpdb->get_results($wpdb->prepare($query, $section['id'], $question['type'], $j, json_encode($question['options'])));
					}
				}
				
			} else { // if section doesn't exists update
				$query = "INSERT INTO ".ECP_MCT_TABLE_SECTIONS." (`test_id`, `name`, `order`) VALUES(%d,%s,%d)";
				$wpdb->get_results($wpdb->prepare($query, $test_id, $section['name'], $k));
				$section_id = $wpdb->insert_id;

				foreach($section['questions'] as $j=>$question) {
					$query = "INSERT INTO ".ECP_MCT_TABLE_QUESTIONS." (`section_id`, `type`, `order`, `options`) VALUES(%d,%s,%d,%s)";
					$wpdb->get_results($wpdb->prepare($query, $section_id, $question['type'], $j, json_encode($question['options'])));
				}
			}
		}
		
		wp_redirect(get_option('home') . '/wp-admin/admin.php?page=ecp_mct/pages/admin/test-new.php&message=update_test&action=edit&test='.$test_id);
	} else {
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
}
exit;
