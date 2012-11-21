<?php if($params != null && !$params -> isEmpty()): ?>
<div class="order_cart">
	<h5>Your Cart:</h5>

	<table cellpadding="0" cellspacing="0" width="100%">
			<?php 
				$products = $params -> getAllProducts();
				$total_price = 0;
				$i = 1; 
				$dis_class = "odd";
				fb($params);
				if($params -> getCoupon())
				{
					$coupon = $params -> getCoupon() -> getPrice();
				}
				$reduce = 0;
				foreach($products as $product)
				{
					$class = "odd";
					$dis_class = "even";
					if($i % 2 == 0 && $i > 0){ $class = "even"; $dis_class = "odd";}
					
					if(!is_null($product -> name)) 
					{
					?>
						<tr class="<?php echo $class;?>">
							<td><?php echo $product -> taxonomy ?> - <?php echo $product -> name ?></td>
							<td class="product_price">$&nbsp;<?php echo $product -> price ?></td>
							<td class="remove_product"><a href="<?php echo $product -> id; ?>"></a></td>
						</tr>
					<?php 
					}
					if($params -> checkProductInCoupon($product -> id))
					{
					?>
					<?php 
					}
					if($product -> discounted_price)
					{
						$total_price += $product -> discounted_price;
					}
					else 
					{
						$total_price += $product -> price;
					}
					$i ++;
				}
			?>
			<?php if($params -> getCoupon()) : ?>
				
				<tr class="<?php echo $dis_class;?>">
						<td>Your coupon discount: </td>
						<td class="product_price">$&nbsp;<?php echo $coupon[1]; ?></td>
						<td></td>
				</tr>
			
			<?php endif; ?>
			
	</table>
</div>
<div class="order_total">
	<span class="total">Your total:</span> 
	<span class="total_price">$ <?php echo $total_price; ?></span>
</div>
<?php else: ?>
<div class="order_cart">
	<h5>Your cart is empty</h5>
</div>
<?php endif; ?>