<?php 


 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$coupon_id = $_POST['coupon_id'];
$coupon_code = $_POST['coupon_code'];
$products = $_POST['products'];
$coupon_price = $_POST['coupon_price'];
$coupon_times = $_POST['coupon_times'];
$validity_time_from = $_POST['validity_time_from'];
$validity_time_till = $_POST['validity_time_till'];

$a=ecpCoupons::getInstance();
echo $a->updateCoupon($coupon_id,$coupon_code,$coupon_price,$coupon_times,$validity_time_from,$validity_time_till);


?>