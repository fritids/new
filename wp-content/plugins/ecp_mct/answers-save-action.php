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
		$scores = array();
		if($test->type == "SAT") {
			$scores['Reading'] = 0;
			$scores['Math'] = 0;
			$scores['Writing'] = 0;
		} else {
			$scores['English'] = 0;
			$scores['Math'] = 0;
			$scores['Reading'] = 0;
			$scores['Science'] = 0;
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
					$scores[$section->type] += 1;
				} else {
					if($test->type == "SAT") {
						$scores[$section->type] -= 0.25;
					}
				}
			}
			
		}
		
		// Transform to sacaled score
		$scaled_scores = array();
		foreach($scores as $k=>$score) {
			$query = "SELECT `scaled_score` FROM ".ECP_MCT_TABLE_SCALED_SCORES." WHERE `test_id`=%d AND `section_type`=%s AND `raw_score`=%d";
			$r = $wpdb->get_row($wpdb->prepare($query, $_REQUEST['test_id'], $k, $score));
			
			if(!$r->scaled_score) {
				$query = "SELECT MIN(`scaled_score`) FROM ".ECP_MCT_TABLE_SCALED_SCORES." WHERE `test_id`=%d AND `section_type`=%s GROUP BY `section_type`";
				$r = $wpdb->get_row($wpdb->prepare($query, $_REQUEST['test_id'], $k));
			}
			$scaled_scores[$k] = $r->scaled_score;
		}
		
		$query = "INSERT INTO ".ECP_MCT_TABLE_USER_NOTES." (`test_id`, `user_id`, `notes`) VALUES(%d,%d,%s)";
		$wpdb->get_results($wpdb->prepare($query, $_REQUEST['test_id'], $current_user->ID, json_encode($scaled_scores)));
		
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