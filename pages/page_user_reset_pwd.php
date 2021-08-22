<?php 
/**
 * Template name: 找回密码
 * Description:   找回密码页面，用于帮助用户在密码丢失后重置密码，代码参考 wp-login.php 文件
 */
?>

<?php

$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'lostpassword';

if ( isset($_REQUEST['key']) ){
    $action = 'resetpassword';
}

if ( !in_array( $action, array( 'lostpassword', 'resetpassword', 'success' ), true ) ){
    $action = 'lostpassword';
}

$lost_active = '';
$reset_active = '';
$success_active = '';

switch ($action) {
    
    // 丢失密码
    case 'lostpassword' :
        
        $errors = new WP_Error();
        
        if ( $http_post ) {
            $errors = retrieve_password();
        }
        if ( isset( $_REQUEST['error'] ) ) {
            if ( 'invalidkey' == $_REQUEST['error'] )
                $errors->add( 'invalidkey', __( '<strong>错误</strong>: 该密钥似乎无效。' ) );
            elseif ( 'expiredkey' == $_REQUEST['error'] )
                $errors->add( 'expiredkey', __( '<strong>错误</strong>: 该密钥已过期。 请再试一次。' ) );
        }
        
        $lost_active = ' class="active"';
        
        break;
        
    // 重置密码
    case 'resetpassword' :
        
        $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
        
        if ( is_wp_error($user) ) {
            if ( $user->get_error_code() === 'expired_key' ){
                wp_redirect( _get_page_user_reset_pwd_link() . '?&action=lostpassword&error=expiredkey' );
            }
            else{
                wp_redirect( _get_page_user_reset_pwd_link() . '?&action=lostpassword&error=invalidkey' );
            }
            exit;
        }
        
        $errors = new WP_Error();
        
        if ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] ){
            $errors->add( 'password_reset_mismatch', __( '<strong>错误</strong>: 密码不匹配，请重新输入！' ) );
        }
        if( strlen($_POST['pass1'])<6 ) {  
            $errors->add( 'password_reset_mismatch2', '<strong>注意</strong>: 密码至少设置6位！' );
        }

        /**
         * Fires before the password reset procedure is validated.
         * @since 3.5.0
         * @param object           $errors WP Error object.
         * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
         */
        do_action( 'validate_password_reset', $errors, $user );
        
        if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
            reset_password($user, $_POST['pass1']);
            wp_redirect( _get_page_user_reset_pwd_link() . '?&action=success' );
            exit;
        }
        
        $reset_active = ' class="active"';
        
        break;
        
    // 密码重置成功
    case 'success' :
        
        $success_active = ' class="active"';
        
        break;
}
?>

<?php
// 错误信息
function error_msg($wp_error='') {
    if ( empty($wp_error) )
        $wp_error = new WP_Error();
    
    if ( $wp_error->get_error_code() ) {
        $errors = '';
        $messages = '';
        foreach ( $wp_error->get_error_codes() as $code ) {
            $severity = $wp_error->get_error_data($code);
            foreach ( $wp_error->get_error_messages($code) as $error ) {
                if ( 'message' == $severity ){
                    $messages .= '    ' . $error . "<br />\n";
                }else{
                    $errors .= '' . $error . "<br />\n";
                }
            }
        }
        if ( !empty( $errors ) ) {
            /**
             * Filter the error messages displayed above the login form.
             * @since 2.1.0
             * @param string $errors Login error message.
             */
            echo '<p class="err-tip">' . apply_filters( 'login_errors', $errors ) . "</p>\n";
        }
        if ( !empty( $messages ) ) {
            /**
             * Filter instructional messages displayed above the login form.
             * @since 2.5.0
             * @param string $messages Login messages.
             */
            echo '<p class="err-tip">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
        }
    }
}
// 找回密码
function retrieve_password() {
    global $wpdb, $wp_hasher;
    $errors = new WP_Error();

    if ( empty( $_POST['user_login'] ) ) {
        $errors->add('empty_username', __('<strong>错误</strong>: 请输入一个用户名或邮箱地址！'));
    } else if ( strpos( $_POST['user_login'], '@' ) ) {
        $user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
        if ( empty( $user_data ) )
            $errors->add('invalid_email', __('<strong>错误</strong>: 该用户名或邮箱地址未注册！'));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }

    /**
     * Fires before errors are returned from a password reset request.
     * @since 2.1.0
     * @since 4.4.0 Added the `$errors` parameter.
     * @param WP_Error $errors A WP_Error object containing any errors generated
     *                         by using invalid credentials.
     */
    do_action( 'lostpassword_post' );

    if ( $errors->get_error_code() ){
        return $errors;
    }
    if ( !$user_data ) {
        $errors->add('invalidcombo', __('<strong>错误</strong>: 无效的用户名或邮箱地址！'));
        return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key        = get_password_reset_key( $user_data );
    
    if ( is_wp_error( $key ) ) {
        return $key;
    }
    
    if ( is_multisite() ) {
        $site_name = get_network()->site_name;
    } else {
        /*
         * The blogname option is escaped with esc_html on the way into the database
         * in sanitize_option we want to reverse this for the plain text arena of emails.
         */
        $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    }

    /**
     * Fires before a new password is retrieved.
     * @since 1.5.0
     * @deprecated 1.5.1 Misspelled. Use 'retrieve_password' hook instead.
     * @param string $user_login The user login name.
     */
    do_action( 'retreive_password', $user_login );
    /**
     * Fires before a new password is retrieved.
     * @since 1.5.1
     * @param string $user_login The user login name.
     */
    do_action( 'retrieve_password', $user_login );

    /**
     * Filter whether to allow a password to be reset.
     * @since 2.7.0
     * @param bool true           Whether to allow the password to be reset. Default true.
     * @param int  $user_data->ID The ID of the user attempting to reset a password.
     */
    $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );
    if ( ! $allow ){
        return new WP_Error('no_password_reset', __('该用户无权修改密码！'));
    }elseif ( is_wp_error($allow) ){
        return $allow;
    }
    
    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );
    
    /**
     * Fires when a password reset key is generated.
     * @since 2.5.0
     * @param string $user_login The username for the user.
     * @param string $key        The generated password reset key.
     */
    do_action( 'retrieve_password_key', $user_login, $key );
    
    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    
    global $wp_version;

    if( version_compare($wp_version,'4.3.0','>=') ){
        $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    }else{
        $hashed = $wp_hasher->HashPassword( $key );
    }
    
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
    
    $message  = __('有人请求修改您账户如下的密码信息：') . "\r\n\r\n";
    $message .= network_home_url( '/' ) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= __('如果这不是您本人操作，请忽略该邮件，一切将会像往常一样。') . "\r\n\r\n";
    $message .= __('如果您希望修改密码，请访问如下链接:') . "\r\n\r\n";
    
    $message .= network_site_url(_get_page_user_reset_pwd_link() . "?&action=resetpassword&key=$key&login=" . rawurlencode($user_login), 'login');
    
    $message = str_replace( site_url('/') . site_url('/'), site_url('/'), $message );
    
    $title = sprintf( __('[%s] 密码重置'), $site_name );
    /**
     * Filters the subject of the password reset email.
     * @since 2.8.0
     * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
     * @param string  $title      Default email title.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     */
    $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );
    
    /**
     * Filters the message body of the password reset mail.
     * If the filtered message is empty, the password reset email will not be sent.
     * @since 2.8.0
     * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     */
    $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

    if ( $message && !wp_mail($user_email, $title, $message) ){
        exit( __('邮件无法发送。') . "<br />\n" . __('可能是由于该网站的主机禁用了 mail() 函数。') );
    }
    
    return true;
}
?>


<?php
get_header();
?>
<section class="container">    
<div class="content">
    <div class="main page-resetpassword">
        <h1 class="hide"><?php the_title(); ?></h1>
        <ul class="steps">
            <li<?php echo $lost_active; ?>><b>账户</b></b>邮箱校验</li>
            <li<?php echo $reset_active; ?>><b>设置</b>新密码</li>
            <li<?php echo $success_active; ?>>成功修改<b>密码</b></li>
        </ul>
        
        <?php 
        if( $lost_active ){ 
            if( $errors !== true ){
        ?>
        <form action="<?php echo esc_url( _get_page_user_reset_pwd_link() . '?&action=lostpassword' ); ?>" method="post">
            <?php error_msg($errors); ?>
            <h3>填写用户名或邮箱：</h3>
            <p><input type="text" name="user_login" class="form-control" placeholder="用户名或邮箱" autofocus></p>
            <p><input type="submit" value="获取密码重置邮件" class="btn btn-default"></p>
        </form>
        <?php 
            }else{ 
                echo '<form>';
                echo '<p class="success-tip"> 已成功向注册邮箱发送邮件！</p>';
                echo '<p>去邮箱查收邮件并点击重置密码链接</p>';
                echo '</form>';
            }
        } ?>

        <?php if( $reset_active ){ ?>
        <form action="" method="post">
            <?php error_msg($errors); ?>
            <h3>设置新密码：</h3>
            <p><input type="password" name="pass1" class="form-control" placeholder="输入新密码" autofocus></p>
            <h3>重复新密码：</h3>
            <p><input type="password" name="pass2" class="form-control" placeholder="重复新密码"></p>
            <p><input type="submit" value="确认提交" class="btn btn-default"></p>
        </form>
        <?php } ?>

        <?php if( $success_active ){ ?>
        <form action="" method="post">
            <p class="success-tip"> 恭喜，密码重置成功！</p>
            <p class="text-center"><a class="btn btn-default" href="<?php echo get_bloginfo('url') ?>">返回回首页</a></p>
        </form>
        <?php } ?>

    </div>
</div>
</section>

<?php
get_footer();