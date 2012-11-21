<div class="wrap">
	<h2>BankSITE&reg; Calendar Settings</h2>
	<form method="post" action="options.php">
	    <table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Event List page</th>
	        	<td><input type="text" name="events_list_page" value="<?php echo get_option('events_list_page'); ?>" /></td>
	        </tr>
		
		<tr valign="top">
	        	<th scope="row">Email</th>
	        	<td>
				<input type="text" name="events_email" value="<?php echo get_option('events_email'); ?>" /><br/>
				<span style="font-style:italic">Note: Upon successful submission of an event, a notification email will be sent to this address</span>
			</td>
	        </tr>
		
		<tr valign="top">
	        	<th scope="row">Enable/Disable Submit an Event feature in the frontend</th>
	        	<td><input type="checkbox" name="events_frontend" value="eventsFront" <?php if(get_option('events_frontend')!=""){echo "checked=checked"; }; ?> /></td>
	        </tr>
		
	        <tr valign="top">
	        	<th scope="row">Show past events</th>
	        	<td><input type="checkbox" name="show_past_events" value="showPast" <?php if(get_option('show_past_events')!=""){echo "checked=checked"; }; ?> /></td>
	        </tr>
	        
	        <tr valign="top">
	        	<th scope="row">Show tool tip</th>
	        	<td><input type="checkbox" name="show_tool_tip" value="showTootip" <?php if(get_option('show_tool_tip')!=""){echo "checked=checked"; }; ?> /></td>
	        </tr>
	         <?php settings_fields('baw-settings-group'); ?>
	    </table>
	    <p class="submit">
	    	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	    </p>
	</form>
</div>