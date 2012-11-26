<?php

/// Make sure that the user don't call this file directly - forces the use of the WP interface
function wpframe_stop_direct_call($file) {
	if(preg_match('#' . basename($file) . '#', $_SERVER['PHP_SELF'])) die('Don\'t call this page directly.'); // Stop direct call
}

/// Shows a message in the admin interface of Wordpress
function wpframe_message($message, $type='updated') {
	if($type == 'updated') $class = 'updated fade';
	elseif($type == 'error') $class = 'updated error';
	else $class = $type;
	
	print '<div id="message" class="'.$class.'"><p>' . __($message, $GLOBALS['wpframe_plugin_name']) . '</p></div>';
}

