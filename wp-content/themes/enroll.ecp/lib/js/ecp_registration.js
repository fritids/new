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
			if($(this).hasClass("satact-edge-online-course")) {
				$("#essay-grading").slideUp();
				$("#essay-grading").find(".product-item").removeClass("selected");
			}
		} else {
			if($(this).hasClass("satact-edge-online-course")) {
				$(this).parents('ul').find(".satact-edge-online-course").removeClass("selected");
				if($(this).attr('price-type') != "free") {
					$("#essay-grading").slideDown();
				} else {
					$("#essay-grading").slideUp();
					$("#essay-grading").find(".product-item").removeClass("selected");
				}
			} else if($(this).hasClass("test-prep-tutoring")) {
				$(this).parents('ul').find(".test-prep-tutoring").removeClass("selected");
			} else if($(this).hasClass("essay-grading")) {
				$(this).parents('ul').find(".essay-grading").removeClass("selected");
			}
			
			$(this).addClass("selected");
		}
	});
	
	$("a.show-description").click(function(evt){ evt.stopPropagation(); });
	$("a.show-description").fancybox();

});