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
 <div class="whitebox">
<script type="text/javascript">
jQuery(function($){
	$("a.learn_more").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	true
	});
});
</script>
<style type="text/css">
.bookContent{ display:none; }
</style>
<?php 
	listProducts('video-explanations');
?>
</div>	
<?php get_footer(); ?>