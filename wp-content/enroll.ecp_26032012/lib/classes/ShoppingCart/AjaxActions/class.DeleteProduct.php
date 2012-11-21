<?php

require_once IDG_CLASS_PATH."Templator/class.Templator.php"; 

class DeleteProduct extends ECPShopManager
{
	public function __construct(){
		parent::__construct();
	}
	
	// PUBLIC METHODS
	
	/*
	 * Delete product from cart and calculate the discount of products
	 */
	public function delete($id)
	{
		
		if($this -> _checkForCouponDiscount($id))
		{
			$this -> removeProductCouponDiscount($id);
		}
		$this -> cart -> deleteProduct($id);
		$session_products = $this -> getAllProducts();
		$prod_filter = new ProductFilter($session_products, "delete_product");
		$sc_products = $prod_filter -> mapProductsDiscount();
		
		$this -> cart -> updateCart($sc_products);
	}
	
	public function getTemplate()
	{
		return Templator::getCartTemplate("template_cart", $this -> cart, "sections/");
	}
	
	// PRIVATE METHODS
	
	private function _checkForCouponDiscount($id)
	{
		return $this -> checkProductInCoupon($id);
	}
}
?>