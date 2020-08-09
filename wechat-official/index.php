<?php 
/**
 * 网站连接微信公众号
 * $rootDir 根目录
 * $requestSign 微信公众号设置 服务器地址（URL）后的标识（?wechat）
 * $appId 微信公众号的开发者ID
 * $appSecret 微信公众号的开发者密码
 * $wechatToken 微信公众号的令牌(Token)
 * $encodingMode 微信公众号的消息加解密方式
 * $encodingAesKey 微信公众号的消息加解密密钥
 * $captchaTime 生成的验证码有效时间
 */

header('Content-type:text/html; Charset=utf-8');

$rootDir          = get_template_directory().'/wechat-official';

$appId            = QGG_options("wechat_official_appid") ? QGG_options("wechat_official_appid") : "";
$appSecret        = QGG_options("wechat_official_appsecret") ? QGG_options("wechat_official_appsecret") : "";
$requestSign      = QGG_options("wechat_official_requestsign") ? QGG_options("wechat_official_requestsign") : "wechat";
$wechatToken      = QGG_options("wechat_official_token") ? QGG_options("wechat_official_token") : "";
$encodingAesKey   = QGG_options("wechat_official_aeskey") ? QGG_options("wechat_official_aeskey") : "";
$encodingMode     = QGG_options("wechat_official_mode") ? QGG_options("wechat_official_mode") : "plaintext";    /* compatible; encryption; */

$wechatQR         = QGG_options("wechat_official_qrcode") ? QGG_options("wechat_official_qrcode") : get_template_directory_uri().'/img/qrcode.png';
$replyDefault     = QGG_options("wechat_official_reply_default") ? QGG_options("wechat_official_reply_default") : "";
$captchaTime      = QGG_options("wechat_official_captcha_time") ? QGG_options("wechat_official_captcha_time") : 10;

// 非微信公众号请求直接返回
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && strpos($_SERVER['REQUEST_URI'], $requestSign) == 0 ) {    // 非微信请求处理
	
	$replyDefault = "抱歉，系统检测到非微信公众号发送的请求，已停止继续访问！";
	if ( $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) != 0 ) {    // 本站请求处理
		if ( $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], 'wp-admin/edit.php') != 0 ) {    // 文章编辑页面错误问题处理
		    return;
	    }
	}
	
}else{
	$replyDefault = $replyDefault;
}

// 常量定义
define('WECHAT_OFFICIAL_DIR', $rootDir);
define('WECHAT_OFFICIAL_QRCODE', $wechatQR);
define('WECHAT_OFFICIAL_APPID', $appId);
define('WECHAT_OFFICIAL_APPSECRET', $appSecret);
define('WECHAT_OFFICIAL_TOKEN', $wechatToken);
define('WECHAT_OFFICIAL_ENCODINGMODE', $encodingMode);
define('WECHAT_OFFICIAL_ENCODINGAESKEY', $encodingAesKey);
define('WECHAT_OFFICIAL_CAPTCHA_TIME', $captchaTime);
define('WECHAT_OFFICIAL_REPLY_DEFAULT', $replyDefault);

//  加载微信公众号 TOKEN 验证文件
include( WECHAT_OFFICIAL_DIR.'/token.php' );
//如果是验证请求,则执行签名验证并退出
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET["echostr"])) {
	ob_clean();
	$wechatObj = new wechatTokenVerify();
	$wechatObj ->valid();
	return;
};


// 非 POST 请求直接返回空值
/*if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	echo '';
	return;
}*/

/** 以上内容为链接微信公众号基础信息 **/

// 加载回复文件
include(WECHAT_OFFICIAL_DIR.'/reply/reply.php');

