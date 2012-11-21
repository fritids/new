<?php
require_once IDG_CLASS_PATH."Utils/class.FormPostHandler.php";
require_once IDG_CLASS_PATH."Utils/class.StringUtils.php";
require_once IDG_CLASS_PATH."ShoppingCart/anet_php_sdk/AuthorizeNet.php";
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPStudent.php";

// live keys

define("AUTHORIZENET_API_LOGIN_ID", "2jt82yBg6Rz");
define("AUTHORIZENET_TRANSACTION_KEY", "2wBT6Pa2QM7d89LZ");
define("AUTHORIZENET_SANDBOX", false);

// sandbox keys

/*define("AUTHORIZENET_API_LOGIN_ID", "67HRz6n8");
define("AUTHORIZENET_TRANSACTION_KEY", "9Kb427hZR6Xe5Q96");
define("AUTHORIZENET_SANDBOX", true);*/

global $ecp_cart;
global $session_utils;

if($ecp_cart -> isEmpty())
{
	wp_redirect(get_bloginfo("url")."/cart/");
}

$handler = new FormPostHandler();

$payment = new AuthorizeNetAIM();
$total_cart_price = 0;
foreach($ecp_cart -> getAllProducts() as $i => $product)
{
	//$total_cart_price += $product -> price;
	/*if($product -> discounted_price)
	{
		$total_cart_price += $product -> discounted_price;
		if(!is_null($product -> name))
		{
			$payment -> addLineItem(($i + 1), htmlentities(StringUtils::shortenString("{$product -> name}", 25), ENT_QUOTES), htmlentities(StringUtils::shortenString("{$product -> desc}", 25), ENT_QUOTES), $product -> quantity, "{$product -> discounted_price}", "Y");
		}
	}
	else 
	{*/
		$price = $product -> price;
		if($product -> discounted_price)
		{
			$price = $product -> discounted_price;
		}
		$total_cart_price += $product -> full_price;
		if(!is_null($product -> name))
		{
			$payment -> addLineItem(($i + 1), htmlentities(StringUtils::shortenString("{$product -> name}", 25), ENT_QUOTES), htmlentities(StringUtils::shortenString("{$product -> desc}", 25), ENT_QUOTES), $product -> quantity, "{$price}", "Y");
		}
	//}
}

$payment -> setFields(
    array(
    	'first_name' => $session_utils -> getUserData("cc_fname"),
   		'last_name' => $session_utils -> getUserData("cc_lname"),
	    'address' => $session_utils -> getUserData("ba_1"),
	    'city' => $session_utils -> getUserData("cc_city"),
	    'state' => $session_utils -> getUserData("cc_state"),
	    'country' => $session_utils -> getUserData("cc_country"),
	    'zip' => $session_utils -> getUserData("cc_zip"),
    	'phone' => $session_utils -> getUserData("cc_phone"),
        'amount' => $total_cart_price,
        'card_num'  => $session_utils -> getUserData("cc_number"),
        'card_code' => $session_utils -> getUserData("cvv2"),
        'exp_date' => $session_utils -> getUserData("cc_ex_month")."/".$session_utils -> getUserData("cc_ex_year"),
        'email_customer' => TRUE,
        'email' => $session_utils -> getUserData("cc_email")
    )
);

$response = $payment -> authorizeAndCapture();

if($response -> approved)
{
	$transaction_id = $response -> transaction_id;
	$session_utils -> addData(array("referrer" => $handler -> getReferrer(), "transaction_id" => $transaction_id));
	
	$total=(int) get_option( "donation_amnt", 0 );	
	$total+=0.05*$total_cart_price;
	update_option( "donation_amnt", $total );
	
	$handler -> redirect("/cart/thankyou");
}
else
{
	$session_utils -> addData(array("referrer" => $handler -> getReferrer(), "response" => $response -> response_reason_text));
	$handler -> redirect("/cart/checkout_error");
}

?>