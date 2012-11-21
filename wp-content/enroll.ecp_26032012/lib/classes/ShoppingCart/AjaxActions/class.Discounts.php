<?php
class Discounts
{
	private $_template_data;
	
	public function __construct($values)
	{
		$this -> _template_data = $this -> _getDiscountsData($values);
	}
	
	/*
	 * Gets discount data for a given ECPProduct
	 */
	private function _getDiscountsData($values)
	{
		return getPostMeta($values, "productDiscount");
	}
	
	/*
	 * Gets the discounts template
	 */
	public function getTemplate()
	{
		return $this -> _template_data;
	}
}
?>