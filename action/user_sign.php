<?php
/**
 * @name 用户登录信息提交
 * @description 用户登录信息提交页面，用户检测用户登录状态及登录信息完整性
 */
?>

<?php
// 加载 wp-load.php 文件
require dirname(__FILE__).'/../../../../wp-load.php';

// POST 数据为空时直接退出
if( !$_POST && !$_POST['action'] ){ exit; }
// 会员中心未开启时直接退出
if( !QGG_Options('user_center_open') ){
	print_r(json_encode(array('error'=>1, 'msg'=>'抱歉，该站点未开启会员中心功能')));
	exit;
}

// 处理用户 POST 数据
$user_input = array();
foreach ($_POST as $key => $value) {
	$user_input[$key] = esc_sql(trim($value));
}
if( !$user_input['action'] ){ exit; }

switch ($user_input['action']) {
	
	// 登录
	case 'signin':
		// 已登录用户直接退出
		if( is_user_logged_in() ) {
			print_r(json_encode(array('error'=>1, 'msg'=>'你已经登录')));
			exit;
		}
		// 通过用户名或邮箱获取用户数据
		if ( !filter_var($user_input['signinName'], FILTER_VALIDATE_EMAIL) ){
			$user_data = get_user_by('login', $user_input['signinName']);
			if (empty($user_data)){
				print_r(json_encode(array('error'=>1, 'msg'=>'用户名或密码错误')));  
				exit();  
			}
		}else{
			$user_data = get_user_by('email', $user_input['signinName']);
			if (empty($user_data)){
				print_r(json_encode(array('error'=>1, 'msg'=>'邮箱或密码错误')));  
				exit();  
			}
		}
		// 获取用户登录账户信息
		$log_name = $user_data->user_login;
		// 保存是否勾选记住密码
		if($user_input['signinRemember']) { 
			$user_input['signinRemember'] = "true"; 
		}else{ 
			$user_input['signinRemember'] = "false"; 
		}
		// 保存用户登录数据
		$login_data = array(
			'user_login'    => $log_name,
			'user_password' => $user_input['signinPassword'],
			'remember'      => $user_input['signinRemember']
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
		if( !preg_match('/^[a-z\d_]{3,20}$/i', $user_input['signupName']) ) {  
			print_r(json_encode(array('error'=>1, 'msg'=>'昵称是以字母数字下划线组合的3-20位字符')));  
			exit();  
		}
		// 校验注册名称是否合规
		if( is_disable_username($user_input['signupName']) ){
			print_r(json_encode(array('error'=>1, 'msg'=>'昵称含保留或非法字符，换一个再试')));  
			exit();
		}
		// 校验邮箱地址是否合规
		if ( !filter_var($user_input['signupEmail'], FILTER_VALIDATE_EMAIL) ){ 
			print_r(json_encode(array('error'=>1, 'msg'=>'邮箱格式错误')));  
			exit();  
		}
		// 校验登录密码是否合规
		if( get_string_length($user_input['signupPassword'])<6 ) {  
			print_r(json_encode(array('error'=>1, 'msg'=>'密码太短')));  
			exit();
		}
		// 校验两次密码是否一致
		if( $user_input['signupPassword'] !== $user_input['signupPassword2'] ) {  
			print_r(json_encode(array('error'=>1, 'msg'=>'两次密码输入不一致')));  
			exit();
		}
		// 获取用户注册信息并返回状态
		/* $signup_password = wp_generate_password( 12, false ); */
		$signup_name = $user_input['signupName'];
		$signup_password = $user_input['signupPassword'];
		$signup_email = $user_input['signupEmail'];
		$signup_status = wp_create_user( $signup_name, $signup_password , $signup_email );
		// 打印错误提示
		if ( is_wp_error($signup_status) ){
			$err = $signup_status->errors;
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
		$from = get_option('admin_email');  
		$headers = 'From: '.$from . "\r\n";  
		$subject = '您已成功注册成为'.get_bloginfo('name').'用户';  
		$msg = '用户名：'.$user_input['signupName']."\r\n".'密码：'.$signup_password."\r\n".'网址：'.get_bloginfo('url');
		if( wp_mail( $user_input['signupEmail'], $subject, $msg, $headers ) ){
			print_r(json_encode(array('error'=>0, 'msg'=>'密码已发送到您的邮箱，请前去查收')));
			exit();
		}else{
			print_r(json_encode(array('error'=>1, 'msg'=>'密码邮件发送失败，请联系网站管理员')));
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
// 计算字符长度
function get_string_length($str,$charset='utf-8') {
	$n = 0; $p = 0; $c = '';
	$len = strlen($str);
	if($charset == 'utf-8') {
		for($i = 0; $i < $len; $i++) {
			$c = ord($str{$i});
			if($c > 252) {
				$p = 5;
			} elseif($c > 248) {
				$p = 4;
			} elseif($c > 240) {
				$p = 3;
			} elseif($c > 224) {
				$p = 2;
			} elseif($c > 192) {
				$p = 1;
			} else {
				$p = 0;
			}
			$i+=$p;$n++;
		}
	} else {
		for($i = 0; $i < $len; $i++) {
			$c = ord($str{$i});
			if($c > 127) {
				$p = 1;
			} else {
				$p = 0;
		}
			$i+=$p;$n++;
		}
	}
	return $n;
}