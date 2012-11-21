<?php
require_once IDG_CLASS_PATH."ShoppingCart/class.ECPShoppingCart.php";
/** 
 * @author tefdos
 * 
 * 
 */
class ECPProduct
{
	public $id;
	public $name;
	public $price;
	public $discount = 0;
	public $discounted_price = 0;
	public $desc;
	public $parent;
	public $taxonomy;
	public $taxonomy_slug;
	
	function __construct(){}
}

?>