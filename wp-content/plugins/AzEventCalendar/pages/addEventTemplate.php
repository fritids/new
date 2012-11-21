<?php
global $wpdb;
global $cal_table_name;
if(isset($_GET["event_id"])){
	$eventId=$_GET["event_id"];
}
if(isset($_POST["action"])){
	if($_POST["event_is_enabled"]=="on"){
		$enabled=1;
	}else{
		$enabled=-1;
	}
	//event_end_date	
	if($_POST["action"]=='update'){
		$wpdb->query("UPDATE $cal_table_name SET 
									event_title='".$_POST["event_name"]."',
									event_date='".date("Y-m-d",strtotime($_POST["event_date"]))."',
									event_end_date='".date("Y-m-d",strtotime($_POST["event_end_date"]))."',
									event_short_desc='".$_POST["event_short_desc"]."',
									event_content='".$_POST["event_details"]."',
									event_image='".$_POST["event_image"]."',
									event_posted_by_name='".$_POST["event_posted_by_name"]."',
									event_posted_by_email='".$_POST["event_posted_by_email"]."',
									event_posted_by_phone='".$_POST["event_posted_by_phone"]."',
									event_is_enabled='".$enabled."'  
									
									WHERE event_id = '".$_POST["event_id"]."'");
		header("Location:admin.php?page=AzEventCalendar/AzEventCalendar.php");
	}else if($_POST["action"]=='add'){
		$wpdb->query("INSERT INTO $cal_table_name(event_title,event_date,event_end_date,event_short_desc,event_content,event_image,event_posted_by_name,event_posted_by_email,event_posted_by_phone,event_posted_on_date,event_is_enabled)
									VALUES( 
									'".$_POST["event_name"]."',
									'".date("Y-m-d",strtotime($_POST["event_date"]))."',
									'".date("Y-m-d",strtotime($_POST["event_end_date"]))."',
									'".$_POST["event_short_desc"]."',
									'".$_POST["event_details"]."',
									'".$_POST["event_image"]."',
									'".$_POST["event_posted_by_name"]."',
									'".$_POST["event_posted_by_email"]."',
									'".$_POST["event_posted_by_phone"]."',
									NOW(),
									'".$enabled."')");
		$eventId = $wpdb->get_var($wpdb->prepare("SELECT MAX(event_id) FROM $cal_table_name"));
		header("Location:admin.php?page=AzEventCalendar/AzEventCalendar.php");
	}
}

if(isset($eventId)){
	$event = $wpdb->get_results("SELECT * FROM $cal_table_name WHERE event_id = '".$eventId."'");
}
?>
<style type="text/css">
#editor-toolbar .active {
	background-color:#E3EEF7 !important;
	border-bottom-color:#E3EEF7 !important;
	color:#333333 !important;
}
#edButtonPreview, #edButtonHTML {
	background-color:#F2F1EB !important;
	border-color:#DFDFDF !important;
	color:#999999 !important;
}
</style>
<script type="text/javascript">
jQuery(function(){
	//jQuery('#event_date').datepicker();
	//jQuery('#event_end_date').datepicker();
	/*jQuery('#event_date').datepicker({
  		onClose : function() {
			jQuery(this).focus();
		}
	});
	jQuery('#event_end_date').datepicker({
  		onClose : function() {
			jQuery(this).focus();
		}
	});*/
	//jQuery("#event_end_date").datepicker();
	
	jQuery('#event_date').datepicker({
		minDate : new Date(),
		onClose : function(thisDate, thisInst){
			jQuery(thisInst).focus();
			jQuery('#event_end_date').datepicker('enable');
			jQuery('#event_end_date').datepicker('option', 'minDate', new Date(thisDate));
		}
	});
	jQuery('#event_end_date').datepicker({
		minDate : new Date(),
		onClose : function(thisDate, thisInst){
			jQuery(thisInst).focus();
		}
	});
	
});

/******* add event validation script ********/

jQuery(function(){
	var uploadifyURL="<?php echo WP_PLUGIN_URL.'/AzEventCalendar/js/' ?>";
	<?php echo "var calRelURL='".substr(get_bloginfo('url'), strrpos(get_bloginfo('url'), "/"))."/wp-content/plugins/AzEventCalendar/js/';"; ?>
	<?php echo "var calBlogBasePath='".substr(get_bloginfo('url'), strrpos(get_bloginfo('url'), "/"))."';"; ?>
	jQuery(".hidden, .error").hide();
	var addeventForm = jQuery("#addEventForm");
	addeventForm.validate({		
		rules: {
			event_name: {
				required: true,
			},
			event_date: {
				required: true,
				date: true
			},
			event_end_date: {
				required: true,
				date: true
			}
		},
		messages: {
			event_name: "Please enter event name",
			event_date: "Please enter event start date",
			event_end_date: "Please enter event end date"
		}
	});
	jQuery("#uploaderImage").attr("src",jQuery("#event_image").val())
	jQuery("#temp_event_image").uploadify({
		'uploader'       : uploadifyURL+'uploadify/uploadify.swf',
		'script'         : uploadifyURL+'uploadify/uploadify.php',
		'cancelImg'      : uploadifyURL+'uploadify/cancel.png',
		'folder'         : '/wp-content/uploads/',
		'queueID'        : 'fileQueue1',
		'auto'           : true,
		'multi'          : true,
		onComplete: function(e, queueID, fileObj, response, data){
			jQuery("#uploaderImage").attr("src",fileObj["filePath"])
			jQuery("#event_image").val(fileObj["filePath"])
		}
	});
	jQuery('.jwysiwyg').wysiwyg({
		controls: {
		  insertImage	: { visible : false},
		  createLink	: { visible : false},
		  strikeThrough : { visible : true },
		  underline     : { visible : true },
		  
		  separator00 : { visible : true },
		  
		  justifyLeft   : { visible : true },
		  justifyCenter : { visible : true },
		  justifyRight  : { visible : true },
		  justifyFull   : { visible : true },
		  
		  separator01 : { visible : true },
		  
		  indent  : { visible : true },
		  outdent : { visible : true },
		  
		  separator02 : { visible : true },
		  
		  subscript   : { visible : true },
		  superscript : { visible : true },
		  
		  separator03 : { visible : true },
		  
		  undo : { visible : true },
		  redo : { visible : true },
		  
		  separator04 : { visible : true },
		  
		  separator06 : { visible : false },
		  
		  insertOrderedList    : { visible : true },
		  insertUnorderedList  : { visible : true },
		  insertHorizontalRule : { visible : true },
		  
		  h4mozilla : { visible : true && jQuery.browser.mozilla, className : 'h4', command : 'heading', arguments : ['h4'], tags : ['h4'], tooltip : "Header 4" },
		  h5mozilla : { visible : true && jQuery.browser.mozilla, className : 'h5', command : 'heading', arguments : ['h5'], tags : ['h5'], tooltip : "Header 5" },
		  h6mozilla : { visible : true && jQuery.browser.mozilla, className : 'h6', command : 'heading', arguments : ['h6'], tags : ['h6'], tooltip : "Header 6" },
		  
		  h4 : { visible : true && !( jQuery.browser.mozilla ), className : 'h4', command : 'formatBlock', arguments : ['<H4>'], tags : ['h4'], tooltip : "Header 4" },
		  h5 : { visible : true && !( jQuery.browser.mozilla ), className : 'h5', command : 'formatBlock', arguments : ['<H5>'], tags : ['h5'], tooltip : "Header 5" },
		  h6 : { visible : true && !( jQuery.browser.mozilla ), className : 'h6', command : 'formatBlock', arguments : ['<H6>'], tags : ['h6'], tooltip : "Header 6" },
		  
		  separator07 : { visible : true },
		  
		  cut   : { visible : true },
		  copy  : { visible : true },
		  paste : { visible : true }
		}
  });
  jQuery('#uploaderImage').load(function(){
  
		jQuery(this).css({"visibility":"visible"});
  
  })
});
</script>

<div class="wrap">
	<h2>Event</h2>
	<div style="width:700px;">
		<form method="post" action="" id="addEventForm">
		
		<div id="titlewrap">
			<label for="title">Title:</label><br/>
			<input style='font-size:22px; width:700px;' id="event_name" value="<?php echo $event[0]->event_title; ?>" type="text" name="event_name"/>
		</div>
		<div id="titlewrap">
			<label for="title">Event Start Date:</label><br/>
			<input style='font-size:18px; width:700px;' id="event_date" value="<?php if($event[0]->event_date!="") echo date("m/d/Y",strtotime($event[0]->event_date)); ?>" type="text" name="event_date"/>
		</div>
		<div id="titlewrap">
			<label for="title">Event End Date:</label><br/>
			<input style='font-size:18px; width:700px;' id="event_end_date" value="<?php if($event[0]->event_end_date!="") echo date("m/d/Y",strtotime($event[0]->event_end_date)); ?>" type="text" name="event_end_date"/>
		</div>
		<div id="titlewrap">
			<label for="title">Event Short Description:</label><br/>
			<textarea style='font-size:18px; width:700px;' id="event_short_desc" name="event_short_desc"><?php echo $event[0]->event_short_desc ?></textarea>
		</div>
		<div id="titlewrap" >
			<label for="event_details">Event Details:</label><br/>
			<textarea id="event_details" class="jwysiwyg" name="event_details" style='width:700px;'><?php echo stripslashes($event[0]->event_content); ?></textarea>
		</div>
		<div id="titlewrap" class="eventImage">
			<label for="title">Event Image:</label><br/>
			<input style='width:700px;' id="temp_event_image" type="file" name="temp_event_image"/>
			<?php 
			if($event[0]->event_image != ""){
				
				echo '<img style="visibility:visible;" src="'.$event[0]->event_image.'" id="uploaderImage" />';
			}else{
				echo '<img style="visibility:hidden;" src="'.$event[0]->event_image.'" id="uploaderImage" />';
			}
			?>
			<div id='fileQueue1'></div>
			<input type="hidden" id="event_image" name="event_image" value="<?php echo $event[0]->event_image; ?>" />
		</div>
		<div id="titlewrap">
			<label for="title">Event Posted By:</label><br/>
			<input style='width:700px;' id="event_posted_by_name" value="<?php echo $event[0]->event_posted_by_name; ?>" type="text" name="event_posted_by_name"/>
		</div>
		<div id="titlewrap">
			<label for="title">Email:</label><br/>
			<input style='width:700px;' id="event_posted_by_email" value="<?php echo $event[0]->event_posted_by_email; ?>" type="text" name="event_posted_by_email"/>
		</div>
		<div id="titlewrap">
			<label for="title">Phone:</label><br/>
			<input style='width:700px;' id="event_posted_by_phone" value="<?php echo $event[0]->event_posted_by_phone; ?>" type="text" name="event_posted_by_phone"/>
		</div>
		<div id="titlewrap">
			<label for="title">Approve Event to Show on Calendar:</label><br/>
			<input id="event_is_enabled" <?php if($event[0]->event_is_enabled!=-1){ echo "checked='checked'"; }; ?> type="checkbox" name="event_is_enabled"/>
		</div>
		
		<?php 
			if(isset($_GET["event_id"])){
				echo '<input type="hidden" name="action" value="update" />';
				echo '<input type="hidden" name="event_id" value="'.$_GET["event_id"].'" />';
			}else{
				echo '<input type="hidden" name="action" value="add" />';
			}
		?>
		<input class="button" type="submit" value="Save" />
		
		</form>
	</div>
</div>
    