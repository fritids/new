<?php


/**
 * 
 * 
 * Utility class ...
 * @author Abuzz
 *
 */
class Util {
	public static $counter=0;
	
	/**
	 * 
	 * Returns current page url ...
	 * @return string
	 * 
	 */
	public static function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
	/**
	 * 
	 * Generate dropdown menu ...
	 * @param array $arr
	 * @param string $name
	 * @param string $selected
	 * @return html
	 */
	
	public static function generateDropDown($arr,$name,$selected=null){
		$out="<select name='".$name."'  id='".$name."'>";
		foreach($arr as $val){
			if($selected==$val){
				$out.="<option selected='selected' value='".$val."'>".$val."</option>";
			}else{
				$out.="<option value='".$val."'>".$val."</option>";	
			}
			
		}
		$out.="</select>";
		return $out;
	}
	
	/**
	 * 
	 * Counter .. :D 
	 */
	public static function getCounter(){
		Util::$counter++;
		return Util::$counter;
	}
	
	/**
	 * Loading Java Scripts and CSS Styles easier ...
	 * @param string $name
	 * @param string $path
	 */
	public static function IDGL_addScripts($name,$path){
	
		$type = pathinfo($path, PATHINFO_EXTENSION);
		
		switch ($type){
			case 'js':
				wp_enqueue_script($name,$path,array('jquery'),'','');
				break;
			case 'css':
				wp_enqueue_style($name,$path,'','','');
				break;
			default:
				// like google maps that dont have extension..
				wp_enqueue_script($name,$path,'','','');
				break;		
		}	
	
	}
	
}
?>