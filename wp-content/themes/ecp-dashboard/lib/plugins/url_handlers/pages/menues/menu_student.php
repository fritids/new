<?php 
global $wp_query;
$user_name=$wp_query->query_vars['uname'];
$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
$sections=get_user_meta($user_id,"_IDGL_elem_userSubtype",true);

if($user_name===null){
	$user_name=ECPUser::getUserName();
}
$is_admin=false;
if ( $user_id==1 ) { $is_admin=true; }
?>
