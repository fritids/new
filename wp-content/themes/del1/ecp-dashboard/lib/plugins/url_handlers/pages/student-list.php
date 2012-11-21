<?php get_header(); ?>
<h1>List of Students</h1>
<ul class="studentlist">
<?php 
global $wpdb;

$q="
SELECT ID, display_name FROM $wpdb->users 
INNER JOIN $wpdb->usermeta ON $wpdb->users.ID=$wpdb->usermeta.user_id
WHERE meta_key='_IDGL_elem_user_type' AND meta_value='student'";
$wp_user_search = $wpdb->get_results($q);

foreach ( $wp_user_search as $userid ) {
	$user_id       = (int) $userid->ID;
	$user_login    = stripslashes($userid->user_login);
	$display_name  = stripslashes($userid->display_name);

	$return  = '';
	$return .= "\t" . '<li>'. $display_name .' | <a href="'.get_bloginfo("url")."/dashboard/".$display_name.'/profile/" >dashboard</a> </li>' . "\n";

	print($return);
}
?>
</ul>
<?php get_footer(); ?>