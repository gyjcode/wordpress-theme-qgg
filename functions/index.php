<?php 
// 定义主题版本
define( 'THEME_VERSION' , '1.0' );

// 请求主题后台设置文件
require get_template_directory() . '/functions/fn_settings.php';
// 主题类文件
require get_template_directory() . '/functions/class_create_post_meta.php';
// WordPress 源函数修改
require get_template_directory() . '/functions/fn_admin.php';
// 主题相关函数
require get_template_directory() . '/functions/fn_theme.php';
// 文章相关函数
require get_template_directory() . '/functions/fn_post.php';
// 自定义文章模板 - 影响微信 Token 验证，待排查
require get_template_directory() . '/functions/fn_post_template.php';
// 后台自定义上传头像
require get_template_directory() . '/functions/fn_local_avatar.php';

// 加载侧栏小工具 - 影响微信 Token 验证，待排查
require get_template_directory() . '/widgets/index.php';

// 加载微信公众号验证码
require get_template_directory() . '/wechat-official/index.php';

// 加载微信公众号验证码
if (QGG_Options('smtp_mail_open')){
	require get_template_directory() . '/functions/fn_smtp_mailer.php';
}
