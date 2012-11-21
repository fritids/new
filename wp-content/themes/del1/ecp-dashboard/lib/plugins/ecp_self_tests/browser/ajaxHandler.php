<?php
//require_once("class.FTPClient.php");
require_once("class.sFTPClient.php");

/*
$ftp_server = "azriel.uk.to";
$ftp_port = 22;
$ftp_user = "abuzzy";
$ftp_pass = "the4zriel";
*/


$ftp_server = "fg0txn14.rtmphost.com";
$ftp_port = 22;
$ftp_user = "fg0txn14";
$ftp_pass = "5te!la18#";

$ftp_instance = new sFTPClient($ftp_server, $ftp_port, $ftp_user, $ftp_pass);


if($_POST['action'] == 'getFolders')
{	
	//print_r($ftp_instance->scanFilesystem(""));
	//$parent_dir =  $ftp_instance->getPWD($_POST['dirName']);
	//echo $parent_dir;
	$output_html = generateHTML($ftp_instance->scanFilesystem($_POST['dirName']), $ftp_instance->getPWD($_POST['dirName']));
	echo $output_html;
}

if($_POST['action'] == 'changeDir')
{
	$output_html = generateHTML($ftp_instance->getDetailedDirectoryStructure($_POST['dirName']), $ftp_instance->getPWD($_POST['dirName']));
	echo $output_html;
}

if($_POST['action'] == 'upDir')
{
	$output_html = generateHTML($ftp_instance->scanFilesystem($_POST['dirName']), $ftp_instance->getPWD($_POST['dirName']));
	echo $output_html;
}


/*
if($_POST['action'] == 'getFolders')
{	
	$output_html = generateHTML($ftp_instance->getDetailedDirectoryStructure($ftp_instance->getPWD()), $ftp_instance->getPWD());
	echo $output_html;
}

if($_POST['action'] == 'changeDir')
{
	$ftp_instance->changeDirectory($_POST['dirName']);
	$output_html = generateHTML($ftp_instance->getDetailedDirectoryStructure($ftp_instance->getPWD()), $ftp_instance->getPWD());
	echo $output_html;
}

if($_POST['action'] == 'upDir')
{
	$ftp_instance->changeDirectory($_POST['dirName']);
	$ftp_instance->getParentDirectory();
	$output_html = generateHTML($ftp_instance->getDetailedDirectoryStructure($ftp_instance->getPWD()), $ftp_instance->getPWD());
	echo $output_html;
}
*/

function generateHTML($arr_param, $parent_dir)
{
	$nice_html = "<div id='abz_file_browser'><div class='abz_parent' id='{$parent_dir}'><div class='abz_icon'><img src='http://azriel.uk.to/ecp/wp-content/themes/ecp-dashboard/lib/plugins/ecp_self_tests/browser/images/file_type/go-previous.png' alt='parent'/></div><div class='abz_attr'>..</div></div>";
	foreach($arr_param as $param)
	{
		$type='abz_file';
		$file_icon = 'template.png';
		$ext_point = strrpos($param[1], '.');
		$video_ext = array('mp4', 'mkv', 'avi');
		$adobe_ext = array('flv');
		$audio_ext = array('mp3', 'wma', 'flac');
		$image_ext = array('png', 'jpg', 'jpeg', 'gif');
		$text_ext = array('html', 'txt', 'css', 'js');
		if($ext_point !== false)
		{
			$file_ext = substr($param[1], $ext_point+1);
		}
		if(in_array($file_ext, $video_ext))
		{
			$file_icon = 'video.png';
		}
		if(in_array($file_ext, $audio_ext))
		{
			$file_icon = 'audio.png';
		}
		if(in_array($file_ext, $image_ext))
		{
			$file_icon = 'image.png';
		}
		if(in_array($file_ext, $text_ext))
		{
			$file_icon = 'text.png';
		}
		if(in_array($file_ext, $adobe_ext))
		{
			$file_icon = 'FLV.png';
		}
		if($param[0] == 'd')
		{
			$type = 'abz_dir';
			$file_icon = 'folder.png';
		}
		$nice_html .= "<div class='{$type}' id='{$param[2]}'>
							<div class='abz_icon'>
								<img src='http://azriel.uk.to/ecp/wp-content/themes/ecp-dashboard/lib/plugins/ecp_self_tests/browser/images/file_type/{$file_icon}' alt='folder'/>
							</div>
							<div class='abz_attr'>{$param[1]}</div>
					 </div>";
	}
	$nice_html .= "</div>";
	return $nice_html;
}

?>