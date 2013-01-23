<?php
get_header('home');

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
        <div class="formline hiddeen_label dob">
        <span class="label">Date of Birth:</span>
        <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Day"); ?>
        <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Month"); ?>
        <?php IDGL_Users::IDGL_renderField($userProfile,"DOB_Year"); ?>
        </div>
        <div class="formline"><span class="label">E-mail address:</span><span class="email-field"><?php echo $userProfile->user_email; ?></span></div>
        <div class="formline">
             <?php IDGL_Users::IDGL_renderField($userProfile,"PrimaryPhone"); ?> (primary)
        </div>
        <div class="formline">
            <?php IDGL_Users::IDGL_renderField($userProfile,"SecondaryPhone"); ?>
        </div>
		<div class="formline">
            <?php IDGL_Users::IDGL_renderField($userProfile,"School"); ?>
        </div>
        <div class="formline hiddeen_label"><span class="label">Graduation Year</span>
            <?php IDGL_Users::IDGL_renderField($userProfile,"Graduation_Year"); ?>
        </div>
		
        <div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"ParentName"); ?></div>
		<div class="formline"><?php IDGL_Users::IDGL_renderField($userProfile,"ParentEmail"); ?></div>
		<br>
		<div class="formline checks"><?php IDGL_Users::IDGL_renderField($userProfile,"recieve_info"); ?></div>
		<br>
		<h4>PSAT Scores:</h4>
		<div class="formline sat-scores">
			<?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Reading"); ?>
			<?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Math"); ?>
			<?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Writing"); ?>
		</div>
		<br>
		<h4>Test Dates:</h4>
		<div class="label">Add SAT Test Date:</div><br><br>
        <div class="formline hiddeen_label test_dates">
            <?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_1"); ?>
			<span class="label">Reading</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_1_Reading"); ?>
			<span class="label">Math</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_1_Math"); ?>
			<span class="label">Writing</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_1_Writing"); ?>
        </div>
		<div class="formline hiddeen_label test_dates">
            <?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_2"); ?>
			<span class="label">Reading</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_2_Reading"); ?>
			<span class="label">Math</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_2_Math"); ?>
			<span class="label">Writing</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_2_Writing"); ?>
        </div>
		<div class="formline hiddeen_label test_dates">
            <?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_3"); ?>
			<span class="label">Reading</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_3_Reading"); ?>
			<span class="label">Math</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_3_Math"); ?>
			<span class="label">Writing</span><?php IDGL_Users::IDGL_renderField($userProfile,"SAT_Date_3_Writing"); ?>
        </div>
		<br><div class="label">Add ACT Test Date:</div><br><br>
		<div class="formline hiddeen_label test_dates">
            <?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_1"); ?>
			<span class="label">English</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_1_English"); ?>
			<span class="label">Math</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_1_Math"); ?>
			<span class="label">Reading</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_1_Reading"); ?>
			<span class="label">Science</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_1_Science"); ?>
        </div>
		<div class="formline hiddeen_label test_dates">
            <?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_2"); ?>
			<span class="label">English</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_2_English"); ?>
			<span class="label">Math</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_2_Math"); ?>
			<span class="label">Reading</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_2_Reading"); ?>
			<span class="label">Science</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_2_Science"); ?>
        </div>
		<div class="formline hiddeen_label test_dates">
            <?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_3"); ?>
			<span class="label">English</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_3_English"); ?>
			<span class="label">Math</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_3_Math"); ?>
			<span class="label">Reading</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_3_Reading"); ?>
			<span class="label">Science</span><?php IDGL_Users::IDGL_renderField($userProfile,"ACT_Date_3_Science"); ?>
        </div>
		<hr>
		
		<div class="two-column clearfix">
			<div class="column">
				<div class="purchase-info clearfix">
					<label><strong>Purchase Info</strong></label>
					<div class="column">
						<div class="label">Products:</div>
						<?php
						$products = unserialize(get_user_meta($user_id,"_IDGL_elem_ECP_user_order",true));

						switch_to_blog(3);

						$query = new WP_Query( array(
							'posts_per_page'=>-1,
							'post_type'=>'ecpproduct'
						));
						echo "<ul>";
						while ( $query->have_posts() ) : $query->the_post(); global $post;
							if( in_array($post->ID, $products)){
								echo "<li>".get_the_title()."</li>";
							}
						endwhile;
						echo "</ul>";
						wp_reset_postdata();
						
						echo '<br><br>';
						global $wpdb;
						
						$post_sql = "SELECT ID, post_content, post_title, post_name
							FROM {$wpdb -> posts}
							LEFT JOIN {$wpdb -> term_relationships} ON({$wpdb -> posts}.ID = {$wpdb -> term_relationships}.object_id)
							LEFT JOIN {$wpdb -> term_taxonomy} ON({$wpdb -> term_relationships}.term_taxonomy_id = {$wpdb -> term_taxonomy}.term_taxonomy_id)
							LEFT JOIN {$wpdb -> terms} ON({$wpdb -> term_taxonomy}.term_id = {$wpdb -> terms}.term_id)
							WHERE {$wpdb -> posts}.post_type = 'ECPProduct' 
							AND {$wpdb -> posts}.post_status = 'publish'
							AND {$wpdb -> term_taxonomy}.taxonomy = 'ecp-products'
							AND {$wpdb -> terms}.slug = 'online-sat-course' 
							ORDER BY {$wpdb -> posts}.menu_order DESC;";
						$data = $wpdb->get_results($wpdb->prepare($post_sql));
						$user_plan = null;
						foreach($data as $plan) {
							if( in_array($plan->ID, $products)){
								$user_plan = $plan->post_title;
								break;
							}
						}
						
						restore_current_blog();
						?>
					</div>
					<div class="column">
						<span class="label">Registration date:</span>
						<?php
							$reg_date = new DateTime($userProfile->user_registered);
							echo $reg_date->format('m/d/Y');
							if($user_plan) {
						?>
						<span class="label">Expiration date:</span>
						<?php
								$exp_date = strtotime($userProfile->user_registered);
								$exp_date = strtotime(date("Y-m-d", $exp_date) . "+$user_plan");
								echo date('m/d/Y', $exp_date);
							}
						?>
					</div>
				</div>
			</div>
			<div class="column">
				<div class="bluebox">
					<label><strong>Change Password</strong></label>
					<?php if($pass_note) echo "<p style='color:#fff;'>".$pass_note."</p>"; ?>
					<label><span>Old Password:</span> <input name="o_pass" type="password" value="" /></label>
					<label><span>New Password:</span> <input name="n_pass" type="password" value="" /></label>
					<label><span>Repeat New Password:</span> <input name="n_c_pass" type="password" value="" /></label>
				</div>
			</div>
		</div>
		
		<hr>
		
		<div class="profile-save-section">
			<input type="submit" class="button orange" value="Update Profile" >
		</div>
    </div>
    <?php endif; ?>
</div>
</form>

<script type="text/javascript">
	jQuery("SELECT").selectBox();
</script>
<?php get_footer(); ?>