<?php 
if(is_user_logged_in()){
	wp_redirect(get_bloginfo("url")."/dashboard/");
	die();
}
get_header(); ?>
<form method="post" action="<?php bloginfo("url"); ?>/wp-login.php" id="loginform" name="loginform">
	<p>
		<label>Username<br>
		<input type="text" tabindex="10" size="20" value="" class="input" id="user_login" name="log"></label>
	</p>
	<p>
		<label>Password<br>
		<input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" name="pwd"></label>
	</p>
	<p class="forgetmenot"><label><input type="checkbox" tabindex="90" value="forever" id="rememberme" name="rememberme"> Remember Me</label></p>
	<p class="submit">
		<input type="submit" tabindex="100" value="Log In" class="button-primary" id="wp-submit" name="wp-submit">
		<input type="hidden" value="http://localhost/ecp/wp-admin/" name="redirect_to">
		<input type="hidden" value="1" name="testcookie">
	</p>
</form>
<?php get_footer(); ?>