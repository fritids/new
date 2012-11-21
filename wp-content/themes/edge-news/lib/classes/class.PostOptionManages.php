<?php
require_once "class.IDGL_Node.php"; 

/**
 * 
 * Class for adding node to post...
 * @author Abuzz
 *
 */
class IDGL_PostOptionManages{
	
	/**
	 * 
	 * Constructor ...
	 */
	function IDGL_PostOptionManages(){
	
	}
	
	/**
	 * 
	 * Fetch models and adds apropriate meta_box to post...
	 */
	public static function IDGL_addPostPanels(){
		if(isset($_GET["post_type"])){
			$currEditType=$_GET["post_type"];
		}elseif(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1)=="post-new.php"){
			$currEditType="post";
		}else{
			$currEditType=get_post_type($_GET["post"]);
		}

		
		if($currEditType!=""){
			if($currEditType=="post"){
				if(is_file(IDG_POST_CONF_FILE)){
					$postOptions=IDGL_Config::getConfig(IDG_POST_CONF_FILE);
					foreach($postOptions as $page){
						$name=$page["name"];
						$title=$page["title"];
						IDGL_PostOptionManages::IDGL_addPostOptionFunction($name,$page);
						add_meta_box( $name, __( $title, $title ), "IDGL_fx_".$name, 'post', 'advanced','high' );
					}
				}
			}else if($currEditType=="page"){
				if(is_file(IDG_POST_CONF_FILE)){
					$postOptions=IDGL_Config::getConfig(IDG_PAGE_CONF_FILE);
					foreach($postOptions as $page){
						$name=$page["name"];
						$title=$page["title"];
						IDGL_PostOptionManages::IDGL_addPostOptionFunction($name,$page);
						add_meta_box( $name, __( $title, $title ),"IDGL_fx_".$name, 'page', 'advanced','high' );
					}
				}
			}else{
				if(is_dir(dirname(__FILE__)."/../models/add/")){
					if(is_file(dirname(__FILE__)."/../models/add/".$currEditType.".xml")){
						$postOptions=IDGL_Config::getConfig(dirname(__FILE__)."/../models/add/".$currEditType.".xml");
						foreach($postOptions as $page){
							$name=$page["name"];
							$title=$page["title"];
							IDGL_PostOptionManages::IDGL_addPostOptionFunction($name,$page);
							add_meta_box( $name, __( $title, $title ), "IDGL_fx_".$name, $currEditType, 'advanced','high' );
						}
					}
				}
			}
		}	
	}
	
	/**
	 * 
	 * Saves post meta in database for current post...
	 * @param integer $post_id
	 */
	public static function IDGL_savePostData($post_id){

	  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	    return $post_id;
	  if ( 'page' == $_POST['post_type'] ) {
	    if ( !current_user_can( 'edit_page', $post_id ) )
	      return $post_id;
	  } else {
	    if ( !current_user_can( 'edit_post', $post_id ) )
	      return $post_id;
	  }
			
		
	 if(isset($_POST['IDGL_elem'])){	
		 foreach($_POST['IDGL_elem'] as $key=>$val){
		 	if(is_array($val)){
		 		update_post_meta($post_id, "_IDGL_elem_".$key, $val);
		 	}else{
		 		update_post_meta($post_id, "_IDGL_elem_".$key, $val);
		 	}
		 }
	 }
	}
	
	/**
	 * 
	 * Add custom post types...
	 */
	public static function IDGL_addPostCustomTypes(){
		
		
	}
	
	/**
	 * 
	 * Renders node model...
	 * @param string $fName
	 * @param string $data
	 */
	public static function IDGL_addPostOptionFunction($fName,$data){
		$fnct='function IDGL_fx_'.$fName.'(){';
		$fnct.= 'echo  \'<div class="IDGL_Wrap">\';';
		foreach($data as $node){
			$nd=new IDGL_Node($node);
			$fnct.='echo "'.$nd->renderAdmin().'";';
		}
		$fnct.= 'echo \'</div>\';';
		$fnct.='}';
		eval($fnct);
	}
}