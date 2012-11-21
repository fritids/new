<?php
/*
Plugin Name: Event Calendar
Plugin URI: BlogDesignStudio.com
Description: 
Author: BlogDesignStudio
Author URI: www.BlogDesignStudio.com
*/

// register plugin

define(AZ_CLASSPATH, dirname(__FILE__)."/Classes/");
define(AZ_TEMPLATES, dirname(__FILE__)."/pages/");

remove_action('init', 'wp_super_edit_init', 5);
remove_filter('mce_external_plugins','wp_super_edit_tinymce_plugin_filter', 100);
remove_filter('tiny_mce_before_init','wp_super_edit_tinymce_filter', 100);
remove_action('admin_menu', 'wp_super_edit_admin_menu_setup');
remove_action('admin_init', 'wp_super_edit_admin_setup');
remove_action( 'template_redirect', 'wp_super_edit_tiny_mce' );

ob_start();
$cal_table_name = $wpdb->prefix . "AzEventCalendar_Events";


wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_register_script('jquery-ui-datepicker', WP_PLUGIN_URL . '/AzEventCalendar/js/jquery-ui-date.min.js');
wp_enqueue_script('jquery-ui-datepicker');
wp_register_script('jquery-tooltip', WP_PLUGIN_URL . '/AzEventCalendar/js/jquery.simpletip.js');
wp_enqueue_script('jquery-tooltip');
wp_register_script('jquery-validate', WP_PLUGIN_URL . '/AzEventCalendar/js/jquery.validate.pack.js');
wp_enqueue_script('jquery-validate');
wp_register_script('swfobject', WP_PLUGIN_URL . '/AzEventCalendar/js/swfobject.js');
wp_enqueue_script('swfobject');
wp_register_script('uploadify', WP_PLUGIN_URL . '/AzEventCalendar/js/uploadify/jquery.uploadify.v2.1.0.min.js');
wp_enqueue_script('uploadify');
wp_register_script('jwysiwyg', WP_PLUGIN_URL . '/AzEventCalendar/js/jwysiwyg/jquery.wysiwyg.js');
wp_enqueue_script('jwysiwyg');
wp_register_script('jquery-paginate', WP_PLUGIN_URL . '/AzEventCalendar/js/Pajinate/jquery.pajinate.min.js');
wp_enqueue_script('jquery-paginate');
/*wp_register_style('jquery-paginate', WP_PLUGIN_URL . '/AzEventCalendar/js/jPaginate/css/style.css');
wp_enqueue_style('jquery-paginate');*/

require_once 'Classes/AzCalendar.php';

add_action('wp_head', 'AzEventCalendar_addCss');
function AzEventCalendar_addCss(){
	echo '<link type="text/css" rel="stylesheet" href="'.WP_PLUGIN_URL.'/AzEventCalendar/css/no-theme/jquery-ui-1.7.2.custom.css" />';
	echo '<link type="text/css" rel="stylesheet" href="'.WP_PLUGIN_URL.'/AzEventCalendar/css/AzCalendar.css" />';
	echo '<link type="text/css" rel="stylesheet" href="'.WP_PLUGIN_URL.'/AzEventCalendar/js/jwysiwyg/jquery.wysiwyg.css" />';
}

add_action( 'widgets_init', 'AzCalendarWidget' );

function AzCalendarWidget() {
	require_once 'Classes/AzCalendarWidget.php';
	register_widget( 'AzCalendarWidget' );
	register_widget( 'AzCalendarWidgetEventList' );
	
}

register_activation_hook( __FILE__, 'AzEventCalendar_activate' );
function AzEventCalendar_activate()
{	
   global $wpdb;
   global $cal_table_name;
   if($wpdb->get_var("show tables like '$cal_table_name'") != $cal_table_name)
   {
	$cat_sql = "CREATE TABLE " . $cal_table_name . " (
		    event_id mediumint(9) NOT NULL AUTO_INCREMENT,
		    event_date DATE NOT NULL,
		    event_end_date DATE NOT NULL,
		    event_title nvarchar(500) NOT NULL,
		    event_short_desc text,
		    event_content text,
		    
		    event_image nvarchar(500) NULL,
		    event_posted_by_name nvarchar(100),
		    event_posted_by_email nvarchar(100),
		    event_posted_by_phone nvarchar(100),
		    
		    event_posted_on_date datetime NULL,
		    event_is_enabled int(1) NOT NULL DEFAULT -1,
		    PRIMARY KEY (event_id)
		  )  ENGINE=InnoDB;";
	$wpdb -> query($cat_sql);	
   }
}


add_action('admin_menu', 'AzCalendar');
function AzCalendar()
{
	if ($_GET['activate'] == true)
	{
		AzEventCalendar_activate();
	}
    add_menu_page('AzCalendar', 'Event Calendar', 7, __FILE__, 'cal_index_page');
    add_submenu_page(__FILE__, 'AzCalendar', 'Add Event', 7, 'add_event', 'cal_add_edit_page');
    add_submenu_page(__FILE__, 'AzCalendar', 'Settings', 7, 'settings', 'cal_settings_page');
    AzCalendar_register_settings();
}

/*add_filter('admin_head','ShowTinyMCE');
function ShowTinyMCE() {
	wp_admin_css('thickbox');
	wp_print_scripts('jquery-ui-core');
	wp_print_scripts('jquery-ui-tabs');
	wp_print_scripts('post');
	wp_print_scripts('editor');
	add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
}*/

function cal_add_edit_page(){
	
	
	AzEventCalendar_addCss();
	require_once "pages/addEventTemplate.php";
}
function AzCalendar_register_settings() {
	//register our settings
	register_setting('baw-settings-group', 'events_list_page');
	register_setting('baw-settings-group', 'events_email');
	register_setting('baw-settings-group', 'events_frontend');
	register_setting('baw-settings-group', 'show_past_events');
	register_setting('baw-settings-group', 'show_tool_tip');
}
function cal_settings_page(){
	require_once 'pages/options.php';
}
function cal_index_page() 
{
	require_once "pages/eventsListTemplate.php";
}


function cal_postEditIcon(){
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
   if ( get_user_option('rich_editing') == 'true') {
	echo "
	<script type='text/javascript'>
		try{
		if(edButtons!=null){
			edButtons[edButtons.length]=new edButton('ed_azCal','azCal','[azCal eventsNo=\'\']','');
		}
		}catch(e){}
	</script>";
   }
}
add_action('admin_head', 'cal_postEditIcon');

//ajax action for geting all events
add_action('wp_ajax_nopriv_getE', 'getE');
add_action('wp_ajax_getE', 'getE');

//ajax action for inserting event from the frontend
add_action('wp_ajax_nopriv_createE', 'createE');
add_action('wp_ajax_createE', 'createE');

//ajax action for captcha validation
add_action('wp_ajax_nopriv_checkCaptcha', 'checkCaptcha');
add_action('wp_ajax_checkCaptcha', 'checkCaptcha');

function renderEventList($atts) {
 	$eventsNo=$atts["eventsNo"];
	$out=AzCalendar::getEventList();
	extract(shortcode_atts(array('pluginContent' => $script.$out), $atts));
	return "{$pluginContent}";
}
add_shortcode('azCal', 'renderEventList');

?>
<?php 
	function getE(){
		echo AzCalendar::getCalendar($_POST["month"], $_POST["year"]);		
		die();
	}
	
	function createE(){
		$eventIsEnabled = -1;
		/*if(is_user_logged_in())
		{
			$eventIsEnabled = 1;
		}*/
		echo AzCalendar::createEvent($_POST["eName"], $_POST["eStartDate"], $_POST["eEndDate"], $_POST["eDesc"], $_POST["uName"], $_POST["uEmail"], $_POST["uCNum"], $eventIsEnabled);
		die();
	}
	
	function checkCaptcha(){
		//echo AzCalendar::checkCaptcha($_POST["code"]);
		
		//echo ABSPATH.'wp-content/plugins/AzEventCalendar/secureImage/securimage.php';
		
		//require_once WP_PLUGIN_URL.'/AzEventCalendar/secureImage/securimage.php';
		require_once ABSPATH.'wp-content/plugins/AzEventCalendar/secureImage/securimage.php';
		$img = new Securimage;
		echo $img->check($_POST["code"]);
		
		
		
		//echo $_SESSION['securimage_code_value'];
		/*print_r($_SESSION);
		echo "kure";
		if($_SESSION['securimage_code_value']==$_POST["code"]){
			echo $_SESSION['securimage_code_value']."*true";
		}else{
			echo "false";
		}*/
		die();
	}
?>
