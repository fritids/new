<?php
extract($_POST);


$result=array();
$result["answers"]=array();

$correct_count=0;
$question_ids=getPostMeta($drill_id,"ecp_drill_questions");
foreach($uans as $qid=>$uanswer){
	$options=getPostMeta($qid,"ecp_answer");
	if($options["answer_type"]=="answer_dropdown"){
		$answer=$options["answer"]["dropdown"];
		if($answer==$uanswer){
			$correct_count++;
		}
		$result["answers"][$qid]=$uanswer;
	}else{
		$answer=$options["answer"];
		if($answer["correct"]==$uanswer){
			$correct_count++;
		}
		$result["answers"][$qid]=$uanswer;
	}
}
$result["correct_count"]=$correct_count;
$result["question_count"]=count($question_ids);
$result["drill_end"]=time();
$result["drill_begin"]=$drill_begin;

//print_r($result);

ECPUser::setTestResult("selftest_".$drill_id,$result);
wp_redirect($_SERVER["HTTP_REFERER"]."?mode=review");