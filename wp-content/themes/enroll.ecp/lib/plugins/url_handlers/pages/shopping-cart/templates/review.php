<?php
	require_once IDG_CLASS_PATH."Utils/class.FormPostHandler.php";
	global $ecp_cart;
	global $session_utils;
	$handler = new FormPostHandler();
	
	
	$step = ($session_utils -> getUserData("referrer") == $handler -> getReferrer()) ? TRUE : FALSE;
	$session_utils -> addData(array("referrer" => $handler -> getReferrer()));
	
	  
	if($ecp_cart -> isEmpty())
	{
		wp_redirect(get_bloginfo("url")."/cart/");
	}
	
	if(!$session_utils -> isEmpty())
	{
		$user_data = $session_utils -> getAllUserData();
	}
	else
	{
		$user_data = NULL;
	}	
?>
<form action="<?php bloginfo("url"); ?>/cart/checkout" method="POST" id="ecp_cart_form">
	<div class="whitebox">
		<h4>Checkout</h4>
		<div class="ecp_order_wrapper">
			<?php echo Templator::getCartTemplate("review", array("user_data" => $user_data, "cart_data" => $ecp_cart -> getAllProducts()), "sections/"); ?>
		</div>
		<div class="step_navigation">
			<a href="#" class="form_navigation step_back" id="ecp_previous_step">Back</a> 
			<input type="submit" id="ecp_next_step" class="form_navigation process_step" value="ORDER" />
		</div>
	</div>
</form>