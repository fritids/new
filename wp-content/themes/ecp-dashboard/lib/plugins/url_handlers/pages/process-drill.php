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
		//$uanswer=eval($uanswer);
		
		if(strpos($uanswer,"/")==1){
			$uanswer=explode("/",$uanswer);
			$uanswer=(float)((int)$uanswer[0])/((int)$uanswer[1]);
		}
        if(strpos($answer,"/")==1){
            $newans = explode("/",$answer);
            $newans=(float)((int)$newans[0])/((int)$newans[1]);
            $answer=(float)$newans;
        }
        
		if($answer==$uanswer){
			$correct_count++;
		}
		$result["answers"][$qid]=$uanswer;
	}elseif($options["answer_type"]=="answer_range"){
		$answer=$options["answer_range"];
		//print_r($_POST);
		//die();
		$uanswer=(float)$uanswer;
		$from=floatval($options["answer"]["range_from"]);
		$to=floatval($options["answer"]["range_to"]);
		//echo $correct_count."<br/>";
		if($uanswer>=$from && $uanswer<=$to){
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
ECPUser::setTestResult("selftest_".$drill_id,$result);
wp_redirect($_SERVER["HTTP_REFERER"]."?mode=review");