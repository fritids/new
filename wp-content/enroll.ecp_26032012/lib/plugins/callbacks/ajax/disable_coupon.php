<?php

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$coupon_id = $_POST['coupon_id'];
$toggle = $_POST['toggle'];
$a = ecpCoupons::getInstance();
if($toggle == 'enable'){
echo $a->enableCoupon($coupon_id).'_enabled';
}
else{
echo $a->disableCoupon($coupon_id).'_disabled';	
}

?>