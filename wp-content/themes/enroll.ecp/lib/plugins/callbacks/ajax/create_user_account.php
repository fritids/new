<?php
	$index = "wp-content";
	$path = dirname(__FILE__);
	$path = substr($path, 0, strpos($path,$index));
	
	require_once  $path."wp-load.php";
	require_once IDG_CLASS_PATH."ShoppingCart/class.ECPStudent.php";
	
	$student = new ECPStudent($_POST["Email"]);
	
	if($student -> createUser($_POST)) {
		echo json_encode(TRUE);
	} else {
		echo json_encode(FALSE);
	}