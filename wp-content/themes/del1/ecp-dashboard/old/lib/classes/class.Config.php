<?php
class IDGL_Config{
	public static $config; 
	public function IDGL_Config(){
	}
	public static function getConfig($xmlPath){
		if(!is_object(IDGL_Config::$config)){
			IDGL_Config::$config=IDGL_Config::parseConfig($xmlPath);
		}
		return IDGL_Config::$config;
	}
	public static function parseNode($xmlNode){
		$outArr=array();
		
		$attr=$xmlNode->attributes();
		foreach($attr as $name=>$value){
			$outArr[(string)$name]=(string)$value;
		}
		$outArr["_name"]=$xmlNode->getName();
		$subNodes=$xmlNode->children();
		foreach($subNodes as $node){
			$outArr[]=IDGL_Config::parseNode($node);
		}
		return $outArr;
	}
	public static function parseConfig($xmlPath){
		
		$xmlConf=new SimpleXMLElement(file_get_contents($xmlPath));
		$conf=array();
		$nodes=$xmlConf->children();
		foreach($nodes as $node){
			$conf[]=IDGL_Config::parseNode($node);
		}
		//print_r($conf);
		return $conf;
	}
}
?>