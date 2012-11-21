jQuery(function($){
	jQuery.ajaxSettings.traditional = true;
	
	var ECP_ACTIONS = "ecp_actions";
	
	var $form = $("#ecp_cart_form");
	var $next = $("#ecp_next_step");
	var $back = $("#ecp_previous_step");
	var products = $("input[name='products_ids[]']");

	$back.click(function(e){
		e.preventDefault();
		window.history.back();
	});
	
	$next.click(function(e){
		e.preventDefault();
		$form.submit();
	});
	
	// ADDS INITIAL (SELECTION) PART ACTIONS OF THE SHOPPING PROCESS
	var FormButtons = {
		allowMultiple: false,
		//on initialisation 
		init: function(){
			this.setSinglesEvents();	
			this.setMultiplesEvents();
			this.setDropdownEvents();
			this.setQuantityEvents();
			Coupon.setCouponAction();
		},
		resetValues: function(){
			//$("ul li input[type='hidden']").val("");
		},
		setSinglesEvents: function(){
			var $singles = $("div.single ul li");
			var elems = $singles.find("a.main_select");
			elems.each(function(){
				if($(this).hasClass("selected"))
				{
					$(this).data("click", true);
				}
				else
				{
					$(this).data("click", false);
				}
			});
			var clicked = null;
			var localroot = this;
			elems.click(function(e){
					e.preventDefault();
					e.stopPropagation();			
					
					var prodId = $(this).attr("rel");
					var main_price = $(this).parents("div.selection").find("span.int_selection_price");
					var hiddenId = $(this).siblings("input[type='hidden']");
					var price = pricesArr[prodId].price;
					currentPrices[prodId].price = price.toString();
					
					if(!$(this).hasClass("selected"))
					{					
						elems.each(function(){
							if($(this).data("click"))
							{
								clicked = $(this);
								clicked.id = $(this).attr("rel");
							}
						});
						
						if(clicked != null)
						{
							localroot.changeElemsClass(clicked, "selected", "remove");	
							clicked.data("click", false);
							Discounts.removeDiscounts(clicked.id);
							Quantity.removeProductQuantity(clicked.id);
							localroot.removeValue(hiddenId, clicked.id);
							clicked = null;
						}
												
						$(this).data("click", true);
						localroot.changeElemsClass($(this), "selected", "add");
						$(this).find("span.cart_added").fadeIn("fast");
						
						Discounts.addDiscounts(prodId);
						Quantity.addProductQuantity(prodId, 1);

						localroot.addValue(hiddenId, prodId);
						localroot.setMainPrice(main_price, price);
						localroot.setSelectionsMainPrice();
					}
					else
					{
						localroot.changeElemsClass($(this), "selected", "remove");
						$(this).data("click", false);
						Discounts.removeDiscounts(prodId);
						Quantity.removeProductQuantity(prodId);
						localroot.removeValue(hiddenId, prodId);			
						localroot.setMainPrice(main_price, 0);
						localroot.setSelectionsMainPrice();
					}
				}
			);
		},
		setMultiplesEvents: function(){
			var $multiples = $("div.multiple ul li");
			var elems = $multiples.find("a.main_select");
			var localroot = this;
			
			elems.unbind("click");
			elems.click(
				function(e){
					e.preventDefault();
					e.stopPropagation();

					var obj = $(this).siblings("input[type='hidden']");
					//var prodId = $(this).attr("rel");
					
					if(!$(this).hasClass("selected"))
					{
						var id = $(this).attr("rel");
						localroot.changeElemsClass($(this), "selected", "add");
						$(this).find("span.cart_added").fadeIn("fast");
						Discounts.addDiscounts(id);
						localroot.addValue(obj, id);
						
						$(this).siblings("div.ecp_quantity")
															.show()
															.find("input")
															.val(1);
						Quantity.addProductQuantity(id, 1);
					}
					else
					{
						var id = $(this).attr("rel");
						$(this).find("span.cart_added").fadeOut("fast");
						localroot.changeElemsClass($(this), "selected", "remove");
						Discounts.removeDiscounts(id);
						localroot.removeValue(obj, id);
						
						$(this).siblings("div.ecp_quantity")
															.hide()
															.find("input")
															.val("");
						Quantity.removeProductQuantity(id);
					}
					localroot.setSelectionsMainPrice();
				}					
			);
		},
		setDropdownEvents: function(){
			var localroot = this;
			var $multiples = $("div.multiple ul li");
			var elems = $multiples.find("select#main_select");
		
			elems.unbind("change");
			elems.change(function(){
				var selectedOptionID = $(this).find("option:selected").attr("title");
				var value = $(this).val();
				var selectedProducts = [];
				var selected = $("a.selected");
				var hiddenId = $(this).parents("li").find("input[type='hidden']");
				
				selected.each(function(i){
					selectedProducts[i] = $(this).attr("rel");
				});
				localroot.addValue(hiddenId, selectedOptionID);
				
				var parent = $(this).siblings("span.description");
				var data = {
					action: ECP_ACTIONS,
					method: "ecp_essays",
					values: JSON.stringify({"value":value, "selectedProducts":selectedProducts}),
					obj: parent,
					callback: essayGradingCallback
				};
				AJAXRequest.makeReq(data);
			});
		},
		setQuantityEvents: function(){
			var localroot = this;
			var selectedProducts = $("input#ecp_essays_quantity").val();
			
			if(selectedProducts != undefined && selectedProducts.length)
			{
				Quantity.productsQuantity = JSON.parse(selectedProducts);
			}
			
			$("div.ecp_quantity").find("input").bind("focusout", function(){
				Quantity.addProductQuantity($(this).attr("name"), $(this).val());
				localroot.setSelectionsMainPrice();
			});
		},
		changeElemsClass: function(elem, className, type){
			switch (type)
			{
				case "add":
					if(!elem.hasClass(className))
					{
						elem.addClass(className);
					}
				break;
				case "remove":
					if(elem.hasClass(className))
					{
						elem.removeClass(className);
					}
				break;
			}			
		},
		removeValue: function(obj, prodId){
			products.each(function(){
				if($(this).val() != "" && prodId == $(this).val())
				{
					$(this).val("");
				}
			});
		},
		addValue: function(obj, prodId){
			obj.val(prodId);
			products = $("input[name='products_ids[]']");
		},
		setSelectionsMainPrice: function(){
			var localroot = this;
			var $multiples = $("div.multiple");
			
			$multiples.each(function(){
				var selected = $(this).find("a.selected");
				var essays = $(this).find("#main_select");
				var mainPrice = $(this).find("span.int_selection_price");
				
				if(selected.length)
				{
					var parent_el = $(this).find("ul li a.selected").parent();
					var prices = parent_el.find("span:last");
					
					var total = 0;
					
					prices.each(function(){
						var price = parseInt($(this).text());
						var quantity = $(this).parent().siblings("div.ecp_quantity").find("input").val();
						quantity = parseInt(quantity);
						if(quantity > 1)
						{
							price *= quantity;
						}
							
						total += price;
					});

					if(parseInt(mainPrice.text()) != total)
					{
						mainPrice.fadeOut("fast", function(){
							mainPrice.text(total);
							$(this).fadeIn("fast");
							localroot.setTotalPrice();
						});   
					}
				}
				else
				{
					mainPrice.fadeOut("fast", function(){
						mainPrice.text(0);
						$(this).fadeIn("fast");
						localroot.setTotalPrice();
					});
				}
				
				if(essays.length)
				{
					if(parseInt(mainPrice.text()) != total)
					{
						var total = parseInt(essays.parent("li").find("span.int_price").text());
						mainPrice.fadeOut("fast", function(){
							mainPrice.text(total);
							$(this).fadeIn("fast");
							localroot.setTotalPrice();
						}); 
					}
				}
			});
		},
		setMainPrice: function(obj, value){
			var localroot = this;
			//value = Math.max(0, value);
			obj.fadeOut("fast", function(){
				obj.text(value);
				$(this).fadeIn("fast");
				localroot.setTotalPrice();
			});
		},
		setEssaysMainPrice: function(obj, value){
			var localroot = this;
			obj.fadeOut("fast", function(){
				obj.text(value);
				$(this).fadeIn("fast");
				localroot.setTotalPrice(false);
			});
		},
		setTotalPrice: function(calculate){
			//arguments[0] == null
			console.log(arguments.length)
			var $totalPrice = $("span.int_total_price");
			if(arguments.length > 0)
			{
				var $prices = $("span.int_selection_price");
			}
			else
			{
				checkCouponDiscount();
				var $prices = $("span.int_selection_price");			
			}
			
			var total = 0;

			$prices.each(function(){
				if($(this).text() !== "0")
				{
					total += parseInt($(this).text());
				}
			});
			$totalPrice.text(total);
		}
	};
	
	// QUANTITIES	
	var Quantity = {
		productsQuantity: {},
		addProductQuantity: function(product_id, quantity){
			this.productsQuantity[product_id] = {quantity: quantity};
			this.prepare();
		},
		
		removeProductQuantity: function(product_id){
			delete this.productsQuantity[product_id];
			this.prepare();
		},
		
		filterByProperty: function(arr, prop, value) {
		    return $.grep(arr, function (item) { return item[prop] == value });
		},
		
		prepare: function(){
			$("input#ecp_essays_quantity").val(this.serialize());	
		},
		
		isEmpty: function(){
			if(this.productsQuantity.length > 0)
			{
				return false;
			}
			return true;
		},
		
		serialize: function(){
			return JSON.stringify(this.productsQuantity);
		}
	};		
	
	// ADD FORM FIELDS ACTION EVENTS
	var FormFields = {
		creditCardControls: function(){
			var selected = $("#cc_type").val();
			var target = "am_ex";
			var visa = "visa", mc = "master_card";			
			$("#cc_type").change(function(){
				var value = $(this).val();
				var data = {
					action: ECP_ACTIONS,
					method: "ecp_cc_types",
					obj: $("#cc_number_wrapper"),
					values: JSON.stringify(value),
					callback: creditCardCallback
				};

				if(value == target)
				{
					AJAXRequest.makeReq(data);
					selected = target;
				}
				else if(selected == target)
				{
					AJAXRequest.makeReq(data);
					selected = value;
				}
				
			});
		}
	};
	
	// ADD COUPON CHECKING EVENTS
	var Coupon = {
		couponHtml: "<span class='hascoupon'></span>",
		couponData: new Object(),
		setCouponAction: function(){
			$("#add_coupon").click(function(e){
				e.preventDefault();
				var couponField = $("#course_coupon");
				if(FormUtils.fieldEmpty(couponField)){ return false; }
				var data = {
					action: ECP_ACTIONS,
					method: "ecp_check_coupon",
					obj: $(this),
					values: JSON.stringify(couponField.val()),
					callback: checkCouponCallback
				};

				AJAXRequest.makeReq(data);
			});
		},
		setTotalCouponDiscount: function(discount){
			var mainPrice = $("div.coupon_section span.int_selection_price").html(discount);
			FormButtons.setTotalPrice(false);
		},
		getCouponProducts: function(){
			var couponField = $("#course_coupon");
			if(!FormUtils.fieldEmpty(couponField))
			{
				var data = {
						action: ECP_ACTIONS,
						method: "ecp_check_coupon",
						obj: $("#add_coupon"),
						values: JSON.stringify(couponField.val()),
						callback: checkCouponCallback
					};
				AJAXRequest.makeReq(data);
			}
		}, 
		removeCouponDiscounts: function(){
			if(Coupon.couponData.products != undefined)
			{
				for(var i = 0, len = Coupon.couponData.products.length; i < len; i++)
				{
					var elem = $("a[rel="+Coupon.couponData.products[i].product_id+"]");
					
					if(elem.children("span.hascoupon").length)
					{ 
						elem.children("span.hascoupon").fadeOut(function(){
							$(this).remove();
						}); 
					}
				}
			}
		},
		removeCouponEssayDiscount: function(){
			if(Coupon.couponData.products != undefined)
			{
				for(var i = 0, len = Coupon.couponData.products.length; i < len; i++)
				{
					var selectedOption = $("#main_select option[title='"+Coupon.couponData.products[i].product_id+"']");
			
					if(!selectedOption.is(":selected"))
					{
						selectedOption.parents("li").find("span.hascoupon").fadeOut(function(){
							$(this).remove();
						}); 
					}
				}
			}
		}
	};
	
	// ADDS ALL EVENTS WITH DISCOUNT PROCESS
	var Discounts = {
		addDiscounts: function(prodId){
			var tempArr = [];
			for(var discIndex in discountArr[prodId].discounts)
			{ 
				var newPrice = parseInt(currentPrices[discIndex].price) - discountArr[prodId].discounts[discIndex];
				
				if(newPrice < 0)
				{
					newPrice = 0;
				}
				currentPrices[discIndex].price = newPrice.toString();
				tempArr.push({"id":discIndex, "price":newPrice});
			}
			HTMLEditor.setPrices(tempArr);
		},
		removeDiscounts: function(prodId){
			var tempArr = [];
			for(var discIndex in discountArr[prodId].discounts)
			{ 
				var newPrice = parseInt(currentPrices[discIndex].price) + parseInt(discountArr[prodId].discounts[discIndex]);
				if(pricesArr[discIndex].price < newPrice)
				{
					newPrice = pricesArr[discIndex].price;
				}
				currentPrices[discIndex].price = newPrice.toString();
				tempArr.push({"id":discIndex, "price":newPrice});
			}
			HTMLEditor.removeDiscounts(tempArr);
		}
	};
	
	var HTMLEditor = {
		htmlCode: "<span class='price basic_price'><span>$ </span><span class=''>%s</span></span>",
		setPrices: function(tempArr){
			for(var tIndex in tempArr)
			{				
				var prodId = tempArr[tIndex].id;
				var price = tempArr[tIndex].price;
				var refElem = $("a[rel='"+prodId+"']");
				
				var basicPrice = refElem.parents("li").find("span.basic_price");
				var refElemDD = $("#main_select :selected");
		
				if(!basicPrice.length && refElem.length)
				{
					var e = $(sprintf(this.htmlCode, pricesArr[prodId].price)).insertBefore(refElem.siblings("span.price"));
				}
				
				if(refElemDD.attr("title") == prodId)
				{
					var basicPriceDD = refElemDD.parents("li").find("span.basic_price");
					if(!basicPriceDD.length)
					{
						var activePriceDD = refElemDD.parents("li").find("span.price");
						var eDD = $(sprintf(this.htmlCode, pricesArr[prodId].price)).insertBefore(activePriceDD);
					}
					
					refElemDD.parents("li").find("span.int_price").text(price);
				}
				refElem.parents("li").find("span.int_price").text(price);
			}
		},
		removeDiscounts: function(tempArr){
			for(var tIndex in tempArr)
			{
				var prodId = tempArr[tIndex].id;
				var price = tempArr[tIndex].price;

				var refElem = $("a[rel='"+prodId+"']");
				var basicPrice = refElem.parents("li").find("span.basic_price");
				var refElemDD = $("#main_select :selected");

				if(currentPrices[prodId].price == pricesArr[prodId].price)
				{
					basicPrice.remove();
				}
				
				if(refElemDD.attr("title") == prodId)
				{
					var basicPriceDD = refElemDD.parents("li").find("span.basic_price");
					if(basicPriceDD.length && (currentPrices[prodId].price == pricesArr[prodId].price)) 
					{
						basicPriceDD.remove();
					}
					refElemDD.parents("li").find("span.int_price").text(price);
				}
				refElem.parents("li").find("span.int_price").text(price);
			}
		}
	};
	
	// ADDS EVENTS TO THE SHOPPING CART
	var Cart = {
		setAction: function(elem){
			var localroot = this;
			elem.click(function(e){
				e.preventDefault();
				var prodId = $(this).attr("href");
				var data = {
					action: ECP_ACTIONS,
					method: "ecp_delete_product",
					obj: $(this),
					values: JSON.stringify(prodId),
					callback: deleteProductCallback
				};
				localroot.deleteProduct(data);
			});
		},
		deleteProduct: function(data){
			AJAXRequest.makeReq(data);
		}
	};
	
	var Error = {
		hasError: false,
		displayError: function(element, errorElement, msg){
			element.find(errorElement + " span").html(msg).parent().fadeIn();
		},
		removeError: function(element, errorElement){
			var notes = element.find(errorElement + " span");
			
			if(notes.length)
			{
				notes.parent().fadeOut();
			}
		}
	};
	
	// CALLBACKS
	
	function discountCallback(msg, obj)
	{
		if(msg != null)
		{
		}
	}
	
	function deleteProductCallback(msg, obj)
	{
		if(msg != null)
		{
			var htmlArr = StringUtils.arrayOfStrings(msg, "|");
			var table = htmlArr[0];
			var total_price = htmlArr[1];
			obj.parents("table").fadeOut(function(){
				$(this).html(table).fadeIn();
				Cart.setAction($("td.remove_product a"));
			});
		
			$("span.total_price").fadeOut(function(){
				$(this).html(total_price).fadeIn();
			});
		}
	}
	
	function essayGradingCallback(msg, obj)
	{
		if(msg != null)
		{
			obj.fadeIn(function(){
				$(this).html(msg);
				var value = obj.find("span.int_price");
				var main_price = obj.parents("div.selection").find("span.int_selection_price");
				
				Coupon.removeCouponEssayDiscount();
				
				if(checkCouponDiscountOnEssays(obj))
				{
					FormButtons.setEssaysMainPrice(main_price, value.text());
					checkCouponDiscount();
					return;
				}
	
				FormButtons.setMainPrice(main_price, value.text());
			});
		}
	}
	
	function creditCardCallback(msg, obj)
	{
		if(msg != null)
		{
			var htmlObj = JSON.parse(msg);
			var ccNum = $("#cc_number_wrapper");
			var ccv2 = $("#cvv2_wrapper");
			ccNum.fadeOut(function(){
				$(this).html(htmlObj["cc_number"]).fadeIn();
				bindTextFieldEvents();
			});
			ccv2.fadeOut(function(){
				$(this).html(htmlObj["ccv2"]).fadeIn();
			});
		}
	}
	
	function checkCouponCallback(msg, obj)
	{	
		if(msg != null)
		{
			var message = JSON.parse(msg);

			if(message.Error)
			{
				Error.hasError = true;
				Error.displayError($("div.coupon_selection"), "div.couponerrornote", message.Error);
				Coupon.removeCouponDiscounts();
				return;
			}
			
			if(Error.hasError)
			{ 
				Error.removeError($("div.coupon_selection"), "div.couponerrornote");
				Error.hasError = false;
			}
			
			Coupon.removeCouponDiscounts();
			
			Coupon.couponData = message;
			for(var i = 0, len = Coupon.couponData.products.length; i < len; i++)
			{
				var elem = $("a[rel="+Coupon.couponData.products[i].product_id+"]");
				
				var selectedOption = $("#main_select option[title='"+Coupon.couponData.products[i].product_id+"']");
				
				if(selectedOption.is(":selected"))
				{
					$(Coupon.couponHtml).hide().appendTo(selectedOption.parents("li")).fadeIn();
				}
				
				$(Coupon.couponHtml).hide().appendTo(elem).fadeIn();
			}			
			checkCouponDiscount();
		}
	}
	
	function checkCouponDiscountOnEssays(holder)
	{
		if(Coupon.couponData.products != undefined)
		{
			for(var i = 0, len = Coupon.couponData.products.length; i < len; i++)
			{
				var selectedOption = holder.siblings("select#main_select").find("option[title='"+Coupon.couponData.products[i].product_id+"']");
				
				if(selectedOption.is(":selected"))
				{
					$(Coupon.couponHtml).hide().appendTo(selectedOption.parents("li")).fadeIn();
					return true;
				}
			}
		}
		return false;
	}
	
	function bindTextFieldEvents()
	{
		$(".cc_num").bind({
			keydown: function(e){
				if( e.which!=8 && e.which!=0 && e.which != 9 && (e.which<48 || e.which>57) && (e.which < 96 || e.which > 105))
			    {
				    // TODO: display error message
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
			}		
		});
	}
	
	function checkCouponDiscount()
	{
		if(Coupon.couponData.products != undefined)
		{
			var couponPrice = Coupon.couponData.coupon_price;
			var couponDiscount = 0;
			for(var i = 0, len = Coupon.couponData.products.length; i < len; i++)
			{
				//if(ArrayUtils.inInputArray(Coupon.couponData.products[i].product_id, products))
				var is_in = ArrayUtils.inObjectArray(Coupon.couponData.products[i].product_id, Quantity.productsQuantity);
				
				if(is_in)
				{
					couponDiscount -=  couponPrice * (is_in.quantity);
				}
			}
			
			Coupon.setTotalCouponDiscount(couponDiscount);
		}
	}
	
	// ajax call object
	var AJAXRequest = {
			path: AJAX_ADMIN_PATH,
			makeReq: function(data){
				$.ajax({
					type: "POST",
					url: this.path,
					data: data, 
					beforeSend: function(){
						/*var $loader = $("<div class='ajax_loader'></div>");
						data.obj.prepend($loader);
						$loader.css({
							//"height" : data.obj.css("height"),
							//"width" : data.obj.css("width"),
							"position" : "absolute"
						});*/
					},
					success: function (msg){
						//data.obj.find("div.ajax_loader").fadeOut("slow", function(){
							//$(this).remove("div.ajax_loader");
							data.callback(msg, data.obj);
						//});
					},
					error: function(msg){
					}
				});
			}
		};
	
	// UTILITIES
	var StringUtils = {
		findPrice: function(str){
			return str.substr(str.indexOf("$") + 2);
		},
		arrayOfStrings: function(str, char){
			return str.split(char);
		}
	};
	
	var FormUtils = {
		fieldEmpty: function(field){
			if($.trim(field.val()) == "")
			{
				return true;
			}
			return false;
		}
	};

	var ArrayUtils = {
		arrayCompare: function(a1, a2){
			if (a1.length != a2.length) return false;
		    var length = a2.length;
		    for (var i = 0; i < length; i++) {
		        if (a1[i] !== a2[i]) return false;
		    }
		    return true;
		},
		inArray: function(needle, haystack){
			var length = haystack.length;
		    for(var i = 0; i < length; i++) {
		        if(typeof haystack[i] == 'object') {
		            if(this.arrayCompare(haystack[i], needle)) return true;
		        } else {
		            if(haystack[i] == needle) return true;
		        }
		    }
		    return false;
		},
		inInputArray: function(needle, haystack){
			var length = haystack.length;
		    for(var i = 0; i < length; i++) {
	            if(haystack[i].value == needle) return true;
		    }
		    return false;
		},
		inObjectArray: function(needle, haystack){
		    if(needle in haystack)
		    { 
	            return haystack[needle];
		    }
		    return false;
		},
		removeElement: function(obj, index){
			return delete obj[index];
		}
	};
	
	Object.size = function(obj) {
	    var size = 0, key;
	    for (key in obj) {
	        if (obj.hasOwnProperty(key)) size++;
	    }
	    return size;
	};

	
	// ACTIVATION
	if($("a.selected").length)
	{
		FormButtons.allowMultiple = true;
		FormButtons.setTotalPrice();
		Coupon.getCouponProducts();
	}
	FormButtons.init();
	FormFields.creditCardControls();
	Cart.setAction($("td.remove_product a"));
});