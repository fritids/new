jQuery(function($){
	jQuery.ajaxSettings.traditional = true;
	
	var ECP_ACTIONS = "ecp_actions";
	
	var $form = $("#ecp_cart_form");
	var $next = $("#ecp_next_step");

	var active_prices = $("a.selected");
	var activePrices = {};
	if(activePrices.length)
	{
		active_prices.each(function(){
			activePrices[$(this).attr("rel")] = $(this).parent().find("span.int_price").text();
		});
	}
	
	console.log(discountArr);
	
	$next.click(function(e){
		e.preventDefault();
		$form.submit();
	});
	// ADDS INITIAL (SELECTION) PART ACTIONS OF THE SHOPPING PROCESS
	var FormButtons = {
		allowMultiple: false,
		//on initialisation 
		init: function(){
			this.resetValues();
			this.setSinglesEvents();
			this.setMultiplesEvents();
			this.setDropdownEvents();
		},
		resetValues: function(){
			$("ul li input[type='hidden']#product_price").val("");
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
			
			var localroot = this;
			elems.click(function(e){
					e.preventDefault();
					e.stopPropagation();
					
					var clicked;
					
					elems.each(function(){
						if($(this).data("click"))
						{
							clicked = $(this);
						}
					});
				
					if(clicked != null)
					{
						localroot.changeElemsClass(clicked, "selected", "remove");
						localroot.setValue(clicked.siblings("input[type='hidden']#product_price"), "");
						clicked.find("span.cart_added").fadeOut("fast");
						clicked.data("click", false);
						Discounts.setDiscount(clicked, "remove");
					}
					
					$(this).data("click", true);
					var value = pricesArr[$(this).attr("rel")].price;
					var obj = $(this).siblings("input[type='hidden']#product_price");
					var main_price = $(this).parents("div.selection").find("span.int_selection_price");
					localroot.changeElemsClass($(this), "selected", "add");
					$(this).find("span.cart_added").fadeIn("fast");
				
					if(!localroot.allowMultiple)
					{
						$("div.errornote").fadeOut();
						localroot.allowMultiple = true;
						localroot.setMultiplesEvents();
						localroot.setDropdownEvents();
					}	
					
					Discounts.setDiscount($(this), "add");
					localroot.setValue(obj, value);
					localroot.setMainPrice(main_price, value);
					localroot.setSelectionsMainPrice();
				}
			);
		},
		setMultiplesEvents: function(){
			var $multiples = $("div.multiple ul li");
			var elems = $multiples.find("a.main_select");
			if(this.allowMultiple)
			{
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
				
				var localroot = this;
			
				elems.unbind("click");
				elems.click(
					function(e){
						e.preventDefault();
						e.stopPropagation();
						if(!$(this).hasClass("selected"))
						{
							var id = $(this).attr("rel");
							activePrices[id] = $(this).parent().find("span.int_price").text();
							localroot.changeElemsClass($(this), "selected", "add");
							$(this).find("span.cart_added").fadeIn("fast");
			
							Discounts.setDiscount($(this), "add");
							
							var main_price = $(this).parents("div.selection").find("span.int_selection_price");
							var ids = $(this).parents("ul").find("a.selected");
							var value = 0;
							ids.each(function(){
								var obj = $(this).siblings("input[type='hidden']#product_price");
								var prodPrice = activePrices[$(this).attr("rel")];
								value += parseInt(prodPrice);
								localroot.setValue(obj, prodPrice);
							});							
							localroot.setSelectionsMainPrice();
						}
						else
						{
							var id = $(this).attr("rel");
							$(this).find("span.cart_added").fadeOut("fast");
							localroot.changeElemsClass($(this), "selected", "remove");
							Discounts.setDiscount($(this), "remove");
							var main_price = $(this).parents("div.selection").find("span.int_selection_price");
							var value = parseInt(main_price.text());
							var obj = $(this).siblings("input[type='hidden']#product_price");
							var prodPrice = $(this).parent().find("span.int_price").text();
							value -= parseInt(prodPrice);
							
							if(!ArrayUtils.removeElement(activePrices, id)){ console.log("Can't delete property."); }
							localroot.setValue(obj, prodPrice);
							localroot.setSelectionsMainPrice();
						}
					}
				);
			}
			else
			{
				var warning = "You have to select an Online SAT Course first.";
				var notes = $("div.errornote");
				elems.click(function(e){
					e.preventDefault();
					e.stopPropagation();
					if(notes.length)
					{
						notes.fadeOut();
					}
					$(this).parents("div.selection").find("div.errornote").html(warning).fadeIn();
				});
			}
		},
		setDropdownEvents: function(){
			var $multiples = $("div.multiple ul li");
			var elems = $multiples.find("select#main_select");
			console.log(this.allowMultiple);
			if(this.allowMultiple)
			{
				elems.unbind("change");
				elems.change(function(){
					var value = $(this).val();
					var selectedProducts = [];
					var selected = $("a.selected");
					
					selected.each(function(i){
						selectedProducts[i] = $(this).attr("rel");
					});
					var parent = $(this).siblings("span.description");
					var data = {
						action: ECP_ACTIONS,
						method: "ecp_essays",
						values: JSON.stringify({"value":value, "selectedProducts":selectedProducts}),
						obj: parent,
						callback: essayGradingCallback
					}
					AJAXRequest.makeReq(data);
				});
			}
			else
			{
				var warning = "You have to select an Online SAT Course first.";
				var notes = $("div.errornote");
				elems.change(function(){
					if(notes.length)
					{
						notes.fadeOut();
					}
					$(this).parents("div.selection").find("div.errornote").html(warning).fadeIn();
				});
			}
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
		setValue: function(obj, value){
			obj.val(value);
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
					var prices = $(this).find("ul li a.selected").parent().find("span:last");
					var total = 0;
					prices.each(function(){
						total += parseInt($(this).text());
					});
					if(parseInt(mainPrice.text()) != total)
					{
						mainPrice.fadeOut("fast", function(){
							mainPrice.text(total);
							$(this).fadeIn("fast");
						});   
					}
				}
				else
				{
					mainPrice.text(0);
				}
				
				if(essays.length)
				{
					if(parseInt(mainPrice.text()) != total)
					{
						var total = parseInt(essays.parent("li").find("span.int_price").text());
						mainPrice.fadeOut("fast", function(){
							mainPrice.text(total);
							$(this).fadeIn("fast");
						});   
					}
				}
				
			});
			localroot.setTotalPrice();
		},
		setMainPrice: function(obj, value){
			var localroot = this;
			obj.fadeOut("fast", function(){
				obj.text(value);
				$(this).fadeIn("fast");
				localroot.setTotalPrice();
			});
		},
		setTotalPrice: function(){
			var $totalPrice = $("span.int_total_price");
			var $prices = $("span.int_selection_price");
			var total = 0;
			$prices.each(function(){
				if($(this).text() !== "")
				{
					total += parseInt($(this).text());
				}
			});
			$totalPrice.text(total);
		}
	}
	
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
				}

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
	}

	// ADDS ALL EVENTS WITH DISCOUNT PROCESS
	var Discounts = {
		setDiscount: function(selObj, action){
			var id = selObj.attr("rel");
			var dProducts = discountArr[id]["discounts"];
            for(var dIndex in discountArr[id]["discounts"])
            {
            	if(discountArr[id]["discounts"][dIndex] != "")
            	{
            		this.updatePrice(id, dIndex, action);
            	}
            }
            
		},
		updatePrice: function(objId, dIndex, action){
			
			switch(action)
			{
				case "add":
					this.addDiscount(objId, dIndex);
				break;
				case "remove":
					this.removeDiscount(objId, dIndex);
				break;
			}
		},
		addDiscount: function(objId, dIndex){
			var dp = $("a[rel='"+dIndex+"']"); // discount price element
			var dpSel = $("#main_select option:selected");
			
			var bp = dp.siblings("span.basic_price"); // basic price
			if(bp.length)
			{
				var priceSpan = dp.parent().find("span.int_price");
				var discountPrice = parseInt(priceSpan.text()) - parseInt(discountArr[objId]["discounts"][dIndex]);
				priceSpan.text(discountPrice);
			}
			else if(!bp.length)
			{
				var priceSpan = dp.parent().find("span.int_price");
				var price = pricesArr[dIndex].price;
				var discountPrice = parseInt(pricesArr[dIndex].price) - parseInt(discountArr[objId]["discounts"][dIndex]);
				var html = "<span class='price basic_price'><span>$ </span><span class=''>"+parseInt(price)+"</span></span>";
				priceSpan.parent().before(html);	
				priceSpan.text(discountPrice);
			}
			
			if(dpSel.attr("label") == dIndex)
			{
				var priceSpan = dpSel.parents("li").find("span.int_price");
				var price = pricesArr[dIndex].price;
				var discountPrice = parseInt(pricesArr[dIndex].price) - parseInt(discountArr[objId]["discounts"][dIndex]);
				var html = "<span class='price basic_price'><span>$ </span><span class=''>"+parseInt(price)+"</span></span>";
				priceSpan.parent().before(html);	
				priceSpan.text(discountPrice);
				
				console.log(dpSel, dIndex);
			}
		},
		removeDiscount: function(objId, dIndex){ // the most important function 
			var dp = $("a[rel='"+dIndex+"']"); // discount price element
			var dpSel = $("#main_select option:selected");
			
			var bp = dp.siblings("span.basic_price"); // basic price
			if(bp.length)
			{
				var singleDiscount = discountArr[objId]["discounts"][dIndex]
				var price = pricesArr[dIndex].price;
				var priceSpan = dp.parent().find("span.int_price");
				var momentPrice = parseInt(priceSpan.text()) + parseInt(singleDiscount);
				if(price != momentPrice)
				{
					priceSpan.text(momentPrice);
				}
				else
				{
					bp.remove();
					priceSpan.text(price);
				}
			}
			var bpSel = dpSel.parents("li");
			var bpSelElem = bpSel.find("span.basic_price");
			if(bpSel.attr("label") == dIndex)
			{
				var singleDiscount = discountArr[objId]["discounts"][dIndex];
 				var price = pricesArr[dIndex].price;
 				var priceSpan = bpSel.find("span.int_price");
 				var momentPrice = parseInt(priceSpan.text()) + parseInt(singleDiscount);
	
 				if(price != momentPrice)
 				{
 					priceSpan.text(momentPrice);
 				}
 				else
 				{
 					bpSelElem.remove();
 					priceSpan.text(price);
 				}
			}
		}
	}
	
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
					obj: $(this).parents("tr"),
					values: JSON.stringify(prodId),
					callback: deleteProductCallback
				}
				localroot.deleteProduct(data);
			});
		},
		deleteProduct: function(data){
			AJAXRequest.makeReq(data);
		}
	}
	
	// CALLBACKS
	
	function discountCallback(msg, obj)
	{
		if(msg != null)
		{
			console.log(msg);
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
	
	function bindTextFieldEvents()
	{
		$(".cc_num").bind({
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
				console.log(total);
				hidden.val(total);
			}		
		});
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
						$loader = $("<div class='ajax_loader'></div>")
						data.obj.prepend($loader);
						$loader.css({
							/*"height" : data.obj.css("height"),
							"width" : data.obj.css("width"),*/
							"position" : "absolute"
						});
					},
					success: function (msg){
						data.obj.find("div.ajax_loader").fadeOut("slow", function(){
							$(this).remove("div.ajax_loader");
							data.callback(msg, data.obj);
						});
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
	}

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
		            if(arrayCompare(haystack[i], needle)) return true;
		        } else {
		            if(haystack[i] == needle) return true;
		        }
		    }
		    return false;
		},
		removeElement: function(obj, index){
			return delete obj[index];
		}
	}
	
	// ACTIVATION
	if($("a.selected").length)
	{
		FormButtons.allowMultiple = true;
		FormButtons.setTotalPrice();
	}
	FormButtons.init();
	FormFields.creditCardControls();
	Cart.setAction($("td.remove_product a"));
});