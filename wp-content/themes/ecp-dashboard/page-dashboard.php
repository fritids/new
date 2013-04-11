<?php get_header('home'); ?>

<?php
global $wp_query;
//Get students if the current logged in user is a teacher
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$metadata = get_user_meta($user_id,_IDGL_elem_user_type,true);

if ( $metadata == 'teacher' ){
    $q="SELECT 
            first_name.meta_value AS first_name,
            last_name.meta_value AS last_name,
            user_email, 
            user_login 
        FROM wp_users
        JOIN wp_teacherstudent ON wp_teacherstudent.student_ID = wp_users.ID
        JOIN wp_usermeta AS _IDGL_elem_user_type ON _IDGL_elem_user_type.user_id = wp_users.ID AND _IDGL_elem_user_type.meta_key = '_IDGL_elem_user_type'
        LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = wp_users.ID AND first_name.meta_key = 'first_name'
        LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = wp_users.ID AND last_name.meta_key = 'last_name'
        WHERE wp_teacherstudent.teacher_ID = $user_id
        ORDER BY first_name";
}
// Get created tests count
$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'SAT'";
$sat_count = $wpdb->get_row($query);

$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'ACT'";
$act_count = $wpdb->get_row($wpdb->prepare($query));

// Get users tests count
$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_USER_NOTES." notes
		  JOIN ".ECP_MCT_TABLE_TESTS." `tests` ON `notes`.`test_id` = `tests`.`id` AND `tests`.`type` = 'SAT'
		  WHERE `notes`.`user_id` = '%d'";
$sat_user = $wpdb->get_row($wpdb->prepare($query, $current_user->ID));

$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_USER_NOTES." notes
		  JOIN ".ECP_MCT_TABLE_TESTS." `tests` ON `notes`.`test_id` = `tests`.`id` AND `tests`.`type` = 'ACT'
		  WHERE `notes`.`user_id` = '%d'";
$act_user = $wpdb->get_row($wpdb->prepare($query, $current_user->ID));
?>
<?php if ( $metadata == 'teacher' ): ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-block">
                <div class="widget-head">
                    <h5><i class="black-icons books"></i>Your Students</h5>
                </div>
                <div class="widget-content">
                    <div class="wrap" style="margin-left: 50px;margin-right: 50px;">
                        <?php 
                            $dg=new IDGL_DataGrid();
                            $dg->setQuery($q);
                            echo $dg->render();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="row-fluid">
	<div class="span6">
		<div class="widget-block">
			<div class="widget-head">
				<h5><i class="black-icons books"></i>Course Progress</h5>
			</div>
			<div class="widget-content">
				<div class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">SAT & ACT</font> Material</div>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/online-sat-progress/" id="p1"></a>
					</div>
				</div>
				<div class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">SAT Only</font> Material</div>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/online-sat-progress/" id="p2"></a>
					</div>
				</div>
				<div class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">ACT Only</font> Material</div>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/online-sat-progress/" id="p3"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class=" widget-block">
			<div class="widget-head">
				<h5><i class="black-icons books"></i>Practice Test Progress</h5>
			</div>
			<?php $page = get_page_by_title("Practice SAT and ACT Exams"); ?>
			<div class="widget-content">
				<div class="statistics-wrap">
					<div class="statistics-block test-block">
						<div class="stat-img sat">SAT Text Book</div>
						<div class="stat-info">
							<div><a href="<?php echo get_permalink($page->ID) ?>"><font color="#f0825b"><?php echo $sat_user->count; ?></font></a> of <a href="<?php echo get_permalink($page->ID) ?>"><font color="#f0825b"><?php echo $sat_count->count; ?></font></a></div>
							<div>Tests complete</div>
						</div>
					</div>
				</div>
				<div class="statistics-wrap">
					<div class="statistics-block test-block">
						<div class="stat-img act">ACT Text Book</div>
						<div class="stat-info">
							<div><a href="<?php echo get_permalink($page->ID) ?>"><font color="#f0825b"><?php echo $act_user->count; ?></font></a> of <a href="<?php echo get_permalink($page->ID) ?>"><font color="#f0825b"><?php echo $act_count->count; ?></font></a></div>
							<div>Tests complete</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
     jQuery(document).ready(function(){

	    options1 = {
			img1: '<?php echo get_template_directory_uri(); ?>/images/c1.png',
			img2: '<?php echo get_template_directory_uri(); ?>/images/c3.png',
			speed: 20,
			limit: <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT and ACT")); ?>
		};
			  
		options2 = {
			img1: '<?php echo get_template_directory_uri(); ?>/images/c1.png',
			img2: '<?php echo get_template_directory_uri(); ?>/images/c3.png',
			speed: 20,
			limit: <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT Only")); ?>
		};

		options3 = {
			img1: '<?php echo get_template_directory_uri(); ?>/images/c1.png',
			img2: '<?php echo get_template_directory_uri(); ?>/images/c3.png',
			speed: 20,
			limit: <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("ACT Only")); ?>
		};

	    jQuery('#p1').cprogress(options1);
		jQuery('#p2').cprogress(options2);
		jQuery('#p3').cprogress(options3);
     });
</script>

<?php
class Wp_Progress {
	
	public static function countDrills($menu_items) {
		global $current_user;
		$drillCount = 0;
		$userCount = 0;
		foreach($menu_items as $item){
			if($item->object == "drill"){
				// Add to drill count
				$drillCount++;
				
				$meta = get_user_meta($current_user->ID, "selftest_".$item->object_id, false);
				if(count($meta)) {
					$userCount++;
				}
			}
		}
		
		if($drillCount)
			return round($userCount*100/$drillCount);
		return 0;
	}
}
?>

<?php get_footer(); ?>