<?php
function getPluginPath()
{
 $index = "wp-content";
 $path = dirname(__FILE__);
 $string = substr($path, 0, strpos($path,$index));
 return $string;
}
$path = getPluginPath();

require  $path."wp-load.php";
global $wpdb;
 $teacher = $_POST['teacher_id'];
 $student = $_POST['student_id'];
 if(!empty($student)){
	 $q = "DELETE FROM wp_teacherstudent where student_ID=".$student;
	 if($wpdb->query($q)){}
 }
// echo 'deleted all';
// else
// echo 'cannot delete';
 if(!empty($teacher) && !empty($student)){
 foreach($teacher as $t){
 $q="INSERT IGNORE INTO wp_teacherstudent (student_ID,teacher_ID) VALUES ($student,$t)";
 if(!$wpdb->query($q)){
 	echo '0';
 }
 }
 }
?>