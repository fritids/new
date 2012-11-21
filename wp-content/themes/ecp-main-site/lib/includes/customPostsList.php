<?php
add_filter('posts_where', 'geotag_search_where' );
function geotag_search_where( $join )
{
	global $current_user;
	get_currentuserinfo();
  	global $wpdb;
    $join .= " AND " .  $wpdb->posts . ".post_author = '" . $current_user->ID."'";
  	return $join;
}
add_filter('manage_posts_columns', 'scompt_custom_columns');
function scompt_custom_columns($defaults) {
    unset($defaults['author']);
    return $defaults;
}
add_action('restrict_manage_posts', 'scompt_restrict_manage_posts');
function scompt_restrict_manage_posts() {

}
add_action('admin_menu', 'mofifyPost');
function mofifyPost(){
	add_meta_box( "Recipe Details", __( "Recipe Details", "Recipe Details" ), showCustomFields, 'post', 'advanced' );
	add_meta_box( "Recipe Details", __( "Recipe Details", "Recipe Details" ),showCustomFields, 'page', 'advanced' );
	remove_meta_box('postexcerpt','post','core');
	remove_meta_box('trackbacksdiv','post','core');
	remove_meta_box('postcustom','post','core');
	remove_meta_box('commentstatusdiv','post','core');
	remove_meta_box('revisionsdiv','post','core');
}
	
?>