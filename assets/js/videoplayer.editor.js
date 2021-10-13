(function() {
    // Load plugin specific language pack
    tinymce.create('tinymce.plugins._videoplayer', {    // 插件ID: _videoplayer
        
        init : function(editor, url) {

            // 添加按钮
            editor.addButton('_videoplayer', {
                title : '视频播放器',
                cmd : '_videoplayer',
                image : url.replace("/js","") + '/img/videoplayer.ico'
            });

            // 修改按钮原有命令
            editor.addCommand('_videoplayer', function() {
                editor.windowManager.open({
                    file : 'admin.php?action=videoplayer',
                    width : 600,
                    height : 400,
                    inline : 1
                }, {
                    plugin_url : url // Plugin absolute URL
                });
            });
            
            // Add a node change handler, selects the button in the UI when a image is selected
            editor.onNodeChange.add(function(editor, cm, n) {
                cm.setActive('_videoplayer', n.nodeName == 'IMG');
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });

    // Register plugin
    tinymce.PluginManager.add('_videoplayer', tinymce.plugins._videoplayer);
})();




