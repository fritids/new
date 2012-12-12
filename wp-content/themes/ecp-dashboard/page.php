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
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<?php the_content(); ?>
	</div>
<?php endwhile; endif; ?>

<?php get_footer(); ?>