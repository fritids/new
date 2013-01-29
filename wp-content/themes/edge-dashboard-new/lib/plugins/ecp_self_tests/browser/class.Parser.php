<?php
class Parser
{
	public function __construct(){}
	
	public function parseWinOutput($dir_arr)
	{
		$res = array();
		foreach($dir_arr as $row)
		{
			$temp = preg_split('/\s+/', $row);
			$nice_formated_element_type = array();
			if(strpos($temp[3], ".") !== 0)
			{
				if($temp[2] == "<DIR>")
				{
					array_push($nice_formated_element_type, "d", $temp[3]);	
				}
				else
				{
					array_push($nice_formated_element_type, "f", $temp[3]);
				}
				array_push($res, $nice_formated_element_type);
			}
		}
		return $res;
	}
	
	public function parseUnixOutput($dir_arr)
	{
		$res = array();
		foreach($dir_arr as $row)
		{
			$temp = preg_split('/\s+/', $row);
			$nice_formated_element_type = array();
			if(strpos($temp[0], ".") !== 0)
			{
				if(strpos($temp[0], "d") !== false)
				{
					array_push($nice_formated_element_type, "d", $temp[8]);	
				}
				else
				{
					array_push($nice_formated_element_type, "f", $temp[8]);
				}
				array_push($res, $nice_formated_element_type);
			}
		}
		return $res;
	}
}
?>