<?php
class IDGL_ThemePages{
	public static $file;
	
	function IDGL_ThemePages(){}
	
	public static function IDGL_addPages(){
		if(is_file(IDG_THEME_CONF_FILE)){
			add_menu_page('IDGL Settings', 'IDGL Settings', 8, "IDGL_mainPage", 'IDGL_ThemePages::IDGL_mainPage');
			$postOptions=IDGL_Config::getConfig(IDG_THEME_CONF_FILE);
			foreach($postOptions as $page){
				$name=$page["name"];
				$title=$page["title"];
				//IDGL_ThemePages::$file=IDG_THEME_CONF_FILE;
				IDGL_ThemePages::IDGL_addThemePages($name,$page);
			}
		}
	}
	public static function IDGL_addSubPages($file){
		if(is_file($file)){
			$postOptions=IDGL_Config::getConfig($file);
			foreach($postOptions as $page){
				$name=$page["name"];
				$title=$page["title"];
				IDGL_ThemePages::$file=$file;
				add_submenu_page("IDGL_mainPage", $name,  $name, 8, $name, 'IDGL_ThemePages::IDGL_pluginSubPage');
			}
		}
	}
	public static function IDGL_register_setting(){
		if(isset($_GET["page"])){
			$pageName=$_GET["page"];
			register_setting('IDGL-settings-group-'.$pageName, 'IDGL_elem_'.$pageName);
		}else{
			$pageName=$_POST["option_page"];
			$temparr=explode("-",$pageName);
			register_setting($pageName,'IDGL_elem_'.$temparr[count($temparr)-1]);
		}
	}
	public static function IDGL_mainPage(){

	}
	public static function IDGL_addThemePages($fName,$data){
		add_submenu_page("IDGL_mainPage", $fName,  $fName, 8, $fName, 'IDGL_ThemePages::IDGL_subPage');
	}
	public static function IDGL_subPage(){
		$pageName=$_GET["page"];
		$postOptions=IDGL_Config::getConfig(IDG_THEME_CONF_FILE);
		foreach($postOptions as $page){
			if($page["name"]==$pageName){
				$optionsPage=$page;
				break;
			}
		}
		echo "<form method='post' action='options.php'><div class='wrap'>";
		settings_fields('IDGL-settings-group-'.$pageName);
		$options = get_option('IDGL_elem_'.$pageName);
		
		echo "<h2>".$optionsPage["title"]."</h2>";
		echo "<div class='IDGL_Wrap'>";
		echo "<div class='IDGL_Controls'>";
		foreach($optionsPage as $node){
			$nd=new IDGL_Node($node);
			echo $nd->renderAdmin($options[$node["name"]],null,null,"_".$pageName);
		}
		echo '<p class="submit">
	    	<input type="submit" class="button-primary" value="Save Changes" />
	    </p>';
		echo "</div></div>";
		echo "</div></form>";
	}
	//stupid !!
	public static function IDGL_pluginSubPage(){
		$pageName=$_GET["page"];
		$postOptions=IDGL_Config::getConfig(IDGL_ThemePages::$file);
		foreach($postOptions as $page){
			if($page["name"]==$pageName){
				$optionsPage=$page;
				break;
			}
		}
		echo "<form method='post' action='options.php'><div class='wrap'>";
		settings_fields('IDGL-settings-group-'.$pageName);
		$options = get_option('IDGL_elem_'.$pageName);
		
		echo "<h2>".$optionsPage["title"]."</h2>";
		echo "<div class='IDGL_Wrap'>";
		echo "<div class='IDGL_Controls'>";
		foreach($optionsPage as $node){
			$nd=new IDGL_Node($node);
			echo $nd->renderAdmin($options[$node["name"]],null,null,"_".$pageName);
		}
		echo '<p class="submit">
	    	<input type="submit" class="button-primary" value="Save Changes" />
	    </p>';
		echo "</div></div>";
		echo "</div></form>";
	}
}
?>