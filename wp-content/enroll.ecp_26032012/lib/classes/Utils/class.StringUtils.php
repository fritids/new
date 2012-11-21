<?php

/** 
 * @author tefdos
 * 
 * 
 */
class StringUtils
{
	/*
	 * Cleans string from possible injection
	 */
	public static function cleanString($str)
	{
		if(is_string($str))
		{
			return htmlentities(strip_tags($str), ENT_QUOTES, "UTF-8");
		}
	}
	
	public static function shortenString($str, $length, $addon = "...")
	{
		if(is_string($str))
		{
			return substr($str, 0, $length).$addon;
		}
	}
	
	/*
	 * Divides the string in three parts, in format (xxx) xxx xxxxxxx
	 * 
	 * $phone - string				phone in a long string
	 * @returns- array				array of the numbers 
	 */
	public static function createUSPhone($phone)
	{
		try 
		{
			$f = substr($phone, 0, 3);
			$s = substr($phone, 3, 3);
			$l = substr($phone, 6, strlen($phone));
			
			return array($f, $s, $l);
		}
		catch (Exception $ex)
		{
			return 0;
		}
	}

	/*
	 * Divides number (string) in parts according to credit card type ($type)
	 * 
	 * $number - string					cc number
	 * $type - string					cc type - "am_ex", "visa", "master_card"
	 */
	public static function createCreditCard($number, $type)
	{
		try
		{
			switch($type)
			{
				case "am_ex":
					return self::createAMCC($number);
				break;
				default:
					return self::createCC($number);
				break;
			}
		}
		catch (Exception $ex)
		{
			return 0;
		}
	}
	
	/*
	 * Creates array of numbers according to America Express format (xxxx xxxxxx xxxxx)
	 * 
	 * $number - string					cc number
	 */
	private static function createAMCC($number)
	{
		$f = substr($number, 0, 4);
		$s = substr($number, 4, 6);
		$l = substr($number, 10, strlen($number));
		
		return array($f, $s, $l);
	}
	
	/*
	 * Creates array of numbers according to America Express format (xxxx xxxxxx xxxxx)
	 * 
	 * $number - string					cc number
	 */
	private static function createCC($number)
	{
		$f = substr($number, 0, 4);
		$s = substr($number, 4, 4);
		$t = substr($number, 8, 4);
		$l = substr($number, 12, strlen($number));
		
		return array($f, $s, $l);
	}
	
}

?>