<?php
require(FILE_DIR.'pages/wpframe.php');
wpframe_stop_direct_call(__FILE__);

global $wpdb;

$current_user = wp_get_current_user();

// Get all created tests
$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'SAT' ORDER BY `id`";
$sat_tests = $wpdb->get_results($query);

$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'ACT' ORDER BY `id`";
$act_tests = $wpdb->get_results($query);
?>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/site.css" />

<div class="row-fluid">
	<div class="span6">
		<div class="widget-block">
			<div class="widget-head">
				<h5><i class="black-icons pencil"></i>SAT Exams</h5>
			</div>
			<div class="widget-content">
				<div class="widget-box">
					<table class="table post-tbl">
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th class="center" style="width: 60px;">Reading</th>
								<th class="center" style="width: 50px;">Math</th>
								<th class="center" style="width: 60px;">Writing</th>
								<th class="center" style="width: 50px;">Total</th>
								<th class="center" style="width: 90px;"></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($sat_tests as $test):
									// Get test sections
									$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_SECTIONS." `sections`
										LEFT JOIN ".ECP_MCT_TABLE_USER_ANSWERS." `answers` ON `answers`.`section_id` = `sections`.`id` AND `answers`.`user_id` = %d
										WHERE `sections`.`test_id`=%d
										AND `answers`.`end_time` IS NULL";

									$sections = $wpdb->get_row($wpdb->prepare($query, $current_user->ID, $test->id));

									// Get notes
									$notes = array();
									$query = "SELECT `notes` FROM ".ECP_MCT_TABLE_USER_NOTES." WHERE `test_id`=%d AND `user_id`=%d";
									$r = $wpdb->get_row($wpdb->prepare($query, $test->id, $current_user->ID));
									$notes = json_decode($r->notes, true);
							?>
							<tr>
								<td><?php echo $test->name;?></td>
								<?php if($sections->count): ?>
									<td colspan="4"></td>
									<?php /*<td><a class="take-test" href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>">Take test</a></td>*/?>
									<td><a class="take-test" test_id="<?php echo $test->id; ?>" href="#test-warning">Take test</a></td>
								<?php else: ?>
									<td class="center"><?php echo $notes['Reading']; ?></td>
									<td class="center"><?php echo $notes['Math']; ?></td>
									<td class="center"><?php echo $notes['Writing']; ?></td>
									<td class="center"><?php echo $notes['Reading']+$notes['Math']+$notes['Writing']; ?></td>
									<td><a class="review-test" href="<?php echo plugin_dir_url(__FILE__); ?>score_report.php?id=<?php echo $test->id; ?>" target="_blank">Review</a></td>
								<?php endif; ?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="span6">
		<div class="widget-block">
			<div class="widget-head">
				<h5><i class="black-icons pencil"></i>ACT Exams</h5>
			</div>
			<div class="widget-content">
				<div class="widget-box">
					<table class="table post-tbl">
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th class="center" style="width: 60px;">English</th>
								<th class="center" style="width: 50px;">Math</th>
								<th class="center" style="width: 60px;">Reading</th>
								<th class="center" style="width: 60px;">Science</th>
								<th class="center" style="width: 50px;">Total</th>
								<th class="center" style="width: 90px;"></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($act_tests as $test):
									// Get test sections
									$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_SECTIONS." `sections`
										LEFT JOIN ".ECP_MCT_TABLE_USER_ANSWERS." `answers` ON `answers`.`section_id` = `sections`.`id` AND `answers`.`user_id` = %d
										WHERE `sections`.`test_id`=%d
										AND `answers`.`end_time` IS NULL";
									$sections = $wpdb->get_row($wpdb->prepare($query, $current_user->ID, $test->id));

									// Get notes
									$notes = array();
									$query = "SELECT `notes` FROM ".ECP_MCT_TABLE_USER_NOTES." WHERE `test_id`=%d AND `user_id`=%d";
									$r = $wpdb->get_row($wpdb->prepare($query, $test->id, $current_user->ID));
									$notes = json_decode($r->notes, true);
							?>
							<tr>
								<td><?php echo $test->name;?></td>
								<?php if($sections->count): ?>
									<td colspan="5"></td>
									<?php /*<td><a class="take-test" href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>">Take test</a></td>*/?>
									<td><a class="take-test" test_id="<?php echo $test->id; ?>" href="#test-warning">Take test</a></td>
								<?php else: ?>
									<td class="center"><?php echo $notes['English']; ?></td>
									<td class="center"><?php echo $notes['Math']; ?></td>
									<td class="center"><?php echo $notes['Reading']; ?></td>
									<td class="center"><?php echo $notes['Science']; ?></td>
									<td class="center"><?php echo ($notes['English']+$notes['Math']+$notes['Reading']+$notes['Science'])/4; ?></td>
									<td><a class="review-test" href="<?php echo plugin_dir_url(__FILE__); ?>score_report.php?id=<?php echo $test->id; ?>" target="_blank">Review</a></td>
								<?php endif; ?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div style="display:none">
	<div id="test-warning">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".take-test").click( function() {
			if(confirm("Once you start the test, the countdown will start running. Continue?")) {
				window.location.href = "<?php echo get_option('home') ?>/blog/test/test_"+jQuery(this).attr('test_id');
			}
		});
	});
</script>