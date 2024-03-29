<?php $before = '<span class="sidenav-icon"></span>'; ?>

<div id="sidebar" class="right-sidebar">
	<ul class="side-nav accordion_mnu collapsible">
		<li><a href="#"><span class="white-icons books"></span> SAT & ACT</a>
			<?php wp_nav_menu(array('container'=>'', 'items_wrap'=>'<ul id="%1$s" class="%2$s">%3$s</ul>', 'link_before'=>$before, 'menu'=>'SAT and ACT')); ?>
		</li>
		<li><a href="#"><span class="white-icons books"></span> SAT Only</a>
			<?php wp_nav_menu(array('container'=>'', 'items_wrap'=>'<ul id="%1$s" class="%2$s">%3$s</ul>', 'link_before'=>$before, 'menu'=>'SAT Only')); ?>
		</li>
		<li><a href="#"><span class="white-icons books"></span> ACT Only</a>
			<?php wp_nav_menu(array('container'=>'', 'items_wrap'=>'<ul id="%1$s" class="%2$s">%3$s</ul>', 'link_before'=>$before, 'menu'=>'ACT Only')); ?>
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