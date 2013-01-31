<?php
class Util
{
	public static $counter = 0;

	public static function curPageURL()
	{
		$pageURL = 'http';
		if($_SERVER["HTTPS"] == "on")
		{
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}
		else
		{
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	public static function generateDropDown($arr, $name, $selected = null)
	{
		$out = "<select name='" . $name . "'  id='" . $name . "'>";
		foreach($arr as $val)
		{
			if($selected == $val)
			{
				$out .= "<option selected='selected' value='" . $val . "'>" . $val . "</option>";
			}
			else
			{
				$out .= "<option value='" . $val . "'>" . $val . "</option>";
			}
		
		}
		$out .= "</select>";
		return $out;
	}

	public static function AddReplaceQueryVars($query, $key, $value)
	{
		if(strpos($query, $key) !== false)
		{
			$ret = array();
			$ex = explode("&", $query);
			
			foreach($ex as $pair)
			{
				if(strpos($pair, $key) !== false)
				{
					$pair = $key."=".$value;
				}
				array_push($ret, $pair);
			}
			return implode("&", $ret);
		}
		else
		{
			return $query."&{$key}={$value}";
		}
	}
	
	public static function getCounter()
	{
		Util::$counter++;
		return Util::$counter;
	}
}
?>