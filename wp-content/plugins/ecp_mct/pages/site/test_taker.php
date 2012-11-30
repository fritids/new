<?php
global $wpdb;

$current_user = wp_get_current_user();

// Get all created tests
$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'SAT' ORDER BY `id`";
$sat_tests = $wpdb->get_results($wpdb->prepare($query, $test_id));

$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'ACT' ORDER BY `id`";
$act_tests = $wpdb->get_results($wpdb->prepare($query, $test_id));
?>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/site.css" />

<h1>Testing Module</h1>

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
				
					// Get test status
					// Get test sections
					$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_SECTIONS." `sections`
						LEFT JOIN ".ECP_MCT_TABLE_USER_ANSWERS." `answers` ON `answers`.`section_id` = `sections`.`id` AND `answers`.`user_id` = %d
						WHERE `sections`.`test_id`=%d
						AND `answers`.`end_time` IS NULL";

					$sections = $wpdb->get_row($wpdb->prepare($query, $current_user->ID, $test->id));
			?>
			<tr>
				<td><?php echo $test->name;?></td>
				<?php if($sections->count): ?>
					<td colspan="3" class="take-test">
						<a href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>" target="_blank">Take test</a>
					</td>
				<?php else: ?>
					<td>#note</td>
					<td>#note</td>
					<td>#note</td>
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
			?>
			<tr>
				<td><?php echo $test->name;?></td>
				<?php if($sections->count): ?>
					<td colspan="3" class="take-test">
						<a href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>" target="_blank">Take test</a>
					</td>
				<?php else: ?>
					<td>#note</td>
					<td>#note</td>
					<td>#note</td>
					<td>#note</td>
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