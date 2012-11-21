<?php get_header(); 

$is_review=false;
if(isset($_GET["mode"]) && $_GET["mode"]="review"){
	$is_review=true;	
}
if ( have_posts() ){
	while ( have_posts() ) {
		the_post();
		$question_id=getPostMeta($post->ID,"ecp_drill_questions");
		$title=get_the_title($post->ID);
		$drill_id=$post->ID;
	}
}
?>
UTRE ME RAZBIRAS
<?php if(!$is_review){?>
<form method="POST" action="<?php bloginfo("url")?>/process-drill/">
		<input type="hidden" name="drill_id" value="<?php echo $drill_id; ?>" />
		<input type="hidden" name="drill_begin" value="<?php echo time(); ?>" />
        <div class="leftcolumn widecolumn">
			<h1 class="left"><?php echo $title; ?></h1>
            <div class="stepcounter right">
            	<a href="#" class="active">1</a>
                <a>2</a>
                <a>3</a>
            </div>
            <div class="qcounter">
            	<?php 
            	for($i=0; $i<count($question_id);$i++){
            		echo '<div class="qnumber"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a></div>';
            	}
            	?>
            </div>            
            <div class="greybox">
            	
            	<?php for($i=0; $i<count($question_id);$i++){ 
            		$post_data=get_post($question_id[$i]);
            		
            		$options=getPostMeta($post_data->ID,"ecp_answer");
            		$answer_type=$options["answer_type"];
            		$answer=$options["answer"];
            		
            		$qcontent=getPostMeta($post_data->ID,"Question");
            		$quid=$question_id[$i];
            	?>
	            	<div class='qpanel' id='qpanel_<?php echo $quid; ?>' style="display:none;">
		            	<h3><?php echo $qcontent; ?></h3>
		            	<ul class="test_qs">
		                    <?php 
		                    	echo "<li val='a'>".$answer["a"]["value"]."</li>";
		                    	echo "<li val='b'>".$answer["b"]["value"]."</li>";
		                    	echo "<li val='c'>".$answer["c"]["value"]."</li>";
		                    	echo "<li val='d'>".$answer["d"]["value"]."</li>";
		                    	echo "<li val='e'>".$answer["e"]["value"]."</li>";
		                    ?>
		                </ul>
	                </div>
                <?php } ?>
                <div class="mainControlls">
					<a id="mark_for_review" class="button smalltext"><label><input name="" type="checkbox" class="checkbox" value="" /> Mark for Review</label></a>
	                <a href="#" id="btn_save_question" class="button green">Save</a>     
	                <a id="btn_prev" href="#" class="button blue">Prev</a>
	                <a id="btn_next" href="#" class="button blue">Next</a>
	                <input type="submit" value="Submit ALL QUESTIONS" class="button right" />
                </div>
            </div>
			<div class="clear"></div>
        </div>
	<div class="clear"></div>
</form>
<?php }else{ 
//require_once(IDG_ECP."ECPUser.php");
$data=ECPUser::getUserAttribute(ECPUser::getUserID(),"selftest_".$drill_id);
$data=$data[0];
?>
<div class="leftcolumn widecolumn">
			<h1 class="left"><?php echo $title; ?><a href="#note_scores" class="note_scores">Scores</a></h1>
            <div class="stepcounter right">
            	<a href="#" class="active">1</a>
                <a>2</a>
                <a>3</a>
            </div>
            <div class="qcounter">
            	<?php 
            	for($i=0; $i<count($question_id);$i++){
            		$options=getPostMeta($question_id[$i],"ecp_answer");
            		if($data["answers"][$question_id[$i]]==null){
            			echo '<div class="qnumber unanswered"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span>unanswered</span></div>';
            		}else if($data["answers"][$question_id[$i]]==$options["answer"]["correct"]){
            			echo '<div class="qnumber correct"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span>correct</span></div>';
            		}else{
            			echo '<div class="qnumber incorrect"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span>incorrect</span></div>';
            		}
            	}
            	?>
            </div>            
            <div class="greybox">
            	<div class='qpanel' id='qpanel_scores' style="display:none;">
            		<h3>Your Total Score is <span class="dropka"><span class="top"><?php echo $data["correct_count"] ?></span><span class="bottom"><?php echo $data["question_count"] ?></span></span></h3>
            		<p>Test started on <?php echo date("F j, Y, g:i a",$data["drill_begin"])?> and ended on  <?php echo date("F j, Y, g:i a",$data["drill_end"])?></p>
            		<p>For <strong>Explanations</strong> please click on the individial question numbers above</p>
            	</div>
            	<?php for($i=0; $i<count($question_id);$i++){ 
            		$post_data=get_post($question_id[$i]);
            		$options=getPostMeta($question_id[$i],"ecp_answer");
            		
            		$answer_type=$options["answer_type"];
            		$answer=$options["answer"];
            		
            		$qcontent=getPostMeta($post_data->ID,"Question");
            		$quid=$question_id[$i];
            	?>
	            	<div class='qpanel' id='qpanel_<?php echo $quid; ?>' style="display:none;">
		            	<h3><?php echo $qcontent; ?></h3>
		            	<ul class="test_qs">
		                    <?php 
		                    	$corr_a="";
		                    	$corr_b="";
		                    	$corr_c="";
		                    	$corr_d="";
		                    	$corr_e="";
		                    			                    	
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="a"){ $corr_a="<span class='correct'>y</span>"; }else if($data["answers"][$question_id[$i]]=="a"){ $corr_a="<span class='correct'>x</span>"; }
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="b"){ $corr_b="<span class='correct'>y</span>"; }else if($data["answers"][$question_id[$i]]=="b"){ $corr_b="<span class='correct'>x</span>"; }
		                 		if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="c"){ $corr_c="<span class='correct'>y</span>"; }else if($data["answers"][$question_id[$i]]=="c"){ $corr_c="<span class='correct'>x</span>"; }
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="d"){ $corr_d="<span class='correct'>y</span>"; }else if($data["answers"][$question_id[$i]]=="d"){ $corr_d="<span class='correct'>x</span>"; }
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="e"){ $corr_e="<span class='correct'>y</span>"; }else if($data["answers"][$question_id[$i]]=="e"){ $corr_e="<span class='correct'>x</span>"; }
            	
		                    	echo "<li val='a'>".$corr_a.$answer["a"]["value"]."</li>";
		                    	echo "<li val='b'>".$corr_b.$answer["b"]["value"]."</li>";
		                    	echo "<li val='c'>".$corr_c.$answer["c"]["value"]."</li>";
		                    	echo "<li val='d'>".$corr_d.$answer["d"]["value"]."</li>";
		                    	echo "<li val='e'>".$corr_e.$answer["e"]["value"]."</li>";
		                    ?>
		                </ul>
	                </div>
                <?php } ?>
                <div class="mainControlls">    
	                <a id="btn_prev" href="#" class="button blue">Prev</a>
	                <a id="btn_next" href="#" class="button blue">Next</a>
                </div>
            </div>
			<div class="clear"></div>
        </div>

<?php } ?>

<style type="text/css">
	.test_qs li{
		cursor: pointer;
	}
</style>
<script type="text/javascript">
jQuery(function(){
	jQuery(".qcounter a, .note_scores").click(function(e){
		e.preventDefault();
		var panel_id="#qpanel_"+jQuery(this).attr("href").split("_")[1];
		jQuery(".qpanel").hide();
		jQuery(panel_id).show();
		jQuery(this).parents(".qcounter").find("a").removeClass("current");
		jQuery(this).addClass("current");

		jQuery(panel_id+" .test_qs li").removeClass("selected");
		jQuery("#mark_for_review input").removeAttr("checked");

		if(jQuery(this).parent().hasClass("review")){
			jQuery("#mark_for_review input").attr("checked","checked");
		}
		
		if(jQuery(this).parent().find("input").length>0){
			var sel=jQuery(this).parent().find("input").val();
			jQuery(panel_id+" .test_qs li[val='"+sel+"']").addClass("selected");
		}
	})
	jQuery(".qcounter a:first").trigger("click");
	jQuery(".test_qs li").click(function(e){
		jQuery(this).parents("ul:first").find("li").removeClass("selected");
		jQuery(this).addClass("selected");
	})
	jQuery("#btn_save_question").click(function(e){
		e.preventDefault();

		var panel_id="#qpanel_"+jQuery(".qcounter a.current").attr("href").split("_")[1];
		if(jQuery(panel_id+" .test_qs li.selected").length==0){ return; }

		jQuery(".qcounter a.current").parent().removeClass("review").addClass("answered");
		jQuery(".qcounter a.current").parent().find("input").remove();
		jQuery(".qcounter a.current").parent().find("span").remove();
		jQuery(".qcounter a.current").parent().append("<span>answered</span>");
		var qid=jQuery(".qcounter a.current").attr("href").split("_")[1];
		var ans=jQuery(panel_id+" .test_qs li.selected").attr("val")
		jQuery(".qcounter a.current").parent().append("<input type='hidden' value='"+ans+"' name='uans["+qid+"]' />");
		jQuery("#mark_for_review input").removeAttr("checked");
	})
	jQuery("#mark_for_review input").change(function(e){
		if(jQuery(this).attr("checked")){
			var panel_id="#qpanel_"+jQuery(".qcounter a.current").attr("href").split("_")[1];
			jQuery(".qcounter a.current").parent().removeClass("answered").addClass("review");
			if(jQuery(".qcounter a.current span").length>0){
				jQuery(".qcounter a.current span").html("review");
				jQuery(".qcounter a.current input").remove();
			}else{
				jQuery(".qcounter a.current").parent().append("<span>review</span>");
			}
		}else{
			jQuery(".qcounter a.current span").html("review");
			jQuery(".qcounter a.current input").remove();
			jQuery(".qcounter a.current").parent().removeClass("review");
		}
	})
	jQuery("#btn_prev").click(function(e){
		e.preventDefault();
		jQuery(".qcounter a.current").parent().prev().find("a").trigger("click");
	})
	jQuery("#btn_next").click(function(e){
		e.preventDefault();
		jQuery(".qcounter a.current").parent().next().find("a").trigger("click");
	})
})
</script>

<?php get_footer(); ?>