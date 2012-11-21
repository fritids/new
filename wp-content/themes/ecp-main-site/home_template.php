<?php
/* 
  Template Name: Home Template
*/ 

?>
<?php get_header(); ?>
<div class="leftcolumn home">
	<div class="ecp-video">
		<?php IDGL_promoVideo(); ?>
	</div>
	<?php get_slider("testimonialsSlider","testimonials",200,190,true); ?>
	<div class="clear"></div>
	<div class="sidebar home-sidebar">
		<div class="announcement">
			<?php echo getThemeMeta('AnnouncementText',"","_Settings"); ?>
			<div class="clear"></div>
			<a href="<?php echo getThemeMeta('actual_link',"","_Settings"); ?>" class="button right"><?php echo getThemeMeta('link_text',"","_Settings"); ?></a>
		</div>
		<?php new Sidebar("Sidebar2"); ?>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
       