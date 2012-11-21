<?php
class AzCalendarWidget extends WP_Widget {
	function AzCalendarWidget() {
		$widget_ops = array( 'classname' => 'AzCalendarWidget', 'description' => __('Event Calendar - displays the events', 'AzCalendarWidget') );
		$control_ops = null;
		$this->WP_Widget( 'AzCalendarWidget', __('Event Calendar', 'Event Calendar'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		
		$submitString = "";
		if(get_option('events_frontend') != "")
		{
			$submitString = "<div class='submitEvent'><a href='#'>+ Submit an EVENT</a></div>";
		}
		
		echo "<div class='widget events'>";
		if ( $title )
			echo "<h2>". $title . "</h2>";
		echo "<!-- ignore-start --><ul class='callHolder'><li><div id='AzEventCal'>
			<div class='AzMonhlyEventCalendar'>".AzCalendar::getCalendar()."</div>
		</div>".$submitString."
		<div class='backgroundPopup' style='display:none;'></div>
		<div class='popUp' style='display:none;'>
			<div class='popupHeding boldText'><span class='headLineText'>SUBMIT AN EVENT</span><span class='closeBtn'>Close X</span></div>
			<div class='clear-fix'></div>
		    <form id='eventForm'>
		    <table class='SubmitEventFormHolder' width='551' border='0' cellspacing='0' cellpadding='2'>
			  <tr>
			    <td valign='top' width='154' class='boldText'><div style='width:154px;'>Event Name:</div></td>
			    <td valign='top'><input type='text' id='eventName' class='required'/></td>
			  </tr>
			  <tr>
			    <td valign='top' class='boldText'>Event Date:</td>
			    <td valign='top'>
				<table width='376' border='0' cellspacing='0' cellpadding='0' align='left'>
				  <tr>
				    <td valign='top' style='padding-left:0;width:42px;'>From:</td>
				    <td valign='top' style='padding-left:5px;width:153px;'>
					<input type='text' class='required dateInput' id='eventStart'/></td>
				    </td>
				    <td valign='top' style='padding-left:5px;width:42px;'>To:</td>
				    <td valign='top' style='padding-left:5px;width:153px;'>
					<input type='text' class='required dateInput' id='eventEnd'/></td>
				    </td>
				  </tr>
				</table>
			    </td>
			  </tr>
			  <tr>
			    <td valign='top' width='154' class='boldText'>Event Description:</td>
			    <td valign='top'><textarea id='eventDesc' class='required'></textarea>
					<div style='color:red;display:none;' class='linitNot'>Max length is 500 characters</div>
				</td>
			  </tr>
			  <tr>
			    <td colspan='2' valign='top'>
				The following information is private and wil not be published:
			    </td>
			  </tr>
			  <tr>
			    <td valign='top' width='154' class='boldText'>Your Name:</td>
			    <td valign='top'><input type='text' id='userName' class='required'/></td>
			  </tr>
			  <tr>
			    <td valign='top' width='154' class='boldText'>Your Email Address:</td>
			    <td valign='top'><input type='text' id='userEmail' class='email'/></td>
			  </tr>
			  <tr>
			    <td valign='top' width='154' class='boldText'>Your Contact Number:</td>
			    <td valign='top'><input type='text' id='userCNum' class='required'/></td>
			  </tr>
			  <tr>
			    <td valign='top' width='154' class='boldText'>Enter Text As shown:</td>
			    <td valign='top'>
				<table width='100%' border='0' cellspacing='0' cellpadding='0'>
				  <tr>
				    <td valign='top' width='80' style='padding-left:0;'>	
					<label id='imp_code'>
						<!--<img src='".get_bloginfo('url')."/wp-content/plugins/AzEventCalendar/secureImage/securimage_show.php' />-->
					</label>
				    </td>
				    <td valign='top'><input type='text' name='agimg' id='agimg' class='smallerInput'/></td>
				  </tr>
				</table>
			    </td>
			  </tr>
			  <tr>
			    <td colspan='2' valign='top'>
				By submitting an event, I acknowledge that you are under no obligation to post the event on your Event Calendar. You may edit the text for the event at your sole discretion.
			    </td>
			  </tr>
			  <tr>
			    <td  valign='top' width='154'>
			    </td> 
			    <td  valign='top'>
			    	<input type='button' class='submintPopUp' value=' ' />
			    	<div id='az_Cal_preloader' class='az_Cal_preloader'></div>
			    </td>
			  </tr>
			</table>
		    </form>
		</div>
		</li></ul><!-- ignore-end --></div>";
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => __('Event Calendar', 'Event Calendar'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}

class AzCalendarWidgetEventList extends WP_Widget {
	function AzCalendarWidgetEventList() {
		$widget_ops = array( 'classname' => 'AzCalendarWidgetEventList', 'description' => __('Event Calendar Event List - displays the list events', 'AzCalendarWidgetEventList') );
		$control_ops = null;
		$this->WP_Widget( 'AzCalendarWidgetEventList', __('Event Calendar Event List', 'Event Calendar Event List'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
				
		if ( $title )
			echo "<div class='widget events'><h2>". $title . "</h2>";
		echo "<div id='AzEventList'>
			<div class='AzMonhlyEventList'>".AzCalendar::getEventList($instance['event_count'])."</div>
		</div></div>";
		
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['event_count'] = strip_tags( $new_instance['event_count'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => __('Event List', 'Event List'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'event_count' ); ?>"><?php _e('Event Count:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'event_count' ); ?>" name="<?php echo $this->get_field_name( 'event_count' ); ?>" value="<?php echo $instance['event_count']; ?>" />
		</p>
	<?php
	}
}
?>
