<?php
class ECPShoppingCart
{
	private $_session;
	private $_CART_NAME;

	public function __construct($name)
	{
		$this -> _CART_NAME = $name;
		//$this -> _session = $_SESSION[$this -> _CART_NAME];
		if(!isset($_SESSION[$this -> _CART_NAME]) || is_null($_SESSION[$this -> _CART_NAME]))
		{
			$_SESSION[$this -> _CART_NAME] = array();
		}
	}

	/*
	 * Adds products in cart
	 */	
	public function addProducts($products = array())
	{
		try 
		{
			foreach($products as $product)
			{
				$_SESSION[$this -> _CART_NAME]["products"][$product -> id] = base64_encode(serialize($product));
			}
		}
		catch(Exception $ex)
		{
			return 0;
		}
	}
	
	/*
	 * updates price for array of products
	 */
	public function updateCart($new_prod)
	{
		foreach($new_prod as $prod)
		{
			$product = $this -> getProduct($prod -> id);
			$product -> price = $prod -> price;
			$_SESSION[$this -> _CART_NAME]["products"][$prod -> id] = base64_encode(serialize($product));
		}
	}
	
	/*
	 * Add discount coupon to cart - session
	 */
	public function addCartCoupon($ecp_coupon)
	{
		$_SESSION[$this -> _CART_NAME]["coupon"] = base64_encode(serialize($ecp_coupon));
	}
	
	/**
	 * 
	 * Adds product quantities to the cart
	 */
	public function addProductsQuantity($quantity)
	{
		$_SESSION[$this -> _CART_NAME]["quantities"] = base64_encode(serialize($quantity));
	}
	
	/*
	 * Removes product in cart
	 */
	public function deleteProduct($id)
	{
		unset($_SESSION[$this -> _CART_NAME]["products"][$id]);
		if($_SESSION[$this -> _CART_NAME]["quantities"])
		{
			$qid = (int) $id;
			$quantities = $this -> getProductsQuantity();
			$q = json_decode(json_encode($quantities));
			unset($q -> $id);			
			$this -> addProductsQuantity($q);
		}
	}
	
	/*
	 * Deletes products in cart
	 */
	public function emptyCart()
	{
		$_SESSION[$this -> _CART_NAME] = array();
	}
	
	/*
	 * Completly remove the current session 
	 */
	public function destroyCart()
	{
		unset($_SESSION[$this -> _CART_NAME]);
	}
	
	/*
	 * Gets all products from cart
	 */
	public function getAllProducts()
	{
		$temp_uns = array();
		foreach($_SESSION[$this -> _CART_NAME]["products"] as $product)
		{
			$prod_uns = unserialize(base64_decode($product));
			$temp_uns[] = $prod_uns;
		}
		return $temp_uns;
	}
	
	/*
	 * Gets product by id from cart
	 */
	public function getProduct($id)
	{
		return unserialize(base64_decode($_SESSION[$this -> _CART_NAME]["products"][$id]));
	}
	
	/**
	 * Gets all products quantities from cart
	 */
	public function getProductsQuantity()
	{
		return unserialize(base64_decode($_SESSION[$this -> _CART_NAME]["quantities"]));
	}
	
	/*
	 * Gets coupon from cart
	 */
	public function getCoupon()
	{
		if(!is_null(base64_decode($_SESSION[$this -> _CART_NAME]["coupon"])))
		{
			return unserialize(base64_decode($_SESSION[$this -> _CART_NAME]["coupon"]));
		}		
	}
	
	/*
	 * Returns total price of products in the cart
	 */
	public function getTotalPrice()
	{
		$total_price = 0;
		foreach ($_SESSION[$this -> _CART_NAME]["products"] as $product) 
		{
			if($product -> discounted_price)
			{
				$total_price += $product -> discounted_price;
			}
			else 
			{
				$total_price += $product -> price;
			}
		}
		return $total_price;
	}
	
	public function cartExist()
	{
		if(!isset($_SESSION[$this -> _CART_NAME]) || is_null($_SESSION[$this -> _CART_NAME]))
		{
			return false;
		}
		return true;
	}
		
	/*
	 * Checks if cart is empty
	 */
	public function isEmpty()
	{
		return empty($_SESSION[$this -> _CART_NAME]);
	}

}

?>