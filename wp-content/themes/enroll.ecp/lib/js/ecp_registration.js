jQuery(function($){
	/* Location Select Box */
	$("SELECT").selectBox();
	updateTestPrepTutoringPrices($("SELECT").selectBox('value'));
	$("#location-select").selectBox().change( function() {
		updateTestPrepTutoringPrices($(this).val());
		getTotal();
	});
	
	$("#tutor-level-select").selectBox().change( function() {
		$(".test-prep-tutoring-junior").removeClass("selected");
		$(".test-prep-tutoring-senior").removeClass("selected");
		if($(this).val() == "senior") {
			$("#test-prep-tutoring-junior").find("ul").hide();
			$("#test-prep-tutoring-senior").find("ul").show();
		} else {
			$("#test-prep-tutoring-senior").find("ul").hide();
			$("#test-prep-tutoring-junior").find("ul").show();
		}
		getTotal();
	});
	
	/* Cart functionality */
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
			} else if($(this).hasClass("test-prep-tutoring-junior") || $(this).hasClass("test-prep-tutoring-senior")) {
				$(this).parents('div').find(".test-prep-tutoring-junior").removeClass("selected");
				$(this).parents('div').find(".test-prep-tutoring-senior").removeClass("selected");
			} else if($(this).hasClass("essay-grading")) {
				$(this).parents('ul').find(".essay-grading").removeClass("selected");
			}
			
			$(this).addClass("selected");
		}
		getTotal();
	});
	
	/* Product description popup */
	$("a.show-description").click(function(evt){ evt.stopPropagation(); });
	$("a.show-description").fancybox();
	
	/* Billing info popup */
	$("a#register-now").fancybox({
		hideOnOverlayClick: false,
		centerOnScroll: true
	});
	
	/* Card Type selectbox */
	$("#cc_type").selectBox().change( function() {
		$("#cc_number").val("");
		$("#cvv2").val("");
		if($(this).val() == '') {
			$("#cc_number").attr("readonly", true);
			$("#cvv2").attr("readonly", true);
		} else {
			$("#cc_number").attr("readonly", false);
			$("#cvv2").attr("readonly", false);
			if($(this).val() == 'visa' || $(this).val() == 'master_card') {
				$("#cc_number").attr("mask", "#### #### #### ####");
				$("#cc_number").rules("remove", "minlength");
				$("#cc_number").rules("add", { minlength: 19, messages : { minlength: "Please enter at least 16 numbers." }});
				$("#cvv2").attr("mask", "###");
				$("#cvv2").rules("remove", "minlength");
				$("#cvv2").rules("add", { minlength: 3 });
			} else if($(this).val() == 'am_ex') {
				$("#cc_number").attr("mask", "#### ###### #####");
				$("#cc_number").rules("remove", "minlength");
				$("#cc_number").rules("add", { minlength: 17, messages : { minlength: "Please enter at least 15 numbers." }});
				$("#cvv2").attr("mask", "####");
				$("#cvv2").rules("remove", "minlength");
				$("#cvv2").rules("add", { minlength: 4 });
				
			}
			// Init masks
			InputMask.init();
		}
	});
	
	
	function updateTestPrepTutoringPrices (location) {
		$(".product-item").find(".location_prices").hide();
		$(".product-item").find("."+location).show();
		$(".product-item.test-prep-tutoring-junior").each(function() {
			var price = $(this).find("."+location).html().substring(1);
			$(this).attr("price", price);
		});
		$(".product-item.test-prep-tutoring-senior").each(function() {
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
		$("#popup-registration-total").html(total);
	}
	
	/* Form Validation*/
	$("#ecp_registration_form").validate({
		errorClass: "validation-error",
		validClass: "validation-valid",
		errorElement: "span"
	});
	
	$("#frmCreatePlan").validate({
		wrapper: "span",
		onfocusout: false,
		onkeyup: false,
		rules: {
			name:{
				required:true,
				maxlength:100,
				remote:{
					type: 'post',
					url: '<?php echo URL::site("admin/rpc/check_plan_name")?>',
					data: {
						id: function(){ return '<?php echo $plan->id; ?>'; }
					}
				}
			},
			description:{ required:true },
			status:{ required:true }
		},
		messages: {
			name: { remote: "Name already exists" }
		}
	});

});