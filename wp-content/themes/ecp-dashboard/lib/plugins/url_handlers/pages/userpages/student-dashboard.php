<?php get_header('home'); ?>
<?php

global $wpdb;

if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}else{
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $metadata = get_user_meta($user_id,_IDGL_elem_user_type,true);
    if ( $metadata != 'teacher' ){
        wp_redirect(get_bloginfo("url"));
        die();
    }
}

$student_id = $_GET['std'];

if( ! $student_id){
    wp_redirect(get_bloginfo("url"));
    die();
}

$student = new WP_User($student_id);
if ( ! $student->exists() OR $student->ID === 0 ){
    wp_redirect(get_bloginfo("url"));
    die();
}

$user_id = $student->ID;

// Get created tests count
$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'SAT'";
$sat_count = $wpdb->get_row($query);

$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_TESTS." WHERE `type` = 'ACT'";
$act_count = $wpdb->get_row($wpdb->prepare($query));

// Get users tests count
$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_USER_NOTES." notes
		  JOIN ".ECP_MCT_TABLE_TESTS." `tests` ON `notes`.`test_id` = `tests`.`id` AND `tests`.`type` = 'SAT'
		  WHERE `notes`.`user_id` = '%d'";
$sat_user = $wpdb->get_row($wpdb->prepare($query, $user_id));

$query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_USER_NOTES." notes
		  JOIN ".ECP_MCT_TABLE_TESTS." `tests` ON `notes`.`test_id` = `tests`.`id` AND `tests`.`type` = 'ACT'
		  WHERE `notes`.`user_id` = '%d'";
$act_user = $wpdb->get_row($wpdb->prepare($query, $user_id));

?>

<h5>Student: <i><?php echo $student->get('first_name').' '.$student->get('last_name'); ?></i></h5>

<div class="row-fluid">
	<div class="span6">
		<div class="widget-block">
			<div class="widget-head">
				<h5><i class="black-icons books"></i>Course Progress</h5>
			</div>
			<div class="widget-content">
				<a class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">SAT & ACT</font> Material</div>
						<span id="p1"></span>
					</div>
				</a>
				<a class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">SAT Only</font> Material</div>
						<span id="p2"></span>
					</div>
				</a>
				<a class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">ACT Only</font> Material</div>
						<span id="p3"></span>
					</div>
				</a>
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
				<a>
					<div class="statistics-wrap">
						<div class="statistics-block test-block">
							<div class="stat-img sat">SAT Text Book</div>
							<div class="stat-info">
								<div><font color="#f0825b"><?php echo $sat_user->count; ?></font> of <font color="#f0825b"><?php echo $sat_count->count; ?></font></div>
								<div>Tests complete</div>
							</div>
						</div>
					</div>
				</a>
				<a>
					<div class="statistics-wrap">
						<div class="statistics-block test-block">
							<div class="stat-img act">ACT Text Book</div>
							<div class="stat-info">
								<div><font color="#f0825b"><?php echo $act_user->count; ?></font> of <font color="#f0825b"><?php echo $act_count->count; ?></font></div>
								<div>Tests complete</div>
							</div>
						</div>
					</div>
				</a>
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
			limit: <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT and ACT"),$student_id); ?>
		};
			  
		options2 = {
			img1: '<?php echo get_template_directory_uri(); ?>/images/c1.png',
			img2: '<?php echo get_template_directory_uri(); ?>/images/c3.png',
			speed: 20,
			limit: <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT Only"),$student_id); ?>
		};

		options3 = {
			img1: '<?php echo get_template_directory_uri(); ?>/images/c1.png',
			img2: '<?php echo get_template_directory_uri(); ?>/images/c3.png',
			speed: 20,
			limit: <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("ACT Only"),$student_id); ?>
		};

	    jQuery('#p1').cprogress(options1);
		jQuery('#p2').cprogress(options2);
		jQuery('#p3').cprogress(options3);
     });
</script>

<?php
class Wp_Progress {
	
	public static function countDrills($menu_items, $student_id) {
        
		$drillCount = 0;
		$userCount = 0;
		foreach($menu_items as $item){
			if($item->object == "drill"){
				// Add to drill count
				$drillCount++;
                $meta = get_user_meta($student_id, "selftest_".$item->object_id, false);
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