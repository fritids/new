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

// Se if user has accepted the terms and conditions
$terms_approval = get_user_meta($current_user->ID, "_IDGL_elem_terms_approval", true);
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
                        <div style="padding:5px; text-align: center;" class="clearfix">
                            <table id="students_table" style="width: 100%; line-height: 25px;">
                                <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>E-MAIL</th>
                                        <th>USER LOGIN</th>
                                        <th>SAT & ACT</th>
                                        <th>SAT ONLY</th>
                                        <th>ACT ONLY</th>
                                        <th>SAT Tests</th>
                                        <th>ACT Tests</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($students as $student): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/student-dashboard/?std=<?php echo $student->ID; ?>">
                                                    <?php echo trim($student->last_name).' '.trim($student->first_name); ?>
                                                </a>
                                            </td>
                                            <td><?php echo $student->user_email; ?></td>
                                            <td><?php echo $student->user_login; ?></td>
                                            <td>
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/student-progress/?std=<?php echo $student->ID; ?>&t=satact">
                                                    <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT and ACT"),$student->ID).'%'; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/student-progress/?std=<?php echo $student->ID; ?>&t=sat">
                                                    <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("SAT Only"),$student->ID).'%'; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/student-progress/?std=<?php echo $student->ID; ?>&t=act">
                                                    <?php echo Wp_Progress::countDrills(wp_get_nav_menu_items("ACT Only"),$student->ID).'%'; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php 
                                                    $query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_USER_NOTES." notes
                                                            JOIN ".ECP_MCT_TABLE_TESTS." `tests` ON `notes`.`test_id` = `tests`.`id` AND `tests`.`type` = 'SAT'
                                                            WHERE `notes`.`user_id` = '%d'";
                                                    $sat_user = $wpdb->get_row($wpdb->prepare($query, $student->ID));
                                                    echo $sat_user->count;
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $query = "SELECT count(*) as count FROM ".ECP_MCT_TABLE_USER_NOTES." notes
                                                            JOIN ".ECP_MCT_TABLE_TESTS." `tests` ON `notes`.`test_id` = `tests`.`id` AND `tests`.`type` = 'ACT'
                                                            WHERE `notes`.`user_id` = '%d'";
                                                    $act_user = $wpdb->get_row($wpdb->prepare($query, $student->ID));
                                                    echo $act_user->count;
                                                ?>
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
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-block">
                <div class="widget-head">
                    <h5><i class="black-icons books"></i>STUDENTS TESTS REPORT</h5>
                </div>
                <div class="widget-content">
                    <div class="statistics-wrap">
                        <div style="padding:5px; " class="clearfix">
                            <?php 
                                $before = '<span class="sidenav-icon"></span>';
                            ?>
                            <ul class="side-nav accordion_mnu collapsible">
                                <li>
                                    <a class="main-category"><span class="white-icons books"></span>SAT & ACT</a>
                                    <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT and ACT"),$user_id);echo $wpm->toReport(); ?>
                                </li>
                                <li>
                                    <a class="main-category"><span class="white-icons books"></span>SAT Only</a>
                                    <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT Only"),$user_id);echo $wpm->toReport(); ?>
                                </li>
                                <li>
                                    <a class="main-category"><span class="white-icons books"></span>ACT Only</a>
                                    <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("ACT Only"),$user_id);echo $wpm->toReport(); ?>
                                </li>
                            </ul>
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
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/online-sat-progress/" class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">SAT & ACT</font> Material</div>
						<span id="p1"></span>
					</div>
				</a>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/online-sat-progress/" class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">SAT Only</font> Material</div>
						<span id="p2"></span>
					</div>
				</a>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>dashboard/admin/online-sat-progress/" class="statistics-wrap">
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
				<a href="<?php echo get_permalink($page->ID) ?>">
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
				<a href="<?php echo get_permalink($page->ID) ?>">
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

<a id="terms-popup-link" href="#terms-popup" style="display:none">Display Terms and Conditions</a>

<div style="display:none">
	<div id="terms-popup">
		<?php
		 $terms = get_page_by_path("end-user-terms-and-conditions");
		 
		 if($terms):
		?>
		<div class="popup-content" id="terms-content">
			<h2><?php echo $terms->post_title; ?></h2>
			<?php echo $terms->post_content; ?>
		</div>
		<div class="terms-buttons">
			<div class="terms-buttons-checkbox">
				<input type="checkbox" id="check-terms" />
				I accept the <a href ="#" id="check-terms-link">END USER TERMS AND CONDITIONS</a>
			</div>
			<a href="#" id="accept-terms" class="disabled">Go</a>
		</div>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("a#terms-popup-link").fancybox({
			modal: true, centerOnScroll: true
		});
		<?php if(! $terms_approval): ?>
		jQuery('a#terms-popup-link').trigger('click');
		jQuery("a#check-terms-link").click(function(e) {
			e.preventDefault();
			jQuery("#terms-content").show();
		});
		jQuery("#check-terms").click(function() {
			if(jQuery(this).is(':checked')) {
				jQuery("#accept-terms").removeClass("disabled");
			} else {
				jQuery("#accept-terms").addClass("disabled");
			}
		});
		jQuery('a#accept-terms').click(function(e) {
			e.preventDefault();
			if(jQuery("#check-terms").is(':checked')) {
				jQuery.ajax({
					type: "POST",
					dataType: "json",
					url: "<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/accept_terms_and_conditions.php",
					success: function(data){
						if(data)
							parent.jQuery.fancybox.close();
						else
							alert("An error occurred. Please try again later.");
					}
				});
			}
		});
		<?php endif; ?>
		
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