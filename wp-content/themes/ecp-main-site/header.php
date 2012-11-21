<?php //if ( !is_user_logged_in() && !isset($_GET["f_side"]) ) { 
	//header("Location:http://edgeincollegeprep.com/edgev2/");
 //} ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ){
		echo " | $site_description";
	}
	if ( $paged >= 2 || $page >= 2 ){
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );
	}
	?></title>
	<script type="text/javascript" src="<?php echo bloginfo('template_directory') .'/js/domtab.js'; ?>"></script>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php if (is_single()){ ?>
		<!--[if IE 7]>
			<style type="text/css">
				html{
					overflow-x:hidden;	
				}
			</style>
		<![endif]-->
	<?php } ?>
	<?php
		if (is_single()){ 
			wp_enqueue_script('comment-reply');
		}
		wp_head();
	?>
	<?php 
		wp_enqueue_script('jquery');
	?>
</head>
<body>

<div id="header">
	<div class="container-wide">
    	
        <a class="left" id="logo" href="<?php bloginfo('url'); ?>"></a>
        
        <div class="right">
            <form action="<?php bloginfo('url'); ?>" id="search-box" method="get">
                <input type="text"  name="s" class="searchfield" />
                <input type="submit" class="searchsubmit" value="" />
            </form>
            <div class="language_select">
            	<div id="google_translate_element"></div>
            	<script>
					function googleTranslateElementInit() {
					  new google.translate.TranslateElement({
					    pageLanguage: 'en'
					  }, 'google_translate_element');
					}
				</script>
				<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            	<?php /*if(function_exists("transposh_widget")) { transposh_widget(); }*/ ?>
            </div>
            <div id="login_menu">
				<!-- <a href="#" class="button blue">Login</a> -->
            </div>
        </div>
        
        <div class="clear"></div>
        
        <div id="menupacker">
        	  <?php 
        	  switch_to_blog(1);
        	  wp_nav_menu( array( 'theme_location'=>'ecp-main-site', 'container' => '', 'menu' => 'Main Menu', 'menu_id' => 'dropmenu' , 'menu_class' => 'menu', 'before' => '', 'after' => '', 'fallback_cb' => '' ) ); 
        	  restore_current_blog();
        	  ?>
        </div>
    </div>
</div><!-- end header -->

<div id="content">
    <div class="container">
      <div class="widecolumn">