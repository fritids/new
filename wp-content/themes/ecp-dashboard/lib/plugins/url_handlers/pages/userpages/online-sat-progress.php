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
	
	$current_user = wp_get_current_user();
    $user_id = $current_user->ID;

	?>
		<ul>
        	<li>
	            <a class="main-category"><span class="white-icons books"></span>SAT & ACT</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT and ACT"),$user_id);echo $wpm->toString(); ?>
           	</li>
           	<li>
	            <a class="main-category"><span class="white-icons books"></span>SAT Only</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT Only"),$user_id);echo $wpm->toString(); ?>
           	</li>
           	<li>
	            <a class="main-category"><span class="white-icons books"></span>ACT Only</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("ACT Only"),$user_id);echo $wpm->toString(); ?>
           	</li>
          </ul>

</div>
<?php get_footer(); ?>