jQuery(function() {
	abuzz_cart_init()
	jQuery('.add_to_cart_wrap input.add_to_cart').click(
			function() {
				var productIDValSplitter = (this.id).split('_');
				var productIDVal = productIDValSplitter[1];
				
				var animatedElem=jQuery(this).parents("div.add_to_cart_wrap");
				/*if(jQuery(this).parents("div").find("img").length>0){
					animatedElem=jQuery(this).parents("div").find("img");
				}else{
					animatedElem=jQuery(this);
				}*/
				
				var productX = animatedElem.offset().left;
				var productY = animatedElem.offset().top;
				//alert(animatedElem.attr("src")+" - "+animatedElem.offset().left+" - "+productX)
				animatedElem=animatedElem.clone();
				
				
				
				if (jQuery('#cart_content #qty-' + productIDVal).length == 0) {
					var basketX = jQuery("#cart_content").offset().left;
					var basketY = jQuery("#cart_content").offset().top;
				} else {
					//var basketX = jQuery('#cart_content' + productIDVal).offset().left;
					//var basketY = jQuery('#cart_content' + productIDVal).offset().top;
					var basketX = jQuery('#cart_content').offset().left;
					var basketY = jQuery('#cart_content').offset().top;
				}
				var gotoX = basketX - productX;
				var gotoY = basketY - productY;

				var newImageWidth = jQuery("#cart_content").width(); // jQuery(this).width() / 2;
				var newImageHeight = jQuery("#cart_content").height(); //jQuery(this).height() / 2;
				
				
				
				
				animatedElem.prependTo(jQuery(this).parent()).css( {
					'position' : 'absolute',
					'background' : 'none transparent',
					'border' : 'solid 1px #000'
				}).animate( {
					opacity : 0.8
				}, 100).animate( {
					opacity : 0.1,
					marginLeft : gotoX,
					marginTop : gotoY,
					width : newImageWidth,
					height : newImageHeight
				}, 1200, function() {
					jQuery(this).remove();
					jQuery.ajax( {
						type : 'POST',
						url : adminAjax + '?action=auzz_card_add',
						data : 'productId=' + productIDVal,
						success : function(msg) {
							jQuery('.abuzz_cart_details').html(msg);
							abuzz_cart_init()
						}
					});
					// $('#notificationsLoader').html('<img
					// src='images/loader.gif'>');
					});

			});

})
function abuzz_cart_init() {
	jQuery('.cart_content .remove_link').click(function(e) {
		e.preventDefault();
		var pid = jQuery(this).attr('id').split('_')[1];
		//$("p").wrapInner("<b></b>"); text-decoration: line-through;
		var li=jQuery(jQuery(this).parents("li")[0]);
		jQuery(jQuery(this).parents("li")[0]).css({"text-decoration":"line-through"});
		jQuery.ajax( {
			type : 'POST',
			url : adminAjax + '?action=auzz_card_remove',
			data : 'productId=' + pid,
			success : function(msg) {
				li.slideUp(500,function(){
					jQuery('.abuzz_cart_details').html(msg);
					abuzz_cart_init()
				})	
				
			}
		});
	})
}