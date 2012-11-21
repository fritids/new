<?php

/**
 * 
 * Comments class ...
 * @author Abuzz
 *
 */

class IDGLComments{
	
	/**
	 * 
	 * Fetches comment model and renders it in admin ...
	 */
	
	public static function IDGL_addCommentsPanels(){
		if(is_file(IDG_COMMENTS_CONF_FILE)){
			$commentsOptions=IDGL_Config::getConfig(IDG_COMMENTS_CONF_FILE);
			foreach($commentsOptions as $page){
				$name=$page["name"];
				$title=$page["title"];
				
				$fnct.= 'echo  \'<div class="IDGL_Wrap">\';';
				foreach($page as $node){
					$nd=new IDGL_Node($node);
					$fnct.='echo "'.$nd->renderAdmin().'";';
				}
				$fnct.= 'echo \'</div>\';';
				eval($fnct);

			}
		}
	}
	/**
	 * 
	 * Saves comment to meta in database ...
	 * @param integer $comment_id
	 */
	public static function IDGL_saveCommentData($comment_id){
		 if(isset($_POST['IDGL_elem'])){	
			 foreach($_POST['IDGL_elem'] as $key=>$val){
			 	update_comment_meta($comment_id, "_IDGL_elem_".$key, $val);
			 }
		 }
	}
	
	/**
	 * 
	 *  ...
	 * @param unknown_type $fName
	 * @param unknown_type $data
	 */
	
	public static function IDGL_addCommentsOptionFunction($fName,$data){
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