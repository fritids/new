jQuery(function($){
	/*$(window).load(function () {
		$(".phone, .sphone, .cc_num, .cc_ph").trigger("blur");
	});
	*/
	var validator = $("#ecp_cart_form").validate({
		rules: {
			FirstName: "required",
			LastName: "required",
			school: "required",
			a_street: "required",
			a_city: "required",
			a_state: "required",
			a_zip: "required",
			ParentName: "required",
			ParentEmail: {
					required: true, 
					email: true
			},
			terms_conditions: "required",
			PrimaryPhone: {
				required: true,
				minlength: 10,
				maxlength: 13,
				digits: true
			},
			
			payer: "required",
			cc_fname: "required",
			cc_lname: "required",
			cc_number: {
				required: true,
				minlength: 15,
				digits: true
			},
			cvv2 : {
				required: true,
				minlength: 3,
				digits: true
			},
			cc_expiration: "required",
			ba_1: "required",
			cc_country: "required",
			cc_email: {
				required: true,
				email: true
			}
		},
		messages: {
			FirstName: "Required",
			LastName: "Required",
			school: "Required",
			a_street: "Required",
			a_city: "Required",
			a_state: "Required",
			a_zip: "Required",
			ParentName: "Required",
			ParentEmail: {
					required: "Required", 
					email: "Not valid email"
			},
			terms_conditions: "Required",
			PrimaryPhone: {
				required: "Required",
				minlength: "13 characters min",
				maxlength: "13 characters min",
				digits: "Only numbers allowed"
			},
			
			payer: "Required",
			cc_fname: "Required",
			cc_lname: "Required",
			cc_number: {
				required: "Required",
				minlength: "Not valid",
				digits: "Only numbers allowed"
			},
			cvv2 : {
				required: "Required",
				minlength: "Not valid",
				digits: "Only numbers allowed"
			},
			cc_expiration: "Required",
			ba_1: "Required",
			cc_country: "Required",
			cc_email: {
				required: "Required",
				email: "Not valid email"
			}
		},
		errorPlacement: function(error, element){
			error.insertAfter(element);
		},
		errorElement: "span",
		errorClass: "required",
		validClass: "valid"
	});

	$(".phone, .sphone, .cc_num, .cc_ph, #cvv2").bind({
		keydown: function(e){
			if( e.which!=8 && e.which!=0 && e.which != 9 && (e.which<48 || e.which>57) && (e.which < 96 || e.which > 105))
		    {
			    //display error message
			    return false;
		    }
		},
		blur: function(){
			var total = "";
			var temp = $(this).parent().find("input[type!=hidden]");
			var hidden = $(this).parent().find("input[type=hidden]");
			temp.each(function(){
				total += $(this).val();
			});
			hidden.val(total);
			$("#ecp_cart_form").validate().element( hidden );
		}		
	});

});