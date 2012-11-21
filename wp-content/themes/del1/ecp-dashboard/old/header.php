<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <!--[if IE]><link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<!--  <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles.php" type="text/css" media="screen" />
	-->
	<?php
	if (is_single()) wp_enqueue_script('comment-reply');
	wp_head();
	?>
	<?php wp_enqueue_script('jquery'); ?>
	<script type="text/javascript">
		jQuery(function(){
			jQuery("#dropmenu li").hover(function(){
				if(jQuery(this).hasClass("hoverClass")){
					jQuery(this).removeClass("hoverClass")
				}else{
					jQuery(this).addClass("hoverClass")
				}
			})
		})
		jQuery(function(){
			jQuery("#dropmenu li ul li").click(function(e){
				//e.preventDefault();
				e.stopPropagation()
				if(jQuery(this).hasClass("opened")){
					jQuery(this).find("ul:first").slideUp();
					jQuery(this).removeClass("opened")
				}else{
					jQuery(this).find("ul:first").slideDown();
					jQuery(this).addClass("opened")
				}
			})
		})
		jQuery(document).ready(function() {
		jQuery("#dropmenu li li li").hover(function(){
				jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).show(268);
				},function(){
				jQuery(this).find('ul:first').css({visibility: "hidden"});
				});
				jQuery('.menu li li li').hover(function(){
					jQuery(this).addClass('hoverClass');
				},function(){
					jQuery(this).removeClass('hoverClass');
				});
				jQuery('.menu li li li').hover(function(){
					jQuery(this).addClass('hoverClass');
				},function(){
					jQuery(this).removeClass('hoverClass');
				});
		});
	</script>
</head>
<body <?php body_class(); ?>>
<div id="header">
	<div class="container">
        <div class="left" id="logo">
        </div>
        <div id="login_menu" class="right">
            <li id="rss"><?php ECPUser::getUserMenu(); ?></li>
        </div>
        <div class="clear"></div>
        <div id="menupacker">
			
			<?php if(ECPUser::getSubType()=="teacher"){ ?>
				<ul class="menu" id="dropmenu">
					<li><a href="#">Teacher menu goes here - To-Do</a></li>
				</ul>
			<?php }else{ ?>
			<ul class="menu" id="dropmenu">
				<li><a href="#">Reading</a>
					<?php wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Reading' ) ); ?>
				</li>
				<li><a href="#">Math</a>
					<ul>
						<li><a href="#">Arithmetic</a>
							<?php wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Arithmetic' ) ); ?>
						</li>
						<li><a href="#">Algebra</a>
							<?php wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Algebra' ) ); ?>
						</li>
						<li><a href="#">Geometry</a>
							<?php wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Geometry' ) ); ?>
						</li>
						<li><a href="#">Advanced Topics</a>
							<?php wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Advanced Topics' ) ); ?>
						</li>
					</ul>
				</li>
				<li><a href="#">Writing</a>
					<?php wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Writing' ) ); ?>
				</li>
				<li><a href="#">Essay</a>
					<?php wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Essay' ) ); ?>
				</li>
			</ul>
			<?php } ?>
			
        </div>
    </div>
</div><!-- end header -->
<div id="content">
    <div class="container">
    <div class="leftcolumn widecolumn">