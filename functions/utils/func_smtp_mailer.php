<?php
/**
 * SMTP 发信设置
 */

function SMTP_Mailer (
    $host = 'smtp.qq.com',      // 服务器地址
    $secure = 'ssl',            // 是否验证 SSL
    $port = 465,                // 邮件发送端口
    $user = '',                 // 邮箱地址，xxx@xxx.com
    $password = '',             // 邮箱登录密码
    $mailFrom = '',             // 发送邮件名称，张三
    $replyTo = ''               // 回复邮件地址，xxx@xxx.com
) {

    //使用 smtp 发邮件
    add_action('phpmailer_init', 'php_mail_smtp');
    function php_mail_smtp( $phpmailer ) {
        $phpmailer -> IsSMTP();                             // 通过 SMTP 发送
        $phpmailer -> SMTPAuth = true;                      // 启用 SMTPAuth 服务
        $phpmailer -> Host = $host;                // SMTP 服务器
        $phpmailer -> SMTPSecure = $secure;        // SMTP 安全加密
        $phpmailer -> Port = $port;                // SMTP 端口号
        $phpmailer -> Username = $user;            // SMTP 发信用户名
        $phpmailer -> Password = $password;            // SMTP 发信密码或授权码
        
        if( $replyTo ){     // 自定义回复给那个邮箱(邮箱地址+收件人名)
            $phpmailer->AddReplyTo( $replyTo, $mailFrom);
        }
    }

    add_filter( 'wp_mail_from', '_wp_mail_from' );
    function _wp_mail_from() {
        return  $user;
    }

    add_filter('wp_mail_from_name', '_wp_mail_from_name');
    function _wp_mail_from_name($smtpReplyToMail){
        return $mailFrom ?: $smtpReplyToMail;
    }

    return ;
}

