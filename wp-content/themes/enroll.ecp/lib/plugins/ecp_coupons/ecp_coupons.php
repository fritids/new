<?php

// Register ECP Coupons Post type
 $args = array(
	'labels' => array(
		'name' => _x('ECP Coupons', 'post type general name'),
		'singular_name' => _x('ECP Coupon', 'post type singular name'),
		'add_new' => _x('Add New', 'ECP Coupon'),
		'add_new_item' => __('Add New ECP Coupon'),
		'edit_item' => __('Edit ECP Coupon'),
		'new_item' => __('New ECP Coupon'),
		'view_item' => __('View ECP Coupon'),
		'search_items' => __('Search ECP Coupon'),
		'not_found' =>  __('No ECP Coupon found'),
		'not_found_in_trash' => __('No ECP Coupon found in Trash'), 
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
	'supports' => array('title','page-attributes'),
  );
register_post_type('ecpcoupon',$args);

add_action( 'admin_init', 'coupon_admin' );
function coupon_admin() {
	
	add_meta_box( 'discount_meta_box',
        'Coupon Details',
        'display_discount_meta_box',
        'ecpcoupon', 'normal', 'high'
    );
	
    add_meta_box( 'products_meta_box',
        'Products to apply this coupon',
        'display_products_meta_box',
        'ecpcoupon', 'normal', 'high'
    );
	
	wp_enqueue_style('discount_meta_box', plugin_dir_url( __FILE__ ) . '/css/styles.css');
    //wp_enqueue_script('your-meta-box', $base_url . '/meta-box.js', array('jquery'), null, true);
}

function display_discount_meta_box( $coupon_data ) {
	$coupon_code = esc_html( get_post_meta( $coupon_data->ID, 'coupon_code', true ));
	$discount_value = get_post_meta( $coupon_data->ID, 'discount_value', true );
	$usage = get_post_meta( $coupon_data->ID, 'usage', true );
	$discount_from = esc_html( get_post_meta( $coupon_data->ID, 'discount_from', true ) );
	$discount_through = esc_html( get_post_meta( $coupon_data->ID, 'discount_through', true ) );
	?>
	<style>
		#discount_meta_box label {display:inline-block;width:130px;font-weight:bold;}
		#discount_meta_box span.radio-option {margin-right: 10px;}
		#discount_meta_box div.field { line-height: 3; }
		#discount_meta_box #percentual { display: none; }
		
		label.validation-error { color:#8a1f11; background:#fbe3e4; border: solid 1px #fbc2c4; line-height: 1; padding: 5px; width: auto !important; }
	</style>
	<div class="field">
		<label>Coupon Code:</label>
		<input type="text" name="coupon_code" class="required" value="<?php echo $coupon_code; ?>" />
	</div>
	<div class="field">
		<label>Discount Value:</label>
		$<input type="text" name="discount_value" size="3" class="required number" value="<?php echo $discount_value; ?>" /> off
	</div>
	<div class="field">
		<label>Usage:</label>
		<input type="text" name="usage" value="<?php echo $usage; ?>" class="required digits" />
	</div>
	<div class="field">
		<label>Valid From:</label>
		<input type="text" name="discount_from" id="discount-from" class="required" value="<?php echo $discount_from; ?>" />
	</div>
	<div class="field">
		<label>Valid Through:</label>
		<input type="text" name="discount_through" id="discount-through" class="required" value="<?php echo $discount_through; ?>" />
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#discount-from").datepicker({minDate: 0});
			jQuery("#discount-through").datepicker({minDate: 0});
			
			jQuery("form#post").validate({
				errorClass: "validation-error"
			});
		});
	</script>
	<?php
}

function display_products_meta_box( $coupon_data ) {
	$applied_products = unserialize( get_post_meta( $coupon_data->ID, 'applied_products', true ) );
	
	global $wpdb;
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
	
	foreach(wp_get_nav_menu_items("Product Order") as $menu_item) {
		$category = $categories[$menu_item -> object_id];
		$s_sql = sprintf($post_sql, $category -> slug);
		$products = $wpdb -> get_results($wpdb -> prepare($s_sql));
		
		echo "<h4>".$category -> name."</h4><ul>";
		
		foreach($products as $post_data){ ?>
		<li>
			<input type="checkbox" name="products[]" value="<?php echo $post_data->ID; ?>" <?php if(in_array($post_data->ID, $applied_products)) echo "checked"; ?> />
			<?php echo $post_data->post_title; ?>
		</li>
		<?php
		}
		echo "</ul>";
	}
}

add_action( 'save_post', 'add_coupon_fields', 10, 2 );

function add_coupon_fields( $post_id, $post ) {
    // Check post type for movie reviews
    if ( $post->post_type == 'ecpcoupon' ) {
        // Store data in post meta table if present in post data
		if ( isset( $_POST['coupon_code'] ) && !empty($_POST['coupon_code']) ) {
            update_post_meta( $post_id, 'coupon_code', $_POST['coupon_code']);
        }
        if ( isset( $_POST['discount_value'] ) && $_POST['discount_value'] != '' && $_POST['discount_value'] > 0 ) {
            update_post_meta( $post_id, 'discount_value', $_POST['discount_value'] );
        }
		if ( isset( $_POST['usage'] ) && !empty($_POST['usage']) ) {
            update_post_meta( $post_id, 'usage', $_POST['usage']);
        }
		if ( isset( $_POST['discount_from'] ) && !empty($_POST['discount_from']) ) {
            update_post_meta( $post_id, 'discount_from', $_POST['discount_from']);
        }
		if ( isset( $_POST['discount_through'] ) && !empty($_POST['discount_through']) ) {
            update_post_meta( $post_id, 'discount_through', $_POST['discount_through']);
        }
		if ( isset( $_POST['products'] ) && !empty($_POST['products']) ) {
            update_post_meta( $post_id, 'applied_products', serialize($_POST['products']));
        }
    }
}