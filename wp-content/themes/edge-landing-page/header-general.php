<!DOCTYPE html>
<!-- COPYRIGHT 2012 WEBDEV.LY; EDGEINCOLLEGEPREP.COM -->
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Basic Page Reqs
  ================================================== -->
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<!-- <title><?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ){
		echo " | $site_description";
	}
	if ( $paged >= 2 || $page >= 2 ){
		echo ' | ' . sprintf( __( 'Page %s', 'edge' ), max( $paged, $page ) );
	}
	?></title> -->
	<title><?php wp_title(''); ?></title>
	<meta name="author" content="Webdev.ly">

	<!-- Mobile-Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Web Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,400italic' rel='stylesheet' type='text/css'>

	<!-- RESOURCE FILES & STUFF
  ================================================== -->
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/base.css'; ?>">
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/skeleton.css'; ?>">
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/layout-general.css'; ?>">
	<link rel="stylesheet" href="<?php echo get_bloginfo('wpurl') . '/stylesheets/themes/theme-general.css'; ?>">

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
		//------ Keep track of whether the navigation is fixed in place.
		var SNAPPED = false;
		var SCROLLELEMENT = 'html, body';
		var SCROLLPADDING = 0;
		function scrollToElement(el) {				
			$(SCROLLELEMENT).stop().animate(
				{ scrollTop: el.offset().top - SCROLLPADDING }, 
				600
			);	
		}

		//In Page Scroll, Portfolio filter
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
	                         // not display:none when measuring. Call before initialising 
	                         // containing tabs for same reason. 

			//flex slider
			$('.flexslider_small').flexslider({
				directionNav:false,
				controlNav:true,
				slideshow: true,
			});

			//Tooltips
			function showSocialToolTip(e, img){
				var path = "images/" + img;
				$('#tooltip').css({'top':e.pageY+10, 'left':e.pageX-110, 'background':'none'});
				$('#tooltip').html('<img src="' + path + '" alt="tooltip">');	
				$('#tooltip').show();	
			}
				
			function hideSocialToolTip(){
				$('#tooltip').hide();
			}
			function moveSocialToolTip(e){
				$('#tooltip').show();
				$('#tooltip').css({'top':e.pageY+10, 'left':e.pageX-110});
			}

			$('.facebook').on('mouseenter', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/facebook_roll.png'; ?>');
				showSocialToolTip(e, $(this).attr('data-tooltip'));
			});
			
			$('.facebook').on('mouseleave', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/facebook.png'; ?>');
				hideSocialToolTip();
			});
			
			$('.facebook').on('mousemove', function(e){
				moveSocialToolTip(e);
			});
			
			$('.twitter').on('mouseenter', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/twitter_roll.png'; ?>');
				showSocialToolTip(e, $(this).attr('data-tooltip'));
			});
			
			$('.twitter').on('mouseleave', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/twitter.png'; ?>');
				hideSocialToolTip();
			});
			
			$('.twitter').on('mousemove', function(e){
				moveSocialToolTip(e);
			});
					
			$('.flickr').on('mouseenter', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/flickr_roll.png'; ?>');
				showSocialToolTip(e, $(this).attr('data-tooltip'));
			});
	
			$('.flickr').on('mouseleave', function(e){
				$(this).find('img').attr('src', '<?php echo get_bloginfo('wpurl') . '/images/flickr.png'; ?>');
				hideSocialToolTip();
			});
			
			$('.flickr').on('mousemove', function(e){
				moveSocialToolTip(e);
			});		

			// Address change
			$.address.change(function(event) {				
				//------ Retrieve element at first path component.
				//------ Scroll to point.
				if(event.pathNames[0]) {
					//------ Other points.
					scrollToElement($("#" + event.pathNames[0]));
					$.address.title("Online SAT/ACT Testing Prep | " + event.pathNames[0]);
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
			$('a#login').click(function(e){
				e.preventDefault(); /*prevents the link  with id 'page2' from opening href '#' */
			  $.get('login.php', function(data){  /*gets the data from mypage2.html*/
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

<body>	
	<div class="header" id="topheader">
		<div id="Top"><div id="Top-container"><a id="login" href="#">Login</a> or <a href="<?php echo get_bloginfo('wpurl') . '/enroll/cart/'; ?>">Register</a></div></div>
		<header class="main container">	
			<h1 class="logo"><a href="<?php echo get_bloginfo('wpurl'); ?>">The Edge in College Prep</a></h1>	
			<div id="tray">
				<div id="nav" class="band navigation">
					<div>
						<nav class="primary container">
							<ul id="navLinks" class="sf-menu">		
								<li><a href="<?php echo get_bloginfo('wpurl') . '/#/Tutoring/'; ?>">Test Prep</a></li>
								<li><a href="<?php echo get_bloginfo('wpurl') . '/#/Admissions/'; ?>">Admissions Counseling</a></li>
								<li><a href="<?php echo get_bloginfo('wpurl') . '/resources/about-the-tests/'; ?>">Resources</a></li>
								<li><a href="http://edgeincollegeprep.zendesk.com/">Get Support</a></li>
							</ul>		
						</nav>
					</div>
				</div>
			</div>		
		</header>	
	</div>	