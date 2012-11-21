<?php
/* 
  Template Name: Page Sidebar8
*/ 
?>
<?php get_header(); ?>
<div class="leftcolumn">
<?php if (have_posts()) : ?>
       <?php while (have_posts()) : the_post(); ?>    
	      <div class="post page">
	 			<div class="title">
                    <h1><?php the_title(); ?></h1>
                </div>
                <div class="entry">
                	<?php the_content(); ?>
                </div> 
		  </div>
       <?php endwhile; ?>
<?php endif; ?>
</div>
<div class="sidebar">
<?php new Sidebar("Sidebar8"); ?>
</div>
<?php get_footer(); ?>