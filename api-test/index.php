<?php 

require_once dirname(__FILE__)."/class.Donation.php";


$options = array(
	"email" => "tefdos@gmail.com",
	"salutation" => "Mr",
	"first" => "Andrea",
	"last" => "Gorgeski",
	"address1" => "JNA 59",
	"city" => "",
	"state" => "",
	"zip" => ""
);
// amount is 5% of any given transaciton
$amount = 1;

$donation = new Donation($options);
$status = $donation -> donate($amount);

print_r($status);