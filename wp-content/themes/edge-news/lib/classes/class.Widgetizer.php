<?php
require_once "class.IDGL_Node.php";

/**
 * 
 * Rendering Widgets 
 * @author Abuzzy
 *
 */
class IDGL_Widgetizer{
	public static $currentWidget;
	
	/**
	 * 
	 * Constructor ...
	 */
	function IDGL_Widgetizer(){
	
	}
	
	
	/**
	 * 
	 * Makes widget from model EVAL!!
	 */
	public static function widgetize(){
		$models=IDGL_File::getFileList( dirname(__FILE__)."/../models/widgets/models/");
		$modelJsArr="";
		foreach($models as $k=>$model){
			IDGL_Widgetizer::$currentWidget=$model;
			if(!is_file(dirname(__FILE__)."/../models/widgets/models/".$model)){
				continue;
			}
			$plugin=substr($model,0,strpos($model,"."));
			$fl=IDGL_Config::getConfig(dirname(__FILE__)."/../models/widgets/models/".$model);
			$className="IDGL_".str_replace(".","_",$model);
			$finalClass='class '.$className.' extends WP_Widget{
				private $type="'.$plugin.'";
				function '.$className.'(){
					parent::WP_Widget(false, $name = "*'. str_replace(".xml","",IDGL_Widgetizer::$currentWidget).'");	
				}
				function form( $instance ) {
					$defaults = array();
					$instance = wp_parse_args( (array) $instance, $defaults ); 
					';
					$finalClass.='echo \'<div onmouseover="initNode(this)" class="IDGL_listNode" style="">\';';
					foreach($fl as $node){
						$nd=new IDGL_Node($node);
						$finalClass.='echo \'<div class="IDGL_'.$nd->getType().'">\';
						echo \'<label>'.$nd->attributes["label"].'</label>\'; 
						';
						//var_dump($node);
						switch($nd->getType()){
							case "Image":
								/*$filepath=$nd->getName();
								if($filepath==""){
									echo "**";
									$filepath=$nd->attributes["default"];
								}
								echo $filepath;*/
								$finalClass.= '
								$filepath=$instance[\''.$nd->getName().'\'];
								if($filepath==""){
									$filepath=\''.$nd->attributes["default"].'\';
								}
								echo \'
									<div  class="imageWrap" style="min-width:'.$nd->attributes["width"].'px;min-height:'.$nd->attributes["height"].'px;">
										<img class="elem" src="\'.$filepath.\'"  width="'.$nd->attributes["width"].'" height="'.$nd->attributes["height"].'"  _scalex="'.$nd->attributes["width"].'" _scaley="'.$nd->attributes["height"].'" />
										<div>
							   			<input type="hidden" class="hiddenFld" name="\'.$this->get_field_name( \''.$nd->getName().'\' ) .\'" value="\'.$filepath.\'" />
							   			<a id="\'.$this->get_field_id( \''.$nd->getName().'\' ) .\'" class="button uploadify" href="#">Browse Your PC</a>
										</div>
									</div>\';';
								break;
							default:
								$finalClass.='echo \'<input class="widefat elem" type="text" value="\'.$instance[\''.$nd->getName().'\'].\'" id="\'.$this->get_field_id( \''.$nd->getName().'\' ) .\'"  name="\'.$this->get_field_name( \''.$nd->getName().'\' ) .\'" />\';';
								break;
						}
						$finalClass.='echo \'</div>\';';
					}
					
					$templateArr=IDGL_File::getFileList(dirname(__FILE__)."/../models/widgets/templates/".$plugin);
					if(count($templateArr)>1){
						$finalClass.='echo \'<label>template :</label>
						<select  class="widefat elem" style="width:100%;" id="\'.$this->get_field_id( \'widgetTemplate\' ).\'" name="\'.$this->get_field_name( \'widgetTemplate\' ) .\'">\';';
						
						foreach($templateArr as $template){
							$finalClass.='if($instance["widgetTemplate"]=="'.$template.'"){
												echo \'<option selected="selected" value="'.$template.'" >'.$template.'</option> \';
											}else{
												echo \'<option value="'.$template.'" >'.$template.'</option> \';
											}';
						}
						$finalClass.='echo \'</select>\';';
					}else{
						//wtf - $templateArr[0] wont print
						//echo ();
						//foreach($templateArr as $template){
							$finalClass.='echo \'<input type="hidden" value="template.xsl"  name="\'.$this->get_field_name( \'widgetTemplate\' ) .\'" />\';';
						//}
					}
					$finalClass.='echo \'</div>\';';				
					$finalClass.='
				}
				function update( $new_instance, $old_instance ) {
					$instance = $old_instance; ';
					foreach($fl as $node){
						//echo $nd->getName()."<br/>";
						$nd=new IDGL_Node($node);
						$finalClass.='$instance["'.$nd->getName().'"] = $new_instance["'.$nd->getName().'"];';
					}
					$finalClass.='$instance["widgetTemplate"] = $new_instance["widgetTemplate"];';
					$finalClass.='return $instance;
				}
				function widget( $args, $instance ) {
					extract( $args );
					echo $before_widget;
					//print_r($instance);
					$XML = new DOMDocument();
					$XML->load( dirname(__FILE__)."/../models/widgets/models/'.$plugin.'.xml" );
					$xslt = new XSLTProcessor();
					$XSL = new DOMDocument();
					$XSL->load( dirname(__FILE__)."/../models/widgets/templates/'.$plugin.'/".$instance["widgetTemplate"], LIBXML_NOCDATA);';
					foreach($fl as $node){
						$nd=new IDGL_Node($node);
						$finalClass.='$xslt->setParameter("", "'.$nd->getName().'", $instance["'.$nd->getName().'"]);';
					}
					$finalClass.='
					$xslt->importStylesheet( $XSL );
					echo $xslt->transformToXML( $XML );
					echo $after_widget;
				}
			}';
			eval($finalClass);
			register_widget($className);
		}
	}
}

// NEZNAM ZASTO E OVA POTREBno

class IDGL_Widget extends WP_Widget {
    function IDGL_Widget() {
        parent::WP_Widget(false, $name = IDGL_Widgetizer::$currentWidget);	
    }

    function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$name = $instance['name'];

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		if ( $name )
			printf( '<p>' . __('Hello. My name is %1$s.', 'example') . '</p>', $name );
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance["title"] = strip_tags( $new_instance["title"] );
		$instance["name"] = strip_tags( $new_instance["name"] );

		return $instance;
	}
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Example', 'example'), 'name' => __('John Doe', 'example'), 'sex' => 'male', 'show_sex' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<!-- Your Name: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('Your Name:', 'example'); ?></label>
			<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:100%;" />
		</p>
	<?php
	}

}
