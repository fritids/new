/*
 * xLazyLoader 1.3 - Plugin for jQuery
 * 
 * Load js, css and images asynchron and get different callbacks
 *
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Depends:
 *   jquery.js
 *
 *  Copyright (c) 2008 Oleg Slobodskoi (ajaxsoft.de)
 */
(function(a){function m(){function k(b,a){m[b](a,function(b){b=="error"?g.push(a):i.push(a)&&c.each(a);o()},"lazy-loaded-"+(c.name?c.name:(new Date).getTime()))}function h(a){c.complete(a,i,g);c[a](a=="error"?g:i);clearTimeout(p);clearTimeout(r)}function o(){i.length==j.length?h("success"):i.length+g.length==j.length&&h("error")}function q(){g.push(this.src);o()}var m=this,c,i=[],g=[],p,r,n,j=[];this.init=function(b){b&&(c=a.extend({},a.xLazyLoader.defaults,b),n={js:c.js,css:c.css,img:c.img},a.each(n,
function(a,b){typeof b=="string"&&(b=b.split(","));j=j.concat(b)}),j.length?(c.timeout&&(p=setTimeout(function(){var b=i.concat(g);a.each(j,function(e,f){a.inArray(f,b)==-1&&g.push(f)});h("error")},c.timeout)),a.each(n,function(b,e){a.isArray(e)?a.each(e,function(a,d){k(b,d)}):typeof e=="string"&&k(b,e)})):h("error"))};this.js=function(b,c,e){var f=a('script[src*="'+b+'"]');if(f.length)f.attr("pending")?f.bind("scriptload",c):c();else{var d=document.createElement("script");d.setAttribute("type","text/javascript");
d.setAttribute("src",b);d.setAttribute("id",e);d.setAttribute("pending",1);d.onerror=q;a(d).bind("scriptload",function(){a(this).removeAttr("pending");c();setTimeout(function(){a(d).unbind("scriptload")},10)});var k=!1;d.onload=d.onreadystatechange=function(){if(!k&&(!this.readyState||/loaded|complete/.test(this.readyState)))k=!0,d.onload=d.onreadystatechange=null,a(d).trigger("scriptload")};l.appendChild(d)}};this.css=function(b,c,e){if(a('link[href*="'+b+'"]').length)c();else{var f=a('<link rel="stylesheet" type="text/css" media="all" href="'+
b+'" id="'+e+'"></link>')[0];a.browser.msie?f.onreadystatechange=function(){/loaded|complete/.test(f.readyState)&&c()}:a.browser.opera?f.onload=c:(location.hostname.replace("www.",""),/http:/.test(b)&&/^(\w+:)?\/\/([^\/?#]+)/.exec(b),c());l.appendChild(f)}};this.img=function(a,c){var e=new Image;e.onload=c;e.onerror=q;e.src=a};this.disable=function(b){a("#lazy-loaded-"+b,l).attr("disabled","disabled")};this.enable=function(b){a("#lazy-loaded-"+b,l).removeAttr("disabled")};this.destroy=function(b){a("#lazy-loaded-"+
b,l).remove()}}a.xLazyLoader=function(a,h){typeof a=="object"&&(h=a,a="init");(new m)[a](h)};a.xLazyLoader.defaults={js:[],css:[],img:[],name:null,timeout:2E4,success:function(){},error:function(){},complete:function(){},each:function(){}};var l=document.getElementsByTagName("head")[0]})(jQuery);
