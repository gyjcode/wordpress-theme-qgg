<?php
/**
 * 文章点赞
 */
// 加载 wp-load.php 文件
require dirname(__FILE__).'/../../../../wp-load.php';

$post_meta_key = checkpost('key');
$post_id = checkpost('pid');
$post_link = get_post_permalink($post_id);
if ($post_meta_key !== 'likes' && !$post_link && !isInStr($post_link, 'post_type=post')) {
    print_r(json_encode(array('error' => 1)));
    exit;
}
$user_meta_val = false;
$post_in_user = false;
if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $user_meta_val = get_user_meta($user_id, 'like-posts', true);
    if ($user_meta_val) {
        $user_meta_val = unserialize($user_meta_val);
        $post_in_user = in_array($post_id, $user_meta_val);
    }
}
// POST 不存在于用户喜欢列表中则添加
if (!$user_meta_val || !$post_in_user) {
    if (!$user_meta_val) {
        $user_meta_val = array($post_id);
    } else {
        array_unshift($user_meta_val, $post_id);
    }
    upmeta($user_meta_val);
    
    $post_meta_val = (int) get_post_meta($post_id, $post_meta_key, true);
    if (!$post_meta_val) {
        $post_meta_val = 0;
    }
    update_post_meta($post_id, $post_meta_key, $post_meta_val + 1);
    print_r(json_encode(array('error' => 0, 'likes' => 1, 'response' => $post_meta_val + 1)));
    exit;
}
// POST 存在于用户喜欢列表中则移除
if ($post_in_user) {
    $key_post_in_user = array_search($post_id, $user_meta_val);
    unset($user_meta_val[$key_post_in_user]);
    upmeta($user_meta_val);
    
    $post_meta_val = (int) get_post_meta($post_id, $post_meta_key, true);
    if (!$post_meta_val) {
        $post_meta_val = 1;
    }
    update_post_meta($post_id, $post_meta_key, $post_meta_val - 1);
    print_r(json_encode(array('error' => 0, 'likes' => 0, 'response' => $post_meta_val - 1)));
    exit;
}
exit;
// 更新登录用户喜爱文章列表
function upmeta($i){
    if (is_user_logged_in()) {
        global $user_id;
        update_user_meta($user_id, 'like-posts', serialize($i));
    }
}
// 校验字符
function checkpost($j){
    return isset($_POST[$j]) ? trim(htmlspecialchars($_POST[$j], ENT_QUOTES)) : '';
}
// 校验链接
function isInStr($k, $l){
    $k = '-_-!' . $k;
    return (bool) strpos($k, $l);
}
