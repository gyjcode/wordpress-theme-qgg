<?php

// 加载微信公众号验证码
include(WECHAT_OFFICIAL_DIR .'/reply/captcha.php');
// 加载微信公众号验证码
include(WECHAT_OFFICIAL_DIR .'/reply/class-wechat-reply.php');

function wechat_official_reply_process() {
    global $wechatObj;
    $captcha = wechat_official_captcha();
    $wechatObj = new Wechat_Official_Reply( $captcha );
    $wechatObj ->responseMsg();
    return;
}
add_action('pre_get_posts', 'wechat_official_reply_process', 4);

