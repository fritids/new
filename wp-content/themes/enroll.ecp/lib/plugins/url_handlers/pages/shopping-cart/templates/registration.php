<?php
	global $wpdb;
	global $current_user;

	$post_sql = "SELECT ID, post_content, post_title, post_name
				FROM {$wpdb -> posts}
				LEFT JOIN {$wpdb -> term_relationships} ON({$wpdb -> posts}.ID = {$wpdb -> term_relationships}.object_id)
				LEFT JOIN {$wpdb -> term_taxonomy} ON({$wpdb -> term_relationships}.term_taxonomy_id = {$wpdb -> term_taxonomy}.term_taxonomy_id)
				LEFT JOIN {$wpdb -> terms} ON({$wpdb -> term_taxonomy}.term_id = {$wpdb -> terms}.term_id)
				WHERE {$wpdb -> posts}.post_type = 'ECPProduct' 
				AND {$wpdb -> posts}.post_status = 'publish'
				AND {$wpdb -> term_taxonomy}.taxonomy = 'ecp-products'
				AND {$wpdb -> terms}.slug = '%s' 
				ORDER BY {$wpdb -> posts}.menu_order ASC;";

	// Get all categories
	$categories = array();
	foreach(get_terms("ecp-products", "hide_empty=0&orderby=none") as $single_category)
	{
		$categories[$single_category -> term_id] = $single_category;
	}

	// Display categories in "Product Order" menu order
	foreach(wp_get_nav_menu_items("Product Order") as $menu_item):
	
		$category = $categories[$menu_item -> object_id];
		$s_sql = sprintf($post_sql, $category -> slug);
		$products = $wpdb -> get_results($wpdb -> prepare($s_sql));
		
		// Order products
		$product_array = array();
		foreach($products as $post_data) {
			if(get_post_meta($post_data -> ID, 'price_type', true) == "free") {
				array_unshift($product_array, $post_data);
			} else {
				array_push($product_array, $post_data);
			}
		}
		$products = $product_array;
?>

<div class="product-category" <?php if($category -> slug == "essay-grading"): ?> style="display: none;" <?php endif; ?>>
	<h3><?php echo $category -> name ?></h3>

	<?php if($category -> slug == "test-prep-tutoring"): ?>
	<div class="location-container">
		<label>Select your location: </label>
		<select>
			<option value="new_york">New York</option>
			<option value="london">London</option>
			<option value="buenos_aires">Buenos Aires / Rio</option>
			<option value="south_florida">South Florida</option>
			<option value="skype">Skype / Online Tutoring</option>
		</select>
	</div>
	<?php endif; ?>

	<ul class="clearfix">
	<? foreach($products as $post_data): ?>
		<li>
			<div class="product-item <?php echo $category->slug; ?>">
				<div class="title"><?php echo $post_data->post_title ?></div>
				<div class="description">
					<a class="show-description" href='#product-info-<?php echo $post_data->ID ?>'>learn more</a>
				</div>
				<div class="price">
					<?php if( get_post_meta($post_data->ID, 'price_type', true) == "free" ) {
					echo "FREE!";
					} elseif( get_post_meta($post_data->ID, 'price_type', true) == "location" ) {
						$prices = unserialize(get_post_meta($post_data->ID, 'location_prices', true));
						if(!empty($prices)) {
							foreach ($prices as $key => $value) {
								echo "<span class='location_prices $key'>$$value</span>";
							}
						}
					} else {
						echo "$".get_post_meta($post_data->ID, 'universal_price', true);
					} ?>
				</div>
			</div>
			
			<div style="display:none">
				<div class="product-info-popup" id="product-info-<?php echo $post_data->ID ?>">
					<div class="category-name"><?php echo $category -> name ?></div>
					<div class="product-name"><?php echo $post_data->post_title ?></div>
					<p><?php echo $post_data->post_content; ?></p>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endforeach; ?>


<div class="register-now-container">
	<a href="#" id="register-now">REGISTER NOW!</a>
</div>
