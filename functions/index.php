<?php 
// 定义主题版本
define( 'THEME_VER' , '2.0' );
define( 'THEME_URI' , get_template_directory_uri() );
define( 'THEME_DIR' , get_template_directory() );

// 后台设置
require THEME_DIR.'/functions/func_settings.php';

// 工具库
// 创建 post meta
require THEME_DIR.'/functions/utils/class_create_post_meta.php';
// 文章模板 - 影响微信 Token 验证，待排查
require THEME_DIR.'/functions/utils/func_post_template.php';
// 上传头像
require THEME_DIR.'/functions/utils/func_local_avatar.php';
// SMTP 发邮件
if (QGG_Options('smtp_mailer_on')){
    if ( !function_exists('SMTP_Mailer')) {
        require THEME_DIR.'/functions/utils/class_smtp_mailer.php';
        new _SMTP_Mailer(
            $host       = QGG_Options('smtp_mailer_host') ?: '',         // SMTP服务器地址
            $secure     = QGG_Options('smtp_mailer_secure') ?: '',       // 是否验证 SSL
            $port       = QGG_Options('smtp_mailer_port') ?: '',         // SMTP邮件发送端口
            $username   = QGG_Options('smtp_mailer_user') ?: '',         // 你的邮箱地址
            $password   = QGG_Options('smtp_mailer_pass') ?: '',         // 你的邮箱登录密码
            $from_name  = QGG_Options('smtp_mailer_from_name') ?: '',    // 发送邮件名称
            $reply_to   = QGG_Options('smtp_mailer_reply_to') ?: '',     // 回复邮件地址
            $test_to    = QGG_Options('smtp_mailer_test_to') ?: ''      // 测试邮件接收人
        );
    };
}

// 自定义工具函数
require THEME_DIR.'/functions/func_tool.php';
// 后台修改
require THEME_DIR.'/functions/func_admin.php';
// 主题公共
require THEME_DIR.'/functions/func_theme.php';
// 文章页面
require THEME_DIR.'/functions/func_post.php';
// 分类页面
require THEME_DIR.'/functions/func_category.php';
// 评论相关
require THEME_DIR.'/functions/func_comment.php';

// 加载侧栏小工具 - 影响微信 Token 验证，待排查
require THEME_DIR.'/widgets/index.php';

// 加载微信公众号验证码
require THEME_DIR.'/wechat-official/index.php';


