<?php 

/*
 * Declare constants - used through the whole framework
 */
if($load_conf!="false"){
	define('IDG_ROOT', TEMPLATEPATH);
	define('IDG_PLUGINS_PATH', TEMPLATEPATH . '/lib/plugins/');
	define('IDG_THEMEASSETS', TEMPLATEPATH . '/lib');
	define('IDG_CLASS_PATH', TEMPLATEPATH . '/lib/classes/');
	define("IDG_POST_CONF_FILE", TEMPLATEPATH."/lib/models/post.xml");
	define("IDG_PAGE_CONF_FILE", TEMPLATEPATH."/lib/models/page.xml");
	define("IDG_THEME_CONF_FILE", TEMPLATEPATH."/lib/models/theme.xml");
	define("IDG_WIDGETS_CONF_FILE", TEMPLATEPATH."/lib/models/widgets.xml");
	define("IDG_USERS_CONF_FILE", TEMPLATEPATH."/lib/models/users.xml");
	define("IDG_COMMENTS_CONF_FILE", TEMPLATEPATH."/lib/models/comment.xml");
	define("IDGL_THEME_URL", get_bloginfo('template_directory'));
}


class IDGLoader{
	
	public static function Instance(){
		static $inst = null;
		if($inst == null){
			$inst = new self();
		}
		return $inst;
	}
	
	private function __construct(){}
	
	private function __clone(){}

	private function fileLoader($folder){
		
		$path = dirname(__FILE__) . "/" . $folder . "/";
		if(is_dir($path)){
		$dir = opendir($path);
		while($file = readdir($dir)){
			if ($file != "." && $file != ".."){
				if(is_dir($path.$file)){
			 		require_once($path.$file."/".$file.".php");
				}
				else{
					require_once($path.$file);
				}
			}
		}
		
		closedir($dir);
		}
		
	}
	
	public static function loadLibraries(){
		self::fileLoader('classes');
	}
	
	public static function loadPlugins(){
		self::fileLoader('plugins');
	}
	
}
?>