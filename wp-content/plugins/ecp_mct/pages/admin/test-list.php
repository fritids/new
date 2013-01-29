<?php
require(FILE_DIR.'pages/wpframe.php');
wpframe_stop_direct_call(__FILE__);

global $wpdb;

// Get all created tests
$query = "SELECT `id`,`name`,`type` FROM ".ECP_MCT_TABLE_TESTS." ORDER BY `id`";
$tests = $wpdb->get_results($wpdb->prepare($query, $test_id));

if($_REQUEST['message'] == 'test_deleted') {
	wpframe_message('Test successfully deleted');
}

?>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_DIR; ?>css/admin.css" />

<div class="wrap tests-list">
	<div id="theme-options-wrap"><img class="icon32" src="<?php echo PLUGIN_DIR; ?>images/icon-32.png"></div>
	<h2>
		Multiple Choice Tests
		<a href="<?php echo admin_url();?>admin.php?page=ecp_mct/pages/admin/test-new.php" class="add-new-h2">Add New</a>
	</h2>
	
	<h3>Created Tests</h3>
	<?php if($tests): ?>
	<table class="widefat">
		<thead>
		<tr>
			<th>Name</th>
			<th style="width: 100px;">Type</th>
			<th style="width: 120px;">Actions</th>
		</tr>
		</thead>
		<tbody id="the-list">
		<?php foreach($tests as $test): ?>
		<tr>
			<td><?php echo $test->name;?></td>
			<td><?php echo $test->type;?></td>
			<td>
				<a href="<?php echo get_option('home') . '/blog/test/test_'.$test->id ?>" target="_blank">View</a> |
				<a href="<?php echo get_option('home') . '/wp-admin/admin.php?page=ecp_mct/pages/admin/test-new.php&action=edit&test='.$test->id?>">Edit</a> |
				<form action="<?php echo PLUGIN_DIR; ?>delete-test-action.php" method="post" style="display: inline">
					<input type="hidden" name="test_id" value="<?php echo $test->id ?>">
					<a class="delete-test" href="#">Delete</a>
				</form>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p class="info">No Tests found.</p>
	<?php endif; ?>
</div>


<script type="text/javascript">
	
	jQuery(".delete-test").click(function() {
		if(confirm("Are you sure you want to delete this test?")) {
			jQuery(this).parent().submit();
		}
	});
</script>