<?php
	//require_once IDG_CLASS_PATH."Utils/class.FormValidator.php";
	require_once IDG_CLASS_PATH."Utils/class.BFormValidator.php";
	require_once IDG_CLASS_PATH."Utils/class.FormPostHandler.php";
	  
	global $ecp_cart; // shopmanager object
	global $session_utils; // user data in session
	
	$handler = new FormPostHandler();
	
	$val_errors = NULL; 
	$step = ($session_utils -> getUserData("referrer") == $handler -> getReferrer()) ? TRUE : FALSE;
	
	$user_data = $session_utils -> getAllUserData();
	
	if($handler -> isPostback("pb") === FALSE)
    {
		// if cart is empty redirect to first step
		if($ecp_cart -> isEmpty())
		{
			$handler -> redirect("/cart/");
			ob_end_flush();
		}
	}
	elseif($step && ($handler -> isPostback("pb") !== FALSE) && !empty($_POST))
	{
		try 
		{
			$handler -> removePostElements(array("pb"));
			$session_utils -> addData($_POST);
			
			$validator = new BFormValidator($_POST);
			
			$validator -> validate('cc_fname') -> vRequired('First name is required');
			$validator -> validate('cc_lname') -> vRequired('Last name is required');
			$validator -> validate('cc_number') -> vRequired('Credit card is required') -> vNumber('Only numbers allowed') -> vCreditCard('Not a valid credit card');
			$validator -> validate('cvv2') -> vRequired('CVV2 code is required') -> vNumber('Only numbers allowed');
			$validator -> validate('ba_1') -> vRequired('Billing address is required');
			//$validator -> validate('cc_city') -> vRequired('Billing city is required');
			$validator -> validate('cc_country') -> vRequired('Billing country is required');
			$validator -> validate('cc_email') -> vRequired('Email is required') -> vEmail('Not a valid email');
	
		  	
			if ($validator -> checkErrors() ) 
			{
				$val_errors = $validator -> displayErrors(TRUE);   
			} 
			else 
			{
				$handler -> redirect("/cart/checkout");
				ob_end_flush();
				exit;
			}
		}
		catch(Exception $ex)
		{
			
		}				
	}
	elseif(!$step)
	{
		$session_utils -> addData(array("referrer" => $handler -> getReferrer()));
		if($ecp_cart -> isEmpty())
		{
			$handler -> redirect("/cart/");
			ob_end_flush();
		}
	}

	$session_utils -> cleanUp(array("cc_number", "cc_type", "cvv2"));
	$user_data = $session_utils -> getAllUserData();
?>

<form action="" method="POST" id="ecp_cart_form">
	<div class="whitebox">
		<h4>Billing Information</h4>
		<div class="ecp_order_wrapper">
			<?php if(is_array($val_errors)): ?>
			<div class="req-notes">
				<h6>Following errors occurred in your form:</h6>
				<ul>
					<?php foreach($val_errors as $error): ?>
						<li><?php echo $error; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php echo Templator::getCartTemplate("billing_info", $user_data, "sections/"); ?>
			<?php echo Templator::getCartTemplate("cart", $ecp_cart, "sections/"); ?>
			<input type="hidden" name="pb" value="<?php echo time(); ?>" />
		</div>
		<div class="step_navigation">
	        <a href="#" class="form_navigation step_back" id="ecp_previous_step">Back</a> 
	        <input type="submit" id="ecp_next_step" class="form_navigation process_step" value="PLACE ORDER" />
			<!--<a href="#" class="form_navigation process_step" id="ecp_next_step">PLACE ORDER</a>-->
        </div>
	</div>
</form>