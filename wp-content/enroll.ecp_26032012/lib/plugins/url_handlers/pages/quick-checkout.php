<?php

get_header();
require_once "shopping-cart/header.php";
require_once IDG_CLASS_PATH."Templator/class.Templator.php"; 
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPShoppingCart.php"; 

$post=get_post($_GET["pid"]);

global $wp_query;
global $ecp_cart;
$ecp_cart=new ECPShoppingCart("quick-cart");

$ecp_cart->addProducts($post);


?>
	<?php echo Templator::getCartTemplate("billing_info", $user_data, "sections/"); ?>
	<?php echo Templator::getCartTemplate("cart", $ecp_cart, "sections/"); ?>
<?php get_footer(); ?>