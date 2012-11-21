<?php 

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$coupon_id = $_POST['coupon_id'];
$product_id = $_POST['product_id'];

$a = ecpCoupons::getInstance();
echo $a->deleteProduct_From_Coupon($coupon_id,$product_id);
/*if($a->deleteProduct_From_Coupon($coupon_id,$product_id)){
	echo 1;
}
else{
	echo 0;
}*/



?>