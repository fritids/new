<?php get_header('home'); ?>

<?php
//Get students if the current logged in user is a teacher
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$metadata = get_user_meta($user_id,_IDGL_elem_user_type,true);

if ( $metadata == 'teacher' ){
    $q="SELECT 
            wp_users.ID,
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
    $students = $wpdb->get_results($q);
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
                    <div class="statistics-wrap" >
                        <div style="padding:5px;" class="clearfix">
                            <table id="students_table" style="width: 100%; line-height: 25px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>first name</th>
                                        <th>last name</th>
                                        <th>user email</th>
                                        <th>user login</th>
                                        <th>SAT & ACT</th>
                                        <th>SAT ONLY</th>
                                        <th>ACT ONLY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $cont = 1; foreach($students as $student): ?>
                                        <tr>
                                            <td><?php echo $cont++; ?></td>
                                            <td><?php echo $student->first_name; ?></td>
                                            <td><?php echo $student->last_name; ?></td>
                                            <td><?php echo $student->user_email; ?></td>
                                            <td><?php echo $student->user_login; ?></td>
                                            <td>
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/student-progress/?std=<?php echo $student->ID; ?>&t=satact">
                                                    <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT and ACT"),$student->ID); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/student-progress/?std=<?php echo $student->ID; ?>&t=sat">
                                                    <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT Only"),$student->ID); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/student-progress/?std=<?php echo $student->ID; ?>&t=act">
                                                    <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("ACT Only"),$student->ID); ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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
        
        
        //STUDENTS TABLE
            if($('#students_table').length>0){
                $('#students_table').dataTable({
                    "bJQueryUI": true,                    
                    "sPaginationType": "full_numbers",
                    "iDisplayLength": 10,
                    "oLanguage": {
                        "oPaginate": {
                            "sFirst": "First",
                            "sLast": "Last",
                            "sNext": "Next",
                            "sPrevious": "Previous"
                        },
                        "sLengthMenu": "Show _MENU_ students",
                        "sZeroRecords": " ",
                        "sInfo": "_START_ to _END_ from _TOTAL_",
                        "sInfoEmpty": " ",
                        "sInfoFiltered": " ",
                        "sSearch": "Search:",
                        "sProcessing": "Searching..."
                    },
                    "sDom" : '<T>t<p>',
                    "oTableTools": {
                        "aButtons": []
                    }
                });
            }
     });
</script>

<?php
class Wp_Progress {
	
	public static function countDrills($menu_items, $user = '') {
        global $current_user;
        
		$drillCount = 0;
		$userCount = 0;
		foreach($menu_items as $item){
			if($item->object == "drill"){
				// Add to drill count
				$drillCount++;
				if($user === '')
                    $meta = get_user_meta($current_user->ID, "selftest_".$item->object_id, false);
                else
                    $meta = get_user_meta($user, "selftest_".$item->object_id, false);
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