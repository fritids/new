<?php
	$index = "wp-content";
	$path = dirname(__FILE__);
	$path = substr($path, 0, strpos($path,$index));
	require_once  $path."wp-load.php";
	
	global $current_user;
	
	$creds = array();
	$creds['user_login'] = $_POST['login'];
	$creds['user_password'] = $_POST['password'];
	$creds['remember'] = true;
	$current_user = wp_signon( $creds, false );
	if ( is_wp_error($current_user) ) {
		echo json_encode(FALSE);
	} else {
		echo json_encode(TRUE);
	}