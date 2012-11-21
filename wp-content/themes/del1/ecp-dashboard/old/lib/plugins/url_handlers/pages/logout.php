<?php
require_once(IDG_PLUGINS_PATH."url_handlers/classes/BUsers.php");
$user = new BUser();
$user->logout();
wp_redirect(get_bloginfo("url")."/login/");
?>