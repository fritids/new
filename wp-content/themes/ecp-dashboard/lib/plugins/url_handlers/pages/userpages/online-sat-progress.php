<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
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
//	var openedList;
//	jQuery(function(){
//		jQuery("#onlineSATprogress li ul").hide();
//		jQuery("#onlineSATprogress li").not("table a").click(function(e){
//			if(jQuery(e.target).hasClass("review")){ return true; }
//			//if(!jQuery(this).hasClass("review")){
//				e.preventDefault();
//				e.stopPropagation();
//	
//					if(jQuery(this).find("a:first").hasClass("expanded")){
//						jQuery(this).find("a:first").removeClass("expanded");
//						var toOpen=jQuery(this).find("ul:first");
//						toOpen.addClass("opened").slideUp();
//					}else{
//	
//						jQuery(this).parent().find("a.expanded").trigger("click")
//	
//						jQuery(this).find("a:first").addClass("expanded");
//	
//						if(jQuery(this).parents(".opened").length==0){
//							if(openedList!=null){
//								openedList.slideUp();
//							}
//							var toOpen=openedList=jQuery(this).find("ul:first");
//							toOpen.addClass("opened").slideDown();
//						}else{
//							var toOpen=jQuery(this).find("ul:first");
//							toOpen.addClass("opened").slideDown();
//						}
//					}
//			//}
//		})
//		//jQuery("#onlineSATprogress li .review").unbind("click");
//	})
</script>
<div class="table-holder" id="onlineSATprogress">
<?php 
	
	global $wp_query;
	$user_id=ECPUser::getUserIDFromUname($wp_query->query_vars['uname']);

	?>
		<ul>
        	<li>
	            <a class='tableslider levelone collapsed'>SAT & ACT</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT & ACT"),$user_id);echo $wpm->toString(); ?>
           	</li>
           	<li>
	            <a class='tableslider levelone collapsed'>SAT Only</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT Only"),$user_id);echo $wpm->toString(); ?>
           	</li>
           	<li>
	            <a class='tableslider levelone collapsed'>ACT Only</a>
	            <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("ACT Only"),$user_id);echo $wpm->toString(); ?>
           	</li>
          </ul>

</div>
</div>	
<div class="clear"></div>
<?php get_footer(); ?>