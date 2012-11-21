<?php
require_once(ABSPATH . WPINC . '/registration.php');
require_once IDG_CLASS_PATH."class.IDGL_Users.php";
require_once IDG_CLASS_PATH."Utils/class.ArrayUtils.php";

/** 
 * @author tefdos
 * 
 * 
 */
class ECPStudent
{
	private $_id;
	private $_err;
	private $_username;
	private $_password;
	private $_user; 
	private $_msg_subject;
	private $_msg_body;
	
	function __construct($username)
	{
		$this -> _err = new WP_Error();
		$this -> _err -> add("insert_user", "Creating user failed.");
		$this -> _err -> add("send_mail", "Sending mail failed.");
		$this -> _err -> add("insert_products", "Inserting products failed.");
		$this -> _username = $username;
	}
	
	// PUBLIC METHODS
	
	/*
	 * Checks if user exists in database
	 */
	public function userExist()
	{
		if ( username_exists($this -> _username)){ $this -> _id = get_user_id_from_string($this -> _username); return TRUE; }
		return FALSE;
	}
	
	/**
	 * Create new user in database
	 */
	public function createUser($user_data)
	{
		$this -> _password = wp_generate_password( 12, false );
		
		$this -> _user = $user_data["FirstName"]." ".$user_data["LastName"];
		$data = array(
			"user_pass" => $this -> _password,
			"user_login" => $this -> _username,
			"user_nicename" => $this -> _user,
			"user_email" => $this -> _username,
			"display_name" => $this -> _user,
			"first_name" => $user_data["FirstName"],
			"last_name" => $user_data["LastName"]
		);
							
		$idgl_data = ArrayUtils::unsetMultipleKeys($user_data, array(
																	"timeout", 
																	"referrer", 
																	"FirstName", 
																	"LastName", 
																	"cc_type", 
																	"cc_fname", 
																	"cc_lname", 
																	"cc_number", 
																	"cvv2",
																	"cc_ex_month", 
																	"cc_ex_year"
		));
		
		$this -> _id = wp_insert_user($data);
		
		if(is_int($this -> _id))
		{		
			$idgl_data["id"] = $this -> _id;		
			$idgl_data["user_type"] = "student";
			$idgl_data["userSubtype"] = array("online-student");
			$idgl_data["registration_date"] = time();
			IDGL_Users::IDGL_addNewUserMeta($idgl_data);
			
			$this -> _msg_subject = getThemeMeta("ecpRegisterMailSubject", "", "_ecpMail");
		    $this -> _msg_body = getThemeMeta("ecpRegisterMailBody", "", "_ecpMail");		
		    $this -> _prepareMail();
			$this -> _sendMail();
		    return $this -> _id;
		}
		else
		{
			return $this -> _err -> get_error_message("insert_user");
		}	
	}
	
	/**
	 * 
	 * Create demo user which account disables in 5 days after registration
	 * @param Array $user_data
	 */
	public function createDemoUser($user_data)
	{
		$this -> _password = wp_generate_password( 12, false );
		
		$this -> _user = $user_data["fname"]." ".$user_data["lname"];
		$data = array(
			"user_pass" => $this -> _password,
			"user_login" => $this -> _username,
			"user_nicename" => $this -> _user,
			"user_email" => $this -> _username,
			"display_name" => $this -> _user,
			"first_name" => $user_data["fname"],
			"last_name" => $user_data["lname"]
		);
		
		$this -> _id = wp_insert_user($data);
		
		if(is_int($this -> _id))
		{		
			$idgl_data["id"] = $this -> _id;		
			$idgl_data["user_type"] = "student";
			$idgl_data["userSubtype"] = array("demo-student");
			$idgl_data["expiration_date"] = strtotime("+5 day");
			IDGL_Users::IDGL_addNewUserMeta($idgl_data);
			
			$this -> _msg_subject = getThemeMeta("ecpTryoutMailSubject", "", "_ecpMail");
		    $this -> _msg_body = getThemeMeta("ecpTryoutMailBody", "", "_ecpMail");
		    
		    $this -> _prepareMail();
			$this -> _sendMail();
		    return $this -> _id;
		}
		else
		{
			return $this -> _err -> get_error_message("insert_user");
		}
	}
	
	/**
	 * 
	 * @param unknown_type $ecp_products
	 */
	public function saveUserProducts($ecp_products)
	{
		// add data to order details
		$products = array();
		$timestamp = time();
		foreach($ecp_products as $i => $single_product)
		{
			$products[$i]["id"] = $single_product -> id;
			$products[$i]["type"] = $single_product -> name;
			$products[$i]["price"] = $single_product -> price;
			$products[$i]["quantity"] = $single_product -> quantity;
			$products[$i]["discount"] = $single_product -> discount;
		}
		//backward compatibility
		$previous_order = unserialize(get_user_meta($this -> _id, "_IDGL_elem_ECP_user_order", true));
		
		$order = (empty($previous_order)) ? array() : $previous_order;
		$order = array_merge($order, $products);
		if(!update_user_meta( $this -> _id, "_IDGL_elem_ECP_user_order", serialize($order)))
		{
			//return $this -> _err -> get_error_message("insert_products");
		}		
		
		$previous_orders_details = get_user_meta($this -> _id, "_IDGL_elem_ECP_user_orders_details", true);
		$orders_details = (empty($previous_orders_details)) ? array() : $previous_orders_details;
		$orders_details[$timestamp] = $products;
		
		if(!update_user_meta( $this -> _id, "_IDGL_elem_ECP_user_orders_details", $orders_details ))
		{
			//return $this -> _err -> get_error_message("insert_products");
		}
	}
	
	/**
	 * 
	 * Send mail for each ordered product category 
	 * @param string $subject
	 * @param string $body
	 */
	public function sendProductMail($subject, $body)
	{
		if(empty($this -> _user))
		{
			$user_info = get_userdata($this -> _id);
			$this -> _user = $user_info -> first_name." ".$user_info -> last_name;
		}
		
		$this -> _msg_subject = getThemeMeta($subject, "", "_ecpMail");
		$this -> _msg_body = getThemeMeta($body, "", "_ecpMail");
		if(!empty($this -> _msg_body))
		{
			$this -> _prepareMail();
			if(gettype($this -> _sendMail() == "string"))
			{
				return false;
			}
		}		
	}
	
	// PRIVATE METHODS
	
	// @TODO: refactor functions in mail class
	
	private function _prepareMail()
	{
		if(empty($this -> _msg_subject))
		{
			$this -> _msg_subject = "ECP Registration Info";
		}
		else
		{
			$this -> _msg_subject = $this -> _templateFormater($this -> _msg_subject);
		}
		
		if(empty($this -> _msg_body))
		{
			$this -> _msg_body = "THIS IS AUTOGENERATED MESSAGE. DO NOT REPLY. \n
						 You have been registered as student on <a href='edgeincollegeprep.com'>ECP website</a>. You can log-in using this credentials: \n
						 		username : {$this -> _username} \n
						 		password : {$this -> _password} \n
						 Thank you for registering.";
		}
		else
		{
			$header_msg_body = "THIS IS AUTOGENERATED MESSAGE. DO NOT REPLY. \n";
			$this -> _msg_body = $header_msg_body.$this -> _templateFormater($this -> _msg_body);
		}
	}
	
	private function _templateFormater($template_string)
	{
		$template_msg = str_replace("{user}", $this -> _user ,$template_string);
		$template_msg = str_replace("{username}", $this -> _username, $template_msg);
		$template_msg = str_replace("{password}", $this -> _password, $template_msg);
		return $template_msg;
	}
	
	private function _sendMail()
	{
		 add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		 $headers = "From: Edge in College Prep Administrator <info@edgeincollegeprep.com>" . "\r\n\\";
		 
		 if(!wp_mail($this -> _username, $this -> _msg_subject, $this->_msg_body, $headers))
		 {
		 	return $this -> _err -> get_error_message("send_mail");
		 }
	}
}
?>