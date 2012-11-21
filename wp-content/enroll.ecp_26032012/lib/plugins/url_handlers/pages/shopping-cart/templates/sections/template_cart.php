<table cellpadding="0" cellspacing="0" width="100%">
	<?php 
		$products = $params -> getAllProducts();
		$total_price = 0;
		$i = 1; 
		foreach($products as $product)
		{
			$class = "odd";
			if($i % 2 == 0 && $i > 0){ $class = "even"; }
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
			else
			{
				if( $product -> price < 0)
				{
			?>
				<tr class="<?php echo $class;?>">
					<td>Your coupon discount: </td>
					<td class="product_price">$&nbsp;<?php echo $product -> price ?></td>
					<td></td>
				</tr>
			<?php 
				}
			}
			$total_price += $product -> price;
			$i ++;
		}
	?>
</table>
|
<span class="total_price">$ <?php echo $total_price; ?></span>