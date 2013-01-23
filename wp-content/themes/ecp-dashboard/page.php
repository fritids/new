<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); global $post; ?>

<h1><?php the_title(); ?></h1>
<?php edit_post_link('Edit Page', '<p>', '</p>'); ?>
	<div class="video-holder">
		<?php  
			$videoURL= getPostMeta($post->ID,"VideoURL"); 
			if($videoURL!=""){ echo getFLVPlayer($videoURL,"qplayer"); }
		?>
	</div>
	
	<div class="page-buttons">
		<?php  
			$values = get_post_custom($post->ID);
			if(isset($values['next_section_slug'][0]) && $values['next_section_slug'][0]!=""):
		?>
		<a class="next-section-button" href="<?php echo esc_url(home_url('/'.$values['next_section_slug'][0])); ?>">Next Section</a>
		<?php endif; ?>
		<?php if(isset($values['drill_slug'][0]) && $values['drill_slug'][0]!=""): ?>
		<a class="take-drill-button" href="<?php echo esc_url(home_url('/drill/'.$values['drill_slug'][0])); ?>">Take drill</a>
		<?php endif; ?>
	</div>

	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<?php the_content(); ?>
	</div>
<?php endwhile; endif; ?>

<?php get_footer(); ?>