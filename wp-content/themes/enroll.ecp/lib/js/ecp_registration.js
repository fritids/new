jQuery(function($){
	/* Location Select Box */
	$("SELECT").selectBox();
	updateTestPrepTutoringPrices($("SELECT").selectBox('value'));
	$("SELECT").selectBox().change( function() {
		updateTestPrepTutoringPrices($(this).val());
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
		getTotal();
	});
	
	$("a.show-description").click(function(evt){ evt.stopPropagation(); });
	$("a.show-description").fancybox();
	
	function updateTestPrepTutoringPrices (location) {
		$(".product-item").find(".location_prices").hide();
		$(".product-item").find("."+location).show();
		$(".product-item.test-prep-tutoring").each(function() {
			var price = $(this).find("."+location).html().substring(1);
			$(this).attr("price", price);
		});
	}
	
	function getTotal() {
		var total = 0;
		$(".product-item.selected").each(function() {
			if($(this).attr("price-type") != "free") {
				total += parseFloat($(this).attr("price"));
			}
		});
		$("#registration-total").html(total);
	}

});