<?php
require_once("drill_report.php");
register_nav_menus( array(
		'ecp_mySATCourse' => __( 'mySATCourse menu', 'ecp_mySATCourse' ),
	) );

PostType::register(IDGL_File::getFileList(dirname(__FILE__)."/models/"));

add_action('admin_menu', 'ecp_questions_add');
function ecp_questions_add(){
	if(isset($_GET["post_type"])){
		$currEditType=$_GET["post_type"];
	}else{
		$currEditType=get_post_type($_GET["post"]);
	}
	if(strtolower($currEditType)!="questions" && strtolower($currEditType)!="drill"){ return; }
	
	$postOptions=IDGL_Config::getConfig(dirname(__FILE__)."/models/".$currEditType.".xml");
	foreach($postOptions as $page){
		$name=$page["name"];
		$title=$page["title"];
		IDGL_PostOptionManages::IDGL_addPostOptionFunction($name,$page);
		add_meta_box( $name, __( $title, $title ), "IDGL_fx_".$name, $currEditType, 'advanced','high' );
	
	}
}

$labels = array(
    'name' => _x( 'Question Category', 'taxonomy general name' ),
    'singular_name' => _x( 'Question Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Question Categories' ),
    'popular_items' => __( 'Popular Question Categories' ),
    'all_items' => __( 'All Question Categories' ),
    'parent_item' => __( 'Parent Question Categories' ),
    'parent_item_colon' => __( 'Parent Question Categories:' ),
    'edit_item' => __( 'Edit Question Categories' ), 
    'update_item' => __( 'Update Question Category' ),
    'add_new_item' => __( 'Add New Question Category' ),
    'new_item_name' => __( 'New Question Category' ),
);
register_taxonomy('qcategories',array('questions'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'question-category' ),
));


$labels = array(
    'name' => _x( 'Drill Category', 'taxonomy general name' ),
    'singular_name' => _x( 'Drill Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Drill Categories' ),
    'popular_items' => __( 'Popular Drill Categories' ),
    'all_items' => __( 'All Drill Categories' ),
    'parent_item' => __( 'Parent Drill Categories' ),
    'parent_item_colon' => __( 'Parent Drill Categories:' ),
    'edit_item' => __( 'Edit Drill Categories' ), 
    'update_item' => __( 'Update Drill Category' ),
    'add_new_item' => __( 'Add New Drill Category' ),
    'new_item_name' => __( 'New Drill Category' ),
);
register_taxonomy('DrillCategories',array('drill'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'drill-category' ),
));

function addBrowserCapability(){
	
}
function ecp_question_video($param,$name,$pagename=""){
	return "
	<script type='text/javascript' src='".get_bloginfo("template_url")."/lib/plugins/ecp_self_tests/browser/js/abz_ftp_file_browser.js'></script>
	
<div>
Browse videos <br/>
<div id='abz_browse'>
Browse
</div>
<div id='abz_dialog'>
	<div id='abz_holder'>
	</div> 
	<div id='abz_preloader'>
	</div>
</div>	
<input type='text' value='".getPostMeta($_GET["post"],"VideoURL")."' class='elem' style='width:100%;' name='IDGL_elem[VideoURL]' id='abz_selected_file'/>
</div>
	";
}
function ecp_question_passage_callback($param,$name,$pagename=""){
	eval('$args="'.$param.'";');
	$current=getPostMeta($_GET["post"],$name);
	if($current["passage_type"]=="plain"){$plain_sel=" checked='checked';";}
	if($current["passage_type"]=="numbered"){$numbered_sel=" checked='checked' ";}

	$outStr="<div>
		<div style='margin-bottom:10px;padding-bottom:5px;border-bottom:solid 1px #cacaca;min-width:800px;'>
			<input class='passage_type' $plain_sel type='radio' name='IDGL_elem".$pagename."[".$name."][passage_type]' value='plain'  /> plain text passage
			<input class='passage_type' $numbered_sel type='radio' name='IDGL_elem".$pagename."[".$name."][passage_type]' value='numbered'  /> numbered lines passage
		</div>
		<table cellpadding='0' cellspacing='0' border='8' id='plain' style='display:none;'>
			<tr>
				<td>
					<textarea id='passage_content' name='IDGL_elem".$pagename."[".$name."][plain_passage]' style='width:900px; min-height:200px;'>".$current['plain_passage']."</textarea>
				</td>
			</tr>
		</table>
		<table cellpadding='0' cellspacing='0' border='8' id='numbered' style='display:none;'>
			<tr>
				<td>
				</td>
				<td class='numbered_passage'>
					<textarea id='passage_content' name='IDGL_elem".$pagename."[".$name."][numbered_passage]' style='width:750px; min-height:200px;'>".$current['numbered_passage']."</textarea>
				</td>
			</tr>
		</table>
		<h4>Passage description:</h4>
		<textarea style='width:900px; min-height:200px;' name='IDGL_elem".$pagename."[".$name."][after_passage_description]'>".$current['after_passage_description']."</textarea>
	</div>
	<script type='text/javascript'>
		jQuery(function(){
			jQuery('.passage_type').click(function(){
				jQuery('.passage_type').each(function(){
					jQuery('#'+jQuery(this).val()).hide();
				})
				jQuery('#'+jQuery('.passage_type:checked').val()).show();
			})
			jQuery('.passage_type:checked').trigger('click');
			initPassageAreas()
		})
	</script>";
	
	return $outStr;
}

add_filter('mce_external_plugins', "tinyplugin_register");
add_filter('mce_buttons', 'tinyplugin_add_button', 0);

function tinyplugin_add_button($buttons)
{
    array_push($buttons, "separator", "tinyplugin");
    return $buttons;
}

function tinyplugin_register($plugin_array)
{
    $url = trim(get_bloginfo('template_url'), "/")."/lib/js/editor_plugin.js";

    $plugin_array['tinyplugin'] = $url;
    return $plugin_array;
}

function ecp_answer_callback($param,$name,$pagename=""){
	eval('$args="'.$param.'";');
	$current=getPostMeta($_GET["post"],$name);
	
	if(is_array($current)){
		$answer_plain_sel=($current["answer_type"]=="answer_plain")? " checked='checked' " : '' ;
		$answer_text_image_sel=($current["answer_type"]=="answer_text_image")? " checked='checked' " : '' ;
		$answer_dropdown_sel=($current["answer_type"]=="answer_dropdown")? " checked='checked' " : '' ;
		
		$answer_correct_a_sel=($current["answer"]["correct"]=="a")? " checked='checked' " : '' ;
		$answer_correct_b_sel=($current["answer"]["correct"]=="b")? " checked='checked' " : '' ;
		$answer_correct_c_sel=($current["answer"]["correct"]=="c")? " checked='checked' " : '' ;
		$answer_correct_d_sel=($current["answer"]["correct"]=="d")? " checked='checked' " : '' ;
		$answer_correct_e_sel=($current["answer"]["correct"]=="e")? " checked='checked' " : '' ;
	}else{
		$current=array();
	}
	
	$outStr="<div>
		<div style='margin-bottom:10px;padding-bottom:5px;border-bottom:solid 1px #cacaca;min-width:800px;'>
			<input class='answer_type' type='radio' name='IDGL_elem".$pagename."[".$name."][answer_type]' value='answer_plain' $answer_plain_sel  /> plain text
			<input class='answer_type' type='radio' name='IDGL_elem".$pagename."[".$name."][answer_type]' value='answer_dropdown' $answer_dropdown_sel  /> dropdown values
		</div>
		<div id='answer_plain' style='display:none;'>

				<div style='margin-top:20px;font-weight:bold;'>Answer Option A</div>
				<textarea class='theEditor' style='width:700px;height:200px;' type='text' name='IDGL_elem".$pagename."[".$name."][answer][a][value]'>".htmlentities($current['answer']['a']['value'],ENT_QUOTES)."</textarea>  | <input type='radio' value='a' name='IDGL_elem".$pagename."[".$name."][answer][correct]' $answer_correct_a_sel /> <strong class='correct_choice'>correct answer</strong>
				<hr/>
				
				<div style='margin-top:20px;font-weight:bold;'>Answer Option B</div>
				<textarea class='theEditor' style='width:700px;height:200px;' type='text' name='IDGL_elem".$pagename."[".$name."][answer][b][value]'>".htmlentities($current['answer']['b']['value'],ENT_QUOTES)."</textarea>  | <input type='radio' value='b' name='IDGL_elem".$pagename."[".$name."][answer][correct]' $answer_correct_a_sel /> <strong class='correct_choice'>correct answer</strong>
				<hr/>
				
				<div style='margin-top:20px;font-weight:bold;'>Answer Option C</div>
				<textarea class='theEditor' style='width:700px;height:200px;' type='text' name='IDGL_elem".$pagename."[".$name."][answer][c][value]'>".htmlentities($current['answer']['c']['value'],ENT_QUOTES)."</textarea>  | <input type='radio' value='c' name='IDGL_elem".$pagename."[".$name."][answer][correct]' $answer_correct_a_sel /> <strong class='correct_choice'>correct answer</strong>
				<hr/>
				
				<div style='margin-top:20px;font-weight:bold;'>Answer Option D</div>
				<textarea class='theEditor' style='width:700px;height:200px;' type='text' name='IDGL_elem".$pagename."[".$name."][answer][d][value]'>".htmlentities($current['answer']['d']['value'],ENT_QUOTES)."</textarea>  | <input type='radio' value='d' name='IDGL_elem".$pagename."[".$name."][answer][correct]' $answer_correct_a_sel /> <strong class='correct_choice'>correct answer</strong>
				<hr/>
				
				<div style='margin-top:20px;font-weight:bold;'>Answer Option E</div>
				<textarea class='theEditor' style='width:700px;height:200px;' type='text' name='IDGL_elem".$pagename."[".$name."][answer][e][value]'>".htmlentities($current['answer']['e']['value'],ENT_QUOTES)."</textarea>  | <input type='radio' value='e' name='IDGL_elem".$pagename."[".$name."][answer][correct]' $answer_correct_a_sel /> <strong class='correct_choice'>correct answer</strong>
				<hr/>
				
				
		</div>
		<div id='answer_dropdown' style='display:none;'>
			<div style='margin-top:10px;'>Enter the correct answer and the dropdown will be auto-generated</div>
			<input style='width:700px;' type='text' name='IDGL_elem".$pagename."[".$name."][answer][dropdown]' value='".$current['answer']['dropdown']."' />
		</div>
	</div>
	<script type='text/javascript'>
		jQuery(function(){
			jQuery('.answer_type').click(function(){
				jQuery('.answer_type').each(function(){
					jQuery('#'+jQuery(this).val()).hide();
				})
				jQuery('#'+jQuery('.answer_type:checked').val()).show();
			})
			jQuery('.answer_type:checked').trigger('click');
		})
	</script>";
	return $outStr;
}

function ecp_drill_questions_callback($param,$name,$pagename=""){
	eval('$args="'.$param.'";');
	$current=getPostMeta($_GET["post"],$name);
	
	$categories = get_terms( "qcategories");
	$cat_out="<select id='category_list'>";
	foreach($categories as $cat){
		treeOrder($cat->name,$cat->term_id,$cat->parent);
	}
	global $txTree;
	$cat_out.= print_to_list($txTree);
	$cat_out.="</select>";
	
	
	
	$postQuestions="<ul class='sliderList connectedSortable'>";
	if(is_array($current)){
		foreach($current as $pid){
			$post=get_post($pid);
			$postQuestions.= ecp_question_to_list_item($post);
		}
	}
	$postQuestions.="</ul>";
	
	$out="<div class='sortables-wrap'>
			<div syle='margin-bottom:10px;'>
				<p>Select from the list of available questions on the left and drag and drop the question that you would like to add to the drill to the right. </p>
				<p>Select a question category: $cat_out </p>
			</div>
			<div style='float:left;width:49%;' id='question_list'></div>
			<div style='float:right;width:50%;' id='selected_question_list'> <p>Question in drill:</p> $postQuestions</div>
		<div style='clear:both;'></div>
		</div>
	<script type='text/javascript'>
		jQuery(function(){
			jQuery('#category_list').change(function(e){
				jQuery.ajax({
					   type: 'POST',
					   url: ajaxurl+'?action=ecp_drill_questions_list',
					   data: 'cid='+jQuery('#category_list').val(),
					   success: function(msg){
						    jQuery('#question_list').html('<p>Available Questions:</p>'+msg)
						  
						    initRemoveQestion();
					   }
				});
			})
			jQuery('#category_list').trigger('change');
		})
		function initRemoveQestion(){
			
			  jQuery('.sortables-wrap ul').sortable({
			  										connectWith: '.sortables-wrap ul',
													stop: function(event, ui) {
																				jQuery('#selected_question_list li').each(function(i){
																					jQuery(this).find('input').remove();
																					var popstid=jQuery(this).attr('id').split('_')[1];
																					jQuery(this).append('<input type=\'hidden\' name=\'IDGL_elem".$pagename."[".$name."][]\' value=\''+popstid+'\' >')
																				})
																				}
													});
			jQuery('.remove_q').unbind('click').click(function(e){
				e.preventDefault();
				jQuery(this).parents('li:first').slideUp(500,function(){
					jQuery(this).remove();
				});
			})
			jQuery('.details_q').unbind('click').click(function(e){
				e.preventDefault();
				jQuery(this).parents('li:first').find('.details').slideToggle();
			})
		}
	</script>
	";
	return $out;
}
add_action('wp_ajax_ecp_drill_questions_list', 'ecp_drill_questions_list');
function ecp_drill_questions_list() {
	echo '<ul class="questionList connectedSortable">';
	$term=(get_term( $_POST["cid"], "qcategories"));
	query_posts(array(
					'posts_per_page'=>-1,
					'post_type'=>'questions',
					'qcategories'=>$term->slug
					));
	if ( have_posts() ){
		while ( have_posts() ) {
			the_post();
			global $post;
			echo ecp_question_to_list_item($post);
		}
	}
	
	wp_reset_query();
	echo "</ul>";
	die();
}
function ecp_question_to_list_item($post){
	
	//$terms = get_the_terms($post->ID, 'QCategories');
	//print_r($terms);
	
	$out="<li id='pid_$post->ID'>";
	$out.='<strong>'.get_the_title($post->ID).'</strong> ';
	$out.='<span style=\'float:right;display:inline-block;\'>
			<a href=\'#\' class=\'remove_q\'>remove</a> <a href=\'#\' class=\'details_q\'>details</a>
		   </span>';
	$out.="<hr/>";
	$out.="<div class='details' style='display:none;'>
			<strong>Question:</strong>
			".addslashes($post->post_content)." <br/>
			<span style='float:right;display:inline-block;'>
				<a href='".get_permalink($post->ID)."' target='_blank' >view</a> | 
				<a  href='".get_bloginfo("url")."/wp-admin/post.php?post=".$post->ID."&action=edit' target='_blank' >edit</a>
			</span>
			<div style='clear:both;'></div>
	</div>";
	$out.="</li>";
	return $out;
}
$txTree=array();
$newTxTree=array();
function treeOrder($name,$id,$parentID){
	global $txTree;
	if($parentID!=0){
			if(!is_array($txTree[$parentID]["children"])){
				$txTree[$parentID]["children"]=array();
			}
			$txTree[$parentID]["children"][$id]=array("name"=>$name,"id"=>$id,"parent"=>$parentID);
			$txTree[$id]=&$txTree[$parentID]["children"][$id];
	}else{
		$txTree[$id]=array("name"=>$name,"id"=>$id,"parent"=>0);
	}
}
function print_to_list($txTree){
	foreach($txTree as $item){
		if($item["parent"]==0){
			$out.="<option value='".$item["id"]."' >".$item["name"];
			$out.="</option>";
			if($item["children"]!=null){
				$out.=print_to_list_helper($item["children"],"&nbsp;&nbsp;&nbsp;".$separator);
			}
			
		}
	}
	return $out;
}
function print_to_list_helper($txTree,$separator){
	foreach($txTree as $item){
		$out.="<option value='".$item["id"]."'>".$separator.$item["name"];
			$out.="</option>";
		if($item["children"]!=null){
			$out.=print_to_list_helper($item["children"],"&nbsp;&nbsp;&nbsp;".$separator);
		}
	}
	return $out;
}
