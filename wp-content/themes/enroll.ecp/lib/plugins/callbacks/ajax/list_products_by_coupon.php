<?php 

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";

require  $path."wp-load.php";
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';

$coupon_id = $_POST['coupon_id'];

?>
			<?php $a = ecpCoupons::getInstance(); $coupon = $a->listCoupon($coupon_id) ; $products = $a->listProductsWithCoupons_edit($coupon_id); $non_products = $a->listProductsWithCoupons_edit_not_in_coupon($coupon[0]['coupon_id']); ?>
			
                    <div class="add_coupon_dialog">
                    <input type="hidden" value="<?php echo $coupon[0]['coupon_id']; ?>" name="edit_coupon_id" class="edit_coupon_id" />
                        <h2>Edit Coupon - Products Relation</h2>
                        <label for="coupon_code">Coupon Code:
                            <input type="text" name="coupon_code" id="edit_coupon_code" maxlength="10" size="10" value="<?php echo $coupon[0]['coupon_code']; ?>">
                        </label>
                        <h3>Products associated to this coupon:</h3>
                        <ul>
                        <?php foreach($products as $product):?>
                        <li><input type="hidden" value="<?php echo $product['ID']; ?>" class="edit_coupon_product_id" /><?php echo $product['post_title']; ?><input type="button" name="update_products_delete_button" class="update_products_delete_button" value="Delete" /></li>
                        <?php endforeach; ?>
                        </ul>
                        <input type="button" value="Add New Product to this Coupon" name="update_add_products_button" class="update_add_products_button" />
                        <div class="update_add_products_wrap" style="display:none;">
                        <h3>Select Products you want to insert under this coupon:</h3>
                       
                        <?php foreach($non_products as $nprod): ?><span>
                        <input type="checkbox" value="<?php echo $nprod['ID']; ?>" name="update_add_product_checkbox" class="update_add_product_checkbox" /><?php echo $nprod['post_title']; ?><br></span>
                        
                        
                        <?php endforeach; ?>
                        <input type="button" value="Insert Products" name="update_add_product_button" class="update_add_product_button" />
                        </div>
                        <label for="coupon_price"><div>Value: -<input type="text" name="coupon_price" class="edit_coupon_price" size="3" value="<?php echo $coupon[0]['coupon_price']; ?>" maxlength="3"/>$</div></label>
                        <label for="coupon_times"><div>How many times it can be used in total: <input type="text" name="coupon_times" class="edit_coupon_times" size="3" value="<?php echo $coupon[0]['coupon_usage']; ?>" maxlength="3" />(only numbers - max 3 characters)</div></label>
                        <label for="validity_time_from">Validity From:<input type="text" name="validity_time_from" class="edit_validity_time_from" readonly value="<?php echo $coupon[0]['coupon_startdate']; ?>" maxlength="10" size="10" /><div class="validity_time_from2"></div></label>
                        <label for="validity_time_till">Validity Till:<input type="text" name="validity_time_till" class="edit_validity_time_till" readonly value="<?php echo $coupon[0]['coupon_enddate']; ?>" maxlength="10" size="10" /></label>
                        <br>
                        <input type="button" value="Update Coupon" id="update_coupon_button" name="update_coupon_button" /><span class="update_coupon_message"></span>
                    </div>