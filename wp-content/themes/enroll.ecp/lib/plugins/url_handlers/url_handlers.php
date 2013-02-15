<?php
require_once "classes/ECPUser.php";

add_filter('rewrite_rules_array', 'url_handlers_insert_rewrite_rules');
add_filter('query_vars', 'url_handlers_insert_rewrite_query_vars');
add_filter('init', 'url_handlers_flush_rules');

function url_handlers_flush_rules()
{
	global $wp_rewrite;
	$wp_rewrite -> flush_rules();
}

function url_handlers_insert_rewrite_rules($rules)
{
	$newrules = array();
	$newrules['(registration)'] = 'index.php?user_action=shopping-cart&section=registration';
	$newrules['(demo-expired)'] = 'index.php?user_action=demo-expired';
//	$newrules['(tryout)/handler'] = 'index.php?user_action=tryout&section=form-handler';
//	$newrules['(tryout)'] = 'index.php?user_action=tryout';	
//	$newrules['(cart)/checkout_error'] = 'index.php?user_action=shopping-cart&section=checkout_error';
//	$newrules['(cart)/thankyou'] = 'index.php?user_action=shopping-cart&section=thankyou';
//	$newrules['(cart)/checkout'] = 'index.php?user_action=shopping-cart&section=checkout';
//	$newrules['(cart)/review'] = 'index.php?user_action=shopping-cart&section=review';
//	$newrules['(cart)/billing'] = 'index.php?user_action=shopping-cart&section=billing-info';
//	$newrules['(cart)/account'] = 'index.php?user_action=shopping-cart&section=account-setup';
//	$newrules['(cart)'] = 'index.php?user_action=shopping-cart&section=selection';
	
	$newrules['(dashboard)/([^/]+)/([^/]+)/*$'] = 'index.php?user_action=dashboard&uname=$matches[2]&upage=$matches[3]';
	

	return $newrules + $rules;
}

function url_handlers_insert_rewrite_query_vars($vars)
{
	array_push($vars, 'uname');
	array_push($vars, 'upage');
	array_push($vars, 'user_action');
	array_push($vars, 'section');
	return $vars;
}

add_action("parse_query", "processECPReqests");

function processECPReqests()
{
	global $wp_query;

	$action = $wp_query -> query_vars['user_action'];
	$section = $wp_query -> query_vars['section'];
	
	if(isset($wp_query -> query_vars['upage']))
	{
		$upage = $wp_query -> query_vars['upage'];
	}
	
	if(isset($upage))
	{
		if(!function_exists("all_on_one")){
			function all_on_one()
			{
				global $wp_query;
				$upage = $wp_query -> query_vars['upage'];
				if(is_file(dirname(__FILE__) . "/pages/userpages/" . $upage . ".php"))
				{
					include dirname(__FILE__) . "/pages/userpages/" . $upage . ".php";
				}
				exit();
			}
		}
		add_action('template_redirect', 'all_on_one');
	}
	else
	{
		if(isset($action))
		{
			if(!function_exists("all_on_one")){
				function all_on_one()
				{
					global $wp_query;
					$action = $wp_query -> query_vars['user_action'];
					$section = $wp_query -> query_vars['section'];
									
					if(is_file(dirname(__FILE__) . "/pages/" . $action . ".php"))
					{
						include dirname(__FILE__) . "/pages/" . $action . ".php";
					}
					
					if(is_file(dirname(__FILE__) . "/pages/{$action}/{$section}.php"))
					{
						include dirname(__FILE__) . "/pages/{$action}/{$section}.php";
					}
					
					exit();
				}
			}
			add_action('template_redirect', 'all_on_one');
		}
	}
}

add_action("wp_login", "processAfterLogin");

function processAfterLogin()
{
	/*$level = ECPUser::getType();
	if($level != 10)
	{
		wp_redirect(get_bloginfo("url") . "/dashboard/");
		die();
	}*/
}

function custom_content()
{
	require_once "pages/login.php";
}