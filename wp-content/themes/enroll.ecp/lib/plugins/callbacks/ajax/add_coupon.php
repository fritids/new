<?php 

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$couponcode = $_POST['couponcode'];
$products = $_POST['products'];
$coupon_price = $_POST['coupon_price'];
$coupon_times = $_POST['coupon_times'];
$validity_time_from = $_POST['validity_time_from'];
$validity_time_till = $_POST['validity_time_till'];

$a = ecpCoupons::getInstance();
$sucess = $a->insertCoupon($couponcode,$coupon_price,$coupon_times,$validity_time_from,$validity_time_till);
$coupon_id = $a->lastInsert();
if($coupon_id){
	echo json_encode(array('success'=>$sucess ,'coupon_id'=>$coupon_id));
}
if(!empty($products)){
	foreach($products as $product){
		$a->insertProduct_in_Coupon($coupon_id,$product);	
		}
	}
?>