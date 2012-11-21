<?php
	require_once IDG_CLASS_PATH."Utils/class.DateUtils.php";
	require_once IDG_CLASS_PATH."Utils/class.StringUtils.php";
	$date_utils = new DateUtils(); 
	//$params=$_POST;
?>

<div class="order_form_wrapper"> 
          <span class="form_element">
              <label for="fname">First Name:</label>
              <input type="text" id="fname" name="fname" class="required" value="<?php echo $params["fname"]; ?>"/>
          </span>
          <span class="form_element">
              <label for="lname">Last Name:</label>
              <input type="text" id="lname" name="lname" class="required" value="<?php echo $params["lname"]; ?>"/>
          </span>
          <span class="form_element">
              <label for="gender">Gender:</label>
              <select id="gender" name="gender">
              	<?php 
              		$sel_value = $params["gender"];
              	?>
                 <option value="m" <?php if($sel_value == "m") echo "selected='selected'"; ?>>Male</option>
                 <option value="f" <?php if($sel_value == "f") echo "selected='selected'"; ?>>Female</option>
              </select>
          </span>
          <span class="form_element">
                <label for="dob">Date of birth:</label>
                <select id="dob_d" name="dob_d">
                   <?php 
                   		$sel_value = NULL;
 		
                  		if(isset($params["dob_d"]) && !empty($params["dob_d"]))
                   		{
                   			$sel_value = $params["dob_d"];
                 		}
                 		
                   ?>
                   <?php echo $date_utils -> printDateOptions("days", $sel_value); ?>
                </select>
                <select id="dob_m" name="dob_m">
                  <?php 
                   		$sel_value = NULL; 
                  		if(isset($params["dob_m"]) && !empty($params["dob_m"]))
                   		{
                   			$sel_value = $params["dob_m"];
                 		}
                   ?>
                  <?php echo $date_utils -> printDateOptions("months", $sel_value); ?>
                </select>
                <select id="dob_y" name="dob_y">
                  <?php 
                   		$sel_value = NULL; 
                  		if(isset($params["dob_y"]) && !empty($params["dob_y"]))
                   		{
                   			$sel_value = $params["dob_y"];
                 		}
                   ?>
                   <?php echo $date_utils -> printDateOptions("years", $sel_value); ?>
                </select>
            </span>
            <span class="form_element">
                <label for="email">E-mail address:</label>
                <input type="text" id="email" name="email" class="required"  value="<?php echo $params["email"]; ?>"/>
            </span>
            <span class="form_element">
                <label for="phone">Phone:</label>
                <?php 
                	  $primary_arr = StringUtils::createUSPhone($params["primary_phone"]);
                ?>
                <span>[</span><input type="text" id="phone" class="input_short phone" maxlength="3" value="<?php echo $primary_arr[0]; ?>"/><span>]</span>
                <input type="text" class="input_short phone" maxlength="3" value="<?php echo $primary_arr[1]; ?>"/>
                <input type="text" class="input_long phone" maxlength="7" value="<?php echo $primary_arr[2]; ?>"/>
                <input type="hidden" value="<?php echo $params["primary_phone"]; ?>" name="primary_phone" id="primary_phone" />
              <span>Primary</span>
            </span>
            <span class="form_element">
                <label for="sphone"></label>
                <?php 
                	  $secondary_arr = StringUtils::createUSPhone($params["secondary_phone"]);
                ?>
                <span>[</span><input type="text" id="sphone" class="input_short sphone" maxlength="3" value="<?php echo $secondary_arr[0]; ?>"/><span>]</span>
                <input type="text" class="input_short sphone" maxlength="3" value="<?php echo $secondary_arr[1]; ?>"/>
                <input type="text" class="input_long sphone" maxlength="7" value="<?php echo $secondary_arr[2]; ?>"/>
                <input type="hidden" value="<?php echo $params["secondary_phone"]; ?>" name="secondary_phone" id="secondary_phone" />
            </span>
            <span class="form_element">
                  <label for="school">School:</label>
                  <input type="text" id="school" name="school"  value="<?php echo $params["school"]; ?>"/>
            </span>
            <span class="form_element">
                  <label for="graduation">Graduation year:</label>
                   <select id="graduation" name="graduation">
                     <?php
                     		$sel_value = $params["graduation"];
                     		$date_utils -> setYearRangeFromToday(2020, 2011); 
                     		echo $date_utils -> printDateOptions("years", $sel_value); 
                     ?>
                  </select>
            </span>
            <span class="form_element">
                  <label><span class="left">Address</span><span>Street:</span></label>
                  <input type="text" id="a_street" name="a_street" value="<?php echo $params["a_street"]; ?>"/>
            </span>
            <span class="form_element">
                  <label><span>City:</span></label>
                  <input type="text" id="a_city" name="a_city" value="<?php echo $params["a_city"]; ?>"/>
            </span>
             <span class="form_element">
                  <label><span>State:</span></label>
                  <input type="text" id="a_state" name="a_state" value="<?php echo $params["a_state"]; ?>"/>
             </span>
             <span class="form_element">
                  <label><span>Zip:</span></label>
                  <input type="text" id="a_zip" name="a_zip" value="<?php echo $params["a_zip"]; ?>"/>
             </span>
             <span class="form_element">
                  <label for="parent_name">Parent Name:</label>
                  <input type="text" id="parent_name" name="parent_name" value="<?php echo $params["parent_name"]; ?>"/>
             </span>
             <span class="form_element">
                  <label for="parent_email">Parent Email:</label>
                  <input type="text" id="parent_email" name="parent_email" value="<?php echo $params["parent_email"]; ?>"/>
             </span>
             <span class="form_element">
                  <label for="test_date">Test Date:</label>
                   <select id="test_date" name="test_date">
                     <?php
                       $sel_value = NULL;
                       if(isset($params["test_date"]) && !empty($params["test_date"]))
                       {
                       	$sel_value = $params["test_date"];
                       }
                       echo $date_utils -> setMonthYearDropdown(2011, $sel_value);
                     ?>
                      <option value='January_2012'>January 2012</option>
                      <option value='March_2012'>March 2012</option>
                      <option value='May_2012'>May 2012</option>
                      <option value='June_2012'>June 2012</option>
                  </select>
             </span>
             <span class="form_element form_checkbox">
             		<?php  
             			 $checked = "";
             			 if(isset($params["terms_conditions"]) && $params["terms_conditions"] == "on")
                       	 {
                       		$checked = "checked='checked'";
                       	 }
                  ?>
                  <input type="checkbox" id="terms_conditions" name="terms_conditions" <?php echo $checked;?>/>
                  <label>I agree to the Edge in College Prep's <a href="http://edgeincollegeprep.com/terms-and-conditions/" target="_blank">Terms and Conditions</a></label>                                 
             </span>
             <span class="form_element form_checkbox">
             		<?php  
             			 $checked = "";
             			 if(isset($params["promos"]) && $params["promos"] == "on")
                       {
                       	$checked = "checked='checked'";
                       }
                  ?>
                  <input type="checkbox" name="promos" <?php echo $checked;?> />
                  <label>I want to receive Promos and Newsletters from Edge in College Prep</label>                                 
             </span>
             <span class="form_element form_checkbox">
             		<?php  
             			 $checked = "";
             			 if(isset($params["info_partners"]) && $params["info_partners"] == "on")
                       {
                       	$checked = "checked='checked'";
                       }
                  ?>
                  <input type="checkbox" name="info_partners" <?php echo $checked;?> />
                  <label>I want to receive information and promotions from ECP Partners which will help me in the college admission process</label>                           </span>
             <span class="form_element optional">
                  <label for="us">Friend to Referral?</label>
                   <select id="us" name="hear_about_us">
                     <option value="website">Website</option>
                     <option value="friend">friend</option>
                  </select>
             </span>
        </div>
			<input type="hidden" name="pb" value="<?php echo time(); ?>" />
		</div>
		<div class="step_navigation">
			<!--<a href="#" class="form_navigation step_back" id="ecp_previous_step">Back</a> 
			<a href="#" class="form_navigation" id="ecp_next_step">Next</a>-->
			<input type="submit" id="ecp_next_step" class="form_navigation process_step" value="Submit" />
		</div>

	</div>
</form>