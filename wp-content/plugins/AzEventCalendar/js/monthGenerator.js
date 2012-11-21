jQuery(function(){
	jQuery('#eventStart').datepicker({
		minDate: new Date(),
		onClose: function(thisDate, thisInst){
			jQuery(this).focus();
			jQuery('#eventEnd').datepicker('enable');
			jQuery('#eventEnd').datepicker('option', 'minDate', new Date(thisDate));
		}
	});
	jQuery('#eventEnd').datepicker({
		minDate : new Date(),
		onClose : function(thisDate, thisInst){
			jQuery(this).focus();
		}
	});
	
	jQuery('a[class*=getNextMonth]').click(function(event){
		 event.preventDefault();									   
		 var currMonth =jQuery(this).attr("class");
		 //alert(test);
		 var monthSplited = currMonth.split("_");
		 
		jQuery.ajax({
			type: "POST",
			url:burl+"/wp-admin/admin-ajax.php",
			data:{action:"getE",
				  month:monthSplited[1], 
				  year:monthSplited[2]},
			success: function(msg){
			   //alert(msg);
			   jQuery(".AzMonhlyEventCalendar").html(msg);
			   //jQuery(".AzMonhlyEventCalendar #AzEventCalendar").remove();
			   //jQuery(".AzMonhlyEventCalendar").append(msg)
			}
		 });
		 
	})
	
	jQuery(".submitEvent").click(function(event){
		az_show_submButton();
		event.preventDefault();
		var tempDate=new Date();
		jQuery('#imp_code').html("<img src='"+burl+"/wp-content/plugins/AzEventCalendar/secureImage/securimage_show.php?temp="+tempDate.getTime()+"'/>");							
		jQuery('.backgroundPopup').css('filter', 'alpha(opacity=50)');
		jQuery(".backgroundPopup, .popUp").fadeIn("slow");
		 if(jQuery.browser.msie && jQuery.browser.version=="6.0") {
			var winHeight = jQuery(window).height();
			var winWidth = jQuery(window).width();
			
			jQuery(".backgroundPopup").css({"width":winWidth+"px", "height":winHeight+"px"});
			jQuery(".backgroundPopup").css({"background":"none", "background":"#000"});
		 }
	})
	jQuery(".backgroundPopup, .closeBtn").click(function(){
		jQuery(".backgroundPopup, .popUp").fadeOut("slow");	
		clearFormFields();
	})
	
	jQuery(document).keyup(function(event){
		var chPup = jQuery(".backgroundPopup, .popUp").attr("style");
		var btnCode = event.keyCode;
		if (btnCode == "27" && chPup == "display: block;") {       
			jQuery(".backgroundPopup, .popUp").fadeOut("slow");
			clearFormFields();
		}
	});
	
	function az_hide_submButton(){
		jQuery('.submintPopUp').hide();
		jQuery('#az_Cal_preloader').show();
	}
	function az_show_submButton(){
		jQuery('.submintPopUp').show();
		jQuery('#az_Cal_preloader').hide();
	}
	
	jQuery('.submintPopUp').click(function(event){
		
		az_hide_submButton();
		var fields = jQuery('.required');
		//alert(fields);
		//alert(fields.length);
		//alert(fields.html());
		var mail = jQuery('#userEmail');
		validateEmail(mail);
		
		for(var i = 0; i < fields.length; i++)
		{
			//alert(fields[i].value);
			if(fields[i].value=="")
			{
				jQuery(fields[i]).addClass('error');
			}
			else
			{
				jQuery(fields[i]).removeClass('error');
			}
		}
		
		var captch = jQuery('#agimg');
		
		
		jQuery('.error').blur(function(){
			if(jQuery(this).val() != "")
			{
				jQuery(this).removeClass('error');
			}
		});
		jQuery('.error').change(function(){
			if(jQuery(this).val() != "")
			{
				jQuery(this).removeClass('error');
			}
		});
		mail.blur(function(){
			validateEmail(jQuery(this));
		});
		validateCaptcha(captch);
	})
	
	function validateEmail(element){
		var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		if(emailPattern.test(element.val()))
		{
			jQuery(element).removeClass('error');
		}
		else
		{	
			jQuery(element).addClass('error');
			
		}
		az_show_submButton()
	}
	
	function validateCaptcha(code){
	//alert(code.val());
		jQuery.ajax({
			type: "POST",
			url:burl+"/wp-admin/admin-ajax.php",
			data:{
				action : "checkCaptcha",
				code : code.val()
			},
			success: function(msg){
			   // fadeout popup;
			   //alert(msg);
			   if(msg == 1 || msg == "1")
			   {
				//remove error class
				jQuery(code).removeClass('error');
				submitForm();
				//return true;
			   }
			   else 
			   {
				//add error class
				az_show_submButton()
				var tempDate=new Date();
		jQuery('#imp_code').html("<img src='"+burl+"/wp-content/plugins/AzEventCalendar/secureImage/securimage_show.php?temp="+tempDate.getTime()+"'/>");
				jQuery('#agimg').val("");
				jQuery(code).addClass('error');
				//return false;
				if(jQuery('#spanEror').length == 0){
					jQuery(".submintPopUp").after("<span id='spanEror' style='padding-right:14px;color:red;'><br/>Error: All fields are required</span>");
				}
			   }
			}
		});
	}
	
	function submitForm()
	{
		az_hide_submButton()
		if(jQuery('.error').length == 0 )
		{
			jQuery.ajax({
				type: "POST",
				url:burl+"/wp-admin/admin-ajax.php",
				data:{
					action : "createE",
					eName : jQuery('#eventName').val(),
					eStartDate : jQuery('#eventStart').val(),
					eEndDate : jQuery('#eventEnd').val(),
					eDesc : jQuery('#eventDesc').val(),
					uName : jQuery('#userName').val(),
					uEmail : jQuery('#userEmail').val(),
					uCNum : jQuery('#userCNum').val()
				},
				success: function(msg){
				   // fadeout popup;
				   az_hide_submButton();
				   if(msg != "-1")
				   {
					    clearFormFields();
					    
						// replace this chunk of code with clearFormFields after fixing the success msg issue
					    /*jQuery('#agimg').val("");
					    jQuery('#imp_code').html("<img src='"+burl+"/wp-content/plugins/AzEventCalendar/secureImage/securimage_show.php'/>");
						jQuery('#eventName').val("");
						jQuery('#eventStart').val("");
						jQuery('#eventEnd').val("");
						jQuery('#eventDesc').val("");
						jQuery('#userName').val("");
						jQuery('#userEmail').val("");
						jQuery('#userCNum').val("");
						jQuery('#spanEror').remove();*/
						jQuery(".backgroundPopup, .popUp").fadeOut("slow");
						if(jQuery('#successSpanAfter').length == 0)
						{
							jQuery(".submitEvent").after("<span id='successSpanAfter' style='text-align:right;display:block;padding-right:14px;' class='baseColor'><br/>Thank You for Submitting an Event</span>");
						}	
				   }
				   else //if(msg == "-1")
				   {
					   	alert ("error");
				   }
				}
			});	
		}
		else
		{
			if(jQuery('#spanEror').length == 0){
				jQuery(".submintPopUp").after("<span id='spanEror' style='padding-right:14px;color:red;'><br/>Error: All fields are required</span>");
			}
			
			event.preventDefault();	
		}
	}
	
	function clearFormFields()
	{
		var tempDate=new Date();
		jQuery('#agimg').val("");
		jQuery('#imp_code').html("<img src='"+burl+"/wp-content/plugins/AzEventCalendar/secureImage/securimage_show.php?temp="+tempDate.getTime()+"'/>");
		jQuery('#eventName').val("");
		jQuery('#eventStart').val("");
		jQuery('#eventEnd').val("");
		jQuery('#eventDesc').val("");
		jQuery('#userName').val("");
		jQuery('#userEmail').val("");
		jQuery('#userCNum').val("");
		jQuery('.error').removeClass('error');
		jQuery('#spanEror').remove();
		jQuery('.linitNot').css({'display':'none'});
	}
	/******* fixing the relative position in ie ********/
	 if (jQuery.browser.msie) {
		var zIndexNumber = 1000;
		jQuery('#AzEventCal div').each(function() {
			jQuery(this).css('zIndex', zIndexNumber);
			zIndexNumber -= 10;
		});
	 }
	 
	 
	if(jQuery('.#AzEventCalendar td.present_day a').html() == null)
	{
	 	var taketextCurdate = jQuery('.#AzEventCalendar td.present_day').html();
		jQuery('.#AzEventCalendar td.present_day').html('<a href="#" class="notactiv">'+taketextCurdate+'</a>');
	}
	jQuery('.notactiv').click(function(e){
		e.preventDefault();							  
	})
	
	
	function limitMessageLength(){
		var text = jQuery(this).val();
		
		if (text.length > 500) {
			
				jQuery(this).val(text.substr(0, 500));
				jQuery('.linitNot').css({'display':'block'});
				

		}else{
			jQuery('.linitNot').css({'display':'none'});
		}
	}
	
	jQuery('#eventDesc').keypress(limitMessageLength);
	jQuery('#eventDesc').change(limitMessageLength);
	
	
	 	 
});