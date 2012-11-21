<!-- Start TABS -->
<ul class="tabs">
	<!-- TO change tab heading, edit the three TabLink heading below -->    
    <li class="TabLink" id="tab0" onClick="ShowTab(0)"><a class="t">FEATURED</a></li>
    <li class="TabLink" id="tab1" onClick="ShowTab(1)"><a class="t">RECENT</a></li>
	<!-- If you add more tabs, add heading above and respective NavLinks below -->
    <li class="NavLinks" id="paging0"><div style="display:none"></div></li>
    <li class="NavLinks" id="paging1"><div style="display:none"></div></li>
</ul>
<div class="TabContent_holder">
	<!-- First Tab Code -->
   
    <!-- Second Tab Code -->
    <div class="TabContent" style="display: none" id="div0">
    	<ul>
			 <?php 
		        switch_to_blog(4);
		        $my_query = new WP_Query('showposts=10&order=ASC'); ?>
		        <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		          <?php the_title(); ?>
		          </a> </li>
		        <?php endwhile;
				restore_current_blog();
		     ?>
        </ul>
    </div>
	 <div class="TabContent" style="display: none" id="div1">
      <ul>
        <?php 
        switch_to_blog(4);
        $my_query = new WP_Query('showposts=10'); ?>
        <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <?php the_title(); ?>
          </a> </li>
        <?php endwhile;
		restore_current_blog();
        ?>
      </ul>
    </div>
    
    <!-- Third Tab Code -->        
</div>
<script type="text/javascript">ShowTab(0);</script>