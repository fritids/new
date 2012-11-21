<?php
class PostType{
	public static function register($filepaths=null){
		if($filepaths!=null){
			$postsArr=$filepaths;
		}else{
			$postsArr=IDGL_File::getFileList(dirname(__FILE__)."/../models/add/");
		}
		foreach($postsArr as $postTypeFileName){
			$postType=explode(".",$postTypeFileName);
			$postType=$postType[0];
			
			$labels = array(
			    'name' => _x(substr($postType,0,11), 'post type general name'),
			    'singular_name' => _x($postType, 'post type singular name'),
			    'add_new' => _x('Add New', $postType),
			    'add_new_item' => __('Add New '.$postType),
			    'edit_item' => __('Edit '.$postType),
			    'new_item' => __('New '.$postType),
			    'view_item' => __('View '.$postType),
			    'search_items' => __('Search '.$postType),
			    'not_found' =>  __('No '.$postType.' found'),
			    'not_found_in_trash' => __('No '.$postType.' found in Trash'), 
			    'parent_item_colon' => ''
			  );
			 $args = array(
			    'labels' => $labels,
			    'public' => true,
			    'publicly_queryable' => true,
			    'show_ui' => true, 
			    'query_var' => true,
			    'rewrite' => true,
			    'capability_type' => 'post',
			    'hierarchical' => true,
			    'menu_position' => 100,
			    'supports' => array('title','editor','page-attributes')
			  );
			register_post_type($postType,$args); 
		}
	}
}
/*
 * 
'title'
'editor' (content)
'author'
'thumbnail' (featured image) (current theme must also support post-thumbnails)
'excerpt'
'trackbacks'
'custom-fields'
'comments' (also will see comment count balloon on edit screen)
'revisions' (will store revisions)
'page-attributes' (template and menu order) (hierarchical must be true)
 */