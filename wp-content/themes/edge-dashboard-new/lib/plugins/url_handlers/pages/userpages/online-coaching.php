<?php
if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}
enque_progress_table_styles();
enque_progress_table_scripts();
get_header(); 

$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
$products=get_user_meta($user_id,"_IDGL_elem_ECP_user_order",true);
$products=unserialize($products);
$sections=get_user_meta($user_id,"_IDGL_elem_userSubtype",true);

include dirname(__FILE__)."/../menues/menu_student.php";

$has_coaching=false;
?>
<?php 
if(in_array("demo-student",$sections) || in_array("online-student",$sections) || in_array("offline-student",$sections) || in_array("school-student",$sections)):
?>
<style type="text/css">
.add_row_link, .table_view_save{
	display: none !important;
}
</style>
<?php 
endif;
?>
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
<div class="whitebox">
	<?php 
	global $wp_query;
	$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
	?>
	<div class="tabssmall" id="tables-shortlinks">
		<?php 
			query_posts('post_type=coaching&post_status=publish&orderby=menu_order&order=ASC');
			if (have_posts()) : 
				while (have_posts()) : the_post(); global $post;
				
				$selected=getPostMeta($post->ID,"relatedProduct");
				$is_in=false;
				foreach($selected as $rel_prod){
					if(in_array($rel_prod,$products[0])){
						$is_in=true;
						$has_coaching=true;
						break;
					}
				}
				if(!$is_in && !in_array("demo-student",$sections)) continue;
				
		?>
		<a href="#<?php echo basename(get_permalink( $post->ID )) ?>"><?php the_title(); ?></a>
		<?php 
				endwhile;
			endif;
			wp_reset_query();
		?>
	</div>
	<div class="clear"></div>
	<?php 
		
		query_posts('post_type=coaching&post_status=publish&orderby=menu_order&order=ASC');
		if (have_posts()) : 
		while (have_posts()) : the_post(); global $post;
		
		$selected=getPostMeta($post->ID,"relatedProduct");
				$is_in=false;
				foreach($selected as $rel_prod){
					if(in_array($rel_prod,$products[0])){
						$is_in=true;
						$has_coaching=true;
						break;
					}
				}
				if(!$is_in && !in_array("demo-student",$sections)) continue;
		
		?>
			<div class="clear"></div>
			<div class="table_wrap" id="<?php echo basename(get_permalink( $post->ID )) ?>">
				<?php echo ECPUser::getProgressTable(basename(get_permalink( $post->ID )),$user_id);  ?>
				<div class="clear"></div>
				<?php the_content(); ?>
			</div>
		<?php 
		endwhile;
		endif;
		wp_reset_query();
	if(ECPUser::userCanEditStudents()){
	
	if(!$has_coaching){
		listProducts('online-coaching');
	}
	
	?>
	<input type="hidden" id="current_user" value="<?php global $wp_query; echo ECPUser::getUserIDFromUname($wp_query->query_vars['uname']); ?>" />
	<?php } ?>
</div>	
<div class="clear"></div>
<?php get_footer(); ?>