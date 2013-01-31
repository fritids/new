jQuery(document).ready(function(){
	jQuery("#abz_browse").click(function(){
		browser.dialog('open');
	});
	browser = jQuery("#abz_dialog").dialog({ 
						autoOpen: false,
						height: 500,
						width: 850,
						modal: true,
						closeOnEscape: true,
						resizable: false, 
						title: "FTP File Browser",
						buttons:{
							'Select': function() {
								var val=jQuery("#abz_selected_file").val().replace("/fg0txn14_apps/ecp_questions_explained/streams/_definst_","").replace(".flv","")
								if(val.indexOf(".mp4")>0){
									val="mp4:"+val;
								}
								jQuery("#abz_selected_file").val(val)
								jQuery('#abz_holder').html(" ");
								//jQuery('#abz_selected_file').attr('value', '');
								jQuery(this).dialog('close');
							},
							'Cancel': function() {
								jQuery('#abz_holder').html(" ");
								jQuery('#abz_selected_file').attr('value', '');
								jQuery(this).dialog('close');
							}	
						},
						open: function(event, ui) {
							AjaxCall('action=getFolders&dirName=/fg0txn14_apps/ecp_questions_explained/streams/_definst_');
						}
			  });
			  
	function AjaxCall(param)
	{
		jQuery.ajax({
			url: browserPath,
			type: 'POST',
			data: param,
			beforeSend: function(){
				handleBeforeSend();
			},
			success: function(msg){
				handleSuccess(msg);
			},
			failure: function(msg){
				handleFailure(msg);
			}
		});
	}
	
	function handleBeforeSend()
	{
	/*
		jQuery('#abz_preloader').position({
		  my: "left bottom",
		  at: "left top",
		  of: ".ui-dialog-buttonpane"
		});
	*/
		jQuery('#abz_preloader').css('display', 'block');
	}
	
	function handleSuccess(param)
	{
		jQuery('#abz_preloader').css('display', 'none');
		jQuery('#abz_holder').html(param);
		
		jQuery('#abz_holder').css("width","400px");
		jQuery('#abz_holder').css("float","left");
		
		jQuery("#preview_player").remove();
		
		jQuery('#abz_holder').after('<div id="preview_player" style="width:400px; float:left;"></div>')
		
		jQuery(".abz_dir").click(function(){
			AjaxCall('action=changeDir&dirName='+jQuery(this).attr('id'));
			//alert(jQuery(this).attr('class') + ' || ' + jQuery(this).attr('id') + ' || ' + jQuery(this).html());
		});
		jQuery(".abz_parent").click(function(){
			AjaxCall('action=upDir&dirName='+jQuery(this).attr('id'));
			//alert(jQuery(this).attr('class') + ' || ' + jQuery(this).attr('id') + ' || ' + jQuery(this).html());
		});
		jQuery(".abz_file").click(function(){
			clearSelection();
			jQuery(this).addClass('selected_file');
			jQuery('#abz_selected_file').attr('value', jQuery(this).attr('id'));
			var add="";
			if(jQuery(this).attr('id').indexOf(".mp4")>0){
				add="mp4:";
			}
			//alert(jQuery(this).attr('id').replace(add+"/fg0txn14_apps/ecp_questions_explained/streams/_definst_","").replace(".flv",""));
			var pl=getPlayerFMS(add+jQuery(this).attr('id').replace("/fg0txn14_apps/ecp_questions_explained/streams/_definst_","").replace(".flv",""),"the_preview",400,260);
			jQuery("#preview_player").html(pl);
			//alert("file selected");
			//alert(jQuery(this).attr('id'));
			// set some hidden input field value to the name of the file that was clicked
			//jQuery("#abz_selected_file").val(jQuery(this).attr('id'));
		});
		//alert(param);
	}
	
	function handleFailure(param)
	{
		alert(param);
	}
	
	function clearSelection()
	{
		jQuery('html').find('.selected_file').removeClass('selected_file');
	}
});