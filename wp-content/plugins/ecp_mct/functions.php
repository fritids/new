<?php

// Uploads cvs scaled scores
function ecp_mct_upload_scaled_scores($files, $test_id) {
	global $wpdb;
	
	if(!empty($files)) {
		foreach($files as $section_type=>$file) {
			if($file['tmp_name']) {
				$filename = basename($file["name"]);
				$file_ext = substr($filename, strrpos($filename, ".") + 1);

				// Verify that it is a csv file
				if($file_ext == "csv" || $file_ext == "CSV") {
					// Read file
					try {
						if($file = fopen($file['tmp_name'],"r")) {
							$row = 0;

							while (($data = fgetcsv($file, 0, ";")) !== FALSE) {
								// Skip the first row that has the names of the colums
								if($row != 0) {
									$query = "INSERT INTO ".ECP_MCT_TABLE_SCALED_SCORES." (`test_id`, `section_type`, `raw_score`, `scaled_score`) VALUES(%d,%s,%d,%d)";
									$wpdb->get_results($wpdb->prepare($query, $test_id, $section_type, $data[0], $data[1]));
								}
								$row++;
							}
							fclose($file);
						} else {
							return "&error=file_error";
						}
					} catch(Exception $e) {
						return "&error=file_error";
					}
				} else {
					 return "&error=file_error";
				}
			}
		}
	}
}