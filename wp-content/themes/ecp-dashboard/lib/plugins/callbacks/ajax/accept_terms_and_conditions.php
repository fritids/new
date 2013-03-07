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
wp_get_current_user();

echo json_encode(add_user_meta( $current_user->ID, "_IDGL_elem_terms_approval", date("Y-m-d H:i:s")));