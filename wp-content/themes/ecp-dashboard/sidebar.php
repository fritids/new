<div id="sidebar" class="right-sidebar">
	<ul class="side-nav accordion_mnu collapsible">
		<li><a href="#"><span class="white-icons pencil"></span> SAT & ACT</a>
			<ul class="acitem">
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Course Intro</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Sentence Completions</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Reading Comprehension</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Advanced Reading</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Overall Test</a></li>
			</ul>
		</li>
		<li><a href="#"><span class="white-icons pencil"></span> SAT Only</a>
			<ul class="acitem">
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Course Intro</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Math 1</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Math 2</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Math 3</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Math 4</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Overall Test</a></li>
			</ul>
		</li>
		<li><a href="#"><span class="white-icons pencil"></span> ACT Only</a>
			<ul class="acitem">
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Course Intro</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Writing 1</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Writing 2</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Writing 3</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Writing 4</a></li>
				<li><a href="#"><span class="sidenav-icon"><span class="sidenav-link-color"></span></span>Overall Test</a></li>
			</ul>
		</li>
	</ul>
	
	<div class="new-update">
		<h2><i class="icon-list-alt"></i> News/Updates</h2>
		<?php
		switch_to_blog(4);
		$args = array( 'numberposts' => '2', 'orderby' => 'post_date', 'order' => 'DESC' );
		$recent_posts = get_posts( $args );
		foreach( $recent_posts as $post ):
			setup_postdata($post);
		?>
		<div class="side-news">
			<h5><?php the_shortlink(get_the_title()) ?></h5>
			<p><?php the_excerpt(); ?></p>
		</div>
		<?php
		endforeach;
		restore_current_blog();
		?>
	</div>
</div>