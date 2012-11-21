<?php

$loc=dirname(__FILE__);
define('IDG_ROOT', $loc);
define('IDG_PLUGINS_PATH', $loc . '/lib/plugins/');
define('IDG_THEMEASSETS', $loc . '/lib');
define('IDG_CLASS_PATH', TEMPLATEPATH . '/lib/classes/');
define("IDG_POST_CONF_FILE", $loc."/lib/models/post.xml");
define("IDG_PAGE_CONF_FILE", $loc."/lib/models/page.xml");
define("IDG_THEME_CONF_FILE", $loc."/lib/models/theme.xml");
define("IDG_WIDGETS_CONF_FILE", $loc."/lib/models/widgets.xml");
define("IDG_USERS_CONF_FILE", $loc."/lib/models/users.xml");
define("IDG_COMMENTS_CONF_FILE", $loc."/lib/models/comment.xml");
define("IDGL_THEME_URL", get_bloginfo('stylesheet_directory'));

require_once(TEMPLATEPATH.'/lib/init.php');
IDGLoader::loadLibraries();
IDGLoader::loadPlugins();

PostType::register(IDGL_File::getFileList(dirname(__FILE__)."/lib/models/add/"));
add_action( 'widgets_init', 'IDGL_WidgetAreas::register_widget_areas' );
add_action('admin_menu', 'IDGL_ThemePages::IDGL_addPages');
$load_conf="false";

?>