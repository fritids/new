<?php

/** 
 * @author tefdos
 * 
 * 
 */
class CCTypes
{
	private $_type;
	
	function __construct($type)
	{
		$this -> _type = $type;
	}
	
	public function getTemplate()
	{
		switch($this -> _type)
		{
			case "am_ex":
				$ret_arr = array();
				$ret_arr["cc_number"] = '<input type="text" class="input_long cc_num" maxlength="4"/>
									     <input type="text" class="input_long cc_num" maxlength="6" />
									     <input type="text" class="input_long cc_num" maxlength="5" />
									     <input type="hidden" value="" name="cc_number" id="cc_number" />';
				$ret_arr["ccv2"] = '<input type="text" id="cvv2" class="input_long" maxlength="4" name="cvv2"/>';
				return json_encode($ret_arr);
			break;
			default:
				$ret_arr = array();
				$ret_arr["cc_number"] = '<input type="text" class="input_long cc_num" maxlength="4"/>
									     <input type="text" class="input_long cc_num" maxlength="4" />
									     <input type="text" class="input_long cc_num" maxlength="4" />
									     <input type="text" class="input_long cc_num" maxlength="4" />
									     <input type="hidden" value="" name="cc_number" id="cc_number" />';
				$ret_arr["ccv2"] = '<input type="text" id="cvv2" class="input_long" maxlength="3" name="cvv2"/>';
				return json_encode($ret_arr);
			break;
		}
	}
}

?>