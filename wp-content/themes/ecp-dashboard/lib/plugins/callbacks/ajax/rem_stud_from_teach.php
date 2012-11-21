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
 
 
if(!empty($teacher) && !empty($student)){
	$q = "DELETE from wp_teacherstudent where student_ID=$student AND teacher_ID=$teacher LIMIT 1";
	if(!$wpdb->query($q)){
		echo '0';
	}
}

?>