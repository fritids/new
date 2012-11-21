<?php
get_header();

include dirname(__FILE__)."/menues/menu_student.php";

$userProfile=ECPUser::getUserData();

if(isset($_POST["first_name"])){
	IDGL_Users::IDGL_saveUserMeta($userProfile);
}
 ?>
<form action="" method="post">
<div class="whitebox profile">
    <div class="split">
        
        <div class="formline"><span class="label">First Name:</span> <input name="first_name" type="text" value="<?php echo $userProfile->first_name; ?>" /></div>
        <div class="formline"><span class="label">Last Name:</span> <input name="last_name" type="text" value="<?php echo $userProfile->last_name; ?>" /></div>
        <div class="formline"><span class="label">Gender:</span> 
          <?php IDGL_Users::IDGL_renderField($userProfile,"Gender"); ?>
        </div>
        <div class="formline"><span class="label">Date of Birth:</span>
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
        <div class="formline"><span class="label">Graduation Year</span>
            <?php IDGL_Users::IDGL_renderField($userProfile,"Graduation_Year"); ?>
        </div>
    </div>
    <div class="split right">
        <div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"ParentName"); ?></div>
        <div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"ParentEmail"); ?></div>   
        <div class="formline checks"><?php IDGL_Users::IDGL_renderField($userProfile,"recieve_info"); ?></div>
        <div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"Pretest_Scores"); ?></div>
        <div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"Final_SAT_Scores"); ?></div>
        <div class="bluebox">
            <label><strong>Change Password</strong></label>
            <label><span>Old Password:</span> <input name="" type="text" value="" /></label>
            <label><span>New Password:</span> <input name="" type="text" value="" /></label>
            <label><span>Repeat New Password:</span> <input name="" type="text" value="" /></label>
        </div>
        <input type="submit" class="button green right" value="Update Profile">
    </div>
</div>
</form>
<?php get_footer(); ?>
