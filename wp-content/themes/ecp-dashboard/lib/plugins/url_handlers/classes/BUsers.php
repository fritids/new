<?php

interface IUser {

}


class BUser implements IUser {

private $password;
private $user;
private $post;
private $get;
private $error;
private $wpdb;

public function __construct(){
    global $wpdb;
	$this->wpdb = $wpdb; 
}

/**
 * 
 * Sanitize POST from XSS Sql injections
 * 
 */

	public function sanitizedPost(){
		
		foreach($_POST as $key => $value){
		$this->post[$key] =  htmlentities(strip_tags($value),ENT_QUOTES, 'UTF-8');
		}
		
	return $this->post;
	}

/**
 * 
 * Sanitize GET from XSS Sql injections
 * 
 */

	public function sanitizedGet(){
	
		foreach($_GET as $key => $value){
		$this->get[$key] =  htmlentities(strip_tags($value),ENT_QUOTES, 'UTF-8');
		}
	
	return $this->get;
	}

	public function login(){
	
	if(isset($this->post['wp-submit']))
	{
        
		//sign in and return errors if any..
		$user = wp_signon($this->post,false);
	
		if(is_wp_error($user)){
			$this->error = $user;
			// reinitialize errors to change standard messages
			if($this->error->get_error_message('invalid_username')){
				
				unset($this->error->errors['invalid_username']);
				$this->error->add('invalid_username','<b>ERROR</b>: Invalid Username Entered');
				
			}
			if($this->error->get_error_message('incorrect_password')){
				
				unset($this->error->errors['incorrect_password']);
				$this->error->add('incorrect_password','<b>ERROR</b>: Incorrect Password Entered');
				
			}
			
		}else{
			
				//$current_user = wp_get_current_user();


	
	//wp_redirect(get_bloginfo("url")."/dashboard/".$user->user_login."/profile/"); 
	?>
	<script type="text/javascript">
	window.location="<?php echo get_bloginfo("url"); ?>";
</script>
	<?php


			
		}
		return $this->error;
			
	}
		
	}

	public function logout(){
	
	wp_logout();
	
	}

	public function lostpassword(){
	
		
		//User submits reset password...
		if(isset($this->post['wp-submit'])){
			
			if(!$user = $this->wpdb->get_row("SELECT * FROM {$this->wpdb->users} WHERE user_login = '{$this->post['user_login']}'")){
				return new WP_Error('invalid_user', '<b>ERROR</b> : Invalid User supplied');
			}	
			
			$user_login = $user->user_login;
			$user_email = $user->user_email;
			
			$allow = apply_filters('allow_password_reset', true, $user->ID);
			if(!$allow){
				$this->error = new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
				return $this->error;
			}
			$key = $user->user_activation_key;
		    if ( empty($key) ) {
			$key = wp_generate_password(20, false);
			$key = "'".$key."'";
			$user_login = "'".$user_login."'";
			//do_action('retrieve_password_key', $user_login, $key);
	
				if(!$this->wpdb->query("UPDATE {$this->wpdb->users} SET user_activation_key = $key WHERE user_login = $user_login")){
				return new WP_Error('problem_query', 'Problem with query');
				}
			//echo $this->wpdb->update($this->wpdb->users, array('user_activation_key' => '$key'), array('user_login' => '$user_login'));
			
		    }
		    
		    $message = __('Someone has asked to reset the password for the following site and username.') . "\r\n\r\n";
	    	$message .= network_site_url() . "\r\n\r\n";
		    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		    $message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.') . "\r\n\r\n";
		    $message .= network_site_url("index.php?user_action=lostpassword&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";
	
		    if ( is_multisite() )
			    $blogname = $GLOBALS['current_site']->site_name;
		    else
			
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
			$title = sprintf( __('[%s] Password Reset'), $blogname );
		
			$title = apply_filters('retrieve_password_title', $title);
			$message = apply_filters('retrieve_password_message', $message, $key);		
			
			if ( $message && !wp_mail($user_email, $title, $message) ){
				return new WP_Error('error_mail','The e-mail could not be sent.');
			}
			else{
				return true;
			}
			
			return true;
			
		}		
	}

	public function resetpassword(){
				
			$key = $this->get['key'];
			$login = $this->get['login'];
			
			$key = preg_replace('/[^a-z0-9]/i', '', $key);
	
		if ( empty( $key ) || !is_string( $key ) )
			return new WP_Error('invalid_key', __('<b>ERROR</b>: Invalid key'));
	
		if ( empty($login) || !is_string($login) )
			return new WP_Error('invalid_key', __('<b>ERROR</b>: Invalid key'));
	
		$user = $this->wpdb->get_row("SELECT * FROM {$this->wpdb->users} WHERE user_activation_key = '{$key}' AND user_login = '{$login}'");
		if ( empty( $user ) )
			return new WP_Error('invalid_key', __('<b>ERROR</b>: Invalid key'));
	
		// Generate something random for a password...
		$new_pass = wp_generate_password();
	
		do_action('password_reset', $user, $new_pass);
	
		wp_set_password($new_pass, $user->ID);
		update_user_option($user->ID, 'default_password_nag', true, true); //Set up the Password change nag.
		$message  = sprintf(__('Username: %s'), $user->user_login) . "\r\n";
		$message .= sprintf(__('Password: %s'), $new_pass) . "\r\n";
		$message .= network_site_url("/login/", 'login') . "\r\n";
	
		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
		$title = sprintf( __('[%s] Your new password'), $blogname );
	
		$title = apply_filters('password_reset_title', $title);
		$message = apply_filters('password_reset_message', $message, $new_pass);
	
		if ( $message && !wp_mail($user->user_email, $title, $message) ){
			return new WP_Error('error_mail','The e-mail could not be sent.');
		}
	  	
		wp_password_change_notification($user);
	
		return true;
		
	}

	public function register(){
	
		// validation on fields
		$validate = new BFormValidator($this->post);
		$validate->validate('username')->vRequired('Empty Username');
		$validate->validate('fname')->vRequired('Empty Firstname');
		$validate->validate('lname')->vRequired('Empty Lastname');
		$validate->validate('password')->vRequired('Empty Password')->vMinLength('Password must be longer', 4)->vMaxLength('Password is too long', 20);
		$validate->validate('password2')->vRequired('Empty Retype Password');
		$validate->validate('email')->vRequired('Empty Email')->vEmail('Email not valid');
		$validate->validate('website')->vURL('Empty Website');
		$validate->validate('aim')->vMinMaxLength('AIM Username exceeds maximum length or minimum length', 2,12);
		$validate->validate('yahoo')->vMinMaxLength('Yahoo Username exceeds maximum length or minimum length', 2,12);
		$validate->validate('jabber')->vMinMaxLength('Jabber Username exceeds maximum length or minimum length', 2,12);
		$validate->validate('biography')->vMaxLength('Biography exceeds maximum length of characters',120);
		
		if($validate->checkErrors()){
			//return errors for further display..
			return $validate->displayErrors(true);
		}
		
		// throw errors by WP_Error object..
		if(get_userdatabylogin($this->post['username'])){
		  return new WP_Error('existing_username', 'Already exists user with same with same username');
		}
		if(get_user_by_email($this->post['email'])){
		  return new WP_Error('existing_email', 'Already exists user with same email');
		}
		if($this->post['password'] !== $this->post['password2']){
			
		  return new WP_Error('password', 'Passwords don`t match');
		}
	 
		$userdata = array();
		// values for user table
		$userdata['user_login'] = $this->post['username'];
		$userdata['user_pass'] = wp_hash_password($this->post['password']);
		$userdata['user_nicename'] = $this->post['username'];
		$userdata['user_email'] = $this->post['email'];
		$userdata['user_url'] = $this->post['website'];
		$userdata['user_registered'] = date('Y-m-d H:i:s');
		$userdata['display_name'] = $this->post['username'];
		
		$this->wpdb->insert( $this->wpdb->users, $userdata);
		$user_id = (int) $this->wpdb->insert_id;
		
		//reset userdata so we can put it in usermeta
		$userdata = array();
		//values for usermeta table 
		$userdata['first_name'] = $this->post['fname'];
		$userdata['last_name'] = $this->post['lname'];
		$userdata['nickname'] = $this->post['username'];
		$userdata['description'] = $this->post['biography'];
		$userdata['rich_editing'] = true;
		$userdata['comment_shortcuts'] = false;
		$userdata['admin_color'] = 'fresh';
		$userdata['use_ssl'] = 0;
		//$userdata['role'] = $this->post['username']; default role mu staev
		$userdata['jabber'] = $this->post['jabber'];
		$userdata['aim'] = $this->post['aim'];
		$userdata['yim'] = $this->post['yahoo'];
			
		foreach($userdata as $key => $value){
			update_user_meta( $user_id, $key , $value);
		}
	 		
	 	$user = new WP_User($user_id);
		$user->set_role(get_option('default_role'));
			
		do_action('user_register', $user_id);
		return true;
	}


}


?>