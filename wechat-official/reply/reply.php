<?php
// 如果是在被动响应微信消息，和微信用户界面中，设置 is_home 为 false，
add_action('parse_query', function($query){	
	$query->is_home 	= false;
	$query->is_search 	= false;
	$query->is_wechat 	= true;
});

// 加载微信公众号验证码
include(WX_ROOT_DIR .'/reply/func-captcha.php');
// 加载微信公众号验证码
include(WX_ROOT_DIR .'/reply/class-wx-reply.php');

function wechat_official_reply_process() {
    global $wechatObj;
    $captcha = wechat_official_captcha();
    $wechatObj = new Wechat_Official_Reply( $captcha );
    $wechatObj ->responseMsg();
    return;
}
add_action('pre_get_posts', 'wechat_official_reply_process', 4);

