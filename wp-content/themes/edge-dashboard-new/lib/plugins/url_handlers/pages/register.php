<?php require_once(IDG_PLUGINS_PATH."url_handlers/classes/BUsers.php");
$user = new BUser();

$post = $user->sanitizedPost();

if($post['register-submit'])
	$errors = $user->register();

?>



<?php get_header(); ?>


<div class="error_messages">
<?php 


		if(is_object($errors))
		{
			// checking if is WP_Error
		  foreach($errors->get_error_messages() as $key)
		  echo $key . '<br>';
		}
		elseif(is_array($errors))
		{
			// validation errors
			foreach($errors as $value => $key){
				echo $key . '<br>';
			}	
			
		}
		elseif(isset($errors))
		{
				echo 'creation of new account succeeded';
				unset($errors);
		}
?>
</div>

<br>
Register New User:
<br>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" id="registerform" name="registerform">
<label for="username">Username : <br /><input type="text" name="username" value="<?php echo $post['username'];?>"></label><br />
<label for="password">Password : <br /><input type="password" name="password" value=""></label><br />
<label for="password2">Retype Password : <br /><input type="password" name="password2" value=""></label><br />
<label for="fname">First Name : <br /><input type="text" name="fname" value="<?php echo $post['fname'];?>"></label><br />
<label for="lname">Last Name : <br /><input type="text" name="lname" value="<?php echo $post['lname'];?>"></label><br />
<label for="email">E-Mail : <br /><input type="text" name="email" value=""></label><br />
<label for="website">Website : <br /><input type="text" name="website" value="<?php echo $post['website'];?>"></label><br />
<label for="aim">AIM : <br /><input type="text" name="aim" value="<?php echo $post['aim'];?>"></label><br />
<label for="yahoo">Yahoo : <br /><input type="text" name="yahoo" value="<?php echo $post['yahoo'];?>"></label><br />
<label for="jabber">Jabber : <br /><input type="text" name="jabber" value="<?php echo $post['jabber'];?>"></label><br />
<label for="biography">Biography : <br /><textarea name="biography" value="<?php echo $post['biography'];?>"></textarea></label><br />
<input type="submit" tabindex="100" value="Register" class="button-primary" id="register-submit" name="register-submit">
</form>



<?php get_sidebar(); ?>
<?php get_footer(); ?>