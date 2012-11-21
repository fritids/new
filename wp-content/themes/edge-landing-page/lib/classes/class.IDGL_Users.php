<?php
require_once "class.IDGL_Node.php"; 



/**
 * 
 * Managing Users Class ...
 * @author Igor
 *
 */
class IDGL_Users{
	
	/**
	 * 
	 * Constructor...
	 */
	function IDGL_Users(){
	
	}
	/**
	 * Fetch Configuration from models calls getUserTypes and subPage
	 * 
	 * @param user $user
	 */
	public static function IDGL_addUserPanels($user){
		$postOptions=IDGL_Config::getConfig(IDG_USERS_CONF_FILE);
		$user_subtype=get_user_meta($user->ID, "_IDGL_elem_user_type", true);
		echo IDGL_Users::IDGL_getUserTypes($user);
		
		foreach($postOptions as $page){
			if($user_subtype==$page["user_type"]){
				IDGL_Users::IDGL_subPage($user_subtype,$user);
				break;
			}
			
		}
	}
	
	/**
	 *  SubPage...
	 * 
	 * @param usertype $user_subtype
	 * @param user $user
	 */
	public static function IDGL_subPage($user_subtype,$user){
		$postOptions=IDGL_Config::getConfig(IDG_USERS_CONF_FILE);
		foreach($postOptions as $page){
			if($page["user_type"]==$user_subtype){
				$optionsPage=$page;
				break;
			}
		}
		
		
		echo "<div class='IDGL_Wrap'>";
		echo "<div class='IDGL_Controls'>";
		foreach($optionsPage as $node){
			$nd=new IDGL_Node($node);
			echo $nd->renderAdmin(get_user_meta($user->ID, "_IDGL_elem_".$node["name"], true));
		}
		echo "</div></div>";
	}
	
	/**
	 * 
	 * Save user Meta in database ...
	 * @param user $user
	 */
	public static function IDGL_saveUserMeta($user){
		if(isset($_POST['IDGL_elem'])){	
			 foreach($_POST['IDGL_elem'] as $key=>$val){
			 	if(is_array($val)){
			 		update_user_meta($user, "_IDGL_elem_".$key, $val);
			 	}else{
			 		update_user_meta($user, "_IDGL_elem_".$key, addslashes($val));
			 	}
			 }
		 }
	}
	
	/**
	 * 
	 * Gets the type of user and echoes informations about type
	 * @param user $user
	 */
	public static function IDGL_getUserTypes($user){
		$user_subtype=get_user_meta($user->ID, "_IDGL_elem_user_type", true);
		echo "<h2>".$user_subtype."*</h2>";
		$postOptions=IDGL_Config::getConfig(IDG_USERS_CONF_FILE);
		echo "<select id='IDGL_elem_user_type' name='IDGL_elem[user_type]'>";
		foreach($postOptions as $page){
			if($user_subtype==$page["user_type"]){
				echo "<option selected='selected' value='".$page["user_type"]."'>".$page["user_type"]."</option>";
			}else{
				echo "<option value='".$page["user_type"]."'>".$page["user_type"]."</option>";
			}
		}
		echo "</select>";
		echo '<script type="text/javascript">
			jQuery(function(){
				jQuery("#IDGL_elem_user_type").change(function(){
					//alert(jQuery(jQuery(this).parents("form")[0]).html())
					jQuery(jQuery(this).parents("form")[0]).submit();
				})
			})
		</script>';
	}
}