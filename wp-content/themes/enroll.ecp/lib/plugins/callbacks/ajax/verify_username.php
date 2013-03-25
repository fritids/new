<?php
	$index = "wp-content";
	$path = dirname(__FILE__);
	$path = substr($path, 0, strpos($path,$index));
	require_once  $path."wp-load.php";
	
	if(username_exists($_POST['email']) > 0) {
		echo json_encode(FALSE);
	} else {
		echo json_encode(TRUE);
	}