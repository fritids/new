<?php

// Register ECP Products Post type
 $args = array(
	'labels' => array(
		'name' => _x('ECP Products', 'post type general name'),
		'singular_name' => _x('ECP Product', 'post type singular name'),
		'add_new' => _x('Add New', 'ECP Product'),
		'add_new_item' => __('Add New ECP Product'),
		'edit_item' => __('Edit ECP Product'),
		'new_item' => __('New ECP Product'),
		'view_item' => __('View ECP Product'),
		'search_items' => __('Search ECP Product'),
		'not_found' =>  __('No ECP Product found'),
		'not_found_in_trash' => __('No ECP Product found in Trash'), 
		'parent_item_colon' => ''
	),
	'public' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'query_var' => true,
	'rewrite' => true,
	'capability_type' => 'post',
	'hierarchical' => true,
	'menu_position' => null,
	'supports' => array('title','editor','page-attributes'),
  );
register_post_type('ecpproduct',$args);

// Register ECP Products taxonomy
register_taxonomy(
	'ecp-products',
	'ecpproduct',
	array('hierarchical' => true, 'label' => __('ECP Products Categories'),
		'query_var' => true,
		'rewrite' => true
	)
);

add_action( 'admin_init', 'my_admin' );

function my_admin() {
	add_meta_box( 'online_subscription_meta_box',
        'Online Course Subscription',
        'display_online_subscription_meta_box',
        'ecpproduct', 'normal', 'high'
    );
    add_meta_box( 'prices_meta_box',
        'ECP Product Prices',
        'display_prices_meta_box',
        'ecpproduct', 'normal', 'high'
    );
}

function display_online_subscription_meta_box( $product_data ) {
	$time_coverage = get_post_meta( $product_data->ID, 'time_coverage', true );
	$time_measure = get_post_meta( $product_data->ID, 'time_measure', true );
	?>
	<div style="line-height: 2;">
		<strong>If this product allows users to access the online courses, please select the product shelf life:</strong>
		<div>
			<label>Online course coverage:</label>
			<input type="text" name="time_coverage" size="3" value="<?php echo $time_coverage; ?>" />
			<input type="radio" value="days" name="time_measure" checked /> days
			<input type="radio" value="months" name="time_measure" <?php checked("months", $time_measure) ?> /> months
		</div>
	</div>
	<?php
}

function display_prices_meta_box( $product_data ) {
    $price_type = get_post_meta( $product_data->ID, 'price_type', true );
	$universal_price = esc_html( get_post_meta( $product_data->ID, 'universal_price', true ) );
	$location_prices = unserialize( get_post_meta( $product_data->ID, 'location_prices', true ) );
    ?>
	<div style="line-height: 2;">
		<strong>Select the type of price for this product:</strong>
		<div><input type="radio" id="price_type_free" value="free" name="price_type" checked> Free Product</div>
		<div><input type="radio" id="price_type_universal" value="universal" name="price_type" <?php checked("universal", $price_type) ?>> Universal Price</div>
		<div><input type="radio" id="price_type_location" value="location" name="price_type" <?php checked("location", $price_type) ?>> Price by Location</div>
	</div>
	<div id="universal-container" style="display:none;">
		<hr>
		<table>
			<tr>
				<td>Universal price: </td>
				<td>$<input type="text" size="10" name="universal_price" value="<?php echo $universal_price; ?>" /></td>
			</tr>
		</table>
	</div>
	<div id="location-container" style="display:none;">
		<hr>
		<table>
			<tr>
				<td>New York: </td>
				<td>$<input type="text" size="10" name="location_prices[new_york]" value="<?php echo $location_prices['new_york']; ?>" /></td>
			</tr>
			<tr>
				<td>London: </td>
				<td>$<input type="text" size="10" name="location_prices[london]" value="<?php echo $location_prices['london']; ?>" /></td>
			</tr>
			<tr>
				<td>Buenos Aires / Rio: </td>
				<td>$<input type="text" size="10" name="location_prices[buenos_aires]" value="<?php echo $location_prices['buenos_aires']; ?>" /></td>
			</tr>
			<tr>
				<td>South Florida: </td>
				<td>$<input type="text" size="10" name="location_prices[south_florida]" value="<?php echo $location_prices['south_florida']; ?>" /></td>
			</tr>
			<tr>
				<td>Skype / Online Tutoring: </td>
				<td>$<input type="text" size="10" name="location_prices[skype]" value="<?php echo $location_prices['skype']; ?>" /></td>
			</tr>
		</table>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			hide_prices();
			jQuery("#price_type_universal").click(function() { hide_prices() });
			jQuery("#price_type_location").click(function() { hide_prices() });
			jQuery("#price_type_free").click(function() { hide_prices() });
			
			function hide_prices() {
				if(jQuery("#price_type_universal").is(':checked')) {
					jQuery("#universal-container").show();
					jQuery("#location-container").hide();
				} else if(jQuery("#price_type_location").is(':checked')) {
					jQuery("#location-container").show();
					jQuery("#universal-container").hide();
				} else if(jQuery("#price_type_free").is(':checked')) {
					jQuery("#location-container").hide();
					jQuery("#universal-container").hide();
				}
			}
		});
	</script>
    <?php
}

add_action( 'save_post', 'add_price_fields', 10, 2 );

function add_price_fields( $product_data_id, $product_data ) {
    // Check post type for movie reviews
    if ( $product_data->post_type == 'ecpproduct' ) {
        // Store data in post meta table if present in post data
		if ( isset( $_POST['time_coverage'] ) && !empty($_POST['time_coverage']) ) {
            update_post_meta( $product_data_id, 'time_coverage', $_POST['time_coverage']);
        }
		if ( isset( $_POST['time_measure'] ) && !empty($_POST['time_measure']) ) {
            update_post_meta( $product_data_id, 'time_measure', $_POST['time_measure']);
        }
        if ( isset( $_POST['price_type'] ) && $_POST['price_type'] != '' ) {
            update_post_meta( $product_data_id, 'price_type', $_POST['price_type'] );
        }
        if ( isset( $_POST['universal_price'] ) && $_POST['universal_price'] != '' ) {
            update_post_meta( $product_data_id, 'universal_price', $_POST['universal_price'] );
        }
		if ( isset( $_POST['location_prices'] ) && !empty($_POST['location_prices']) ) {
            update_post_meta( $product_data_id, 'location_prices', serialize($_POST['location_prices']));
        }
    }
}