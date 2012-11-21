
var currentImageEdit;
function initNode(target){
	initUploadify(jQuery(target).find(".IDGL_Image .uploadify"))
	initImagegallery(jQuery(target).find(".IDGL_ImageGallery .uploadify"));
	initColorNode(jQuery(target).find(".IDGL_listNode .IDGL_Color, .IDGL_Color input"));
	initComponents(jQuery(target))
	jQuery(target).removeAttr("onmouseover")
}
jQuery(function(){
	initIDGL()
})
function initIDGL(){
	initUploadify();
	initImagegallery();
	initColorNode()
	initComponents();
	jQuery(".sortable").sortable({
		placeholder: 'ui-state-highlight',
		opacity: 0.6
	});
	jQuery(".sortable").disableSelection();
}
function initImagegallery(node){
	if(node==null){
		node=jQuery(".IDGL_ImageGallery .uploadify");
	}
	node.each(function(i){
		var theTarget=jQuery(this);
		var time=new Date();
		var qstr="";
		var id="qid_"+time.getTime();
		var newFileName="img_"+time.getTime()+".jpg";
		theTarget.attr("id","p"+id);
		theTarget.parent().append("<div id='"+id+"'></div>");
		
		var imgdata=theTarget.parent().find(".model").find(".elem");
		var imgmodel=theTarget.parent().find(".model").parent().clone();
		imgmodel.find(".model").removeClass("model").css("display","")
		theTarget.parent().find(".model").parent().remove();
		
		if(imgdata.attr("_scalex")!=null){
			qstr="&w="+imgdata.attr("_scalex")+"|"+imgdata.attr("_scaley")+"|"+imgdata.attr("width")+"|"+imgdata.attr("height");
		}
		
		theTarget.parent().find(".image_remove").click(function(e){
			e.preventDefault();
			jQuery(this).parents(".imageWrap").parent().remove();
			/*theTarget.parent().prev(".elem").attr("src","");
			theTarget.parent().prev(".elem").attr("value","");
			theTarget.parent().find(".hiddenFld").val("");*/
		})
		
		var button = theTarget;
		new AjaxUpload(button, {
			action: adminAjax+'?action=imageUpload&newFileName='+newFileName+qstr, 
			name: "Filedata",
			onSubmit : function(file, ext){
	
			},
			onComplete: function(file, response){
				var imagePath=uploadPath+newFileName.replace(/\s/g,'_');
				var thumbPath=uploadPath+"thumb_"+newFileName.replace(/\s/g,'_');
				var imgs=theTarget.parent().find(".imageWrap")
				var targetElem=imgmodel.clone();
				//targetElem=targetElem.parent().clone();
				
				//alert(targetElem.html());
				/*if(targetElem.css("display")=="block"){
					
					targetElem.find(".model").removeClass(".model")
					targetElem.find(".model").css("temp","1");
					//targetElem.css("display","")
				}*/
				
				//rgetElem.css("display","block")
				//targetElem.find(".model").removeClass("model")
				//targetElem.find(".model").css("display","")
				var tempDate=new Date();
				//alert(targetElem.find(".elem").length)
				targetElem.find(".elem").attr("src",thumbPath+"?temp="+tempDate.getTime());
				targetElem.find(".elem").attr("value",imagePath);
				targetElem.find(".hiddenFld").val(imagePath);
				targetElem.find(".image_remove").css("display","inline");
				
				jQuery(theTarget.parents(".IDGL_ImageGallery")[0]).find("ol").prepend(targetElem);
				//alert(targetElem.html())
				//alert(theTarget.parent().html())
				
				//alert(theTarget.parent().html())
				/*theTarget.parent().prev(".elem").attr("src",imagePath);
				theTarget.parent().prev(".elem").attr("value",imagePath);
				theTarget.parent().find(".hiddenFld").val(theTarget.parent().parent().find(".elem").attr("src"));
				theTarget.parent().find(".image_remove").css("display","inline");*/
			}
		});
	});
}

function initUploadify(node){
	if(node==null){
		node=jQuery(".IDGL_Image .uploadify");
	}
	node.each(function(i){
		var theTarget=jQuery(this);
		var time=new Date();
		var qstr="";
		var id="qid_"+time.getTime();
		var newFileName="img_"+time.getTime()+".jpg";
		theTarget.attr("id","p"+id);
		theTarget.parent().append("<div id='"+id+"'></div>");
		if(theTarget.parent().prev(".elem").attr("_scalex")!=null){
			qstr="&w="+(theTarget.parent().prev(".elem").attr("_scalex"))+"|"+(theTarget.parent().prev(".elem").attr("_scaley"));
		}
		
		theTarget.parent().find(".image_remove").click(function(e){
			e.preventDefault();
			theTarget.parent().prev(".elem").attr("src","");
			theTarget.parent().prev(".elem").attr("value","");
			theTarget.parent().find(".hiddenFld").val("");
		})
		
		var button = theTarget;
		new AjaxUpload(button, {
			action: adminAjax+'?action=imageUpload&newFileName='+newFileName+qstr, 
			name: "Filedata",
			onSubmit : function(file, ext){
	
			},
			onComplete: function(file, response){
				var time=new Date();
				var imagePath=uploadPath+newFileName.replace(/\s/g,'_');
				theTarget.parent().prev(".elem").attr("src",imagePath+"?temp="+time.getTime());
				theTarget.parent().prev(".elem").attr("value",imagePath);
				theTarget.parent().find(".hiddenFld").val(theTarget.parent().parent().find(".elem").attr("src"));
				theTarget.parent().find(".image_remove").css("display","inline");
			}
		});
	});
}

function initColorNode(node){
	if(node==null){
		node=jQuery(".IDGL_listNode .IDGL_Color, .IDGL_Color input");
	}
	node.each(function(i){
		//alert(i)
		var parent=jQuery(this);
		var selColor=jQuery(this).find(".elem").attr("value");
		var elem=jQuery(this).find(".elem");
		jQuery(this).find(".trigger").click(function(e){
			elem.trigger("click")
		})
		elem.ColorPicker({

			onChange: function (hsb, hex, rgb) {

				parent.find(".elem").attr("value","#"+hex);
				parent.find(".elem").css("background-color","#"+hex);
			},
			color:selColor
		});
	});
}
function initNodeTabs(node){
	node.find(".tabList").remove();
	node.find(".TabBegin").each(function(i){
		var tempId=jQuery(this).attr("id");
		var time=new Date();
		jQuery(this).attr("id",tempId.split("_")[0]+"_"+time.getTime());
		
	})
}


function initComponents(node){
	if(node==null){
		jQuery(".IDGL_listNode .IDGL_DatePicker input").datepicker({showOn: 'button', buttonImage: 'calendar.gif', buttonImageOnly: true});
		/*jQuery(".IDGL_GoogleMap .GooleMapNode, .IDGL_GoogleMap input").focus(function(){
			jQuery("body").googleMap(jQuery(this),jQuery(this).attr("value"));
		});*/
		
		jQuery(".IDGL_GoogleMap .GooleMapNode, .IDGL_GoogleMap input").each(function(){
			var elem=jQuery(this)
			//alert(elem.parent().find(".trigger").attr("src"))
			elem.parent().find(".trigger").click(function(){
				//alert("**")
				elem.trigger("focus")
			})
			elem.focus(function(){
				jQuery("body").googleMap(jQuery(this),jQuery(this).attr("value"));
			});
		})
		
		jQuery(".IDGL_Wrap").each(function(){
			var tabList="";
			if (jQuery(this).find(".tabList").html() == null) {
				jQuery(this).find(".IDGL_TabBegin").each(function(i){
					tabList += "<li><a href='#" + jQuery(this).attr("id") + "'>" + jQuery(this).attr("id").split("_")[0] + "</a></li>";
				})
				jQuery(this).prepend("<ul class='tabList'>" + tabList + "</ul>");
				jQuery(this).tabs();
			}
		})
		
		jQuery(".IDGL_Slider").each(function(i){
			var instance=jQuery(this);
			var opts=instance.find(".IDGL_SliderValue").attr("options")
			var val=instance.find(".IDGL_SliderValue").val()
			opts=opts.split(",")
			
			var compOptions=new Object();
			for(var i=0;i<opts.length;i++){
				var temp=opts[i].split(":")
				compOptions[temp[0]]=eval(temp[1]);
			//	alert(compOptions[temp[0]])
			}
			
			compOptions.slide=function(event, ui) {
				
				if(ui.values!=null){
					instance.find(".elem").val("["+ui.values+"]");
					instance.find(".localValue").html("["+ui.values+"]");
				}else{
					instance.find(".elem").val(ui.value);
					instance.find(".localValue").html(ui.value);
				}
			}
			compOptions.value=eval(instance.find(".elem").val());
			compOptions.values=eval(instance.find(".elem").val());
			//alert(compOptions.value)
			//alert(opts)  localValue
			instance.find(".slideHolder").slider(compOptions);
		})
		

		
	}else{
		node.find(".IDGL_DatePicker input").datepicker({showOn: 'button', buttonImage: imagesPath+'calendar.gif', buttonImageOnly: true});
		node.find(".IDGL_GoogleMap .GooleMapNode, .IDGL_GoogleMap input").each(function(){
			var elem=jQuery(this)
			elem.parent().find(".trigger").click(function(){
				elem.trigger("focus")
			})
			elem.focus(function(){
				jQuery("body").googleMap(jQuery(this),jQuery(this).attr("value"));
			});
		})
		
		
		node.find(".IDGL_Wrap").each(function(){
			var tabList="";
			if (jQuery(this).find(".tabList").html() == null) {
				jQuery(this).find(".IDGL_TabBegin").each(function(i){
					tabList += "<li><a href='#" + jQuery(this).attr("id") + "'>" + jQuery(this).attr("id").split("_")[0] + "</a></li>";
				})
				jQuery(this).prepend("<ul class='tabList'>" + tabList + "</ul>");
				jQuery(this).tabs();
			}
		})
	}
	
}

// plugin page
jQuery(function(){
	var marker;
	jQuery.fn.googleMap = function(target,address) {
		jQuery("body .dialog").remove();
		jQuery("body").append("<div class=\'dialog\' title='MapLocation'><div style='width:680px;height:350px;' id='theGoogleMap'></div><input style='width:680px;' type='text' id='masterMapLocation' class='mapLocation' /></div>")
		jQuery(".dialog").dialog({
			bgiframe: true,
			height: 500,
			width:700,
			modal: true,
			buttons: {
				'Create': function() {
					target.attr("value",jQuery("#masterMapLocation").val());
					jQuery(this).dialog('close');
				},
				Cancel: function() {
					jQuery(this).dialog('close');
				}
			}
		});
		
		var latlng = new google.maps.LatLng(45, 45);
		var zoomlvl=1;
		if (address != "") {
			address =  address.replace(/\[/g,'').replace(/\]/g,'');
			var adr=address.split(",");
			latlng= new google.maps.LatLng(adr[0],adr[1]),
			zoomlvl= parseInt(adr[2]);
		}
		var myOptions = {
	      zoom: zoomlvl,
	      center: latlng,
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    };
	    var map = new google.maps.Map(document.getElementById("theGoogleMap"), myOptions);
		if (address != null) {
			if (address != "") {
				address =  address.replace(/\[/g,'').replace(/\]/g,'');
				adr=address.split(",")
				marker = new google.maps.Marker({
					position: new google.maps.LatLng(adr[0],adr[1]),
					map: map
				});
				map.setCenter( new google.maps.LatLng(adr[0],adr[1]))
				marker.setDraggable(true);
			}
		}
		google.maps.event.addListener(map, 'center_changed', function() {
			
			
		});
		google.maps.event.addListener(map, 'rightclick', function(e) {
			if(marker!=null){
				marker.setPosition(e.latLng);
			}else{
				marker = new google.maps.Marker({position:e.latLng, map:map});
				marker.setDraggable(true);
			}
			var mapCent=e.latLng;
			mapCent=mapCent.toString().replace(/\(/g,'').replace(/\)/g,'')
			jQuery("#masterMapLocation").attr("value","["+mapCent+","+map.getZoom()+"]")
			
			
	        google.maps.event.addListener(marker, "dragend", function(e) {
	        	var mapCent=e.latLng;
				mapCent=mapCent.toString().replace(/\(/g,'').replace(/\)/g,'')
				jQuery("#masterMapLocation").attr("value","["+mapCent+","+map.getZoom()+"]")
	        });
			google.maps.event.addListener(map, 'zoom_changed', function() {
				var mapCent=e.latLng;
				mapCent=mapCent.toString().replace(/\(/g,'').replace(/\)/g,'')
				jQuery("#masterMapLocation").attr("value","["+mapCent+","+map.getZoom()+"]")
			});
			//+","+map.getMapType()
			
		});
		
		
  
	};
	jQuery("#listHolder .node .GooleMapNode").focus(function(){
		jQuery("body").googleMap(jQuery(this),jQuery(this).attr("value"));
	})
	try{
		jQuery('.IDGL_DatePicker img').attr('src',imagesPath+'/calendar.png');
	}catch(e){}
	jQuery('.IDGL_TabBegin fieldset:last').addClass('last-fieldset');
})
function getPlayer(filepath,id){
	return '<embed height="420" width="640" flashvars="file='+filepath+'" wmode="opaque" allowscriptaccess="always" allowfullscreen="true" quality="high" bgcolor="#000000" name="'+id+'" id="'+id+'" style="" src="'+imagesPath+'player.swf" type="application/x-shockwave-flash">';
}

(function($) {

	var types = ['DOMMouseScroll', 'mousewheel'];

	$.event.special.mousewheel = {
	    setup: function() {
	        if ( this.addEventListener ) {
	            for ( var i=types.length; i; ) {
	                this.addEventListener( types[--i], handler, false );
	            }
	        } else {
	            this.onmousewheel = handler;
	        }
	    },
	    
	    teardown: function() {
	        if ( this.removeEventListener ) {
	            for ( var i=types.length; i; ) {
	                this.removeEventListener( types[--i], handler, false );
	            }
	        } else {
	            this.onmousewheel = null;
	        }
	    }
	};

	$.fn.extend({
	    mousewheel: function(fn) {
	        return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
	    },
	    
	    unmousewheel: function(fn) {
	        return this.unbind("mousewheel", fn);
	    }
	});


	function handler(event) {
	    var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
	    event = $.event.fix(orgEvent);
	    event.type = "mousewheel";
	    
	    // Old school scrollwheel delta
	    if ( event.wheelDelta ) { delta = event.wheelDelta/120; }
	    if ( event.detail     ) { delta = -event.detail/3; }
	    
	    // New school multidimensional scroll (touchpads) deltas
	    deltaY = delta;
	    
	    // Gecko
	    if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
	        deltaY = 0;
	        deltaX = -1*delta;
	    }
	    
	    // Webkit
	    if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
	    if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }
	    
	    // Add event and delta to the front of the arguments
	    args.unshift(event, delta, deltaX, deltaY);
	    
	    return $.event.handle.apply(this, args);
	}

	})(jQuery);

function initPassageAreas(){
	jQuery(".numbered_passage textarea").each(function(){
		jQuery(this).change(function(){
			var val=jQuery(this).val();
			var rows=val.split("\n");
			var passNum="";
			for(var i=0;i<rows.length;i++){
				if((i+1)%5==0){
					passNum+="line-"+(i+1)+"\n";
				}else{
					passNum+="\n";	
				}
			}
			jQuery(this).parent().find(".passageNum").val(passNum);
			jQuery(this).trigger("mousedown")
			jQuery(this).trigger("mousemove")
			jQuery(this).trigger("mouseup")
		})
		var h=200;
		var w=100;
	
		jQuery(this).parent().prepend("<textarea disabled='disabled' style='float:left;font-size:13px;height:200px;line-height:18.3px;margin:2px;overflow:hidden;text-align:right;' class='passageNum'></textarea>")
		jQuery(this).mousedown(function(){
			jQuery(this).mousemove(function(e){
				jQuery(this).parent().find(".passageNum")[0].scrollTop=this.scrollTop;
			});
		})
		jQuery(this).mouseup(function(){
			jQuery(this).trigger("mousemove")
			jQuery(this).unbind("mousemove");
		})
		jQuery(this).mousewheel(function(){
			jQuery(this).trigger("mousedown")
			jQuery(this).trigger("mousemove")
			jQuery(this).trigger("mouseup")
		});
		jQuery(this).trigger("change")
	})
}