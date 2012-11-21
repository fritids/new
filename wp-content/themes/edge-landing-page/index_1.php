<?php get_header(); ?>
<div class="leftcolumn">
<?php if (have_posts()) : ?>
       <?php while (have_posts()) : the_post(); ?>    
	      <div class="post page">
	 			<div class="title">
                    <h1><a  href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                </div>
                <div class="entry">
                	<?php the_content(); ?>
                </div> 
		  </div>
       <?php endwhile; ?>
<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>