<?php
PostType::register(IDGL_File::getFileList(dirname(__FILE__) . "/models/"));

register_taxonomy('ecp-products', 'ecpproduct', array('hierarchical' => true, 'label' => __('ECP Product Categories'), 'query_var' => true, 'rewrite' => true));

add_action('admin_menu', 'ecp_products_add');

function ecp_products_add()
{
	if(isset($_GET["post_type"]))
	{
		$currEditType = $_GET["post_type"];
	}
	else
	{
		$currEditType = get_post_type($_GET["post"]);
	}
	if(strtolower($currEditType) != "ecpproduct")
	{
		return;
	}
	
	$postOptions = IDGL_Config::getConfig(dirname(__FILE__) . "/models/" . $currEditType . ".xml");
	foreach($postOptions as $page)
	{
		$name = $page["name"];
		$title = $page["title"];
		if(isset($_GET["action"]) && $_GET["action"] == "edit")
		{
			IDGL_PostOptionManages::IDGL_addPostOptionFunction($name, $page);
			add_meta_box($name, __($title, $title), "IDGL_fx_" . $name, $currEditType, 'advanced', 'high');
		}
	}
}

function ecp_product_dicount_callback($param, $name, $pagename = "")
{
	eval('$args="' . $param . '";');
	$current = getPostMeta($_GET["post"], $name);
	
	$out = "<div class='IDGL_listNode'>";
	$out .= "<div><span class='label'><big>Enter discount ammount to products when selected :</big></span>";
	
	$current_post_arr = wp_get_object_terms($_GET["post"], 'ecp-products');
	$current_post_parent = $current_post_arr[0] -> parent;
	$children_of_the_post = get_term_children( $current_post_arr[0] -> term_id, $current_post_arr[0] -> taxonomy);
	
	$ids = array();
	foreach($current_post_arr as $term)
	{
		$ids[] = $term -> term_id;
	}
	
	query_posts(array('posts_per_page'=>'-1','post_type' => 'ecpproduct', 'post__not_in' => array($_GET["post"])));
	if(have_posts()):
		while(have_posts()):
			the_post();
			global $post;
			//echo get_the_title($post->ID);
			
			$cat = wp_get_object_terms($post -> ID, 'ecp-products');
			if($cat[0] -> term_id == $current_post_arr[0] -> term_id){ continue;}
			$tax_id = $cat[0] -> term_taxonomy_id;
			if(in_array($tax_id, $children_of_the_post))
			{
				$title = get_the_title($post -> ID);
				$out .= "<span class='label'> Discount to product <big>$title</big>:</span>
				<input class='elem' type='text' name='IDGL_elem" . $pagename . "[" . $name . "][" . $post -> ID . "]' id='IDGL_elem" . $pagename . "[" . $name . "][" . $post -> ID . "]' value='" . htmlentities($current[$post -> ID],ENT_QUOTES) . "' />";
			}
			
		endwhile;
	
	endif;
	wp_reset_query();
	
	$out .= "</div></div>";
	return $out;
}

function ecp_product_quantity_callback($param, $name, $pagename="")
{
}

