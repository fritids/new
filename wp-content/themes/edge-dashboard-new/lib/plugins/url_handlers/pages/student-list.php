<?php get_header(); ?>
<h1>List of Students</h1>
<ul class="studentlist" style="margin:0;padding:0;list-style:none;">
<?php 

$current_user = wp_get_current_user();
$uid=$current_user->ID;

global $wpdb;

/*$q="
SELECT * FROM $wpdb->users 
INNER JOIN $wpdb->usermeta meta ON $wpdb->users.ID=meta.user_id
INNER JOIN $wpdb->usermeta meta1 ON $wpdb->users.ID=meta1.user_id
INNER JOIN wp_teacherstudent ON wp_teacherstudent.teacher_ID=$wpdb->users.ID
WHERE meta.meta_key='_IDGL_elem_user_type' AND meta.meta_value='student'
AND meta1.meta_key='_IDGL_elem_userSubtype' AND meta1.meta_value LIKE '%offline-student%'
AND wp_teacherstudent.teacher_ID=$uid
";
echo $q;*/
$q="SELECT * from wp_users WHERE ID in(
SELECT student_ID FROM wp_teacherstudent 
WHERE wp_teacherstudent.teacher_ID=$uid
)";
$wp_user_search = $wpdb->get_results($q);

foreach ( $wp_user_search as $userid ) {
	$user_id       = (int) $userid->ID;
	$user_login    = stripslashes($userid->user_login);
	$display_name  = stripslashes($userid->display_name);
	
	$products=get_user_meta($user_id,"_IDGL_elem_ECP_user_order",true);
	$products=unserialize($products);

	//print_r($products);
	?>
	<li>
		<strong><?php echo $userid->user_nicename; ?></strong> <br/>
		<span style="font-weight:normal;">Email:</span> <a href="<?php echo $userid->user_email; ?>"><?php echo $userid->user_email; ?></a> <br/>
		<span style="font-weight:normal;">Actions:</span> <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $user_login ?>/profile/" >Dashboard</a> | <a href="<?php echo get_bloginfo("url") ?>/dashboard/<?php echo $user_login ?>/live-test-progress/" >Live Test Progress</a>
		<hr style="margin-top: 10px; border:none; border-top:solid 1px #648596;" />
	</li>
	<?php 
	/*$return  = '';
	$return .= "\t" . '<li>'. $display_name .' | <a href="'.get_bloginfo("url")."/dashboard/".$user_login.'/profile/" >dashboard</a> </li>' . "\n";

	echo ($return);*/
}
?>
</ul>
<?php get_footer(); ?>