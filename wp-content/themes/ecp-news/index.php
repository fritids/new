<?php get_header(); ?>
<div class="leftcolumn">
<?php if (have_posts()) : ?>
       <?php while (have_posts()) : the_post(); ?>    
	      <div class="post">
	 			<div class="title">
                    <h1><a  href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                </div>
                 <div class="single-meta">
                	<div class="date"><?php the_time('j M, Y'); ?> </div>
                </div>
                <div class="clear"></div>
                <div class="entry">
                	<?php the_content(); ?>
                </div> 
                <div class="postmeta">
                    	<?php comments_popup_link('0', '1', '%','commentlink'); ?>
                    	<?php 
							$url_social = urlencode(get_permalink($post->ID));
							$tempArr = 'http://www.facebook.com/plugins/like.php?href='.$url_social.'&amp;layout=button_count&amp;show_faces&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21'; 
						?>	
		                <div class="abuzz_facebook">
						<iframe src="<?php echo $tempArr; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:21px;" allowTransparency="true"></iframe>
		                </div>
		                 <div class="abuzz_facebook">
		                 	 <script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
							<g:plusone size='medium' href='<?php the_permalink(); ?>'></g:plusone>
		                 </div>
                        <a href="<?php the_permalink(); ?>" class="more-link"><span>Continue Reading</span><span class="arrow"></span></a>.
                         <div class="clear"></div>
               </div>
		  </div>
       <?php endwhile; ?>
	   <?php else : ?>
        <div class="post">
            <div class="title">
              <h2>Not Found</h2>
            </div>
            <div class="entry">
              <p>Sorry, but you are looking for something that isn't here.</p>
            </div>
        </div>
<?php endif; ?>
<?php if (function_exists('wp_pagenavi')){ ?>
	 <div class="pageNav">
		    <?php  wp_pagenavi();  ?>
		    <div class="clear"></div>
	</div>
<?php } ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>