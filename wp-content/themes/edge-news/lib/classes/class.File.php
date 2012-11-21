<?php
/**
 * 
 * File manipulation class ...
 * @author Abuzz
 *
 */
class IDGL_File {
	/**
	 * 
	 * Fetch files in directory and returns array of filenames ...
	 * @param directoryname $dir
	 */
	public static function getFileList($dir){
		$dirArr=array();
		if ($handle = opendir($dir)) {
		    while (false !== ($file = readdir($handle))) {
		       // if(is_file($dir."/".$file)){
				if($file!="." && $file!=".."){	
		    		$dirArr[]=$file;
				}
				//}
		    }
		    closedir($handle);
		}
		return $dirArr;
	}
	/**
	 * 
	 * Remove file ...
	 * @param filename $fileName
	 */
	public static function delete($fileName){
		if(is_file($fileName)){
			unlink($fileName);
		}else{
			IDGL_File::removeDir($fileName, true);
		}
	}
	/**
	 * 
	 * Creates file also fills it with optional content ...
	 * @param string $fileName
	 * @param string $data
	 */
	public static function create($fileName,$data=null){
		$fl = fopen($fileName, 'w+') ;
		fwrite($fl,$data);
		fclose($fl);
	}
	/**
	 * 
	 * Creates folder on specified location ...
	 * @param filename $fileName
	 */
	public static function createFolder($fileName){
		mkdir($fileName);
	}
}
?>