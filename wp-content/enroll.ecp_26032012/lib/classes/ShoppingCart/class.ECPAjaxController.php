<?php
require_once "AjaxActions/class.Essays.php";
require_once "AjaxActions/class.Discounts.php";
require_once "AjaxActions/class.CCTypes.php";
require_once "AjaxActions/class.Coupons.php";
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPShopManager.php";

class ECPAjaxController
{
	public static function Controller($method, $values)
	{
		
		switch ($method)
		{
			case "ecp_essays":
				return new Essays($values);
			break;
			case "ecp_discounts":
				return new Discounts($values);
			break;
			case "ecp_delete_product":
				$ecp_cart = new ECPShopManager();
				$del_prod = $ecp_cart -> deleteProduct($values);
				return $del_prod;
			break;
			case "ecp_cc_types":
				return new CCTypes($values);
			break;
			case "ecp_check_coupon":
				return new Coupons($values);
			break;
		}
	}
}
?>