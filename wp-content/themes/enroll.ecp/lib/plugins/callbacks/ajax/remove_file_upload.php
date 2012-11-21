<?php 

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';
require_once '../classes/class.DownloadManager.php';

if($_POST['file_id']){
$a = new DownloadManager();
echo $a->deleteUpload($_POST['file_id']);
}
?>