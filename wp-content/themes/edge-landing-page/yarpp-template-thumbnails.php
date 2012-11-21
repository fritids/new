<?php /*
Post Thumbnail Template
*/ 
?>

<?php if ($related_query->have_posts()):?>

<div class="related-posts">

<h2>Related Posts</h2>
	
	<ul>
		<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
			
			<li>
				<a href="<?php the_permalink() ?>" rel="bookmark">
           			<?php the_title(); ?>
                </a>
			</li>
				
		<?php endwhile; ?>
	</ul>
	
	<div class="clear">&nbsp;</div>

</div>

<?php endif; ?>