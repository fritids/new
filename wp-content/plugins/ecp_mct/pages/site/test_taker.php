<?php
require(FILE_DIR.'pages/wpframe.php');
wpframe_stop_direct_call(__FILE__);

global $wpdb;

$current_user = wp_get_current_user();

// Get all created tests
$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'SAT' ORDER BY `id`";
$sat_tests = $wpdb->get_results($wpdb->prepare($query, $test_id));

$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'ACT' ORDER BY `id`";
$act_tests = $wpdb->get_results($wpdb->prepare($query, $test_id));
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
									<td colspan="3" class="take-test">
										<a href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>" target="_blank">Take test</a>
									</td>
								<?php else: ?>
									<td class="center"><?php echo $notes['Reading']; ?></td>
									<td class="center"><?php echo $notes['Math']; ?></td>
									<td class="center"><?php echo $notes['Writing']; ?></td>
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
									<td colspan="3" class="take-test">
										<a href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>" target="_blank">Take test</a>
									</td>
								<?php else: ?>
									<td class="center"><?php echo $notes['English']; ?></td>
									<td class="center"><?php echo $notes['Math']; ?></td>
									<td class="center"><?php echo $notes['Reading']; ?></td>
									<td class="center"><?php echo $notes['Science']; ?></td>
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