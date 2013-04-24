<?php
	$index = "wp-content";
	$path = dirname(__FILE__);
	$path = substr($path, 0, strpos($path,$index));
	require_once  $path."wp-load.php";
	
	$post_sql = "SELECT post_id
				FROM {$wpdb -> postmeta}
				WHERE {$wpdb -> postmeta}.meta_key = 'coupon_code'
				AND {$wpdb -> postmeta}.meta_value = '%s'";
				
	$s_sql = sprintf($post_sql, $_POST['code']);
	
	$result = $wpdb -> get_results($wpdb -> prepare($s_sql));
	
	if(isset($result[0])) {
	
		$post_sql = "SELECT ID, post_title, post_name
					FROM {$wpdb -> posts} post
					LEFT JOIN {$wpdb -> term_relationships} term ON(post.ID = term.object_id)
					WHERE post.post_type = 'ecpcoupon'
					AND post.ID = '%s'
					AND post.post_status = 'publish';";
					
		$s_sql = sprintf($post_sql, $result[0]->post_id);
		
		$coupon = $wpdb -> get_results($wpdb -> prepare($s_sql));
		
		if(isset($coupon[0])) {
			$discount_from = get_post_meta($coupon[0]->ID, 'discount_from', true);
			$discount_through = get_post_meta($coupon[0]->ID, 'discount_through', true);
			$usage = get_post_meta($coupon[0]->ID, 'usage', true);
			
			if(strtotime($discount_from) <= time() &&
					strtotime($discount_through) >= time() &&
					$usage > 0) {
				
				$discount['id'] = $coupon[0]->ID;
				$discount['name'] = $coupon[0]->post_title;
				$discount['value'] = get_post_meta($coupon[0]->ID, 'discount_value', true);
				$discount['products'] = unserialize(get_post_meta($coupon[0]->ID, 'applied_products', true));
				
				echo json_encode($discount);
				
			} else {
				echo json_encode(false);
			}
		} else {
			echo json_encode(false);
		}
	} else {
		echo json_encode(false);
	}