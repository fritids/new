<?php
get_header();
require_once "shopping-cart/header.php";
require_once IDG_CLASS_PATH."Templator/class.Templator.php"; 
global $wp_query;
global $ecp_cart;
$section = $wp_query -> query_vars['section'];

if(!is_null($section))
{
	echo Templator::getCartTemplate($section, $ecp_cart);
}
?>
<?php get_footer(); ?>
