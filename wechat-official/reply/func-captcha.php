<?php
/**
 * 基于云落大神(https://gitcafe.net/)微信公众平台验证码查看插件修改而来(2020-03-22)
*/
// 生成微信验证码
function wechat_official_captcha(){
    date_default_timezone_set('Asia/Shanghai');
    $min     = floor(date("i")/WX_CAPTCHA_TIME);
    $day     = date("d");
    $day     = ltrim($day,0);
    $url     = home_url();
    $captcha = sha1($min.$url.WX_TOKEN);
    $captcha = substr($captcha , $day , 6);
    return $captcha;
}


/***  WP端开始 ***/
// 自定义微信验证码可见AJAX请求
function wechat_official_captcha_view() {
    $action    = $_POST['action'];
    $post_id   = $_POST['id'];
    $captcha   = $_POST['captcha'];
    $wxcaptcha = wechat_official_captcha();
    
    if(!isset( $action ) || !isset( $post_id ) || !isset( $captcha ) ) exit('400');
    
    if($captcha == $wxcaptcha ) {
        $captcha_content = get_post_meta($post_id, 'wxcaptcha_content')[0];
        exit($captcha_content);
    }else{
        exit('400');
    }
}
add_action('wp_ajax_nopriv_wechat_official_captcha_view', 'wechat_official_captcha_view');
add_action('wp_ajax_wechat_official_captcha_view', 'wechat_official_captcha_view');


// 自定义微信验证码短代码实现
function wechat_official_captcha_shortcode($atts, $content=null) {
    
    $post_id = get_the_ID();
    add_post_meta($post_id, 'wxcaptcha_content', $content, true) or update_post_meta($post_id, 'wxcaptcha_content', $content);
    extract(shortcode_atts(array('captcha'=>null), $atts));
    
    // if ( current_user_can( 'administrator' ) ) { return $content; }
    
    $html = '
    <div id="wechat-official-captcha" class="wechat-official-captcha">
       <div class="main">
            <div class="qrcode">
                <img src="'.WX_QRCODE.'" alt="扫码关注公众号">
            </div>
            <div class="text">
                <div class="tips">
                    <p>本段内容已被隐藏，您需要扫码关注微信公众号，发送【<span>验证码</span>】获取最新验证码查看。</p>
                    <p class="prinmary">注意：验证码<b>'.WX_CAPTCHA_TIME.'</b>分钟内有效！</p>
                </div>
                <div class="ipt">
                    <input type="text" id="wechat-official-captcha-value" placeholder="输入验证码并提交">
                    <input id="submit-captcha-view" data-action="wechat_official_captcha_view" data-id="'.$post_id.'" type="button" value="提交">
                </div>
            </div>
       </div>
    </div>';

    $css = '
    <style type="text/css">
    /* 网站添加公众号涨粉功能样式代码 */
    .wechat-official-captcha{
        position: relative;
        width: 100%;
        height: 150px;
        margin: 1rem auto 0;
        border: 1px dashed #24b4f0;
        border-radius: 5px;
        padding: 0.6rem;
        overflow: hidden;
    }
    .wechat-official-captcha .main{
        display: flex;
        position: relative;
        width: auto;
        height: 100%;
    }
    .wechat-official-captcha .qrcode{
        position: relative;
        transform: translate(0,-50%);
        top: 50%;
        left: 0;
        width: auto;
        height: 100%;
    }
    .wechat-official-captcha .qrcode img{
        object-fit: cover;
        width: auto;
        height: 100%;
    }
    .wechat-official-captcha .text{
        position: relative;
        transform: translate(0,-50%);
        top: 50%;
        width: 100%;
        margin-left: 1rem;
    }
    .wechat-official-captcha .text .tips{
        text-indent: 0;
        width: 100%;
        font-size: 1rem;
        line-height: 1.5rem;
    }
    .wechat-official-captcha .tips p{
        margin: 0.6rem 0;
    }
    .wechat-official-captcha .tips p span{
        font-weight: bold;
        color: #f00;
    }
    .wechat-official-captcha .tips .prinmary{
        color: #d65900;
    }
    .wechat-official-captcha .tips .prinmary b{
        margin: 0 3px;
        font-weight: bold;
        color: #f00;
    }
    .wechat-official-captcha .ipt{
        display: flex;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 2rem;
        margin: 0.6rem 0;
    }
    .wechat-official-captcha input{
        margin: 0 5px;
        border: 1px solid #24b4f0;
        padding: 0;
    }
    .wechat-official-captcha input[type=text]{
        flex-grow: 1;
        padding: 0.3rem 1rem;
    }
    .wechat-official-captcha input[type=button]{
        flex-basis: 6rem;
        color: #fff;
        background-color: #24b4f0;
    }
    .wechat-official-captcha input[type=button]:hover{
        opacity: 0.8;
    }
    @media (max-width: 480px) {
        .wechat-official-captcha{
            height: 160px;
        }
        .wechat-official-captcha .main{
            display: block;
        }
        .wechat-official-captcha .qrcode{
            float: left;
            top: 0;
            transform: none;
            width: 100px;
            height: 100px;
        }
        .wechat-official-captcha .text{
            top: 0;
            transform: none;
            width: auto;
            height: 100px;
            margin-left: 110px;
            padding: 1px 0;
            font-size: 1.2rem;
        }
        .wechat-official-captcha .ipt{
            position: absolute;
            bottom: -35px;
            left: 15px;
            right: 5px;
            width: auto;
            height: 30px;
            margin: 0 0 0 -120px;
        }
    }
    </style>';

    $js = '
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
    <script type="text/javascript">
        // 隐藏指定元素
        function show_hide_content( atts, content ) {
            $( atts ).hide();
            $( atts ).after("<div class=\"wechat-official-captcha-success\">" + content + "</div>");
        }

        /** 点击开启密码可见 */
        $("#submit-captcha-view").click(function () {
            // AJAX 请求获取隐藏内容
            var ajax_data = {
                action  : $("#submit-captcha-view").data("action"),
                id      : $("#submit-captcha-view").data("id"),
                captcha : $(".wechat-official-captcha-value").val()
            };
            /* console.log(ajax_data); */
            $.post("'.admin_url( 'admin-ajax.php' ).'", ajax_data, function ( hideContent ) {
                
                hideContent = $.trim( hideContent );    // 删除字符串开始和末尾的空格
                if (hideContent !== "400") {
                    show_hide_content(".wechat-official-captcha", hideContent);
                    sessionStorage.setItem("wechat-official-captcha-" + ajax_data["id"], hideContent);  /**隐藏内容直接存入浏览器缓存,下次直接读取,ps.有个问题,内容更新会略坑,不管了 */
                } else {
                    alert("您的验证码错误，请重新申请！");
                }
            });
        });
        
        
        /**  已经密码可见的自动从浏览器读取内容并显示，这里加个延时处理 */
        (function () {
            if ($("#submit-captcha-view").length > 0) { /**如果网站有密码可见,就执行 */
                setTimeout(function () {
                    var id = "wechat-official-captcha-" + $("#submit-captcha-view").data("id"),
                        length = sessionStorage.length;
                    for (var i = 0; i < length; i++) {
                        var key = sessionStorage.key(i),
                            value = sessionStorage.getItem(key);
                        if (key.indexOf(id) >= 0) { /*发现目标 */
                            show_hide_content(".wechat-official-captcha", value);
                            break;
                        }
                    }

                }, 900);
            }
        }());

        /** 密码可见 end */
        
    </script>';
    
    return $html.$css.$js;
}
add_shortcode('WXCaptcha', 'wechat_official_captcha_shortcode');

// 添加 QTags 按钮
function _add_qtags_button_wxcaptcha() {
    ?>
    <script type="text/javascript">
        if ( typeof QTags != 'undefined' ) {
            QTags.addButton( 'WXCaptcha', '微信验证码', '[WXCaptcha]微信验证码隐藏内容', '[/WXCaptcha]\n' );
        }
    </script>
    <?php
}
add_action('admin_print_footer_scripts', '_add_qtags_button_wxcaptcha');

// 注册 tinyMCE 按钮
function _register_tinymce_buttons_wxcaptcha( $buttons ){
    array_push($buttons, "|", "_wxcaptcha");
    return $buttons;
}
// 添加 tinyMCE 按钮
function _add_tinymce_buttons_wxcaptcha( $plugin_array ){
    $plugin_array['_wxcaptcha'] = WX_ROOT_URL.'/assets/js/tinymce.editor.js';
    return $plugin_array;
}
add_filter('mce_buttons', '_register_tinymce_buttons_wxcaptcha');
add_filter('mce_external_plugins', '_add_tinymce_buttons_wxcaptcha');

/***  WP端结束 ***/