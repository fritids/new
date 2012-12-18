<?php get_header('home'); ?>

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
						<a href="#" id="p1"></a>
					</div>
				</div>
				<div class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">SAT Only</font> Material</div>
						<a href="#" id="p2"></a>
					</div>
				</div>
				<div class="statistics-wrap">
					<div class="statistics-block">
						<div class="stat-info">My <font color="#f0825b">ACT Only</font> Material</div>
						<a href="#" id="p3"></a>
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
			<div class="widget-content">
				<div class="statistics-wrap">
					<div class="statistics-block test-block">
						<div class="stat-img sat">SAT Text Book</div>
						<div class="stat-info">
							<div><font color="#f0825b">X</font> of <font color="#f0825b">10</font></div>
							<div>Tests complete</div>
						</div>
					</div>
				</div>
				<div class="statistics-wrap">
					<div class="statistics-block test-block">
						<div class="stat-img act">ACT Text Book</div>
						<div class="stat-info">
							<div><font color="#f0825b">X</font> of <font color="#f0825b">5</font></div>
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