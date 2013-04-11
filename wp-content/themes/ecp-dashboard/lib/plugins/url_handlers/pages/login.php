<?php

require_once(IDG_PLUGINS_PATH."url_handlers/classes/BUsers.php");
$user = new BUser();

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