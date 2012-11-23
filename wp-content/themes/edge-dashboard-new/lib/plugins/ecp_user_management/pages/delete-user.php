<?php
$user_id = $_GET["id"];
if(!is_null($user_id))
{
	$return_url = $_GET["rurl"];
	if(wpmu_delete_user($user_id))
	{
		wp_redirect($return_url);
	}
}
?>
