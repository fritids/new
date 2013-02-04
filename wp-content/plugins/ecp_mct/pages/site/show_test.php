<?php
require(FILE_DIR.'pages/wpframe.php');
wpframe_stop_direct_call(__FILE__);

global $wpdb;

$current_user = wp_get_current_user();

// Get test info
$query = "SELECT `id`,`name`,`type` FROM ".ECP_MCT_TABLE_TESTS." WHERE `id`=%d";
$test = $wpdb->get_row($wpdb->prepare($query, $test_id));

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
$field_1_values = array("",".","1","2","3","4","5","6","7","8","9");
$field_2_values =  $field_3_values = array("","/",".","0","1","2","3","4","5","6","7","8","9");
$field_4_values = array("",".","0","1","2","3","4","5","6","7","8","9");

$question_count = 1;
?>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/site.css" />

<div class="test-container">
	<?php if ( is_user_logged_in() ): ?>
		<?php if($current_section): ?>
		<form name="test-take" action="<?php echo PLUGIN_DIR; ?>answers-save-action.php" method="post" id="test-<?php echo $test_id?>">

			<label class="section-title"><?php echo $current_section->name; ?></label>
			
			<div id="time-left" class="hasCountdown clearfix"></div>
			
			<?php
				// Get test questions
				$query = "SELECT `id`,`type`,`options` FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE `section_id`=%d ORDER BY `order`";
				$questions = $wpdb->get_results($wpdb->prepare($query, $current_section->id));
			?>
			<ul class="question-list">
				<?php
					foreach($questions as $k=>$question):
						$options = json_decode($question->options, true);
				?>
				<li>
					<div>Question <?php echo $question_count++;?>:</div>

					<?php if($question->type == "Multiple Choice"): ?>
					<div class="options-list-container clearfix">
						<ol class="options-list" <?php if($test->type == "ACT" && ($k+1)%2 == 0) echo 'START="6"'?>>
							<?php foreach($options as $j=>$option): ?>
							<li <?php if($test->type == "ACT" && ($k+1)%2 == 0 && $j == 3) echo 'VALUE="10"';?>>
								<input type="radio" name="answers[<?php echo $question->id; ?>]" value="<?php echo $j; ?>" />
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
			<input type="hidden" name="test_id" value="<?php echo  $test_id ?>" />

		</form>
		<?php else: ?>
		<p>Test finished. Thanks!</p>
			<?php
				$page = get_page_by_title("Practice SAT and ACT Exams");
				if ($page->ID):
			?>
			<div>
				<a href="<?php echo get_permalink($page->ID) ?>" class="button">See Scores</a>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php else: ?>
		<p>Sorry! You have to be logged in to take this test.</p>
	<?php endif; ?>
</div>

<?php
// Calculate estimated end time
$end_time = strtotime($start_time)+($current_section->duration*60);
?>

<script type="text/javascript">
	
	function updateTimer() {
		now = new Date().getTime()/1000;
		kickoff = <?php echo $end_time ?>;
		diff = kickoff - now;
		
		mins = Math.floor( diff / (60) );
		secs = Math.floor( diff );
		
		mm = mins;
		ss = secs  - mins  * 60;
		
		document.getElementById("time-left")
            .innerHTML = "<span class='countdown_row countdown_show2'>"+
                "<span class='countdown_section'><span class='countdown_amount'>" + mm + "</span><br> Minutes</span>" +
                "<span class='countdown_section'><span class='countdown_amount'>" + ss + "</span><br> Seconds</span>" +
				"</span>";
	}
	setInterval('updateTimer()', 1000 );
</script>