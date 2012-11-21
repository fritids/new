<?php

/**
 * 
 * Rewriting class for idioglossia
 * @author Bojan Naumoski
 *
 */
class BRewrite{
	
	protected $files = array();
	protected $plugins = array();
	protected $newrules = array();
	protected $queryvars = array();
	
	
	/**
	 * 
	 * First thing to do set plugins to be parsed
	 * @param unknown_type $plugins
	 */
	public static function Instance($plugins){
		static $inst = null;
		if($inst == null){
			$inst = new self($plugins);
		}
		return $inst;
	}
	
	/**
	 * 
	 * Separate plugin folders with ,
	 * @param string $plugins
	 */
	private function __construct($plugins){
		
		if(isset($plugins)){
			$this->plugins = explode(',', trim($plugins));
		return true;
		}
		else
		{
		return false;			
		}
	}
	
	public function createRules(){
		
		$this->newRules();
		print_r($this->newrules);
	}
	
	/**
	 * 
	 * fills array newrules with rewrited vars
	 */
	private function newRules(){
		
		foreach($this->plugins as $plugin){
		$this->pluginFilesParse($plugin);
			foreach($this->queryvars as $qvar){
				foreach($this->files as $file){
			$this->newrules['('.$file.')'] = 'index.php?'.$qvar."=".$file;
				}
			}
		}
		
	}
	
	/** 
	 * 
	 * Must be set after Instance, sets query vars...
	 * @param unknown_type $queryvars
	 */
	public function setQueryVars($queryvars = array()){
		$this->queryvars = $queryvars;
	}
	
	
	private function pluginFilesParse($plugin,$folder=""){
		$path = IDG_PLUGINS_PATH."/".$plugin . "/pages/".$folder;
		if(is_dir($path)){
		$dir = opendir($path);
		while($file = readdir($dir)){
			if ($file != "." && $file != ".."){
				if(is_dir($path.$file)){
					//rekurzija za podfolderite
					$this->pluginFilesParse($plugin, $file."/");
				}else{
					$file = str_replace(".php", "", $file);
					$this->files []= $folder.$file;
				}		
			}
			
		}
		closedir($dir);
		}
		// return $this->files; nema potreba ova e samo za testiranje...
	}
	
			
	
}
?>