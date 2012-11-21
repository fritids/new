<?php
if(!session_id())
{
	session_start();
	/*if (isset($_SESSION['HTTP_USER_AGENT']))
	{
	    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
	    {
	        exit;
	    }
	}
	else
	{
	    $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	}*/
}
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPShoppingCart.php";
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPCoupon.php";
require_once IDG_CLASS_PATH."ShoppingCart/FormActions/class.ProductFilter.php"; 
require_once IDG_CLASS_PATH."ShoppingCart/AjaxActions/class.DeleteProduct.php";
	
class ECPShopManager
{
	protected $cart;
	protected $coupon;

	public function __construct()
	{
		$this -> cart = new ECPShoppingCart("ECP_".session_id());
	}
	
	public function addProducts($products, $quantities = null, $coupon = null)
	{
		$prod_filter = new ProductFilter($products);
		$sc_products = $prod_filter -> mapProductsDiscount($quantities);
		
		if(!is_null($coupon))
		{
			$coupon_products = array();
			$ecp_coupon = new ECPCoupon($coupon);
			$coupon_products = $ecp_coupon -> checkDiscount($sc_products);
			$this -> addCoupon($ecp_coupon);
			$this -> cart -> addProducts($coupon_products);
			$this -> cart -> addProductsQuantity($quantities);
			return;
		}
		$this -> cart -> addProducts($sc_products);
		$this -> cart -> addProductsQuantity($quantities);
	}
	
	public function addProductsQuantity($quantity)
	{
		$this -> cart -> addProductsQuantity($quantity);
	}
	
	public function deleteProduct($id)
	{
		$prod_delete = new DeleteProduct();
		$prod_delete -> delete($id);
		return $prod_delete;
	}
	
	public function addCoupon($ecp_coupon)
	{
		$this -> cart -> addCartCoupon($ecp_coupon);
		$this -> coupon = $this -> cart -> getCoupon();
	}
	
	public function getCoupon()
	{
		return $this -> cart -> getCoupon();
	}
	
	public function checkProductInCoupon($product_id)
	{
		$this -> coupon = $this -> cart -> getCoupon();
		if(!($this -> coupon)) { return false; }
		return $this -> coupon -> checkProductInCoupon($product_id);
	}
	
	public function removeProductCouponDiscount($product_id)
	{
		$this -> coupon -> removeProductCouponDiscount($product_id);
		$this -> cart -> addCartCoupon($this -> coupon);
	}
	
	public function getAllProducts()
	{
		return $this -> cart -> getAllProducts();
	}
	
	public function getProductsQuantity()
	{
		return $this -> cart -> getProductsQuantity();
	}
	
	public function emptyCart()
	{
		$this -> cart -> emptyCart();
	}
	
	public function destroyCart()
	{
		$this -> cart -> destroyCart();
	}
		
	public function cartExist()
	{
		return $this -> cart -> cartExist();
	}
	
	public function isEmpty()
	{
		return $this -> cart -> isEmpty();
	}
}

?>