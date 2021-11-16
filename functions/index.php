<?php 
// 定义主题版本
define( 'THEME_VER' , '2.1' );
define( 'THEME_URI' , get_template_directory_uri() );
define( 'THEME_DIR' , get_template_directory() );

/**
 * 后台设置
 */
require THEME_DIR.'/functions/func_settings.php';

/**
 * 封装好的功能
 */
// 加载微信公众号验证码
if (QGG_Options('wechat_official_on')){
    require THEME_DIR.'/wechat-official/index.php';
}
// 创建 post meta
require THEME_DIR.'/functions/utils/class_create_post_meta.php';
// 文章模板
require THEME_DIR.'/functions/utils/func_post_template.php';
// 上传头像
require THEME_DIR.'/functions/utils/func_local_avatar.php';
// 分集视频
if ( !function_exists('_VideoPlayer') ) {
    require THEME_DIR.'/functions/utils/class_video_player.php';
    $height   = QGG_Options('video_player_height') ?: 500;
    $height_m = QGG_Options('video_player_height_m') ?: 300;
    $poster   = QGG_Options('video_player_poster') ?: get_template_directory_uri().'/assets/img/video-poster.png';
    $sources  = array();
    for ($i=1; $i <= 3; $i++) {
        $type = QGG_Options('video_player_jx_type-'.$i) ?: null;
        $id   = 'jxID'.$i;    // 视频解析的唯一ID 值，系统自动生成
        $name = QGG_Options('video_player_jx_name-'.$i) ?: null;
        $api  = QGG_Options('video_player_jx_api-'.$i) ?: null;

        if ( isset($name) ) $sources[] = array( "id" => $id, "name" => $name, "type" => $type,  "api" => html_entity_decode($api));
    };
    // 实例化
    $videoPlayer = new _VideoPlayer($sources, $poster, $height, $height_m);
};

// SMTP 发邮件
if ( QGG_Options('smtp_mailer_on') ) {
    if ( !function_exists('_SMTP_Mailer') ) {
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

/**
 * 主题开发
 */
// 公共工具库
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
// 侧栏小工具
require THEME_DIR.'/widgets/index.php';

