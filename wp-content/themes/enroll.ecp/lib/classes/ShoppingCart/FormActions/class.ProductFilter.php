<?php
require_once IDG_CLASS_PATH."Utils/class.ArrayUtils.php";
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPProduct.php";
/** 
 * @author tefdos
 * 
 * 
 */
class ProductFilter
{
	private $_products;
	
	function __construct($array, $map_type = "default")
	{	
		if($map_type == "default")
		{
			//$this -> _products = ArrayUtils::mapArrays($array[0], $array[1]);
			$this -> _products = ArrayUtils::mapValuesToKeys($array);
		}
		elseif($map_type == "delete_product")
		{
			$this -> _products = ArrayUtils::mapCartUpdate($array);
		}
		elseif($map_type == "single_product_selection")
		{
			$this -> _products = ArrayUtils::mapSingleSelectedProduct($array[0], $array[1]);
		}
	}
	
	/*
	 * gets all discounts for a every product
	 */
	public function mapProductsDiscount($quantities)
	{
		$products = array();
		$product_ids = array_keys($this -> _products);
		foreach($product_ids as $id)
		{
			$product = new ECPProduct();
			$product_price = getPostMeta($id, "ProductPrice");
			$discount = $this -> _findDiscountedProductPrice($id, $product_ids);
			$post = get_post($id);
			$product -> id = $id;
			$product -> price = max(0, $product_price - $discount);
			$product -> name = $post -> post_title;
			$product -> desc = $post -> post_content;
			$product_tax = wp_get_post_terms($id, "ecp-products");
			$product -> taxonomy = $product_tax[0] -> name;
			$product -> parent = $product_tax[0] -> parent;
			$product -> taxonomy_slug = $product_tax[0] -> slug;
			
			if(strpos($product -> taxonomy_slug, "essay") !== FALSE)
			{
				$product -> is_essay = TRUE;
			}
			
			if(!is_null($quantities))
			{	
				$product -> quantity = $quantities -> $id -> quantity;
				$product -> full_price = (int)$product -> quantity * (int)$product -> price;
			}
			else
			{
				$product -> full_price = $product -> price;
			}
			array_push($products, $product);
		}
		
		return $products;
	}
	
	/*
	 * DEPRECATED
	 * Gets discount relationship of a product with other selected products
	 * 
	 * $array - array					consists of all products in cart 
	 */
	public function mapSingleProductDiscount($cart_products)
	{
		$product = new ECPProduct();
		if(!is_null($this -> _products))
		{		
			$product_meta = getECPProductMeta($this -> _products -> ID);
			$product -> id = $this -> _products -> ID;
			$product -> name = $this -> _products -> post_title;
			$product -> price = $product_meta["price"];
			$product -> desc = $this -> _products -> post_content;

			foreach($cart_products as $cart_product)
			{
				if($cart_product -> id == $product -> id)
				{
					$product -> discounted_price = $product_meta["price"] - $cart_product -> price;
					$product -> price = $cart_product -> price;
				}
				/*if(array_key_exists($cart_product -> id, $product_meta["discount"]) && !empty($product_meta["discount"][$cart_product -> id]))
				{
					$product -> discounted_price += $product_meta["discount"][$cart_product -> id];
				}*/
			}
		}
		else
		{
			$product -> id = 0;
			$product -> name = "";
			$product -> price = 0;
			$product -> desc = "";
		}
		return $product;
	}
	
	/*
	 * Because of lack of creativity and real data structure, we have to filter out essay categories
	 * 
	 * $slug - string
	 */
	public static function slugFilter($slug)
	{
		if(strpos($slug, "essay") !== false)
		{
			return "essay";
		}
		
		return $slug;
	}
	
	/*
	 * Gets every discount for the product, if exists for a selected product
	 * 
	 * $id - array							id of product for which the discount is being calculated
	 * $product_ids - array					array of selected products
	 */
	private function _findDiscountedProductPrice($id, $product_ids)
	{
		$dis = 0;
		foreach($product_ids as $pid)
		{
			if($pid != $id)
			{
				$product_meta = getECPProductMeta($pid);
				if(in_array($id, array_keys($product_meta["discount"])))
				{
					$dis += $product_meta["discount"][$id];
				}
			}
		}
		return $dis;
	}
}

?>