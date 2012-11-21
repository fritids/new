jQuery.fn.editable = function(url, options) {
	// Options
	options = arrayMerge({
		"url": url,
		"paramName": "q",
		"callback": null,
		"saving": "saving …",
		"type": "text",
		"submitButton": 0,
		"delayOnBlur": 0,
		"extraParams": {},
		"editClass": null
	}, options);
	// Set up
	this.click(function(e) {
			if (this.editing) return;
			if (!this.editable) this.editable = function() {
				var me = this;
				if(jQuery(me).find(".handler").length>0){
					jQuery(me).attr("data-handler",true);
					jQuery(me).find(".handler").remove();
				}
				if(jQuery(me).find(".delete_row").length>0){
					jQuery(me).attr("data-delete",true);
					jQuery(me).find(".delete_row").remove();
				}
				me.editing = true;
				me.orgHTML = jQuery(me).html();
				me.innerHTML = "";
				if (options.editClass) jQuery(me).addClass(options.editClass);
				var f = document.createElement("form");
				var i = createInputElement(me.orgHTML);
				jQuery(i).css("width","95%")
				var t = 0;
				f.appendChild(i);
				me.appendChild(f);
				i.focus();
				jQuery(i).blur(
						function(e){
							var pre="";
							var after="";
							if(jQuery(this).parent().parent().attr("data-handler")=="true"){
								jQuery(this).parent().parent().removeAttr("data-handler")
								pre="<span class='handler'></span>";
							}
							if(jQuery(this).parent().parent().attr("data-delete")=="true"){
								jQuery(this).parent().parent().removeAttr("data-delete")
								after="<span class='delete_row'></span>";
							}
							jQuery(this).parent().parent().html(pre+jQuery(this).val()+after);
							
							me.editing = false;	
						}
					).keydown(function(e) {
						if (e.keyCode == 27) { // ESC
							e.preventDefault;
							reset
						}
					});
				jQuery(f).submit(function(e) {
					if (t) clearTimeout(t);
					e.preventDefault();
					var p = {};
					p[i.name] = jQuery(i).val();
					me.editing = false;	
				});
				function reset() {
					me.innerHTML = me.orgHTML;
					if (options.editClass) jQuery(me).removeClass(options.editClass);
					me.editing = false;					
				}
			};
			this.editable();
		})
	;
	// Don't break the chain
	return this;
	// Helper functions
	function arrayMerge(a, b) {
		if (a) {
			if (b) for(var i in b) a[i] = b[i];
			return a;
		} else {
			return b;		
		}
	};
	function createInputElement(v) {
		if (options.type == "textarea") {
			var i = document.createElement("textarea");
			options.submitButton = true;
			options.delayOnBlur = 100; // delay onBlur so we can click the button
		} else {
			var i = document.createElement("input");
			i.type = "text";
		}
		jQuery(i).val(v);
		i.name = options.paramName;
		return i;
	}
};
/*
 * jQuery Text Children plugin
 * Examples and documentation at: http://plugins.learningjquery.com/textchildren/
 * Version: 0.1 (02/27/2008)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Requires: jQuery v1.1.3.1 or later
 */

(function($) {
$.fn.textChildren = function(n, options) {
  if (typeof n == 'object') {
    options = n;
    n = 'all';
  }
  var opts = $.extend({}, $.fn.textChildren.defaults, options || {}); 

  var output = opts.outputType == 'string' ? '' : [];
  var contents = '';
  this.each(function(index) {
    var nodeIndex = n;
    var txtNodes = $.grep( this.childNodes, function(node) {
      return (node.nodeType == 3);
    });
  
    if (n == 'first') {
      nodeIndex = 0;
    } else if (n == 'last') {
      nodeIndex = txtNodes.length - 1;
    } else if ( n < 0 ) {
      nodeIndex = txtNodes.length + n;
    }
    if ( nodeIndex >= txtNodes.length || nodeIndex < 0 ) {
      return;
    } 
    if (n == undefined || n == 'all') {
      for (var i=0; i < txtNodes.length; i++) {
        contents = opts.trim ? $.trim(txtNodes[i].nodeValue) : txtNodes[i].nodeValue;
        if (opts.outputType == 'array') {
          ( output.length && contents == '') ? output : output.push(contents);
        } else {
          output += output == '' ? contents : opts.stringDelimiter + contents;
        }
      }
    } else {
      contents = opts.trim ? $.trim(txtNodes[nodeIndex].nodeValue) : txtNodes[nodeIndex].nodeValue;
      if (opts.outputType == 'array') {
        output.push(contents);
      } else {
        output += output == '' ? contents : opts.stringDelimiter + contents;
      }
    }
  });
  return output;
};


$.fn.textChildren.defaults = {
  outputType: 'string',       // one of 'string' or 'array'
  stringDelimiter: '',        // if outputting to string, inserts a delimiter between nodes
  trim: true                  // whether to trim white space from start/end of text nodes
};

})(jQuery);
jQuery.fn.outerHTML = function(s){
	return (s) ? this.before(s).remove() : jQuery("<p><p>").append(this.eq(0).clone()).html();
};

// PROGRESS TABLE

jQuery.fn.initProgressTable = function(params){
	params = jQuery.extend({
		minlength : 0,
		maxlength : 99999
	}, params);
	//alert("here")
	this.each(function(i)
	{
		var index = i;
		var current_context = this;
		
		/*jQuery(this).find(".t_editable td").each(function(){
			jQuery(this).html('<span class="contenteditable" contenteditable="true" >'+jQuery(this).html()+'</span>') ;//.wrap('<span contenteditable="true" />');
		})*/
		jQuery(this).find(".t_editable td").each(function(){
			jQuery(this).html(jQuery(this).text())
		})
		jQuery(this).find(".t_editable td").editable();
		jQuery(this).find(".t_editable td").mouseenter(function(){
			jQuery(this).addClass("theHoverTD");
		}).mouseleave(function(){
			jQuery(this).removeClass("theHoverTD");
		})
		//jQuery(this).find(".t_editable td").html("<div contenteditable='true'>"++"</div>").attr("contenteditable", true);
		jQuery(this).find(".t_sortable tbody tr").each(function(i){
			jQuery(this).find("td:first").prepend("<span class='handler'></span>");
			jQuery(this).find("td:last").append("<span class='delete_row'></span>");
		});
		
		jQuery(this).find(".t_sortable tbody").sortable({
			"handle" : "span.handler",
			stop : function(event, ui){
				jQuery(event.target).find("tr").removeClass("alt");
				jQuery(event.target).find("tr:odd").addClass("alt");
			}
		});
		
		jQuery(this).find(".t_extendable").each(function(){
			//var timestamp = new Date();
			var id = "id_" + index + "_" + Math.floor(Math.random() * 100000);
			jQuery(this).after("<a href='"+id+"' class='table_view_save' id='save_" + id + "'>Save</a>");
			jQuery(this).attr("id", "table_" + id).after("<a href='#' class='add_row_link' id='for_table_" + id + "'>add row</a>");
		});
		
		jQuery(this).find("a.add_row_link").click(function(e){
			e.preventDefault();
			var table_id = jQuery(this).attr("id").replace("for_", "");
			
			var clone = jQuery("#" + table_id + " tr:last").clone();
			clone.find("td").each(function(i){
				if (jQuery(this).find("span").length == 0)
				{
					jQuery(this).html("---");
				}
				else
				{
					var span_clone = jQuery(this).find("span").clone();
					if (jQuery(this).find(".score").length > 0)
					{
						jQuery(this).html("");
					}
					else
					{
						jQuery(this).html("---");
					}
					jQuery(this).html("---");
					jQuery(this).append(span_clone).find("span:not(.handler, .delete_row)").html("--");
				}
			});
			jQuery("#" + table_id + " tr:last").after(clone);
			
			jQuery(current_context).find(".t_editable td").editable();
			
			jQuery("#" + table_id + " tr").removeClass("alt");
			jQuery("#" + table_id + " tr:odd").addClass("alt");
		});
		
		jQuery(this).find(".delete_row").live("click", function()
		{
			var tr = jQuery(this).parents("tr:first");
			tr.fadeOut(500, function(){
				tr.remove();
			});
		});
		
		jQuery(this).find(".table_view_save").click(function(e){
			e.preventDefault();
			var html = jQuery(current_context).html();
			var table = jQuery("<div>"+html+"</div>");
			
			table.find(".handler").remove();
			table.find(".delete_row").remove();
			table.find("td").removeAttr("contenteditable");
			
			table.find(".table_view_save, .add_row_link").remove();
			
			jQuery("span.contenteditable").each(function(){
				jQuery(this).replaceWith( jQuery(this).html() )
			})
			
			//table.find(".add_row_link").remove();
			//table.find(".table_view_save").remove();

			jQuery.ajax({
				type : "POST",
				url : adminAjax + "?action=progress_table_save",
				data: "table_name="+jQuery(current_context).attr("id")+"&table_data="+table.html()+"&current_user="+jQuery("#current_user").val(),
				success : function(msg){
					alert(msg);
				}
			});
		});
	});
	return this;
};

// ADMIN TABLE INITIALIZING

jQuery.fn.initProgressTableAdmin = function(params){
	// alert(jQuery(this).html())
	var current_context = jQuery(this);
	jQuery(this).append("<div id='table_holder' style='display:none'></div><a href='#' class='table_view' id='switch_view'>switch view</a>");
	jQuery(".table_wrap").initProgressTable();
	jQuery("#switch_view").click(function(e){
		e.preventDefault();
		current_context.find("textarea").toggle();
		switchView();
		jQuery("#table_holder").toggle();
		initTables();
	});
	
	jQuery("#publish").click(function(){
		switchView();
	});
	
	jQuery("#switch_view").trigger("click");
	return this;
};

var init_text_a = true;

jQuery(function(){
	jQuery(".table_wrap").initProgressTable();
	
	if (jQuery("textarea[name='IDGL_elem[tableRenderer]']").length > 0)
	{
		jQuery("textarea[name='IDGL_elem[tableRenderer]']").parent().initProgressTableAdmin();
	}
	
	jQuery("#tables-shortlinks a").click(function(e){
		e.preventDefault();
		if (!jQuery(this).hasClass("current"))
		{
			jQuery(".table_wrap").hide();
			jQuery(jQuery(this).attr("href")).show();
			jQuery("#tables-shortlinks a.current").removeClass("current");
			jQuery(this).addClass("current");
		}
	});
	
	jQuery("#tables-shortlinks a:first").trigger("click");
});

function switchView()
{
	var elem = jQuery("textarea[name='IDGL_elem[tableRenderer]']");
	if (jQuery("#table_holder").css("display") == "block")
	{
		jQuery("#table_holder .handler").remove();
		jQuery("#table_holder .delete_row").remove();
		jQuery("#table_view_save").remove();
		jQuery("#table_holder td").removeAttr("contenteditable");
		jQuery("span.contenteditable").each(function(){
				jQuery(this).replaceWith( jQuery(this).html() )
			})
		jQuery("a.add_row_link").remove();
		var html = jQuery("#table_holder").html();
		elem.val(html);
		jQuery("#switch_view").attr("class", "table_view");
	}
	else
	{
		jQuery("#table_holder").html(elem.val());
		jQuery("#switch_view").attr("class", "html_view");
	}
}

function initTables()
{
	jQuery(".t_editable td").attr("contenteditable", true);
	jQuery(this).find(".t_editable td").each(function(){
			jQuery(this).html('<span class="contenteditable" contenteditable="true" >'+jQuery(this).html()+'</span>') ;//.wrap('<span contenteditable="true" />');
		})
		jQuery(this).find(".t_editable td").mouseenter(function(){
			jQuery(this).addClass("theHoverTD");
		}).mouseleave(function(){
			jQuery(this).removeClass("theHoverTD");
		})
	jQuery(".t_sortable tbody tr").each(function(i){
		jQuery(this).find("td:first").prepend("<span class='handler'></span>");
		jQuery(this).find("td:last").append("<span class='delete_row'></span>");
	});
	
	jQuery(".t_sortable tbody").sortable({
		"handle" : "span.handler",
		stop : function(event, ui)
		{
			jQuery(event.target).find("tr").removeClass("alt");
			jQuery(event.target).find("tr:odd").addClass("alt");
		}
	});
	
	jQuery(".t_extendable").each(function(){
		var timestamp = new Date();
		var id = "id_" + Math.floor(Math.random() * 100000);
		jQuery(this).attr("id", "table_" + id).after("<a href='#' class='add_row_link' id='for_table_" + id + "'>add row</a>");
	});
	
	jQuery("a.add_row_link").click(function(e){
		e.preventDefault();
		var table_id = jQuery(this).attr("id").replace("for_", "");
		console.log(table_id);
		jQuery("#" + table_id + " tr:last").after(jQuery("#" + table_id + " tr:last").clone());
		jQuery("#" + table_id + " tr").removeClass("alt");
		jQuery("#" + table_id + " tr:odd").addClass("alt");
	});
	
	jQuery(".delete_row").live("click", function(){
		var tr = jQuery(this).parents("tr:first");
		tr.fadeOut(500, function(){
			tr.remove();
		});
	});
	
	jQuery("#table_view_save").click(function(e){
		e.preventDefault();
		var html = jQuery(".table_wrap").html();
		var table = jQuery(html).find("tr").parents("table");
		table.find(".handler").remove();
		table.find(".delete_row").remove();
		table.find("td").removeAttr("contenteditable");
		jQuery("span.contenteditable").each(function(){
				jQuery(this).replaceWith( jQuery(this).html() )
			})
		jQuery.ajax({
			type : "POST",
			url : adminAjax + "?action=progress_table_save",
			data : "table_name=" + jQuery(".table_wrap").attr("id")	
					+ "&table_data=" + table.outerHTML() + "&current_user="
					+ jQuery("#current_user").val(),
			success : function(msg){
				alert(msg);
			}
		});
	});
}
