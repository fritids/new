<div class="wrap">
<?php
	require_once IDG_PLUGINS_PATH.'ecp_user_management/templates/search_bar.php';

	$url = Util::curPageURL();
	$pUrl = parse_url($url);	
	$queryVars = $pUrl["query"];
	
	$sort = $_GET["sort"];
	$search = $_POST["s_type"];	
?>

	<ul class="subsubsub">
		<li>
			<a <?php if(!isset($_GET["filter"])){ ?>class="current"<?php }?> href="/ecp/portal/wp-admin/admin.php?page=student-list">All</span></a>
		</li>
		<li>
			<a <?php if($_GET["filter"]=="online"){ ?>class="current"<?php }?> href="<?php echo "?".Util::AddReplaceQueryVars($queryVars, "filter", "online"); ?>">Online Students</a>
		</li>
		<li>
			<a <?php if($_GET["filter"]=="live"){ ?>class="current"<?php }?> href="<?php echo  "?".Util::AddReplaceQueryVars($queryVars, "filter", "live"); ?>">Live Students</a>
		</li>
		<li>
			<a <?php if($_GET["filter"]=="school"){ ?>class="current"<?php }?> href="<?php echo "?".Util::AddReplaceQueryVars($queryVars, "filter", "school"); ?>">School Students</a>
		</li>
	</ul>
	<?php
		global $wpdb;
	
		if(isset($_GET["filter"]))
		{
			$filter = " AND meta_value LIKE '%online-student%' ";
			if($_GET["filter"] == "live")
			{
				$filter = " AND meta_value LIKE '%offline-student%' ";
			}
			elseif($_GET["filter"] == "school")
			{
				$filter = " AND meta_value LIKE '%school-student%' ";
			}

			$q = "SELECT u.ID,  
						 u.user_login AS Login_Name, 
						 u.user_nicename AS Nicename, 
						 u.user_email AS Email, 
						 u.display_name AS Display, 
						 FName.meta_value as 'First_Name', 
						 LName.meta_value AS 'Last_Name', 
						 School.meta_value AS 'School',
						 Terms.meta_value AS 'Terms_Approval',
						 u.user_registered AS 'Register_Date'
						 FROM $wpdb->users AS u
						 LEFT JOIN ( 
							SELECT user_id, meta_value
							FROM $wpdb->usermeta 
							WHERE meta_key IN ('_IDGL_elem_user_type', '_IDGL_elem_userSubtype')
							{$filter}
							GROUP BY user_id
						 ) AS Meta 
						 ON u.ID = Meta.user_id
						 JOIN (
							 SELECT user_id, meta_key, meta_value  
							 FROM $wpdb->usermeta 
							 WHERE meta_key = 'first_name' AND meta_value <> ''
						 ) AS FName
						 ON u.ID = FName.user_id
						 JOIN (
							SELECT user_id, meta_key, meta_value  
							FROM $wpdb->usermeta 
							WHERE meta_key = 'last_name' AND meta_value <> ''
						 ) AS LName
						 ON u.ID = LName.user_id 
						 LEFT OUTER JOIN (
							SELECT user_id, meta_key, meta_value  
							FROM $wpdb->usermeta 
							WHERE meta_key = '_IDGL_elem_school'
						 ) AS School
						 ON u.ID = School.user_id
						 LEFT OUTER JOIN (
							SELECT user_id, meta_key, meta_value  
							FROM $wpdb->usermeta 
							WHERE meta_key = '_IDGL_elem_terms_approval'
						 ) AS Terms
						 ON u.ID = Terms.user_id 
						 WHERE Meta.meta_value IS NOT NULL";
		}
		else
		{
			$q = "SELECT u.ID,  
						 u.user_login AS Login_Name, 
						 u.user_nicename AS Nicename, 
						 u.user_email AS Email, 
						 u.display_name AS Display, 
						 FName.meta_value as 'First_Name', 
						 LName.meta_value AS 'Last_Name', 
						 School.meta_value AS 'School',
						 Terms.meta_value AS 'Terms_Approval',
						 u.user_registered AS 'Register_Date'
						 FROM $wpdb->users AS u
						 LEFT JOIN ( 
							SELECT user_id, meta_value
							FROM $wpdb->usermeta 
							WHERE meta_key = '_IDGL_elem_user_type'
							AND meta_value = 'student' 
							GROUP BY user_id
						 ) AS Meta 
						 ON u.ID = Meta.user_id
						 JOIN (
							 SELECT user_id, meta_key, meta_value  
							 FROM $wpdb->usermeta 
							 WHERE meta_key = 'first_name' AND meta_value <> ''
						 ) AS FName
						 ON u.ID = FName.user_id
						 JOIN (
							SELECT user_id, meta_key, meta_value  
							FROM $wpdb->usermeta 
							WHERE meta_key = 'last_name' AND meta_value <> ''
						 ) AS LName
						 ON u.ID = LName.user_id 
						 LEFT OUTER JOIN (
							SELECT user_id, meta_key, meta_value  
							FROM $wpdb->usermeta 
							WHERE meta_key = '_IDGL_elem_school'
						 ) AS School
						 ON u.ID = School.user_id
						 LEFT OUTER JOIN (
							SELECT user_id, meta_key, meta_value  
							FROM $wpdb->usermeta 
							WHERE meta_key = '_IDGL_elem_terms_approval'
						 ) AS Terms
						 ON u.ID = Terms.user_id
						 WHERE Meta.meta_value IS NOT NULL";
		}
		
		$return_url = $_SERVER[REQUEST_URI];
		$delete_page = parse_url($return_url, PHP_URL_PATH)."?page=delete-user&id={ID}&rurl={$return_url}";
		
		$dg_params = array("edit" => get_bloginfo("url") . "/wp-admin/user-edit.php?user_id={ID}", "delete" => "{$delete_page}");
		$dg = new IDGL_DataGrid($dg_params);
		$dg -> addSort($sort);
		$dg -> addQueryString();
		
		if(isset($search))
		{
			$addon = "";
			switch($search)
			{
				case "email":
					$addon = "AND u.user_email LIKE '%{$_POST['s_value']}%'";
					break;
				case "name":
					$addon = "AND u.user_nicename LIKE '%{$_POST['s_value']}%'";
					break;
			}
			$q = "{$q} {$addon}";
		}
	
		if(isset($sort))
		{
			$sortType = "";
			if(strpos($sort, "_DESC") !== false)
			{
				$sort = explode("_", $sort);
				$sort[1] = ( $sort[1] == "DESC" ) ? "" : "_".$sort[1];
				$sort = $sort[0].$sort[1];
				$sortType = "DESC";
			}
			$q = "{$q} ORDER BY {$sort} {$sortType}";
		}

		$dg -> setQuery($q);
		echo $dg -> render();
	?>
</div>

<style>
	table#IDGL_table tr th:first-child { width: 30px; }
	table#IDGL_table tr th:last-child { width: 110px; }
	#id { width: 30px; }
	#login_name, #email { width: 180px; }
</style>