<?php 
global $wp_query;
$user_name=$wp_query->query_vars['uname'];
$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
$sections=get_user_meta($user_id,"_IDGL_elem_userSubtype",true);

if($user_name===null){
	$user_name=ECPUser::getUserName();
}
$is_admin=false;
if ( $user_id==1 ) { $is_admin=true; }



?>
<h2>Currently viewing <?php echo $user_name; ?>'s profile</h2>
<div class="tabsdash">
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/profile/">Profile</a>
	<?php if(in_array("online-student",$sections) || $is_admin || in_array("demo-student",$sections)): ?><a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/online-sat-progress/">Online SAT Course Progress</a><?php endif; ?>
	<?php if(in_array("offline-student",$sections) || $is_admin || in_array("demo-student",$sections)): ?><a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/live-test-progress/" >Test Prep Tutoring</a><?php endif; ?>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/e-books/">E-Books</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/online-coaching/">Admissions Counseling Packages</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/official-practice-test-explanation/" href="" class="twoline">College Board's<br>Official Practice Test Explanations</a>
	<!-- <a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/payment-info/">Payment Info</a> -->
</div>