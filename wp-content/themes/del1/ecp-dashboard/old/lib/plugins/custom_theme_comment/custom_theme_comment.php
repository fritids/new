<?php
 $commCounter=0;
function custom_theme_comment($comment, $args, $depth) {
	global $commCounter;
	$commCounter++;
	$GLOBALS['comment'] = $comment; 
   ?>

   <li><div class="commentPart" id="comment-<?php comment_ID(); ?>" >
           	    	<div class="left">
                        <?php echo get_avatar($comment,$size='70',$default='<path_to_url>' ); ?>
                        <h4><?php printf(__('%s'), get_comment_author_link()) ?></h4>
                        <small>
                        <?php 
                        	$entry_datetime = abs(strtotime(get_comment_date())); 
                        	echo date('M d, Y',$entry_datetime);
                        ?>
                    	</small>
                    </div>
                    <div class="right">
                        <?php comment_text() ?>
                    </div>
                    <div class="clirer"></div>
                </div></li>  
<?php
}
?>