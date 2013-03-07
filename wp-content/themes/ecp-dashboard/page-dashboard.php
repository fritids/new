<?php get_header('home'); ?>

<?php
global $wp_query;

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
		<h2><?php echo $terms->post_title; ?></h2>
		<div class="popup-content">
			<?php echo $terms->post_content; ?>
			<div class="terms-buttons">
				<a href="#" id="accept-terms">I accept the terms and conditions</a>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("a#terms-popup-link").fancybox({
			modal: true
		});
		
		<?php if(! $terms_approval): ?>
		$('a#terms-popup-link').trigger('click');
		$('a#accept-terms').click(function(e) {
			e.preventDefault();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "<?php echo get_bloginfo('template_url'); ?>/lib/plugins/callbacks/ajax/accept_terms_and_conditions.php",
				success: function(data){
					if(data)
						parent.$.fancybox.close();
					else
						alert("An error occurred. Please try again later.");
				}
			});
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