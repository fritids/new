<?php
class ConnectionHandler
{
	private $_url;
	private $_query;
	
	public function __construct($url = "")
	{
		$this -> _url = $url;	
	}
	
	public function debug_curl($curl, $data=null)
	{
		static $buffer = '';
		
		if(is_null($curl))
		{
			$r = $buffer;
			$buffer = '';
		}
		else
		{
			$buffer .= $data;
			fb(strlen($data));
		}
	}
	
	/**
	 * 
	 * Sends data to remote location using different methods
	 */
	public function post_data()
	{
		if(function_exists("curl_exec"))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
			curl_setopt($ch, CURLOPT_URL, $this -> _url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this -> _query); 
			
			/*curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(&$this, 'debug_curl'));
			curl_setopt($ch, CURLOPT_WRITEFUNCTION, array(&$this, 'debug_curl'));
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);*/
		
			$data = curl_exec($ch);
			
			//var_dump($this -> _query);
			//var_dump($data);
			curl_close($ch);
			return $data; 
		}
		elseif(ini_get("allow_url_fopen"))
		{
			$options = array(
				"http" => array(
					"method" => "POST",
					"header" => "Content-type: application/x-www-form-urlencoded",
					"content" => $this -> _query
				)
			);
			
			$context = stream_context_create($options);
			
			return file_get_contents($this -> _url, false, $context);
		}
		else
		{
			$params = parse_url($this -> _url);
			$fp = fsockopen($params["host"], isset($params['port']) ? $params['port'] : 80, $errno, $errstr, 30);
			if(!$fp)
			{
				return false;
			}
			else
			{
				$out = "POST ".$params['path']." HTTP/1.1\r\n";
				$out.= "Host: ".$params['host']."\r\n";
				$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
				$out.= "Content-Length: ".strlen($this -> _query)."\r\n";
				$out.= "Connection: Close\r\n\r\n";
				if(isset($this -> _query)) $out.= $this -> _query;
				fwrite($fp, $out);
				while(!feof($fp)) $data = fgets($fp); 
				fclose($fp);
				return $data;
			}
		}
	}
	
	/**
	 * 
	 * Gets remote data, with different ways
	 */
	public function fetch_data()
	{
		if(function_exists("curl_exec"))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
			curl_setopt($ch, CURLOPT_URL, $this -> _url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
			$data = curl_exec($ch);
			curl_close($ch);
			return $data; 
		}
		elseif(ini_get('allow_url_fopen'))
		{
			return file_get_contents($this -> _url);
		}
		else
		{
			$params = parse_url($this -> _url);
			$fp = fsockopen($params["host"], isset($params['port']) ? $params['port'] : 80, $errno, $errstr, 30);
			if(!$fp)
			{
				return false;
			}
			else
			{
				$out = "POST ".$params['path']." HTTP/1.1\r\n";
				$out.= "Host: ".$params['host']."\r\n";
				$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
				$out.= "Content-Length: ".strlen($params["query"])."\r\n";
				$out.= "Connection: Close\r\n\r\n";
				if(isset($params["query"])) $out.= $params["query"];
				fwrite($fp, $out);
				while(!feof($fp)) $data = fgets($fp); 
				fclose($fp);
				return $data;
			}
		}
	}
	
	/**
	 * @param field_type $url
	 */
	public function set_url($url)
	{
		$this -> _url = $url;
	}
	/**
	 * @return the $url
	 */
	public function get_url()
	{
		return $this -> _url;
	}
	/**
	 * @return the $_query
	 */
	public function get_query()
	{
		return $this -> _query;
	}

	/**
	 * @param field_type $_query
	 */
	public function set_query($_query)
	{
		$this -> _query = $_query;
	}
}
?>