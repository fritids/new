<?php 
if(is_user_logged_in()){
	wp_redirect(get_bloginfo("url")."/dashboard/");
	die();
}
require_once(IDG_PLUGINS_PATH."url_handlers/classes/BUsers.php");
$user = new BUser();

$post = $user->sanitizedPost();
$get = $user->sanitizedGet();
if($post['wp-submit']){
$message = $user->lostpassword();
}
if($get['key'] && $get['login']){
$message = $user->resetpassword();
//print_r($message);
}

?>
<?php get_header(); ?>
<div class="error_messages">
<?php 
if(isset($message)){
if(is_object($message)){
	
	foreach($message->get_error_messages() as $key)
	echo $key;
	
}
else{
	echo "Message Sent Sucessful , Check your mail.";
}
}
?>
</div>


<p class="message">Please enter your username or e-mail address. You will receive a new password via e-mail.</p>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" id="lostpasswordform" name="lostpasswordform">
	<p>
		<label>Username or E-mail:<br>
		<input type="text" tabindex="10" size="20" value="" class="input" id="user_login" name="user_login"></label>
	</p>
	<p class="submit"><input type="submit" tabindex="100" value="Get New Password" class="button-primary" id="wp-submit" name="wp-submit"></p>
</form>
<p id="nav">
<a href="<?php bloginfo("url"); ?>/wp-login.php">Log in</a>
</p>

<?php get_sidebar(); ?>
<?php get_footer(); ?>