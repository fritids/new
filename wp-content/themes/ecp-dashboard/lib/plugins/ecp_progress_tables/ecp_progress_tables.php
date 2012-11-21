<?php
PostType::register(IDGL_File::getFileList(dirname(__FILE__) . "/models/"));

add_action('admin_menu', 'ecp_table_templates_add');

function ecp_table_templates_add()
{
	if(isset($_GET["post_type"]))
	{
		$currEditType = $_GET["post_type"];
	}
	else
	{
		$currEditType = get_post_type($_GET["post"]);
	}
	if($currEditType != "progresstemplates" && $currEditType != "coaching")
	{
		return;
	}
	
	$postOptions = IDGL_Config::getConfig(dirname(__FILE__) . "/models/" . $currEditType . ".xml");

	foreach($postOptions as $page)
	{		
		$name = $page["name"];
		$title = $page["title"];
		IDGL_PostOptionManages::IDGL_addPostOptionFunction($name, $page);
		add_meta_box($name, __($title, $title), "IDGL_fx_" . $name, $currEditType, 'advanced', 'high');
	}
}

function ecp_progress_table_callback($param, $name, $pagename = "")
{
	eval('$args="' . $param . '";');
	//$bgArr=IDGL_File::getFileList(IDG_ROOT."/images/");
	$table_html = getThemeMeta($name, "", $pagename);
	
	echo '<div class="progress_table_template">
				<a href="#" id="btn_switch">view html</a>
				<textarea id="html_holder" rows="5" cols="60" name="IDGL_elem' . $pagename . '[' . $name . ']" >' . $table_html . '</textarea>
				<div id="table_holder">' . $table_html . '</div>
			</div>';
}
/*
 * ?>
	<script type="text/javascript">
		jQuery(function(){
			jQuery("#btn_switch").click(function(e){
				e.preventDefault();
				if(jQuery(this).html().indexOf("html")!=-1){
					jQuery("#table_holder").hide();
					jQuery("#html_holder").show();
				}
			})
		})
	</script>
	<?php 
 */

/*
 * Print admin head
 */
add_action('admin_head', 'ecp_progress_table_head');

function ecp_progress_table_head()
{
	echo '<link type="text/css" rel="stylesheet" href="' . IDGL_THEME_URL . '/lib/plugins/ecp_progress_tables/css/style.css" />' . "\n";
	echo '<script type="text/javascript" src="' . IDGL_THEME_URL . '/lib/plugins/ecp_progress_tables/js/pr_scripts.js"></script> ';
}

function enque_progress_table_styles()
{
	add_action('wp_print_styles', 'add_progress_table_styles');

	function add_progress_table_styles()
	{
		wp_register_style('progress_table_styles', IDGL_THEME_URL . '/lib/plugins/ecp_progress_tables/css/style.css');
		wp_enqueue_style('progress_table_styles');
	}
}

function enque_progress_table_scripts()
{
	wp_register_script('progress_table_scripts', IDGL_THEME_URL . '/lib/plugins/ecp_progress_tables/js/pr_scripts.js');
	wp_enqueue_script('progress_table_scripts');
}

/* AJAX API */
add_action('wp_ajax_progress_table_save', 'progress_table_save');

function progress_table_save()
{
	extract($_POST);
	fb($_POST);
	
	if(isset($current_user) && isset($table_name) && isset($table_data))
	{
		if(ECPUser::setAttribute($current_user, $table_name, trim($table_data)))
		{
			echo "Data saved successfully";
			exit();
		}
		echo "User data not saved. Please try again.";
	}
	exit();
}

function get_user_progress_tables($user_id)
{
	global $wpdb;
	$prt_sql = "SELECT meta_value 
				FROM wp_usermeta
				WHERE user_id = {$user_id}
				AND meta_key LIKE '%table_id%'";
	$results = $wpdb -> get_results($prt_sql);
	fb($results);
}

function ecp_related_product($param,$name,$pagename=""){
	global $post;
	
	$selected=-1;
	if(isset($_GET["post"])){
		$selected=getPostMeta($_GET["post"],$name);
		$out="<label>Related Product: </label>";
		switch_to_blog(3);
		$args = array(
			'posts_per_page'=>-1,
			'post_type'=>'ecpproduct'
		);
		$query = new WP_Query( $args );
		while ( $query->have_posts() ) : $query->the_post(); global $post;
			if(in_array($post->ID,$selected)){
				$out.="<div style='margin-top:10px;'><input name='IDGL_elem[$name][]' type='checkbox' value='".$post->ID."' checked='checked'>".get_the_title()."</div>";
			}else{
				$out.="<div style='margin-top:10px;'><input name='IDGL_elem[$name][]' type='checkbox' value='".$post->ID."'>".get_the_title()."</div>";
			}
			
		endwhile;
		wp_reset_postdata();
	    restore_current_blog();
	}
	
	return $out;
}