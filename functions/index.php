<?php 
// 定义主题版本
define( 'THEME_VERSION' , '1.0' );
define( 'THEME_URI' , get_template_directory_uri() );
define( 'THEME_DIR' , get_template_directory() );

// 后台设置
require THEME_DIR.'/functions/func_settings.php';

// 工具函数
// 创建 post meta
require THEME_DIR.'/functions/utils/class_create_post_meta.php';
// 文章模板 - 影响微信 Token 验证，待排查
require THEME_DIR.'/functions/utils/func_post_template.php';
// 上传头像
require THEME_DIR.'/functions/utils/func_local_avatar.php';
// SMTP 发邮件
if (QGG_Options('smtp_mail_open')){
	require THEME_DIR.'/functions/utils/func_smtp_mailer.php';
	SMTP_Mailer(
		$smtpHost          = QGG_Options('smtp_mail_host'),            // SMTP服务器地址
		$smtpSecure        = QGG_Options('smtp_mail_secure'),          // 是否验证 SSL
		$smtpPort          = QGG_Options('smtp_mail_port'),            // SMTP邮件发送端口
		$smtpUser          = QGG_Options('smtp_mail_user'),            // 你的邮箱地址
		$smtpPass          = QGG_Options('smtp_mail_pass'),            // 你的邮箱登录密码
		$smtpMailFromName  = QGG_Options('smtp_mail_from_name'),       // 发送邮件名称
		$smtpReplyToMail   = QGG_Options('smtp_mail_reply_to')        // 回复邮件地址
	);
}

// 后台修改
require THEME_DIR.'/functions/func_admin.php';
// 主题函数
require THEME_DIR.'/functions/func_theme.php';
// 文章函数
require THEME_DIR.'/functions/func_post.php';

// 加载侧栏小工具 - 影响微信 Token 验证，待排查
require THEME_DIR.'/widgets/index.php';

// 加载微信公众号验证码
require THEME_DIR.'/wechat-official/index.php';


