<?php
	global $wpdb;
	global $current_user;
	
	require_once IDG_CLASS_PATH."Utils/class.DateUtils.php";
	$date_utils = new DateUtils();
	
	$states_list = array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA",
		"ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC",
		"SD","TN","TX","UT","VT","VA","WA","WV","WI","WY");
	
	$cards_list = array('visa'=>'Visa', 'master_card'=>'Master Card', 'am_ex'=>'American Express');
	
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
		
		// Order products
		$product_array = array();
		foreach($products as $post_data) {
			if(get_post_meta($post_data -> ID, 'price_type', true) == "free") {
				array_unshift($product_array, $post_data);
			} else {
				array_push($product_array, $post_data);
			}
		}
		$products = $product_array;
?>

<div class="product-category" id="<?php echo $category -> slug; ?>" <?php if($category -> slug == "essay-grading"): ?>style="display: none;"<?php endif; ?>>
	<?php if($category -> slug != "test-prep-tutoring-junior" && $category -> slug != "test-prep-tutoring-senior" && $category -> slug != "essay-grading"): ?>
	<h3><?php echo $category -> name ?></h3>
	<?php elseif($category -> slug == "test-prep-tutoring-junior"): ?>
	<h3>Test Prep Tutoring</h3>
	<?php endif; ?>
	
	<?php if($category -> slug == "essay-grading"): ?>
	<div class="upgrade-course">Do you want to add on <span>Essay Grading</span> to the course?</div>
	<div class="upgrade-course-sub">Get up to 15 SAT or ACT essays graded by an Edge test prep expert for $10/essay</div>
	<?php endif; ?>

	<?php if($category -> slug == "test-prep-tutoring-junior"): ?>
	<div class="location-container">
		<label>Select your location: </label>
		<select id="location-select">
			<option value="new_york">New York</option>
			<option value="london">London</option>
			<option value="buenos_aires">Buenos Aires / Rio</option>
			<option value="south_florida">South Florida</option>
			<option value="skype">Skype / Online Tutoring</option>
		</select>
	</div>
	<div class="location-container">
		<label>Level or tutors: </label>
		<select id="tutor-level-select">
			<option value="junior">Junior</option>
			<option value="senior">Senior</option>
		</select>
	</div>
	<?php endif; ?>

	<ul class="clearfix" <?php if($category -> slug == "test-prep-tutoring-senior"): ?> style="display: none;" <?php endif; ?>>
	<? foreach($products as $post_data): ?>
		<li>
			<div class="product-item <?php echo $category->slug; ?>" price-type="<?php echo get_post_meta($post_data->ID, 'price_type', true); ?>" price="<?php echo get_post_meta($post_data->ID, 'universal_price', true); ?>">
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
</div>

<div class="register-now-container">
	<a href="#billing-form-popup" id="register-now">REGISTER NOW!</a>
</div>





<div style="display:none">
	<div id="billing-form-popup">
		<form action="" method="post" id="ecp_registration_form">
			<div class="title"><span>STEP 2 -</span> PAYMENT INFO</div>
			<div class="field clearfix">
				<label>First & Last Name</label>
				<div style="width: 215px; margin-right: 5px;">
					<input type="text" name="cc_fname" class="required lettersonly" style="width: 200px;" />
				</div>
				<div style="width: 215px;">
					<input type="text" name="cc_lname" class="required lettersonly" style="width: 200px;" />
				</div>
			</div>
			<div class="field clearfix">
				<label>Billing Address</label>
				<div style="width: 435px;">
				<input type="text" name="ba_1" class="required" style="width: 420px;" />
				</div>
			</div>
			<div class="field clearfix">
				<label>City, State & Zip</label>
				<div style="width: 215px; margin-right: 5px;">
					<input type="text" name="cc_city" class="required letterswithbasicpunc" style="width: 200px;" />
				</div>
				<div style="width: 105px; margin-right: 5px;">
					<select name="cc_state" class="required" style="width: 100px;">
						<option></option>
						<?php foreach ($states_list as $state): ?>
						<option value="<?php echo $state; ?>"><?php echo $state; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div style="width: 105px;">
					<input type="text" name="cc_zip" class="required" style="width: 90px;" />
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
					<input type="text" id="cc_number" name="cc_number" class="required" style="width: 420px;" readonly />
				</div>
			</div>
			<div class="field clearfix">
				<label>Expiration</label>
				<div style="width:89px; margin-right: 5px;">
					<select name="cc_ex_month" class="required" style="width:84px;">
						<option value=""></option>
						<?php foreach ($cc_months as $month): ?>
						<option value="<?php echo $month; ?>"><?php echo $month; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div style="width:134px;">
					<select name="cc_ex_year" class="required" style="width:120px;">
						<option value=""></option>
						<?php foreach ($cc_years as $year): ?>
						<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<label class="cvv">CVV#</label>
				<div style="width:105px;">
					<input type="text" id="cvv2" name="cvv2" class="required" style="width:90px;" readonly />
				</div>
			</div>
			<div class="field clearfix">
				<label>Coupon Code</label>
				<input type="text" style="width:150px;" />
			</div>
			<div class="field clearfix total">
				Your total purchase:
				<div>$<span id="popup-registration-total">0</span></div>
			</div>
			<div class="field clearfix total">
				You have to pay:
				<div>$<span>0</span></div>
			</div>

			<div class="billing-checkboxes">
				<div class="field checkbox">
					<input type="checkbox" name="terms_conditions" class="required" /> I agree to the Edge in College Prep's <a href="#">Terms and Conditions</a>
				</div>
				<div class="field checkbox">
					<input type="checkbox" name="promos" checked /> I want to receive Promos and Newsletters from Edge in College Prep
				</div>
				<div class="field checkbox">
					<input type="checkbox" name="info_partners" checked /> I want to receive information and promotions from ECP Partners which will help me in the college admission process
				</div>
			</div>
			<div class="submit-button clearfix">
				<input type="submit" value="Finalize Registration">
			</div>
		</form>
	</div>
</div>