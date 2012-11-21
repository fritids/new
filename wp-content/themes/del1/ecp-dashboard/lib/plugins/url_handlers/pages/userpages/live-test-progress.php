<?php
if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}
enque_progress_table_styles();
enque_progress_table_scripts();
get_header(); 
include dirname(__FILE__)."/../menues/menu_student.php";
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
	?>
	<input type="hidden" id="current_user" value="<?php global $wp_query; echo ECPUser::getUserIDFromUname($wp_query->query_vars['uname']); ?>" />
	<?php } ?>
</div>	
<div class="clear"></div>
<?php get_footer(); ?>