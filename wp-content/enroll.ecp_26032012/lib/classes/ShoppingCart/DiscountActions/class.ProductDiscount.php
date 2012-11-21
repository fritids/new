<?php
/** 
 * @author tefdos
 * 
 * 
 */
class ProductDiscount
{
	
	public $ids;
	
	function __construct()
	{
		$this -> ids = $this -> _getAllECPProducts();
	}
	
	/*
	 * Generates array of discount arrays for each product
	 */
	public function mapDiscounts()
	{
		$temp_arr = array();
		foreach($this -> ids as $prod_id)
		{
			$post_meta = getPostMeta($prod_id -> ID, "productDiscount");
			if(empty($post_meta)){ $post_meta = array(); };
			$temp_arr[$prod_id -> ID] = array("discounts" => array_filter($post_meta));
		}
		return $temp_arr;
	}
	
	/*
	 * Generates array of prices for each product
	 */
	public function mapPrices()
	{
		$temp_arr = array();
		foreach($this -> ids as $prod_id)
		{
			$temp_arr[$prod_id -> ID] = array("price" => getPostMeta($prod_id -> ID, "ProductPrice"));
		}
		return $temp_arr;
	}
	
	/*
	 * Changes the current prices of the products
	 * 
	 * $prices - array				all basic prices of the products
	 * $cart_products - array		shopping cart products
	 */
	public function mapCurrentPrices($prices, $generic_discounts)
	{
		foreach($generic_discounts as $dk => $discount)
		{
			$prices[$dk]["price"] = (($prices[$dk]["price"] - (int)$discount)) > 0 ? $prices[$dk]["price"] - (int)$discount : 0;
			
		}
		return $prices;
	}
	
	/*
	 * Returns every ECP product from database 
	 */
	private function _getAllECPProducts()
	{
		global $wpdb;
		$post_sql = "SELECT ID
					FROM {$wpdb -> posts}
					LEFT JOIN {$wpdb -> term_relationships} ON({$wpdb -> posts}.ID = {$wpdb -> term_relationships}.object_id)
					LEFT JOIN {$wpdb -> term_taxonomy} ON({$wpdb -> term_relationships}.term_taxonomy_id = {$wpdb -> term_taxonomy}.term_taxonomy_id)
					LEFT JOIN {$wpdb -> terms} ON({$wpdb -> term_taxonomy}.term_id = {$wpdb -> terms}.term_id)
					WHERE {$wpdb -> posts}.post_type = 'ECPProduct' 
					AND {$wpdb -> posts}.post_status = 'publish'
					AND {$wpdb -> term_taxonomy}.taxonomy = 'ecp-products'
					ORDER BY {$wpdb -> posts}.post_date ASC;";
		
		$results = $wpdb -> get_results($wpdb -> prepare($post_sql));
		return $results;
	}
}

?>