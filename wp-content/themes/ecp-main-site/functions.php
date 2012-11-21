<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$themename = "BlogDesignStudio_Idioglossia";
$shortname = "Idioglossia";

/*
 * Include base classes
 */

require_once(TEMPLATEPATH.'/lib/init.php');

if($load_conf!="false"){
	IDGLoader::loadLibraries();
	IDGLoader::loadPlugins();
}

/*
 * Register scripts in Backend
 */

if(is_admin()){
Util::IDGL_addScripts('jquery-ui_js',IDGL_THEME_URL . '/lib/js/jquery-ui-1.7.2.custom.min.js');
Util::IDGL_addScripts('ajaxupload_js',IDGL_THEME_URL . '/lib/js/ajaxupload.js');
Util::IDGL_addScripts('idgl_colorpicker_js',IDGL_THEME_URL . '/lib/js/colorpicker/colorpicker.js');
Util::IDGL_addScripts('idgl_colorpicker_css',IDGL_THEME_URL.'/lib/js/colorpicker/css/colorpicker.css');
Util::IDGL_addScripts('jquery-ui_css',IDGL_THEME_URL.'/lib/css/ui-lightness/jquery-ui-1.7.2.custom.css');
Util::IDGL_addScripts('idgl_styles_css',IDGL_THEME_URL.'/lib/css/styles.css');
Util::IDGL_addScripts('google-maps_js', 'http://maps.google.com/maps/api/js?sensor=false');
}

// Register scripts on Front Page
Util::IDGL_addScripts('jquery-ui_js',IDGL_THEME_URL . '/lib/js/jquery-ui-1.7.2.custom.min.js');
Util::IDGL_addScripts('jquery-ui_css',IDGL_THEME_URL.'/lib/css/ui-lightness/jquery-ui-1.7.2.custom.css');
Util::IDGL_addScripts('main_js',IDGL_THEME_URL.'/lib/js/main.js');
Util::IDGL_addScripts('easySlider',IDGL_THEME_URL.'/js/easySlider1.7.js'); 
Util::IDGL_addScripts('mainMenu',IDGL_THEME_URL.'/js/mainMenu.js');

/*
 * Print admin head
 */
add_action('admin_head','IDGL_Admin_head');
function IDGL_Admin_head() {
	global $IDGL_model;
	$uplData=wp_upload_dir();
	echo "<script type='text/javascript'>
			var imagesPath='".IDGL_THEME_URL."/lib/images/';
			var adminAjax='".get_bloginfo('url')."/wp-admin/admin-ajax.php';
			var uploadPath='".$uplData["url"]."/';
			var selectedModel='".$IDGL_model."';
		</script>";
		if (function_exists('wp_tiny_mce')) wp_tiny_mce();
		wp_admin_css();
}

add_action('wp_head','IDGL_add_scripts_front');
function IDGL_add_scripts_front(){
	echo "<script type='text/javascript'>
			var imagesPath='".IDGL_THEME_URL."/lib/images/';
			var adminAjax='".get_bloginfo('url')."/wp-admin/admin-ajax.php';
		</script>";
}


add_action('save_post', 'IDGL_PostOptionManages::IDGL_savePostData');

add_action('admin_menu', 'IDGL_PostOptionManages::IDGL_addPostPanels');

if($load_conf!="false"){
	add_action('admin_menu', 'IDGL_ThemePages::IDGL_addPages');
	add_action('init', 'PostType::register');
	add_action( 'widgets_init', 'IDGL_WidgetAreas::register_widget_areas' );
}


add_action( 'widgets_init', 'IDGL_Widgetizer::widgetize' );
add_action('admin_init', 'IDGL_init' );

function IDGL_init(){
   	IDGL_ThemePages::IDGL_register_setting();
}

/*
 * Upload handler - used by the image uploader
 */
add_action('wp_ajax_imageUpload', 'imageUpload' );
function imageUpload(){
	$uplData=wp_upload_dir();
	$tempFile = $_FILES['Filedata']['tmp_name'];
	if(isset($_GET["newFileName"])){
		$targetFile =str_replace(" ","_",$uplData["path"]."/".$_GET["newFileName"]);
		$targetThumb =str_replace(" ","_",$uplData["path"]."/thumb_".$_GET["newFileName"]);
	}else{
		$targetFile =str_replace(" ","_",$uplData["path"]."/".$_FILES['Filedata']['name']);
		$targetThumb =str_replace(" ","_",$uplData["path"]."/thumb_".$_FILES['Filedata']['name']);
	}
	move_uploaded_file($tempFile,$targetFile);
	if(isset($_GET["w"])){
		$sizes=explode("|",$_GET["w"]);
		if($sizes[2]!=null){
			WPACImage::resizeTo($sizes[2],$sizes[3],$targetFile,$targetThumb);
		}
		WPACImage::resizeTo($sizes[0],$sizes[1],$targetFile);
	}
	die();
}

/* interfaceFunctions */
/*
 * hasPostMeta::Get a value defined in the in the "wp_postmeta" table
 * 		$pid - id of the post/page
 * 		$key - name of the key	
 * 		$default - default value to be returned if no result has been retrieved
 */
function getPostMeta($pid,$key,$default=""){
	$meta=get_post_meta($pid, "_IDGL_elem_".$key, true);
	if($meta!=""){
	if(is_array($meta)){
			return $meta;
		}else{
			return stripcslashes($meta);
		}
	}else{
		return $default;
	}
}
/*
 * hasPostMeta::Checks if a certain key / value exists in the "wp_postmeta" table
 * 		$pid - id of the post/page
 * 		$key - name of the key	
 */
function hasPostMeta($pid, $key){
	$meta=get_post_meta($pid, "_IDGL_elem_".$key, true);
	if($meta!=""){
		return true;
	}
}
/*
 * getThemeMeta::Get a value defined in the "wp_options" table
 * 		$key - name of the key
 * 		$default - default value to be returned if no result has been retrieved
 */
function getThemeMeta($key,$default="",$pageName=""){
	$options = get_option('IDGL_elem'.$pageName);
	if($options[$key]!=""){
		if(is_array($options[$key])){
			return $options[$key];
		}else{
			return stripcslashes($options[$key]);
		}
	}else{
		return $default;
	}
}

function getCommentMeta($cid,$key,$default=""){
	$meta=get_comment_meta($cid, "_IDGL_elem_".$key, true);
	if($meta!=""){
	if(is_array($meta)){
			return $meta;
		}else{
			return stripcslashes($meta);
		}
	}else{
		return $default;
	}
}
function hasCommentMeta($cid, $key){
	$meta=get_post_meta($cid, "_IDGL_elem_".$key, true);
	if($meta!=""){
		return true;
	}
}

$mapCount=0;

/*
 * Creates a map with a marker
 * 		$mapName - the javascript name of the map
 * 		$mapValue - "long,lat,zoom-value" triple
 * 		$w - width of <div> wrapper for the map
 * 		$h - height of <div> wrapper for the map
 */
function renderGoogleMap($mapName,$mapValue,$w=400,$h=300){
	global $mapCount;
	$mapValue=str_replace(array("[","]"),"",$mapValue);
	$mapParams=explode(",",$mapValue);
	echo '<div id="map_canvas-'.$mapCount.'" style="width: '.$w.'px; height: '.$h.'px"></div>';
	echo '<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&key=ABQIAAAACuPnpsfgQtylGTK5oELr8hR0DCSZkES4mAjJ0aPCnwtfcV3XsRSRV4aCAUZF_Yo1zu8OsCxCX7Salw
	"></script> ';
	echo '<script type="text/javascript">
			    var map_'.$mapName.';
				jQuery(function(){
			      if (GBrowserIsCompatible()) {
			        map_'.$mapName.' = new GMap2(document.getElementById("map_canvas-'.$mapCount.'"));
			        map_'.$mapName.'.setCenter(new GLatLng('.$mapParams[0].', '.$mapParams[1].'), '.$mapParams[2].');
			        map_'.$mapName.'.setUIToDefault();
			        //map_'.$mapName.'.openInfoWindow(map_'.$mapName.'.getCenter(),document.createTextNode("Hello, world"));
			        
			      }
			   })
		  </script>';
	$mapCount++;
}

/*
 * Adds a marker to a specific google map
 * 		$mapName - the javascript name of the map - an exsisting map
 * 		$mapValue - "long,lat,zoom-value" triple
 * 		$html - the html content that should appear in the marker's bubble
 */
function addGoogleMapMarker($mapName,$mapValue,$html=""){
	$mapValue=str_replace(array("[","]"),"",$mapValue);
	$mapParams=explode(",",$mapValue);
	echo '<script>
				jQuery(function(){
					var point = new GLatLng('.$mapParams[0].','.$mapParams[1].');
					var marker=new GMarker(point);
			   		map_'.$mapName.'.addOverlay(marker);';
			   	
				if($html!=""){	
			   		echo 'GEvent.addListener(marker, "click", function() {
							    var myHtml = "'.$html.'";
							    map_'.$mapName.'.openInfoWindowHtml(point, myHtml);
							  });';
				}
			echo '})</script>';
}

/*
 * Default rendering for the image gallery node - returnd ul>li>a>img - need to figure out the alt??
 * 		$galArray - an array of comma separated values - paths to images
 */
function renderImageGallery($galArray){
	if(is_array($galArray)){
		foreach($galArray as $image){
			$thumb=str_replace("img_","thumb_img_",$image);
			echo '<li><a href="'.$image.'"><img src="'.$thumb.'" /></a></li>';
		}
	}
}
// retreives image from the post
function getImage($num) {
	global $more;
	$more = 1;
	$content = get_the_content();
	$count = substr_count($content, '<img');
	$start = 0;
	for($i=1;$i<=$count;$i++) {
		$imgBeg = strpos($content, '<img', $start);
		$post = substr($content, $imgBeg);
		$imgEnd = strpos($post, '>');
		$postOutput = substr($post, 0, $imgEnd+1);
		$image[$i] = $postOutput;
		$start=$imgEnd+1;  
		$cleanF = strpos($image[$num],'src="')+5;
		$cleanB = strpos($image[$num],'"',$cleanF)-$cleanF;
		$imgThumb = substr($image[$num],$cleanF,$cleanB);
	}
	if(stristr($image[$num],'<img')) 
	{ 
		echo str_replace(" ", "%20", $imgThumb); 
	}
	else
	{
		echo bloginfo('template_directory')."/images/default-related-thumbnail.jpg";
	}
	$more = 0;
}
//retreive image ends
function contentexcerpt($num) {
	$limit = $num+1;
	$contentexcerpt = explode(' ', get_the_content(), $limit);
	array_pop($contentexcerpt);
	$contentexcerpt = implode(" ",$contentexcerpt)."...";
	echo $contentexcerpt;
}
function getGlobalImage($imageName){
	//switch_to_blog(1);
	$filepath=getThemeMeta($imageName,"","_Settings");
	//restore_current_blog();
	return $filepath;
}
function wp_get_post_image($num=1,$content=null){
	global $more;
	$more = 1;
	if($content==null){
		$content = get_the_content();
	}
	$count = substr_count($content, '<img');
	$start = 0;
		for($i=1;$i<=$count;$i++) {
			$imgBeg = strpos($content, '<img', $start);
			$post = substr($content, $imgBeg);
			$imgEnd = strpos($post, '>');
			$postOutput = substr($post, 0, $imgEnd+1);
			$image[$i] = $postOutput;
			$start=$imgEnd+1;  
			$cleanF = strpos($image[$num],'src="')+5;
			$cleanB = strpos($image[$num],'"',$cleanF)-$cleanF;
			$imgThumb = substr($image[$num],$cleanF,$cleanB);
		}
	if(stristr($image[$num],'<img')) 
	{ 
			$tempArr=explode("?",$imgThumb);
			return urlencode($tempArr[0]);
			return str_replace("+","%2b",str_replace(" ", "%20", $tempArr[0])); 
	}
	else
	{
		return "";
	}
}
/**** wp mu get img path ****/

function get_image_path ($img_path = null) {
	$theImageSrc = $img_path;
	global $blog_id;
	if (isset($blog_id) && $blog_id > 0) {
		$imageParts = explode('/files/', $theImageSrc);
		if (isset($imageParts[1])) {
			$theImageSrc = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
		}
	}
	return $theImageSrc;
}


/* blog navigaion */
function wp_pagenavi($before = '', $after = '', $prelabel = '', $nxtlabel = '', $pages_to_show = 7, $always_show = false) {
	global $request, $posts_per_page, $wpdb, $paged;
	if(empty($prelabel)) {
		$prelabel  = '<strong>&lt;</strong>';
	}
	if(empty($nxtlabel)) {
		$nxtlabel = '<strong>&gt;</strong>';
	}
	$half_pages_to_show = round($pages_to_show/2);
	if (!is_single()) {
		if(!is_category()) {
			preg_match('#FROM\s(.*)\sORDER BY#siU', $request, $matches);		
		} else {
			preg_match('#FROM\s(.*)\sGROUP BY#siU', $request, $matches);		
		}
		$fromwhere = $matches[1];
		$numposts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $fromwhere");
		$max_page = ceil($numposts /$posts_per_page);
		if(empty($paged)) {
			$paged = 1;
		}
		if($max_page > 1 || $always_show) {
			echo "$before <span>Page $paged of $max_page </span> <div class='Nav'>";
			if ($paged >= ($pages_to_show-1)) {
				echo '<a href="'.get_pagenum_link().'">1st</a> <div class="dotts">...</div> ';
			}
			previous_posts_link($prelabel);
			for($i = $paged - $half_pages_to_show; $i  <= $paged + $half_pages_to_show; $i++) {
				if ($i >= 1 && $i <= $max_page) {
					if($i == $paged) {
						echo "<span class='on'>$i</span>";
					} else {
						echo ' <a href="'.get_pagenum_link($i).'">'.$i.'</a> ';
					}
				}
			}
			next_posts_link($nxtlabel, $max_page);
			if (($paged+$half_pages_to_show) < ($max_page)) {
				echo ' <div class="dotts">...</div> <a href="'.get_pagenum_link($max_page).'" class="lastpage">Last</a>';
			}
			echo "<div class='NavEnd'></div></div> $after";

		}
	}
}

function new_excerpt_more($more) {
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');


/****** init widgets ****/

//RECENT COMMENTS widget START
class IDGL_Recent_Comments extends WP_Widget {
	function IDGL_Recent_Comments() {
		parent::WP_Widget(false, $name = 'IDGL_Recent_Comments');
	}

	function form($instance) {
		$title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function widget($args, $instance) {
	switch_to_blog(4);
		?>
		<div class="widget sideComments">
            <h2 class="RecentComments"><?php echo $instance['title']; ?></h2>
            <ul class="recentComList">
                <?php
                global $wpdb;
                $sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
                comment_post_ID, comment_author, comment_date, comment_approved,
                comment_type,comment_author_url,
                SUBSTRING(comment_content,1,100) AS com_excerpt
                FROM $wpdb->comments
                LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
                $wpdb->posts.ID)
                WHERE comment_approved = '1' 
                ORDER BY comment_date DESC
                LIMIT 5";
                $comments = $wpdb->get_results($sql);
                $output = $pre_HTML;
                foreach ($comments as $comment) {
                $output .= "\n<li><a href=\"" . get_permalink($comment->ID) .
                "#comment-" . $comment->comment_ID . "\" title=\"on " .
                $comment->post_title . "\">" .strip_tags($comment->com_excerpt).
                "...</a>" . "<span class='comment-details'><span class='avtor'>".$comment->comment_author."</span> 
                <span class='datum'>".date(strip_tags("d M, Y",$comment->comment_date))."</span></span></li>";
                }
                $output .= $post_HTML;
                echo $output; ?>
            </ul>
        </div>
		<?php
		restore_current_blog();
	}
}
//RECENT COMMENTS widget START


//Recent FORUM Discussions
class Recent_FORUM_Discussions extends WP_Widget {
	function Recent_FORUM_Discussions() {
		parent::WP_Widget(false, $name = 'Recent_FORUM_Discussions');	
	}
	function form($instance) {
		$title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function widget($args, $instance) {
		?>	
		<div class="widget forums">
			<h2><?php echo $instance['title']; ?></h2>
			<ul>
				<?php
				global $wpdb;
				$query="SELECT * FROM bb_topics WHERE topic_status=0 ORDER BY topic_time DESC LIMIT 5";
				$results=$wpdb->get_results($query);
				foreach ($results as $result) {
					echo "<li><a href='".get_bloginfo('url')."/forum/topic.php?id=".$result->topic_id."'>".$result->topic_title."</a></li>";
				}
				?>
			</ul>
		</div>
		<?php 
	}
}


//get_slider("sliderSlider","post");
class IDGL_Slider extends WP_Widget {
	function IDGL_Slider() {
		parent::WP_Widget(false, $name = 'IDGL_Slider');	
	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function widget($args, $instance) {
		?>
		<div class="slider-wrap-relative widget">
		<?php get_slider("sliderWidget","slider",310,220,false); ?>
		</div>
		<?php 
	}
}

class IDGL_Rss_Subscription_Form extends WP_Widget {
	function IDGL_Rss_Subscription_Form() {
		parent::WP_Widget(false, $name = 'IDGL_Rss_Subscription_Form');	
	}
	function form($instance) {				
        $title = esc_attr($instance['title']);
		$listArea = esc_attr($instance['listArea']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('FeedBurner ID:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('listArea'); ?>"><?php _e('List Of Reasons (leave line break for new node):'); ?> <textarea cols="25" name="<?php echo $this->get_field_name('listArea'); ?>"><?php echo $listArea; ?></textarea></p>
		<?php 
    }
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['listArea'] = strip_tags($new_instance['listArea']);
        return $instance;
		
	}
	function widget($args, $instance) {
		?>
		<div class="widget rss_form">
			<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $instance['title']; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" class="subscribeform">
			  <p> 
				  <input type="text" name="email" value="Enter email address..." onfocus="if (this.value == 'Enter email address...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Enter email address...';}" />
				  <input type="hidden" value="edgeincollegeprep/XwXl" name="uri" />
				  <input type="hidden" name="loc" value="en_US" />
			  </p>
			  <p>
			 	 <input type="submit" id="subscribesubmit" class="button green fixmeBtn" value="Subscribe" />
			  </p>
			</form>
			<div class="clear"></div>
		</div>
		<div class="widget">
			<h4 class="reasons-title">Top 5 Reasons to <span>SUBSCRIBE</span></h4>
			<ul class="reasons-list">
              <?php 
					$tempArr =  explode("\n", $instance['listArea']);
					foreach($tempArr as $list){
						echo ' <li>'.$list.'</li>';
					} 
				?>
            </ul>
            <div class="clear"></div>
		</div>
		<?php 
	}
}
class IDGL_PriceList extends WP_Widget {
	function IDGL_PriceList() {
		parent::WP_Widget(false, $name = 'IDGL_PriceList');	
	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function widget($args, $instance) {
		echo "<div class='widget'><ul class='price-list-ul'>";
		switch_to_blog(1);
		for($i=0;$i<10;$i++){
			
			$label=getThemeMeta("Prod".$i."1","","_PromotedProducts");
			$link=getThemeMeta("Prod".$i."2","","_PromotedProducts");
			$price=getThemeMeta("Prod".$i."3","","_PromotedProducts");
			$color=getThemeMeta("Prod".$i."4","","_PromotedProducts");
			if($label!=""){
				echo "<li style='background-color:$color;'>
					<a href='$link'>	
						<span class='label-product'>$label</span>
						<span class='price-product'>$price</span>
					</a>
				</li>";	
			}
		}
		restore_current_blog();
		echo "</ul></div>";
	}
}

class IDGL_FAQ extends WP_Widget {
	function IDGL_FAQ() {
		parent::WP_Widget(false, $name = 'IDGL_FAQ');	
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['faq_category'] = strip_tags($new_instance['faq_category']);
		$instance['displayNo'] = strip_tags($new_instance['displayNo']);
        return $instance;
	}
	function form($instance) {				
        $title = esc_attr($instance['title']);
        $faq_category = esc_attr($instance['faq_category']);
        $displayNo= esc_attr($instance['displayNo']);
        switch_to_blog(1); $faq_cats=get_terms("faq-category"); restore_current_blog();
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('displayNo'); ?>"><?php _e('Number of items:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('displayNo'); ?>" name="<?php echo $this->get_field_name('displayNo'); ?>" type="text" value="<?php echo $displayNo; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('faq_category'); ?>"><?php _e('Category:'); ?> 
            	<select class="widefat" id="<?php echo $this->get_field_id('faq_category'); ?>" name="<?php echo $this->get_field_name('faq_category'); ?>">
					<?php 
						foreach($faq_cats as $cat){
							if($cat->slug==$faq_category){
								echo "<option selected='selected' value='".$cat->slug."'>".$cat->name."(".$cat->count.")</option>";
							}else{
								echo "<option value='".$cat->slug."'>".$cat->name."(".$cat->count.")</option>";
							}
						}
					?>
            	</select>
            </label></p>
            
        <?php 
    }
	function widget($args, $instance) {
		$faq_category = esc_attr($instance['faq_category']);
		$displayNo= esc_attr($instance['displayNo']);
		if($displayNo==""){
			$displayNo=5;
		}
		switch_to_blog(1);
		query_posts('posts_per_page='.$displayNo.'&post_type=faq&taxonomy=faq-category&term='.$faq_category);
		?>
		<div class="widget faq">
                    <h2><?php echo $instance['title']; ?></h2>
                    <ul>
		<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post(); global $post;
		?>
			<li><a href="<?php bloginfo("url"); ?>/faq/<?php echo $faq_category; ?>/#faq-<?php echo $post->ID ?>"><?php the_title(); ?><span><?php echo getPostMeta($post->ID, "eventDate") ?></span></a></li>
		<?php 
		endwhile;
		endif; ?>
		 </ul>
         </div>
		<?php 
		wp_reset_query();
		restore_current_blog();
	}
}

class IDGL_Recent_News extends WP_Widget {
	function IDGL_Recent_News() {
		parent::WP_Widget(false, $name = 'IDGL_Recent_News');	
	}
	function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 
    }
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
		
	}
	function widget($args, $instance) {
		?>
		<div class="widget news">
                   <h2><?php echo $instance['title']; ?></h2>
                    <ul>
                     <?php 
				        switch_to_blog(4);
				        $my_query = new WP_Query('showposts=5'); ?>
				        <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
				        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				          <?php the_title(); ?>
				          </a> </li>
				        <?php endwhile;
						restore_current_blog();
				      ?>
				      </ul>
                </div>
                <div class="clear"></div>
		<?php 
	}
}


class IDGL_Upcoming_Events extends WP_Widget {
	function IDGL_Upcoming_Events() {
		parent::WP_Widget(false, $name = 'IDGL_Upcoming_Events');	
	}
	function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 
    }
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
		
	}
	function widget($args, $instance) {
		switch_to_blog(1);
		query_posts('posts_per_page=5&cat=4');
		?>
		<div class="widget events">
                    <h2><?php echo $instance['title']; ?></h2>
                    <ul>
		<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post(); global $post;
		?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?><span><?php echo getPostMeta($post->ID, "eventDate") ?></span></a></li>
		<?php 
		endwhile;
		endif; ?>
		 </ul>
         </div>
		<?php 
		wp_reset_query();
		restore_current_blog();
		?>
		
		<?php 
	}
}

add_action( 'widgets_init', 'wb_load_widgets' );
function wb_load_widgets() {
	register_widget( 'IDGL_Slider' );
	register_widget( 'IDGL_PriceList' );
	register_widget( 'IDGL_FAQ' );	
	register_widget( 'IDGL_Recent_News' );
	register_widget( 'IDGL_Upcoming_Events' );
	register_widget( 'IDGL_twitter_list' );
	register_widget( 'IDGL_domtab_list' );
	register_widget( 'IDGL_Rss_Subscription_Form' );
	register_widget( 'IDGL_Recent_Comments' );
	register_widget( 'Recent_FORUM_Discussions' );
	
}

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">


<div class="comment-author vcard">

<div class="comment-head">


         <?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?><span class="person-say">says:</span>
        

         
</div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>
      

</div>

<div class="comment-text">
<?php comment_text() ?>
</div>

     </div><div class="clear"></div>
     <div class="reply">
         	<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      	 </div>
      	 
     <div class="clear"></div>
<?php
}


function mytheme_trackback($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author">
         <?php printf(__('%s'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

     </div>
<?php
}
//TWITTER widget START
class IDGL_twitter_list extends WP_Widget {
	function IDGL_twitter_list() {
		parent::WP_Widget(false, $name = 'IDGL_twitter_list');	
	}
	function form($instance) {
		$title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$NoLinks = esc_attr($instance['NoLinks']);
		$timestamps = esc_attr($instance['timestamps']);
		$linked = esc_attr($instance['linked']);
		
		$replies = esc_attr($instance['replies']);
		$hyperlinks = esc_attr($instance['hyperlinks']);
		
		
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('NoLinks'); ?>"><?php _e('Number of links:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('NoLinks'); ?>" name="<?php echo $this->get_field_name('NoLinks'); ?>" type="text" value="<?php echo $NoLinks; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('linked'); ?>"><?php _e('Linked:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('linked'); ?>" name="<?php echo $this->get_field_name('linked'); ?>" type="text" value="<?php echo $linked; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('timestamps'); ?>"><?php _e('Show timestamps:'); ?> <input id="<?php echo $this->get_field_id('timestamps'); ?>" name="<?php echo $this->get_field_name('timestamps'); ?>" type="checkbox" <?php if($timestamps!=""){ echo "checked='checked'"; } ?> /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('hyperlinks'); ?>"><?php _e('Discover Hyperlinks:'); ?> <input id="<?php echo $this->get_field_id('hyperlinks'); ?>" name="<?php echo $this->get_field_name('hyperlinks'); ?>" type="checkbox" <?php if($hyperlinks!=""){ echo "checked='checked'"; } ?> /></label></p>
            <p><label for="<?php echo $this->get_field_id('replies'); ?>"><?php _e('Discover @replies:'); ?> <input id="<?php echo $this->get_field_id('replies'); ?>" name="<?php echo $this->get_field_name('replies'); ?>" type="checkbox" <?php if($replies!=""){ echo "checked='checked'"; } ?> /></label></p>
        <?php 
	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function widget($args, $instance) {
		$title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$NoLinks = esc_attr($instance['NoLinks']);
		$timestamps = esc_attr($instance['timestamps']);
		$linked = esc_attr($instance['linked']);
		$replies = esc_attr($instance['replies']);
		$hyperlinks = esc_attr($instance['hyperlinks']);
		$userXml = file_get_contents("http://twitter.com/statuses/user_timeline/$username.rss");
		if($userXml){
			$fileCon=new SimpleXMLElement($userXml);
			
			echo '<div class="widget widget_twitter">
					<div class="postsWigetContent">';
			if($title!=""){
				echo "<h2 class='twitterHead'>$title <a class='follow' href='http://twitter.com/".$username."'>Follow us</a></h2>";
			}
			if($fileCon)
			echo "<ul class='twitter' style='overflow:hidden;'>";
			$counter=0;
			foreach($fileCon->channel->item as $item){
				if($counter>=$NoLinks){
					break;
				}
				echo '<li class="twitter-item">';
				
				$content=substr($item->title,strpos($item->title,":")+1);
				if($hyperlinks!=""){
					$content=$this->wb_tweeter_hyperlinks($content);
				}
				if($replies!=""){
					$content=$this->wb_tweeter_users($content);
				}	
				echo $content;
				echo '<a class="twitter-link" href="'.$item->link.'">'.$linked.'</a>';
				if($timestamps!=""){
					echo '<span class="twitter-timestamp">
							<abbr title="2010/04/19 21:53:50">'. date("Y/m/d H:i:s",strtotime($item->pubDate)) .'</abbr>
						</span>';
				}
				echo '</li>';
				$counter++;
			}
			echo "</ul>";
			echo '</div>
				</div>';
			}
	}
	 function wb_tweeter_hyperlinks($text) {
	    $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);    
	    $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);
	    return $text;
	}
	function wb_tweeter_users($text) {
       $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
       return $text;
	} 
}
//TWITTER widget END

class IDGL_domtab_list extends WP_Widget {
	function IDGL_domtab_list() {
		parent::WP_Widget(false, $name = 'IDGL_domtab_list');	
	}
	function form($instance) {
		$title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 

	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function widget($args, $instance) {
		?>
			<div class="widget domtab">
				<?php include("_tabs.php"); ?>
				<div class="clear"></div>
			</div>
		<?php
	}
}

if(isset($_GET["f_side"]) && $_GET["f_side"]=="sidebar"){
	 add_action('init', "render_sidebar");
	 
 	function render_sidebar() {
       new Sidebar("forumSidebar");
       die();
    }
}
if(isset($_GET["f_side"]) && $_GET["f_side"]=="header"){
 add_action('init', "render_header");
	 
 	function render_header() {
       get_header(); 
       die();
    }
}
if(isset($_GET["f_side"]) && $_GET["f_side"]=="footer"){
 add_action('init', "render_footer");
	 
 	function render_footer() {
       get_footer(); 
       die();
    }
}


/******* menu class ******/

class Wp_Menu{
	private $menu_items;
	public static $current_selection;
	public static $flat_map;
	public function Wp_Menu($menu_items){
		$this->menu_items=array();

		foreach($menu_items as $item){
			$menu_item=$this->menu_items[$item->ID]=new Wp_MenuItem($item,$item->menu_item_parent);
			if($item->menu_item_parent!=0){
				$this->menu_items[$item->menu_item_parent]->addChild($menu_item);
			}
		}
	}
	public function toString(){
		
		foreach($this->menu_items as $item){
			
			if($item->getParentID()==0){
				if($item->item->object_id==6) continue;
				$out.="<div class='elem_".$item->item->object_id."'>";
				$out.=$item->toString();
				$out.="</div>";
			}
		}
		
		return $out;
	}
	
	public function getSubMenu(){
		$current=Wp_Menu::$current_selection;
		if(count($current->submenu)==0){
			$current=Wp_Menu::$flat_map[$current->parent];
		}
		if(is_array($current->submenu)){
			foreach($current->submenu as $item){
					$out.=$item->toString();
			}
		}
		return $out;
	}
}
class Wp_MenuItem{
	public $item;
	public $parent;
	public $submenu;
	public function Wp_MenuItem($item,$parent=0,$submenu=null){
		$this->item=$item;
		$this->parent=$parent;
		if($this->submenu==null){
			$this->submenu=array();
		}
		global $wp_query;
		$post_obj = $wp_query->get_queried_object();
		$post_id = $post_obj->ID;
		//echo $post_id." -- ".$this->item->object_id."<br/>";
		if(Wp_Menu::$current_selection==null && $post_id==$this->item->object_id){
			Wp_Menu::$current_selection=$this;
		}
		Wp_Menu::$flat_map[$this->item->ID]=$this;
	}
	public function addChild($item){
		$this->submenu[]=$item;
	}
	public function getParentID(){
		return $this->parent;
	}
	public function getType(){
		return $this->item->object;
	}
	public function toString(){
		if(Wp_Menu::$current_selection==$this){
			if($this->parent==0){
				$out.="<h5>".$this->item->title."</h5>";
			}else{
				$out.="<a href='".$this->item->url."'>".$this->item->title."</a>";
			}
			
		}else{
			if($this->parent==0){
				$out.="<h5>".$this->item->title."</h5>";
			}else{
				$out.="<a href='".$this->item->url."'>".$this->item->title."</a>";
			}
		}
		
		if(count($this->submenu)>0){
			foreach($this->submenu as $item){
					$out.=$item->toString();
			}
		}
		return $out;
	}
}