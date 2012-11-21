<?php
if(!session_id())
{
	session_start();
	/*if (isset($_SESSION['HTTP_USER_AGENT']))
	{
	    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
	    {
	        exit;
	    }
	}
	else
	{
	    $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	}*/
	
}
/** 
 * @author tefdos
 * 
 * 
 */

require_once IDG_CLASS_PATH."Utils/class.StringUtils.php";
class SessionUtils
{

	private $_data_name;
	
	function __construct($name)
	{
		$timeout = 15000;
		$this -> _data_name = $name."_".session_id();
		if(!isset($_SESSION[$this -> _data_name]) || is_null($_SESSION[$this -> _data_name]))
		{
			$_SESSION[$this -> _data_name] = array();
		}  
		$this -> setTimeOut($timeout);	
	}
	
	/*
	 * Sets session timeout
	 * 
	 * $timeout - integer			number of seconds the session will be active
	 */
	public function setTimeOut($timeout)
	{
		if(isset($_SESSION[$this -> _data_name]['timeout']) ) 
		{
			$session_life = time() - $_SESSION[$this -> _data_name]['timeout'];
			if($session_life > $timeout)
		        { session_destroy(); }
		}
		$_SESSION[$this -> _data_name]['timeout'] = time();
	}
	
	/*
	 * Add data to session object
	 */
	public function addData($data)
	{
		try 
		{
			foreach($data as $k => $v)
			{
				$v = StringUtils::cleanString($v);
				$_SESSION[$this -> _data_name][$k] = base64_encode(serialize($v));
			}
		}
		catch(Exception $ex)
		{
			return 0;
		}
	}
	
	/*
	 * Update user data in session 
	 */
	public function updateData($data)
	{
		foreach($data as $k => $v)
		{
			$v = StringUtils::cleanString($v);
			$_SESSION[$this -> _data_name][$k] = base64_encode(serialize($v));
		}
	}
	
	/*
	 * Returns individual data from session with specified key 
	 */
	public function getUserData($key)
	{
		return unserialize(base64_decode($_SESSION[$this -> _data_name][$key]));
	}
	
	/*
	 * returns session object with all data
	 */
	public function getAllUserData()
	{
		$temp_uns = array();
		foreach($_SESSION[$this -> _data_name] as $k => $single_data)
		{
			$prod_uns = unserialize(base64_decode($single_data));
			$temp_uns[$k] = $prod_uns;
		}
		return $temp_uns;
	}
	
	public function cleanUp($data)
	{
		if(is_array($data))
		{
			foreach($data as $k)
			{
				unset($_SESSION[$this -> _data_name][$k]);
			}
		}
	}
	
	public function emptyUserData()
	{
		$_SESSION[$this -> _data_name] = array();
	}
	
	/*
	 * Completly destroys the session data
	 */
	public function destroyUserData()
	{
		unset($_SESSION[$this -> _data_name]);
	}
	
	/*
	 * Checks if session with _data_name is empty
	 */
	public function isEmpty()
	{
		return empty($_SESSION[$this -> _data_name]);
	}
}

?>