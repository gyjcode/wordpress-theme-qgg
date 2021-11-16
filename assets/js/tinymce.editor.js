/**
 * tinyMCE 编辑器扩展
 */

// 分集视频按钮
(function() {
    // Load plugin specific language pack
    tinymce.create('tinymce.plugins._videoplayer', {    // 插件ID: _videoplayer
        
        init : function(editor, url) {

            // 添加按钮
            editor.addButton('_videoplayer', {
                title : '分集视频播放器',
                cmd : '_videoplayer',
                image : url.replace("/js","") + '/img/videoplayer.svg'
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

// 展开收缩按钮
(function() {
    // Load plugin specific language pack
    tinymce.create('tinymce.plugins._collapse', {    // 插件ID: _collapse
        
        init : function(editor, url) {

            // 添加按钮
            editor.addButton('_collapse', {
                title : '展开/收缩内容',
                cmd : '_collapse',
                image : url.replace("/js","") + '/img/collapse.svg'
            });

            // 修改按钮原有命令
            editor.addCommand('_collapse', function() {
                let shortcode = '[Collapse title="说明文字"]显示隐藏内容[/Collapse]';
                window.tinyMCE.activeEditor.insertContent(shortcode);
            });
            
            // Add a node change handler, selects the button in the UI when a image is selected
            editor.onNodeChange.add(function(editor, cm, n) {
                cm.setActive('_collapse', n.nodeName == 'IMG');
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });

    // Register plugin
    tinymce.PluginManager.add('_collapse', tinymce.plugins._collapse);
})();


