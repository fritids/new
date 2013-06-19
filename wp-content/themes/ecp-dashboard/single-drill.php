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

$test_data=ECPUser::getUserAttribute(ECPUser::getUserID(),"selftest_".$drill_id);


function cmp($a, $b)
{
    if ($a["drill_begin"] == $b["drill_begin"]) {
        return 0;
    }
    return ($a["drill_begin"] < $b["drill_begin"]) ? -1 : 1;
}
usort($test_data, "cmp");

//print_r($test_data);

$trials_count=count($test_data);
if($trials_count>=3){
	$is_review=true;
}
$current_trial=$trials_count-1;
if(isset($_GET["trial"])){
	$current_trial=$_GET["trial"];
}
$data=$test_data[$current_trial];

?>
<?php edit_post_link('Edit Drill', '<p>', '</p>'); ?>
<?php if(!$is_review){?>
<form method="POST" action="<?php bloginfo("url")?>/process-drill/">
		<input type="hidden" name="drill_id" value="<?php echo $drill_id; ?>" />
		<input type="hidden" name="drill_begin" value="<?php echo time(); ?>" />
        <div class="leftcolumn widecolumn">
			<h1 class="left"><?php echo $title; ?></h1>
            <div class="stepcounter clearfix">
				<div class="stepcounter-inner">
            	<?php 
            		for($i=0;$i<$trials_count;$i++){
            			if($current_trial==$i){
            				echo '<a href="?mode=review&trial='.$i.'" class="active">'.($i+1).'</a>';
            			}else{
            				echo '<a href="?mode=review&trial='.$i.'">'.($i+1).'</a>';
            			}
            		}
            	?>
				</div>
            </div>
            <div class="qcounter clearfix">
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
            		
            		$qcontent=$post_data->post_content;
            		$quid=$question_id[$i];
            	?>
            		
				<div class='qpanel' id='qpanel_<?php echo $quid; ?>' style="display:none;">
				<?php edit_post_link('Edit Question', '<p>', '</p>',$quid); ?>
				<h3><?php echo apply_filters('the_content', $qcontent); ?><?php //echo $qcontent; ?></h3>
				<!-- Question passgae begin -->
					<?php
					$qpassage=getPostMeta($post_data->ID,"ecp_question_passage");
					if($qpassage["passage_type"]!="" && ($qpassage["plain_passage"]!="" || $qpassage["numbered_passage"]!="")){?>
						<div class="passage-container clearfix">
							<?php 
								$qpassage=getPostMeta($post_data->ID,"ecp_question_passage");
								if($qpassage["passage_type"]=="plain"){
									echo "<div class='plain passage' style='display:none'>".$qpassage["plain_passage"]."</div>";
								}elseif($qpassage["passage_type"]=="numbered"){
									$passage=$qpassage["numbered_passage"];
									$right_div="";
									$passage_rows=explode("\n",$passage);
									$count=1;
									foreach($passage_rows as $row){
										if($count%5==0){
											$right_div.="<div>line-".$count."</div>";
										}else{
											$right_div.="<br/>";
										}
										$count++;
									}
									?>
									<div class='numbered passage' style='display:none'>
										<div class='passage-numbers' style="float:left; text-align:right; padding-right:5px; border-right:solid 1px #cacaca;margin-right:5px;"><?php echo $right_div; ?></div>
										<div class='passage-content' style="float:left; width:800px;"><?php echo nl2br($qpassage["numbered_passage"]); ?></div>
										<div><strong>
											<?php echo $qpassage["after_passage_description"]; ?>
										</strong></div>
									</div>
							<?php } ?>

							<a href="#" class="button blue view_passage">View Passage</a>
						</div>
					<?php } ?>
					<!-- Question passgae end -->

					<!-- Question Answers begin -->
					<?php if($answer_type=="answer_plain"){?>
					<ul class="test_qs">
						<?php 
							echo "<li val='a'><span>A</span>".$answer["a"]["value"]."</li>";
							echo "<li val='b'><span>B</span>".$answer["b"]["value"]."</li>";
							echo "<li val='c'><span>C</span>".$answer["c"]["value"]."</li>";
							echo "<li val='d'><span>D</span>".$answer["d"]["value"]."</li>";
							echo "<li val='e'><span>E</span>".$answer["e"]["value"]."</li>";
						?>
					</ul>
					 <?php }else if($answer_type=="answer_dropdown"){ ?>
						<div class="clear" style="margin-bottom:15px;"></div>
					 <?php 
						$chars=array(" ","/",".","0","1","2","3","4","5","6","7","8","9");
						for($j=0;$j<strlen($answer["dropdown"]);$j++){
							echo "<select class='partial_value'>";	
							foreach($chars as $char){
								echo "<option value='$char'>$char</option>";	
							}
							echo "</select>";	
						}?>
						<div class="clear" style="margin-bottom:15px;"></div>
					<?php }elseif($answer_type=="answer_range"){ ?>
								<div class="clear" style="margin-bottom:15px;"></div>
					 <?php 
						$chars=array(" ","/",".","0","1","2","3","4","5","6","7","8","9");
						for($j=0;$j<strlen($answer["range_to"]);$j++){
							echo "<select class='partial_value'>";	
							foreach($chars as $char){
								echo "<option value='$char'>$char</option>";	
							}
							echo "</select>";	
						}?>
						<div class="clear" style="margin-bottom:15px;"></div>
					<?php }?>
					<!-- Question Answers end -->
				</div>
                <?php } ?>
                
                <div class="mainControlls clearfix">
					<a id="mark_for_review" class="button smalltext"><label><input name="" type="checkbox" class="checkbox" value="" /> Mark for Review</label></a>
	                <!--a href="#" id="btn_save_question" class="button green">Save</a-->     
	                <a id="btn_prev" href="#" class="button orange">Prev</a>
	                <a id="btn_next_save" href="#" class="button orange">Next</a>
	                <input id="btn_submit" type="submit" value="Submit ALL QUESTIONS" class="button green right" />
                </div>
            </div>
        </div>
</form>
<?php }else{ ?>
<div class="leftcolumn widecolumn">
			<h1 class="left"><?php echo $title; ?><a href="#note_scores" class="button orange note_scores">Scores</a></h1>
            <div class="stepcounter clearfix">
				<div class="stepcounter-inner">
            	<?php 
					for($i=0;$i<$trials_count;$i++){
            			if($current_trial==$i){
            				echo '<a href="?mode=review&trial='.$i.'" class="active">'.($i+1).'</a>';
            			}else{
            				echo '<a href="?mode=review&trial='.$i.'">'.($i+1).'</a>';
            			}
            		}
            	?>
				</div>
            </div>
            <div class="qcounter results">
            	<?php 
            	for($i=0; $i<count($question_id);$i++){
            		$options=getPostMeta($question_id[$i],"ecp_answer");
            		
            		
            		
            		$answer_type=$options["answer_type"];
            		$answer=$options["answer"];
            		
            		
            		
            		if($answer_type=="answer_dropdown"){
            			if($data["answers"][$question_id[$i]]==""){
	                		echo '<div class="qnumber unanswered"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span>left blank</span></div>';
	                	}else if($data["answers"][$question_id[$i]]==$answer["dropdown"]){
							echo '<div class="qnumber correct"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span></span></div>';
						}else{
							echo '<div class="qnumber incorrect"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span></span></div>';
						}
            		}elseif($answer_type=="answer_range"){
						if($data["answers"][$question_id[$i]]==null){
	            			echo '<div class="qnumber unanswered"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span>left blank</span></div>';
	            		}else if(	
								(float)$data["answers"][$question_id[$i]]>floatval($options["answer"]["range_from"]) &&
								(float)$data["answers"][$question_id[$i]]<floatval($options["answer"]["range_to"]) ){
	            			echo '<div class="qnumber correct"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span></span></div>';
	            		}else{
	            			echo '<div class="qnumber incorrect"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span></span></div>';
	            		}
					}else{
	            		if($data["answers"][$question_id[$i]]==null){
	            			echo '<div class="qnumber unanswered"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span>left blank</span></div>';
	            		}else if($data["answers"][$question_id[$i]]==$options["answer"]["correct"]){
	            			echo '<div class="qnumber correct"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span></span></div>';
	            		}else{
	            			echo '<div class="qnumber incorrect"><a href="#qid_'.$question_id[$i].'">'.($i+1).'</a><span></span></div>';
	            		}
            		}
            	}
            	?>
            </div>            
            <div class="greybox resultsbox">
            	<div class='qpanel' id='qpanel_scores' style="display:none;">
            		<h3>Your Total Score is <span class="inline_score"><span class="score"><?php echo $data["correct_count"] ?></span><span class="score-total"><?php echo $data["question_count"] ?></span></span></h3>
            		<p>Test started on <?php echo date("F j, Y, g:i a",$data["drill_begin"])?> and ended on  <?php echo date("F j, Y, g:i a",$data["drill_end"])?></p>

                    <h4>For <span class="dark">Explanations</span>, please click on the individual question numbers above</h4>
            	</div>
            	<?php for($i=0; $i<count($question_id);$i++){ 
            		$post_data=get_post($question_id[$i]);
            		$options=getPostMeta($question_id[$i],"ecp_answer");
            		
            		$answer_type=$options["answer_type"];
            		$answer=$options["answer"];
            		
            		$qcontent=$post_data->post_content;
            		$quid=$question_id[$i];
            		
            	?>
	            	<div class='qpanel' id='qpanel_<?php echo $quid; ?>' style="display:none;">
		            	<?php edit_post_link('Edit Question', '<p>', '</p>',$quid); ?>
		            	<h3><?php echo $qcontent; ?></h3>
		            	<!-- Question passgae begin -->
		            	<?php 
		            	$qpassage=getPostMeta($post_data->ID,"ecp_question_passage");
		            	if($qpassage["passage_type"]!="" && ($qpassage["plain_passage"]!="" || $qpassage["numbered_passage"]!="")){?>
		            		<div class="passage-container">
			            		<?php 
			            			$qpassage=getPostMeta($post_data->ID,"ecp_question_passage");
			            			if($qpassage["passage_type"]=="plain"){
			            				echo "<div class='plain passage' style='display:none'>".$qpassage["plain_passage"]."</div>";
			            			}elseif($qpassage["passage_type"]=="numbered"){
			            				$passage=$qpassage["numbered_passage"];
			            				$right_div="";
			            				$passage_rows=explode("\n",$passage);
			            				$count=1;
										foreach($passage_rows as $row){
											if($count%5==0){
			            						$right_div.="<div>line-".$count."</div>";
			            					}else{
			            						$right_div.="<br/>";
			            					}
			            					$count++;
										}
			            				?>
			            				<div class='numbered passage' style='display:none'>
			            					<div class='passage-numbers' style="float:left; text-align:right; padding-right:5px; border-right:solid 1px #cacaca;margin-right:5px;"><?php echo $right_div; ?></div>
			            					<div class='passage-content' style="float:left;"><?php echo nl2br($qpassage["numbered_passage"]); ?></div>
				            				<div><strong>
				            					<?php echo $qpassage["after_passage_description"]; ?>
				            				</strong></div>
			            				</div>
			            		<?php } ?>
			            		
		            			<a href="#" class="button blue view_passage">View Passage</a>
		            		</div>
	            		<?php } ?>
		            	
		            	

		            	<?php if($answer_type=="answer_plain"){?>
                        <h3 class="blue">Your answer is:</h3>
		            	<ul class="test_qs">
		                    <?php 
		                    	$corr_a="";
		                    	$corr_b="";
		                    	$corr_c="";
		                    	$corr_d="";
		                    	$corr_e="";
								
														
		                    	$correct=$data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]!="";
		                    	
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="a"){ $corr_a="<div class='answermark correct'></div>"; }else if($data["answers"][$question_id[$i]]=="a"){ $corr_a="<div class='answermark incorrect'></div>"; }
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="b"){ $corr_b="<div class='answermark correct'></div>"; }else if($data["answers"][$question_id[$i]]=="b"){ $corr_b="<div class='answermark incorrect'></div>"; }
		                 		if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="c"){ $corr_c="<div class='answermark correct'></div>"; }else if($data["answers"][$question_id[$i]]=="c"){ $corr_c="<div class='answermark incorrect'></div>"; }
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="d"){ $corr_d="<div class='answermark correct'></div>"; }else if($data["answers"][$question_id[$i]]=="d"){ $corr_d="<div class='answermark incorrect'></div>"; }
            					if($data["answers"][$question_id[$i]]==$options["answer"]["correct"] && $options["answer"]["correct"]=="e"){ $corr_e="<div class='answermark correct'></div>"; }else if($data["answers"][$question_id[$i]]=="e"){ $corr_e="<div class='answermark incorrect'></div>"; }
            	
		                    	echo "<li val='a'><span>A</span>".$corr_a.$answer["a"]["value"]."</li>";
		                    	echo "<li val='b'><span>B</span>".$corr_b.$answer["b"]["value"]."</li>";
		                    	echo "<li val='c'><span>C</span>".$corr_c.$answer["c"]["value"]."</li>";
		                    	echo "<li val='d'><span>D</span>".$corr_d.$answer["d"]["value"]."</li>";
		                    	echo "<li val='e'><span>E</span>".$corr_e.$answer["e"]["value"]."</li>";
		                    ?>
		                </ul>
		                <?php }elseif($answer_type=="answer_dropdown"){
		                	//print_r($data);
		                	//echo $data["answers"][$question_id[$i]]." ---- ".$answer["dropdown"];
		                	$u_answer=$data["answers"][$question_id[$i]];
		                	$correct=false;
		                	if($data["answers"][$question_id[$i]]==""){
		                		echo "<h3 class='blue'>Your answer is <span class='purple'>missing</span></h3>";
		                	}else if($data["answers"][$question_id[$i]]==$answer["dropdown"]){
								echo "<h3 class='blue'>Your answer <span class='answerblock green'>".($u_answer)."</span> is <span class='green'>correct</span></h3>";
								$correct=true;
							}else{
								echo "<h3 class='blue'>Your answer <span class='answerblock red'>".($u_answer)."</span> is <span class='red'>incorrect</span></h3>";
							}
		                }?>
		                
	               
	               
		            <?php if(!$correct): ?>
	               	<div class="correct_summary">
	               		<h3 class="blue">The Correct Answer is: <?php if($answer_type=="answer_dropdown"){ ?> <strong><?php echo $options["answer"]["dropdown"]; ?></strong><?php }elseif($answer_type=="answer_range"){ echo "between ".floatval($options["answer"]["range_from"])." and ".floatval($options["answer"]["range_to"]);  } ?></h3>
		            	
		            	<?php if($answer_type=="answer_plain"){ ?>
		            	<ul class="test_qs">
		            		<?php 
            					if($options["answer"]["correct"]=="a"){ echo "<li val='a'><span>A</span><div class='answermark correct'></div>".$answer["a"]["value"]."</li>"; }
            					if($options["answer"]["correct"]=="b"){ echo "<li val='b'><span>B</span><div class='answermark correct'></div>".$answer["b"]["value"]."</li>"; }
		                 		if($options["answer"]["correct"]=="c"){ echo "<li val='c'><span>C</span><div class='answermark correct'></div>".$answer["c"]["value"]."</li>"; }
            					if($options["answer"]["correct"]=="d"){ echo "<li val='d'><span>D</span><div class='answermark correct'></div>".$answer["d"]["value"]."</li>"; }
            					if($options["answer"]["correct"]=="e"){ echo "<li val='e'><span>E</span><div class='answermark correct'></div>".$answer["e"]["value"]."</li>"; }
		            	?>	
		            	</ul> 
		            	<?php } ?>
	               	</div>
	               <?php endif;?>
	                
	                <div class="Explanation">
	                	<h3>Explanation:</h3>
		                <div class="video-holder">
			                <?php  
			                	$videoURL= getPostMeta($post_data->ID,"VideoURL"); 
			                	if($videoURL!=""){ echo getFLVPlayer($videoURL,"qplayer"); }
			                ?>
		               	 </div>
		               	 <div class="text-explanation" style="margin:10px 0;">
		               	 	<?php 
		               	 	if(hasPostMeta($post_data->ID,"questionDescription")){
		               	 		echo nl2br(getPostMeta($post_data->ID,"questionDescription"));
		               	 	}
		               	 	?>
		               	 </div>
	               </div>
	               
	               </div>
			       <?php 
            		// answers loop end
            		} ?>
                 
                
                
                <div class="mainControlls">    
	                <a id="btn_prev" href="#" class="button orange">Prev</a>
	                <a id="btn_next" href="#" class="button orange">Next</a>
                </div>
            </div>
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
	if(jQuery(".note_scores").length>0){
		jQuery(".note_scores").trigger("click");
	}else{
		jQuery(".qcounter a:first").trigger("click");
	}
	
	jQuery(".test_qs li").click(function(e){
		jQuery(this).parents("ul:first").find("li").removeClass("selected");
		jQuery(this).addClass("selected");
	})
	jQuery("#btn_next_save").click(function(e){
		e.preventDefault();
		var panel_id="#qpanel_"+jQuery(".qcounter a.current").attr("href").split("_")[1];
		if(jQuery(panel_id+" .test_qs li.selected").length==0 && jQuery(panel_id+" .test_qs").length>0){
			if(!confirm("Are you sure you want to skip this question?"))
				return;
		} else {
			var ans=jQuery(panel_id+" .test_qs li.selected").attr("val")
			if(ans==null){

				ans="";
				jQuery(panel_id+" .partial_value").each(function(){

					if(jQuery(this).val()!=" "){
						//alert(jQuery(this).val());
						ans+=jQuery(this).val();
					}else{
						//return;
						// notification here
					}
				});
			}

			jQuery(".qcounter a.current").parent().removeClass("review").addClass("answered");
			jQuery(".qcounter a.current").parent().find("input").remove();
			jQuery(".qcounter a.current").parent().find("span").remove();
			jQuery(".qcounter a.current").parent().append("<span>answered</span>");
			var qid=jQuery(".qcounter a.current").attr("href").split("_")[1];
		
			jQuery(".qcounter a.current").parent().append("<input type='hidden' value='"+ans+"' name='uans["+qid+"]' />");
			jQuery("#mark_for_review input").removeAttr("checked");
		}
		jQuery(".qcounter a.current").parent().next().find("a").trigger("click");
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
		if(jQuery("#qpanel_scores").css("display")=="block"){
			jQuery(".qcounter a:last").trigger("click");
		}else{
			jQuery(".qcounter a.current").parent().prev().find("a").trigger("click");
		}
	})
	jQuery("#btn_next").click(function(e){
        alert('este');
		e.preventDefault();
		if(jQuery("#qpanel_scores").css("display")=="block"){
			jQuery(".qcounter a:first").trigger("click");
		}else{
			jQuery(".qcounter a.current").parent().next().find("a").trigger("click");
		}
	})

	jQuery(".view_passage").click(function(e){
		e.preventDefault();
		if(jQuery(this).html().indexOf("View")!=-1){
			jQuery(this).parent().find(".passage").slideDown();
			jQuery(this).html("Hide Passage");
		}else{
			jQuery(this).parent().find(".passage").slideUp();
			jQuery(this).html("View Passage")
		}
	})

	jQuery("#btn_submit").click(function(e){
		e.preventDefault();
		jQuery(this).parent().append('<div class="note_info">Check your Answers? <br/> <a href="#" onclick="jQuery(this).parents(\'form:first\').submit()">Submit Anyway</a><span></span></div>');
		return false;
	})
})


</script>

<?php get_footer(); ?>