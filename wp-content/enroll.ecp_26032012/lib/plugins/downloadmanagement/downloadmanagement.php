<?php
//////////////////////
function getPluginPath()
{
 $index = "wp-content";
 $path = dirname(__FILE__);
 $string = substr($path, 0, strpos($path,$index));
 return $string;
}
$path = getPluginPath();

require_once  $path."wp-load.php";

//////////////////////

if(!is_user_logged_in()){
	return 0;
}

// da se proveri od baza dali $filename postoi tamu i ako postoi za toj user togas moze da se zeme fajlot

$filename = $_GET['file'];
$file = dirname(__FILE__)."/files/$filename";
if(!is_file($file)){
	return 0;
}

$mime = explode(';',shell_exec("file -bi " . $file));
$mime = $mime[0];
$filetype = explode('.' , $file);
$filetype = array_pop($filetype);
//echo $filetype;
//echo $mime;

@header("Content-type: $mime");
@header("Content-Disposition: attachment; filename=$filename");
echo file_get_contents($filename);
?>