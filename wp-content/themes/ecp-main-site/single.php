<?php get_header(); ?>
<div class="leftcolumn">
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="title">
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <div class="clear"></div>
        </div>
        <div class="single-meta">
        	<div class="date"><?php the_time('j M, Y'); ?> </div>
         	<div class="categorie-meta">
				<?php the_category('&nbsp;&nbsp;|&nbsp;&nbsp;') ?>
			</div>
			<div class="clear"></div>
        </div>
		<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

        <div class="entry">
			<?php the_content() ; ?>
        </div><!-- end .entry -->
    	 <div class="postmeta">
              <?php comments_popup_link('0', '1', '%','commentlink'); ?>
            	<?php 
					$url_social = urlencode(get_permalink($post->ID));
					$tempArr = 'http://www.facebook.com/plugins/like.php?href='.$url_social.'&amp;layout=button_count&amp;show_faces&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21'; 
				?>	
                <div class="abuzz_facebook">
				<iframe src="<?php echo $tempArr; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:21px;" allowTransparency="true"></iframe>
                </div>
                <!-- AddThis Button BEGIN -->
                <div class="abuzz_sharethis">
					<div class="addthis_toolbox addthis_default_style ">
					<a class="addthis_counter addthis_pill_style"></a>
					</div>
					<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4d0a2c5913480923"></script>
				</div>
				<!-- AddThis Button END -->
                
         </div>
		<?php //new Sidebar("Ad_Single_Post"); ?>
		<div class="clear"></div>
	</div>
  <!-- end .post -->
  <div class="clear"></div>
  <div class="sidebar-after-post">
  	<?php new Sidebar("afterPost"); ?>
  </div>
  <div class="clear"></div>
	<div id="commentArea">
		<?php comments_template('', true); ?>
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
</div>
<div class="sidebar">
<?php new Sidebar("blogSidebar"); ?>
</div>
<?php get_footer(); ?>