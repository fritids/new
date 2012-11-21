<?php
class IDGL_File {
	public static function getFileList($dir){
		$dirArr=array();
		if ($handle = opendir($dir)) {
		    while (false !== ($file = readdir($handle))) {
		       // if(is_file($dir."/".$file)){
				if($file!="." && $file!=".." && $file != ".DS_Store" && $file != ".svn"){
					$dirArr[]=$file;
				}
				//}
		    }
		    closedir($handle);
		}
		return $dirArr;
	}
	public static function delete($fileName){
		if(is_file($fileName)){
			unlink($fileName);
		}else{
			IDGL_File::removeDir($fileName, true);
		}
	}
	public static function create($fileName,$data=null){
		$fl = fopen($fileName, 'w+') ;
		fwrite($fl,$data);
		fclose($fl);
	}
	public static function createFolder($fileName){
		mkdir($fileName);
	}
	public static function removeDir($dir, $DeleteMe) {
	    if(!$dh = @opendir($dir)) return;
	    while (false !== ($obj = readdir($dh))) {
	        if($obj=='.' || $obj=='..') continue;
	        if (!@unlink($dir.'/'.$obj)) SureRemoveDir($dir.'/'.$obj, true);
	    }
	
	    closedir($dh);
	    if ($DeleteMe){
	        @rmdir($dir);
	    }
	}
}
?>