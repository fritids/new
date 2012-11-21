<?php 

 $index = "wp-content";
 $path = dirname(__FILE__);
 $path = substr($path, 0, strpos($path,$index));
 require_once  $path."wp-load.php";

class DownloadManager {
		
	public function deleteUpload($upload_id){
		global $wpdb;
		return $wpdb->query('DELETE FROM wp_file_uploads WHERE file_upload_id='.$upload_id);
	}
	
	public function listUpload($upload_id){
		global $wpdb;
		return $wpdb->query("SELECT * FROM wp_file_uploads WHERE file_upload_id=".$upload_id);
		
		
	}
	
	public function listAllUploads(){
		global $wpdb;
		return $wpdb->get_results('SELECT * FROM wp_file_uploads', ARRAY_A);
		
	}
	
	
	
}

?>