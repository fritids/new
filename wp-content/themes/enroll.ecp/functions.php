<?php
//error_reporting(E_ALL^E_NOTICE);
//ini_set('display_errors', 1);

$themename = "BlogDesignStudio_Idioglossia";
$shortname = "Idioglossia";
//ini_set("session.cache_limiter", "must-revalidate"); // IE back button functionality
/*
 * Declare constants - used through the whole framework
 */
define("IDG_SESSION_PATH", $path = realpath(dirname(__FILE__)."/Session"));
define("IDG_ECP_CARD_TEMPLATES", TEMPLATEPATH."/lib/plugins/url_handlers/pages/shopping-cart/templates/");
define('IDG_ROOT', TEMPLATEPATH);
define('IDG_THEMEASSETS', TEMPLATEPATH . '/lib');
define('IDG_CLASS_PATH', TEMPLATEPATH . '/lib/classes/');
define("IDG_POST_CONF_FILE", TEMPLATEPATH."/lib/models/post.xml");
define("IDG_PAGE_CONF_FILE", TEMPLATEPATH."/lib/models/page.xml");
define("IDG_THEME_CONF_FILE", TEMPLATEPATH."/lib/models/theme.xml");
define("IDG_WIDGETS_CONF_FILE", TEMPLATEPATH."/lib/models/widgets.xml");
define("IDG_USERS_CONF_FILE", TEMPLATEPATH."/lib/models/users.xml");
define("IDGL_THEME_URL", get_bloginfo('template_directory'));

/*
 * Include base classes
 */
require_once(IDG_CLASS_PATH."class.Util.php");
require_once(IDG_CLASS_PATH."class.Config.php");
require_once(IDG_CLASS_PATH."class.PostOptionManages.php");
require_once(IDG_CLASS_PATH."class.PostType.php");
require_once(IDG_CLASS_PATH."class.CustomPostList.php");
require_once(IDG_CLASS_PATH."class.ThemePages.php");
require_once(IDG_CLASS_PATH."class.IDGL_Users.php");
require_once(IDG_CLASS_PATH."class.WidgetAreas.php");
require_once(IDG_CLASS_PATH."class.Widgetizer.php");
require_once(IDG_CLASS_PATH."class.Sidebar.php");
require_once(IDG_CLASS_PATH."class.IDGL_DataGrid.php");

/*
 * Register scripts / maybe these should go in the admin head
 */
wp_enqueue_script('jquery');
wp_register_script('jquery-ui', IDGL_THEME_URL . '/lib/js/jquery-ui-1.7.2.custom.min.js');
wp_enqueue_script('jquery-ui');
wp_register_script('ajaxupload', IDGL_THEME_URL . '/lib/js/ajaxupload.js');
wp_enqueue_script('ajaxupload');
wp_register_script('idgl_colorpicker', IDGL_THEME_URL . '/lib/js/colorpicker/colorpicker.js');
wp_enqueue_script('idgl_colorpicker');
wp_register_script('main', IDGL_THEME_URL . '/lib/js/main.js');
wp_enqueue_script('main');

/*
 * Print admin head
 */
add_action('admin_head','IDGL_Admin_head');
function IDGL_Admin_head() {
	global $IDGL_model;
	$uplData=wp_upload_dir();
	echo '<link type="text/css" rel="stylesheet" href="'.IDGL_THEME_URL.'/lib/js/colorpicker/css/colorpicker.css" />' . "\n";
	echo '<link type="text/css" rel="stylesheet" href="'.IDGL_THEME_URL.'/lib/css/ui-lightness/jquery-ui-1.7.2.custom.css" />' . "\n";
	echo '<link type="text/css" rel="stylesheet" href="'.IDGL_THEME_URL.'/lib/css/styles.css" />' . "\n";
	echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> ';
	echo "<script type='text/javascript'>
			var imagesPath='".IDGL_THEME_URL."/lib/images/';
			var adminAjax='".get_bloginfo('url')."/wp-admin/admin-ajax.php';
			var uploadPath='".$uplData["url"]."/';
			var selectedModel='".$IDGL_model."';
		</script>";

		if (function_exists('wp_tiny_mce')) wp_tiny_mce();
		wp_admin_css();

}

add_action('wp_head','IDGL_add_scripts_front');
function IDGL_add_scripts_front(){
	echo "<script type='text/javascript'>
			var imagesPath='".IDGL_THEME_URL."/lib/images/';
			var adminAjax='".get_bloginfo('url')."/wp-admin/admin-ajax.php';
		</script>";
}

if(class_exists("IDGL_PostOptionManages")){
	add_action('save_post', 'IDGL_PostOptionManages::IDGL_savePostData');
}
if(class_exists("IDGL_ThemePages")){
	add_action('admin_menu', 'IDGL_ThemePages::IDGL_addPages');
}
if(class_exists("IDGL_PostOptionManages")){
	add_action('admin_menu', 'IDGL_PostOptionManages::IDGL_addPostPanels');
}
if(class_exists("PostType")){
	add_action('init', 'PostType::register');
}
if(class_exists("IDGL_WidgetAreas")){
	add_action( 'widgets_init', 'IDGL_WidgetAreas::register_widget_areas' );
}
if(class_exists("IDGL_Widgetizer")){
	add_action( 'widgets_init', 'IDGL_Widgetizer::widgetize' );
}

add_action('admin_init', 'IDGL_init' );
function IDGL_init(){
   	IDGL_ThemePages::IDGL_register_setting();
   	//register_setting('IDGL-settings-group', 'IDGL_elem');
}

add_action('init', 'check_session_init' );
function check_session_init(){
	if(session_id()){
		
	}
} 

set_error_handler("ecp_error_handler");

function ecp_error_handler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) 
	{
        // This error code is not included in error_reporting
        return;
    }
    //require_once IDG_CLASS_PATH."KLogger/src/KLogger.php";
	//$logger = KLogger::instance(IDGL_LOG_DIR, KLogger::FATAL);
	
    switch ($errno) 
    {
	    case E_USER_ERROR:
	        $str =  "<b>My ERROR</b> [$errno] $errstr<br />\n";
	        $str .= "  Fatal error on line $errline in file $errfile";
	        $str .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
	        $str .= "Aborting...<br />\n";
	        exit(1);
	        break;
	
	    case E_USER_WARNING:
	        $str = "<b>My WARNING</b> [$errno] $errstr<br />\n";
	        break;
	
	    case E_USER_NOTICE:
	        $str = "<b>My NOTICE</b> [$errno] $errstr<br />\n";
	        break;
	
	    default:
	        $str = "Unknown error type: [$errno] $errstr<br />\n";
	        break;
    }
	fb($str);
	//$logger -> logFatal($str);
  
    return true;
}


/*
 * users and user types
 */
if(class_exists("IDGL_Users")){
	add_action( 'show_user_profile', 'IDGL_show_add_user_fields' );
	add_action( 'edit_user_profile', 'IDGL_show_add_user_fields' );
	add_action( 'personal_options_update', 'IDGL_save_add_user_fields' );
	add_action( 'edit_user_profile_update', 'IDGL_save_add_user_fields' );
}
function IDGL_show_add_user_fields( $user ) {
	IDGL_Users::IDGL_addUserPanels($user);
}
function IDGL_save_add_user_fields( $user ) { 
	if ( !current_user_can( 'edit_user', $user_id ) ) return false;
	IDGL_Users::IDGL_saveUserMeta($user);
}



/*
 * Upload handler - used by the image uploader
 */
add_action('wp_ajax_imageUpload', 'imageUpload' );
function imageUpload(){
	echo "***";
	$uplData=wp_upload_dir();
	include "lib/classes/class.Image.php";
	$tempFile = $_FILES['Filedata']['tmp_name'];
	print_r($_GET);
	if(isset($_GET["newFileName"])){
		$targetFile =str_replace(" ","_",$uplData["path"]."/".$_GET["newFileName"]);
		$targetThumb =str_replace(" ","_",$uplData["path"]."/thumb_".$_GET["newFileName"]);
	}else{
		$targetFile =str_replace(" ","_",$uplData["path"]."/".$_FILES['Filedata']['name']);
		$targetThumb =str_replace(" ","_",$uplData["path"]."/thumb_".$_FILES['Filedata']['name']);
	}
	echo $targetFile."\n";
	echo $targetThumb."\n";
	move_uploaded_file($tempFile,$targetFile);
	if(isset($_GET["w"])){
		$sizes=explode("|",$_GET["w"]);
		if($sizes[2]!=null){
			WPACImage::resizeTo($sizes[2],$sizes[3],$targetFile,$targetThumb);
		}
		WPACImage::resizeTo($sizes[0],$sizes[1],$targetFile);
	}
	die();
}

/* interfaceFunctions */
/*
 * hasPostMeta::Get a value defined in the in the "wp_postmeta" table
 * 		$pid - id of the post/page
 * 		$key - name of the key	
 * 		$default - default value to be returned if no result has been retrieved
 */
function getPostMeta($pid,$key,$default=""){
	$meta=get_post_meta($pid, "_IDGL_elem_".$key, true);
	if($meta!=""){
		if(is_array($meta)){
			return $meta;
		}else{
			return stripcslashes($meta);
		}
	}else{
		return $default;
	}
}
/*
 * hasPostMeta::Checks if a certain key / value exists in the "wp_postmeta" table
 * 		$pid - id of the post/page
 * 		$key - name of the key	
 */
function hasPostMeta($pid, $key){
	$meta=get_post_meta($pid, "_IDGL_elem_".$key, true);
	if($meta!=""){
		return true;
	}
}
/*
 * getThemeMeta::Get a value defined in the "wp_options" table
 * 		$key - name of the key
 * 		$default - default value to be returned if no result has been retrieved
 */
function getThemeMeta($key,$default="",$pageName=""){
	$options = get_option('IDGL_elem'.$pageName);
	if($options[$key]!=""){
		if(is_array($options[$key])){
			return $options[$key];
		}else{
			return stripcslashes($options[$key]);
		}
	}else{
		return $default;
	}
}
$mapCount=0;

/*
 * Creates a map with a marker
 * 		$mapName - the javascript name of the map
 * 		$mapValue - "long,lat,zoom-value" triple
 * 		$w - width of <div> wrapper for the map
 * 		$h - height of <div> wrapper for the map
 */
function renderGoogleMap($mapName,$mapValue,$w=400,$h=300){
	global $mapCount;
	$mapValue=str_replace(array("[","]"),"",$mapValue);
	$mapParams=explode(",",$mapValue);
	echo '<div id="map_canvas-'.$mapCount.'" style="width: '.$w.'px; height: '.$h.'px"></div>';
	echo '<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&key=ABQIAAAACuPnpsfgQtylGTK5oELr8hR0DCSZkES4mAjJ0aPCnwtfcV3XsRSRV4aCAUZF_Yo1zu8OsCxCX7Salw
	"></script> ';
	echo '<script type="text/javascript">
			    var map_'.$mapName.';
				jQuery(function(){
			      if (GBrowserIsCompatible()) {
			        map_'.$mapName.' = new GMap2(document.getElementById("map_canvas-'.$mapCount.'"));
			        map_'.$mapName.'.setCenter(new GLatLng('.$mapParams[0].', '.$mapParams[1].'), '.$mapParams[2].');
			        map_'.$mapName.'.setUIToDefault();
			        //map_'.$mapName.'.openInfoWindow(map_'.$mapName.'.getCenter(),document.createTextNode("Hello, world"));
			        
			      }
			   })
		  </script>';
	$mapCount++;
}

/*
 * Adds a marker to a specific google map
 * 		$mapName - the javascript name of the map - an exsisting map
 * 		$mapValue - "long,lat,zoom-value" triple
 * 		$html - the html content that should appear in the marker's bubble
 */
function addGoogleMapMarker($mapName,$mapValue,$html=""){
	$mapValue=str_replace(array("[","]"),"",$mapValue);
	$mapParams=explode(",",$mapValue);
	echo '<script>
				jQuery(function(){
					var point = new GLatLng('.$mapParams[0].','.$mapParams[1].');
					var marker=new GMarker(point);
			   		map_'.$mapName.'.addOverlay(marker);';
			   	
				if($html!=""){	
			   		echo 'GEvent.addListener(marker, "click", function() {
							    var myHtml = "'.$html.'";
							    map_'.$mapName.'.openInfoWindowHtml(point, myHtml);
							  });';
				}
			echo '})</script>';
}

/*
 * Default rendering for the image gallery node - returnd ul>li>a>img - need to figure out the alt??
 * 		$galArray - an array of comma separated values - paths to images
 */
function renderImageGallery($galArray){
	if(is_array($galArray)){
		foreach($galArray as $image){
			$thumb=str_replace("img_","thumb_img_",$image);
			echo '<li><a href="'.$image.'"><img src="'.$thumb.'" /></a></li>';
		}
	}
}

/*
 * Load all plugins
 */
if(is_dir(dirname(__FILE__)."/lib/plugins")){
	$plugins=IDGL_File::getFileList(dirname(__FILE__)."/lib/plugins");
	foreach($plugins as $plugin){
		require_once dirname(__FILE__)."/lib/plugins/".$plugin."/".$plugin.".php";
	}
}

/*
 * Gets ecp products meta data for given product (post) it
 */
function getECPProductMeta($pid)
{
	$error = array(
				"id" => $pid,
				"price" => 0,
				"discount" => 0
			);
	$post_meta = array(
				"id" => $pid,
				"price" => getPostMeta($pid, "ProductPrice", $error),
				"discount" => getPostMeta($pid, "productDiscount", $error)
	);

	return $post_meta;
}

function getAllECPProducts()
{
	global $wpdb;
	$post_sql = "SELECT ID, post_content, post_title, post_name
					FROM {$wpdb -> posts}
					LEFT JOIN {$wpdb -> term_relationships} ON({$wpdb -> posts}.ID = {$wpdb -> term_relationships}.object_id)
					LEFT JOIN {$wpdb -> term_taxonomy} ON({$wpdb -> term_relationships}.term_taxonomy_id = {$wpdb -> term_taxonomy}.term_taxonomy_id)
					LEFT JOIN {$wpdb -> terms} ON({$wpdb -> term_taxonomy}.term_id = {$wpdb -> terms}.term_id)
					WHERE {$wpdb -> posts}.post_type = 'ECPProduct' 
					AND {$wpdb -> posts}.post_status = 'publish'
					AND {$wpdb -> term_taxonomy}.taxonomy = 'ecp-products'
					ORDER BY {$wpdb -> posts}.post_date ASC;";
	return $wpdb -> get_results($wpdb -> prepare($post_sql));
}

/*
 * AJAX FUNCTIONALITY
 */
add_action("wp_ajax_nopriv_ecp_actions", "ecp_ajax_actions");
add_action("wp_ajax_ecp_actions", "ecp_ajax_actions");

function ecp_ajax_actions()
{
	require_once IDG_CLASS_PATH."ShoppingCart/class.ECPAjaxController.php";
	$method = $_POST["method"];
	$values = json_decode(stripslashes($_POST["values"]));
	$ret = ECPAjaxController::Controller($method, $values);
	echo $ret -> getTemplate();
	die();
}
if(isset($_GET["render"])){
	getProducts($_GET["render"]);
}
function getProducts($type){
	echo 'post_type=ecpproduct&post_status=publish&posts_per_page=-1&ecp-products='.$type;
	query_posts('post_type=ecpproduct&post_status=publish&posts_per_page=-1&ecp-products=college-board-official-practice-tests-explanations');
	if ( have_posts() ) : while ( have_posts() ) : the_post(); global $post;
	$terms = get_the_terms($post->ID, 'ecp-products');  
	print_r($terms);
	?>
	<div class="users_books" style="clear:both;">
		<h2><?php the_title(); ?></h2>
		<div class="bookContent">
			<div style="width:600px;" id="prid_<?php echo $post->ID; ?>"><?php the_content(); ?></div>
		</div>
		<p>
            <a class="button blue learn_more" href="#prid_<?php echo $post->ID; ?>">Find Out More</a>
                <a class="button pink" href="#">Buy</a>
                <a class="button green" href="#">Download</a>
		</p>
	</div>
	<?php 
	endwhile;
	endif;
	wp_reset_query();
	die();
}


/******* menu class ******/

class Wp_Menu{
	private $menu_items;
	public static $current_selection;
	public static $flat_map;
	public function Wp_Menu($menu_items){
		$this->menu_items=array();

		foreach($menu_items as $item){
			$menu_item=$this->menu_items[$item->ID]=new Wp_MenuItem($item,$item->menu_item_parent);
			if($item->menu_item_parent!=0){
				if(isset($this->menu_items[$item->menu_item_parent]))
					$this->menu_items[$item->menu_item_parent]->addChild($menu_item);
			}
		}
	}
	public function toString(){
		
		foreach($this->menu_items as $item){
			
			if($item->getParentID()==0){
				if($item->item->object_id==6) continue;
				$out.="<div class='elem_".$item->item->object_id."'>";
				$out.=$item->toString();
				$out.="</div>";
			}
		}
		
		return $out;
	}
	
	public function getSubMenu(){
		$current=Wp_Menu::$current_selection;
		if(count($current->submenu)==0){
			$current=Wp_Menu::$flat_map[$current->parent];
		}
		if(is_array($current->submenu)){
			foreach($current->submenu as $item){
					$out.=$item->toString();
			}
		}
		return $out;
	}
}
class Wp_MenuItem{
	public $item;
	public $parent;
	public $submenu;
	public function Wp_MenuItem($item,$parent=0,$submenu=null){
		$this->item=$item;
		$this->parent=$parent;
		if($this->submenu==null){
			$this->submenu=array();
		}
		global $wp_query;
		$post_obj = $wp_query->get_queried_object();
		$post_id = $post_obj->ID;
		//echo $post_id." -- ".$this->item->object_id."<br/>";
		if(Wp_Menu::$current_selection==null && $post_id==$this->item->object_id){
			Wp_Menu::$current_selection=$this;
		}
		Wp_Menu::$flat_map[$this->item->ID]=$this;
	}
	public function addChild($item){
		$this->submenu[]=$item;
	}
	public function getParentID(){
		return $this->parent;
	}
	public function getType(){
		return $this->item->object;
	}
	public function toString(){
		if(Wp_Menu::$current_selection==$this){
			if($this->parent==0){
				$out.="<h5>".$this->item->title."</h5>";
			}else{
				$out.="<a href='".$this->item->url."'>".$this->item->title."</a>";
			}
			
		}else{
			if($this->parent==0){
				$out.="<h5>".$this->item->title."</h5>";
			}else{
				$out.="<a href='".$this->item->url."'>".$this->item->title."</a>";
			}
		}
		
		if(count($this->submenu)>0){
			foreach($this->submenu as $item){
					$out.=$item->toString();
			}
		}
		return $out;
	}
}