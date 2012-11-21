<?php get_header(); ?>
<?php 
global $wpdb;
$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");

foreach ( $wp_user_search as $userid ) {
	$user_id       = (int) $userid->ID;
	$user_login    = stripslashes($userid->user_login);
	$display_name  = stripslashes($userid->display_name);

	$return  = '';
	$return .= "\t" . '<li>'. $display_name .' | <a href="'.get_bloginfo("url")."/dashboard/".$display_name.'/profile/" >dashboard</a> </li>' . "\n";

	print($return);
}
?>
<?php get_footer(); ?>