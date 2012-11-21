<?php
get_header();

include dirname(__FILE__)."/../menues/menu_student.php";

global $wp_query;
$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);

$userProfile=ECPUser::getUserData($user_id);
if(isset($_POST["first_name"])){
	IDGL_Users::IDGL_saveUserMeta($userProfile);
}
 ?>
**
<?php get_footer(); ?>