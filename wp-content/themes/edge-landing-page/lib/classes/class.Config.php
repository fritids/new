<?php



/**
 * Config manipulation class ...
 * @author Abuzz
 *
 */
class IDGL_Config{
	public static $config; 
	
	/**
	 * Constructor ...
	 */
	public function IDGL_Config(){
	}
	/**
	 * 
	 * Fetch configuration from file , if its already initialised then return parsed config
	 * @param filename $xmlPath
	 * @return object config
	 */
	public static function getConfig($xmlPath){
		if(!is_object(IDGL_Config::$config)){
			IDGL_Config::$config=IDGL_Config::parseConfig($xmlPath);
		}
		return IDGL_Config::$config;
	}
	/**
	 * 
	 * Parse node from XML configuration file
	 * @param filename $xmlNode
	 * @return array
	 */
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
	/**
	 * 
	 * Parse configuration from XML file
	 * 
	 * @param unknown_type $xmlPath
	 * @return array
	 */
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