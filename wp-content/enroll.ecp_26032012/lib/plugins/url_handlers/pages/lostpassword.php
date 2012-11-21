<?php get_header(); ?>
<p class="message">Please enter your username or e-mail address. You will receive a new password via e-mail.</p>
<form method="post" action="http://localhost/wordpress/wp-login.php?action=lostpassword" id="lostpasswordform" name="lostpasswordform">
	<p>
		<label>Username or E-mail:<br>
		<input type="text" tabindex="10" size="20" value="" class="input" id="user_login" name="user_login"></label>
	</p>
	<p class="submit"><input type="submit" tabindex="100" value="Get New Password" class="button-primary" id="wp-submit" name="wp-submit"></p>
</form>
<p id="nav">
<a href="http://localhost/wordpress/wp-login.php">Log in</a>
</p>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>