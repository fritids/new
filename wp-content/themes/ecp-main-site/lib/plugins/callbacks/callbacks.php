<?php

register_taxonomy( 'faq-category', 'faq',
	array(
         'hierarchical' => true,
		 'label' => __('FAQ category'),
		 'query_var' => 'faq',
		 'rewrite' => array('slug' => 'faq' )
	)
);


function get_slider($name,$type,$w,$h,$showBull){
	 switch_to_blog(1);
	global $post;?>
	<div class="slider slider_<?php echo $name; ?>" id="slider_<?php echo $name; ?>"><ul class="slider<?php echo $name; ?>">
		<?php 
		$pids=getThemeMeta($name,"","_Settings");
		if($pids != ""){
		$count=0;
		foreach($pids as $post_id){ ?>
			<?php query_posts( array( 'p' => $post_id, "post_type"=>$type ));?>
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>				
			<li>
				<?php
					$postThumb=wp_get_post_image();
				?>
				<?php if($showBull){ ?>
				<h1><?php the_title(); ?></h1>
				<?php } ?>
				<?php if(hasPostMeta($post_id, 'slider_url')){  ?>
				<a href="<?php echo getPostMeta($post_id,'slider_url');  ?>">
				<?php } ?>
					<?php if($postThumb!=""){?>
						<img src="<?php bloginfo("template_directory"); ?>/_timthumb.php?src=<?php echo get_image_path($postThumb); ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>" width="<?php echo $w; ?>" height="<?php echo $h; ?>" alt="<?php the_title(); ?>" />
		            <?php }?>
		            	<div class="info-text-slider">
		            	<?php 
			            	$content = strip_tags(get_the_content(), "p"); 
							echo $content;
		            	?>
		            	<?php 
		            	if(hasPostMeta($post_id, 'name_input')){
		            	?>
			            	<span class="testimonials-name">
			            		<?php echo getPostMeta($post_id,'name_input');  ?>
			               	</span>
		            	<?php 
		            	} 
		          		?>
		          		<?php 
		            	if(hasPostMeta($post_id, 'name_atributes')){
		            	?>
			            	<span class="testimonisl-atribute">
			            		<?php echo getPostMeta($post_id,'name_atributes'); ?>
			            	</span>
		            	<?php 
		            	} 
		          		?>
		            </div>
		        <?php if(hasPostMeta($post_id, 'slider_url')){  ?>     
	            </a>
	            <?php } ?>      
			</li>
			<?php endwhile; ?>
			<?php endif; ?>
		<?php 
			$count++;
			} 
		}
		?>
		<?php wp_reset_query(); ?>
	</ul></div>
	<script type="text/javascript">
		<!--
		jQuery(function(){
			jQuery("#slider_<?php echo $name; ?>").easySlider({
				auto: true,
				numeric:true,
				controlsShow:true,
				speed: 300,
				controlsBefore:	'<div class="controls-wrapper controls-wrapper_<?php echo $name; ?>">',
				controlsAfter:	'<div class="clear"></div></div>',	
				continuous:true,
				showNumbers:false,
				numericId: 	'controls_<?php echo $name; ?>'
			});
		});
		//-->
	</script>
	<?php
	restore_current_blog();
}

function IDGL_postList($param,$name,$pagename=""){
	eval('$args='.$param.';');
	?>
	<style type="text/css">
		ul.sliderList li{
			-moz-border-radius: 5px;
			border-radius: 5px;
			background:#fafafa;
			padding:10px;
			list-style:decimal;
			list-style-position:inside;
		}
		ul.postList li{
			-moz-border-radius: 5px;
			border-radius: 5px;
			background:#fefefe;
			padding:10px;
			list-style:decimal;
			list-style-position:inside;
		}
	</style>
	<script type="text/javascript">
		jQuery(function(){
			init_pager_<?php echo $name; ?>()
		})
		function init_pager_<?php echo $name; ?>(){
			jQuery(".pager_<?php echo $name; ?> a.page-numbers").click(function(e){
				e.preventDefault();
				var page_no=jQuery(this).attr("id").split("_")[1];
				jQuery.ajax({
				   type: "POST",
				   url: ajaxurl+"?action=auzz_get_slider_post_list",
				   data: "page_no="+page_no+"&part=right&post_type=<?php echo $args["post_type"]; ?>&name=<?php echo $name; ?>",
				   success: function(msg){
				     //alert(msg );
					  jQuery("#right_container_<?php echo $name; ?>").html(msg);
					  init_pager_<?php echo $name; ?>()
				   }
				 });
			})
			jQuery(".right_<?php echo $name; ?> ul ._add").click(function(e){
				e.preventDefault();
				var id=jQuery(this).attr("id").split("_")[1];
				//var imagefld="<div class='IDGL_listNode'><div class='IDGL_image'><div class='imageWrap'><img class='elem' width='290' height='220' _scalex='290' _scaley='220' /><div><input type='hidden' class='hiddenFld' name='IDGL_elem_Settings[testimonialsSliderSlideImages][]' /><a class='button uploadify' href='#'>Browse Your PC</a></div></div></div></div>";
				var temp=jQuery(".left_<?php echo $name; ?> ul").append("<li><input type='hidden' name='IDGL_elem_Settings[<?php echo $name; ?>][]' value='"+id+"' />"+jQuery(this).parent().find("span").html()+"<a href='#' class='_remove button'>remove</a>")
				initUploadify(temp.find(".uploadify"));
				jQuery(".left_<?php echo $name; ?> ul ._remove:last").click(function(e){
					e.preventDefault();
					jQuery(jQuery(this).parents("li")[0]).remove();
				})
			});
			jQuery(".left_<?php echo $name; ?> ul").sortable();
			initUploadify(jQuery(".left_<?php echo $name; ?> ul .uploadify"));
			jQuery(".left_<?php echo $name; ?> ul ._remove").click(function(e){
				e.preventDefault();
				jQuery(jQuery(this).parents("li")[0]).remove();
			})
		}
	</script>
	<?php
	auzz_get_slider_post_list($param,$name,$pagename="");
	
}



add_action('wp_ajax_auzz_get_slider_post_list', 'auzz_get_slider_post_list' );
function auzz_get_slider_post_list($param="",$name="",$pagename=""){
	global $wpdb;
	if($param!=""){
		eval('$args='.$param.';');
		$type=$args["post_type"];

		
	}else{
		$type=$_POST["post_type"];

		$name=$_POST["name"];
	}
	$page_size=10;
	$current_page=0;
	if(isset($_POST["page_no"])){
		$current_page=(int)$_POST["page_no"];
	}else{
		echo "<table style='width:100%'><tr>";
	}

	if(!isset($_POST["part"]) && $_POST["part"]!="right"){
		echo "<td valign='top' width='50%'>";
		// left part
		$current=getThemeMeta($name,"","_Settings");
		$currentImages=getThemeMeta("testimonialsSliderSlideImages","","_Settings");
	    if($current==""){
	    	$current=array();
	    }
		
		$selected_querystr = "
		    SELECT wposts.* 
		    FROM $wpdb->posts wposts
		    WHERE wposts.post_status = 'publish' AND wposts.ID IN (".implode(",",$current).")
		    AND wposts.post_type = '$type' 
		    ORDER BY wposts.post_date DESC";

		$pageposts_temp = $wpdb->get_results($selected_querystr, OBJECT);
		$pageposts=array();
		foreach($pageposts_temp as $post){
		 	$pageposts[$post->ID]=$post;
		}
		echo "<div class='left_$name tablenav' id='left_container' style='height:auto;'>";
		echo "<ul class='sliderList'>";
		$count=0;
	    foreach($current as $post_index){
	    	$post=$pageposts[$post_index];
	    	
			//$img="<div class='IDGL_listNode'><div class='IDGL_image'><div class='imageWrap'><img class='elem' src='".$currentImages[$count]."' width='290' height='220' _scalex='290' _scaley='220' /><div><input type='hidden' value='".$currentImages[$count]."' class='hiddenFld' name='IDGL_elem_Settings[testimonialsSliderSlideImages][]' /><a class='button uploadify' href='#'>Browse Your PC</a></div></div></div></div>";
			
			echo "<li><input type='hidden' name='IDGL_elem_Settings[$name][]' value='$post->ID' />".$post->post_title."<a href='#' class='_remove button'>remove</a></li>";
			$count++;
	    }
	    echo "</ul>";
	    echo "<div style='clear:both;'></div>";
		echo "</div>";
		echo "</td>";
		// left part end
	}
	
	if(!isset($_POST["page_no"])){
		echo "<td valign='top' style='padding-left:20px;' width='50%'>";
	}
	
	$count_querystr = "
	    SELECT COUNT(wposts.ID) 
	    FROM $wpdb->posts wposts
	    WHERE wposts.post_status = 'publish' 
	    AND wposts.post_type = '$type' 
	    ORDER BY wposts.post_date DESC";
	
	$post_count = $wpdb->get_var($wpdb->prepare($count_querystr));
	$post_count=min($post_count,150);

	
	echo "<div class='right_$name tablenav' id='right_container_$name' style='height:auto;'>";
	echo '<div class="tablenav-pages pager pager_'.$name.'" style="clear:both;float:none;"><span class="displaying-num">Displaying '.($current_page*$page_size+1).'&#8211;'.($current_page*$page_size+$page_size).' of '.$post_count.'</span>';
	
	//<a class='next page-numbers' href='/city.com/nyc/wp-admin/edit.php?paged=2'>&raquo;</a></div> ';
	//echo "<ul class='pager'>";
	for($i=0;$i<ceil($post_count/$page_size);$i++){
		echo "<a class='page-numbers' href='#' id='page_{$i}'>".($i+1)."</a>";
		//echo "<li><a href='#' id='page_{$i}'>".($i+1)."</a></li>";
	}
	//echo "</ul>";
	 echo "<div style='clear:both;'></div>";
    echo "</div>";
	
	$querystr = "
	    SELECT wposts.* 
	    FROM $wpdb->posts wposts
	    WHERE wposts.post_status = 'publish' 
	    AND wposts.post_type = '$type' 
	    ORDER BY wposts.post_date DESC LIMIT ".($current_page*$page_size).", ".($page_size);
	
	//echo $querystr;
	
    $pageposts = $wpdb->get_results($querystr, OBJECT);
    
    echo "<ul class='postList'>";
    foreach($pageposts as $post){
    	echo "<li> <a href='#' id='id_".$post->ID."' class='_add button'>add</a><span>".$post->post_title."</span></li>";
    }
    echo "</ul>";
    echo "</div>";
   
	if(isset($_POST["page_no"])){
		 
		die();
	}else{
		echo "</td></tr></table>";
	}
}



function IDGL_BgImagesList($param,$name,$pagename=""){
	eval('$args="'.$param.'";');
	$bgArr=IDGL_File::getFileList(IDG_ROOT."/images/");
	$current=getThemeMeta($name,"",$pagename);
	echo "<ul class='imagesList'>";
	foreach($bgArr as $img){
		if(strpos($img,".png")!=-1 || strpos($img,".jpg")!=-1){
			$sel="";
			if($current==$img){
				$sel=" checked='checked' ";
			}
			echo "<li><label><img width='100px' src='".IDGL_THEME_URL.'/images/'.$img."' /> <input type='radio' ".$sel." name='IDGL_elem".$pagename."[".$name."]' value='".$img."' /> select</label></li>";
		}
	}
	echo "</ul>";
}
function print_datagrid(){
	$dg=new IDGL_DataGrid("wp_posts",array(
		"post_title"=>"Title",
		"post_status"=>"Status",
		"post_modified"=>"Date Modified",
		"guid"=>"ID"
	));
	echo $dg->render();
}
function randomPosts_idl(){
	global $post;
	$default_article_slider=getGlobalImage("default-article_slider");
	$output = "<ul>";
		$posts = get_posts('orderby=rand&numberposts=6'); 
		foreach($posts as $post) {
			$postThumb = catch_that_image();
		    $output .= '<li>';
				if($postThumb!=""){
					$output .='<a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.get_bloginfo("template_directory").'/_timthumb.php?src='.$postThumb.'&amp;w=150&amp;h=120" width="150" height="120" alt="'.get_the_title().'" /></a>';
				}else{
					$output .='<a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.$default_article_slider.'" width="150" height="120" alt="'.get_the_title().'" /></a>';
				}
				$output .='<a class="title-transparent" href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
			$output .='</li>';
		}
	$output .='</ul>';
	wp_reset_query();
	return $output;	
}

/******** promotional video ******/

function IDGL_promoVideo(){
	echo '<object id="video_player" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="635" height="358" id="guest" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="allowFullScreen" value="false" />
		<param name="movie" value="'.get_bloginfo('template_directory').'/images/smpClean_ecp.swf?filePath='.getThemeMeta("promotionalVideo","","_VideoControl").'&image='.getThemeMeta("videoStartImage","","_VideoControl").'&t=1" />
		<param name="quality" value="high" />
		<param name="wmode" value="transparent" />
		<embed name="video_player" src="'.get_bloginfo('template_directory').'/images/smpClean_ecp.swf?filePath='.getThemeMeta("promotionalVideo","","_VideoControl").'&image='.getThemeMeta("videoStartImage","","_VideoControl").'&t=1" quality="high" wmode="transparent"  width="635" height="358" name="guest" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
		</object>';
}

function IDGL_promoVideoList($param,$name,$pagename=""){
	global $wpdb;
	$selected_querystr = "
		    SELECT wposts.* 
		    FROM $wpdb->posts wposts
		    WHERE wposts.post_type = 'attachment' AND post_mime_type='video/x-flv' 
		    ORDER BY wposts.post_date DESC";

 	$pageposts = $wpdb->get_results($selected_querystr, OBJECT);
 	$current=getThemeMeta($name,"",$pagename);
 	echo "<select name='IDGL_elem".$pagename."[".$name."]'>";
 	foreach($pageposts as $single_post){
 		if($current==$single_post->guid){
 			echo "<option selected='selected' value='".$single_post->guid."' >".$single_post->post_title."</option>";
 		}else{
 			echo "<option value='".$single_post->guid."' >".$single_post->post_title."</option>";
 		}
 		
 	}
 	echo "</select>";
 	echo "<div style='clear:both;'></div><br/><div id='videoContainer'>";
 	IDGL_promoVideo();
 	echo "</div>";
}



//add_action( 'widgets_init', wdgt_init );

add_action('widgets_init',function(){
     register_widget('Login_Widget');
      register_widget('Enroll_Widget');
      register_widget('Improve_50_Widget');
      register_widget('Quick_enroll');
      
});

class Login_Widget extends WP_Widget {

	function Login_Widget() {
		parent::WP_Widget(false, $name = 'Login_Widget');
	}

	function widget( $args, $instance ) {

	?>
		<div class="widget student-login">
                    <h2>Student Login</h2>
                    <div>
						<form method="post" action="/portal/login/" id="loginform" name="loginform">
							<div class="login-node">
								<label>Username:</label>
								<input type="text" tabindex="10" size="20" value="<?php echo $post['user_login'] ?>" class="input" id="user_login" name="user_login">
							</div>
							<div class="login-node">
								<label>Password:</label>
								<input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" name="user_password"></label>
							</div>
							<div class="the_submit_holder">
							<input type="submit" value="LOGIN" class="the_submit" id="wp-submit" name="wp-submit" />
							<a href="/portal/lostpassword/" class="forgot_pass">Forgot your password</a>
							<div class="clear"></div>
							</div>
							
						</form>
					</div>
         </div>
	<?php
		
	}

	function update( $new_instance, $old_instance ) {
	}

	function form( $instance ) {
		
	}

}
class Enroll_Widget extends WP_Widget {

	function Enroll_Widget() {
		parent::WP_Widget(false, $name = 'Enroll_Widget');
	}

	function widget( $args, $instance ) {

	?>
		<div class="widget">
        	<a href="http://edgeincollegeprep.com/enroll/cart/" ><img style="width:292px; margin:auto;" src="<?php bloginfo("stylesheet_directory") ?>/images/enroll_button.png" /></a>
        </div>
	<?php
		
	}

	function update( $new_instance, $old_instance ) {
	}

	function form( $instance ) {
		
	}

}
class Improve_50_Widget extends WP_Widget {

	function Improve_50_Widget() {
		parent::WP_Widget(false, $name = 'Improve 50 points widget');
	}

	function widget( $args, $instance ) {

	?>
		<?php if ( current_user_can('manage_options') ) { ?>
		<div class="widget improve_50"  style="width:197px;margin:auto;">
        	<a href="http://edgeincollegeprep.com/enroll/cart/" >
        		<img src="<?php bloginfo("stylesheet_directory") ?>/images/improve_50_points.jpg" />
        	</a>
        </div>
        <?php } ?>
	<?php
		
	}

	function update( $new_instance, $old_instance ) {
	}

	function form( $instance ) {
		
	}

}
class Quick_enroll extends WP_Widget {

	function Quick_enroll() {
		parent::WP_Widget(false, $name = 'Quick Enroll Form');
	}

	function widget( $args, $instance ) {

	?>
		<div class="widget student-login trial-signup">
                    <div>
						<form method="post" action="http://edgeincollegeprep.com/enroll/tryout/">
							<div class="login-node">
								<label>First Name:</label>
								<input type="text" name="fname" id="fname" />
							</div>
							<div class="login-node">
								<label>Last Name:</label>
								<input type="text" name="lname" id="lname" />
							</div>
							<div class="login-node">
								<label>Email:</label>
								<input type="text" name="email" id="email" />
							</div>
							<div class="the_submit_holder">
							<input type="submit" value="" class="the_submit" />
							<div class="clear"></div>
							</div>
						</form>
					</div>
         </div>
	<?php
		
	}

	function update( $new_instance, $old_instance ) {
	}

	function form( $instance ) {
		
	}

}
?>