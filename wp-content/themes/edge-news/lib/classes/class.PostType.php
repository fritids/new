<?php

/**
 * 
 * 
 * Post type Class ...
 * @author Abuzz
 *
 */

class PostType{
	
	/**
	 * 
	 * Registers custom post type from models...
	 * @param string $filepaths
	 */
	public static function register($filepaths=null){
		if($filepaths!=""){
			$postsArr=$filepaths;
		}else{
			$postsArr=IDGL_File::getFileList(dirname(__FILE__)."/../models/add/");
		}
		foreach($postsArr as $postTypeFileName){
			$postType=explode(".",$postTypeFileName);
			$postType=$postType[0];
			
			$labels = array(
			    'name' => _x($postType, 'post type general name'),
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
			    'menu_position' => null,
			    'supports' => array('title','editor')
			  );
			  if($postType=="faq"){
			  	$args=array_merge($args,array('supports' => array('page-attributes','title','editor')));
			  }
			register_post_type($postType,$args); 
		}
	}
}