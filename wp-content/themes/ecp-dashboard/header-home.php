<?php
if(is_user_logged_in()){
	$current_user = wp_get_current_user();
	add_filter( 'show_admin_bar', '__return_false' );
	
    // Access control just when user is a student.
    $user_type = get_user_meta($current_user->ID,'_IDGL_elem_user_type',true);
    if($user_type != 'teacher'){
        // Verify students orders expiration
        if(Util::curPageURL()!= home_url('/')."lostpassword/" && Util::curPageURL()!= home_url('/')."login/"  && Util::curPageURL()!= home_url('/')."order-expired/"){
            $access = false;
            $orders_details = get_user_meta($current_user->ID, "_IDGL_elem_ECP_user_orders_details", true);
            switch_to_blog(3);
            foreach($orders_details as $order_date=>$order) {
                foreach($order as $product) {
                    $coverage = get_post_meta( $product['id'], 'time_coverage', true);
                    $coverage_measure = get_post_meta( $product['id'], 'time_measure', true);
                    
                    if($coverage && $coverage_measure) {
                        $expiration = strtotime(date("Y-m-d", $order_date) . " +{$coverage} {$coverage_measure}");
                        if($expiration > time()) {
                            $access = true;
                        }
                    }
                }
            }
            restore_current_blog();

            // 5 days of free trial
            if(strtotime(date("Y-m-d", strtotime($current_user->user_registered)) . " +5 days") > time()) {
                $access = true;
            }

            if(! $access)
                wp_redirect(home_url('/')."order-expired/");
        }
    }
} else {
	if(Util::curPageURL()!= home_url('/')."lostpassword/" && Util::curPageURL()!= home_url('/')."login/"){
		wp_redirect(home_url('/')."login/");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

		<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css" rel="stylesheet" media="all" />
		<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap-responsive.css" rel="stylesheet" media="all" />
		<link href="<?php echo get_template_directory_uri(); ?>/css/jquery.selectBox.css" rel="stylesheet" media="all" />
		<link href="<?php echo get_template_directory_uri(); ?>/css/jquery.fancybox-1.3.4.css" rel="stylesheet" media="all">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<!--[if IE]><link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie.css" type="text/css" media="screen, projection" /><![endif]-->
        <link href="<?php echo get_template_directory_uri(); ?>/css/TableTools.css" rel="stylesheet" media="all" />
        <link href="<?php echo get_template_directory_uri(); ?>/css/demo_table_jui.css" rel="stylesheet" media="all" />        

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php
		if (is_single()) wp_enqueue_script('comment-reply');
		wp_head();
		?>

		<script src="<?php echo get_template_directory_uri(); ?>/lib/js/jquery-1.8.3.min.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/lib/js/jquery-ui-1.8.24.custom.min.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.dataTables.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/TableTools.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/jCProgress-1.0.2.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.selectBox.min.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fancybox-1.3.4.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/multiple-accordion.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/custom-script.js"></script>
		
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
						<div class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">The Edge Logo</a></div>
					</div>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								Hello <?php echo $current_user->user_login ?> <i class="white-icons admin_user"></i><b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo esc_url( home_url('/')."dashboard/".$current_user->user_login."/profile/"); ?>"><i class="icon-cog"></i>Account Settings</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo esc_url( wp_logout_url('') ) ?>"><i class="icon-off"></i><strong>Logout</strong></a></li>
							</ul>
						</li>
					</ul>
					<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="dropdown"><a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/online-sat-progress/" class="dropdown-toggle"><i class="nav-icon frames"></i>Course Progress</a></li>
							<li class="dropdown"><a href="<?php echo esc_url( home_url( '/' ) ); ?>course-material" class="dropdown-toggle"><i class="nav-icon books"></i>Course Material</a></li>
							<?php
								$page = get_page_by_title("Practice SAT and ACT Exams");
								if ($page->ID):
							?>
							<li class="dropdown"><a href="<?php echo get_permalink($page->ID) ?>" class="dropdown-toggle"><i class="nav-icon cup"></i>Take Practice Tests</b></a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div id="main-content" class="full-fluid">
			<div class="container-fluid">
				<ul class="breadcrumb">
					<?php
					if(function_exists('bcn_display')) {
						bcn_display();
					}
					?>
				</ul>
