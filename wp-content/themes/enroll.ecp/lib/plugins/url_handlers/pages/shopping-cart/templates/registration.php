<?php
	global $wpdb;
	global $current_user;
	
	$user_selecction;
	
	if(isset($_SESSION['auth-error'])) {
		$user_selecction = $_SESSION['user-selection'];
	?>
	<div class="error-div">
		There has been an error with your authorization, the reason being: <?php echo  $_SESSION['auth-error'] ?>
	</div>
	<?php
		unset($_SESSION['auth-error']);
		unset($_SESSION['user-selection']);
	}
	
	require_once IDG_CLASS_PATH."Utils/class.DateUtils.php";
	
	$states_list = array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA",
		"ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC",
		"SD","TN","TX","UT","VT","VA","WA","WV","WI","WY");
	
	$cards_list = array('visa'=>'Visa', 'master_card'=>'Master Card', 'am_ex'=>'American Express');
	
	//dob dates
	$date_utils_grad = new DateUtils();
	$date_utils_grad->setYearRangeFromToday(date("Y")+7, date("Y")-3);
	
	// cc dates
	$date_utils = new DateUtils();
	$cc_months = $date_utils -> getNumMonths();
	$date_utils -> setYearRangeFromToday(2020, date("Y"));
	$cc_years = $date_utils -> getYears();

	$post_sql = "SELECT ID, post_content, post_title, post_name
				FROM {$wpdb -> posts}
				LEFT JOIN {$wpdb -> term_relationships} ON({$wpdb -> posts}.ID = {$wpdb -> term_relationships}.object_id)
				LEFT JOIN {$wpdb -> term_taxonomy} ON({$wpdb -> term_relationships}.term_taxonomy_id = {$wpdb -> term_taxonomy}.term_taxonomy_id)
				LEFT JOIN {$wpdb -> terms} ON({$wpdb -> term_taxonomy}.term_id = {$wpdb -> terms}.term_id)
				WHERE {$wpdb -> posts}.post_type = 'ECPProduct' 
				AND {$wpdb -> posts}.post_status = 'publish'
				AND {$wpdb -> term_taxonomy}.taxonomy = 'ecp-products'
				AND {$wpdb -> terms}.slug = '%s' 
				ORDER BY {$wpdb -> posts}.menu_order ASC;";

	// Get all categories
	$categories = array();
	foreach(get_terms("ecp-products", "hide_empty=0&orderby=none") as $single_category)
	{
		$categories[$single_category -> term_id] = $single_category;
	}

	// Display categories in "Product Order" menu order
	foreach(wp_get_nav_menu_items("Product Order") as $menu_item):
	
		$category = $categories[$menu_item -> object_id];
		$s_sql = sprintf($post_sql, $category -> slug);
		$products = $wpdb -> get_results($wpdb -> prepare($s_sql));
?>

<div class="product-category" id="<?php echo $category -> slug; ?>" <?php if($category -> slug == "essay-grading"): ?>style="display: none;"<?php endif; ?>>
	<?php if($category -> slug != "test-prep-tutoring-junior" && $category -> slug != "test-prep-tutoring-senior" && $category -> slug != "essay-grading"): ?>
	<h3><?php echo $category -> name ?></h3>
	<?php elseif($category -> slug == "test-prep-tutoring-junior"): ?>
	<h3>Test Prep Tutoring</h3>
	<?php endif; ?>
	
	<?php if($category -> slug == "essay-grading"): ?>
	<div class="upgrade-course">Do you want to add on <span>Essay Grading</span> to the course?</div>
	<div class="upgrade-course-sub">Get up to 15 SAT or ACT essays graded by an Edge test prep expert</div>
	<?php endif; ?>

	<?php if($category -> slug == "test-prep-tutoring-junior"): ?>
	<div class="location-container">
		<label>Select your location: </label>
		<select id="location-select" style="width: 270px;">
			<option value="new_york">New York</option>
			<option value="london">London</option>
			<option value="buenos_aires">Buenos Aires / Rio</option>
			<option value="south_florida">South Florida</option>
			<option value="skype">Skype / Online Tutoring</option>
		</select>
	</div>
	<div class="location-container">
		<label>Level of tutors: </label>
		<select id="tutor-level-select">
			<option value="junior">Junior</option>
			<option value="senior">Senior</option>
		</select>
	</div>
	<?php endif; ?>

	<ul class="clearfix" <?php if($category -> slug == "test-prep-tutoring-senior"): ?> style="display: none;" <?php endif; ?>>
	<?php foreach($products as $post_data): ?>
		<li>
			<div class="product-item <?php echo $category->slug; ?> <?php if(get_post_meta($post_data->ID, 'price_type', true) == "free") echo "selected"; ?>"
				 post-id="<?php echo $post_data->ID; ?>"
				 name="<?php echo $post_data->post_title; ?>"
				 desc="<?php echo htmlspecialchars($post_data->post_content); ?>"
				 taxonomy_slug="<?php echo $category -> slug; ?>"
				 price-type="<?php echo get_post_meta($post_data->ID, 'price_type', true); ?>"
				 price="<?php if(get_post_meta($post_data->ID, 'universal_price', true)) echo get_post_meta($post_data->ID, 'universal_price', true); else echo "0"; ?>">
				
				<div class="title"><?php echo $post_data->post_title ?></div>
				<div class="description">
					<a class="show-description" href='#product-info-<?php echo $post_data->ID ?>'>learn more</a>
				</div>
				<div class="price">
					<?php if( get_post_meta($post_data->ID, 'price_type', true) == "free" ) {
					echo "FREE!";
					} elseif( get_post_meta($post_data->ID, 'price_type', true) == "location" ) {
						$prices = unserialize(get_post_meta($post_data->ID, 'location_prices', true));
						if(!empty($prices)) {
							foreach ($prices as $key => $value) {
								echo "<span class='location_prices $key'>$$value</span>";
							}
						}
					} else {
						echo "$".get_post_meta($post_data->ID, 'universal_price', true);
					} ?>
				</div>
			</div>
			
			<div style="display:none">
				<div class="product-info-popup" id="product-info-<?php echo $post_data->ID ?>">
					<div class="category-name"><?php echo $category -> name ?></div>
					<div class="product-name"><?php echo $post_data->post_title ?></div>
					<p><?php echo $post_data->post_content; ?></p>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endforeach; ?>

<div class="total-container">
	Your total purchase: $<span id="registration-total">0</span>
	<input type="hidden" id="registration-total-hdn" value="0" />
</div>
<div class="register-now-container">
	<a href="#billing-form-popup" id="register-now">REGISTER NOW!</a>
</div>

<div style="display:none">
	<div id="billing-form-popup">
		<?php /* ACCOUNT INFO POPUP */ ?>
		<div id="account-info-form" class="billing-form-popup" <?php if($current_user->ID != 0): ?>style="display: none;"<?php endif; ?>>
			<form method="post" id="ecp_account_form">
				<div class="title"><span>STEP 1 -</span> STUDENT INFO</div>
				
				<div id="account-error" class="validation-error login">
					There was an error creating your account, please contact us on : <a href="mailto:online@edgeincollegeprep.com">online@edgeincollegeprep.com</a>
				</div>
				
				<div class="field clearfix">
					<label>First & Last Name</label>
					<div style="width: 215px; margin-right: 5px;">
						<input type="text" name="first_name" id="first_name" class="required lettersonly" style="width: 200px;" value="<?php echo $user_selecction['first_name']; ?>" />
					</div>
					<div style="width: 215px;">
						<input type="text" name="last_name" id="last_name" class="required lettersonly" style="width: 200px;" value="<?php echo $user_selecction['last_name']; ?>" />
					</div>
				</div>
				<div class="field clearfix">
					<label>Gender</label>
					<div style="width: 215px; margin-right: 5px;">
						<select name="gender" id="gender" class="required" style="width: 207px;">
							<option></option>
							<option value="m" <?php if($user_selecction['gender'] == "m") echo "selected='selected'"; ?>>Male</option>
							<option value="f" <?php if($user_selecction['gender'] == "f") echo "selected='selected'"; ?>>Female</option>
						</select>
					</div>
				</div>
				<div class="field clearfix">
					<label>Email Address</label>
					<div style="width: 435px;">
						<input type="text" name="email" id="email" class="required email" style="width: 420px;" value="<?php echo $user_selecction['email']; ?>" />
					</div>
				</div>
				<div class="field clearfix">
					<label>School</label>
					<div style="width: 435px;">
						<input type="text" name="school" id="school" style="width: 420px;" value="<?php echo $user_selecction["school"]; ?>"/>
					</div>
			   </div>
				<div class="field clearfix">
					<label>Expected HS Graduation Year</label>
					<div style="width: 165px;">
						<select id="dob_y" name="Graduation_Year" class="required" style="width: 160px;">
							<?php 
								  $sel_value = NULL; 
								  if(isset($user_selecction["Graduation_Year"]) && !empty($user_selecction["Graduation_Year"]))
								  {
									  $sel_value = $user_selecction["Graduation_Year"];
								  }
							 ?>
							 <?php echo $date_utils_grad -> printDateOptions("years", $sel_value?$sel_value:date("Y")); ?>
						</select>
					</div>
				</div>
				<div class="billing-checkboxes">
					<div class="field checkbox terms">
						<input type="checkbox" name="terms_conditions" class="required" /> I agree to the Edge in College Prep's <a href="#">Terms and Conditions</a>
					</div>
					<div class="field checkbox">
						<?php  
							$checked = "";
							if(isset($user_selecction["promos"]) && $user_selecction["promos"] == "on")
							{
							   $checked = "checked='checked'";
							}
						?>
						<input type="checkbox" name="promos" <?php echo $checked;?> /> I want to receive Promos and Newsletters from Edge in College Prep
					</div>
					<div class="field checkbox">
						<?php  
							$checked = "";
							if(isset($user_selecction["info_partners"]) && $user_selecction["info_partners"] == "on")
							{
							   $checked = "checked='checked'";
							}
					   ?>
						<input type="checkbox" name="info_partners" <?php echo $checked;?> /> I want to receive information and promotions from ECP Partners which will help me in the college admission process
					</div>
				</div>

				<div class="submit-button clearfix">
					<button type="button" id="create-account">Create Account</button>
					<a href="#" id="go-to-login">Already have an account</a>
				</div>
			</form>
		</div>
		
		<?php /* LOGIN INFO POPUP */ ?>
		<div id="login-info-form" class="billing-form-popup" style="display: none;" >
			<form method="post" id="ecp_login_form">
				<div class="title"><span>STEP 1 -</span> ACCOUNT INFO</div>
				<div id="login-error" class="validation-error login">The username or password is incorrect</div>
				<div class="field clearfix">
					<label>Username</label>
					<div style="width: 435px;">
						<input type="text" name="login_username" id="login_username" class="required" style="width: 420px;" autocomplete="off" />
					</div>
				</div>
				<div class="field clearfix">
					<label>Password</label>
					<div style="width: 435px;">
						<input type="password" name="login_pass" id="login_pass" class="required" style="width: 420px;" autocomplete="off" />
					</div>
				</div>
				<div class="submit-button clearfix">
					<button type="button" id="login">Login</button>
					<a href="#" id="back-btn">Back</a>
				</div>
			</form>
		</div>
	
		<?php /* BILLING INFO POPUP */ ?>
		<div id="billing-info-form" class="billing-form-popup" <?php if($current_user->ID == 0): ?>style="display: none;"<?php endif; ?>>
			<form action="index.php?user_action=shopping-cart&section=checkout" method="post" id="ecp_registration_form">
				<div class="title"><span>STEP 2 -</span> PAYMENT INFO</div>
				
				<div id="account-success" class="account-success">
					Thank you for registering on college prep. Check your email for login credentials. If you don't receive any email from us, please contact us on : <a href="mailto:online@edgeincollegeprep.com">online@edgeincollegeprep.com</a>
				</div>
				
				<div class="field clearfix">
					<label>First & Last Name</label>
					<div style="width: 215px; margin-right: 5px;">
						<input type="text" name="cc_fname" class="required lettersonly" style="width: 200px;" autocomplete="off" value="<?php echo $user_selecction['cc_fname']; ?>" />
					</div>
					<div style="width: 215px;">
						<input type="text" name="cc_lname" class="required lettersonly" style="width: 200px;" autocomplete="off" value="<?php echo $user_selecction['cc_lname']; ?>" />
					</div>
				</div>
				<div class="field clearfix">
					<label>Email Address</label>
					<div style="width: 435px;">
						<input type="text" name="cc_email" class="required email" style="width: 420px;" autocomplete="off" value="<?php echo $user_selecction['cc_email']; ?>" />
					</div>
				</div>
				<div class="field clearfix">
					<label>Phone Number</label>
					<div style="width: 435px;">
						<input type="text" name="cc_phone" class="required" mask="[###] ### #######" style="width: 420px;" autocomplete="off" value="<?php echo $user_selecction['cc_phone']; ?>" />
					</div>
				</div>
				<div class="field clearfix">
					<label>Billing Address</label>
					<div style="width: 435px;">
					<input type="text" name="ba_1" class="required" style="width: 420px;" autocomplete="off" value="<?php echo $user_selecction['ba_1']; ?>" />
					</div>
				</div>
				<div class="field clearfix">
					<label>City, State & Zip</label>
					<div style="width: 215px; margin-right: 5px;">
						<input type="text" name="cc_city" class="required letterswithbasicpunc" style="width: 200px;" autocomplete="off" value="<?php echo $user_selecction['cc_city']; ?>" />
					</div>
					<div style="width: 105px; margin-right: 5px;">
						<select name="cc_state" id="cc_state" class="required" style="width: 100px;">
							<option></option>
							<?php foreach ($states_list as $state): ?>
							<option value="<?php echo $state; ?>" <?php if($user_selecction['cc_state'] == $state) echo "selected='selected'"; ?>><?php echo $state; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div style="width: 105px;">
						<input type="text" name="cc_zip" class="required" style="width: 90px;" value="<?php echo $user_selecction['cc_zip']; ?>" />
					</div>
				</div>
				<div class="field clearfix">
					<label>Card Type</label>
					<div style="width: 365px;">
						<select id="cc_type" name="cc_type" class="required" style="width: 350px;">
							<option value="">CHOOSE CARD TYPE</option>
							<?php foreach ($cards_list as $k=>$card): ?>
							<option value="<?php echo $k; ?>"><?php echo $card; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="field clearfix">
					<label>Credit Card #</label>
					<div style="width: 435px;">
						<input type="text" id="cc_number" name="cc_number" class="required" style="width: 420px;" autocomplete="off" readonly />
					</div>
				</div>
				<div class="field clearfix">
					<label>Expiration</label>
					<div style="width:89px; margin-right: 5px;">
						<select name="cc_ex_month" id="cc_ex_month" class="required" style="width:84px;">
							<option value=""></option>
							<?php foreach ($cc_months as $month): ?>
							<option value="<?php echo $month; ?>"><?php echo $month; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div style="width:134px;">
						<select name="cc_ex_year" id="cc_ex_year" class="required" style="width:120px;">
							<option value=""></option>
							<?php foreach ($cc_years as $year): ?>
							<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<label class="cvv">CVV#</label>
					<div style="width:105px;">
						<input type="text" id="cvv2" name="cvv2" class="required" style="width:90px;" autocomplete="off" readonly />
					</div>
				</div>
				<div class="field clearfix" style="display: none;">
					<label>Coupon Code</label>
					<input type="text" style="width:150px;" />
					<a href="#" id="applyCoupon">Apply Coupon</a>
				</div>
				<div class="field clearfix total">
					Your total purchase:
					<div>$<span id="popup-registration-total">0</span></div>
				</div>
				<div class="field clearfix total" style="display: none;">
					You have to pay:
					<div>$<span>0</span></div>
				</div>

				<div class="submit-button clearfix">
					<input type="submit" id="finalize-reg" value="Finalize Registration" />
					<input type="hidden" name="purchase-description" id="purchase-description" />
				</div>
			</form>
		</div>
</div>
	
<script type="text/javascript">
    jQuery(document).ready(function($){
		/* Form Validation*/
		var validator1 = $("#ecp_account_form").validate({
			errorClass: "validation-error",
			validClass: "validation-valid",
			errorElement: "span",
			rules: {
				email:{
					remote:{
						type: 'post',
						url: '<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/verify_username.php'
					}
				}
			},
			messages: {
				email: {
					remote: "This email is already registered in the system"
				}
			}
		});
		
		var validator2 = $("#ecp_login_form").validate({
			errorClass: "validation-error",
			validClass: "validation-valid",
			errorElement: "span"
		});

		var validator3 = $("#ecp_registration_form").validate({
			errorClass: "validation-error",
			validClass: "validation-valid",
			errorElement: "span"
		});

		
	
		$("#go-to-login").click(function(e) {
			e.preventDefault();
			validator1.resetForm();
			$("#account-info-form").hide();
			$("#login-info-form").show();
			$("#first_name").val("");
			$("#last_name").val("");
			$("#email").val("");
			$("#school").val("");
		});

		$("#back-btn").click(function(e) {
			e.preventDefault();
			validator2.resetForm();
			$("#login-error").hide();
			$("#account-info-form").show();
			$("#login-info-form").hide();
			$("#login_username").val("");
			$("#login_pass").val("");
		});

		$("#create-account").click(function() {
			var link = $(this);
			if($("#ecp_account_form").valid()) {
				$("#account-error").hide();
				$("#first_name").attr('disabled',true);
				$("#last_name").attr('disabled',true);
				$("#gender").selectBox('disable');
				$("#email").attr('disabled',true);
				$("#school").attr('disabled',true);
				$("#dob_d").selectBox('disable');
				$("#dob_m").selectBox('disable');
				$("#dob_y").selectBox('disable');
				link.addClass('disabled');
				jQuery.ajax({
					type: "POST",
					dataType: "json",
					data: {
						FirstName: $("#first_name").val(),
						LastName: $("#last_name").val(),
						Gender: $("#gender").selectBox('value'),
						Email: $("#email").val(),
						School: $("#school").val(),
						DOB_Day: $("#dob_d").selectBox('value'),
						DOB_Month: $("#dob_m").selectBox('value'),
						DOB_Year: $("#dob_y").selectBox('value')
					},
					url: "<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/create_user_account.php",
					success: function(data){
						if(data) {
							if($("#registration-total-hdn").val() > 0) {
								$("#account-info-form").hide();
								$("#billing-info-form").show();
								$("#account-success").show();
							} else {
								window.location = "<?php echo site_url('/thankyou'); ?>";
							}
						} else {
							$("#account-error").show();
							$("#first_name").attr('disabled',false);
							$("#last_name").attr('disabled',false);
							$("#gender").selectBox('enable');
							$("#email").attr('disabled',false);
							$("#school").attr('disabled',false);
							$("#dob_d").selectBox('enable');
							$("#dob_m").selectBox('enable');
							$("#dob_y").selectBox('enable');
							link.removeClass('disabled');
						}
					}
				});
			}
		});
	
		$("#login").click(function() {
			var link = $(this);
			if($("#ecp_login_form").valid()) {
				$("#login-error").hide();
				$("#login_username").attr('disabled',true);
				$("#login_pass").attr('disabled',true);
				link.addClass('disabled');
				jQuery.ajax({
					type: "POST",
					dataType: "json",
					data: {login: $("#login_username").val(), password: $("#login_pass").val()},
					url: "<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/login_user.php",
					success: function(data){
						if(data) {
							$("#login-info-form").hide();
							$("#billing-info-form").show();
						} else {
							$("#login_username").attr('disabled',false);
							$("#login_pass").attr('disabled',false);
							link.removeClass('disabled');
							$("#login-error").show();
						}
					}
				});
			}
		});
		
		$("#finalize-reg").click(function() {
			if($("#ecp_registration_form").valid()) {
				$(this).attr("disabled", true);
				$(this).addClass('disabled');
				$("#ecp_registration_form").submit();
			}
		});
	});
</script>