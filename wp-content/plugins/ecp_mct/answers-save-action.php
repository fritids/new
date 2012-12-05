<?php
require('../../../wp-blog-header.php');
auth_redirect();

$current_user = wp_get_current_user();

if(isset($_REQUEST['submit'])) {
	// Save test information in database
	$query = "UPDATE ".ECP_MCT_TABLE_USER_ANSWERS." SET `answers` = %s, `end_time` = %s WHERE `id`= %d";
	$wpdb->get_results($wpdb->prepare($query, json_encode($_REQUEST['answers']), date("Y-m-d H:i:s"), $_REQUEST['answer_id']));
	
	// Score student test if no sections left
	if($_REQUEST['sections_left'] == 0) {
		//Get test
		$query = "SELECT `id`, `name`, `type`, `options_num` FROM ".ECP_MCT_TABLE_TESTS." WHERE `id`=%d";
		$test = $wpdb->get_row($wpdb->prepare($query, $_REQUEST['test_id']));
		//Get sections
		$query = "SELECT `id`, `name`, `type`, `duration` FROM ".ECP_MCT_TABLE_SECTIONS." WHERE `test_id`=%d";
		$sections = $wpdb->get_results($wpdb->prepare($query, $_REQUEST['test_id']));
		
		//Initialize RAW scores
		$score = array();
		if($test->type == "SAT") {
			$score['Reading'] = 0;
			$score['Math'] = 0;
			$score['Writing'] = 0;
		} else {
			$score['English'] = 0;
			$score['Math'] = 0;
			$score['Reading'] = 0;
			$score['Science'] = 0;
		}
		
		foreach($sections as $section) {
			// Get questions
			$query = "SELECT `id`, `type`, `options` FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE `section_id`=%d";
			$questions = $wpdb->get_results($wpdb->prepare($query, $section->id));
			
			// Get questions
			$query = "SELECT `answers` FROM ".ECP_MCT_TABLE_USER_ANSWERS." WHERE `section_id`=%d AND `user_id`=%d";
			$user_answers = $wpdb->get_row($wpdb->prepare($query, $section->id, $current_user->ID));
			
			$answers = json_decode($user_answers->answers, true);
			
			foreach($questions as $question) {
				$options = json_decode($question->options, true);
				$correct = false;
				if($question->type == "Multiple Choice") {
					$answer = $answers[$question->id];
					if($options[$answer]['correct'])
						$correct = true;
				} else {
					$answer = $answers[$question->id];
					$number = to_number($answer['field_1_value'].$answer['field_2_value'].$answer['field_3_value'].$answer['field_4_value']);
					
					foreach($options as $option) {
						if($option['type'] == "Range") {
							if($number >= (float) $option['start'] && $number <= (float) $option['end']) {
								$correct = true;
								break;
							}
						} else {
							$a_number = to_number($option['field_1'].$option['field_2'].$option['field_3'].$option['field_4']);
							
							if($number == $a_number) {
								$correct = true;
								break;
							}
						}
					}
				}
				
				if($correct) {
					$score[$section->type] += 1;
				} else {
					if($test->type == "SAT") {
						$score[$section->type] -= 0.25;
					}
				}
			}
			
		}
		echo '<pre>';print_r($score);die('</pre>');
	}
}

function to_number ($str) {
	if(preg_match('/^((?P<whole>\d+)(?=\s))?(\s*)?(?P<numerator>\d+)\/(?P<denominator>\d+)$/', $str, $m)) {
		return $m['whole'] + $m['numerator']/$m['denominator'];
	}
	return (float) $str;
}

wp_redirect(get_option('home') . '/blog/test/test_'.$_REQUEST['test_id']);
?>