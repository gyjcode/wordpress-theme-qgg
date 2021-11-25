/**
 * tinyMCE 编辑器扩展
 */

// 微信验证码
(function() {
    // Load plugin specific language pack
    tinymce.create('tinymce.plugins._wxcaptcha', {    // 插件ID: _wxcaptcha
        
        init : function(editor, url) {

            // 添加按钮
            editor.addButton('_wxcaptcha', {
                title : '微信验证码',
                cmd : '_wxcaptcha',
                image : url.replace("/js","") + '/images/captcha.svg'
            });
            console.log(url);
            // 修改按钮原有命令
            editor.addCommand('_wxcaptcha', function() {
                let shortcode = '[WXCaptcha]微信验证码隐藏内容[/WXCaptcha]';
                window.tinyMCE.activeEditor.insertContent(shortcode);
            });
            
            // Add a node change handler, selects the button in the UI when a image is selected
            editor.onNodeChange.add(function(editor, cm, n) {
                cm.setActive('_wxcaptcha', n.nodeName == 'IMG');
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });

    // Register plugin
    tinymce.PluginManager.add('_wxcaptcha', tinymce.plugins._wxcaptcha);
})();


