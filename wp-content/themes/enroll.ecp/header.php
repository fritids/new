<?php
$ua = $wp_query -> query_vars["user_action"];
if($ua == "shopping-cart")
{
	ob_start();
	require_once IDG_CLASS_PATH."ShoppingCart/class.ECPShopManager.php";
	require_once IDG_CLASS_PATH."Utils/class.SessionUtils.php";
	global $ecp_cart;
	global $session_utils;
	global $current_user; 
	
	$current_user = wp_get_current_user();
	$ecp_cart = new ECPShopManager();
	$session_utils = new SessionUtils("UserData");
}

if($ua = "tryout")
{
	global $tryout_session;
	global $errors;
	require_once IDG_CLASS_PATH."Utils/class.SessionUtils.php";
	require_once IDG_CLASS_PATH."Templator/class.Templator.php"; 
	$errors = new SessionUtils("ErrorsData");
	$tryout_session = new SessionUtils("TryoutData");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<!--  <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles.php" type="text/css" media="screen" /> -->
		<?php
			if (is_single()) wp_enqueue_script('comment-reply');
				wp_head();
		?>
		<?php wp_enqueue_script('jquery'); ?>
		<title>ECP Enroll</title>
		<?php 
			wp_register_script('json2', IDGL_THEME_URL . '/lib/js/json2.js');
			wp_enqueue_script('json2');
		?>
	</head>
	
	<body <?php body_class(); ?>>
		<header>
			<h1><a href="<?php bloginfo('url'); ?>/registration">THE EDGE in College Prep</a></h1>
		</header>
		
		<div id="sub-title-bar">
			<h2>ACCOUNT REGISTRATION</h2>
			<div>Register & Receive a FREE testing trial</div>
		</div>
		
		<div class="container">