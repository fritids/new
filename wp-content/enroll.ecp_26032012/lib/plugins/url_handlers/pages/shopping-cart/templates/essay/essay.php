<a href='#' class='learn_more'>learn more</a>
<?php if($params["discount_ammount"] != 0) : ?>
<span class='price basic_price'><span>$</span> <span class=""><?php echo $params["price"]?></span></span>
<span class="price"><span>$</span> <span class="int_price"><?php echo ((int)$params["price"] - (int)$params["discount_ammount"])?></span></span>
<?php else: ?>
<span class='price'><span>$</span> <span class="int_price"><?php echo $params["price"]?></span></span>
<?php endif; ?>
<input type="hidden" name="products_ids[]" value="<?php echo $params["id"]; ?>" />
