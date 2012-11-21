<?php
if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}
enque_progress_table_styles();
get_header(); 
include dirname(__FILE__)."/../menues/menu_student.php";

?>
<div class="whitebox">
<script type="text/javascript">
	var openedList;
	jQuery(function(){
		jQuery("#onlineSATprogress li ul").hide();
		jQuery("#onlineSATprogress li").not("table a").click(function(e){
			if(jQuery(e.target).hasClass("review")){ return true; }
			//if(!jQuery(this).hasClass("review")){
				e.preventDefault();
				e.stopPropagation();
	
					if(jQuery(this).find("a:first").hasClass("expanded")){
						jQuery(this).find("a:first").removeClass("expanded");
						var toOpen=jQuery(this).find("ul:first");
						toOpen.addClass("opened").slideUp();
					}else{
	
						jQuery(this).parent().find("a.expanded").trigger("click")
	
						jQuery(this).find("a:first").addClass("expanded");
	
						if(jQuery(this).parents(".opened").length==0){
							if(openedList!=null){
								openedList.slideUp();
							}
							var toOpen=openedList=jQuery(this).find("ul:first");
							toOpen.addClass("opened").slideDown();
						}else{
							var toOpen=jQuery(this).find("ul:first");
							toOpen.addClass("opened").slideDown();
						}
					}
			//}
		})
		//jQuery("#onlineSATprogress li .review").unbind("click");
	})
</script>
<div class="table-holder" id="onlineSATprogress">
<?php 
	
	global $wp_query;
	$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);

	?>
		<ul>
        	<li>
	            <a class='tableslider levelone collapsed'>Reading</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("Reading"),$user_id);echo $wpm->toString(); ?>
           	</li>
           	<li>
	            <a class='tableslider levelone collapsed'>Math</a>
	            <ul>
        			<li>
        				<a class='tableslider levelone collapsed'>Arithmetic</a>
		            	<?php $wpm=new Wp_Menu(wp_get_nav_menu_items("Math - Arithmetic"),$user_id);echo $wpm->toString(); ?>
		            </li>
		            <li>
        				<a class='tableslider levelone collapsed'>Algebra</a>
		            	<?php $wpm=new Wp_Menu(wp_get_nav_menu_items("Math - Algebra"),$user_id);echo $wpm->toString(); ?>
		            </li>
		            <li>
        				<a class='tableslider levelone collapsed'>Geometry</a>
		            	<?php $wpm=new Wp_Menu(wp_get_nav_menu_items("Math - Geometry"),$user_id);echo $wpm->toString(); ?>
		            </li>
		            <li>
        				<a class='tableslider levelone collapsed'>Advanced Topics</a>
		            	<?php $wpm=new Wp_Menu(wp_get_nav_menu_items("Math - Advanced Topics"),$user_id);echo $wpm->toString(); ?>
		            </li>
		        </ul>
           	</li>
           	<li>
	            <a class='tableslider levelone collapsed'>Writing</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("Writing"),$user_id);echo $wpm->toString(); ?>
           	</li>
          </ul>

</div>
</div>	
<div class="clear"></div>
<?php get_footer(); ?>