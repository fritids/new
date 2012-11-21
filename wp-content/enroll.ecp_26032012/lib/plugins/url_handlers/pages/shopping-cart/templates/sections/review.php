<div class="order_form_wrapper">
	<div class="ecp_user_data">
		<ul>
			<?php foreach($params["user_data"] as $k => $user_data): ?>
			<li><?php echo $k; ?> => <?php echo $user_data; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<br/>
	<div class="ecp_cart_data">
		<ul>
			<?php foreach($params["cart_data"] as $k => $cart_data): ?>
			<li>"NAME" -  <?php echo $cart_data -> name; ?>-<?php echo $cart_data -> taxonomy; ?>  "PRICE" - <?php echo $cart_data -> price; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>