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

foreach($student as $stud){
	$q="DELETE FROM wp_teacherstudent WHERE teacher_ID=$teacher and student_ID=$stud";
	$wpdb->query($q);
	$q="INSERT INTO wp_teacherstudent (teacher_ID,student_ID) VALUES ($teacher,$stud)";
	if(!$wpdb->query($q)){
		echo '0';
	}
}
?>