<?php
get_header();

include dirname(__FILE__)."/../menues/menu_student.php";

global $wp_query;

$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);

if(isset($_POST["first_name"])){
	IDGL_Users::IDGL_saveUserMeta($user_id);
	update_user_meta( $user_id, "first_name", $_POST["first_name"] );
	update_user_meta( $user_id, "last_name", $_POST["last_name"] );
}
$userProfile=ECPUser::getUserData($user_id);

//user_pass
global $current_user;
$pass_note="";
get_currentuserinfo();
if($_POST["o_pass"]){
	$pass_match=wp_check_password($_POST["o_pass"],$current_user->user_pass, $current_user->ID);
	if(!$pass_match){
		$pass_note="The password you've entered does not match with your current password";
	}else{
		if($_POST["n_pass"]!=$_POST["n_c_pass"]){
			$pass_note="The new passwords does not match";
		}else{
			$udata=array(
				'ID' => $user_id,
				'user_pass'=>$_POST["n_pass"]
			);
			wp_update_user($udata);
			$pass_note="Your password has been successfully updated";
		}
	}
}
     
$sections=get_user_meta($user_id,"_IDGL_elem_user_type",true);


 ?>
<style type="text/css">
	.hiddeen_label .IDGL_listNode .label{
		display: none;
	} 
</style>

<form action="" method="post">
<div class="whitebox profile clearfix">
    
        <?php if($sections=="teacher"): ?>
        <div class="split">   
	        <div class="formline"><span class="label">First Name:</span> <input name="first_name" type="text" value="<?php echo $userProfile->first_name; ?>" /></div>
	        <div class="formline"><span class="label">Last Name:</span> <input name="last_name" type="text" value="<?php echo $userProfile->last_name; ?>" /></div>
	    </div>
	    <div class="split right">
	        <div class="bluebox">
	            <label><strong>Change Password</strong></label>
	            <?php if($pass_note) echo "<p style='color:#fff;'>".$pass_note."</p>"; ?>
	            <label><span>Old Password:</span> <input name="o_pass" type="password" value="" /></label>
	            <label><span>New Password:</span> <input name="n_pass" type="password" value="" /></label>
	            <label><span>Repeat New Password:</span> <input name="n_c_pass" type="password" value="" /></label>
	        </div>
	        <input type="submit" class="button green right" value="Update Profile">
	    </div>
        <?php else: ?>
     <div class="split">   
        <div class="formline"><span class="label">First Name:</span> <input name="first_name" type="text" value="<?php echo $userProfile->first_name; ?>" /></div>
        <div class="formline"><span class="label">Last Name:</span> <input name="last_name" type="text" value="<?php echo $userProfile->last_name; ?>" /></div>
        <div class="formline hiddeen_label"><span class="label">Gender:</span> 
          <?php IDGL_Users::IDGL_renderField($userProfile,"Gender"); ?>
        </div>
        <div class="formline hiddeen_label">
        <span class="label">Date of Birth:</span>
        <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Day"); ?>
        <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Month"); ?>
        <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Year"); ?>
        </div>
        <div class="formline"><span class="label">E-mail address:</span> <?php echo $userProfile->user_email; ?></div>
        <div class="formline">
             <?php IDGL_Users::IDGL_renderField($userProfile,"PrimaryPhone"); ?> - primary
        </div>
        <div class="formline">
            <?php IDGL_Users::IDGL_renderField($userProfile,"SecondaryPhone"); ?>
        </div>
        <div class="formline hiddeen_label"><span class="label">Graduation Year</span>
            <?php IDGL_Users::IDGL_renderField($userProfile,"Graduation_Year"); ?>
        </div>
		
        <div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"ParentName"); ?></div>
		<div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"ParentEmail"); ?></div>   
		<div class="formline checks"><?php IDGL_Users::IDGL_renderField($userProfile,"recieve_info"); ?></div>
		<div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"Pretest_Scores"); ?></div>
		<div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"Final_SAT_Scores"); ?></div>
        <div class="bluebox">
            <label><strong>Change Password</strong></label>
            <?php if($pass_note) echo "<p style='color:#fff;'>".$pass_note."</p>"; ?>
            <label><span>Old Password:</span> <input name="o_pass" type="password" value="" /></label>
            <label><span>New Password:</span> <input name="n_pass" type="password" value="" /></label>
            <label><span>Repeat New Password:</span> <input name="n_c_pass" type="password" value="" /></label>
        </div>
		<div class="profile-save-section">
			<input type="submit" class="button orange" value="Update Profile" >
		</div>
    </div>
    <?php endif; ?>
</div>
</form>
<?php get_footer(); ?>