<?php

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$a = ecpCoupons::getInstance();
$products = $a->listAllProducts();

	$pagenum;
	$query;
 	if(!isset($_POST['pagenum'])){
		$pagenum = 1;
	}
	else{
		$pagenum = $_POST['pagenum'];
	}

 $query = "SELECT * FROM {$wpdb->posts} WHERE post_type='ecpproduct'";
 $rows =  count($wpdb->get_results($query,ARRAY_A));
 
 $per_page = 5;
 $last = ceil($rows/$per_page);
 
 if($pagenum < 1){
 	$pagenum = 1;
 }
 elseif($pagenum > $last){
 	$pagenum = $last;
 }
 
 $max = 'limit ' . ($pagenum - 1) * $per_page .',' . $per_page;
 
 
 $results = $wpdb->get_results($query.' '.$max,ARRAY_A);
 $count_posts = count($results);
  echo '<ul>';
foreach($results as $product){
	echo "<li><input type='checkbox' name='all_products_checkbox' value='". $product['ID'] ."' />" . $product['post_title'] . "</li>";
}
  echo '</ul>';
  ?>
  
  <div class="tablenav-pages"><span class="displaying-num">
	<?php for($i=1;$i<=$last;$i++): ?>
	
	<?php 
	if($pagenum==$i){ 
	echo '<span class="page-numbers current">'.$i.'</span>';
	} 
	elseif($i==$last){
   // echo '<a href="'.$i.'" class="next page-numbers">>></a>';
	}
	else{
	echo '<a href="&pagenum='.$i.'" class="page-numbers">'.$i.'</a>'; 
	 
	}
	?>
	<?php endfor; ?>
  </div>
  
 