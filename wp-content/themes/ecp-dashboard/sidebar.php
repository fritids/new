<?php $before = '<span class="sidenav-icon"></span>'; ?>

<div id="sidebar" class="right-sidebar">
	<ul class="side-nav accordion_mnu collapsible">
		<li><a href="#"><span class="white-icons books"></span> SAT & ACT</a>
			<ul class="acitem">
				<?php wp_nav_menu(array('menu_class'=>'menu', 'container'=>'', 'items_wrap'=>'%3$s', 'link_before'=>$before, 'menu'=>'SAT and ACT')); ?>
			</ul>
		</li>
		<li><a href="#"><span class="white-icons books"></span> SAT Only</a>
			<ul class="acitem">
				<?php wp_nav_menu(array('menu_class'=>'menu', 'container'=>'', 'items_wrap'=>'%3$s', 'link_before'=>$before, 'menu'=>'SAT Only')); ?>
			</ul>
		</li>
		<li><a href="#"><span class="white-icons books"></span> ACT Only</a>
			<ul class="acitem">
				<?php wp_nav_menu(array('menu_class'=>'menu', 'container'=>'', 'items_wrap'=>'%3$s', 'link_before'=>$before, 'menu'=>'ACT Only')); ?>
			</ul>
		</li>
	</ul>
	
	<div class="new-update">
		<h2><i class="icon-list-alt"></i> News/Updates</h2>
		<?php
		switch_to_blog(4);
		$args = array( 'numberposts' => '2', 'orderby' => 'post_date', 'order' => 'DESC', 'post_type' => 'post' );
		$recent_posts = get_posts( $args );
		foreach( $recent_posts as $recent ):
			setup_postdata($recent);
		?>
		<div class="side-news">
			<h5><a href="<?php echo wp_get_shortlink($recent->ID) ?>"><?php echo $recent->post_title; ?></a></h5>
			<p><?php the_excerpt(); ?></p>
		</div>
		<?php
		endforeach;
		restore_current_blog();
		?>
	</div>
</div>