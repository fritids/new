<?php

/** 
 * @author tefdos
 * 
 * 
 */
class FormPostHandler
{
	private $_data = array();
	private $_haspostdata = FALSE;
	
	function __construct()
	{
		$this -> _haspostdata = ($_SERVER['REQUEST_METHOD'] === 'POST') ? TRUE : FALSE;
		$this -> _data["_haspostdata"] = $this -> _haspostdata;
	}
	
	/*
	 * checks if postback on form
	 */
	public function isPostback($param)
	{
		if($this -> _haspostdata)
		{
			return $this -> getval($param);
		}
	}
	
	/*
	 * Removes post element by key name
	 * 
	 * $needle - array				array of elements
	 */
	public function removePostElements($needle)
	{
		foreach($needle as $k)
		{
			unset($_POST[$k]);
		}
	}
	
	/*
	 * Getter magic method
	 */
	public function __get($name)
	{
		 if (array_key_exists($name, $this -> _data)) 
		 {
           	 return $this -> _data[$name];
         }
	}
	
	/*
	 * Finds the page's referrer  
	 */
	public function getReferrer()
	{
		return $_SERVER["REQUEST_URI"];
	}
	
	/*
	 * Redirects to a specified URI 
	 */
	public function redirect($path)
	{
		wp_redirect(get_bloginfo("url")."{$path}");
	}
	
	/**
	 * getval
	 * @param string $key
	 * @return mixed
	 */
	public function getval($key)
	{
		return (isset($_POST[$key])) ? $_POST[$key] : FALSE;
	}
}

?>