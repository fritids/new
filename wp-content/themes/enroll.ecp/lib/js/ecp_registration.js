jQuery(function($){
	/* Location Select Box */
	$("SELECT").selectBox();
	$(".product-item").find("."+$("SELECT").selectBox('value')).show();
	$("SELECT").selectBox().change( function() {
		$(".product-item").find(".location_prices").hide();
		$(".product-item").find("."+$(this).val()).show();
	});
	
	
	$(".product-item").click(function() {
		if($(this).hasClass("selected")) {
			$(this).removeClass("selected");
		} else {
			if($(this).hasClass("satact-edge-online-course")) {
				$(this).parents('ul').find(".satact-edge-online-course").removeClass("selected");
			} else if($(this).hasClass("test-prep-tutoring")) {
				$(this).parents('ul').find(".test-prep-tutoring").removeClass("selected");
			}
			
			$(this).addClass("selected");
		}
	});
	
	$("a.show-description").click(function(evt){ evt.stopPropagation(); });
	$("a.show-description").fancybox();

});