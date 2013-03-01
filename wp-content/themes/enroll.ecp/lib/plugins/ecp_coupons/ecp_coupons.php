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
	?>
	<style>
		#discount_meta_box label {display:inline-block;width:130px;font-weight:bold;}
		#discount_meta_box span.radio-option {margin-right: 10px;}
		#discount_meta_box div.field { line-height: 3; }
		#discount_meta_box .percentual { display: none; }
	</style>
	<div class="field">
		<label>Discount type:</label>
		<span class="radio-option"><input type="radio" name="discount-type" value="ammount" checked /> Ammount</span>
		<span class="radio-option"><input type="radio" name="discount-type" value="percent" /> Percentual</span>
	</div>
	<div class="field">
		<label>Discount Value:</label>
		<span class="ammount">$</span><input type="text" name="discount-value" size="3" /><span class="percentual">%</span> off
	</div>
	<div class="field">
		<label>Valid From:</label>
		<input type="text" name="discount-from" />
	</div>
	<div class="field">
		<label>Valid Through:</label>
		<input type="text" name="discount-through" />
	</div>
	<div class="field">
		<label>Minimum Purchase:</label>
		$<input type="text" name="minimum-purchase" />
	</div>
	<?php
}

function display_codes_meta_box( $coupon_data ) {
	?>
	<?php
}