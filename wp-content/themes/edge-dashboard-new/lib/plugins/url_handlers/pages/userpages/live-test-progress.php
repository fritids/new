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
?>
<?php 
if(!ECPUser::userCanEditStudents(ECPUser::getUserID())):
?>
<style type="text/css">
.add_row_link, .table_view_save{
	display: none !important;
}
</style>
<script type="text/javascript">
var dont_init_tables=true;
</script>
<?php 
endif;
?>
<div class="whitebox">
	<?php 
	global $wp_query;
	$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
	?>
	<div class="tabssmall" id="tables-shortlinks">
		<?php 
			query_posts('post_type=progresstemplates&post_status=publish&orderby=menu_order&order=ASC');
			if (have_posts()) : 
				while (have_posts()) : the_post(); global $post;
				$selected=getPostMeta($post->ID,"relatedProduct");
				$is_in=false;
				foreach($selected as $rel_prod){
					if(in_array($rel_prod,$products)){
						$is_in=true;
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
		
		query_posts('post_type=progresstemplates&post_status=publish&orderby=menu_order&order=ASC');
		if (have_posts()) : 
		while (have_posts()) : the_post(); global $post;
			$selected=getPostMeta($post->ID,"relatedProduct");
				$is_in=false;
				foreach($selected as $rel_prod){
					if(in_array($rel_prod,$products)){
						$is_in=true;
						break;
					}
				}
				if(!$is_in && !in_array("demo-student",$sections)) continue;
				get_user_progress_tables($user_id);
				fb($user_id);
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
	if(ECPUser::userCanEditStudents(ECPUser::getUserID())){
	?>
	<input type="hidden" id="current_user" value="<?php global $wp_query; echo ECPUser::getUserIDFromUname($wp_query->query_vars['uname']); ?>" />
	<?php } ?>
</div>	
<div class="clear"></div>
<?php get_footer(); ?>