var InputMask = function()
{
	return {
		init: function()
		{
			jQuery("input[mask]").keypress(function(event)
				{
					var kc = event.keyCode || event.charCode;

					/* don't mess with non printable keys */
					if (kc < 32) return;

					var o = jQuery(this);
					var mask = o.attr("mask");
					var i = o.val().length;

					while (true)
					{
						/* ignore anything too long */
						if (i >= mask.length)
						{
							var selecttxt = '';
							if (window.getSelection)
							    selecttxt = window.getSelection();
							
							if (document.getSelection && selecttxt =='') 
							    selecttxt = document.getSelection();
							    
							if (document.selection  && selecttxt =='') 
							    selecttxt = document.selection.createRange().text;

							if(selecttxt == '')
								event.preventDefault();
							return;
						}
						else if (mask.charAt(i) == '#')
						{
							if (kc < 48 || kc > 58)
								event.preventDefault();

							break;
						}
						o.val(o.val() + mask.charAt(i++));
						
					}
				}).keyup(function(event){});
		}
	}
;}();