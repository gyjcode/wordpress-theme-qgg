<?php 
/**
 * WordPress 链接微信公众号
 */

header('Content-type:text/html; Charset=utf-8');
// 根目录
$rootDir        = get_template_directory().'/wechat-official';
$rootUrl        = get_template_directory_uri().'/wechat-official';
// 配置 # 开发
$appId          = QGG_options("wechat_official_appid") ?: '';
$appSecret      = QGG_options("wechat_official_appsecret") ?: '';
$token          = QGG_options("wechat_official_token") ?: '';
$encodingAesKey = QGG_options("wechat_official_aeskey") ?: '';
$encodingMode   = QGG_options("wechat_official_mode") ?: 'plaintext';    /* compatible; encryption; */
// 配置 # 功能
$qrcode         = QGG_options("wechat_official_qrcode") ?: get_template_directory_uri().'/assets/img/qrcode.png';
$replyDefault   = QGG_options("wechat_official_reply_default") ?: '';
$captchaTime    = QGG_options("wechat_official_captcha_time") ?: 10;
?>

<?php 
/**==================== 正文开始 ====================*/
// 调试模式
define('DEBUG_MODE', false);    // 调试模式

// 常量 # 基础
define('WX_ROOT_DIR', $rootDir);        // 根目录
define('WX_ROOT_URL', $rootUrl);        // 根目录
define('WX_LOG_ERR', WX_ROOT_DIR.'/log_error.txt');    // 错误日志文件
// 常量 # 开发
define('WX_QRCODE', $qrcode);    // 公众号二维码
define('WX_APPID', $appId);    // 开发者 ID
define('WX_APPSECRET', $appSecret);    // 开发者密码
define('WX_TOKEN', $token);    // 令牌
define('WX_ENCODING_MODE', $encodingMode);    // 消息加解密方式
define('WX_ENCODING_AESKEY', $encodingAesKey);    // 消息加解密密钥
// 常量 # 功能
define('WX_CAPTCHA_TIME', $captchaTime);
define('WX_REPLY_DEFAULT', $replyDefault);

// 工具函数
include( WX_ROOT_DIR.'/includes/tools.php' );

/** 
 * TOKEN 验证
 * 
 * "GET /?wechat_official&signature=xxx&echostr=xxx&timestamp=1633909620&nonce=1539440063 HTTP/1.0" 200 19 "-" "Mozilla/4.0"
 * 开发者提交信息后，微信服务器将发送GET请求到填写的服务器地址URL上
 * 1）将token、timestamp、nonce三个参数进行字典序排序
 * 2）将三个参数字符串拼接成一个字符串进行sha1加密
 * 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
 * https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Access_Overview.html
 */
include( WX_ROOT_DIR.'/includes/class-wx-token.php' );
$isWechatToken = isset($_GET['signature']) && isset($_GET['timestamp']) && isset($_GET['nonce']) && isset($_GET['echostr']);
if ( $_SERVER['REQUEST_METHOD'] == 'GET' && $isWechatToken ) {
    ob_clean();
    // 实例化令牌验证
    $wechatObj = new wechatTokenVerify();
    // 返回验证消息
    $wechatObj ->valid();
    return;
};

/**
 * 非微信请求自动返回
 * 
 * "POST /?wechat_official&signature=xxx&timestamp=1633909737&nonce=296574914&openid=xxx&encrypt_type=aes&msg_signature=xxx HTTP/1.1" 200 91488 "-" "Mozilla/4.0"
 * 当普通微信用户向公众账号发消息时，微信服务器将POST消息的XML数据包到开发者填写的URL上
 * 微信服务器在五秒内收不到响应会断掉连接，并且重新发起请求，总共重试三次
 * https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Receiving_standard_messages.html
 */
$isWechatRequest = isset($_GET['signature']) && isset($_GET['timestamp']) && isset($_GET['nonce']) && isset($_GET['openid']);
if ( $isWechatRequest ) {
    // 微信请求时修改查询参数
    add_action('parse_query', function($query){	
        $query->is_home 	= false;
        $query->is_search 	= false;
        $query->is_wechat 	= true;
    });
}


// 回复功能
include(WX_ROOT_DIR.'/reply/reply.php');

