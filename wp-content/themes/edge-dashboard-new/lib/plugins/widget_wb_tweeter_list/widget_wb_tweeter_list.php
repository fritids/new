<?php
$widgetName="widget_twitter_feed";
class wb_tweeter_list extends WP_Widget {
	function wb_tweeter_list() {
		global $widgetName;
		parent::WP_Widget(false, $name = $widgetName);	
	}
	function form($instance) {
		$title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$NoLinks = esc_attr($instance['NoLinks']);
		$timestamps = esc_attr($instance['timestamps']);
		$linked = esc_attr($instance['linked']);
		
		$replies = esc_attr($instance['replies']);
		$hyperlinks = esc_attr($instance['hyperlinks']);
		
		
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('NoLinks'); ?>"><?php _e('Number of links:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('NoLinks'); ?>" name="<?php echo $this->get_field_name('NoLinks'); ?>" type="text" value="<?php echo $NoLinks; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('linked'); ?>"><?php _e('Linked:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('linked'); ?>" name="<?php echo $this->get_field_name('linked'); ?>" type="text" value="<?php echo $linked; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('timestamps'); ?>"><?php _e('Show timestamps:'); ?> <input id="<?php echo $this->get_field_id('timestamps'); ?>" name="<?php echo $this->get_field_name('timestamps'); ?>" type="checkbox" <?php if($timestamps!=""){ echo "checked='checked'"; } ?> /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('hyperlinks'); ?>"><?php _e('Discover Hyperlinks:'); ?> <input id="<?php echo $this->get_field_id('hyperlinks'); ?>" name="<?php echo $this->get_field_name('hyperlinks'); ?>" type="checkbox" <?php if($hyperlinks!=""){ echo "checked='checked'"; } ?> /></label></p>
            <p><label for="<?php echo $this->get_field_id('replies'); ?>"><?php _e('Discover @replies:'); ?> <input id="<?php echo $this->get_field_id('replies'); ?>" name="<?php echo $this->get_field_name('replies'); ?>" type="checkbox" <?php if($replies!=""){ echo "checked='checked'"; } ?> /></label></p>
        <?php 
	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function widget($args, $instance) {
		$title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$NoLinks = esc_attr($instance['NoLinks']);
		$timestamps = esc_attr($instance['timestamps']);
		$linked = esc_attr($instance['linked']);
		$replies = esc_attr($instance['replies']);
		$hyperlinks = esc_attr($instance['hyperlinks']);
		
		$twittread=get_site_option("twitter_readx");
			
		if(date("G")!=$twittread){
		
			add_site_option("twitter_read",date("G"));
			$newContent=file_get_contents("http://twitter.com/statuses/user_timeline/$username.rss");
			file_put_contents(dirname(__FILE__)."/tweeterCache.xml",$newContent);
		}else{
			$newContent=file_get_contents(dirname(__FILE__)."/tweeterCache.xml");
		}
		$fileCon=new SimpleXMLElement($newContent);
		if($title!=""){
			echo "<h2 class='twitterHead'>$title</h2>";
		}
		echo "<ul class='twitter' style='overflow:hidden;'>";
		$counter=0;
		foreach($fileCon->channel->item as $item){
			if($counter>=$NoLinks){
				break;
			}
			echo '<li class="twitter-item">';
			
			$content=substr($item->title,strpos($item->title,":")+1);
			if($hyperlinks!=""){
				$content=$this->wb_tweeter_hyperlinks($content);
			}
			if($replies!=""){
				$content=$this->wb_tweeter_users($content);
			}	
			echo $content;
			echo '<a class="twitter-link" href="'.$item->link.'">'.$linked.'</a>';
			if($timestamps!=""){
				echo '<span class="twitter-timestamp">
						<abbr title="2010/04/19 21:53:50">'. date("Y/m/d H:i:s",strtotime($item->pubDate)) .'</abbr>
					</span>';
			}
			echo '</li>';
			$counter++;
		}
		echo "</ul>";
		echo '<a class="follow" href="http://twitter.com/'.$username.'">follow me</a>';
	}
	 function wb_tweeter_hyperlinks($text) {
	    $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);    
	    $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);
	    return $text;
	}
	function wb_tweeter_users($text) {
       $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
       return $text;
	} 
}
add_action( 'widgets_init', 'wb_load_widgets' );
function wb_load_widgets() {
	register_widget( 'wb_tweeter_list' );
}
?>