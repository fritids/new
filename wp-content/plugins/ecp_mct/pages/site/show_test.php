<?php
global $wpdb;

$current_user = wp_get_current_user();

// See if user has previously taken the test
$taken_test = false;
$query = "SELECT `id`,`answers` FROM ".ECP_MCT_TABLE_USER_ANSWERS." WHERE `test_id`=%d  AND `user_id`=%d";
$user_answers = $wpdb->get_results($wpdb->prepare($query, $test_id, $current_user->ID));
if($user_answers) {
	$answers = json_decode($user_answers[0]->answers, true);
	$taken_test = true;
}

// Get test sections
$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_SECTIONS." WHERE `test_id`=%d ORDER BY `order`";
$sections = $wpdb->get_results($wpdb->prepare($query, $test_id));

$question_count = 1;
?>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/site.css" />

<div class="test-container">
	<?php if ( is_user_logged_in() ): ?> 
	<form name="test-take" action="<?php echo PLUGIN_DIR; ?>test-take-action.php" method="post" id="test-<?php echo $test_id?>">
		<ul class="section-list">
			<?php foreach($sections as $section): ?>
			<li>
				<label class="section-title"><?php echo $section->name; ?></label>
				<?php
					// Get test questions
					$query = "SELECT `id`,`options` FROM ".ECP_MCT_TABLE_QUESTIONS." WHERE `section_id`=%d ORDER BY `order`";
					$questions = $wpdb->get_results($wpdb->prepare($query, $section->id));
				?>
				<ul class="question-list">
					<?php
						foreach($questions as $question):
							$options = json_decode($question->options, true);
					?>
					<li>
						Question <?php echo $question_count++;?>:
						<ol class="options-list">
							<?php foreach($options as $option): ?>
							<li>
								<?php if($taken_test): ?>
									<input type="radio" <?php if($option['order'] == $answers[$question->id]): ?>checked<?php endif; ?> disabled />
									<?php if($option['order'] == $answers[$question->id] && $option['correct']): ?>
										<img src="<?php echo PLUGIN_DIR; ?>images/correct.png">
									<?php elseif($option['order'] == $answers[$question->id] && !$option['correct']): ?>
										<img src="<?php echo PLUGIN_DIR; ?>images/wrong.png">
									<?php endif; ?>
								<?php else: ?>
									<input type="radio" name="answers[<?php echo $question->id; ?>]" value="<?php echo $option['order']; ?>" />
								<?php endif; ?>
							</li>
							<?php  endforeach; ?>
						</ol>
					</li>
					<?php  endforeach; ?>
				</ul>
			</li>
			<?php endforeach; ?>
		</ul>
		
		<?php if(!$taken_test): ?>
		<input type="submit" name="submit" id="action-button" value="Submit Test"  />
		<input type="hidden" name="test_id" value="<?php echo  $test_id ?>" />
		<?php endif; ?>
	</form>
	<?php else: ?>
		<p>Sorry! You have to be logged in to take this test.</p>
	<?php endif; ?>
</div>