<?php get_header('home'); ?>

<?php
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
			limit: 70
		};
			  
		options2 = {
			img1: '<?php echo get_template_directory_uri(); ?>/images/c1.png',
			img2: '<?php echo get_template_directory_uri(); ?>/images/c3.png',
			speed: 20,
			limit: 100
		};

		options3 = {
			img1: '<?php echo get_template_directory_uri(); ?>/images/c1.png',
			img2: '<?php echo get_template_directory_uri(); ?>/images/c3.png',
			speed: 20,
			limit: 25
		};

	    jQuery('#p1').cprogress(options1);
		jQuery('#p2').cprogress(options2);
		jQuery('#p3').cprogress(options3);
     });
</script>

<?php get_footer(); ?>