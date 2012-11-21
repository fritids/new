<?php 

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$coupon_id = $_POST['coupon_id'];
$products = $_POST['products'];

$a=ecpCoupons::getInstance();
$msg=0;
if(!empty($products)){
foreach($products as $product){
	if($a->insertProduct_in_Coupon($coupon_id,$product))
	{
		$msg=1;
	}
	
}

}
echo $msg;

?>