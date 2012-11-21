<?php get_header(); ?>
<style type="text/css">
</style>
<script type="text/javascript">
	jQuery(function(){
		jQuery(".faqContainer .entry").hide();
		jQuery(".faqContainer .title").click(function(e){
			e.preventDefault();
			jQuery(this).parent().find(".entry").slideToggle();
			jQuery(this).addClass('active-list-item');
		});
		var hash=window.location.hash;
		if(hash!=""){
			jQuery(hash).find(".entry").slideDown();
			jQuery(hash).find('.title').addClass('active-list-item');
		}
	})
</script>
<div class="leftcolumn">
<?php 
if (have_posts()) : ?>
<div class="faqContainer">
		<div class="title faq-title">
        	<h1>F.A.Q.</h1>
        </div>
<?php while (have_posts()) : the_post(); ?>

	<div id="faq-<?php the_ID(); ?>" class="faq">
        <div class="title">
            <h1><a href="#"><?php the_title(); ?></a></h1>
        </div>
        <div class="entry" style="display:none;">
			<?php the_content() ; ?> <br/> <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
        </div>
		<div class="clear"></div>
	</div>
	<?php endwhile; ?>
</div>
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
	
	<?php new Sidebar("Sidebar4"); ?>
	
</div>
<?php get_footer(); ?>