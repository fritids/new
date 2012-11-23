Galleria.transitions.add('lightbox', function(params, complete) {
    var sq = this.jQuery('square');
    var anim = function() {
        var img = jQuery(params.next).show().css('visibility','hidden');
        var w = params.next.width + 20;
        var h = params.next.height + 20;
        sq.show().animate({
            width: w,
            height: h,
            top: params.next.style.top,
            left: params.next.style.left
        },{
            easing: 'swing',
            duration: params.speed,
            complete: function() {
                complete();
                img.hide().css('visibility','visible').fadeIn(params.speed, function() {
                    sq.hide();
                });
            }
        });
    };
    if (params.prev) {
        jQuery(params.prev).hide();
    }
    anim();
});

Galleria.themes.create({
    name: 'lightbox',
    author: 'Galleria',
    version: '1.0',
    css: 'galleria.lightbox.css',
    defaults: {
        carousel: false,
        transition: 'lightbox',
        transition_speed: 200,
        image_margin: 10,
        max_scale_ratio: 1,
        thumb_quality: true,
        opacity: .8,
        show: false
    },
    init: function(options) {
        var showing = false;
        var anim = options.transition == 'lightbox';
        var resize = this.proxy(function() {
            this.rescale();
            var img = this.controls.getActive().image;
            if (img) {
                var t;
                if (anim) {
                    this.jQuery('square').css({
                        width: img.width,
                        height: img.height,
                        left: img.style.left,
                        top: img.style.top
                    });
                }
                window.clearTimeout(t);
                this.jQuery('info').hide();
                t = window.setTimeout(this.proxy(function() {
                    var d = this.getDimensions(img);
                    this.jQuery('info').fadeIn(this.options.transition_speed).css({
                        bottom: d.top - 8
                    });
                }), 200);
            }
        });
        var re = function(elem) {
            return elem.className.replace(/.*\-(right|left)/,'jQuery1');
        };
        var close = this.proxy(function() {
            showing = false;
            this.jQuery('popup,close,square').hide();
            this.jQuery('overlay').fadeOut(options.transition_speed);
            this.detachKeyboard();
            jQuery(window).unbind('resize', resize);
            jQuery(this.controls.getActive().image).css('visibility','hidden');
            this.setActive(null);
        });

        this.addElement('overlay','popup','square','close');
        this.jQuery('overlay').css('opacity', options.opacity);
        this.append({
            'images': 'square',
            'popup': 'container',
            'target': 'thumbnails-container',
            'container' : 'close'
        });
        
        this.jQuery('overlay,popup').hide().appendTo('body');

        this.jQuery('square').css({
            top: this.stageHeight/2 - 200,
            left: this.stageWidth/2 - 200
        }).hide();
        
        this.jQuery('close').text('close').bind('click',close).hide();
        this.jQuery('image-nav').show();
        this.jQuery('image-nav-right,image-nav-left').css('opacity',1).hover(function() {
            var o = { opacity: 0.7 };
            o[re(this)] = -47;
            jQuery(this).animate(o, 200);
        }, function() {
            var o = { opacity: 1 };
            o[re(this)] = -45;
            jQuery(this).animate(o, 200);
        });

        this.bind(Galleria.LOADSTART, function(e) {
            if (!showing) {
                showing = true;
                this.jQuery('overlay,popup,close').show();
                if (anim) {
                    this.jQuery('square').show();
                }
                jQuery(window).bind('resize',resize);
                resize();
                this.attachKeyboard({
                    left: this.prev,
                    right: this.next,
                    backspace: close,
                    escape: close
                });
            }
            this.jQuery('info').hide();
            if (!e.cached) {
                this.jQuery('loader').show();
            }
        });
        this.bind(Galleria.LOADFINISH, function(e) {
            var d = this.getDimensions(e.imageTarget);
            var i = this.jQuery('info');
            this.jQuery('loader').hide();
            window.setTimeout(function() {
                i.css('bottom', d.top - 8).fadeIn(options.transition_speed);
            }, anim ? options.transition_speed : 1);
        });
    }
});