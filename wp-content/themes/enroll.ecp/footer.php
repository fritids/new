
</div><!-- end container -->
<?php /* switch_to_blog(1); ?>
<div id="footer">
	<div class="container">
		<div class="before-desclamer">
		<div class="left left-width">
			<div class="subscribe">
				<h3 class="left coloring-green">Connect:</h3>
	            <a href="<?php echo getThemeMeta('rssLink','','_SocialMedia'); ?>" target="_blank" class="rss"></a>
	            <a href="#" onclick="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo getThemeMeta('feedID','','_SocialMedia'); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" class="email-social"></a>
	            <a href="<?php echo getThemeMeta('twitterAccountinfo','','_SocialMedia'); ?>" target="_blank" class="tw"></a>
	            <a href="<?php echo getThemeMeta('facebookAccountinfo','','_SocialMedia'); ?>" target="_blank" class="fb"></a>
	            <div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div class="footer-menu-holder">
				<h3 class="left coloring-green">TEST PREP NEAR YOU:</h3>
				<div class="footer-menu">
					<?php wp_nav_menu( array( 'container' => '', 'menu' => 'TEST_PREP_NEAR_YOU', 'menu_id' => 'flat1' , 'menu_class' => 'flatmenu', 'before' => '', 'after' => '', 'fallback_cb' => '' ) ); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="right right-width">
			<div class="right-footer-info">
				<span class="phone-num-footer"><?php echo getThemeMeta('footer_phone_numb','','_FooterText'); ?></span>
				<span class="footer-date"><?php echo getThemeMeta('footer_date_numb','','_FooterText'); ?></span>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="the_footer_menu">
    	<?php 

					$mitems=wp_get_nav_menu_items("Main Menu");
					$wpm=new Wp_Menu($mitems);
					echo $wpm->toString();
				
			?>
			<div class="clear" style="clear:both !important;float:none !important;"></div>
    </div>
	
	<div class="clear"></div>
		<?php echo getThemeMeta('FooterText','','_FooterText'); ?>
	    <a href="http://edgeincollegeprep.com/privacy-policy/" target="_blank">Privacy Policy</a> &amp; 
	    <a href="http://edgeincollegeprep.com/terms-and-conditions/" target="_blank">Terms and Conditions</a>
    </div>
</div><!-- end #footer -->
<?php 
restore_current_blog(); */ ?>
<? wp_footer(); ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22985057-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
