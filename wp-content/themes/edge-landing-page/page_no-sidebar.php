<?php
/* 
  Template Name: Page NO Sidebar
*/ 
?>
<?php get_template_part( 'header', 'general' ); ?> 
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="container">
<div id="round-container"><br />
<h3><span><?php the_title(); ?></span></h3>
<div class="content-inner">
<?php echo the_content(); ?>
<?php endwhile; endif; ?>
</div>
</div>
</div>
<script language="javascript" type="text/javascript">
  var myBorder = RUZEE.ShadedBorder.create({ corner:8, shadow:16 });
  myBorder.render('round-container');
</script>
<div id="footer-school"><img src="<?php echo get_bloginfo('wpurl') . '/images/small_school.png'; ?>" alt="Online SAT and ACT Test Prep for Students"></div>
<?php get_footer(); ?>