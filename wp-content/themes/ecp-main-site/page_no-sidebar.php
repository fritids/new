<?php
/* 
  Template Name: Page NO Sidebar
*/ 
?>
<?php get_header(); ?>
<div class="leftcolumn" style="width:100%;">
<?php if (have_posts()) : ?>
       <?php while (have_posts()) : the_post(); ?>    
	      <div class="post page" style="width:100%;">
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
<?php get_footer(); ?>