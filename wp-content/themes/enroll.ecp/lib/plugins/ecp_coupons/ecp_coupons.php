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
	
    add_meta_box( 'codes_meta_box',
        'Coupon Codes',
        'display_codes_meta_box',
        'ecpcoupon', 'normal', 'high'
    );
	
	wp_enqueue_style('discount_meta_box', plugin_dir_url( __FILE__ ) . '/css/styles.css');
    //wp_enqueue_script('your-meta-box', $base_url . '/meta-box.js', array('jquery'), null, true);
}

function display_discount_meta_box( $coupon_data ) {
	$discount_type = get_post_meta( $coupon_data->ID, 'discount_type', true );
	$discount_value = esc_html( get_post_meta( $coupon_data->ID, 'discount_value', true ) );
	$discount_from = esc_html( get_post_meta( $coupon_data->ID, 'discount_from', true ) );
	$discount_through = esc_html( get_post_meta( $coupon_data->ID, 'discount_through', true ) );
	?>
	<style>
		#discount_meta_box label {display:inline-block;width:130px;font-weight:bold;}
		#discount_meta_box span.radio-option {margin-right: 10px;}
		#discount_meta_box div.field { line-height: 3; }
		#discount_meta_box #percentual { display: none; }
	</style>
	<div class="field">
		<label>Discount type:</label>
		<span class="radio-option"><input type="radio" name="discount_type" id="discount-type-ammount" value="ammount" checked /> Ammount</span>
		<span class="radio-option"><input type="radio" name="discount_type" id="discount-type-percent" value="percent" <?php checked("percent", $discount_type) ?> /> Percentual</span>
	</div>
	<div class="field">
		<label>Discount Value:</label>
		<span id="ammount-sign" id="">$</span><input type="text" name="discount_value" size="3" value="<?php echo $discount_value; ?>" /><span id="percentual-sign">%</span> off
	</div>
	<div class="field">
		<label>Valid From:</label>
		<input type="text" name="discount_from" id="discount-from" value="<?php echo $discount_from; ?>" />
	</div>
	<div class="field">
		<label>Valid Through:</label>
		<input type="text" name="discount_through" id="discount-through" value="<?php echo $discount_through; ?>" />
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#discount-from").datepicker({minDate: 0});
			jQuery("#discount-through").datepicker({minDate: 0});
			
			choose_discount();
			jQuery("#discount-type-ammount").click(function() { choose_discount() });
			jQuery("#discount-type-percent").click(function() { choose_discount() });
			
			function choose_discount() {
				if(jQuery("#discount-type-ammount").is(':checked')) {
					jQuery("#ammount-sign").show();
					jQuery("#percentual-sign").hide();
				} else if(jQuery("#discount-type-percent").is(':checked')) {
					jQuery("#percentual-sign").show();
					jQuery("#ammount-sign").hide();
				}
			}
		});
	</script>
	<?php
}

function display_codes_meta_box( $coupon_data ) {
	$coupon_codes = unserialize( get_post_meta( $coupon_data->ID, 'coupon_code', true ) );
	?>
	<table>
		<tr>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[0]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[1]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[2]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[3]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[4]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[5]; ?>" /></td>
		</tr>
		<tr>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[6]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[7]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[8]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[9]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[10]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[11]; ?>" /></td>
		</tr>
		<tr>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[12]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[13]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[14]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[15]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[16]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[17]; ?>" /></td>
		</tr>
		<tr>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[18]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[19]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[20]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[21]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[22]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[23]; ?>" /></td>
		</tr>
		<tr>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[24]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[25]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[26]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[27]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[28]; ?>" /></td>
			<td><input type="text" name="coupon_code[]" value="<?php echo $coupon_codes[29]; ?>" /></td>
		</tr>
	</table>
	<?php
}

add_action( 'save_post', 'add_coupon_fields', 10, 2 );

function add_coupon_fields( $post_id, $post ) {
    // Check post type for movie reviews
    if ( $post->post_type == 'ecpcoupon' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['discount_type'] ) && $_POST['discount_type'] != '' ) {
            update_post_meta( $post_id, 'discount_type', $_POST['discount_type'] );
        }
        if ( isset( $_POST['discount_value'] ) && $_POST['discount_value'] != '' && $_POST['discount_value'] > 0 ) {
            update_post_meta( $post_id, 'discount_value', $_POST['discount_value'] );
        }
		if ( isset( $_POST['discount_from'] ) && !empty($_POST['discount_from']) ) {
            update_post_meta( $post_id, 'discount_from', $_POST['discount_from']);
        }
		if ( isset( $_POST['discount_through'] ) && !empty($_POST['discount_through']) ) {
            update_post_meta( $post_id, 'discount_through', $_POST['discount_through']);
        }
		if ( isset( $_POST['coupon_code'] ) && !empty($_POST['coupon_code']) ) {
            update_post_meta( $post_id, 'coupon_code', serialize($_POST['coupon_code']));
        }
    }
}