<?php

require_once IDG_CLASS_PATH.'/ShoppingCart/CouponActions/class.ecpCoupons.php';
/** 
 * @author tefdos
 * 
 * 
 */
class Coupons
{
	private $_template_data;
	private $_ecp_coupon;
	private $_err;

	function __construct($coupon)
	{
		$this -> _err = new WP_Error();
		$this -> _err -> add("coupon_not_valid", "The coupon code you entered is not valid.");
		$this -> _err -> add("coupon_has_no_products", "The coupon code you entered is not valid.");
		$this -> _ecp_coupon = ecpCoupons::getInstance();
		$this -> _template_data = $this -> _validateCoupon($coupon);
	}
	
	private function _validateCoupon($coupon)
	{
		$coupon_data = $this -> _ecp_coupon -> checkCouponExist($coupon);
		if(!empty($coupon_data))
		{ 
			$coupon_id = $coupon_data[0]["coupon_id"];
			$coupon_price = $coupon_data[0]["coupon_price"];
			$products = $this -> _getAllProductsWithCoupon($coupon_id);
			if(is_string($products))
			{
				return json_encode(array("Error" => $products));
			}
			$coupon_details = array("products" => $products, "coupon_price" => $coupon_price);
			return json_encode($coupon_details);
		}

		return json_encode(array("Error" => $this -> _err -> get_error_message("coupon_not_valid")));
	}
	
	private function _getAllProductsWithCoupon($coupon_id)
	{
		$coupon_data = $this -> _ecp_coupon -> getAllProductsByCouponID($coupon_id);
		if(!empty($coupon_data))
		{
			return $coupon_data;
		}
		
		return $this -> _err -> get_error_message("coupon_has_no_products"); 
	}
	
	
	/*
	 * Gets the coupons template
	 */
	public function getTemplate()
	{
		return $this -> _template_data;
	}
}

?>