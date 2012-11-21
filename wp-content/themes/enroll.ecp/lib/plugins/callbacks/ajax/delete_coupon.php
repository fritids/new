<?php 
 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";

require  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$coupon_id = $_POST['coupon_id'];
$product_id = $_POST['product_id'];

$a = ecpCoupons::getInstance();
echo $a->removeCoupon($coupon_id);
?>