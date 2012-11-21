            <h2>Member Login</h2>
            <div>
				<form method="post" action="/portal/login/" id="loginform" name="loginform">
					<div class="login-node">
						<label>Username:</label>
						<input type="text" tabindex="10" size="20" value="<?php echo $post['user_login'] ?>" class="input" id="user_login" name="user_login">
					</div>
					<div class="login-node">
						<label>Password:</label>
						<input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" name="user_password"></label>
					</div>
					<div class="the_submit_holder">
					<input type="submit" value="LOGIN" class="the_submit" id="wp-submit" name="wp-submit" />
					<a href="/portal/lostpassword/" class="forgot_pass">Forgot Password?</a><br />
					<a href="#">Not a Member? Sign Up Today!
					<div class="clear"></div>
					</div>
					
				</form>
			</div>