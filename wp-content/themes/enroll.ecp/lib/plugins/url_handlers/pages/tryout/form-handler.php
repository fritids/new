<?php
if(!defined('ABSPATH'))
	exit();
	
if(isset($_POST))
{
	global $errors;
	global $tryout_session;

	require_once IDG_CLASS_PATH."Utils/class.BFormValidator.php";
	require_once IDG_CLASS_PATH."ShoppingCart/class.ECPStudent.php";
	
	if(!isset($_POST["pb"])) { wp_redirect(wp_get_referer()); }
	// validation
	
	$validator = new BFormValidator($_POST);
	$validator -> validate('fname') -> vRequired('First name is required');
	$validator -> validate('lname') -> vRequired('Last name is required');
	$validator -> validate('primary_phone') -> vRequired('Primary phone is required') -> vNumber('Only numbers allowed');
	$validator -> validate('a_street') -> vRequired('Street is required');
	$validator -> validate('a_city') -> vRequired('City is required');
	$validator -> validate('a_state') -> vRequired('State is required');
	$validator -> validate('a_zip') -> vRequired('Zip is required');
	//$validator -> validate('parent_name') -> vRequired('Parent name is required');
	//$validator -> validate('parent_email') -> vRequired('Parent email is required') -> vEmail('Not a valid email');
	$validator -> validate('terms_conditions') -> vRequired('You have to agree to terms and conditions') -> vChecked("You must accept ECP terms and conditions", "on");
	
	
	if ($validator -> checkErrors() )
	{	
		$tryout_session -> addData($_POST);	
		$errors -> addData($validator -> displayErrors(TRUE));
		wp_redirect(wp_get_referer());
		exit;
	}
	
	$student = new ECPStudent($_POST["email"]);
	if(!($student -> userExist()))
	{
		switch_to_blog(2);
		$student -> createDemoUser($_POST);
		restore_current_blog();
		$errors -> emptyUserData();
		$tryout_session -> emptyUserData();
	}
	else
	{
		$tryout_session -> addData($_POST);
		$errors -> addData(array("user_exists" => "User with this email is already registered."));
		wp_redirect(wp_get_referer());
	}
}
?>