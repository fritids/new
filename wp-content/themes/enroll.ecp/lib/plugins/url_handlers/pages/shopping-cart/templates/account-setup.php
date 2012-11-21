<?php
	
	//require_once IDG_CLASS_PATH."Utils/class.FormValidator.php";
	require_once IDG_CLASS_PATH."Utils/class.BFormValidator.php";
    require_once IDG_CLASS_PATH."Utils/class.FormPostHandler.php";
    require_once IDG_CLASS_PATH."ShoppingCart/class.ECPCoupon.php";
	  
    global $ecp_cart; // shopmanager object
	global $session_utils; // user data in session 
	global $current_user;

    $handler = new FormPostHandler();
	$val_errors = NULL;
	$step = ($session_utils -> getUserData("referrer") == $handler -> getReferrer()) ? TRUE : FALSE;
	
	if($handler -> isPostback("pb") === FALSE)
    {	
	  	$pids = $handler -> getval("products_ids");
	  	$quantities = NULL;
	  	
	  	if($handler -> getval("ecp_essays_quantity") !== FALSE)
	  	{
	  		$quantities = json_decode(stripslashes($handler -> getval("ecp_essays_quantity")));
	  		// should be refactored - all products data should go in ecp cart. Used in ECPShopManager, DeleteProduct
	  		$session_utils -> addData(array("essay-order" => $quantities));
	  	}
     	if($pids !== FALSE)
      	{
			if(!($ecp_cart -> isEmpty())) { $ecp_cart -> emptyCart(); }

		 	$coupon = $handler -> getval("course_coupon");
	 	  	//fb($coupon);
		 	// check if coupon is entered
		 	if($coupon)
		 	{
			 	//$ecp_coupon = new ECPCoupon($coupon);
			 	//$ecp_coupon -> checkDiscount($pids);
			 	//$ecp_cart -> addCoupon($ecp_coupon);
			 	$ecp_cart -> addProducts($pids, $quantities, $coupon);
			 	//$ecp_cart -> addCoupon($coupon);
		 	}
		 	else
		 	{
		 		$ecp_cart -> addProducts($pids, $quantities);
		 	}	
      	}
	  	
	  	// check if user is already logged in and makes an order. 
  		// add product to shopping cart and redirect to billing process, because we have all data needed
    	if($current_user -> ID != 0)
		{
			$session_utils -> addData($_POST);
			$session_utils -> addData($current_user -> data);
			$handler -> redirect("/cart/billing");
			ob_end_flush();
			exit;		
		}
	  	
		// if cart is empty redirect to first step
		if($ecp_cart -> isEmpty())
		{
			$handler -> redirect("/cart/");
			ob_end_flush();
		}
		
	}	
	elseif($step && $handler -> isPostback("pb") !== FALSE)
	{
		try 
		{
			$handler -> removePostElements(array("pb"));
			$session_utils -> addData($_POST);
			
			$validator = new BFormValidator($_POST);
			$validator -> validate('FirstName') -> vRequired('First name is required');
			$validator -> validate('LastName') -> vRequired('Last name is required');
			$validator -> validate('PrimaryPhone') -> vRequired('Primary phone is required') -> vNumber('Only numbers allowed');
			$validator -> validate('a_street') -> vRequired('Street is required');
			$validator -> validate('a_city') -> vRequired('City is required');
			//$validator -> validate('a_state') -> vRequired('State is required');
			$validator -> validate('a_zip') -> vRequired('Zip is required');
			$validator -> validate('ParentName') -> vRequired('Parent name is required');
			$validator -> validate('ParentEmail') -> vRequired('Parent email is required') -> vEmail('Not a valid email');
			$validator -> validate('terms_conditions') -> vRequired('You have to agree to terms and conditions') -> vChecked("You must accept ECP terms and conditions", "on");
		
			if ($validator -> checkErrors() ) 
			{
				$val_errors = $validator -> displayErrors(TRUE);   
			} 
			else 
			{
				$handler -> redirect("/cart/billing");
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
	}
  	$session_utils -> addData(array("referrer" => $handler -> getReferrer()));
	if(!$session_utils -> isEmpty())
	{
		$session_utils -> cleanUp(array("cc_number", "cc_type", "cvv2"));
		$user_data = $session_utils -> getAllUserData();
	}
	else
	{
		$user_data = NULL;
	}
?>
<form action="<?php $_SERVER["PHP_SELF"]; ?>" class="order_form" method="POST" id="ecp_cart_form">
	<div class="whitebox">
		<h4>Account Setup</h4>
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
			<?php echo Templator::getCartTemplate("billing_form",  $user_data, "sections/"); ?>
			<?php echo Templator::getCartTemplate("cart", $ecp_cart, "sections/"); ?>
			<input type="hidden" name="pb" value="<?php echo time(); ?>" />
		</div>
		<div class="step_navigation">
			<a href="#" class="form_navigation step_back" id="ecp_previous_step">Back</a> 
			<a href="#" class="form_navigation" id="ecp_next_step">Next</a>
		</div>
	</div>
</form>