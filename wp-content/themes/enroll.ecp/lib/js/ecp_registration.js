jQuery(function($){
	// Init masks
	InputMask.init();
			
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
			if($(this).hasClass("satact-edge-online-course")) {
				$("#essay-grading").slideUp();
			}
			$(this).removeClass("selected");
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
	
	/***********************
	 * Registration popups
	 ***********************/
	$("a#register-now").fancybox({
		hideOnOverlayClick: false,
		centerOnScroll: true
	});
	
	/* Form Validation*/
	var validator1 = $("#ecp_account_form").validate({
		errorClass: "validation-error",
		validClass: "validation-valid",
		errorElement: "span",
		rules: {
			email:{
				remote:{
					type: 'post',
					url: TEMPLATE_PATH+'/lib/plugins/callbacks/ajax/verify_username.php'
				}
			}
		},
		messages: {
			email: {
				remote: "This email is already registered in the system"
			}
		}
	});

	var validator2 = $("#ecp_login_form").validate({
		errorClass: "validation-error",
		validClass: "validation-valid",
		errorElement: "span"
	});

	var validator3 = $("#ecp_registration_form").validate({
		errorClass: "validation-error",
		validClass: "validation-valid",
		errorElement: "span"
	});
	
	$("#go-to-login").click(function(e) {
		e.preventDefault();
		validator1.resetForm();
		$("#account-info-form").hide();
		$("#login-info-form").show();
		$("#first_name").val("");
		$("#last_name").val("");
		$("#email").val("");
		$("#school").val("");
	});

	$("#back-btn").click(function(e) {
		e.preventDefault();
		validator2.resetForm();
		$("#login-error").hide();
		$("#account-info-form").show();
		$("#login-info-form").hide();
		$("#login_username").val("");
		$("#login_pass").val("");
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
	
	$("#create-account").click(function() {
		var link = $(this);
		if($("#ecp_account_form").valid()) {
			$("#account-error").hide();
			$("#first_name").attr('disabled',true);
			$("#last_name").attr('disabled',true);
			$("#gender").selectBox('disable');
			$("#email").attr('disabled',true);
			$("#school").attr('disabled',true);
			$("#dob_d").selectBox('disable');
			$("#dob_m").selectBox('disable');
			$("#dob_y").selectBox('disable');
			link.addClass('disabled');
			jQuery.ajax({
				type: "POST",
				dataType: "json",
				data: {
					FirstName: $("#first_name").val(),
					LastName: $("#last_name").val(),
					Gender: $("#gender").selectBox('value'),
					Email: $("#email").val(),
					School: $("#school").val(),
					DOB_Day: $("#dob_d").selectBox('value'),
					DOB_Month: $("#dob_m").selectBox('value'),
					DOB_Year: $("#dob_y").selectBox('value')
				},
				url: TEMPLATE_PATH+"/lib/plugins/callbacks/ajax/create_user_account.php",
				success: function(data){
					if(data) {
						if($("#registration-total-hdn").val() > 0) {
							$("#account-info-form").hide();
							$("#billing-info-form").show();
							$("#account-success").show();
						} else {
							window.location = SITE_PATH+"/thankyou";
						}
					} else {
						$("#account-error").show();
						$("#first_name").attr('disabled',false);
						$("#last_name").attr('disabled',false);
						$("#gender").selectBox('enable');
						$("#email").attr('disabled',false);
						$("#school").attr('disabled',false);
						$("#dob_d").selectBox('enable');
						$("#dob_m").selectBox('enable');
						$("#dob_y").selectBox('enable');
						link.removeClass('disabled');
					}
				}
			});
		}
	});
	
	$("#login").click(function() {
		var link = $(this);
		if($("#ecp_login_form").valid()) {
			$("#login-error").hide();
			$("#login_username").attr('disabled',true);
			$("#login_pass").attr('disabled',true);
			link.addClass('disabled');
			jQuery.ajax({
				type: "POST",
				dataType: "json",
				data: {login: $("#login_username").val(), password: $("#login_pass").val()},
				url: TEMPLATE_PATH+"/lib/plugins/callbacks/ajax/login_user.php",
				success: function(data){
					if(data) {
						$("#login-info-form").hide();
						$("#billing-info-form").show();
					} else {
						$("#login_username").attr('disabled',false);
						$("#login_pass").attr('disabled',false);
						link.removeClass('disabled');
						$("#login-error").show();
					}
				}
			});
		}
	});
	
	var coupons = new Array();
	$("#applyCoupon").click(function(e) {
		e.preventDefault();
		if($("#couponCode").val()) {
			jQuery.ajax({
				type: "POST",
				dataType: "json",
				data: {
					code: $("#couponCode").val()
				},
				url: TEMPLATE_PATH+"/lib/plugins/callbacks/ajax/verify_coupon.php",
				success: function(data){
					if(data) {
						// verify if coupon is already applied
						var applied = false;
						for(var i=0; i<coupons.length; i++) {
							if(data.id == coupons[i].id) {
								applied = true;
								$("#couponInvalid").html("Coupon already applied");
								$("#couponInvalid").fadeIn();
								setTimeout(function () { $("#couponInvalid").fadeOut(); }, 2000);
								break;
							}
						}
						
						// If coupon is not already applied, apply it
						if(!applied) {
							$("#couponCode").val("");
							coupons.push(new coupon(data.id,data.name,data.value,data.products));
							$("#coupons-applied").val(JSON.stringify(coupons));
							getTotal();
							
							$("#couponValid").html(data.name);
							$("#couponValid").fadeIn();
							setTimeout(function () { $("#couponValid").fadeOut(); }, 2000);
						}
					} else {
						$("#couponInvalid").html("Invalid Code");
						$("#couponInvalid").fadeIn();
						setTimeout(function () { $("#couponInvalid").fadeOut(); }, 2000);
					}
				}
			});
		}
	});

	$("#finalize-reg").click(function() {
		if($("#ecp_registration_form").valid()) {
			var button = $(this);
			button.attr("disabled", true);
			button.addClass('disabled');
			$("#checkout-error").hide();
			
			jQuery.ajax({
				type: "POST",
				dataType: "json",
				data: {
					cc_fname: $("#cc_fname").val(),
					cc_lname: $("#cc_lname").val(),
					cc_email: $("#cc_email").val(),
					cc_phone: $("#cc_phone").val(),
					ba_1: $("#ba_1").val(),
					cc_city: $("#cc_city").val(),
					cc_state: $("#cc_state").selectBox('value'),
					cc_zip: $("#cc_zip").val(),
					cc_type: $("#cc_type").selectBox('value'),
					cc_number: $("#cc_number").val(),
					cc_ex_month: $("#cc_ex_month").selectBox('value'),
					cc_ex_year: $("#cc_ex_year").selectBox('value'),
					cvv2: $("#cvv2").val(),
					purchase_description: $("#purchase-description").val(),
					coupons_applied: $("#coupons-applied").val()
				},
				url: TEMPLATE_PATH+"/lib/plugins/callbacks/ajax/checkout.php",
				success: function(data){
					if(data.auth_error) {
						$("#checkout-error").html(data.auth_error);
						$("#checkout-error").show();
						button.attr("disabled", false);
						button.removeClass('disabled');
					} else {
						window.location = SITE_PATH+"/thankyou";
					}
				}
			});
		}
	});
	
	/************
	 * FUNCTIONS
	 ************/
	
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
		var products = new Array();
		var subtotal = 0;
		var discount = 0;
		$(".product-item.selected").each(function() {
			var item = $(this);
			var item_discount = 0;
			// Add price to subtotal
			if(item.attr("price-type") != "free") {
				subtotal += parseFloat(item.attr("price"));
				
				for(var i=0; i<coupons.length; i++) {
					for(var j=0; j<coupons[i].products.length; j++) {
						if(item.attr("post-id") == coupons[i].products[j]) {
							item_discount += parseFloat(coupons[i].value);
						}
					}
				}
				discount += item_discount;
			}
			
			// Create product object
			products.push(new product(item.attr("post-id"),item.attr("name"),item.attr("desc"),item.attr("price"),item_discount,item.attr("taxonomy_slug")));
		});
		
		if(discount > 0) {
			$("#popup-registration-discount-container").show();
			$("#popup-registration-total-container").show();
		}
		
		$("#registration-total-hdn").val(subtotal - discount);
		$("#registration-total").html(subtotal - discount);
		
		$("#popup-registration-subtotal").html(subtotal);
		$("#popup-registration-discount").html(discount);
		$("#popup-registration-total").html(subtotal - discount);
		$("#purchase-description").val(JSON.stringify(products));
		
	}
	
	function product(id, name, desc, price, discount, taxonomy_slug) {
		this.id = id;
		this.name = name;
		this.desc = desc;
		this.price = price;
		this.discount = discount;
		this.taxonomy_slug = taxonomy_slug;
	}
	
	function coupon(id, name, value, products) {
		this.id = id;
		this.name = name;
		this.value = value;
		this.products = products;
	}
	
	
});