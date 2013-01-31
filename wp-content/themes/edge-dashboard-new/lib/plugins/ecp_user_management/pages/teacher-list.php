<div class="wrap">
<?php
global $wpdb;
$sort = $_GET["sort"];

$q = "SELECT ID,user_login,display_name 
	  FROM $wpdb->users 
	  INNER JOIN $wpdb->usermeta ON $wpdb->users.ID=$wpdb->usermeta.user_id
	  WHERE meta_key='_IDGL_elem_user_type' AND meta_value='teacher'";

$return_url = $_SERVER[REQUEST_URI];
$delete_page = parse_url($return_url, PHP_URL_PATH)."?page=delete-user&id={ID}&rurl={$return_url}";

$dg_params = array("edit" => get_bloginfo("url") . "/wp-admin/user-edit.php?user_id={ID}", "delete" => "{$delete_page}");
$dg = new IDGL_DataGrid($dg_params);

$dg -> addSort($sort);
$dg -> addQueryString();
$dg -> setQuery($q);
echo $dg -> render();

?>
</div>