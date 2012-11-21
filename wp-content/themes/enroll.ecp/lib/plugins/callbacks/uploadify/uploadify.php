<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
function getPluginPatho()
{
 $index = "wp-content";
 $path = dirname(__FILE__);
 $string = substr($path, 0, strpos($path,$index));
 return $string;
}
$path = getPluginPatho();
require_once  $path."wp-load.php";

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_REQUEST['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
    
	$tempname = explode('.',$_FILES['Filedata']['name']);
	$hashName = uniqid($tempname[0].'_',false).'.'.$tempname[1];
	
	$hashFile = str_replace('//','/',$targetPath) . $hashName;
	
	if(move_uploaded_file($tempFile,$hashFile)){	
		global $wpdb;
		$data = array('file_upload_name'=>$_FILES['Filedata']['name'],'file_upload_hash'=>$hashFile);
		$wpdb->insert('wp_file_uploads', $data);
	}
	$returnval = array('file_id'=>$wpdb->insert_id,'file_name'=>$_FILES['Filedata']['name']);
	echo json_encode($returnval); 
}

?>