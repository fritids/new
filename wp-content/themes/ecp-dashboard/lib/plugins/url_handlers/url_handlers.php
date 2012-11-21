<?php

require_once "classes/ECPUser.php";

add_filter('rewrite_rules_array','url_handlers_insert_rewrite_rules');
add_filter('query_vars','url_handlers_insert_rewrite_query_vars');
add_filter('init','url_handlers_flush_rules');

function url_handlers_flush_rules(){
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
}
function url_handlers_insert_rewrite_rules($rules)
{
	$newrules = array();
	$newrules['logout'] = 'index.php?user_action=logout';
	$newrules['login'] = 'index.php?user_action=login';
	$newrules['lostpassword'] = 'index.php?user_action=lostpassword';
	
	if(ECPUser::getType()==10){
		//admin pages
		
		//$newrules = array();
		$newrules['(profile)'] = 'index.php?user_action=profile';
		$newrules['(student-list)'] = 'index.php?user_action=student-list';
		$newrules['(process-drill)'] = 'index.php?user_action=process-drill';
		$newrules['(dashboard)/([^/]+)/([^/]+)/*$'] = 'index.php?user_action=dashboard&uname=$matches[2]&upage=$matches[3]';
		
	}elseif(ECPUser::getType()==0){
		//user, can be teacher or a student
		if(ECPUser::getSubType()=="student"){
			//$newrules = array();
			$newrules['(profile)'] = 'index.php?user_action=profile';
			$newrules['(process-drill)'] = 'index.php?user_action=process-drill';
			$newrules['(dashboard)/([^/]+)/([^/]+)/*$'] = 'index.php?user_action=dashboard&uname=$matches[2]&upage=$matches[3]';
		}elseif(ECPUser::getSubType()=="teacher"){
			//$newrules = array();
			$newrules['(profile)'] = 'index.php?user_action=profile';
			$newrules['(student-list)'] = 'index.php?user_action=student-list';
			$newrules['(dashboard)/([^/]+)/([^/]+)/*$'] = 'index.php?user_action=dashboard&uname=$matches[2]&upage=$matches[3]';
			$newrules['(process-drill)'] = 'index.php?user_action=process-drill';
		}
	}
	
	return $newrules + $rules;
}
function url_handlers_insert_rewrite_query_vars($vars)
{
    array_push($vars, 'uname');
    array_push($vars, 'upage');
    array_push($vars, 'user_action');
    return $vars;
}



add_action( "parse_query", "processECPReqests");




function processECPReqests(){
	global $wp_query;
	//print_r($wp_query->query_vars);
	
	//echo $action."..".$upage;
	$action = $wp_query->query_vars['user_action'];
	$upage = $wp_query->query_vars['upage'];
	
	if(isset($upage)){
		if(!function_exists("all_on_one")){
			function all_on_one () {
				global $wp_query;
				$upage = $wp_query->query_vars['upage'];
				if(is_file(dirname(__FILE__)."/pages/userpages/".$upage.".php")){
					//echo dirname(__FILE__)."/pages/userpages/".$upage.".php";
					include dirname(__FILE__)."/pages/userpages/".$upage.".php";
				}
				exit;
			}
		}
		add_action('template_redirect', 'all_on_one');
	}else{
		if(isset($action)){
			if(!function_exists("all_on_one")){
				function all_on_one () {
					global $wp_query;
					$action = $wp_query->query_vars['user_action'];
	
					if(is_file(dirname(__FILE__)."/pages/".$action.".php")){
						
						include dirname(__FILE__)."/pages/".$action.".php";
					}
					exit;
				}
			}
			add_action('template_redirect', 'all_on_one');
		}
	}
	/*switch($action){
		case "logout":
			
			break;
		case "login":
			//require_once "pages/login.php";
			
			break;
		case "dashboard":
			
			break;
	}*/	
}
/*
add_action("wp_login","processAfterLogin");
function processAfterLogin(){
	$level=ECPUser::getType();
	if($level!=10){
		wp_redirect(get_bloginfo("url")."/profile/");
		die();
	}
}
*/
function custom_content(){
	require_once "pages/login.php";
}