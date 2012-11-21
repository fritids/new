jQuery(function(){
	var widgetCache=jQuery('.wpac_sortable ul').html();
	var wordpressWidgetCache=jQuery('.wordpress_sortable ul').html();
	var count=0;
	jQuery('.wpac_widget_area li').each(function(){
		jQuery(this).find('input, select').each(function(){
				var tempName=jQuery(this).attr('name');
				if(tempName.indexOf('wpac')==-1){
					jQuery(this).attr('name','wpac['+tempName+'_'+count+']');
				}
				
			})
			count++;
	})
	jQuery('.wpac_widget_area').sortable({
		handle:"span.label",
		cursor: 'move',
		receive: function(e) {
			jQuery(e.target).find('div').css('display','block')
			jQuery(e.target).find('select').removeAttr('onchange')
			jQuery(e.target).find('input, select').each(function(){
				var tempName=jQuery(this).attr('name');
				if(tempName.indexOf('wpac')==-1){
					jQuery(this).attr('name','wpac['+tempName+'_'+count+']');
				}
				
			})
			count++;
		}
	})
	jQuery('.wpac_sortable ul, .wpac_widget_area, .wordpress_sortable ul').sortable({
		handle:"span.label",
		cursor: 'move',
		connectWith: '.wpac_widget_area',
	 	remove: function() {
			jQuery('.wpac_sortable ul').html(widgetCache)
			jQuery('.wordpress_sortable ul').html(wordpressWidgetCache)
		}
	});
})