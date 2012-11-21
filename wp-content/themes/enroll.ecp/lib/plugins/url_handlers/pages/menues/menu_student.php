<?php 
global $wp_query;
$user_name=$wp_query->query_vars['uname'];
if($user_name===null){
	$user_name=ECPUser::getUserName();
}

?>
<h2>Currently viewing <?php echo $user_name; ?>'s profile</h2>
<div class="tabsdash">
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/profile/">Profile</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/online-sat-progress/">Online SAT Course Progress</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/live-test-progress/" >Live Test Progress</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/e-books/">E-Books</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/online-coaching/">Online Coaching</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/official-practice-test-explanation/" href="">College Board's<br>Official Practice Test Explanations</a>
	<a href="<?php echo get_option('home'); ?>/dashboard/<?php echo $user_name; ?>/payment-info/">Payment Info</a>
</div>