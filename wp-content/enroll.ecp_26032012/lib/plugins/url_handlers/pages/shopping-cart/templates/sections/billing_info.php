<?php 
	require_once IDG_CLASS_PATH."Utils/class.StringUtils.php";
	require_once IDG_CLASS_PATH."Utils/class.DateUtils.php";
	require_once IDG_CLASS_PATH."Utils/class.HTMLUtils.php";
	$date_utils = new DateUtils();
?>
<div class="order_form_wrapper">
	<span class="form_element radio_element">
	    <label>Who is paying?</label>
	    <span class="multiline_group">
	    	<?php $checked = FALSE; if($params["payer"] == "p_student") { $checked = TRUE; }; echo HTMLUtils::formRadio("payer", "p_student", $checked); ?> <label>YOU ( STUDENT )</label> 
	    	<?php $checked = FALSE; if($params["payer"] == "p_parent") { $checked = TRUE; }; echo HTMLUtils::formRadio("payer", "p_parent", $checked); ?> <label>Parent</label> 
	    	<?php $checked = FALSE; if($params["payer"] == "p_else") { $checked = TRUE; }; echo HTMLUtils::formRadio("payer", "p_else", $checked); ?> <label>Somebody else</label> 
	    </span>
	 </span>
	<span class="form_element">
	    <label for="cc_fname">Cardholder First Name:</label>
	    <input type="text" id="cc_fname" name="cc_fname" value="<?php echo $params["cc_fname"]; ?>" />
	</span>
	 <span class="form_element">
	   <label for="cc_lname">Last Name:</label>
	    <input type="text" id="cc_lname" name="cc_lname" value="<?php echo $params["cc_lname"]; ?>" />
	</span>
	<span class="form_element">
	    <label for="cc_type">Credit Card Type:</label>
	    	<?php 
				$sel_value = NULL; 
				if(isset($params["cc_type"]) && !empty($params["cc_type"]))
				{
					$sel_value = $params["cc_type"];
				}
				$options = array(
                  'visa'  => 'Visa',
                  'master_card' => 'Master Card',
                  'am_ex' => 'American Express'
                );
                echo HTMLUtils::formDropdown("cc_type", $options, $sel_value, "id=cc_type");
			?>
	    <img src="<?php echo get_bloginfo("template_directory"); ?>/lib/plugins/url_handlers/pages/shopping-cart/images/credit_card_logos.png" width="163" height="31"  />
	</span>
	<span class="form_element">
	    <label for="cc_number">Credit Card Number:</label>
	    <div id="cc_number_wrapper">
	    <?php if(!isset($params["cc_type"]) || $params["cc_type"] == "visa" || $params["cc_type"] == "master_card") : ?>
		    <input type="text" class="input_long cc_num" maxlength="4" />
		    <input type="text" class="input_long cc_num" maxlength="4" />
		    <input type="text" class="input_long cc_num" maxlength="4" />
		    <input type="text" class="input_long cc_num" maxlength="4" />
		    <input type="hidden" value="" name="cc_number" id="cc_number" />
	    <?php elseif($params["cc_type"] == "am_ex"): ?>
	    	<input type="text" class="input_long cc_num" maxlength="4"/>
			<input type="text" class="input_long cc_num" maxlength="6" />
			<input type="text" class="input_long cc_num" maxlength="5" />
			<input type="hidden" value="" name="cc_number" id="cc_number" />
	    <?php endif; ?>
	    </div>
	</span>
	<span class="form_element">
	     <label for="cvv2">CVV2 Code:</label>
	     <div id="cvv2_wrapper">
	     	<?php if(!isset($params["cc_type"]) || $params["cc_type"] == "visa" || $params["cc_type"] == "master_card") : ?>
	     		<input type="text" id="cvv2" class="input_long" maxlength="4" name="cvv2"/>
	     	<?php elseif($params["cc_type"] == "am_ex"): ?>
	     		<input type="text" id="cvv2" class="input_long" maxlength="3" name="cvv2"/>
	     	<?php endif; ?>
	     </div>
	</span>
	<span class="form_element">
	     <label for="cc_expiration">Expiration Date :</label>
	     <?php 
				$sel_value = NULL; 
				if(isset($params["cc_ex_month"]) && !empty($params["cc_ex_month"]))
				{
					$sel_value = $params["cc_ex_month"];
				}
				$options = $date_utils -> getNumMonths();
                echo HTMLUtils::formDropdown("cc_ex_month", $options, $sel_value, "id=cc_ex_month");
		 ?>
		 <?php 
				$sel_value = NULL; 
				if(isset($params["cc_ex_year"]) && !empty($params["cc_ex_year"]))
				{
					$sel_value = $params["cc_ex_year"];
				}
				$date_utils -> setYearRangeFromToday(2020,2011);
				$options = $date_utils -> getYears();
                echo HTMLUtils::formDropdown("cc_ex_year", $options, $sel_value, "id=cc_ex_year");
		 ?>
	</span>
	<span class="form_element">
	     <label><span class="left">Billing Address</span><span>Line 1:</span></label>
	     <input type="text" id="ba_1" name="ba_1" value="<?php echo $params["ba_1"]; ?>" />
	</span>
	<span class="form_element">
	     <label><span>Line 2:</span></label>
	     <input type="text" id="ba_2" name="ba_2" value="<?php echo $params["ba_2"]; ?>" />
	</span>
	<span class="form_element">
	     <label for="cc_city">City:</label>
	     <input type="text" id="cc_city" name="cc_city" value="<?php echo $params["cc_city"]; ?>" />
	</span>
	<span class="form_element">
	     <label for="cc_state">State (optional):</label>
	     <input type="text" id="cc_state" name="cc_state" value="<?php echo $params["cc_state"]; ?>" />
	</span>
	<span class="form_element">
	     <label for="cc_zip">ZIP/Post Code:</label>
	     <input type="text" id="cc_zip" name="cc_zip" value="<?php echo $params["cc_zip"]; ?>" />
	</span>
	<span class="form_element">
	     <label for="cc_country">Country:</label>
	     <input type="text" id="cc_country" name="cc_country" value="<?php echo $params["cc_country"]; ?>" />
	</span>
	<span class="form_element">
	     <label for="cc_phone">Phone:</label>
	     <?php $phone_arr = StringUtils::createUSPhone($params["cc_phone"]); ?>
	     <span>[</span><input type="text" class="input_short cc_ph" maxlength="3" value="<?php echo $phone_arr[0]; ?>" /><span>]</span>
	     <input type="text" class="input_short cc_ph" maxlength="3" value="<?php echo $phone_arr[1]; ?>" />
	     <input type="text" class="input_long cc_ph" maxlength="7" value="<?php echo $phone_arr[2]; ?>" />
	     <input type="hidden" value="<?php echo $params["cc_phone"]; ?>" name="cc_phone" id="cc_phone" />
	 </span> 
	 <span class="form_element">
	     <label for="cc_email">Email:</label>
	     <input type="text" id="cc_email" name="cc_email" value="<?php echo $params["cc_email"]; ?>" />
	</span>
</div>