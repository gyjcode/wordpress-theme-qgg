<?php
/**
 * SMTP 发信设置
 */

$smtpHost          = QGG_Options('smtp_mail_host') ? QGG_Options('smtp_mail_host') : "smtp.qq.com";            // SMTP服务器地址
$smtpSecure        = QGG_Options('smtp_mail_secure') ? QGG_Options('smtp_mail_secure') : "ssl";     // 是否验证 SSL
$smtpPort          = QGG_Options('smtp_mail_port') ? QGG_Options('smtp_mail_port') : 465;            // SMTP邮件发送端口
$smtpUser          = QGG_Options('smtp_mail_user') ? QGG_Options('smtp_mail_user') : "";            // 你的邮箱地址
$smtpPass          = QGG_Options('smtp_mail_pass') ? QGG_Options('smtp_mail_pass') : "";            // 你的邮箱登录密码
$smtpMailFromName  = QGG_Options('smtp_mail_from_name') ? QGG_Options('smtp_mail_from_name') : "";  // 发送邮件名称
$smtpReplyToMail   = QGG_Options('smtp_mail_reply_to') ? QGG_Options('smtp_mail_reply_to') : "";    // 回复邮件地址

// 常量定义
define('SMTP_MAIL_HOST', $smtpHost);
define('SMTP_MAIL_SECURE', $smtpSecure);
define('SMTP_MAIL_PORT', $smtpPort);
define('SMTP_MAIL_USER', $smtpUser);
define('SMTP_MAIL_PASS', $smtpPass);
define('SMTP_MAIL_FROM_NAME', $smtpMailFromName);
define('SMTP_MAIL_REPLY_TO', $smtpReplyToMail);

//使用 smtp 发邮件
add_action('phpmailer_init', 'php_mail_smtp');
function php_mail_smtp( $phpmailer ) {
	$phpmailer->IsSMTP();                             // 通过 SMTP 发送
	$phpmailer->SMTPAuth = true;                      // 启用 SMTPAuth 服务
	$phpmailer->Host = SMTP_MAIL_HOST;                // SMTP 服务器
	$phpmailer->SMTPSecure = SMTP_MAIL_SECURE;        // SMTP 安全加密
	$phpmailer->Port = SMTP_MAIL_PORT;                // SMTP 端口号
	$phpmailer->Username = SMTP_MAIL_USER;            // SMTP 发信用户名
	$phpmailer->Password = SMTP_MAIL_PASS;            // SMTP 发信密码或授权码
	
	if( SMTP_MAIL_REPLY_TO ){     // 自定义回复给那个邮箱(邮箱地址+收件人名)
		$phpmailer->AddReplyTo( SMTP_MAIL_REPLY_TO, SMTP_MAIL_FROM_NAME);
	}
}

// 过滤 WordPress 默认设置
add_filter( 'wp_mail_from', '_wp_mail_from' );
function _wp_mail_from() {
	return  SMTP_MAIL_USER ;
}
add_filter('wp_mail_from_name', '_wp_mail_from_name');
function _wp_mail_from_name($smtpReplyToMail){
	return SMTP_MAIL_FROM_NAME ?: $smtpReplyToMail;
}