<?php 
	global $ecp_cart;
	global $session_utils;
	require_once IDG_CLASS_PATH."Utils/class.FormPostHandler.php";
	require_once IDG_CLASS_PATH."ShoppingCart/DiscountActions/class.ProductDiscount.php";
	
	// set prices with all arrays
	$discounter = new ProductDiscount();
	$discounts = $discounter -> mapDiscounts();
	$prices = $discounter -> mapPrices();
	$current_prices = $prices;
	$generic_discounts = array();
	
	// handle form data
	$handler = new FormPostHandler();
	$section = "regular_section";
	$cart_products = NULL;
	
	if ( is_user_logged_in() ) 
	{
		get_currentuserinfo();
		global $current_user;
		if(isset($_GET["pid"]))
		{
			$ecp_cart -> addProducts(array($_GET["pid"]));
		}
	}
	
	if(!$ecp_cart -> isEmpty())
	{
		$cart_products = $ecp_cart -> getAllProducts();
		$generic_discounts = ArrayUtils::selectedProductsDiscounts($cart_products);
		$current_prices = $discounter -> mapCurrentPrices($prices, $generic_discounts);
		$section = "first_page_cart_products";
	}
	
	if(!$session_utils -> isEmpty())
	{
		$session_utils -> cleanUp(array("cc_number", "cc_type", "cvv2"));
	}
	$session_utils -> addData(array("referrer" => $handler -> getReferrer()));
?>
<link rel="stylesheet" href="<?php bloginfo("template_directory") ?>/lib/js/fancybox/jquery.fancybox-1.3.3.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/sprintf.js"></script>
<script type="text/javascript">
	var discountArr = <?php echo json_encode($discounts); ?>; 
	var pricesArr = <?php echo json_encode($prices); ?>; 
	var currentPrices = <?php echo json_encode($current_prices); ?>; 
</script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/fancybox/jquery.fancybox-1.3.3.pack.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory") ?>/lib/js/bubble-popup.js"></script>

<form action="account" method="POST" id="ecp_cart_form">
	<div class="whitebox">
		<h4>YOUR SELECTION</h4>
		<?php
			global $wpdb;
			global $ecp_cart;
			
			$items=wp_get_nav_menu_items("Product Order");
			$order=array();
			foreach($items as $menu_item){
				$order[]=$menu_item->object_id;
			}
			
			$coupon = $ecp_cart -> getCoupon();
			$tax_arr = get_terms("ecp-products", "hide_empty=0&orderby=none");

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
			$directory=array();
			foreach($tax_arr as $single_tax)
			{
				$directory[$single_tax->term_id]=$single_tax;
			}
			
			//foreach($tax_arr as $single_tax)
			foreach($order as $id)
			{
				//echo $single_tax;
				$single_tax=$directory[$id];
				$s_sql = sprintf($post_sql, $single_tax -> slug);
				$data = $wpdb -> get_results($wpdb -> prepare($s_sql));
				$template_data = array(
								"tax" => $single_tax,
								"data" => $data
						);
				echo Templator::getCartTemplate($section, array("template" => $template_data, "cart_products" => $cart_products, "discounts" => $generic_discounts), "sections/");
			}
		?>
		<div class="section coupon_section">
			<div class="selection coupon_selection">
				<div class="couponerrorwrapper"><div class="couponerrornote"><span></span></div></div>
				<div>
					<label for="course_coupon">Have a Coupon</label>
					<input type="text" name="course_coupon" id="course_coupon" class="course_coupon" value="<?php $coupon_code = ($coupon) ? $coupon -> code : "" ; echo $coupon_code; ?>" /> 
					<input type="submit" id="add_coupon" class="add_coupon" value="ADD" />
				</div>
				<span class="selection_price">
					<span class='price basic_price'>
						<span>$ </span>
						<span class='int_selection_price'><?php $coupon_discount = ($coupon) ? $coupon -> price : "0" ; echo $coupon_discount; ?></span>
					</span>
				</span>
			</div>
		</div>
		<div class="order_total">
			<span class="total">Your total:</span> 
			<span class="total_price">
				<span>$ </span>
				<span class="int_total_price">0</span>
			</span>
		</div>
		<div class="step_navigation">
			<a href="#" class="form_navigation" id="ecp_next_step">Next</a>
		</div>
   </div>
</form>