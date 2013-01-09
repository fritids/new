<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}
enque_progress_table_styles();
get_header(); 
include dirname(__FILE__)."/../menues/menu_student.php";

?>

<div class="table-holder" id="onlineSATprogress">
<?php 
	
	global $wp_query;
	$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);

	?>
		<ul>
        	<li>
	            <a class="main-category">SAT & ACT</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT and ACT"),$user_id);echo $wpm->toString(); ?>
           	</li>
           	<li>
	            <a class="main-category">SAT Only</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT Only"),$user_id);echo $wpm->toString(); ?>
           	</li>
           	<li>
	            <a class="main-category">ACT Only</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("ACT Only"),$user_id);echo $wpm->toString(); ?>
           	</li>
          </ul>

</div>
<?php get_footer(); ?>