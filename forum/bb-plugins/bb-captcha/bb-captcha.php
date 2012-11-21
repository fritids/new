<?php
/*
Plugin Name: bbCaptcha
Version: 1.0
Plugin URI: http://www.tipsandtricks-hq.com/?p=2529
Author: Ruhul Amin
Author URI: http://www.tipsandtricks-hq.com
Description:  Adds reCAPTCHA to your bbPress forum's registration page.
*/

include_once('recaptchalib.php');

function bb_captcha_settings_page_add()
{
	bb_admin_add_submenu( __( 'bbCaptcha' ), 'moderate', 'bb_captcha_settings_page', 'options-general.php' );
}
add_action( 'bb_admin_menu_generator', 'bb_captcha_settings_page_add' );

function bb_captcha_settings_page()
{
	echo '<div class="wrap">';
	echo '<h2>bbCaptcha Settings</h2>';
		
	if (isset ($_POST['submit']))
	{
		bb_update_option('bb_captcha_public_key', (string)$_POST['bb_captcha_public_key']);
		bb_update_option('bb_captcha_private_key', (string)$_POST['bb_captcha_private_key']);
		
		echo '<div style="background-color:#D3DFF5;border: 1px solid #0A356E;margin:10px 0 10px 0;padding:5px;font-weight: bold;">Settings Saved</div>';
		//echo '<div id="message" class="updated fade">Settings Saved</div>';
	}
	?>	
	<strong>reCAPTCHA Settings</strong> (If you want to use <a href="http://recaptcha.net/learnmore.html" target="_blank">reCAPTCHA</a> then you need to get reCAPTCHA API keys from <a href="http://recaptcha.net/whyrecaptcha.html" target="_blank">here</a> and use in the settings below)
	<br /><br />
	
	<form class="settings" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="6">

    <tr valign="top"><td width="25%" align="left">
    <strong>Public Key:</strong>
    </td><td align="left">
    <input name="bb_captcha_public_key" type="text" size="50" value="<?php echo bb_get_option('bb_captcha_public_key'); ?>"/>
    <br /><i>The public key for the reCAPTCHA API</i><br />
    </td></tr>  
    <tr valign="top"><td width="25%" align="left">
    <strong>Private Key:</strong>
    </td><td align="left">
    <input name="bb_captcha_private_key" type="text" size="50" value="<?php echo bb_get_option('bb_captcha_private_key'); ?>"/>
    <br /><i>The private key for the reCAPTCHA API</i><br />
    </td></tr>
    </table>	
	
    <div class="submit">
        <input type="submit" name="submit" value="Update Settings &raquo;" />
    </div>
    </form>	
	<?php 
	echo '</div>';
}

function is_bbcaptcha_registration_page() 
{	
    return (bb_find_filename($_SERVER['PHP_SELF']) == "register.php");
}

function bbcaptcha_registration_add_image_verification()
{
	if (is_bbcaptcha_registration_page()) 
	{
    	$publickey = bb_get_option('bb_captcha_public_key');
	    echo "<script type='text/javascript'>var RecaptchaOptions = { theme : 'white', lang : 'en' , tabindex : 5 };</script>";
	    echo '<fieldset><legend>Image Verification (Please prove that you are human)</legend>';
	    echo recaptcha_get_html($publickey);
	    echo '</fieldset>';
	}	
}

function bbcaptcha_verify_recaptcha()
{
	if (is_bbcaptcha_registration_page() && $_POST)
	{
		$publickey = bb_get_option('bb_captcha_public_key');
		$privatekey = bb_get_option('bb_captcha_private_key');
		if(!empty($publickey) && !empty($privatekey))
		{
		    $resp = recaptcha_check_answer($privatekey,
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);
		    if (!$resp->is_valid) 
		    {
				bb_get_header();
				echo "<h2>Error</h2><p class='error'>Image verification failed. Please <a href='register.php'>go back and try again</a>!";
				bb_get_footer();
				exit;				
		    }			
		}
		else
		{
			echo "You haven't specified the reCAPTCHA keys in the settings menu yet!";
		}
	}
	
}

add_action('bb_send_headers', 'bbcaptcha_verify_recaptcha');
add_action('extra_profile_info', 'bbcaptcha_registration_add_image_verification', 11);
?>