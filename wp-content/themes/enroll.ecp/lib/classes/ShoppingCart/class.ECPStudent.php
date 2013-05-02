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
			"user_password" => $this -> _password,
			"user_login" => $this -> _username,
			"user_nicename" => $this -> _user,
			"user_email" => $this -> _username,
			"display_name" => $this -> _user,
			"first_name" => $user_data["FirstName"],
			"last_name" => $user_data["LastName"]
		);
							
		$idgl_data = ArrayUtils::unsetMultipleKeys($user_data, array( "timeout", "referrer", "FirstName",  "LastName" ));
		
		$this -> _id = wp_insert_user($data);
		
		if(is_int($this -> _id))
		{		
			$idgl_data["id"] = $this -> _id;		
			$idgl_data["user_type"] = "student";
			$idgl_data["userSubtype"] = array("online-student");
			$idgl_data["registration_date"] = time();
			$idgl_data["terms_approval"] = date("Y-m-d H:i:s");
			IDGL_Users::IDGL_addNewUserMeta($idgl_data);
			
			// Send Email
			$this -> _msg_subject = getThemeMeta("ecpRegisterMailSubject", "", "_ecpMail");
		    $this -> _msg_body = getThemeMeta("ecpRegisterMailBody", "", "_ecpMail");		
		    $this -> _prepareMail();
			$this -> _sendMail();
			
			// Login User
			wp_signon( $data, false );
			
		    return $this -> _id;
		}
		else
		{
			return false;
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
		}
		//backward compatibility
		$previous_order = unserialize(get_user_meta($this -> _id, "_IDGL_elem_ECP_user_order", true));
		
		$order = (empty($previous_order)) ? array() : $previous_order;
		$order = array_merge($order, $products);
		if(!update_user_meta( $this -> _id, "_IDGL_elem_ECP_user_order", serialize($order)))
		{
			return false;
		}		
		
		$previous_orders_details = get_user_meta($this -> _id, "_IDGL_elem_ECP_user_orders_details", true);
		$orders_details = (empty($previous_orders_details)) ? array() : $previous_orders_details;
		$orders_details[$timestamp] = $products;
		
		if(!update_user_meta( $this -> _id, "_IDGL_elem_ECP_user_orders_details", $orders_details ))
		{
			return false;
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
	
	/**
	 * 
	 * Send mail to the site administrator
	 * @param string $subject
	 * @param string $body
	 */
	public function sendAdminMail($ecp_products)
	{
		if(empty($this->_user))
		{
			$user_info = get_userdata($this->_id);
			$this->_user = $user_info->first_name." ".$user_info->last_name;
		}
		
		$message = "A new purchase has been made. Here is the description:<br><br>";
		$message .= "<div><b>User:</b> " . $this->_username . "</div>";
		$message .= "<div><b>Products: </b></div>";
		$message .= "<ul>";
		
		foreach($ecp_products as $i => $single_product)
		{
			$category = get_term_by('slug',$single_product->taxonomy_slug,"ecp-products");
			
			$message .= "<li>";
			$message .= $single_product->name . ", " . $category->name . " - $" . ($single_product->price - $single_product->discount);
			$message .= "</li>";
		}
		$message .= "</ul>";
		
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		$headers = "From: Edge in College Prep Administrator <info@edgeincollegeprep.com>" . "\r\n\\";

		if(!wp_mail(get_option('admin_email'), "ECP New Purchase", $message, $headers))
		{
		   return $this->_err->get_error_message("send_mail");
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