<?php
if(session_id() == "")
{
	session_start();
}

$post_data = $_POST;

if(isset($post_data))
{
	require_once IDG_CLASS_PATH . "class.IDGL_Users.php";
	require_once IDG_CLASS_PATH . "class.BFormValidator.php";
	require_once IDG_CLASS_PATH . "class.BMailer.php";
	
	$return_url = $post_data["return_url"];
	
	// validation on fields
	$validate = new BFormValidator($post_data["IDGL_elem"]);
	
	$validate -> validate("Username") -> vRequired('Empty Username');
	$validate -> validate("FirstName") -> vRequired('Empty Firstname');
	$validate -> validate("LastName") -> vRequired('Empty Lastname');
	$validate -> validate("Nickname") -> vRequired('Empty Nickname');
	$validate -> validate("Email") -> vRequired('Empty Email') -> vEmail("Not a valid email");
	
	switch($post_data["IDGL_elem"]["user_type"])
	{
		case "student":
			$validate -> validate("Gender") -> vRequired('Please choose gender');
		break;
	}
	
	if($validate -> checkErrors())
	{
		$_SESSION["v_error"] = serialize($validate -> displayErrors());
		header("Location: {$return_url}&res=v_error");
		return;
	}
	else
	{
		unset($_SESSION["v_error"]);
	}
	
	$random_password = wp_generate_password(12, false);
	$user = $post_data["IDGL_elem"]["FirstName"] . " " . $post_data["IDGL_elem"]["LastName"];
	$username = $post_data["IDGL_elem"]["Username"];
	$user_email = $post_data["IDGL_elem"]["Email"];
	$data = array(
					"user_pass" => $random_password, 
					"user_login" => $username, 
					"user_nicename" => $user, 
					"user_email" => $user_email, 
					"display_name" => $user, 
					"first_name" => $post_data["IDGL_elem"]["FirstName"], 
					"last_name" => $post_data["IDGL_elem"]["LastName"], 
					"user_registered" => date('Y-m-d H:i:s')
	);
	
	$id = wp_insert_user($data);
	if(!is_int($id))
	{
		header("Location: {$return_url}&res=error");
		return $id;
	}
	
	$err = IDGL_Users::IDGL_saveUserMeta($id);
	
	if(isset($post_data["teachers"]))
	{
		global $wpdb;
		$q = "INSERT IGNORE INTO wp_teacherstudent (student_ID,teacher_ID) VALUES ";
		foreach ($post_data["teachers"] as $teacher)
		{
			$q .= "($id, $teacher),";			
		}
		
		$q = rtrim($q, ",");
		
		if(!$wpdb -> query($q))
		{
			$err_ins = false;
		}
	}
	// send mail to user

	$msg_subject = getThemeMeta("ecpUserMailSubject", "", "_ecpMail");
	$msg_body = getThemeMeta("ecpUserMailBody", "", "_ecpMail");
	$mailer = new BMailer($username, $random_password, $user_email);
	$mailer -> add_content($msg_subject, $msg_body);
	if(gettype($mailer -> send_mail()) == "string")
	{
		header("Location: {$return_url}&res=mail-error");
		exit();
	}
	
	if(!isset($err_ins))
	{
		header("Location: {$return_url}&res=success");
		exit();
	}
	else
	{
		header("Location: {$return_url}&res=error");
		exit();
	}
}
?>