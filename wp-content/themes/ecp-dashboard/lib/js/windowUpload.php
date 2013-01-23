<?php
function getPluginPath()
{
	$index = "wp-content";
	$path = dirname(__FILE__);
	$string = substr($path, 0, strpos($path,$index));
	return $string;
}
$path = getPluginPath();
require  $path."wp-load.php";

wp_enqueue_script('jquery');
wp_register_script('jquery-ui', IDGL_THEME_URL . '/lib/js/jquery-ui-1.8.24.custom.min.js');
wp_enqueue_script('jquery-ui');
wp_register_script('ajaxupload', IDGL_THEME_URL . '/lib/js/ajaxupload.js');
wp_enqueue_script('ajaxupload');
wp_register_script('idgl_colorpicker', IDGL_THEME_URL . '/lib/js/colorpicker/colorpicker.js');
wp_enqueue_script('idgl_colorpicker');
wp_register_script('main', IDGL_THEME_URL . '/lib/js/main.js');
wp_enqueue_script('main');
wp_head();
IDGL_Admin_head();
?>
<script type="text/javascript">
jQuery(function(){
	jQuery(".image_insert").click(function(e){
		e.preventDefault();
		parent.insert_data("<img src='"+jQuery(".imageWrap img").attr("src")+"' />");
	})
	jQuery(".image_remove").click(function(e){
		parent.insert_data("");
	})
})
</script>
<div class="IDGL_listNode">
	<div class="IDGL_Image">
		<div class="imageWrap"><img value="" src="" class="elem">
			<div><input type="hidden" value="" name="" class="hiddenFld"> <a
				href="#" class="button uploadify" id="pqid_1290007951799">Browse</a> <a
				href="#" class="button image_remove">Close</a> <a href="#"
				class="button image_insert">Insert</a></div>
		</div>
	</div>
</div>
