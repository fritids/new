<?php
$Email= $_POST['email'];
$Name= $_POST['name'];
require_once 'ConstantContact.php';

$ccContactOBJ = new CC_Contact();

/**
 * If we have post fields means that the form have been submitted.
 * Therefore we start submiting inserted data to ConstantContact Server. 
 */
	
$postFields = array();
$postFields["email_address"] = $_POST['email'];;
$postFields["first_name"] = $_POST['name'];
// The Code is looking for a State Code For Example TX instead of Texas
$postFields["lists"] = array('ContactLists'=>'http://api.constantcontact.com/ws/customers/jessbrondo/lists/21');
$postFields["custom_fields"] = array();
//echo $ccContactOBJ->apiPath;
$contactXML = $ccContactOBJ->createContactXML(null,$postFields);

if (!$ccContactOBJ->addSubscriber($contactXML)) {
	$error = true;
} else {
	$error = false;
	$_POST = array();
}


if($error){
	echo "Sorry, please try again later";
}else{
	echo "Thank you for subscribing";
}