Galleria.themes.create({
    name: 'classic',
    author: 'Galleria',
    version: '1.1',
    css: 'galleria.classic.css',
    defaults: {
        transition: 'slide'
    },
    init: function(options) {

        this.jQuery('loader').show().fadeTo(200, .4);
        this.jQuery('counter').show().fadeTo(200, .4);
        
        this.jQuery('thumbnails').children().hover(function() {
            jQuery(this).not('.active').fadeTo(200, 1);
        }, function() {
            jQuery(this).not('.active').fadeTo(400, .4);
        }).not('.active').css('opacity',.4);
        
        this.jQuery('container').hover(this.proxy(function() {
            this.jQuery('image-nav-left,image-nav-right,counter').fadeIn(200);
        }), this.proxy(function() {
            this.jQuery('image-nav-left,image-nav-right,counter').fadeOut(500);
        }));
        
        this.jQuery('image-nav-left,image-nav-right,counter').hide();
        
        var elms = this.jQuery('info-link,info-close,info-text').click(function() {
            elms.toggle();
        });
        
        this.bind(Galleria.LOADSTART, function(e) {
            if (!e.cached) {
                this.jQuery('loader').show().fadeTo(200, .4);
            }
            if (this.hasInfo()) {
                this.jQuery('info').show();
            } else {
                this.jQuery('info').hide();
            }
            jQuery(e.thumbTarget).parent().addClass('active').css('opacity',1)
                .siblings('.active').removeClass('active').fadeTo(400,.4);
        });

        this.bind(Galleria.LOADFINISH, function(e) {
        	jQuery(".smp").remove();
        	this.jQuery('loader').fadeOut(200);
            jQuery(e.thumbTarget).css('opacity',1);
            if(this.getData().altVideo.toLowerCase().indexOf(".flv")!=-1){
            	jQuery(".galleria-stage").append("<div class='smp'>"+generatePlayer(this.getData().altVideo)+"</div>");
            }
        });
    }
});

function generatePlayer(videoPath){
	var outStr='<object height="365" align="middle" width="620" id="smpClean" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">';
	outStr+='<param value="true" name="allowFullScreen"/>';
	outStr+='<param value="transparent" name="wmode"/>';
	outStr+='<param value="'+themePath+'/smpClean.swf?filePath='+videoPath+'" name="movie"/>';
	outStr+='<embed height="365" width="620" src="'+themePath+'/smpClean.swf?filePath='+videoPath+'" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowfullscreen="true" wmode="transparent"></object>';
	return outStr;
}