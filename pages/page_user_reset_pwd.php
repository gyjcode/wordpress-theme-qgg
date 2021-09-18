<?php 
/**
 * Template name: 找回密码
 * Description:   找回密码页面，用于帮助用户在密码丢失后重置密码，代码参考 wp-login.php 文件
 */
?>

<?php
/**
 * 获取请求信息
 */
// $_SERVER  — 服务器和执行环境信息
$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
// $_REQUEST — HTTP Request 变量
$action = $_REQUEST['action'] ?: 'retrieve_password';
$action = !empty($_REQUEST['key']) ? 'reset_password' : $action;

if ( !in_array( $action, array('retrieve_password', 'reset_password', 'success'), true ) ){
    $action = 'retrieve_password';
}

$retrieve_active = '';
$reset_active    = '';
$success_active  = '';
// 请求路由
switch ($action) {
    // 第一步：找回密码
    case 'retrieve_password' :
        // 使用 WP_Error 类处理错误
        $errors = new WP_Error();
        if ( $http_post ) {
            $errors = _retrieve_password();
        }
        if ( isset( $_REQUEST['error'] ) ) {
            if ( 'invalidkey' == $_REQUEST['error'] )
                $errors->add( 'invalidkey', __( '<strong>错误</strong>: 密码重置链接无效。' ) );
            elseif ( 'expiredkey' == $_REQUEST['error'] )
                $errors->add( 'expiredkey', __( '<strong>错误</strong>: 密码重置链接已过期，请重试。' ) );
        }
        $retrieve_active = 'active';
        break;
        
    // 第二步：重置密码
    case 'reset_password' :
        // 密码重置校验
        $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
        // 错误处理
        if ( is_wp_error($user) ) {
            if ( $user->get_error_code() === 'expired_key' ){  // 链接超期
                wp_redirect( _get_page_user_reset_pwd_link().'?&action=retrieve_password&error=expiredkey' );
            } else {    //链接无效
                wp_redirect( _get_page_user_reset_pwd_link().'?&action=retrieve_password&error=invalidkey' );
            }
            exit;
        }
        // 使用 WP_Error 类处理错误信息
        $errors = new WP_Error();
        if ( isset($_POST['password1']) && $_POST['password1'] != $_POST['password2'] ){
            $errors->add( 'reset_password_mismatch', __( '<strong>错误</strong>: 密码不匹配，请重新输入！' ) );
        }
        if( strlen($_POST['password1']) < 6 ) {  
            $errors->add( 'reset_password_mistake', __( '<strong>注意</strong>: 密码至少设置6位！' ) );
        }

        /**
         * Fires before the password reset procedure is validated.
         * @since 3.5.0
         * @param object           $errors WP Error object.
         * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
         */
        do_action( 'validate_password_reset', $errors, $user );
        
        if ( ( ! $errors->get_error_code() ) && isset( $_POST['password1'] ) && !empty( $_POST['password1'] ) ) {
            reset_password($user, $_POST['password1']);
            wp_redirect( _get_page_user_reset_pwd_link() . '?&action=success' );
            exit;
        }
        
        $reset_active = 'active';
        
        break;
        
    // 第三步：设置成功
    case 'success' :
        
        $success_active = 'active';
        
        break;
}
?>

<?php
/**
 * 获取重置密码密钥
 * 基于 wp-includes\user.php 修改所得 
 */
function _get_password_reset_key ($user) {
    global $wp_hasher;

	if ( ! ( $user instanceof WP_User ) ) {
		return new WP_Error( 'invalidcombo', __( '<strong>错误</strong>：用户名或邮箱地址无效' ) );
	}

    /**
	 * Fires before a new password is retrieved.
	 * @since 1.5.1
	 * @param string $user_login The user login name.
	 */
    // 修改执行为 _retrieve_password ( $user_login = null ) 
	do_action( '_retrieve_password', $user->user_login );

    $allow = true;
	if ( is_multisite() && is_user_spammy( $user ) ) {
		$allow = false;
	}

    /**
	 * Filters whether to allow a password to be reset.
	 * @since 2.7.0
	 * @param bool $allow Whether to allow the password to be reset. Default true.
	 * @param int  $ID    The ID of the user attempting to reset a password.
	 */
	$allow = apply_filters( 'allow_password_reset', $allow, $user->ID );

	if ( ! $allow ) {
		return new WP_Error( 'no_password_reset', __( '该用户无权修改密码' ) );
	} elseif ( is_wp_error( $allow ) ) {
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
    do_action( 'retrieve_password_key', $user->user_login, $key );
    
    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    // HASH 加密生成的密码 # 4.3.0 版本后根据时间戳生成 $hashed 解决 key 不变导致的安全问题
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    // 更新数据库
    $key_saved = wp_update_user(
		array(
			'ID'                  => $user->ID,
			'user_activation_key' => $hashed,
		)
	);
    // 更新失败返回错误
	if ( is_wp_error( $key_saved ) ) {
		return $key_saved;
	}
    return $key;
}

/**
 * 找回密码
 * 基于 wp-includes\user.php 修改所得
 */
function _retrieve_password ( $user_login = null ) {
    $errors = new WP_Error();
	$user_data = false;

    // Use the passed $user_login if available, otherwise use $_POST['user_login'].
	if ( ! $user_login && ! empty( $_POST['user_login'] ) ) {
		$user_login = $_POST['user_login'];
	}
    // 校验登录信息并获取用户数据
    if ( empty( $user_login ) ) {
        $errors->add('empty_username', __('<strong>错误</strong>: 请输入一个用户名或邮箱地址！'));
    } else if ( strpos( $_POST['user_login'], '@' ) ) {
        $user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
        if ( empty( $user_data ) ) {
            $errors->add('invalid_email', __('<strong>错误</strong>: 该用户名或邮箱地址未注册！'));
        }
    } else {
        $user_data = get_user_by('login', trim( wp_unslash( $user_login ) ));
    }

    /**
	 * Filters the user data during a password reset request.
	 * Allows, for example, custom validation using data other than username or email address.
	 * @since 5.7.0
	 * @param WP_User|false $user_data WP_User object if found, false if the user does not exist.
	 * @param WP_Error      $errors    A WP_Error object containing any errors generated
	 *                                 by using invalid credentials.
	 */
	$user_data = apply_filters( 'lostpassword_user_data', $user_data, $errors );

    /**
	 * Fires before errors are returned from a password reset request.
	 * @since 2.1.0
	 * @since 4.4.0 Added the `$errors` parameter.
	 * @since 5.4.0 Added the `$user_data` parameter.
	 * @param WP_Error      $errors    A WP_Error object containing any errors generated
	 *                                 by using invalid credentials.
	 * @param WP_User|false $user_data WP_User object if found, false if the user does not exist.
	 */
	do_action( 'lostpassword_post', $errors, $user_data );

    /**
	 * Filters the errors encountered on a password reset request.
	 * The filtered WP_Error object may, for example, contain errors for an invalid
	 * username or email address. A WP_Error object should always be returned,
	 * but may or may not contain errors.
	 * If any errors are present in $errors, this will abort the password reset request.
	 * @since 5.5.0
	 * @param WP_Error      $errors    A WP_Error object containing any errors generated
	 *                                 by using invalid credentials.
	 * @param WP_User|false $user_data WP_User object if found, false if the user does not exist.
	 */
	$errors = apply_filters( 'lostpassword_errors', $errors, $user_data );

	if ( $errors->has_errors() ) {
		return $errors;
	}

    if ( !$user_data ) {
        $errors->add('invalidcombo', __('<strong>错误</strong>: 用户名或邮箱地址无效！'));
        return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key        = _get_password_reset_key( $user_data );   // 新的密钥获取函数
    
    if ( is_wp_error( $key ) ) {
        return $key;
    }
    
    // Localize password reset message content for user.
	$locale = get_user_locale( $user_data );
    // 切换本地翻译
	$switched_locale = switch_to_locale( $locale );
    
    if ( is_multisite() ) {
        $site_name = get_network()->site_name;
    } else {
        /*
         * The blogname option is escaped with esc_html on the way into the database
         * in sanitize_option we want to reverse this for the plain text arena of emails.
         */
        $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    }

    // 邮件内容 # 你可以自定义自己的邮件信息
    $message  = __('有人请求修改您账户如下的密码信息：') . "\r\n\r\n";
	/* translators: %s: Site name. */
	$message .= sprintf( __( '网站名称: %s' ), $site_name ) . "\r\n\r\n";
    $message .= __( '网站地址: ' ) . network_home_url( '/' ) . "\r\n\r\n";
	/* translators: %s: User login. */
    $message .= sprintf(__('账户名称: %s'), $user_login) . "\r\n\r\n";
    $message .= __('如果这不是您本人操作，请忽略该邮件，一切将会像往常一样。') . "\r\n\r\n";
    $message .= __('如果您希望修改密码，请访问如下链接:') . "\r\n\r\n";
    $message .= network_site_url(_get_page_user_reset_pwd_link() . "?&action=reset_password&key=$key&login=" . rawurlencode($user_login), 'login') . '&wp_lang=' . $locale . "\r\n\r\n";
    if ( ! is_user_logged_in() ) {
		$requester_ip = $_SERVER['REMOTE_ADDR'];
		if ( $requester_ip ) {
			$message .= sprintf(
				/* translators: %s: IP address of password reset requester. */
				__( '本次发起密码重置的 IP 地址为： %s.' ),
				$requester_ip
			) . "\r\n";
		}
	}
    // 替换链接，防止错误
    $message = str_replace( site_url('/') . home_url('/'), home_url('/'), $message );
    // 邮件标题
    $title = sprintf( __('[%s]：密码重置'), $site_name );
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
    // 回复本地翻译
    if ( $switched_locale ) {
		restore_previous_locale();
	}
    // 邮件发送失败返回错误
    if ( $message && !wp_mail($user_email, wp_specialchars_decode( $title ), $message) ){
        $errors->add( 'retrieve_password_email_failure', __( '<strong>错误</strong>: 邮件发送失败。可能是由于该网站的主机禁用了 mail() 函数。' ) );
		return $errors;
    }
    
    return true;
}
/**
 * 错误消息
 * 基于 wp-login.php 中 login_header 函数部分代码修改所得 
 */
function _error_msg( $wp_error='' ) {
    if ( empty($wp_error) ) $wp_error = new WP_Error();
    
    if ( $wp_error->has_errors() ) {
        $errors = '';
        $messages = '';
        foreach ( $wp_error->get_error_codes() as $code ) {
            $severity = $wp_error->get_error_data($code);
            foreach ( $wp_error->get_error_messages($code) as $error_message ) {
                if ( 'message' == $severity ){
                    $messages .= '    ' . $error_message . "<br />\n";
                }else{
                    $errors .= '    ' . $error_message . "<br />\n";
                }
            }
        }
        if ( !empty( $errors ) ) {
            /**
             * Filter the error messages displayed above the login form.
             * @since 2.1.0
             * @param string $errors Login error message.
             */
            echo '<p class="error-tip">' . apply_filters( 'login_errors', $errors ) . "</p>\n";
        }
        if ( !empty( $messages ) ) {
            /**
             * Filter instructional messages displayed above the login form.
             * @since 2.5.0
             * @param string $messages Login messages.
             */
            echo '<p class="error-tip">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
        }
    }
}

?>


<?php
/**
 * 前端显示
 */
get_header();
?>
<section class="container">    
<div class="content-wrapper">
    <div class="module content page-reset-password">
        <header class="page-header hide">
            <h1 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
        </header>
        <ul class="steps">
            <li class="step <?php echo $retrieve_active; ?>">邮箱校验</li>
            <li class="step <?php echo $reset_active; ?>">设置密码</li>
            <li class="step <?php echo $success_active; ?>">修改成功</li>
        </ul>
        <!-- 第一步：找回密码 -->
        <?php if( $retrieve_active ){ ?> 
            <?php if( $errors !== true ){ ?>
            <div class="form-wrapper">
                <form
                method="post"
                action="<?php echo esc_url( _get_page_user_reset_pwd_link() . '?&action=retrieve_password' ); ?>"><!-- 重置密码的请求地址 -->
                    <?php _error_msg($errors); ?>
                    <h3>填写用户名或邮箱：</h3>
                    <p><input type="text" name="user_login" class="form-control" placeholder="用户名或邮箱" autofocus></p>
                    <p><input type="submit" value="获取密码重置邮件" class="btn btn-default"></p>
                </form>
            </div>
            <?php } else { ?>
                <form>
                    <p class="success-tip"> 已成功向注册邮箱发送邮件！</p>
                    <p>去邮箱查收邮件并点击重置密码链接</p>
                </form>
            <?php }?>
        <?php } ?>
        <!-- 第二步：重置密码 -->
        <?php if( $reset_active ){ ?>
        <div class="form-wrapper">
            <form action="" method="post">
                <?php _error_msg($errors); ?>
                <h3>设置新密码：</h3>
                <p><input type="password" name="password1" class="form-control" placeholder="输入新密码" autofocus></p>
                <h3>重复新密码：</h3>
                <p><input type="password" name="password2" class="form-control" placeholder="重复新密码"></p>
                <p><input type="submit" value="确认提交" class="btn btn-default"></p>
            </form>
        </div>
        <?php } ?>
        <!-- 第三步：设置成功 -->
        <?php if( $success_active ){ ?>
        <div class="form-wrapper">
            <form action="" method="post">
                <p class="success-tip"> 恭喜，密码重置成功！</p>
                <p class="text-center"><a class="btn btn-default" href="<?php echo get_bloginfo('url') ?>">返回回首页</a></p>
            </form>
        </div>
        <?php } ?>
    </div>
</div>
</section>

<?php
get_footer();
