jQuery.fn.outerHTML = function(s) {
	return (s)?this.before(s).remove() : jQuery("<p><p>").append(this.eq(0).clone()).html();
}
jQuery.fn.initProgressTable = function(params) {
	params = jQuery.extend( {minlength: 0, maxlength: 99999}, params);
	this.each(function(i) {
		
		var index=i;
		var current_context=this;
		jQuery(this).find(".t_editable td").attr("contenteditable",true)
		jQuery(this).find(".t_sortable tbody tr").each(function(i){
			jQuery(this).find("td:first").prepend("<span class='handler'></span>")
			jQuery(this).find("td:last").append("<span class='delete_row'></span>")
		})
		jQuery(this).find(".t_sortable tbody").sortable({
												"handle":"span.handler",
												stop: function(event, ui) {
														jQuery(event.target).find("tr").removeClass("alt");
														jQuery(event.target).find("tr:odd").addClass("alt");
													}
												});
		jQuery(this).find(".t_extendable").each(function(){
			var timestamp=new Date();
			var id="id_"+index+"_"+timestamp.getTime();
			jQuery(this).after("<a href='#' class='table_view_save' id='save_"+id+"'>Save</a>");
			jQuery(this).attr("id","table_"+id).after("<a href='#' class='add_row_link' id='for_table_"+id+"'>add row</a>");
		})
		jQuery(this).find("a.add_row_link").click(function(e){
			e.preventDefault();
			var table_id=jQuery(this).attr("id").replace("for_","");
			var clone=jQuery("#"+table_id+" tr:last").clone();
			
			clone.find("td").each(function(i){
				if(jQuery(this).find("span").length==0){

					jQuery(this).html("---");
				}else{
					var span_clone=jQuery(this).find("span").clone();
					if(jQuery(this).find(".score").length>0){ jQuery(this).html(""); }else{ jQuery(this).html("---"); }
					jQuery(this).html("---");
					jQuery(this).append(span_clone).find("span:not(.handler, .delete_row)").html("--");
				}
			})
			jQuery("#"+table_id+" tr:last").after(clone)
			jQuery("#"+table_id+" tr").removeClass("alt");
			jQuery("#"+table_id+" tr:odd").addClass("alt");
		})
		jQuery(this).find(".delete_row").live("click",function(){
			var tr=jQuery(this).parents("tr:first");
			tr.fadeOut(500,function(){ tr.remove(); });
		})
		jQuery(this).find(".table_view_save").click(function(e){
			e.preventDefault();
			var html=jQuery(current_context).html();
			var table=jQuery(html);
			
			table.find(".handler").remove();
			table.find(".delete_row").remove();
			table.find("td").removeAttr("contenteditable");
			jQuery.ajax({
			   type: "POST",
			   url: adminAjax+"?action=progress_table_save",
			   data: "table_name="+jQuery(current_context).attr("id")+"&table_data="+table.outerHTML()+"&current_user="+jQuery("#current_user").val(),
			   success: function(msg){
				    alert(msg)
			   }
			 });
		})	
	});
	return this;
};

jQuery.fn.initProgressTableAdmin = function(params) {
	//alert(jQuery(this).html())
	var current_context=jQuery(this);
	jQuery(this).append("<div id='table_holder' style='display:none'></div><a href='#' class='table_view' id='switch_view'>switch view</a>");
	jQuery(".table_wrap").initProgressTable();
	jQuery("#switch_view").click(function(e){
		e.preventDefault();
		current_context.find("textarea").toggle();
		switchView();
		jQuery("#table_holder").toggle();
		initTables();
	})
	jQuery("#publish").click(function(){ switchView(); });
	jQuery("#switch_view").trigger("click")		
	return this;
};

var init_text_a=true;
jQuery(function(){
	jQuery(".table_wrap").initProgressTable();
	if(jQuery("textarea[name='IDGL_elem[tableRenderer]']").length>0){
		jQuery("textarea[name='IDGL_elem[tableRenderer]']").parent().initProgressTableAdmin();
	}
	jQuery("#tables-shortlinks a").click(function(e){
		e.preventDefault();
		if(!jQuery(this).hasClass("current")){
			jQuery(".table_wrap").hide();
			jQuery(jQuery(this).attr("href")).show();
			jQuery("#tables-shortlinks a.current").removeClass("current");
			jQuery(this).addClass("current")
		}
	})
	jQuery("#tables-shortlinks a:first").trigger("click");
})
function switchView(){
	var elem=jQuery("textarea[name='IDGL_elem[tableRenderer]']");
	if(jQuery("#table_holder").css("display")=="block"){
		jQuery("#table_holder .handler").remove();
		jQuery("#table_holder .delete_row").remove();
		jQuery("#table_view_save").remove();
		jQuery("#table_holder td").removeAttr("contenteditable");
		jQuery("a.add_row_link").remove();
		var html=jQuery("#table_holder").html();
		elem.val(html);
		jQuery("#switch_view").attr("class","table_view");
	}else{
		jQuery("#table_holder").html(elem.val());
		jQuery("#switch_view").attr("class","html_view");
	}
}
function initTables(){
	jQuery(".t_editable td").attr("contenteditable",true)
	jQuery(".t_sortable tbody tr").each(function(i){
		jQuery(this).find("td:first").prepend("<span class='handler'></span>")
		jQuery(this).find("td:last").append("<span class='delete_row'></span>")
	})
	jQuery(".t_sortable tbody").sortable({
											"handle":"span.handler",
											stop: function(event, ui) {
													jQuery(event.target).find("tr").removeClass("alt");
													jQuery(event.target).find("tr:odd").addClass("alt");
												}
											});
	jQuery(".t_extendable").each(function(){
		var timestamp=new Date();
		var id="id_"+timestamp.getTime();
		jQuery(this).attr("id","table_"+id).after("<a href='#' class='add_row_link' id='for_table_"+id+"'>add row</a>");
	})
	jQuery("a.add_row_link").click(function(e){
		e.preventDefault();
		var table_id=jQuery(this).attr("id").replace("for_","");
		jQuery("#"+table_id+" tr:last").after(jQuery("#"+table_id+" tr:last").clone())
		
		jQuery("#"+table_id+" tr").removeClass("alt");
		jQuery("#"+table_id+" tr:odd").addClass("alt");
	})
	jQuery(".delete_row").live("click",function(){
		var tr=jQuery(this).parents("tr:first");
		tr.fadeOut(500,function(){ tr.remove(); });
	})
	jQuery("#table_view_save").click(function(e){
		e.preventDefault();
		var html=jQuery(".table_wrap").html();
		var table=jQuery(html).find("tr").parents("table");
		
		table.find(".handler").remove();
		table.find(".delete_row").remove();
		table.find("td").removeAttr("contenteditable");

		jQuery.ajax({
		   type: "POST",
		   url: adminAjax+"?action=progress_table_save",
		   data: "table_name="+jQuery(".table_wrap").attr("id")+"&table_data="+table.outerHTML()+"&current_user="+jQuery("#current_user").val(),
		   success: function(msg){
			    alert(msg)
		   }
		 });
	})	

}
