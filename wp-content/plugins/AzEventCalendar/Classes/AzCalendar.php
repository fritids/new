<?php


class AzCalendar{
	public function AzCalendar(){}
	

	public static function getEventListDetails($evCount=null){
		require_once AZ_CLASSPATH.'class.Templator.php';
		
		global $wpdb;
		global $cal_table_name;
		
		if($evCount != null)
		{
			$limit = " limit " . $evCount;
		}
		$events = $wpdb -> get_results("SELECT * FROM $cal_table_name WHERE event_end_date > DATE_ADD(NOW(),INTERVAL -1 DAY) AND event_is_enabled=1 ORDER BY event_date ASC" . $limit);
		
		return Templator::getTemplate("eventListDetails", $events, AZ_TEMPLATES);
	}
	
	public static function getEventList($evCount=null){
		switch_to_blog(1);		
		global $wpdb;
		global $cal_table_name;
		$cal_table_name = $wpdb->prefix . "AzEventCalendar_Events";
		if(get_option('events_list_page')!=""){
			$cal_link=get_option('events_list_page');
		}
		if($evCount!=null){
			$limit=" limit ".$evCount;
		}
		$events = $wpdb->get_results("SELECT * FROM $cal_table_name WHERE event_end_date > DATE_ADD(NOW(),INTERVAL -1 DAY) AND event_is_enabled=1 ORDER BY event_date ASC".$limit);
		//echo "SELECT * FROM $cal_table_name WHERE event_end_date > DATE_ADD(NOW(),INTERVAL -1 DAY) AND event_is_enabled=1 ORDER BY event_date ASC".$limit;
		$out="<ul>";
		for($i=0;$i<sizeof($events);$i++){
			$out.="<li>";
			//$out.="<div class='eventWraper'><a id='evid_".$events[$i]->event_id."'></a>"; href='$cal_link#evid_".$events[$j]->event_id."'
			$out.="<a href='$cal_link#evid_".$events[$i]->event_id."'>".$events[$i]->event_title;
			//if($events[$i]->event_image != "")
			//{
				//$out.="<div class='eventImgWraper'><img src='".$events[$i]->event_image."' alt='".$events[$i]->event_title."' /></div>";
			//}
			$out.="<span>".date("d  F  Y", strtotime($events[$i]->event_date))." - ".date("d  F  Y", strtotime($events[$i]->event_end_date))."</span></a>";
			
			//$out.="<p>".$events[$i]->event_content."</p>";
			//$out.="<!--<p>".$events[$i]->event_short_desc."</p>--></div>";
			//$out.="</div>";
			$out.="</li>";
		}
		$out.="</ul>";
		restore_current_blog();
		return $out; 

	}
	public static function getCalendar($mnth=null,$yr=null){
		
		global $wpdb;
		global $cal_table_name;
		
		$scriptToolTip = "";
		if(get_option('show_tool_tip')!="")
		{
			$scriptToolTip = 'jQuery("#AzEventCalendar .evented a").each(function(){
			var localEl=jQuery(this);
			jQuery(this).simpletip(
			{ 
			fixed: true,
   			position: [38, 0],
				onBeforeShow: function(e){
					 this.update(localEl.parent().find(".eventList").html()); 
				}
			 }); 
		})'; 	
		}
		
		$year = date('Y');
		if($yr!=null){
			$year=$yr;
		}
		$month = date('n');
		if($mnth!=null){
			$month=$mnth;
		}
		
		$day = date('j');
		$daysInMonth = date("t",mktime(0,0,0,$month,1,$year));
		$firstDay = date("w", mktime(0,0,0,$month,1,$year));
		$tempDays = $firstDay + $daysInMonth;
		$weeksInMonth = ceil($tempDays/7);
		$week=array();
		for($j=0;$j<$weeksInMonth;$j++) {
	            for($i=0;$i<7;$i++) {
	                $counter++;
	                $week[$j][$i] = $counter;
	                $week[$j][$i] -= $firstDay;
	                if (($week[$j][$i] < 1) || ($week[$j][$i] > $daysInMonth)) {    
	                    $week[$j][$i] = "";
	                }
	            }
	        }
		if(get_option('show_past_events') !="")
		{
			$events = $wpdb->get_results("SELECT * FROM $cal_table_name
			WHERE event_date BETWEEN '$year-$month-01' AND DATE('$year-$month-01' + INTERVAL 1 MONTH - INTERVAL 1 DAY)
			OR event_end_date BETWEEN '$year-$month-01' AND DATE('$year-$month-01' + INTERVAL 1 MONTH - INTERVAL 1 DAY)
			AND event_is_enabled=1 ORDER BY event_id DESC");	
		}
		else
		{
			$events = $wpdb->get_results("SELECT * FROM $cal_table_name WHERE event_end_date > DATE_ADD(NOW(),INTERVAL -1 DAY) AND event_is_enabled=1 ORDER BY event_id DESC");
		}
	
	
		for($i = 0; $i < sizeof($events) ;$i++) {
			if($events[$i]->event_is_enabled == -1)
			{
				unset($events[$i]);
			}
		}
		
	
	
		$nextYear=$year;
		$prevYear=$year;
		$nextMonth=$month;
		$prevMonth=$month;
		if($month==1){
		    $prevYear--;
		    $prevMonth=12;
			    $nextMonth++;
		}else if($month==12){
		    $nextYear++;
		    $nextMonth=1;
			    $prevMonth--;
		}else{
		    $nextMonth++;
		    $prevMonth--;
		}
		
		if(get_option('events_list_page')!=""){
			$cal_link=get_option('events_list_page');
		}
		$out= '<table id="AzEventCalendar" class="baseBrdColor" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th colspan="7" class="baseBgColor reducePadding">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<a class="neutralColor getNextMonth_'.$prevMonth.'_'.$prevYear.'" href="#">&laquo;&nbsp;'.date('M', mktime(0,0,0,$month-1,1,$year)).'</a>
								</td>
								<td>
									'.date('F', mktime(0,0,0,$month,1,$year)).' '.$year.'
								</td>
								<td>
									<a class="neutralColor getNextMonth_'.$nextMonth.'_'.$nextYear.'" href="#">'.date('M', mktime(0,0,0,$month+1,1,$year)).'&nbsp;&raquo;</a>
								</td>
								
							</tr>
						</table>
					</th>
				</tr>
				<tr class="neutralColorGray">
					<th class="neutralColor">S</th>
					<th class="neutralColor">M</th>
					<th class="neutralColor">T</th>
					<th class="neutralColor">W</th>
					<th class="neutralColor">T</th>
					<th class="neutralColor">F</th>
					<th class="neutralColor" style="border:0;">S</th>
				</tr>';

		foreach ($week as $key => $val) { 
			$out.= "<tr>";
			for ($i=0;$i<7;$i++) {
				$eventDay=$val[$i];
				$eventHTML="";
				$todays_date = strtotime(date("Y-m-d"));
				$currentDate=strtotime($year."-".$month."-".$eventDay);
				$enable_present="";
				if($todays_date == $currentDate)
				{
					$enable_present = "present_day";
				}
				$out.= "<td class='evented $enable_present'>";
				for($j=0;$j<sizeof($events);$j++){
					$fromDate=strtotime($events[$j]->event_date);
					$toDate=strtotime($events[$j]->event_end_date);
					if($currentDate>=$fromDate && $currentDate<=$toDate){
						if(!strpos($eventDay,"href")){
							$eventDay="<div class='eventWrap'><a class='baseColor' href='$cal_link#evid_".$events[$j]->event_id."'>".$eventDay."</a><span class='eventList' style='display:none;'>";
						}
						if(!strpos($eventDay,"kure")){
							$eventDay.="<span><span class='kure'></span><h4>".$events[$j]->event_title."</h4><!--<em> ".$events[$j]->event_date." to ".$events[$j]->event_end_date." </em>--><small>".substr($events[$j]->event_short_desc, 0, 150)."</small></span>";
						}else{
							$eventDay.="<span><h4>".$events[$j]->event_title."</h4><!--<em> ".$events[$j]->event_date." to ".$events[$j]->event_end_date." </em>--><small>".substr($events[$j]->event_short_desc, 0, 150)."</small></span>";
						}
						
					}
				}
				if(!strpos($eventDay,"eventList")){
					$eventDay.="</span></div>";
				}
				$out.=$eventDay;
				$out.= "</td>";
			}
			$out.= "</tr>";
		}
		$out.= '</table>
		<!--[if IE 6]>
            <link href="'.get_bloginfo("url").'/wp-content/plugins/AzEventCalendar/css/azIe.css" media="all" rel="stylesheet" type="text/css" />
        <![endif]-->
		<script src="'.get_bloginfo("url").'/wp-content/plugins/AzEventCalendar/js/monthGenerator.js"></script>
		<script type="text/javascript">
		var burl="'.get_bloginfo('url').'";
		var yearToBeReplaced = '.$year.' ;
		var monthToBeReplaced = '.$month.' ;'.
		$scriptToolTip
		.'</script>
		';
		return $out;
	}
	
	public static function createEvent($eName, $eStartDate, $eEndDate, $eDesc, $uName, $uEmail, $uCNum, $isEnabled)
	{	
		include_once("mailer/class.phpmailer.php");
		global $wpdb;
		global $cal_table_name;
		$eDesc = substr($eDesc, 0 , 500);
		$result = $wpdb->query("INSERT INTO $cal_table_name(event_title,event_date,event_end_date,event_content,event_posted_by_name,event_posted_by_email,event_posted_by_phone,event_posted_on_date,event_is_enabled)
									VALUES( 
									'".strip_tags(htmlentities($eName))."',
									'".date("Y-m-d",strtotime(strip_tags(htmlentities($eStartDate))))."',
									'".date("Y-m-d",strtotime(strip_tags(htmlentities($eEndDate))))."',
									'".strip_tags(htmlentities($eDesc))."',
									'".strip_tags(htmlentities($uName))."',
									'".strip_tags(htmlentities($uEmail))."',
									'".strip_tags(htmlentities($uCNum))."',
									NOW(),
									'".$isEnabled."')");
		//strip_tags(htmlentities($str));
		if($result === FALSE)
		{
			return -1;
		}
		else
		{
			// send email from here
			$message="<span style='font-weight:bold;'>New Event:</span><br/>";
			$message.="<span style='font-weight:bold;'>Event Name: ".stripslashes($eName)."</span> <br/>";
			$message.="<span style='font-weight:bold;'>From: ".stripslashes($eStartDate)." </span><br/>";
			$message.="<span style='font-weight:bold;'>To: ".stripslashes($eEndDate)."</span> <br/>";
			$message.="<span style='font-weight:bold;'>Description: ".stripslashes($eDesc)."</span> <br/>";
			$mail = new PHPMailer();
			$mail->From     = $uEmail;
			$mail->FromName = stripslashes($uName);
			//$mail->Mailer   = "smtp";
			$mail->IsHTML(true);
			$mail->Subject = "New Event Added";
			$mail->Body    = $message;
			if(get_option("events_email") != "")
			{
				$mail->AddAddress(get_option("events_email"));
			}
			else
			{
				$mail->AddAddress(get_option("admin_email"));
			}
			$mail->Host ='localhost';
			$mail->Port = 25;		    
			$mail->Send();
			return 0;
		}
		
	}
}
