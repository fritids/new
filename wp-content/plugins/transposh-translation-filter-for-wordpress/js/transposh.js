/*
 * Transposh v0.7.6
 * http://transposh.org/
 *
 * Copyright 2011, Team Transposh
 * Licensed under the GPL Version 2 or higher.
 * http://transposh.org/license
 *
 * Date: Tue, 02 Aug 2011 03:11:42 +0300
 */
(function(a){function u(c,b){if(a.trim(b).length!==0){var d=function(){var c=a(this).attr("id").substr(a(this).attr("id").lastIndexOf("_")+1),d=a("#"+f+"img_"+c);a("#"+f+c).attr("data-source",1);d.removeClass("tr-icon-yellow").removeClass("tr-icon-green").addClass("tr-icon-yellow")};a("*[data-token='"+c+"'][data-hidden!='y']").html(b).each(d);a("*[data-token='"+c+"'][data-hidden='y']").attr("data-trans",b).each(d)}}function v(c,b){clearTimeout(o);h.push(c);m.push(b);u(c,b);o=setTimeout(function(){var c=
{ln0:t_jp.lang,sr0:n,translation_posted:"2",items:h.length},b;for(b=0;b<h.length;b+=1)c["tk"+b]=h[b],c["tr"+b]=m[b],p+=a("*[data-token='"+h[b]+"']").size();a.ajax({type:"POST",url:t_jp.post_url,data:c,success:function(){var c=p/j*100;t_jp.progress&&a("#"+k).progressbar("value",c)}});m=[];h=[]},200)}function i(c,b){v(c,a("<div>"+a.trim(b)+"</div>").text());var d=(j-a("."+f+'[data-source=""]').size())/j*100;t_jp.progress&&a("#"+l).progressbar("value",d)}function w(c,b,d){var e="",f="";a(c).each(function(a){e+=
"&q="+encodeURIComponent(c[a])});if(b)f=t_jp.olang;a.ajax({url:"http://ajax.googleapis.com/ajax/services/language/translate?v=1.0"+e+"&langpair="+f+"%7C"+t_jp.lang,dataType:"jsonp",success:d})}function q(c,b,d){w(b,d,function(e){e.responseStatus>=200&&e.responseStatus<300?e.responseData.translatedText!==void 0?i(c[0],e.responseData.translatedText):a(e.responseData).each(function(a){this.responseStatus===200&&i(c[a],this.responseData.translatedText)}):e.responseStatus>=400&&!d&&q(c,b,!0)})}function x(c,
b){var d="[";a(c).each(function(a){d+='"'+encodeURIComponent(c[a])+'",'});d=d.slice(0,-1)+"]";a.ajax({url:"http://api.microsofttranslator.com/V2/Ajax.svc/TranslateArray?appId="+t_jp.MSN_APPID+"&to="+t_jp.binglang+"&texts="+d,dataType:"jsonp",jsonp:"oncomplete",success:b})}function y(c,b){n=2;x(b,function(b){a(b).each(function(a){i(c[a],this.TranslatedText)})})}function z(c,b){a.getJSON(t_jp.post_url+"?tgp="+b+"&tgl="+t_jp.lang,function(a){a.sentences!==void 0&&a.sentences[0].trans&&i(c,a.sentences[0].trans)})}
function A(c,b){var d="";a(c).each(function(a){d+="&q="+encodeURIComponent(c[a])});a.ajax({url:"http://api.apertium.org/json/translate?"+d+"&langpair="+t_jp.olang+"%7C"+t_jp.lang+"&markUnknown=no",dataType:"jsonp",success:b})}function B(c,b){n=3;A(b,function(b){b.responseStatus>=200&&b.responseStatus<300&&(b.responseData.translatedText!==void 0?i(c[0],b.responseData.translatedText):a(b.responseData).each(function(a){this.responseStatus===200&&i(c[a],this.responseData.translatedText)}))})}function r(a,
b){t_jp.msn&&t_jp.preferred==="2"?y(a,b):t_jp.apertium&&(t_jp.olang==="en"||t_jp.olang==="es")?B(a,b):t_jp.tgp?b[0]&&z(a[0],b[0]):q(a,b,!1)}function s(){var c=[],b=0,d=[],e=[];t_jp.tgp&&(t=0);a("."+f+'[data-source=""]').each(function(){var f=a(this).attr("data-token"),g=a(this).attr("data-orig");g===void 0&&(g=a(this).html());c[g]!==1&&(c[g]=1,b+g.length>t&&(r(e,d),b=0,d=[],e=[]),b+=g.length,e.push(f),d.push(g))});r(e,d)}var t=128,j,f=t_jp.prefix,l=f+"pbar",k=l+"_s",n=1,p=0,o,h=[],m=[];t_jp.MSN_APPID=
"FACA8E2DF8DCCECE0DC311C6E57DA98EFEFA9BC6";t_jp.jQueryUI="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/";a(document).ready(function(){if(t_jp.msn)if(t_jp.binglang=t_jp.lang,t_jp.binglang==="zh")t_jp.binglang="zh-chs";else if(t_jp.binglang==="zh-tw")t_jp.binglang="zh-cht";a("#"+f+"setdeflang").click(function(){a.get(t_jp.post_url+"?tr_cookie="+Math.random());a(this).hide("slow");return!1});j=a("."+f+'[data-source=""]').size();a.ajaxSetup({cache:!0});if(j&&!t_jp.noauto&&(t_jp.google||t_jp.msn||
t_jp.apertium||t_jp.tgp))if(t_jp.progress){var c=function(){a.xLazyLoader({js:t_jp.jQueryUI+"jquery-ui.min.js",css:t_jp.jQueryUI+"themes/"+t_jp.theme+"/jquery-ui.css",success:function(){a("#"+f+"credit").css({overflow:"auto"}).append('<div style="float: left;width: 90%;height: 10px" id="'+l+'"/><div style="margin-bottom:10px;float:left;width: 90%;height: 10px" id="'+k+'"/>');a("#"+l).progressbar({value:0});a("#"+k).progressbar({value:0});a("#"+k+" > div").css({background:"#28F828",border:"#08A908 1px solid"});
s()}})};typeof a.xLazyLoader==="function"?c():a.getScript(t_jp.plugin_url+"/js/lazy.js",c)}else s();t_jp.edit&&a.getScript(t_jp.plugin_url+"/js/transposhedit.js")})})(jQuery);
