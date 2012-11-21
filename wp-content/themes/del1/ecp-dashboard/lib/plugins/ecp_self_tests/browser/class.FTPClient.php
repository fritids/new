<?php
require_once("class.Parser.php");

class FTPClient
{
	private $my_connection;
	private $my_login;

	public function __construct($ftp_server, $ftp_port, $timeout, $username, $password)
	{
		$this->my_connection = ftp_connect($ftp_server, $ftp_port, $timeout);
		$this->my_login = ftp_login($this->my_connection, $username, $password);
	}
	/*
	public function connect($ftp_server, $ftp_port, $timeout)
	{
		
		return $connection;
	}
	
	public function login($connection, $username, $password)
	{
		$login = ftp_login($connection, $username, $password);
		return $login;
	}
	*/
	public function getDirectoryStructure($dir)
	{
		$directoryStructureArray = ftp_nlist($this->my_connection, $dir);
		return $directoryStructureArray;
	}
	
	public function getDetailedDirectoryStructure($dir)
	{
		$directoryStructureArray = ftp_rawlist($this->my_connection, $dir);
		$my_parser = new Parser();
		if(ftp_systype($this->my_connection) == "UNIX")
		{
			$directoryStructureArray = $my_parser->parseUnixOutput($directoryStructureArray);
		}
		else
		{
			$directoryStructureArray = $my_parser->parseWinOutput($directoryStructureArray);
		}
		return $directoryStructureArray;
	}
	
	public function changeDirectory($dir)
	{
		ftp_chdir($this->my_connection, $dir);
	}
	
	public function getPWD()
	{
		$current_dir = ftp_pwd($this->my_connection);
		return $current_dir;
	}
	
	public function getParentDirectory()
	{
		ftp_cdup($this->my_connection);
	}
	
	public function getSystemType()
	{
		return ftp_systype($this->my_connection);
	}
	
	public function getConnection()
	{
		return $this->my_connection;
	}
	
	public function getLogin()
	{
		return $this->my_login;
	}
	
	public function close()
	{
		ftp_close($this->my_connection);
	}
}
?>