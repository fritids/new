<?php
if(is_user_logged_in()){
	$current_user = wp_get_current_user();
}

if(Util::curPageURL()=="http://edgeincollegeprep.com/portal/profile/"){
	if(is_user_logged_in()){
		wp_redirect(get_bloginfo("url")."/dashboard/".$current_user->user_login."/profile/");
		die();
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

		<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css" rel="stylesheet" media="all">
		<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap-responsive.css" rel="stylesheet" media="all">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<!--[if IE]><link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie.css" type="text/css" media="screen, projection" /><![endif]-->

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php
		if (is_single()) wp_enqueue_script('comment-reply');
		wp_head();
		?>

		<?php wp_enqueue_script('jquery'); ?>
		<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.js"></script>
		<style type="text/css" media="screen">
			html { margin-top: 0 !important; }
			* html body { margin-top: 0 !important; }
		</style>
	</head>
<body <?php body_class(); ?>>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner top-nav full-fluid">
			<div class="container-fluid">
				<div class="branding">
					<div class="logo"><a href="index.html">The Edge Logo</a></div>
				</div>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							Hello <?php echo $current_user->user_login ?> <i class="white-icons admin_user"></i><b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="#"><i class="icon-inbox"></i>Inbox<span class="alert-noty">0</span></a></li>
							<li><a href="#"><i class="icon-envelope"></i>Notifications<span class="alert-noty">4</span></a></li>
							<li><a href="#"><i class="icon-cog"></i>Account Settings</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo esc_url( wp_logout_url('') ) ?>"><i class="icon-off"></i><strong>Logout</strong></a></li>
						</ul>
					</li>
				</ul>
				<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class="dropdown"><a href="index.html" class="dropdown-toggle"><i class="nav-icon frames"></i> Dashboard </a></li>
						<li class="dropdown"><a href="edge.html" class="dropdown-toggle" data-toggle="dropdown"><i class="nav-icon cup"></i> SAT/ACT Edge<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a class="iframe" href="#inline_content">Customize Study Plan</a></li>
								<li><a href="edge.html">My Dashboard</a></li>
								<li><a href="edge.html">Take Practice Tests</a></li>
								<li class=" divider"></li>
								<li class="nav-header">My Shortcuts</li>
								<li><a href="edge.html">Reading</a></li>
								<li><a href="edge.html">Writing</a></li>
								<li><a href="edge.html">Math</a></li>
								<li><a href="edge.html">Essay</a></li>
							</ul>
						</li>
						<li class="dropdown"><a href="#" class="dropdown-toggle"><i class="nav-icon books"></i> Test Prep Tutoring</a></li>
						<li class="dropdown"><a href="#" class="dropdown-toggle"><i class="nav-icon sign_post"></i> Admissions Counseling</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	
	
	<!--div id="header">
		<div class="container">
			<div class="left" id="logo">
			</div>
			<div class="language_select"></div>
			<div id="login_menu" class="right">
				<ul><li id="rss"><?php //ECPUser::getUserMenu(); ?></li></ul>
			</div>
			<div class="back_to_site_button">
				<a href="http://edgeincollegeprep.com">Back to the site</a>
			</div>
			<div class="clear"></div>
			<?php //if(is_user_logged_in()){ ?>
			<div id="menupacker">

				<?php /*if(ECPUser::getSubType()=="teacher"){ ?>
					<ul class="menu" id="dropmenu">
						<li><a href="#">Teacher menu goes here - To-Do</a></li>
					</ul>
				<?php }else{ */?>
				<ul class="menu" id="dropmenu">
					<li><a href="#">Reading</a>
						<?php //wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Reading' ) ); ?>
					</li>
					<li><a href="#">Math</a>
						<ul>
							<li><a href="#">Arithmetic</a>
								<?php //wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Arithmetic' ) ); ?>
							</li>
							<li><a href="#">Algebra</a>
								<?php //wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Algebra' ) ); ?>
							</li>
							<li><a href="#">Geometry</a>
								<?php //wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Geometry' ) ); ?>
							</li>
							<li><a href="#">Advanced Topics</a>
								<?php //wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Math - Advanced Topics' ) ); ?>
							</li>
						</ul>
					</li>
					<li><a href="#">Writing</a>
						<?php //wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Writing' ) ); ?>
					</li>
					<li><a href="#">Essay</a>
						<?php //wp_nav_menu( array( 'menu_class' => 'menu', 'container' => '', 'menu'=>'Essay' ) ); ?>
					</li>
				</ul>
				<?php // } ?>

			</div>
			<?php //} ?>
		</div>
	</div--><!-- end header -->
<div id="main-content" class="full-fluid">
    <div class="container-fluid">
		<ul class="breadcrumb">
			<?php
			if(function_exists('bcn_display')) {
				bcn_display();
			}
			?>
		</ul>
		
    <div class="leftcolumn widecolumn">