<?php get_template_part( 'header', 'refresh' ); ?> 
<div class="container">
<div id="round-container"><br />
<h3><span>That page is...missing.</span></h3>
<div class="content-inner">
	<br />
	<div class="post">    
		<h2>It appears that the page you're looking for seems to have vanished into thin air. Either that, or it never existed in the first place. If you ask us, it was the latter.</h2><br />
		<div style="float:right;">Feel free to <a href="http://edgeincollegeprep.com/contact-us/">Contact Us</a> if you need further assistance.</div><br /><br /><br /><br />
		<div>
		<center>You will be automatically redirected to our home page here in 5 seconds...</center>
		</div>
        <?php if ( function_exists('google404') ) google404(); ?>
	</div>
<br /><br /><br /><br /><br /><br />
</div>
</div>
</div>
<script language="javascript" type="text/javascript">
  var myBorder = RUZEE.ShadedBorder.create({ corner:8, shadow:16 });
  myBorder.render('round-container');
</script>
<div id="footer-school"><img src="<?php echo get_bloginfo('wpurl') . '/images/small_school.png'; ?>" alt="Online SAT and ACT Test Prep for Students"></div>
<?php get_footer(); ?>