<?php
require_once IDG_CLASS_PATH."Templator/class.Templator.php";

class Essays
{
	private $_template_data;
	
	public function __construct($values)
	{
		$this -> _getEssayData($values);
	}
	
	/*
	 * Gets all data needed to populate the template ( or echo directly )
	 */
	private function _getEssayData($values)
	{
		global $wpdb;
		$total_discount = 0;
        $essay_id = $wpdb -> get_var( $wpdb -> prepare( "SELECT ID FROM {$wpdb -> posts} WHERE post_title = %s AND post_type='ECPProduct'", $values -> value));
        if($essay_id)
        {
	        $this -> _template_data = getECPProductMeta($essay_id);
	        foreach($values -> selectedProducts as $selected_id)
	        {
	        	$product_meta = array_filter(getPostMeta($selected_id, "productDiscount"));
	        	$total_discount += ArrayUtils::mapDiscountKeyValues($product_meta, $essay_id);
	        }
        	$this -> _template_data["discount_ammount"] = $total_discount;
        }
        else
        {
        	$this -> _template_data = array("id" => NULL,
											"price" => 0,
											"discount" => 0,
        									"discount_ammount" => 0);
        }
	}
	
	/*
	 * Creates the appropriate template
	 */
	public function getTemplate()
	{
		return Templator::getCartTemplate("essay", $this -> _template_data, "essay/");
	}
}
?>