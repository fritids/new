<?php
get_header();

include dirname(__FILE__)."/../menues/menu_student.php";

global $wp_query;
$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);

$userProfile=ECPUser::getUserData($user_id);
if(isset($_POST["first_name"])){
	IDGL_Users::IDGL_saveUserMeta($userProfile);
}
 ?>
<form action="" method="post">
<div class="whitebox">
            	<div class="split">
                	
            		<label><span>First Name:</span> <input name="first_name" type="text" value="<?php echo $userProfile->first_name; ?>" size="30" /></label>
                    <label><span>Last Name:</span> <input name="last_name" type="text" value="<?php echo $userProfile->last_name; ?>" size="30" /></label>
                    <label><span>Gender:</span> 
                      <?php IDGL_Users::IDGL_renderField($userProfile,"Gender"); ?>
                    </label>
                    <label><span>Date of Birth:</span></label>
                    <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Day"); ?>
                    <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Month"); ?>
                    <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Year"); ?>
                    <label><span>E-mail address:</span> <?php echo $userProfile->user_email; ?></label>
                    <label><span>Phone:</span> 
                         <?php IDGL_Users::IDGL_renderField($userProfile,"PrimaryPhone"); ?> - primary
                    </label>
                    <label>
                        <span>&nbsp;</span>
                        <?php IDGL_Users::IDGL_renderField($userProfile,"SecondaryPhone"); ?>
                    </label>
                    <label><span>Graduation Year</span>
                        <?php IDGL_Users::IDGL_renderField($userProfile,"Graduation_Year"); ?>
                    </label>
                </div>
                <div class="split right">
            		<label><?php IDGL_Users::IDGL_renderField($userProfile,"ParentName"); ?></label>
                    <label><?php IDGL_Users::IDGL_renderField($userProfile,"ParentEmail"); ?></label>   
					<?php IDGL_Users::IDGL_renderField($userProfile,"recieve_info"); ?>
                    <label><?php IDGL_Users::IDGL_renderField($userProfile,"Pretest_Scores"); ?></label>
                    <label><?php IDGL_Users::IDGL_renderField($userProfile,"Final_SAT_Scores"); ?></label>
                    <div class="bluebox">
                    	<label><strong>Change Password</strong></label>
                        <label><span>Old Password:</span> <input name="" type="text" value="" size="30" /></label>
                        <label><span>New Password:</span> <input name="" type="text" value="" size="30" /></label>
                        <label><span>Repeat New Password:</span> <input name="" type="text" value="" size="30" /></label>
                    </div>
                    <input type="submit" class="button green right" value="Update Profile">
                </div>
			</div>
</form>
<?php get_footer(); ?>