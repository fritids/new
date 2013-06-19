<?php 
require_once(IDG_PLUGINS_PATH."url_handlers/classes/BUsers.php");
$user = new BUser();

if($_SESSION['state'] != '' AND $_SESSION['code'] != ''){
	if($_SESSION['state'] === $_SESSION['auth']){
		unset($_SESSION['auth']);
		unset($_SESSION['state']);
		$response = fopen("https://auth.casenex.com/auth/access_token?client_id=edge_id&client_secret=2816fhsO49fD2h3yd78f9sa439gEfkf2lhni&code=".$_SESSION['code']."&redirect_uri=http://www.edgeincollegeprep.com/portal","r");
		$result = fread($response,500);
		$result = json_decode($result);
		unset($_SESSION['code']);
//        $result = array(
//            'access_token'=>'70fb9f226dccb5cd39f2e2d09b268073',
//            'refresh_token'=>'84a7cedc6a31bf0183d5afa5fb84ffe5',
//            'expires'=>'2013-05-23T17:50:44Z',
//            'user_id'=>'148417',
//            'username'=>'skdteacher@casenex.com',
//            'email'=>'skdteacher@casenex.com');
		
		$user_temp = new WP_User_Query( array( 'search' => $result->user_id, 'search_columns'=>array('casenex_id') ) );
//        $user_temp = new WP_User_Query( array( 'search' => $result['user_id'], 'search_columns'=>array('casenex_id') ) );
		
		if ( !empty( $user_temp->results ) ) {
			$user_id = $user_temp->results[0]->ID;
			$user_login = $user_temp->results[0]->user_login;            
			wp_set_current_user($user_id, $user_login);
			wp_set_auth_cookie($user_id);
			$errors = do_action('wp_login', $user_login);

			if (is_user_logged_in()){
				?>
					<script type="text/javascript">
						window.location="<?php echo get_bloginfo("url"); ?>";
					</script>
				<?php 
			}
		}
	}
}

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = substr(str_shuffle($chars), 0, 10);
    $_SESSION['auth'] = $authorization_key = $str;
    
//filtered $_POST
$post = $user->sanitizedPost();

if($post['wp-submit']) $errors = $user->login();


get_header(); 

?>
<div class="error_messages">
<?php 
// print errors from login if any..
if(isset($errors)){
foreach($errors->get_error_messages() as $key)
echo $key;
}
?>
</div>

<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" id="loginform" name="loginform">
	<p>
		<label>Username<br>
		<input type="text" tabindex="10" size="20" value="<?php echo $post['user_login'] ?>" class="input" id="user_login" name="user_login"></label>
	</p>
	<p>
		<label>Password<br>
		<input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" name="user_password"></label>
	</p>
	<p class="forgetmenot"><label><input type="checkbox" tabindex="90" value="true" id="remember" name="remember"> Remember Me</label></p>
	<p><a href="<?php bloginfo("url"); ?>/lostpassword">Forgot your password?</a></p>
    <p>
        <a href="https://auth.casenex.com/auth/authorize?client_id=edge_id&redirect_uri=<?php echo htmlentities(get_bloginfo("url")); ?>&state=<?php echo $authorization_key; ?>">
            Login with CaseNEX
        </a>
    </p>
	<p class="submit">
		<input type="submit" tabindex="100" value="Log In" class="button-primary" id="wp-submit" name="wp-submit">
		<input type="hidden" value="<?php bloginfo("url"); ?>/wp-admin/" name="redirect_to">
		<input type="hidden" value="1" name="testcookie">
	</p>
</form>
<?php get_sidebar(); ?>
<?php get_footer(); 
/*
<script type="text/javascript">
	window.location="<?php echo get_bloginfo("url")."/profile/".$user_info->display_name."/profile/"; ?>";
</script>
*/
?>