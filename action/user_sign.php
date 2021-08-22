<?php
/**
 * 登录弹窗
 */

// 加载 wp-load.php 文件
require dirname(__FILE__).'/../../../../wp-load.php';

//  获取配置项
$user_center_on = QGG_Options('user_center_on') ?: false;

// POST 数据为空时直接退出
if( !$_POST && !$_POST['action'] ){ exit; }
// 会员中心未开启时直接退出
if( !$user_center_on ){
    print_r(json_encode(array('error'=>1, 'msg'=>'抱歉，该站点未开启会员中心功能')));
    exit;
}

// 处理用户 POST 数据
$reqData = array();
foreach ($_POST as $key => $value) {
    $reqData[$key] = esc_sql(trim($value));
}
if( !$reqData['action'] ){ exit; }

switch ($reqData['action']) {
    
    // 登录
    case 'signin':
        // 已登录用户直接退出
        if( is_user_logged_in() ) {
            print_r(json_encode(array('error'=>1, 'msg'=>'你已经登录')));
            exit;
        }
        // 通过用户名或邮箱获取用户数据
        if ( !filter_var($reqData['username'], FILTER_VALIDATE_EMAIL) ){
            $findUser = get_user_by('login', $reqData['username']);
            if (empty($findUser)){
                print_r(json_encode(array('error'=>1, 'msg'=>'用户名或密码错误'))); 
                exit();  
            }
        }else{
            $findUser = get_user_by('email', $reqData['username']);
            if (empty($findUser)){
                print_r(json_encode(array('error'=>1, 'msg'=>'邮箱或密码错误')));  
                exit();  
            }
        }
        // 获取用户登录账户信息
        $login = $findUser -> user_login;
        // 保存是否勾选记住密码
        $reqData['remember'] = $reqData['remember'] ? true : false; 

        // 保存用户登录数据
        $login_data = array(
            'user_login'    => $login,
            'user_password' => $reqData['password'],
            'remember'      => $reqData['remember']
        ); 
        // 使用“记住”功能对用户进行身份验证和登录。
        $user_verify = wp_signon( $login_data, is_ssl() );   
        if ( is_wp_error($user_verify) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'用户名/邮箱或密码错误')));  
            exit();  
        }else{
            print_r(json_encode(array('error'=>0, 'msg'=>'成功登录，页面跳转中')));
            exit(); 
        }
        
        break;
        
    // 注册
    case 'signup':
        // 用户一登录直接退出并返回错误信息
        if( is_user_logged_in() ) {
            print_r(json_encode(array('error'=>1, 'msg'=>'你已经登录')));
            exit;
        }
        // 校验注册名称是否合规
        if( !preg_match('/^[a-z\d_]{3,20}$/i', $reqData['username']) ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'昵称是以字母数字下划线组合的3-20位字符')));  
            exit();  
        }
        // 校验注册名称是否合规
        if( is_disable_username($reqData['username']) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'昵称含保留或非法字符，换一个再试')));  
            exit();
        }
        // 校验邮箱地址是否合规
        if ( !filter_var($reqData['email'], FILTER_VALIDATE_EMAIL) ){ 
            print_r(json_encode(array('error'=>1, 'msg'=>'邮箱格式错误')));  
            exit();  
        }
        // 校验登录密码是否合规
        if( tool_get_strlen($reqData['password']) < 6 ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'密码太短')));  
            exit();
        }
        // 校验两次密码是否一致
        if( $reqData['password'] !== $reqData['password2'] ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'两次密码输入不一致')));  
            exit();
        }
        // 获取用户注册信息并返回状态
        $name     = $reqData['username'];
        $email    = $reqData['email'];
        $password = $reqData['password'];
        /* $password = wp_generate_password( 12, false ); */
        // 尝试注册
        $status   = wp_create_user( $name, $password ,$email );
        // 注册失败
        if ( is_wp_error($status) ){
            $err = $status -> errors;
            if( !empty($err['existing_user_login']) ){
                print_r(json_encode(array('error'=>1, 'msg'=>'用户名已存在，换一个试试')));  
            }else if( !empty($err['existing_user_email']) ){
                print_r(json_encode(array('error'=>1, 'msg'=>'邮箱已存在，换一个试试')));  
            }else{
                print_r(json_encode(array('error'=>1, 'msg'=>'注册失败，请稍后再试')));  
            }
            exit();
        }
        // 注册成功发送邮件信息
        $from    = get_option('admin_email');  
        $headers = 'From: '.$from . "\r\n";  
        $subject = '您已成功注册成为'.get_bloginfo('name').'用户';
        $msg     = '用户名：'.$reqData['username']."\r\n".'密码：'.$password."\r\n".'网址：'.get_bloginfo('url');
        // 尝试发送邮件
        $result = wp_mail( $reqData['email'], $subject, $msg, $headers );
        if( $result ){
            print_r(json_encode(array('error'=>0, 'msg'=>'密码已发送到您的邮箱，请前去查收')));
            exit();
        }else{
            print_r(json_encode(array('error'=>1, 'msg'=>'注册成功！但密码邮件发送失败，请联系网站管理员')));
            exit();
        }
        
        break;
    
    // 默认
    default:
        # code...
        break;
}

exit();


// 校验用户昵称是否合规
function is_disable_username($name){
    global $disable_reg_keywords;
    if( !$disable_reg_keywords || !$name ){
        return false;
    }
    foreach ($disable_reg_keywords as $value) {
        if( !empty($value) && is_in_str(strtolower($name), strtolower($value)) ){
            return true;
        }
    }
    return false;
}
function is_in_str($haystack, $needle) { 
    $haystack = '-_-!' . $haystack; 
    return (bool)strpos($haystack, $needle); 
}
