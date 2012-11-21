<div class="wrap">
<?php 
global $wpdb;

$q="
SELECT ID,user_login,display_name FROM $wpdb->users 
INNER JOIN $wpdb->usermeta ON $wpdb->users.ID=$wpdb->usermeta.user_id
WHERE meta_key='_IDGL_elem_user_type' AND meta_value='teacher'";

$dg_params=array(
				"edit"=>get_bloginfo("url")."/wp-admin/user-edit.php?user_id={ID}",
				"delete"=>""
				);

	$dg=new IDGL_DataGrid($dg_params);
	$dg->setQuery($q);
	echo $dg->render();

?>
</div>