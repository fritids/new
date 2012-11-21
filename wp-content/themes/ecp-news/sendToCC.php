<?php
$Email= $_POST['email'];
$Name= $_POST['name'];
require_once 'ConstantContact.php';
$ConstantContact = new ConstantContactDD();
$ConstantContact->setUsername('route999'); /* set the constant contact username */
$ConstantContact->setPassword('02081903'); /* set the constant contact password */
$ConstantContact->setCategory('ecp_subscription'); /* set the constant contact interest category first_name=joe&last_name=smith */
$hasSent=$ConstantContact->add($Email,array("first_name"=>$Name));
if($hasSent){
	echo "Thank you for subscribing";
}else{
	echo "Sorry, please try again later";
}