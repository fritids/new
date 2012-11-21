jQuery(function($){
	$("#dropmenu li a").not("#dropmenu li li a").click(function(e) {
		if(jQuery(this).parent().find("ul").length>0){
			e.preventDefault();
			$(this).parent().find("ul.sub-menu").slideDown('fast').show(); 
			$(this).parent().hover(function() {
			}, function(){
				$(this).parent().find("ul.sub-menu").slideUp('slow'); 
			});
		}
	});
	$("#dropmenu li").not("#dropmenu li li").each(function(){
		if($(this).children('ul.sub-menu').size() > 0){
			$(this).addClass('has_sub_menu');
		}
	});
	$('.flatmenu').each(function(){
		$(this).find('li a:last').css({"border":"0"});
	});
	jQuery("#notification_guide").hide();
	jQuery("#guideForm #submit_guide").click(function(e){
		e.preventDefault();
		
		var note=jQuery("#notification_guide");
		note.html("");
		var guide_name=jQuery("#guide_name").val();
		if(guide_name==""){
			note.append("Please enter your name<br/>");
			jQuery("#notification_guide").show();
		}
		var guide_email=jQuery("#guide_email").val();
		if(guide_email=="" || !validate_em(guide_email)){
			note.append("Please enter valid email");
			jQuery("#notification_guide").show();
		}
		if(note.html()!=""){ return; }
		 $.ajax({
		   type: "POST",
		   url: imagesPath+"../../sendToCC.php",
		   data: "name="+guide_name+"&email="+guide_email,
		   success: function(msg){
			   note.html( msg );
		   }
		 });
		 jQuery("#notification_guide").show();
		 note.html( "<img src='"+imagesPath+"preloader.gif' />" )
	})

})
function validate_em(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = email;
   if(reg.test(address) == false) {
      return false;
   }else{
	   return true;
   }
}

