<?php
require_once "class.File.php"; 
class IDGL_CustomPostList{
	function IDGL_CustomPostList(){
	
	}
	public static function IDGL_addAdminMenu(){
		$models=IDGL_File::getFileList( dirname(__FILE__)."/../models/");
		$modelJsArr="";
		foreach($models as $k=>$model){
			$modelJsArr.="'".$model."',";
		}
		return substr($modelJsArr,0,strlen($modelJsArr)-1);
		//substr  (  string $string  ,  int $start  [,  int $length  ] )
	}
	public static function IDGL_savePostData($post_id){
	 
	}
	public static function IDGL_addPostOptionFunction($fName,$data){
		
	}
}