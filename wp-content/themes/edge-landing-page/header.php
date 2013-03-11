<!DOCTYPE html>
<!-- COPYRIGHT 2012 HTTP://WEBDEV.LY; EDGEINCOLLEGEPREP.COM -->
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Basic Page Reqs
  ================================================== -->
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title(''); ?></title>
	<meta name="author" content="http://webdev.ly">

	<!-- Mobile-Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Web Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,400italic' rel='stylesheet' type='text/css'>

	<!-- Stylesheets -->
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/base.css'; ?>">
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/skeleton.css'; ?>">
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/layout.css'; ?>">
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/themes/theme.css'; ?>">

	<script src="<?php echo get_bloginfo('wpurl') . '/javascripts/modernizr-2.5.3.min.js'; ?>"></script>
	
	<script type="text/javascript"  src="<?php echo get_bloginfo('wpurl') . '/javascripts/jquery-1.7.1.min.js'; ?>"></script>
	<script type="text/javascript"  src="<?php echo get_bloginfo('wpurl') . '/javascripts/plugins.all.js'; ?>"></script>	
	<script type="text/javascript" src="<?php echo get_bloginfo('wpurl') . '/javascripts/jquery.address-1.4.min.js'; ?>"></script>
	
	<!-- Flex Slider -->
	<script src="<?php echo get_bloginfo('wpurl') . '/javascripts/jquery.flexslider-min.js'; ?>"></script>

	<!-- PrettyPhoto -->
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/prettyPhoto.css'; ?>" type="text/css" media="screen"/>
	<script type="text/javascript" src="<?php echo get_bloginfo('wpurl') . '/javascripts/jquery.prettyPhoto.js'; ?>"></script>	

	<!-- Superfish menu -->
	<link rel="stylesheet" media="screen" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/superfish.css'; ?>" /> 
	
	<script src="<?php echo get_bloginfo('wpurl') . '/javascripts/jquery.hoverIntent.minified.js'; ?>"></script> 
	<script src="<?php echo get_bloginfo('wpurl') . '/javascripts/superfish.js'; ?>"></script> 
	<script src="<?php echo get_bloginfo('wpurl') . '/javascripts/supersubs.js'; ?>"></script> 	
	<script type="text/javascript" src="<?php echo get_bloginfo('wpurl') . '/javascripts/shadedborder.js'; ?>"></script>
	
	<!-- JS Scripts -->	
	<script type="text/javascript">
		var SNAPPED = false;
		var SCROLLELEMENT = 'html, body';
		var SCROLLPADDING = 0;
		function scrollToElement(el) {				
			$(SCROLLELEMENT).stop().animate(
				{ scrollTop: el.offset().top - SCROLLPADDING }, 
				600
			);	
		}
		//In Page Scroll
		$(document).ready(function($) {	
			//prettyphoto
			$("a[class^='prettyPhoto']").prettyPhoto({social_tools:false});

			//Superfish menu
			$("ul.sf-menu").supersubs({ 
	            minWidth:    12,   // minimum width of sub-menus in em units 
	            maxWidth:    50,   // maximum width of sub-menus in em units 
	            extraWidth:  3 // extra width can ensure lines don't sometimes turn over 
	                               // due to slight rounding differences and font-family 
	        }).superfish();  // call supersubs first, then superfish, so that subs are 
	                         // not display:none when measuring. Call before initializing 
	                         // containing tabs for same reason. 

			//flex slider
			$('.flexslider_small').flexslider({
				directionNav:false,
				controlNav:true,
				slideshow: true,
			});

			$('.facebook').on('mouseenter', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/facebook_roll.png'; ?>');
			});
			
			$('.facebook').on('mouseleave', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/facebook.png'; ?>');
			});

			$('.twitter').on('mouseenter', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/twitter_roll.png'; ?>');
			});
			
			$('.twitter').on('mouseleave', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/twitter.png'; ?>');
			});

			$('.flickr').on('mouseenter', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/flickr.png'; ?>');
			});
	
			$('.flickr').on('mouseleave', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/flickr_roll.png'; ?>');
			});

			// Address change
			$.address.change(function(event) {				
				//------ Retrieve element at first path component.
				//------ Scroll to point.
				if(event.pathNames[0]) {
					//------ Other points.
					scrollToElement($("#" + event.pathNames[0]));
					$.address.title(event.pathNames[0] + " | Online SAT & ACT Test Prep Courses");
				}
			});
		
			//------ Setup in-page scrolling for .internal links.
			$('.internal').click(function(event) {
				event.preventDefault();
				//------ Retrieve subject
				var target = $(this.hash);
				if(target.length && this.hash) {
					var before = $.address.value();
					$.address.value(target.attr('id'));
					//------ Manually invoke if no change event
					if(before == $.address.value()) scrollToElement(target);
				} else {
					$.address.value($(this).attr('href'));
				}
				//Set class to 'active'
				if ($(this).hasClass('active'))
				{
                   $(this).removeClass('active');
                }
				else 
				{
                   $(this).parents().siblings().find('a').removeClass('active');
                   $(this).addClass('active');
				}
				return false;				
			});
			//------ Listen on scroll event to modify nav position as needed
			var nav = $('#nav');
			var the_window = $(window);
			var topmost_point = nav.offset().top;
			//------ Capture height of nav bar		
			SCROLLPADDING = nav.height() + $('#topheader').height();
			//------ Check whether to use html or body element for scrolling
			$('html, body').each(function () {
				var initScrollTop = $(this).attr('scrollTop');
				$(this).attr('scrollTop', initScrollTop + 1);
				if ($(this).attr('scrollTop') == initScrollTop + 1) {
					SCROLLELEMENT = this.nodeName.toLowerCase();
					$(this).attr('scrollTop', initScrollTop);
					return false;
				}
			});
			//Tiles hover animation
			$('.tile').each(function(){
			var $span = $(this).children('span');
			$span.css('bottom', "-"+$span.outerHeight()+'px');
			});
	
			var bottom = 0;
			
			$('.tile').hover(function(){
				var $span = $(this).children('span');
				if(!$span.data('bottom')) $span.data('bottom',$span.css('bottom'));
				$span.stop().animate({'bottom':0},250);
			},function(){
				var $span = $(this).children('span');
				$span.stop().animate({'bottom':$span.data('bottom')},250);
			});
		});	
	</script>
	
<!-- Modal Script -->		
	<script type="text/javascript">
	var modal = (function(){
		var 
		method = {},
		$overlay,
		$modal,
		$content,
		$close;

		// Center the modal in the viewport
		method.center = function () {
			var top, left;

			top = Math.max($(window).height() - $modal.outerHeight(), 0) / 2;
			left = Math.max($(window).width() - $modal.outerWidth(), 0) / 2;

			$modal.css({
				top:top + $(window).scrollTop(), 
				left:left + $(window).scrollLeft()
			});
		};

		// Open the modal
		method.open = function (settings) {
			$content.empty().append(settings.content);

			$modal.css({
				width: settings.width || 'auto', 
				height: settings.height || 'auto'
			});

			method.center();
			$(window).bind('resize.modal', method.center);
			$modal.show();
			$overlay.show();
		};

		// Close the modal
		method.close = function () {
			$modal.hide();
			$overlay.hide();
			$content.empty();
			$(window).unbind('resize.modal');
		};

		// Generate the HTML and add it to the document
		$overlay = $('<div id="overlay"></div>');
		$modal = $('<div id="modal"></div>');
		$content = $('<div id="modal_content"></div>');
		$close = $('<a id="modal_close" href="#">close</a>');

		$modal.hide();
		$overlay.hide();
		$modal.append($content, $close);

		$(document).ready(function(){
			$('body').append($overlay, $modal);						
		});

		$close.click(function(e){
			e.preventDefault();
			method.close();
		});

		return method;
	}());

	// Wait until the DOM has loaded before querying the document
	$(document).ready(function(){

		$('a#pointer1').click(function(e){
			modal.open({content: "<h3>Combines test prep AND admissions counseling.</h3>We don't only help you get the scores you need to get into your dream school, <br>we help you Get Accepted with our Advisory and Application packages as well. <br>The Edge team is with you every step of your college admissions journey.<br><br><a href=\"http://info.edgeincollegeprep.com/take-your-first-step-towards-acceptance-at-your-dream-school/\"><div class=\"c2a\">Get Started Today!</div></a>"});
			e.preventDefault();
		});
		$('a#pointer2').click(function(e){
			modal.open({content: "<h3>Customized program for each student based on specific needs.</h3>All students begin with our trademarked StratEDGEy Session, during which your advisor will assess<br> your strengths and weaknesses, determine which test (SAT, ACT, SAT Subject Tests) is right for you, <br>and will develop a better understanding of how much prep you'll need to achieve your goal scores.<br> From there, she will customize a study plan and/or Advisory Plan to help put you on the right path to <br>getting accepted to your dream schools.<br><br><a href=\"http://info.edgeincollegeprep.com/take-your-first-step-towards-acceptance-at-your-dream-school/\"><div class=\"c2a\">Get Started Today!</div></a>"});
			e.preventDefault();
		});
		$('a#pointer3').click(function(e){
			modal.open({content: "<h3>Unparalleled materials/teaching methods; Only REAL exams used.</h3>Over the past 7 years, Edge students have improved an average of 100 points for every 10<br> hours of prep they complete with us. How do we do it? Through our SAT/ACT Edge program.<br> All Edge students use a combination of The SAT/ACT Edge Guide, Online Course, and iPhone app.<br> Our instructors also have an uncanny combination of charisma and brilliance, and motivate their students<br> through innovative teaching methods. All instructors, who have graduated from top US universities<br> and have scored in at least the 98th percentile, complete an extensive hiring, training, and testing program,<br> and are personally certified by The Edge's Founder.<br><br><a href=\"http://info.edgeincollegeprep.com/take-your-first-step-towards-acceptance-at-your-dream-school/\"><div class=\"c2a\">Get Started Today!</div></a>"});
			e.preventDefault();
		});
		$('a#admission1').click(function(e){
			modal.open({content: "<h3>Advisory Services</h3>Your own personal private guidance counselor there to assist you with ALL<br> of the tough decisions in high school, including courseload, summer programs,<br> meaningful community service projects, leadership experience, the creation<br> of a school list, and even the more dramatic issues like expulsion and suspension.<br><br><a href=\"http://info.edgeincollegeprep.com/schedule-your-free-30-minute-consultation-today/\"><div class=\"c2a\">Get Started Today!</div></a>"});
			e.preventDefault();
		});
		$('a#admission2').click(function(e){
			modal.open({content: "<h3>Application Packages</h3>Assistance with every aspect of your college application, including the creation of an application<br> timeline, the entry of student/family data, the brainstorming/outlining/perfecting ALL of your<br> essays, development of the ideal resume/CV, selection of ideal references, and honing ideal<br> interviewing skills.<br><br><a href=\"http://info.edgeincollegeprep.com/schedule-your-free-30-minute-consultation-today/\"><div class=\"c2a\">Get Started Today!</div></a>"});
			e.preventDefault();
		});
		$('a#admission3').click(function(e){
			modal.open({content: "<h3>Special Cases</h3>Special cases are our specialty. Whether you are an NCAA recruited athlete or<br> an elite musician looking to apply to the top seminaries, The Edge has the<br> perfect advisor to assist you with all of the intricate components of the application<br> process. With international team members in London, Frankfurt, Buenos Aires,<br> and Rio de Janeiro, we have become experts at working with international students<br> and have assisted students from over 13 countries. Our Global Elite team can help<br> you with everything from in-person, Skype, or travel tutoring, admissions counseling, <br>and every aspect of the application process.<br><br><a href=\"http://info.edgeincollegeprep.com/schedule-your-free-30-minute-consultation-today/\"><div class=\"c2a\">Get Started Today!</div></a>"});
			e.preventDefault();
		});
		$('a#login').click(function(e){
			e.preventDefault(); /*prevents the link  with id 'page2' from opening href '#' */
		  $.get('login.php', function(data){  /*gets the data from mypage2.html*/
		    modal.open({content: data}); /*fills in the contents of the modal from the data in mypage2.html*/
		  })
		});
		$('a#playvideo').click(function(e){
			e.preventDefault(); /*prevents the link  with id 'page2' from opening href '#' */
		  $.get('play_video.html', function(data){  /*gets the data from mypage2.html*/
		    modal.open({content: data}); /*fills in the contents of the modal from the data in mypage2.html*/
		  })
		});
	});
</script>	
	<script type="text/javascript" src="<?php echo get_bloginfo('wpurl') . '/javascripts/jquery.all.libraries.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo get_bloginfo('wpurl') . '/javascripts/sliders.js'; ?>"></script>	
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php wp_head() ?>
	<!-- Begin Inspectlet Embed Code -->
	<script type="text/javascript" id="inspectletjs">
		var __insp = __insp || [];
		__insp.push(['wid', 1342062011]);
		(function() {
			function __ldinsp(){var insp = document.createElement('script'); insp.type = 'text/javascript'; insp.async = true; insp.id = "inspsync"; insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://www.inspectlet.com/inspect/1342062011.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(insp, x); }
			if (window.attachEvent){
				window.attachEvent('onload', __ldinsp);
			}else{
				window.addEventListener('load', __ldinsp, false);
			}
		})();
	</script>
	<!-- End Inspectlet Embed Code -->
</head>
<body data-spy="scroll" data-target=".smartnav">	
	<div id="Top"></div>
	<div class="band header" id="topheader">
		<header class="main container">	
			<h1 class="logo"><a href="<?php echo get_bloginfo('wpurl'); ?>">The Edge in College Prep - Online SAT & ACT Test Prep</a></h1>			
			<div class="social_menu"> 
				<ul>
					<li><a href="http://www.facebook.com/edgeincollegeprep" target="_blank" class="facebook"><img src="<?php echo get_bloginfo('wpurl') . '/images/facebook.png'; ?>" alt="Keep in Touch with The Edge on Facebook"></a></li>
					<li><a href="http://www.twitter.com/edgecollegeprep" target="_blank" class="twitter"><img src="<?php echo get_bloginfo('wpurl') . '/images/twitter.png'; ?>" alt="Keep in Touch with The Edge in College Prep on Twitter"></a></li>
					<li><a id="login" href="#" target="_blank" class="flickr" style=""><img src="<?php echo get_bloginfo('wpurl') . '/images/flickr_roll.png'; ?>" alt="Online Test Prep Login"></a></li>
				</ul>
			</div>
		</header>	
	</div>	
	<div id="tray">
		<div id="nav" class="band navigation">
			<div class="smartnav">
				<nav class="primary container">
					<ul id="navLinks" class="nav">		
						<li>
							<a href="#Top" class="internal active">Get Accepted</a>
						</li>
						<li><a href="#Why-The-Edge" class="internal">Why The Edge?</a></li>
						<li><a href="#Tutoring" class="internal">Test Prep</a>
						</li>				
						<li><a href="#Admissions" class="internal">Admissions Counseling</a>
						</li>
						<li><a href="#Testimonials" class="internal">Testimonials</a></li>								
						<li><a href="#" class="internal">Resources</a>
							<ul>
								<li><a href="http://info.edgeincollegeprep.com/blog"><span>- </span>Our Blog</a></li>
								<li><a href="http://edgeincollegeprep.zendesk.com/home"><span>- </span>FAQ</a></li>
								<li><a href="<?php echo get_bloginfo('wpurl') . '/reach-out/contact-us/'; ?>"><span>- </span>Contact Us</a></li>						
							</ul>
							</li>	
					</ul>		
				</nav>
			</div>
		</div>
	</div>