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
	public $original_price = 0;
	public $discount = 0;
	public $discounted_price = 0;
	public $desc;
	public $parent;
	public $taxonomy;
	public $taxonomy_slug;
	public $quantity = 1;
	public $is_essay = FALSE;
	public $full_price = 0;
	
	function __construct(){}
}

?>