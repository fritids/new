<?php

/**
 * 
 * 
 * Widget Register Class...
 * @author Abuzz
 *
 *
 */
class IDGL_WidgetAreas{
	
	
	/**
	 * 
	 * Registers Sidebar for Model fetched from XML...
	 */
	
	public static function register_widget_areas(){
		if ( !function_exists('register_sidebars') )
		return;
		$xmlConf=new SimpleXMLElement( file_get_contents(IDG_WIDGETS_CONF_FILE));
		foreach($xmlConf->IDGLWidgetAreas[0]->widget as $widget){
			register_sidebar(array(
				'name' => (string)$widget["name"],
				'id' => (string)$widget["name"],
				'before_widget' => '<div id="%1$s'.$widget["name"].'" class="widget widgetcontainer %2$s'.$widget["name"].'">',
				'after_widget' => "</div>",
				'before_title' =>$widget->options->before_title,
				'after_title' => $widget->options->after_title
			));
		}
	}
}
?>