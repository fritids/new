<?php
require_once IDG_CLASS_PATH."Utils/class.StringUtils.php";
require_once IDG_CLASS_PATH."ShoppingCart/anet_php_sdk/AuthorizeNet.php";
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPStudent.php";

global $current_user;

// live keys

//define("AUTHORIZENET_API_LOGIN_ID", "2jt82yBg6Rz");
//define("AUTHORIZENET_TRANSACTION_KEY", "9Y25KQhQ66xxEA2r");
//define("AUTHORIZENET_SANDBOX", false);

// sandbox keys

define("AUTHORIZENET_API_LOGIN_ID", "67HRz6n8");
define("AUTHORIZENET_TRANSACTION_KEY", "9Kb427hZR6Xe5Q96");
define("AUTHORIZENET_SANDBOX", true);


$student = new ECPStudent($current_user->user_email);

if($student -> userExist()) {

	$payment = new AuthorizeNetAIM();
	$total_cart_price = 0;
	$products = json_decode(stripslashes($_POST['purchase-description']));

	foreach($products as $i => $product)
	{
		$price = $product -> price;
		$total_cart_price += $product -> price;
		if(!is_null($product -> name))
		{
			$payment -> addLineItem(($i + 1), htmlentities(StringUtils::shortenString("{$product -> name}", 25), ENT_QUOTES), htmlentities(StringUtils::shortenString("{$product -> desc}", 25), ENT_QUOTES), 1, "{$price}", "Y");
		}
	}

	$payment -> setFields(
		array(
			'first_name' => $_POST['cc_fname'],
			'last_name' => $_POST['cc_lname'],
			'address' => $_POST['ba_1'],
			'city' => $_POST['cc_city'],
			'state' => $_POST['cc_state'],
			'zip' => $_POST['cc_zip'],
			'phone' => $_POST['cc_phone'],
			'amount' => $total_cart_price,
			'card_num'  => $_POST['cc_number'],
			'card_code' => $_POST['cvv2'],
			'exp_date' => $_POST['cc_ex_month']."/".$_POST['cc_ex_year'],
			'email_customer' => TRUE,
			'email' => $_POST['cc_email'],
		)
	);

	$response = $payment -> authorizeAndCapture();

	if($response -> approved)
	{

		$terms = get_terms("ecp-products");
		$categories = array();

		foreach ($terms as $term)
		{
			if(strpos($term -> slug, "essay") !== false)
			{
				$categories["essay"] = array("subject" => "essay-subject", "body" => "essay-body");
			}
			else if(strpos($term -> slug, "test-prep-tutoring") !== false)
			{
				$categories["online-test-prep"] = array("subject" => "online-test-prep-subject", "body" => "online-test-prep-body");
			}
			else if(strpos($term -> slug, "admissions-counseling") !== false)
			{
				$categories["admissions-counseling"] = array("subject" => "online-coaching-subject", "body" => "online-coaching-body");	
			}
			else if(strpos($term -> slug, "satact-edge-online-course") !== false)
			{
				$categories["satact-edge-online-course"] = array("subject" => "online-sat-course-subject", "body" => "online-sat-course-body");	
			}
		}

		$h = array();
		foreach($products as $product)
		{
			if(!in_array($product -> taxonomy_slug, $h))
			{
				$slug = ProductFilter::slugFilter($product -> taxonomy_slug);
				$student -> sendProductMail($categories[$slug]["subject"], $categories[$slug]["body"]);
				array_push($h, $product -> taxonomy_slug);
			}
		}

		// Save users order
		$student->saveUserProducts($products);
		
		// Send email to the admin
		$student->sendAdminMail($products);

		$_SESSION['transaction_id'] = $response -> transaction_id;

//		$total=(int) get_option( "donation_amnt", 0 );	
//		$total+=0.05*$total_cart_price;
//		update_option( "donation_amnt", $total );
		wp_redirect(get_bloginfo("url")."/thankyou");
	}
	else
	{
		$_SESSION['auth-error'] = $response -> response_reason_text;
		$_SESSION['user-selection'] = $_POST;
		wp_redirect(get_bloginfo("url")."/cart");
	}
} else {
	$_SESSION['auth-error'] = "Problem loadin user's account";
	$_SESSION['user-selection'] = $_POST;
		wp_redirect(get_bloginfo("url")."/cart");
}