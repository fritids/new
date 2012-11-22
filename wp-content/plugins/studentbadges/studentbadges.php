<?php
/*
Plugin Name: Student Badges
Plugin URI: http://edgeincollegeprep.com
Description: Custom achievements & badges for Edge Students
Version: 1.0
Author: Webdevly LLC
Author URI: http://webdev.ly
*/

$disableRunlength = 1;

add_action('admin_menu', 'registerUserBadgesMenuOption');

function userbadges_check_for_admin()
{
	if (!current_user_can('administrator'))
	wp_die(__( 'You do not have sufficient permissions to manage the badges. Please log in as admin.'));
}


function registerUserBadgesMenuOption() {
	add_menu_page('Badges/Achievements', 'Student Badges', 'administrator', 'studentbadges.php', 'userBadgesReport',   plugins_url('studentbadges/images/icon.png'), 72);
}

function userbadges_shortcode_parse($atts,$content = null)
{
	global $current_user;
	get_currentuserinfo();
	global $authordata;
	$un = 'user';
	//var_dump($authordata);
	
	switch($atts['user'])
	{
	case 'current':
		$dispU = $current_user;
		break;
	case 'author':
		$dispU = $authordata;
		break;
	default:
		$cu = get_user_by('login',$atts['user']);
		$dispU = $cu;
		if(!$dispU)
		{
			print "You have an error in the shortcode. Please try again. ERROR: User not found.";
			return;
		}	
		break;
	}

	$dispUid = $dispU->ID;
	$dispUn = $dispU->user_login;


	//$badges = "badge here";
	$badges = ub_display_badges($dispUid,1);

	switch ($atts['type'])
	{
	case 'avatarbox':
		$avatar = get_avatar($dispUid,48);
		$boxstyle = userBadgesGetOption("userbadges_css_avatarbox") ? userBadgesGetOption("userbadges_css_avatarbox") : "padding: 2px; background-color: #eee; border: 1px solid black; width: 150px; height: 52px;";
		$avatarstyle = userBadgesGetOption("userbadges_css_avatar") ? userBadgesGetOption("userbadges_css_avatar") : "float:left;";
		$badgestyle = userBadgesGetOption("userbadges_css_badges") ? userBadgesGetOption("userbadges_css_badges")  : "float:left; padding: 2px;";

		print "<div class=\"userbadgesbox\" style=\"$boxstyle\"><span class=\"userbadgesavatar\" style=\"$avatarstyle\">$avatar</span><span class=\"userbadgesboxbadges\" style=\"$badgestyle\"><b>$dispUn</b><br>$badges</span></div> <!-- end main ub -->";
		break;
	case 'box':
		$avatarstyle = "float:left;";
		$badgestyle = "float: left; padding: 2px;";
		$boxstyle = "padding: 2px; background-color: #eee; border: 1px solid black; width: 150px; height: 50px;";

		print "<div class=\"userbadgesbox\" style=\"$boxstyle\"><span class=\"userbadgesboxbadges\" style=\"$badgestyle\"><b>$dispUn</b><br>$badges</span></div> <!-- end main ub -->";
		break;
	case 'plain':
		print $badges;
		break;
	default:
		print "You have an error in the shortcode. Please try again. ERROR: Display type not found.";
		return;
	}
}

add_shortcode('userbadges', 'userbadges_shortcode_parse');

function userbadges_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', WP_PLUGIN_URL.'/studentbadges/image_upload.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}

function userbadges_admin_styles() {
	wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'ubCreate') {
	add_action('admin_print_scripts', 'userbadges_admin_scripts');
	add_action('admin_print_styles', 'userbadges_admin_styles');
}


function userBadgesReport()
{
	userbadges_check_for_admin();
?>
<h2>Student Badges Summary</h2>

<div id="poststuff" class="metabox-holder" style="width:500px;">
<div id="post-body">
    <div id="post-body-content">
	<div id="namediv" class="stuffbox">
	    <h3><label for="link_name">Available Badges</label></h3>
	    <div class="inside">
	<?php userbadges_show_badge('rnd',1); ?>
	    </div>
	</div>
    </div>
</div>
</div>

<div id="poststuff" class="metabox-holder" style="width:500px;">
<div id="post-body">
    <div id="post-body-content">
	<div id="namediv" class="stuffbox">
	    <h3><label for="link_name">Points Summary</label></h3>
	    <div class="inside">
		<?php 
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_points";	
	$results = $wpdb->get_results("select sum(points) as sump, points_type from $typesTable where 1 group by points_type");
	print "<table>";
	foreach($results as $result)
	{
		print "<tr><td><h2><span style=\"color: #ff8923;\">$result->sump </span></h2></td><td width=\"100%\"><h2>".ucfirst($result->points_type)." Points</h2></td></tr>";
	}
	print "</table>";

?>
	    </div>
	</div>
    </div>
</div>
</div>



<?php
}

function userBadgesCreate()
{
	$flag = 0;
	userbadges_check_for_admin();

	if(!empty($_POST['badge_name']))
	{
		if(empty($_POST['upload_image']))
		{
			print "<h3>Image field was blank</h3>";
			$flag = 1;
		}
		else
		{
			userbadges_add_type($_POST['badge_name'],$_POST['upload_image'],1);
			print "<h3>Badge \"".$_POST['badge_name']."\" Successfully added.</h3><br>";
		}
	}

?>

<h2>Create Badges</h2>
<div id="poststuff" class="metabox-holder" style="width:500px;">
<div id="post-body">
    <div id="post-body-content">
        <div id="namediv" class="stuffbox">
            <h3><label for="link_name">Create New Badge</label></h3>
	    <div class="inside">
		<p>Upload max of 16x16px for images. Larger images will be resized and displayed as 16x16px</p>
<table>
<form action="" method="post">
<tr>
<td rowspan="1" colspan="2">
Badge Name <input type="text" size="16" name="badge_name" value="<?php echo ($flag == 0)? "" : $_POST['badge_name']; ?>" />
</td>
</tr>

<tr>
<td>
<input id="upload_image" type="text" size="16" name="upload_image" value="" />
</td>
<td>
<input id="upload_image_button" type="button" value="Upload Image" />
</td>
</tr>
<tr><td><input id="save_button" type="submit" value="Add Badge" name="add_badge"/></td></tr>
</form>
</table>
            </div>
        </div>
    </div>
</div>
</div>

<?php	
}

// Custom Badge Trigger functions

function userBadgesTriggerAssign($userName,$badgeType)
{
	$user = get_user_by('login', $userName);
	if(!empty($user))
	{
		userbadges_add_award($user->ID,$badgeType);
		return 1;
	}
	else
	{
		return 0;
	}
}

// End trigger functions

add_action('admin_menu', 'registerUserBadgesSubMenuOpts');

function userBadgesAssign()
{
	userbadges_check_for_admin();
	if(!empty($_POST['user_name']))
	{
		$user = get_user_by('login', $_POST['user_name']);
		if(!empty($user))
		{
			userbadges_add_award($user->ID,$_POST['badges']);
			print "<h3>Badge successfully assigned</h3>";
		}
		else
		{
			print "<h3>User not found</h3>";
		}
	}
?>

<h2>Assign Badges</h2>

<div id="poststuff" class="metabox-holder" style="width:500px;">
<div id="post-body">
    <div id="post-body-content">
	<div id="namediv" class="stuffbox">
	    <h3><label for="link_name">Assign Badge To Student</label></h3>
	    <div class="inside">
<table>
<form action="" method="post">
<tr>
<td rowspan="1" colspan="2">
User Name <input id="user_name" type="text" size="16" name="user_name" value="" />
</td>
</tr>

<tr>
<td rowspan="1" colspan="2">
Select Badge
</td>
</tr>
</table>
<select name="badges">

<?php
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_types";

	$badges = $wpdb->get_results("select * from $typesTable where `custom` = '1'");

	foreach($badges as $badge)
	{
		print "<option value=\"".$badge->name."\">".$badge->name."</value>";
	}

?>

</select>
<input type="submit" value="Assign" />
</form>
	    </div>
	</div>
    </div>
</div>
</div>

<?php	
}

function userBadgesRemove()
{
	userbadges_check_for_admin();
	if(!empty($_POST['user_name']))
	{
		$user = get_user_by('login', $_POST['user_name']);
		if(!empty($user))
		{
			userbadges_remove_award($user->ID,$_POST['badges']);
			print "<h3>Badge successfully removed</h3>";
		}
		else
		{
			print "<h3>User not found</h3>";
		}
	}
?>

<h2>Remove Custom Badge from Student</h2>

<div id="poststuff" class="metabox-holder" style="width:500px;">
<div id="post-body">
    <div id="post-body-content">
	<div id="namediv" class="stuffbox">
	    <h3><label for="link_name">Remove Badge From Student</label></h3>
	    <div class="inside">
<table>
<form action="" method="post">
<tr>
<td rowspan="1" colspan="2">
User Name <input id="user_name" type="text" size="16" name="user_name" value="" />
</td>
</tr>

<tr>
<td rowspan="1" colspan="2">
Select Badge
</td>
</tr>
</table>
<select name="badges">

<?php
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_types";

	$badges = $wpdb->get_results("select * from $typesTable where `custom` = '1'");

	foreach($badges as $badge)
	{
		print "<option value=\"".$badge->name."\">".$badge->name."</value>";
	}

?>

</select>
<input type="submit" value="Remove" />
</form>
	    </div>
	</div>
    </div>
</div>
</div>

<?php	
}

function userbadges_rebuild_comment_points()
{
	userbadges_check_for_admin();
	global $wpdb;

	$typesTable = $wpdb->prefix . "userbadges_points";
	$commentsTable = $wpdb->prefix . "comments";
	
	$wpdb->query($wpdb->prepare("DELETE FROM $typesTable WHERE points_type = %s",'comment'));
	$results = $wpdb->get_results("Select * FROM $commentsTable WHERE `comment_approved` = '1'");
	foreach($results as $result)
	{
		userbadges_add_points(
			$result->user_id, 'comment', -1, $result->comment_ID
		);
	}
}

function userBadgesReBuild()
{
	userbadges_check_for_admin();
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_points";
	$commentsTable = $wpdb->prefix . "comments";

	if(empty($_POST['yes']))
	{
?>
	<form action="" method="post">
	<p><b>Are you sure you want to rebuild comment points?</b></p>
	<p>Doing so will remove all existing comment points and rebuild the database. This will take some time to finish depending on how many comments you have.</p>
	<input type="hidden" name="yes" value="1">
	<input type="submit" value="Yes">
	</form>
<?php
	}
	else
	{
		print "Deleting Existing Points...<br>";
		$wpdb->query($wpdb->prepare("DELETE FROM $typesTable WHERE points_type = %s",'comment'));
		print "Rebuilding points..<br>";

		$results = $wpdb->get_results("Select * FROM $commentsTable WHERE `comment_approved` = '1'");

		foreach($results as $result)
		{
			userbadges_add_points(
				$result->user_id, 'comment', -1, $result->comment_ID
			);
		}
		print "Successfully rebuilt points<br>";
	}
}

function userBadgesSetOption($optname,$value)
{
	global $wpdb;
	$optionsTable = $wpdb->prefix."options";
	
	$wpdb->query($wpdb->prepare("delete from $optionsTable where `option_name` = %s",$optname));
	$wpdb->insert( $optionsTable,array( 'option_name' => $optname,'option_value' => $value),array('%s','%s'));
}

function userBadgesGetOption($optname)
{
	global $wpdb;
	$optionsTable = $wpdb->prefix."options";

	$res = $wpdb->get_row($wpdb->prepare("select * from $optionsTable where `option_name` = %s",$optname));
	return $res->option_value;
}


function userBadgesSettings()
{
	userbadges_check_for_admin();
	print "<div class=\"wrap\"><h2>Settings</h2>";
	$chk ='';
	$warnImg = plugins_url()."/studentbadges/images/warning.png";
	//userBadgesSetOption('userbadges_append_to_comments',1);
	$settingsVariableList = array(
		array("desc" => "Automatically Display Student Badges on Comments", "name" => "userbadges_append_to_comments", "type" => "check"),
		array("desc" => "Resize Badges to 16x16", "name" => "userbadges_resize_icons", "type" => "check"),
		array("desc" => "Show Auto Assigned Badges", "name" => "userbadges_show_auto_badges", "type" => "check"),
		array("desc" => "Number of Points per Student Action", "name" => "userbadges_points_size", "type" => "text"),
		array("desc" => "<br><div style=\"background-color: #B1DDFA; border: 1px solid; padding: 5px;\"><table><tr><td><img width=\"16\" src=\"$warnImg\">  The settings below are for advanced users only. Do not worry about them if you do not know CSS or if you do not want to modify the looks of badges.</td></tr></table></div><br>", "name" => "", "type" => "info"),
		array("desc" => "CSS File Path (put the full path, like http://edgeincollegeprep.com/styles/ubcustomstyle.css)", "name" => "userbadges_css_url", "type" => "text"),
		array("desc" => "Class name for the Badge Icon Image (E.g. badgeClass)", "name" => "userbadges_css_image_class", "type" => "text"),
		array("desc" => "CSS Before Block", "name" => "userbadges_css_before_block", "type" => "text"),
		array("desc" => "CSS After Block", "name" => "userbadges_css_after_block", "type" => "text"),
		array("desc" => "CSS Before Image", "name" => "userbadges_css_before_image", "type" => "text"),
		array("desc" => "CSS After Image", "name" => "userbadges_css_after_image", "type" => "text"),
		array("desc" => "<br><div style=\"background-color: #B1DDFA; border: 1px solid; padding: 5px;\"><table><tr><td><img width=\"16\" src=\"$warnImg\">  The settings below are for boxes displayed via shortcodes. Do not touch them if you do not know CSS.</td></tr></table></div><br>", "name" => "", "type" => "info"),
		array("desc" => "CSS for userbadges shortcode box", "name" => "userbadges_css_avatarbox", "type" => "text"),
		array("desc" => "CSS for userbadges shortcode box avatar", "name" => "userbadges_css_avatar", "type" => "text"),
		array("desc" => "CSS for userbadges shortcode box badges", "name" => "userbadges_css_badges", "type" => "text")
	);

	// var_dump($_POST);
	
	if(!empty($_POST['seed']))
	{
		print "Settings Saved";

		foreach($settingsVariableList as $optionArray)
		{
			if($optionArray['type'] == "check")
			{
				if(!empty($_POST[$optionArray['name']]))
					userBadgesSetOption($optionArray['name'],1);
				else
					userBadgesSetOption($optionArray['name'],0);
			}
			
			if($optionArray['type'] == "text")
			{
				if(!empty($_POST[$optionArray['name']]))
					userBadgesSetOption($optionArray['name'],$_POST[$optionArray['name']]);
			}

		}
	}

	print '<form action="" method="post"><br><br>';

	print "<table width=\"50%\">";
	foreach($settingsVariableList as $optionArray)
	{
		if($optionArray['type'] == "check" )
		{
			if(userBadgesGetOption($optionArray['name']))
			{
				$chk = 'checked="checked"';
			}
			else
				$chk = '';

			print 	"<tr><td>{$optionArray['desc']}</td><td><input type=\"checkbox\" $chk name=\"{$optionArray['name']}\"></td></tr>";
		}

		if($optionArray['type'] == "info")
		{
			print "<tr><td>".$optionArray['desc']."</td></tr>";
		}


		if($optionArray['type'] == "text" )
		{
			$prevValue = userBadgesGetOption($optionArray['name']);
			print 	"<tr><td>{$optionArray['desc']}</td><td><input type=\"text\" $chk name=\"{$optionArray['name']}\" value=\"$prevValue\"></td></tr>";
		}

	}
?>
	<input type="hidden" name="seed" value="1" />
	<tr><td><input name="Submit" type="submit" value="Save Changes" /></td></tr>
</table>
</form></div>

<?php	
			
}


function registerUserBadgesSubMenuOpts() {
	add_submenu_page( 'studentbadges.php', 'Create Badge', 'Create Badge', 'administrator', 'ubCreate', 'userBadgesCreate' ); 
	add_submenu_page( 'studentbadges.php', 'Assign Badge', 'Assign Badge', 'administrator', 'ubAssign', 'userBadgesAssign' ); 
	add_submenu_page( 'studentbadges.php', 'Remove Badge', 'Remove Badge', 'administrator', 'ubRemove', 'userBadgesRemove' ); 
	add_submenu_page( 'studentbadges.php', 'Settings', 'Settings', 'administrator', 'ubSettings', 'userBadgesSettings' ); 
	//add_submenu_page( 'studentbadges.php', 'Rebuild Comment Points', 'Rebuild Comment Points', 'administrator', 'ubRebuild', 'userBadgesReBuild' ); 
}


function userbadges_get_runlength($uid)
{
	global $current_user,$wpdb;
	global $disableRunlength;

	if($disableRunlength)
		return;

	get_currentuserinfo();

	if($uid == 0)
		return;

	$pointsTable = $wpdb->prefix . "userbadges_points";

	// Mainpage visit points
	$sql = "SELECT awarded FROM $pointsTable WHERE points_type = 'mainpage' AND user_id = $uid ORDER BY awarded DESC";
	$runs = $wpdb->get_results($sql);

	$maxRunLength = 0;
	$prevAward = 0;
	$rl = 0;

	foreach($runs as $run)
	{
		if($prevAward == 0)
		{
			$prevAward = $run->awarded;
			continue;
		}

		$caplus1 = date("d-m-Y",strtotime("+1 day",$run->awarded));
		$caplus1 = explode('-',$caplus1);

		$pad = date("d-m-Y",$prevAward);
		$pad = explode('-',$pad);

		if( ($caplus1[0] == $pad[0]) && ($caplus1[1] == $pad[1])
			&& ( ($caplus1[2] == $pad[2] ) ) )
		{
			$rl++;
		}
		else
		{
			if($rl > $maxRunLength)
			{
				$maxRunLength = $rl;
			}
			else
			{
				$rl = 0;
			}
		}

		$prevAward = $run->awarded;
	}
	return $maxRunLength;
}

function userbadges_get_points($uid = 0)
{
	global $current_user,$wpdb;
	get_currentuserinfo();

	if($uid == 0)
		return 0;

	$pointsTable = $wpdb->prefix . "userbadges_points";

	// Mainpage visit points
	$sql = "SELECT sum(points) as points FROM $pointsTable WHERE user_id = $uid";
	$points = $wpdb->get_results($sql);

	foreach($points as $point)
		return $point->points;
}

function userbadges_get_comment_count($uid = 0)
{
	global $current_user,$wpdb;
	get_currentuserinfo();

	if($uid == 0)
		return 0;

	$pointsTable = $wpdb->prefix . "comments";

	// Mainpage visit points
	$sql = "SELECT count(*) as ccount FROM $pointsTable WHERE user_id = $uid";
	$points = $wpdb->get_row($sql);

	return $points->ccount;
}

function userbadges_default_display($content)
{
	if(!userBadgesGetOption('userbadges_append_to_comments'))
		return $content;
	
	$comment_ID = get_comment_ID();
	$comment = get_comment($comment_id);

	$cuid = $comment->user_id;

	$ad = ub_display_badges($cuid,1);
	return $ad."<br>".$content;
}

add_filter('comment_text', 'userbadges_default_display');

function userbadges_comment_viewed($content)
{

	$comment_ID = get_comment_ID();
	$comment = get_comment($comment_id);

	$cuid = $comment->user_id;

	global $current_user,$wpdb;
	get_currentuserinfo();
	$uid = $current_user->ID;

	if($cuid != $uid)
		return $content;


	$pointsTable = $wpdb->prefix . "userbadges_points";

	// Mainpage visit points
	$sql = "SELECT * FROM $pointsTable WHERE comment_id = $comment_ID";
	$points = $wpdb->get_results($sql);

	if(!empty($points))
		return $content;

	if ($comment->comment_approved == 1) {

		userbadges_add_points($current_user->ID, 'comment', -1, $comment_ID);
	}

	return $content;

}

add_filter('comment_text', 'userbadges_comment_viewed');

function userbadges_remove_award($uid, $bid)
{
	userbadges_check_for_admin();
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_awards";

	$wpdb->query( 
		$wpdb->prepare( 
			"
			DELETE FROM $typesTable
			WHERE user_id = %d
			AND badge_id = %s
			",
			$uid, $bid 
		)
	);
}

function userbadges_add_award($uid, $bid)
{
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_awards";

	$wpdb->insert( $typesTable,array( 'user_id' => $uid,'badge_id' => $bid),array('%d','%s'));
}

function userbadges_add_points($uid, $points_type, $postid, $commentid)
{
	global $wpdb;
	$points = userBadgesGetOption('userbadges_points_size') ;
	$points = ( $points == '' )? 0 : $points;
	$typesTable = $wpdb->prefix . "userbadges_points";
	$now = current_time('timestamp');

	$wpdb->insert( $typesTable,array( 'user_id' => $uid,'awarded' => $now, 'points' => $points, 'points_type' => $points_type, 'post_id' => $postid, 'comment_id' => $commentid),array('%d','%d','%d','%s','%d','%d'));
}

function userbadges_add_type($name, $icon, $type = 0)
{
	userbadges_check_for_admin();
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_types";

	$wpdb->insert( $typesTable,array( 'name' => $name,'icon' => $icon, 'custom' => $type),array('%s','%s','%d'));
}

function userbadges_show_badge($name, $all = 0)
{
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_types";
	
	$resize = userBadgesGetOption('userbadges_resize_icons') ? "width=\"16\" height=\"16\"" : "";

	if($all == 1)
	{
		print "<h4>Custom Badges</h4><br>";
		$badges = $wpdb->get_results("select * from $typesTable where `custom` = '1'");

		foreach($badges as $badge)
		{
			print "<img $resize src=\"".$badge->icon."\" title=\"".$badge->name."\" >";
		}

		$badges = $wpdb->get_results("select * from $typesTable where `custom` = '0'");
		print "<h4>Automatically Assigned Badges Badges</h4><br>";
		if(userBadgesGetOption("userbadges_show_auto_badges"))
		{
			foreach($badges as $badge)
			{
				print "<img $resize src=\"".$badge->icon."\" title=\"".$badge->name."\" >";
			}
		}
		else
		{
			print "Auto assigned badges Disabled. Please enable it in settings";
		}
		return;

	}
	else
	{
		$badges = $wpdb->get_results("select * from $typesTable where `name` = '$name'");
	}

	$ad = '';

	$ad .= userBadgesGetOption('userbadges_css_before_block');
	
	if(userBadgesGetOption('userbadges_css_image_class'))
	{
		$imageClass = "class=\"".userBadgesGetOption('userbadges_css_image_class')."\" ";
	}


	foreach($badges as $badge)
	{
		$ad .= userBadgesGetOption('userbadges_css_before_image')."<img $resize $imageClass src=\"".$badge->icon."\" title=\"".$badge->name."\" >".userBadgesGetOption('userbadges_css_after_image');
	}

	$ad .= userBadgesGetOption('userbadges_css_after_block');

	return $ad;
}

function userbadges_show_awards($uid)
{
	global $wpdb;
	$typesTable = $wpdb->prefix . "userbadges_types";
	$awardsTable = $wpdb->prefix . "userbadges_awards";
	
	$resize = userBadgesGetOption('userbadges_resize_icons') ? "width=\"16\" height=\"16\"" : "";

	$awards = $wpdb->get_results("select * from $awardsTable where `user_id` = '$uid'");

	$ad = '';

	$ad .= userBadgesGetOption('userbadges_css_before_block');

	foreach($awards as $name)
	{
		$badges = $wpdb->get_results("select * from $typesTable where `name` = '$name->badge_id'");
		/*
		 * foreach($badges as $badge)
		{
			$ad .= "<img $resize src=\"".$badge->icon."\" title=\"".$badge->name."\" >";
		}
		 */
		if(userBadgesGetOption('userbadges_css_image_class'))
		{
			$imageClass = "class=\"".userBadgesGetOption('userbadges_css_image_class')."\" ";
		}

		foreach($badges as $badge) {
		
			$ad .= userBadgesGetOption('userbadges_css_before_image')."<img $resize $imageClass src=\"".$badge->icon."\" title=\"".$badge->name."\" >".userBadgesGetOption('userbadges_css_after_image');
		}
	}

	$ad .= userBadgesGetOption('userbadges_css_after_block');

	return $ad;
}


function ub_display_badges($uid, $returnTxt = 0)
{
	global $wpdb;
	if(empty($uid))
		return;

	$typesTable = $wpdb->prefix . "userbadges_types";
	$awardsTable = $wpdb->prefix . "userbadges_awards";

	$ad = '';

	if(userBadgesGetOption("userbadges_show_auto_badges"))
	{
		$ad .= userbadges_show_badge("Registered Student");
		$lpoints = userbadges_get_points($uid);

		if($lpoints > 100 && $lpoints < 500)
			$ad .= userbadges_show_badge("500+ Points");
		else if($lpoints > 500 && $lpoints <1000)
			$ad .= userbadges_show_badge("500+ Points");
		else if($lpoints > 1000)
			$ad .= userbadges_show_badge("1000+ Points");
		else
			$ad .= userbadges_show_badge("1-100 Points");


		$lpoints = userbadges_get_comment_count($uid);

		if($lpoints >= 1 && $lpoints < 50)
			$ad .= userbadges_show_badge("Has completed 1 to 50 exercises");
		else if($lpoints > 50 && $lpoints <100)
			$ad .= userbadges_show_badge("Has completed 51 to 100 exercises");
		else if($lpoints > 100)
			$ad .= userbadges_show_badge("Has completed 100+ exercises");
	}		

	$ad .= userbadges_show_awards($uid);

	if($returnTxt)
		return $ad;
	else
		print $ad;

}

function userbadges_mainpage_points()
{
	global $wpdb, $current_user;
	global $disableRunlength;
	get_currentuserinfo();
	$uid = $current_user->ID;

	$pointsTable = $wpdb->prefix . "userbadges_points";
	$rlTable = $wpdb->prefix . "userbadges_runlengths";

	$today = current_time('mysql');
	$today = explode(" ",$today);
	$today = explode("-",$today[0]);

	$today00 = mktime(0,0,0, $today[1],$today[2],$today[0]);
	$today2359 = mktime(23,59,59, $today[1],$today[2],$today[0]);
	

	// Mainpage visit points
	$sql = "SELECT * FROM $pointsTable WHERE user_id = '$uid' AND awarded >= '$today00' AND awarded < '$today2359' AND points_type = 'mainpage'";
	$points = $wpdb->get_results($sql);

	if(empty($points))
	{
		userbadges_add_points($uid, 'mainpage', -1, -1);
	}
	else
	{
		print "<!-- userbadges_debug: Mainpage Points already assigned for today! -->";
	}
	// Mainpage visit end
	// Update Runlength

	if($disableRunlength)
		return;

	$maxRl = userbadges_get_runlength($uid);

	$sql = "SELECT runlength FROM $rlTable WHERE user_id = '$uid'";
	$points = $wpdb->get_var( $wpdb->prepare( $sql ) );

	if(empty($points))
	{
		$wpdb->insert(
			$rlTable, 
			array( 
				'user_id' => $uid, 
				'runlength' => $maxRl 
			), 
			array( 
				'%d', 
				'%d' 
			) 
		);
	}
	else
	{
		if($points < $maxRl)
		{
			$wpdb->update( 
				$rlTable, 
				array( 
					'runlength' => $maxRl	// integer (number) 
				), 
				array( 'user_id' => $uid ), 
				array( 
					'%d'	// value2
				), 
				array( '%d' ) 
			);			
		}
	}

}

function userbadges_assign_post_badges($post_id)
{
	// Start with points per visit
	global $wpdb, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;

	$today = current_time('mysql');
	$today = explode(" ",$today);
	$today = explode("-",$today[0]);

	$today00 = mktime(0,0,0, $today[1],$today[2],$today[0]);
	$today2359 = mktime(23,59,59, $today[1],$today[2],$today[0]);


	$pointsTable = $wpdb->prefix . "userbadges_points";

	$sql = "SELECT * FROM $pointsTable WHERE  post_id = '$post_id' AND user_id = '$uid' AND points_type = 'visit'";
	$points = $wpdb->get_results($sql);

	if(empty($points))
	{
		userbadges_add_points($uid, 'visit', $post_id, -1);
	}
	else
	{
		print "<!-- userbadges_debug: Post Points already assigned for ! -->";
	}

}

function userbadges_postviewed() 
{
	global $user_ID, $post;
	global $current_user;
	get_currentuserinfo();

	if($cssUrl = userBadgesGetOption('userbadges_css_url'))
	{
		wp_register_style( 'userbadges-award-style', $cssUrl );
        	wp_enqueue_style( 'userbadges-award-style' );
	}	

	if(!is_user_logged_in())
		return;
	
	if(is_int($post)) 
	{
		$post = get_post($post);
	}

	userbadges_mainpage_points();
	
	if(!wp_is_post_revision($post)) 
	{
		if(is_single() || is_page()) 
		{
			
			$id = intval($post->ID);

			userbadges_assign_post_badges($id);
		}
	}
}
add_action('wp_head', 'userbadges_postviewed');

function userbadges_install() 
{

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	// Create tables
	global $wpdb;
	
$ubprefix = $wpdb->prefix;
$sql = "
CREATE TABLE IF NOT EXISTS `{$ubprefix}userbadges_awards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `badge_id` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
); ";

dbDelta($sql);

$sql = "
CREATE TABLE IF NOT EXISTS `{$ubprefix}userbadges_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `points_type` varchar(64) NOT NULL,
  `points` int(11) NOT NULL,
  `awarded` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '-1',
  `comment_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
);";

dbDelta($sql);

$sql = "
CREATE TABLE IF NOT EXISTS `{$ubprefix}userbadges_runlengths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `runlength` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);";

dbDelta($sql);

$sql = " CREATE TABLE IF NOT EXISTS `{$ubprefix}userbadges_types` (
  `badge_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `custom` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`badge_id`)
);";

dbDelta($sql);



// Add badge types
//

if(!$wpdb->query("Select * from {$ubprefix}userbadges_types where 1"))
{
	userbadges_add_type('Registered Student',plugins_url()."/studentbadges/images/user.png");

	userbadges_add_type('Random Example',plugins_url()."/studentbadges/images/cake.png",1);
	userbadges_add_type('Top Student',plugins_url()."/studentbadges/images/star.png",1);

	userbadges_add_type('Has Viewed 1 to 50 exercises',plugins_url()."/studentbadges/images/bronze.png");
	userbadges_add_type('Has Viewed 51 to 100 exercises',plugins_url()."/studentbadges/images/silver.png");
	userbadges_add_type('Has Viewed 100+ exercises',plugins_url()."/studentbadges/images/gold.png");

	userbadges_add_type('Has posted 1 to 50 comments',plugins_url()."/studentbadges/images/tbronze.png");
	userbadges_add_type('Has posted 51 to 100 comments',plugins_url()."/studentbadges/images/tsilver.png");
	userbadges_add_type('Has posted 100+ comments',plugins_url()."/studentbadges/images/tgold.png");

	userbadges_add_type('100+ Points',plugins_url()."/studentbadges/images/mbronze.png");
	userbadges_add_type('500+ Points',plugins_url()."/studentbadges/images/msilver.png");
	userbadges_add_type('1000+ Points',plugins_url()."/studentbadges/images/mgold.png");
	userbadges_add_type('1-100 Points',plugins_url()."/studentbadges/images/greystar.png");
}

	// Rebuild comments
userbadges_rebuild_comment_points();

userBadgesSetOption("userbadges_resize_icons", 1);
userBadgesSetOption("userbadges_show_auto_badges", 1);
userBadgesSetOption("userbadges_points_size", 10);
}

register_activation_hook(__FILE__,'userbadges_install');



?>