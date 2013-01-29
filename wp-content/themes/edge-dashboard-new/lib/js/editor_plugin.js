
var currentEdit=null;
function insert_data(data){
	if(data!=""){
		tinyMCE.activeEditor.selection.setContent(data);
		jQuery("#TB_closeWindowButton").trigger("click");
	}
}
(function() {

    tinymce.create('tinymce.plugins.tinyplugin', {

        init : function(ed, url){
            ed.addButton('tinyplugin', {
                title : 'upload image',
                onclick : function() {
            		tb_show('', url + '/windowUpload.php?ver=322&TB_iframe=true');
            		currentEdit=ed;
                },
                image: url + "/wand.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'Contnet Mage plugin',
                author : 'Grzegorz Winiarski',
                authorurl : 'http://ditio.net',
                infourl : '',
                version : "1.0"
            };
        }
    });

  
    tinymce.PluginManager.add('tinyplugin', tinymce.plugins.tinyplugin);
    
})();
