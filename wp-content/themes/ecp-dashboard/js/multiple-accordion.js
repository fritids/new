/*
 * jQuery UI Multilevel Accordion v.1
 * 
 * Copyright (c) 2011 Pieter Pareit
 *
 * http://www.scriptbreaker.com
 *
 */

//plugin definition
(function($){
    $.fn.extend({

    //pass the options variable to the function
    accordion: function(options) {
        
		var defaults = {
			speed: 300
		};

		// Extend our default options with those provided.
		var opts = $.extend(defaults, options);
		//Assign current element to variable, in this case is UL element
 		var $this = $(this);
 		
		//avoid jumping to the top of the page when the href is an #
 		$this.find("li").each(function() {
 			if($(this).find("ul").size() != 0){
 				if($(this).find("a:first").attr('href') == "#"){
 		  			$(this).find("a:first").click(function(){return false;});
 		  		}
 			}
 		});
		
		//collapse menu
		$this.find("ul").each(function() {
			$(this).slideUp(opts.speed);
		});

 		//open active level
 		$this.find("li.current-menu-item").each(function() {
			$(this).parents("li").find("a:first").addClass("opened");
 			$(this).parents("ul").slideDown(opts.speed);
 		});

  		$this.find("li a").click(function() {
  			if($(this).parent().find("ul").size() != 0){
  				if($(this).parent().find("ul:first").is(":visible")){
					$(this).removeClass("opened");
  					$(this).parent().find("ul:first").slideUp(opts.speed);
  				}else{
					$(this).addClass("opened");
  					$(this).parent().find("ul:first").slideDown(opts.speed);
  				}
  			}
  		});
    }
});
})(jQuery);