		<!--Footer Section-->
		<div class="band bottom">
			<center><a href="<?php echo get_bloginfo('wpurl') . '/our-locations/new-york/'; ?>">New York</a> <font color="#2b414e">-</font> <a href="<?php echo get_bloginfo('wpurl') . '/our-locations/london/'; ?>">London</a> <font color="#2b414e">-</font>  <a href="<?php echo get_bloginfo('wpurl') . '/our-locations/rio-de-janeiro/'; ?>">Rio de Janeiro</a> <font color="#2b414e">-</font> <a href="<?php echo get_bloginfo('wpurl') . '/our-locations/buenos-aires'; ?>">Buenos Aires</a> <font color="#2b414e">-</font>  <a href="<?php echo get_bloginfo('wpurl') . '/our-locations/south-florida/'; ?>">South Florida</a> <font color="#2b414e">-</font> <a href="<?php echo get_bloginfo('wpurl') . '/our-locations/global-elite/'; ?>">Global Elite</a></center>
			</div>
	<div class="content6" id="Resources">
	<br />
		<footer class="main container">
			<div class="four columns">
				<div class="footer_title">
					<h4>About us</h4>
				</div>
			<a href="<?php echo get_bloginfo('wpurl') . '/about/our-story/'; ?>">Our Story</a><br/>
			<a href="<?php echo get_bloginfo('wpurl') . '/about/meet-the-team/'; ?>">Meet the Team</a><br/>
			<a href="<?php echo get_bloginfo('wpurl') . '/about/press/'; ?>">Press</a><br/>
			<a href="<?php echo get_bloginfo('wpurl') . '/about/partners/'; ?>">Partners</a><br/>
			<a href="http://info.edgeincollegeprep.com/">Our Blog</a><br/>
			</div>
			<div class="four columns">
				<div class="footer_title">					
					<h4>Resources</h4>					
				</div><div itemscope itemtype="http://schema.org/SoftwareApplication">			
				<a href="<?php echo get_bloginfo('wpurl') . '/resources/tutoring-partnerships/'; ?>">For Tutors & Schools</a><br/>
				<a href="<?php echo get_bloginfo('wpurl') . '/resources/about-the-tests/'; ?>">About the Tests</a><br/>
				
				<a itemprop="url" href="https://itunes.apple.com/us/app/the-sat-act-edge/id554554652?mt=8" target="itunes_store"><span itemprop="name">SAT/ACT <span itemprop="operatingSystems">iOS</span> App</span> <img itemprop="image" src="http://r.mzstatic.com/images/web/linkmaker/badge_appstore-sm.gif" alt="SAT & ACT Test Prep App for iOS" style="border: 0;"/></a><br/>
				<a href="<?php echo get_bloginfo('wpurl') . '/resources/sat-v-act-score-comparison/'; ?>">SAT vs ACT Comparison</a><br/>
			</div></div>
			<div class="four columns">
				<div class="footer_title">					
					<h4>Reach Out</h4>					
				</div>			
				<a href="<?php echo get_bloginfo('wpurl') . '/reach-out/contact-us/'; ?>">Contact Us</a><br/>
				<a href="<?php echo get_bloginfo('wpurl') . '/reach-out/careers/'; ?>">Careers</a><br/>
				<a href="http://edgeincollegeprep.zendesk.com">FAQ/Help</a><br/>											
			</div>
			<div class="four columns clearfix">
				<div class="footer_title">				
					<h4>Legal</h4>					
				</div>				
				<a href="<?php echo get_bloginfo('wpurl') . '/legal/privacy-policy'; ?>">Privacy Policy</a><br />
				<a href="<?php echo get_bloginfo('wpurl') . '/legal/terms-and-conditions'; ?>">Terms and Conditions</a><br /><br /><br />
				<h6 class="phone">1.877.499.EDGE</h6>				
				<h6 class="email">Mon–Fri: 10am EST to 6pm EST</h6>
			</div>
		</footer>
	</div>
<div class="content6">
		<footer class="bottom container">
		<br /><br />
			<div class="eight columns first-credit">
				<p>Copyright &copy; 2012 | <a href="http://edgeincollegeprep.com">The Edge in College Prep</a> | All Rights Reserved.</p>
			</div>
			<div class="eight columns last-credit">
				<p>SAT & ACT Test Prep and Admissions Counseling</p>			
			</div>
			</div>
		</footer>
		<script type="text/javascript" src="//assets.zendesk.com/external/zenbox/v2.4/zenbox.js"></script>
		<style type="text/css" media="screen, projection">
		  @import url(//assets.zendesk.com/external/zenbox/v2.4/zenbox.css);
		</style>
		
		<script type="text/javascript">
		  if (typeof(Zenbox) !== "undefined") {
		    Zenbox.init({
		      dropboxID:   "20113738",
		      url:         "https://edgeincollegeprep.zendesk.com",
		      tabID:       "support",
		      tabColor:    "#e4631a",
		      tabPosition: "Left"
		    });
		  }
		</script>
		
		<!-- Start of Async HubSpot Analytics Code -->
		<script type="text/javascript">
			(function(d,s,i) {
			if (d.getElementById(i)){return;}
			var n = d.createElement(s),e = document.getElementsByTagName(s)[0];
			n.id=i;n.src = '//js.hubspot.com/analytics/172914.js';
			e.parentNode.insertBefore(n, e);
			})(document, "script", "hs-analytics");
		</script>
		<!-- End of Async HubSpot Analytics Code -->
		
		<!-- Start Google Analytics -->
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
		<!-- End Google Analytics -->
		
		<!-- The Last of the JS... -->
		<script src="<?php echo get_bloginfo('wpurl') . '/javascripts/tabs.js'; ?>"></script>
		<script type="text/javascript">
		$(document).ready(function() {	
				
				// Create the dropdown bases
				$("<select />").appendTo(".navigation nav");
				
				// Create default option "Go to..."
				$("<option />", {
				   "selected": "selected",
				   "value"   : "",
				   "text"    : "Go to..."
				}).appendTo("nav select");

				// Populate dropdowns with the first menu items
				$(".navigation nav li a").each(function() {
				 	var el = $(this);
				 	$("<option />", {
				     	"value"   : el.attr("href"),
				    	"text"    : el.text()
				 	}).appendTo("nav select");
				});
				
				//make responsive dropdown menu actually work			
		      	$("nav select").change(function() {
		        	window.location = $(this).find("option:selected").val();
		      	});
		});
	</script>
	
	<!-- The End -->
</body>
</html>