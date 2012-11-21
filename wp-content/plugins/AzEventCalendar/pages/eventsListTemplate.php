<?php
global $wpdb;
global $cal_table_name;


if(isset($_POST["action"]) && isset($_POST["event_id"])){
	if($_POST["action"]=="delete"){
		$wpdb->query("DELETE FROM $cal_table_name WHERE event_id = '".$_POST["event_id"]."'");
	}
	if($_POST["action"]=="approve" || $_POST["action"]=="unapprove"){
		if($_POST["action"]=="approve"){
			$val=1;
		}else{
			$val=-1;
		}
		$wpdb->query("UPDATE $cal_table_name SET event_is_enabled='".$val."'  WHERE event_id = '".$_POST["event_id"]."'");
	}
	header("location:".$_SERVER["HTTP_REFERER"]);
	die();
}

$eventsPerPage=10;

$events_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $cal_table_name; "));
$events_pending = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $cal_table_name WHERE event_is_enabled=-1;"));
$events_appoved = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $cal_table_name WHERE  event_is_enabled=1;"));
$events_upcomming = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $cal_table_name WHERE  event_is_enabled=1 and event_end_date > DATE_ADD(NOW(),INTERVAL -1 DAY);"));
$events_past = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $cal_table_name WHERE  event_is_enabled=1 and event_end_date < DATE_ADD(NOW(),INTERVAL -1 DAY);"));

if(isset($_GET["filter"])){
	if($_GET["filter"]=="pending"){
		$filer=" WHERE event_is_enabled=-1";
	}else if($_GET["filter"]=="approved"){
		$filer=" WHERE event_is_enabled=1";
	}else if($_GET["filter"]=="upcoming"){
		$filer=" WHERE event_end_date > DATE_ADD(NOW(),INTERVAL -1 DAY)";
	}else if($_GET["filter"]=="past"){
		$filer=" WHERE event_end_date < DATE_ADD(NOW(),INTERVAL -1 DAY)";
	}
}
if(isset($_GET["limit"])){
	$limit=$_GET["limit"];
}else{
	$limit=0;
}


$events = $wpdb->get_results("SELECT * FROM $cal_table_name $filer ORDER BY event_date DESC limit ".($limit*10).",$eventsPerPage ");
$events_total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $cal_table_name $filer"));

$url='http'.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(strpos($url, "limit")){
	$url=substr($url,0, strpos($url, "&limit"));
}
?>
<div class="wrap">
	<h2>Events List <a class="button add-new-h2" href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=add_event">Add New</a></h2>

	<div class="tablenav">
        <div class="tablenav-pages">
            <span class="displaying-num">Displaying <?php echo ($limit*$eventsPerPage+1); ?> - <?php echo ($limit*$eventsPerPage+$eventsPerPage+1); ?> of <?php echo $events_total; ?></span>
            <?php
				$hoops=$events_total/$eventsPerPage;
				for($i=0;$i<$hoops;$i++){
					if($_GET["limit"]==$i){
						echo '<span class="page-numbers current">'.($i+1).'</span> ';
					}else{
						echo ' <a href="'.$url.'&limit='.$i.'" class="page-numbers">'.($i+1).'</a> ';
					}
				}
			?>
        </div>
        <br class="clear"/>
    </div>
    <ul class="subsubsub">
        <li>
            <a class="current" href="admin.php?page=AzEventCalendar/AzEventCalendar.php">All <span class="count">(<?php echo $events_count; ?>)</span></a>
            |
        </li>
        <li>
            <a href="admin.php?page=AzEventCalendar/AzEventCalendar.php&filter=pending">Pending <span class="count">(<?php echo $events_pending; ?>)</span></a>
			|
        </li>
		 <li>
            <a href="admin.php?page=AzEventCalendar/AzEventCalendar.php&filter=approved">Approved <span class="count">(<?php echo $events_appoved; ?>)</span></a>
        	|
        </li>
        <li>
            <a href="admin.php?page=AzEventCalendar/AzEventCalendar.php&filter=upcoming">Upcoming <span class="count">(<?php echo $events_upcomming; ?>)</span></a>
        	|
        </li>
         <li>
            <a href="admin.php?page=AzEventCalendar/AzEventCalendar.php&filter=past">Past <span class="count">(<?php echo $events_past; ?>)</span></a>
        </li>
        
    </ul>
    
    <div class="clear"/>
    <table cellspacing="0" class="widefat page fixed">
        <thead>
            <tr>
                <th style="" class="manage-column" scope="col">
                     Event Title
                </th>
                <th style="" class="manage-column" scope="col">
                    Event Start Date
                </th>
                 <th style="" class="manage-column" scope="col">
                    Event End Date
                </th>
                <th style="" class="manage-column" scope="col">
                    Posted By
                </th>
                <th style="" class="manage-column" scope="col">
                    Posted On
                </th>
				<th style="" class="manage-column" scope="col">
                   Action
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
			$i=0;
			foreach ($events as $event) { ?>
				<tr class="<?php if($i%2) echo "alternate" ?> iedit" id="page-2">
	                <td >
	                    <?php echo $event->event_title; ?>
	                </td>
	                <td>
	                    <?php echo $event->event_date; ?>
	                </td>
	                 <td>
	                    <?php echo $event->event_end_date; ?>
	                </td>
	                <td >
	                     <?php echo $event->event_posted_by_name; ?>
	                </td>
	                <td >
	                     <?php echo $event->event_posted_on_date; ?>
	                </td>
					<td >
	                   <ul class="subsubsub">
					        <li>
					            <a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=add_event&event_id=<?php echo $event->event_id; ?>">Edit</a>
								|
					        </li>
					        <li>
                           
									<form method="post" action="" style="display:inline;">
					            	<input type="hidden" name="action" value="delete" />
									<input type="hidden" name="event_id" value="<?php echo $event->event_id ; ?>" />
									<a href="#" id="deletEventBtn" onclick="if(confirm('Are you sure you want to delete this event?')) {jQuery(this).parent().submit()}">Delete</a>
								</form>
								|
					        </li>
							 <li>
					             <form method="post" action="" style="display:inline;" id="suubmitthisShit">
					            	<input type="hidden" name="action" value="<?php if($event->event_is_enabled==1){ echo "unapprove"; }else{ echo "approve"; }  ?>" />
									<input type="hidden" name="event_id" value="<?php echo $event->event_id ; ?>" />
									<a href="#" onclick="jQuery(this).parent().submit();"><?php if($event->event_is_enabled==1){ echo "UnApprove"; }else{ echo "<strong>Approve</strong>"; }  ?> </a>
								</form>
					        </li>
					    </ul>
	                </td>
	            </tr>
			<?php
			$i++;
			} ?>
        </tbody>
    </table>
	
	<div class="tablenav">
        <div class="tablenav-pages">
            <span class="displaying-num">Displaying <?php echo ($limit*$eventsPerPage+1); ?> - <?php echo ($limit*$eventsPerPage+$eventsPerPage+1); ?> of <?php echo $events_total; ?></span>
            <?php
				$hoops=$events_total/$eventsPerPage;
				for($i=0;$i<$hoops;$i++){
					if($_GET["limit"]==$i){
						echo '<span class="page-numbers current">'.($i+1).'</span> ';
					}else{
						echo ' <a href="'.$url.'&limit='.$i.'" class="page-numbers">'.($i+1).'</a> ';
					}
				}
			?>
        </div>
        <br class="clear"/>
    </div>
	
	</div>
    