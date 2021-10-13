<?php
/**
 * SMTP 发信设置
 */

class _SMTP_Mailer {

    function __construct (
        $host       = 'smtp.qq.com', // 服务器地址
        $secure     = 'ssl',         // 是否验证 SSL
        $port       = 465,           // 邮件发送端口
        $username   = '',            // 邮箱地址，xxx@xxx.com
        $password   = '',            // 邮箱登录密码，授权码
        $from_name  = '',            // 发送邮件名称，张三
        $reply_to   = '',            // 回复邮件地址，xxx@xxx.com
        $test_to    = ''             // 测试邮件接收人
    ) {
        // 变量赋值
        $this->host       = $host;
        $this->secure     = $secure;
        $this->port       = $port;
        $this->username   = $username;
        $this->password   = $password;
        $this->from_name  = $from_name;
        $this->reply_to   = $reply_to;

        //使用 smtp 发邮件
        add_action('phpmailer_init',  array($this, '_php_smtp_mailer'));
        add_filter('wp_mail_from', array($this, '_smtp_mail_from') );
        add_filter('wp_mail_from_name', array($this, '_smtp_mail_from_name'));
        // 测试发信
        $this->_php_smtp_mailer_test($test_to);
    }
    
    public function _php_smtp_mailer( $phpmailer ) {
        $phpmailer -> IsSMTP();                    // 通过 SMTP 发送
        $phpmailer -> SMTPAuth   = true;           // 启用 SMTPAuth 服务
        $phpmailer -> Host       = $this->host;          // SMTP 服务器
        $phpmailer -> SMTPSecure = $this->secure;        // SMTP 安全加密
        $phpmailer -> Port       = $this->port;          // SMTP 端口号
        $phpmailer -> Username   = $this->username;      // SMTP 发信用户名
        $phpmailer -> Password   = $this->password;      // SMTP 发信密码或授权码
        
        if( $this->reply_to ){     // 自定义回复给那个邮箱(邮箱地址+收件人名)
            $phpmailer->AddReplyTo( $this->reply_to, $this->from_name);
        }
    }

    // 修改发信者名称
    public function _smtp_mail_from () {
        return $this->username;
    }

    // 修改回复邮件地址
    public function _smtp_mail_from_name () {
        return $this->from_name;
    }

    // 发信测试
    public function _php_smtp_mailer_test ($test_to) {
        // 非测试不显示消息
        if( (isset( $_GET['page']) && ($_GET['page'] != "qgg-options") ) || $test_to == "") return false;
        
        $test_result = wp_mail($test_to, '测试发信', '恭喜您，测试发信成功');
        // 发送失败返回错误
        if ( !$test_result ) {
            add_action('admin_notices',  array($this, '_smtp_mailer_test_error_msg'));
            return;
        }
        add_action('admin_notices',  array($this, '_smtp_mailer_test_success_msg'));
        return;
    }
    // 发信错误消息
    public function _smtp_mailer_test_error_msg () {
        echo '
        <div class="notice notice-error">
            <p class="smtp-mailer-test-error"><strong>错误</strong>: 测试发信失败，请检查相关配置！</p>
        </div>';
    }
    // 发信错误消息
    public function _smtp_mailer_test_success_msg () {
        echo '
        <div class="notice notice-success fade is-dismissible">
            <p class="smtp-mailer-test-success"><strong>成功</strong>: 恭喜您，测试发信成功！注意手动清空测试收信人哦！</p>
        </div>';
    }
}