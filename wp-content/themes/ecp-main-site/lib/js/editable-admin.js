
// za na front page title change
jQuery(function($){
	jQuery('.PostsList .post h2 a').editableText({
	          // default value
	          newlinesEnabled: true
	     });
	
	 $('.PostsList .post h2 a').change(function(){    
	     var content = $(this).html();
	     var postid = $(this).parent().parent().attr("id");
	     postid = postid.split("-");
	     alert(content);
	     $.ajax({
	        type: "POST",
	        url: "wp-content/themes/Idioglossia/editablecontent.php",
	        data: "postid=" + postid[1] + "&content=" + content + "&type=header",
	        success: function(msg){
	          alert( "Data Saved: " + msg );
	        }
	     });
	 });
	    
});

function ajaxeditable(type,postid,content){
	
	if(type == 'entry'){
		type = content;
	}
	if(type == ''){
		
	}
	
	jQuery.ajax({
        type: "POST",
        url: "/wordmaps/wp-content/themes/Idioglossia/editablecontent.php",
        data: "postid=" + postid[1] + "&content=" + content + "&type=" + type,
        success: function(msg){
          return msg;
        }
     });
	
}


//single page content
jQuery(function($){
	var type = null;
	jQuery('div.post h2 , .entry p:not(.postmetadata, img)').editableText({
	          // default value
	          newlinesEnabled: true
	}).addClass("bedited");
	
	jQuery('div.post h2 , .entry p:not(.postmetadata, img)').change(function(){
	
		if($(this).hasClass("bedited") && $(this)[0].tagName == 'H2'){
			type = 'title';
		}
		if($(this).hasClass("bedited") && $(this)[0].tagName == 'p'){
			type = 'content';
		}
		
         var content = $(this).html();
	     var postid = $(this).parent().parent().attr("id");
	     postid = postid.split("-");
	     ajaxeditable(type,postid,content);
		     
	 });    
});
