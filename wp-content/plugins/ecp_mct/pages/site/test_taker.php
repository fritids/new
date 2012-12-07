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

<div class="test-taker-container">
	<?php if ( is_user_logged_in() ): ?>
	<div class="test-taker-sat">
		<h2>SAT</h2>
		<?php if($sat_tests): ?>
		<table>
			<thead>
			<tr>
				<th>&nbsp;</th>
				<th style="width: 60px;">Reading</th>
				<th style="width: 50px;">Math</th>
				<th style="width: 60px;">Writing</th>
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
					<td style="text-align: center;"><?php echo $notes['Reading']; ?></td>
					<td style="text-align: center;"><?php echo $notes['Math']; ?></td>
					<td style="text-align: center;"><?php echo $notes['Writing']; ?></td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php else: ?>
		<p class="info">No Tests found.</p>
		<?php endif; ?>
	</div>
	<div class="test-taker-act">
		<h2>ACT</h2>
		<?php if($act_tests): ?>
		<table>
			<thead>
			<tr>
				<th>&nbsp;</th>
				<th style="width: 60px;">English</th>
				<th style="width: 50px;">Math</th>
				<th style="width: 60px;">Reading</th>
				<th style="width: 60px;">Science</th>
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
					<td style="text-align: center;"><?php echo $notes['English']; ?></td>
					<td style="text-align: center;"><?php echo $notes['Math']; ?></td>
					<td style="text-align: center;"><?php echo $notes['Reading']; ?></td>
					<td style="text-align: center;"><?php echo $notes['Science']; ?></td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php else: ?>
		<p class="info">No Tests found.</p>
		<?php endif; ?>
	</div>
	<?php else: ?>
		<p>Sorry! You have to be logged in.</p>
	<?php endif; ?>
</div>