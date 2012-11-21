<?php
$class = "";
switch($params["template"]["tax"] -> slug)
{
	case "online-sat-course":
		$class = "cp_period";
		break;
	case "college-board-official-practice-tests-explanations":
		$class = "long_description";
		break;
}
?>
<div class='section <?php echo $params["template"]["tax"] -> description ?>'>
	<div class='selection'>
		<h3><?php echo $params["template"]["tax"] -> name ?></h3>
		<ul>
<?php 
$total_price = 0;
switch ($params["template"]["tax"] -> slug)
{
	/*case "essay-grading":
		?>
		<li>
			<select class='main_select' id="main_select">
			<option value="0">Select</option>
			<?php 
				require_once IDG_CLASS_PATH."ShoppingCart/FormActions/class.ProductFilter.php";
				$filter_product = new ProductFilter(array($params["cart_products"], $params["template"]["data"]), "single_product_selection");
				$fp = $filter_product -> mapSingleProductDiscount($params["cart_products"]);
				$price_class = "";
				$int_class = "int_price";
				$option_value = "";
			
				foreach($params["template"]["data"] as $post_data)
				{
					$selected = "";
					if($post_data -> ID == $fp -> id)
					{
						$selected = "selected='selected'"; 
						$option_value = $post_data -> ID;
					}
					?>
						<option value='<?php echo $post_data -> post_title ?>' <?php echo $selected;?> title="<?php echo $post_data -> ID; ?>"><?php echo $post_data -> post_title ?></option>";
					<?php 
				}
			?>
			</select>
			<span class="description">
				<a href='#' class='learn_more'>learn more</a>
				<?php $discount_price = $fp -> price; $total_price = $discount_price; ?>
				<?php if($fp -> discounted_price > 0) { $price_class = "basic_price"; $int_class = ""; } ?>
	            <span class='price <?php echo $price_class; ?>'>
		            <span>$</span> 
		            <span class="<?php echo $int_class; ?>"><?php echo $fp -> price + $fp -> discounted_price; ?></span>
	            </span>
	                
				<?php if($fp -> discounted_price > 0): $int_class = "int_price"; ?>
	                <span class='price'>
		               	<span>$</span> 
		               	<span class="<?php echo $int_class; ?>"><?php echo $discount_price; ?></span>
	                </span>
	            <?php endif;?>
				<input type="hidden" name="products_ids[]" value="<?php echo $option_value; ?>" />
			</span>
		</li>
		<?php 
	break;*/
	default:
		foreach($params["template"]["data"] as $post_data)
		{
			$post_meta = getECPProductMeta($post_data -> ID);
			$post_id = "";
			$product_class = "";
			$price_class = "";
			$style = "";
			$int_class = "int_price";
	
			foreach($params["cart_products"] as $product)
			{
				if($post_data -> ID == $product -> id) 
				{ 
					$product_class = "selected";
					$style = "style='display:block;'";
					$post_id = $post_data -> ID;
					$discount_price = $product -> price;
					$quantity = $product -> quantity;
					if(array_key_exists($post_data -> ID, $params["discounts"])) { $product -> discounted_price = $params["discounts"][$post_data -> ID]; }
					$total_price += ($discount_price * $quantity);
					//$total_price += $product -> full_price;
				}
			}
			?>
			<li>
				<?php if($params["template"]["tax"] -> slug == "video-explanations"): echo '<div class="coming_soon"></div>'; endif;?>
                <a href='#' class='main_select <?php echo $product_class." ".$class; ?>' rel="<?php echo $post_data -> ID; ?>">
	                <?php echo $post_data -> post_title ?>
	                <span class="cart_added" <?php echo $style; ?>></span>
                </a>
                <a href='#' class='learn_more'>learn more</a>
                <?php if(array_key_exists($post_data -> ID, $params["discounts"])) { $price_class = "basic_price"; $int_class = ""; } ?>
                <span class='price <?php echo $price_class; ?>'>
	                <span>$</span> 
	                <span class="<?php echo $int_class; ?>"><?php echo $post_meta["price"] ?></span>
                </span>
                
				<?php if( array_key_exists($post_data -> ID, $params["discounts"])): $int_class = "int_price"; ?>
                	<span class='price'>
	                	<span>$</span> 
	                	<span class="<?php echo $int_class; ?>"><?php echo max(0, $post_meta["price"] - $params["discounts"][$post_data -> ID]); ?></span>
                	</span>
                <?php endif;?>
				 <?php if(strpos($params["template"]["tax"] -> slug, "essay" ) !== false): ?>
				 		<?php if($product_class == "selected"): $class = "visible"; endif; ?>
						<div class="ecp_quantity <?php echo $class ?>"><div>Quantity:</div><input type="text" name="<?php echo $post_data -> ID; ?>" value="<?php echo $quantity; ?>" /></div>
						
				<?php endif; ?>
               <input type="hidden" name="products_ids[]" value="<?php echo $post_id; ?>" />
	        </li>
	       <?php 
		}
		
	break;
}
?>
		</ul>
		<div class="errornote"><span></span></div>
		<span class="selection_price">
			<span>$</span> 
			<span class="int_selection_price"><?php echo $total_price; ?></span>
		</span>
	</div>
</div>
