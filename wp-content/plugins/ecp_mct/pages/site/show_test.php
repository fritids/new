<?php
global $wpdb;

$current_user = wp_get_current_user();

// Get test sections
$query = "SELECT `sections`.`id`,`sections`.`name`,`sections`.`duration`,`answers`.`id` as `answer_id`,`answers`.`start_time` FROM ".ECP_MCT_TABLE_SECTIONS." `sections`
	LEFT JOIN ".ECP_MCT_TABLE_USER_ANSWERS." `answers` ON `answers`.`section_id` = `sections`.`id` AND `answers`.`user_id` = %d
	WHERE `sections`.`test_id`=%d
	AND `answers`.`end_time` IS NULL
	ORDER BY `sections`.`order`";

$sections = $wpdb->get_results($wpdb->prepare($query, $current_user->ID, $test_id));
$current_section = $sections[0];

$answer_id = $current_section->answer_id;
$start_time = $current_section->start_time;

// Create answer row in db for this user if doesn't exist
if(is_user_logged_in() && $current_section && !$answer_id) {
	$start_time = date("Y-m-d H:i:s");
	// Save test information in database
	$query = "INSERT INTO ".ECP_MCT_TABLE_USER_ANSWERS." (`section_id`, `user_id`, `start_time`) VALUES(%d,%d,%s)";
	$wpdb->get_results($wpdb->prepare($query, $current_section->id, $current_user->ID, $start_time));
	$answer_id = $wpdb->insert_id;
}

// Fill in options
$field_1_values = $field_4_values = array("",".","0","1","2","3","4","5","6","7","8","9");
$field_2_values =  $field_3_values = array("","/",".","0","1","2","3","4","5","6","7","8","9");

// Calculate estimated end time
$time_left = strtotime($start_time)+$current_section->duration - time();

$question_count = 1;
?>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/site.css" />
<script type="text/javascript" src="<?php echo PLUGIN_DIR; ?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo PLUGIN_DIR; ?>js/jquery.countdown.js"></script>

<div class="test-container">
	<?php if ( is_user_logged_in() ): ?> 
		<?php if($current_section): ?>
		<form name="test-take" action="<?php echo PLUGIN_DIR; ?>answers-save-action.php" method="post" id="test-<?php echo $test_id?>">

			<label class="section-title"><?php echo $current_section->name; ?></label>
			
			<div id="time-left"></div>
			
			<?php
				// Get test questions
				$query = "SELECT `id`,`type`,`options` FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE `section_id`=%d ORDER BY `order`";
				$questions = $wpdb->get_results($wpdb->prepare($query, $current_section->id));
			?>
			<ul class="question-list">
				<?php
					foreach($questions as $question):
						$options = json_decode($question->options, true);
				?>
				<li>
					<div>Question <?php echo $question_count++;?>:</div>

					<?php if($question->type == "Multiple Choice"): ?>
					<div class="options-list-container clearfix">
						<ol class="options-list">
							<?php foreach($options as $option): ?>
							<li>
								<input type="radio" name="answers[<?php echo $question->id; ?>]" value="<?php echo $option['order']; ?>" />
							</li>
							<?php  endforeach; ?>
						</ol>
					</div>
					<?php else: ?>
					<div class="answer-list-container">
						<ol class="answers-list">
							<select name="answers[<?php echo $question->id; ?>][field_1_value]">
								<?php foreach($field_1_values as $val): ?>
								<option val="<?php echo $val;?>"><?php echo $val;?></option>
								<?php  endforeach; ?>
							</select>
							<select name="answers[<?php echo $question->id; ?>][field_2_value]">
								<?php foreach($field_2_values as $val): ?>
								<option val="<?php echo $val;?>"><?php echo $val;?></option>
								<?php  endforeach; ?>
							</select>
							<select name="answers[<?php echo $question->id; ?>][field_3_value]">
								<?php foreach($field_3_values as $val): ?>
								<option val="<?php echo $val;?>"><?php echo $val;?></option>
								<?php  endforeach; ?>
							</select>
							<select name="answers[<?php echo $question->id; ?>][field_4_value]">
								<?php foreach($field_4_values as $val): ?>
								<option val="<?php echo $val;?>"><?php echo $val;?></option>
								<?php  endforeach; ?>
							</select>
						</ol>
					</div>
					<?php endif; ?>


				</li>
				<?php  endforeach; ?>
			</ul>

			<input type="submit" name="submit" id="action-button" value="<?php if(count($sections) >1): ?>Next Section<?php else: ?>Finish Test<?php endif; ?>"  />
			<input type="hidden" name="sections_left" value="<?php echo  count($sections)-1 ?>" />
			<input type="hidden" name="answer_id" value="<?php echo  $answer_id ?>" />

		</form>
		<?php else: ?>
		<p>Test finished. Thanks!</p>
		<?php endif; ?>
	<?php else: ?>
		<p>Sorry! You have to be logged in to take this test.</p>
	<?php endif; ?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#time-left").countdown({until: <?php echo $time_left?>, format: 'MS'});
	});
</script>