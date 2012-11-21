<?php
if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}
get_header(); 
include dirname(__FILE__)."/../menues/menu_student.php";
?>
<div class="whitebox">
	
</div>	
<div class="clear"></div>
<?php get_footer(); ?>