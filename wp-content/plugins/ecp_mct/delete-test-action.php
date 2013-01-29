<?php
require('../../../wp-blog-header.php');
auth_redirect();

if($_POST['test_id']) {
	$query = "DELETE FROM ".ECP_MCT_TABLE_TESTS." WHERE `id`= %d";
	$wpdb->get_results($wpdb->prepare($query, $_POST['test_id']));
}

wp_redirect(get_option('home') . '/wp-admin/admin.php?page=ecp_mct/pages/admin/test-list.php&message=test_deleted');