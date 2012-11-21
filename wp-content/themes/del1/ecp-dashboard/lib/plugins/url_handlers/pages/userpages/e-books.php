<?php
if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}
get_header(); 
include dirname(__FILE__)."/../menues/menu_student.php";
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
	listProducts('e-books');
?>	
</div>	
<div class="clear"></div>
<?php get_footer(); ?>