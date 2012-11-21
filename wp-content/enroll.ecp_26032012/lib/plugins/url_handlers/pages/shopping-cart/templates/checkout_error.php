<?php
require_once IDG_CLASS_PATH."Utils/class.FormPostHandler.php";
global $ecp_cart;
global $session_utils;
$handler = new FormPostHandler();
$step = ($session_utils -> getUserData("referrer") == $handler -> getReferrer()) ? TRUE : FALSE;

if($ecp_cart -> isEmpty())
{
	$handler -> redirect(get_bloginfo("url")."/cart/");
}

$response = $session_utils -> getUserData("response");

?>
<div class="whitebox">
	<h4>Checkout Error</h4>
	<div class="ecp_order_wrapper">
		<?php echo Templator::getCartTemplate("checkout_error", array("response" => $response), "sections/"); ?>
	</div>
</div>
<?php 
	$session_utils -> cleanUp("response");
?>