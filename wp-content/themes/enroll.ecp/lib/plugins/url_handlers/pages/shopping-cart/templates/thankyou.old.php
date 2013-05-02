<?php
require_once IDG_CLASS_PATH."Utils/class.FormPostHandler.php";
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPStudent.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';
require_once IDG_CLASS_PATH.'/ShoppingCart/FormActions/class.ProductFilter.php';

global $ecp_cart;
global $session_utils;
$handler = new FormPostHandler();
$one = $session_utils -> getUserData("referrer");
$two = $handler -> getReferrer();

if( $one  )
{	
	$step = false;
}
else 
{
	$step = true;
}

$session_utils -> addData(array("referrer" => $handler -> getReferrer()));
if($step)
{
	wp_redirect(get_bloginfo("url")."/cart/");
	return;
}

$user_data = $session_utils -> getAllUserData();
if(isset($user_data["Email"]))
{
	$student = new ECPStudent($user_data["Email"]);
}
elseif(isset($user_data["user_email"]))
{
	$student = new ECPStudent($user_data["user_email"]);
}

if(!($student -> userExist()))
{
	$student -> createUser($user_data);
}

if($ecp_cart -> cartExist())
{
	$cart_products = $ecp_cart -> getAllProducts();
	$terms = get_terms("ecp-products");
	$categories = array();
	
	foreach ($terms as $term)
	{
		if(strpos($term -> slug, "essay") !== false)
		{
			$categories["essay"] = array("subject" => "essay-subject", "body" => "essay-body");
		}
		else
		{
			$categories[$term -> slug] = array("subject" => "{$term -> slug}-subject", "body" => "{$term -> slug}-body");			
		}
	}
	
	$h = array();
	foreach($cart_products as $product)
	{
		$original_price = getPostMeta($product -> id, "ProductPrice");
		if($original_price != $product -> price)
		{
			$product -> discount =  $original_price - $product -> price;
		}
		
		if(!in_array($product -> taxonomy_slug, $h))
		{
			$slug = ProductFilter::slugFilter($product -> taxonomy_slug);
			$student -> sendProductMail($categories[$slug]["subject"], $categories[$slug]["body"]);
			array_push($h, $product -> taxonomy_slug);
		}
	}
	
	$student -> saveUserProducts($cart_products);
	$coupon = $ecp_cart -> getCoupon();
	
	if($coupon)
	{
		$tashak = ecpCoupons::getInstance();
		$tashak -> decreaseCouponUsage($coupon -> getCouponId());
	}
}
?>

<div class="whitebox">
	<h4>Thank you for ordering</h4>
	<div class="ecp_order_wrapper">
		<?php echo Templator::getCartTemplate("thankyou", array("user_data" => $user_data, "cart_data" => $cart_products), "sections/"); ?>
	</div>
</div>
<?php 
	if($ecp_cart -> cartExist())
	{
		$ecp_cart -> destroyCart();
	}
	$session_utils -> destroyUserData();
?>