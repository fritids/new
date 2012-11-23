<?php
global $wpdb;

// Get all created tests
$query = "SELECT `id`,`name` FROM ".ECP_MCT_TABLE_TESTS." ORDER BY `id`";
$tests = $wpdb->get_results($wpdb->prepare($query, $test_id));
?>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/admin.css" />

<div class="wrap">
	<div id="theme-options-wrap"><img class="icon32" src="<?php echo PLUGIN_DIR; ?>images/icon-32.png"></div>
	<h2>
		Multiple Choice Tests
		<a href="<?php echo admin_url();?>admin.php?page=ecp_mct/pages/admin/test-new.php" class="add-new-h2">Add New</a>
	</h2>
	
	<?php if($tests): ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Actions</th>
		</tr>
		<?php foreach($tests as $test): ?>
		<tr>
			<td><?php echo $test->name;?></td>
			<td>
				<a href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>" target="_blank">View</a> |
				<a>Edit</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php else: ?>
	<p>There are no tests created.</p>
	<?php endif; ?>
</div>


<script type="text/javascript">
	
</script>