<?php

/** 
 * @author tefdos
 * 
 * 
 */
class ArrayUtils
{
	/*
	 * maps two or more arrays
	 * 
	 * $array - array		arrays for mapping
	 */
	public static function mapArrays($arr_1, $arr_2)
	{
		if(sizeof($arr_1) == sizeof($arr_2))
		{
			$temp = array();
			for($i = 0, $len = sizeof($arr_1); $i < $len; $i ++)
			{
				if(!empty($arr_1[$i]))
				{
					$temp[$arr_2[$i]] = $arr_1[$i];
				}
			}
			return $temp;
		}
	}
	
	/*
	 * Set values as keys
	 */
	public static function mapValuesToKeys($arr)
	{
		$temp_arr = array();
		$arr = array_filter($arr);
		foreach($arr as $k => $v)
		{
			if(!is_null($k))
			{
				$temp_arr[$v] = $k;
			}
		}
		return $temp_arr;	
	}
	
	/*
	 * Checks if id is present as key in the array
	 */
	public static function mapDiscountKeyValues($arr_k, $id)
	{
		$temp = 0;
		foreach($arr_k as $k => $v)
		{
			if($k == $id)
			{
				$temp += (int)$v;
			}
		}
		return $temp;
	}
	
	/*
	 * Maps parameters array objects id to new array key <----- stupidest comment runnerup 
	 */
	public static function mapCartUpdate($arr)
	{
		$temp = array();
		foreach($arr as $product)
		{
			$temp[$product -> id] = NULL;
		}
		return $temp;
	}
	
	public static function selectedProductsDiscounts($cart_products)
	{
		$pd = array();
		foreach($cart_products as $product)
		{			
			$product_discounts = getPostMeta($product -> id, "productDiscount");
			
			if(!is_string($product_discounts))
			{
				$product_discounts = array_filter($product_discounts);
				
				if(!empty($product_discounts))
				{
					foreach($product_discounts as $k => $discounts)
					{
						$pd[$k] += $discounts;
					}
				}
			}
		}
		
		return $pd;
	}
	
	/*
	 * Finds selected product from the cart
	 * 
	 * $cart_products - array
	 * $single_products - array				products to search for in the cart
	 */
	public static function mapSingleSelectedProduct($cart_products, $single_products)
	{
		$ret_prod = NULL;
		foreach($cart_products as $sel_product)
		{
			foreach($single_products as $product)
			{
				if($sel_product -> id == $product -> ID)
				{
					$ret_prod = $product;
					break;
					return $ret_prod;
				}
			}
		}
		
		return $ret_prod;
	}

	/*
	 * Deletes multiple keys with values from a given array
	 * 
	 * $arr - array
	 * $keys - array			keys names
	 */
	public static function unsetMultipleKeys($arr, $keys)
	{
		if(!is_array($keys) && empty($keys)) { return; }
		if(!is_array($arr) && empty($arr)) { return; }
		foreach($arr as $k => $v)
		{
			if(in_array($k, $keys))
			{
				unset($arr[$k]);
			}
		}
		return $arr;
	}
	
	public static function mergeDifferentElements($array)
	{		
        foreach ($array as $k=>$na)
            $new[$k] = serialize($na);
        $uniq = array_unique($new);
        foreach($uniq as $k=>$ser)
            $new1[$k] = unserialize($ser);
        return ($new1);

	}
}

?>