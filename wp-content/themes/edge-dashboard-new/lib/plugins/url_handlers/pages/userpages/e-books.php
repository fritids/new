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
//echo ECPUser::getSubType();
//user_type
global $wp_query;
$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
$products=get_user_meta($user_id,"_IDGL_elem_ECP_user_order",true);
$products=unserialize($products);


	listProducts('e-books');
?>	
</div>	
<div class="clear"></div>
<?php get_footer(); ?>