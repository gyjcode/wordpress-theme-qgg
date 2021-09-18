<?php
/**
 * 会员中心
 */

// 加载 wp-load.php 文件
require dirname(__FILE__).'/../../../../wp-load.php';
// 获取配置项
$user_center_on             = QGG_Options('user_center_on') ?: false;
$user_publish_on            = QGG_Options('user_publish_on') ?: false;
$user_publish_alert_mail_on = QGG_Options('user_publish_alert_mail_on') ?: false;
$user_publish_alert_mail_to = QGG_Options('user_publish_alert_mail_to') ?: false;


// POST 数据为空时直接退出
if( !$_POST ){ exit; }
// 会员中心未开启时直接退出
if( !$user_center_on ){ exit; }
// 用户未登录时直接退出
if( !is_user_logged_in() ) {
    print_r(json_encode(array('error'=>1)));
    exit;
}

$user_id = get_current_user_id();

// 处理用户 POST 数据
$user_input = array();
foreach ($_POST as $key => $value) {
    $user_input[$key] = esc_sql(trim($value));
}
if( empty($user_input['action']) ){ exit; }
if( empty($user_input['paged']) ){
    $user_input['paged'] = 1;
}

date_default_timezone_set('PRC');
$time_now = date('Y-m-d G:i:s');
$time_null = '0000-00-00 00:00:00';
$caches = array();
$printr = array();    // 打印变量

switch ($user_input['action']) {
    
    // 我要投稿
    case 'publish':
        // 投稿功能未开启时直接退出
        if( !$user_publish_on ){
            print_r(json_encode(array('error'=>1, 'msg'=>'站点未允许用户发布文章')));
            exit();
        }
        // 获取上次投稿时间,限制重复投稿
        $last_post = $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_author='{$user_id}' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
        if ( time() - strtotime($last_post) < 1 ){
            print_r(json_encode(array('error'=>1, 'msg'=>'两次提交文章时间间隔太短，请稍候再来')));  
            exit();
        }
        // 获取投稿内容
        $post_title   =  $user_input['post_title'];
        $post_url     =  $user_input['post_url'];
        $post_content =  $user_input['post_content'];
        // 判断文章标题是否合规
        if ( empty($post_title) || mb_strlen($post_title, 'utf-8') > 50 ) {
            print_r(json_encode(array('error'=>1, 'msg'=>'文章标题不能为空，且小于50个字符')));  
            exit();
        }
        // 判断文章内容是否合规
        if ( empty($post_content) || mb_strlen($post_content, 'utf-8') > 10000 || mb_strlen($post_content, 'utf-8') < 10 ) {
            print_r(json_encode(array('error'=>1, 'msg'=>'文章内容不能为空，且介于10-10000字符之间')));  
            exit();
        }
        // 判断来源链接是否合规
        if ( !empty($post_url) && mb_strlen($post_url, 'utf-8') > 200 ) {
            print_r(json_encode(array('error'=>1, 'msg'=>'来源链接不能大于200个字符')));  
            exit();
        }
        // 文章内容拼接来源链接,方便审核
        if( !empty($post_url) ){
            $post_content .= '<p>文章来源：<a href="'.$post_url.'" target="_blank">'.$post_url.'</a></p>';
        }
        // 判断文章标题是否已存在,存在则直接退出
        $post_title = $wpdb->get_var("SELECT post_title FROM $wpdb->posts WHERE post_author='{$user_id}' AND post_title = '{$post_title}' LIMIT 1");
        if( !empty($post_title) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'标题 '. $post_title .' 已存在')));  
            exit();
        }
        // 向数据库中插入文章数据
        $post_data = array(
            'post_status'  => 'pending',
            'post_title'   => $post_title,
            'post_author'  => $user_id,
            'post_content' => $post_content
        );
        $post_id = wp_insert_post( $post_data );
        // 插入文章返回的 ID 为空时提示投稿失败信息
        if (!$post_id) { 
            print_r(json_encode(array('error'=>1, 'msg'=>'投稿失败，请稍后再试')));  
            exit();
        }
        // 为投稿文章设置来源链接数据
        if( !empty($post_url) ){
           add_post_meta($post_id, 'from_url', $post_url, true);
        }
        // 邮件提醒站长投稿文章信息
        if( $user_publish_alert_mail_on ){
            wp_mail($user_publish_alert_mail_to, '站长，有新投稿：'.$post_title, $post_content);
        }
        // 投稿成功,返回信息
        print_r(json_encode(array('error'=>0, 'msg'=>'投稿成功，站长审核中...')));  
        exit();
        
        break;
    
    // 我的文章
    case 'posts':
        // 确认选择的文章发布状态
        $post_status_all = array('publish', 'draft', 'pending', 'trash', 'future');
        if( $user_input['status'] == 'all' ){
            $post_status = $post_status_all;
        }else{
            if( !in_array($user_input['status'], $post_status_all) ){ die('文章状态选择错误！'); }
            $post_status = $user_input['status'];
        }
        // 获取指定发布状态的文章
        $args = array(
            'ignore_sticky_posts' => 1,
            'showposts' => 10,
            'paged' => $user_input['paged'],
            'orderby' => 'date',
            'author' => $user_id,
            'post_status' => $post_status
        );
        query_posts($args);
        // 显示文章发布状态目录
        if( isset($user_input['first']) ){
            $printr['menus'] = array(
                array('name' => 'all', 'title' => '全部', 'count' => user_post_query('all') ),
                array('name' => 'publish', 'title' => '已发布', 'count' => user_post_query('publish') ),
                array('name' => 'future', 'title' => '定时', 'count' => user_post_query('future') ),
                array('name' => 'pending', 'title' => '待审', 'count' => user_post_query('pending') ),
                array('name' => 'draft', 'title' => '草稿', 'count' => user_post_query('draft') ),
                array('name' => 'trash', 'title' => '回收站', 'count' => user_post_query('trash') )
            );
        };
        // 获取指定发布状态文章的数量
        $count = user_post_query($user_input['status']);
        // 获取文章列表页面( 10篇文章一页，ceil()向上取整 )
        if( str_is_int($user_input['paged']) && $count && $user_input['paged'] <= ceil($count/10) ){
            $printr['items'] = user_post_data();
            $printr['max'] = $count;
        }
        
        break;
        
    // 我的评论
    case 'comments':
        // 获取当前用户评论的数量
        $count = user_comment_count();
        // 获取用户评论页面( 10个评论一页，ceil()向上取整 )
        if( str_is_int($user_input['paged']) && $count && $user_input['paged'] <= ceil($count/10) ){
            $printr['items'] = user_comment_data($user_input['paged']);
            $printr['max'] = $count;
        }
    
        break;
    
    // 获取资料
    case 'userinfo':
        // 获取用户信息并打印
        $user_data = get_userdata( $user_id );
        $printr['user'] = array(
            'regtime'   => $user_data->user_registered,
            'logname'   => $user_data->user_login,
            'nickname'  => $user_data->display_name,
            'email'     => $user_data->user_email,
            'url'       => $user_data->user_url,
            'qq'        => get_user_meta( $user_id, 'qq', true ),
            'wechat'    => get_user_meta( $user_id, 'wechat', true ),
            'weibo'     => get_user_meta( $user_id, 'weibo', true ),
            'avatar'    => get_user_meta( $user_id, 'avatar', true ),
        );
        
        break;
    
    // 修改资料
    case 'info.edit':
        $file = $user_input['avatar'];
        $file_id = array(
            'name'     => $user_input['name'],
            'type'     => $user_input['type'],
            'error'    => $user_input['error'],
            'size'     => $user_input['size']
        );
        $avatar_url = user_get_upload_avatar($file_id, $post_id = 0);
        // 校验用户昵称是否为空
        if( !$user_input['nickname']
         || ( $user_input['nickname'] && tool_get_strlen($user_input['nickname'] ) > 12 && tool_get_strlen($user_input['nickname'] ) < 2) ) {
            print_r(json_encode(array('error'=>1, 'msg'=>'昵称不能为空且限制在2-12字内')));  
            exit();
        }
        // 校验用户昵称是否合规
        if( is_disable_username($user_input['nickname']) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'昵称含保留或非法字符，换一个再试')));  
            exit();
        }
        // 校验用户邮箱是否为空
        if( !$user_input['email'] ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'邮箱不能为空')));
            exit();
        }
        // 校验用户邮箱是否合规
        if( $user_input['email'] && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $user_input['email']) ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'邮箱格式错误')));
            exit();
        }
        // 校验用户邮箱是否存在
        $hasmail = $wpdb->get_var( "SELECT ID FROM wp_users WHERE user_email='{$user_input["email"]}'" );
        if( $hasmail && (int)$hasmail !== $user_id ){
            print_r(json_encode(array('error'=>1, 'msg'=>'邮箱已存在，换一个试试')));
            exit();
        }
        // 校验用户网址是否合规
        if( $user_input['url'] && (!preg_match("/^((http|https)\:\/\/)([a-z0-9-]{1,}.)?[a-z0-9-]{2,}.([a-z0-9-]{1,}.)?[a-z0-9]{2,}$/", $user_input['url']) ) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'PHP网址格式错误')));
            exit();
        }
        // 校验 QQ 号码是否合规
        if( $user_input['qq'] && !preg_match("/^[1-9]\d{4,13}$/", $user_input['qq']) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'QQ格式错误')));
            exit();
        }
        //校验微信账号是否合规
        if( $user_input['wechat'] ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'微信字数过长，限制在30字内')));
            exit();  
        }
        // 校验微博账号是否合规
        if( $user_input['weibo'] && (!preg_match("/^((http|https)\:\/\/)([a-z0-9-]{1,}.)?[a-z0-9-]{2,}.([a-z0-9-]{1,}.)?[a-z0-9]{2,}$/", $user_input['weibo']) ) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'微博格式错误')));
            exit();
        }
        
        // 用户 Meta 信息更新
        if( $user_input['nickname'] ){ update_user_meta($user_id, 'nickname', $user_input['nickname']);}
        if( $user_input['qq'] ){ update_user_meta($user_id, 'qq', $user_input['qq']); }
        if( $user_input['wechat'] ){ update_user_meta($user_id, 'wechat', $user_input['wechat']); }
        if( $user_input['weibo'] ){ update_user_meta($user_id, 'weibo', $user_input['weibo']); }
        //update_user_meta($user_id, 'avatar', $avatar_url);
        
        // 用户其他信息更新
        $user_datas = array( 'ID' => $user_id );
        
        if( $user_input['email'] ){ $user_datas['user_email'] = $user_input['email']; }
        if( $user_input['url'] ) { $user_datas['user_url'] = $user_input['url']; }
        if( $user_input['nickname'] ) { $user_datas['display_name'] = $user_input['nickname']; }
        
        $update_user = wp_update_user( $user_datas ); 
        // 更新失败时提示错误
        if( !$update_user || is_wp_error($update_user) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'修改失败，请稍后再试')));  
            exit(); 
        }
        
        print_r(json_encode(array('error'=>0)));  
        exit(); 
        
        break;
    
    // 修改密码
    case 'password.edit':
        // 密码为空时提示错误信息
        if( !$user_input['passwordold'] && !$user_input['password'] && !$user_input['password2'] ){
            print_r(json_encode(array('error'=>1, 'msg'=>'密码不能为空'))); 
            exit();
        }
        // 限制输入密码不少于 6 位
        if( strlen($user_input['password'])<6 ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'密码至少6位')));  
            exit();
        }
        // 两次密码不一致时提示错误
        if( $user_input['password'] !== $user_input['password2'] ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'两次密码输入不一致')));  
            exit();
        }
        // 新旧密码相同时提示错误
        if( $user_input['passwordold'] == $user_input['password'] ) {  
            print_r(json_encode(array('error'=>1, 'msg'=>'新密码和原密码不能相同')));  
            exit();
        }
        // 获取原密码并解密以验证输入是否正确
        global $wp_hasher;
        require_once( ABSPATH.WPINC.'/class-phpass.php' );
        $wp_hasher = new PasswordHash(8, TRUE);
        if(!$wp_hasher->CheckPassword($user_input['passwordold'], $current_user->user_pass)) {
            print_r(json_encode(array('error'=>1, 'msg'=>'原密码错误')));  
            exit(); 
        }

        // 更新用户密码信息并返回值
        $update_user = wp_update_user( 
            array (
                'ID' => $user_id,
                'user_pass' => $user_input['password']
            ) 
        );
        // 修改密码错误打印提示信息并退出
        if( is_wp_error($update_user) ){
            print_r(json_encode(array('error'=>1, 'msg'=>'修改失败，请稍后再试')));  
            exit(); 
        }
        
        print_r(json_encode(array('error'=>0)));  
        exit(); 
        
        break;
    
    // 默认参数
    default:
        # code...
        break;
}
// 打印信息
print_r( json_encode($printr) );
exit;


// 判断整数
function str_is_int($str){
    return 0 === strcmp($str , (int)$str);
}
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
// 指定用户文章数据查询
function user_post_query( $poststatus ) {
    global $wpdb, $user_id;
    if( $poststatus == 'all' ){
        $count = $wpdb->get_var( "SELECT COUNT(1) FROM $wpdb->posts WHERE post_author={$user_id} AND post_type='post'" );
    }else{
        $count = $wpdb->get_var( "SELECT COUNT(1) FROM $wpdb->posts WHERE post_author={$user_id} AND post_type='post' AND post_status='{$poststatus}'" );
    }
    return (int)$count;
}
// 指定文章 Meta 数据查询
function user_post_data(){
    $items = array();
    while ( have_posts() ) : the_post(); 
        $cat = '';
        if( !is_category() ) {
            $category = get_the_category();
            if($category[0]){
                $cat = $category[0]->cat_name;
            }
        };

        $items[] = array(
            'thumb'   => user_get_thumbnail_src(),
            'link'    => get_permalink(),
            'title'   => html_entity_decode(get_the_title()),
            'desc'    => get_the_excerpt(),
            'time'    => get_the_time('m-d'),
            'cat'     => $cat,
            'view'    => user_get_views(),
            'comment' => (int)get_comments_number('0', '1', '%'),
            'like'    => user_get_the_post_likes_number(),
        );

    endwhile; 
    wp_reset_query();
    
    return $items;
}
// 获取文章缩略图
function user_get_thumbnail_src() {  
    global $post;
    $thumb = _get_the_post_thumbnail();  
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $thumb, $strResult, PREG_PATTERN_ORDER);  
    return $strResult[1];
}
// 获取文章喜欢数
function user_get_the_post_likes_number(){
    global $post;
    $post_ID = $post->ID;
    return (int)get_post_meta( $post_ID, 'likes', true );
}
// 获取文章阅读数
function user_get_views(){
    global $post;
    $post_ID = $post->ID;
    return (int)get_post_meta($post_ID, 'views', true);
}

// 获取用户评论数据
function user_comment_data($paged=1){
    $items = array();
    $args = array(
        'user_id' => get_current_user_id(),
        'number' => 10,
        'offset' => ($paged-1)*10
    );
    $comments = get_comments($args);
    foreach($comments as $comment){
        $items[] = array(
            'content' => $comment->comment_content,
            'post_link' => get_comment_link( $comment->comment_ID ),
            'post_title' => html_entity_decode(get_the_title( $comment->comment_post_ID )),
            'time' => $comment->comment_date
        );
    }
    return $items;
}
// 获取当前用户评论数量
function user_comment_count() {
    global $wpdb, $user_id;
    $count = $wpdb->get_var( "SELECT COUNT(1) FROM $wpdb->comments WHERE user_id={$user_id}" );
    return (int)$count;
}
// 用户上传文件更新 avatar (用户无 upload_files 权限)
function user_get_upload_avatar($file_id, $post_id){
    global $wpdb;
    // 加载必要的处理文件
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    // 自定义头像上传路径
    function avatar_upload_dir( $dir ) {
        $sub_dir = get_currentuserinfo()->ID;
        return array(
            'path'  => $dir['basedir'] . '/avatars/' . $sub_dir,
            'url'   => $dir['baseurl'] . '/avatars/' . $sub_dir,
            'subdir'=> '/avatars/' . $sub_dir,
        ) + $dir;
    }
    
    add_filter( 'upload_dir', 'avatar_upload_dir');
    
    $avatar_id = media_handle_upload( $file_id, $post_id, array(), 
        array(
            'mimes' => array(
                'jpg|jpeg|jpe'    => 'image/jpeg',
                'gif'            => 'image/gif',
                'png'            => 'image/png',
            ),
            'test_form'                    => true
        ) 
    );
    remove_filter( 'upload_dir', 'avatar_upload_dir' );
    if ( is_wp_error( $avatar_id ) ) {
        return $file_id;
    }else{
        $avatar_url = wp_get_attachment_image_src( $avatar_id, 'full')[0];
        return $avatar_url;
    }
}
