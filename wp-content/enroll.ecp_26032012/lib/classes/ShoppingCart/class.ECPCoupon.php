<?php
require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';
/** 
 * @author tefdos
 * 
 * 
 */
class ECPCoupon
{
	
	private $_data;
	private $_ecp_coupon;
	private $_err;
	private $_coupon_products;
	private $_coupon_discount_price;
	private $_coupon_id;
	
	public $price;
	public $code;

	public function __construct($coupon)
	{
		$this -> _err = new WP_Error();
		$this -> _err -> add("coupon_not_valid", "The coupon code you entered is not valid.");
		$this -> _err -> add("coupon_has_no_products", "The coupon code you entered is not valid.");
		$this -> _err -> add("coupon_can't_be_updated", "The coupon can't be updated.");
		$this -> _ecp_coupon = ecpCoupons::getInstance();
		$this -> price = 0;
		$this -> code = $coupon;
		$this -> _data = $this -> _validateCoupon($coupon);
	}
	
	private function _validateCoupon($coupon)
	{
		$coupon_data = $this -> _ecp_coupon -> checkCouponExistObject($coupon);
		if(!empty($coupon_data))
		{ 
			$this -> _coupon_id = $coupon_data[0][0];
			$coupon_price = $coupon_data[0][1];
			$products = $this -> _getAllProductsWithCoupon($this -> _coupon_id);
			if(is_string($products))
			{
				return $products;
			}
			$coupon_details = array($products, $coupon_price);
			return $coupon_details;
		}

		return $this -> _err -> get_error_message("coupon_not_valid");
	}
	
	private function _getAllProductsWithCoupon($coupon_id)
	{
		$coupon_data = $this -> _ecp_coupon -> getAllProductsByCouponIDObject($coupon_id);
		if(!empty($coupon_data))
		{
			return $coupon_data;
		}
		
		return $this -> _err -> get_error_message("coupon_has_no_products");
	}
	
	// PUBLIC METHODS
	
	public function checkDiscount($products)
	{
		if(is_array($this -> _data))
		{
			$this -> _coupon_products = array();
			foreach($this -> _data[0] as $k => $v)
			{
				array_push($this -> _coupon_products, $v -> product_id);
			}
			$this -> _coupon_discount_price = $this -> _data[1];
			$intersect = array_intersect($this -> _coupon_products, $products);
			foreach ($intersect as $k => $v)
			{
				$this -> price -= $this -> _coupon_discount_price;
			}
			return $this -> addCouponDiscountToProduct($products);
		}
	}
	
	public function addCouponDiscountToProduct($products)
	{
		$product_arr = array();
		foreach($products as $product)
		{
			foreach($this -> _coupon_products as $coupon_products)
			{
				if($coupon_products == $product -> id)
				{
					//$product -> price = $product -> price - $this -> _coupon_discount_price;
					$product -> discounted_price = $product -> price - $this -> _coupon_discount_price;
				}
			}
			array_push($product_arr, $product);
		}
		return $product_arr;
	}
	
	public function checkProductInCoupon($product_id)
	{
		if(in_array($product_id, $this -> _coupon_products)) { return true; }
		return false;
	}
	
	public function removeProductCouponDiscount($product_id)
	{
		if(in_array($product_id, $this -> _coupon_products))
		{
			$this -> price += $this -> _coupon_discount_price;
		}
	}
	
	public function decreaseCouponUsage()
	{
		try
		{
			$this -> _ecp_coupon -> decreaseCouponUsage($this -> _coupon_id);
		}
		catch(Exception $ex)
		{
			echo $this -> _err -> get_error_message("coupon_can't_be_updated");
		}		
	}
	
	// GETTERS AND SETTERS
	
	/*
	 * Return all products in coupon
	 */
	public function getCouponProducts()
	{
		return $this -> _coupon_products;
	}

	/*
	 * Return id of coupon
	 */
	public function getCouponId()
	{
		return $this -> _coupon_id;
	}
	
	/*
	 * Gets the coupons template
	 */
	public function getPrice()
	{
		return $this -> _data;
	}
}

?>