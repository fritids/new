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
var timer,items=0,translations=[],tokens=[],langs=[],sources=[],BATCH_SIZE=128,pair_count=0,curr_pair=0;t_jp.MSN_APPID="FACA8E2DF8DCCECE0DC311C6E57DA98EFEFA9BC6";function make_progress(b,a){curr_pair+=1;jQuery("#progress_bar").progressbar("value",curr_pair/pair_count*100);jQuery("#p").text("("+a+") "+b);curr_pair===pair_count&&jQuery("#tr_loading").data("done",!0)}
function ajax_translate_me(b,a,d,e){a=jQuery("<div>"+jQuery.trim(a)+"</div>").text();make_progress(a,d);clearTimeout(timer);items+=1;tokens.push(b);translations.push(a);langs.push(d);sources.push(e);timer=setTimeout(function(){var a={translation_posted:"2",items:items},c;for(c=0;c<items;c+=1)tokens[c]!==tokens[c-1]&&(a["tk"+c]=tokens[c]),langs[c]!==langs[c-1]&&(a["ln"+c]=langs[c]),translations[c]!==translations[c-1]&&(a["tr"+c]=translations[c]),sources[c]!==sources[c-1]&&(a["sr"+c]=sources[c]);jQuery.ajax({type:"POST",
url:t_jp.post_url,data:a,success:function(){},error:function(){}});items=0;translations=[];tokens=[];langs=[];sources=[]},200)}function do_mass_ms_translate(b,a){var d="[";jQuery(b).each(function(a){d+='"'+encodeURIComponent(b[a])+'",'});d=d.slice(0,-1)+"]";jQuery.ajax({url:"http://api.microsofttranslator.com/V2/Ajax.svc/TranslateArray?appId="+t_jp.MSN_APPID+"&to="+t_jp.binglang+"&texts="+d,dataType:"jsonp",jsonp:"oncomplete",success:a})}
function do_mass_ms_invoker(b,a,d){t_jp.binglang=d;if(t_jp.binglang==="zh")t_jp.binglang="zh-chs";else if(t_jp.binglang==="zh-tw")t_jp.binglang="zh-cht";do_mass_ms_translate(a,function(a){jQuery(a).each(function(a){ajax_translate_me(b[a],this.TranslatedText,d,2)})})}function do_mass_google_translate_l(b,a,d){var e="",h;for(h in a)e+="&langpair=%7C"+a[h];jQuery.ajax({url:"http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q="+encodeURIComponent(b)+e,dataType:"jsonp",success:d})}
function do_mass_google_invoker_l(b,a,d){do_mass_google_translate_l(a,d,function(a){a.responseStatus===200&&(a.responseData.translatedText!==void 0?ajax_translate_me(b,a.responseData.translatedText,d[0],1):jQuery(a.responseData).each(function(a){this.responseStatus===200&&ajax_translate_me(b,this.responseData.translatedText,d[a],1)}))})}
function translate_post(b){var a="",d=[],e=[],h,c,f,g,l=0,i=[],j=[],k;jQuery("#tr_loading").data("done",!1);jQuery.getJSON(t_jp.post_url+"?tr_phrases_post=y&post="+b+"&random="+Math.random(),function(b){jQuery("#tr_translate_title").html("Translating post: "+b.posttitle);if(b.length===void 0)jQuery("#tr_loading").html("Nothing left to translate"),jQuery("#tr_loading").data("done",!0);else{curr_pair=pair_count=0;for(f in b.p)pair_count+=b.p[f].l.length;jQuery("#tr_loading").html('<br/>Translation: <span id="p"></span><div id="progress_bar"/>');
jQuery("#progress_bar").progressbar({value:0});if(t_jp.preferred==="2")for(h in t_jp.m_langs){a=t_jp.m_langs[h];e=[];d=[];for(f in b.p)g=b.p[f],g.l.indexOf(a)!==-1&&(e.push(unescape(f)),d.push(g.t),g.l.splice(g.l.indexOf(a),1),g.l.length===0&&(b.length-=1,delete b.p[f]));if(e.length){for(c in e)k=e[c],l+k.length>BATCH_SIZE&&(do_mass_ms_invoker(j,i,a),l=0,i=[],j=[]),l+=k.length,j.push(d[c]),i.push(k);do_mass_ms_invoker(j,i,a)}}for(f in b.p)g=b.p[f],do_mass_google_invoker_l(g.t,unescape(f),g.l)}})}
jQuery(document).ready(function(){t_jp.post&&translate_post(t_jp.post)});
