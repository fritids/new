<?php
get_header();

include dirname(__FILE__)."/../menues/menu_student.php";

global $wp_query;
$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
$userProfile=ECPUser::getUserData($user_id);
$products=get_user_meta($user_id,"_IDGL_elem_ECP_user_order",true);
$products=unserialize($products);
 ?>
 
<div class="whitebox profile">
<h3>Purchased Products</h3>
 <?php
 switch_to_blog(3);
foreach($products as $pid){
	$post=get_post($pid);
	?>
		<h6><?php echo $post->post_title; ?></h6>
		<p><?php echo $post->post_content; ?></p>
<?php 
}
restore_current_blog();
?>
 </div>
<?php get_footer(); ?>