<?php

//wp_register_script('jquery.galleria.pack.js',get_bloginfo('template_url').'/lib/plugins/LaGalleria/js/jquery.galleria.pack.js'); 
//wp_enqueue_script('jquery.galleria.pack.js');

class LaGalleria{
	public static function render($galArray){
		
		$outStr="<div id='galleria'>";
		//print_r($galArray);
		//echo "***";
		if(is_array($galArray)){
			foreach($galArray as $image){
				$thumb=str_replace("img_","thumb_img_",$image);
				$outStr.= "<img src='".$image."' />";
				$outStr.= "<p>sample title</p>";
			}
		}
		$outStr.="</div>
		<script type='text/javascript' src='".get_bloginfo('template_url')."/lib/plugins/LaGalleria/js/jquery.galleria.pack.js'></script> 
		<script type='text/javascript'>
		    // Galleria.loadTheme('".get_bloginfo('template_url')."/lib/plugins/LaGalleria/js/themes/lightbox/galleria.lightbox.js');
			Galleria.loadTheme('".get_bloginfo('template_url')."/lib/plugins/LaGalleria/js/themes/classic/galleria.classic.js');
		    jQuery('#galleria').galleria({
		        image_crop: true,
		        transition: 'fade',
		        data_config: function(img) {
		            return {
		                description: jQuery(img).next('p').html(),
		                altVideo: jQuery(img).attr('alt')
		            };
		        }   
		    });
		    </script>
		";
		return $outStr;
	}
}