<?php
class ECPUser{
	public static function getUserMenu(){
		if(!is_user_logged_in()){
			 echo 'Welcome Guest | ';
		     echo '<a href="'.get_bloginfo("home").'/login/">Log In</a>'; 
		}else{
			 global $current_user;
		     get_currentuserinfo();
		     echo 'Welcome ' . $current_user->user_login . ' | <a href="'.get_bloginfo("url")."/dashboard/".ECPUser::getUserName().'/profile/">';
		     echo 'Profile</a> | '; 
			 if(ECPUser::userCanEditStudents()){
		     	 echo '<a href="'.get_bloginfo("url").'/student-list/">Students</a> | ';
		     }
			if(ECPUser::isAdmin()){
		     	 echo '<a href="'.get_bloginfo("url").'/wp-admin/">Backend</a> | ';
		     }
		     echo '<a href="'.get_bloginfo("url").'/logout">Log Out</a>'; 
		}
	}
	public static function logOut(){
		wp_logout();
	}
	public static function getType(){
		 global $current_user;
	     get_currentuserinfo();
	     return $current_user->wp_user_level;
	}
	public static function getSubType(){
		$type=ECPUser::getAttribute("user_type");
		return $type;
	}
	public static function getUserData($userid=null){
		if($userid!=null){
			$current_user=get_userdata($userid);
		}else{ 
			global $current_user;
	     	get_currentuserinfo();
		}
	     return $current_user;
	}
	public static function getUserID(){
		 global $current_user;
	     get_currentuserinfo();
	     return $current_user->ID;
	}
	public static function getUserIDFromUname($uname){
		$user = get_userdatabylogin($uname);
		return $user->ID;
	}
	public static function getUserName(){
		 global $current_user;
	     get_currentuserinfo();
	     return $current_user->user_login;
	}
	public static function getAttribute($key){
		global $current_user;
		return get_user_meta($current_user->ID, "_IDGL_elem_".$key,true);
	}
	public static function setAttribute($userid,$key,$value){
		return update_user_meta($userid,$key,$value);
	}
	public static function setTestResult($result_name,$result){
		global $current_user;
		add_user_meta($current_user->ID,$result_name,$result);
	}
	public static function getUserAttribute($userid,$key){
		return get_user_meta($userid,$key,$value);
	}
	public static function getProgressTable($key,$user_id=null){
		if($user_id==null){
			global $wp_query;
			$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);
		}
		$table=get_user_meta($user_id, $key,true);
		fb($key . " <--");
		fb($user_id, $table);
		if($table!=""){
			return $table;
		}else{
			global $post;
      fb($post->ID . " prochitaj me");
			return getPostMeta($post->ID,"tableRenderer");
		}
	}
	public static function userCanEditStudents(){
		$type=ECPUser::getAttribute("user_type");
		if($type=="teacher" || current_user_can('level_10')){
			return true;
		}
		return false;
	}
	
	public static function isAdmin(){
		if ( current_user_can('manage_options') ) { return true; }
	}
}