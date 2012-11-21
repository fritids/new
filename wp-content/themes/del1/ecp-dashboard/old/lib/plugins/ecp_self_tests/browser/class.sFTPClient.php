<?php

require_once("class.Parser.php");

class sFTPClient
{
	private $my_connection;
	private $my_sftp;
	
	public $my_parent_dir;
	private $my_home_dir;
	

	public function __construct($ftp_server, $ftp_port, $username, $password)
	{
		$this->my_connection = ssh2_connect($ftp_server, $ftp_port);
		if(ssh2_auth_password($this->my_connection, $username, $password))
		{
			$this->my_sftp = ssh2_sftp($this->my_connection);
		}
		else
		{
			echo "Connection failed";
		}
	}
	
	public function changeDirectory($param)
	{
		echo $param;
	}
	
	public function getDetailedDirectoryStructure($current_dir)
	{
		$temp_file = $current_dir;
		
		if($current_dir == "")
		{
			$temp_file = ".";
		}
		
		$file_without_slash = substr($temp_file, 1);
		$this->my_home_dir = ssh2_sftp_realpath($this->my_sftp, $file_without_slash);
		
		
		$sftp = $this->my_sftp;
		
		$dir = "ssh2.sftp://".$sftp.$this->my_home_dir;  
		
		$tempArray = array();
		//$tempArray[] = $this->my_home_dir;
		$handle = opendir($dir);
		// List all the files
		if(substr($dir, -1) == "/")
		{
			$dir = substr($dir, 0, -1);
		}
		while (false !== ($file = readdir($handle))) 
		{
			if (substr("$file", 0, 1) != ".")
			{
				//$tempArray[] = filetype($dir."/".$file);
				//$file_arr = explode('.', $file);
				//if(in_array('flv', $file_arr) || count($file_arr) > 1)
				//$file_without_slash = substr($file, 1);
				
				if(filetype($dir."/".$file) != "dir")
				{
					$tempArray[] = array("f", $file, $this->my_home_dir."/".$file);
				} 
				else 
				{
					//$tempArray[] = array("d", $file, ssh2_sftp_realpath($this->my_sftp, $file));
					$tempArray[] = array("d", $file, $this->my_home_dir."/".$file);
				}
			}
		}
		closedir($handle);
		return $tempArray;
	}
	
	public function getPWD($current_file)
	{
		$parent = ssh2_sftp_realpath($this->my_sftp, $current_file."/..");
		return $parent;
	}
	
	public function scanFilesystem($current_dir) 
	{
		$temp_file = $current_dir;
		
		if($current_dir == "")
		{
			$temp_file = ".";
		}
		
		$this->my_home_dir = ssh2_sftp_realpath($this->my_sftp, $temp_file);
		
		$sftp = $this->my_sftp;
		
		$dir = "ssh2.sftp://".$sftp.$this->my_home_dir;  
		
		$tempArray = array();
		//$tempArray[] = $this->my_home_dir;
		$handle = opendir($dir);
		// List all the files
		if(substr($dir, -1) == "/")
		{
			$dir = substr($dir, 0, -1);
		}
		while (false !== ($file = readdir($handle))) 
		{
			if (substr("$file", 0, 1) != ".")
			{
				//$tempArray[] = filetype($dir."/".$file);
				//$file_arr = explode('.', $file);
				//if(in_array('flv', $file_arr) || count($file_arr) > 1)
				//$file_without_slash = substr($file, 1);
				
				if(filetype($dir."/".$file) != "dir")
				{
					$tempArray[] = array("f", $file, $this->my_home_dir."/".$file);
				} 
				else 
				{
					//$tempArray[] = array("d", $file, ssh2_sftp_realpath($this->my_sftp, $file));
					$tempArray[] = array("d", $file, $this->my_home_dir."/".$file);
				}
			}
		}
		closedir($handle);
		return $tempArray;
    }
}

?>