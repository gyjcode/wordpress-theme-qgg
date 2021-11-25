<?php 
/**
 * @name 首页滚动公告 
 * @description 在引用位置处加载一个滚动广告，后台主题设置可输入需要滚动的公告内容，每行一个，支持 HTML 代码
 */

global $current_user;
get_currentuserinfo();
// 配置项
$user_center_on    = QGG_Options('user_center_on') ?: false;
$kefu_user_m_on    = QGG_Options('rollbar_kefu_user_m_on') ?: false;
$kefu_user_m_tip   = QGG_Options('rollbar_kefu_user_m_tip') ?: '会员中心';

$kefu_m_on         = QGG_Options('rollbar_kefu_m_on') ?: false;
$kefu_sort         = QGG_Options('rollbar_kefu_sort') ?: '';
$kefu_m_sort       = QGG_Options('rollbar_kefu_m_sort') ?: '';
$kefu_top_tip      = QGG_Options('rollbar_kefu_top_tip') ?: '回顶部';
$kefu_top_m_tip    = QGG_Options('rollbar_kefu_top_m_tip') ?: '回顶';
$kefu_comt_tip     = QGG_Options('rollbar_kefu_comment_tip') ?: '去评论';
$kefu_comt_m_tip   = QGG_Options('rollbar_kefu_comment_m_tip') ?: '评论';
$kefu_tel_tip      = QGG_Options('rollbar_kefu_tel_tip') ?: '电话咨询';
$kefu_tel_m_tip    = QGG_Options('rollbar_kefu_tel_m_tip') ?: '电话';
$kefu_tel_num      = QGG_Options('rollbar_kefu_tel_num') ?: '';
$kefu_qq_tip       = QGG_Options('rollbar_kefu_qq_tip') ?: 'QQ 咨询';
$kefu_qq_m_tip     = QGG_Options('rollbar_kefu_qq_m_tip') ?: 'QQ';
$kefu_qq_num       = QGG_Options('rollbar_kefu_qq_num') ?: '';
$kefu_wechat_tip   = QGG_Options('rollbar_kefu_wechat_tip') ?: '关注微信';
$kefu_wechat_m_tip = QGG_Options('rollbar_kefu_wechat_m_tip') ?: '微信';
$kefu_wechat_qr    = QGG_Options('rollbar_kefu_wechat_qr') ?: '';
$kefu_diy_tip      = QGG_Options('rollbar_kefu_diy_tip') ?: '在线客服';
$kefu_diy_m_tip    = QGG_Options('rollbar_kefu_diy_m_tip') ?: '在线';
$kefu_diy_link     = QGG_Options('rollbar_kefu_diy_link') ?: '';

// 排序 # 去空格
$class = '';
$kefu_sort = trim( $kefu_sort );
// 手机端变更配置
if( $kefu_m_on && wp_is_mobile() ){
    $class           = 'is-mobile';
    $kefu_sort       = trim($kefu_m_sort);
    $kefu_top_tip    = $kefu_top_m_tip;
    $kefu_comt_tip   = $kefu_comt_m_tip;
    $kefu_tel_tip    = $kefu_tel_m_tip;
    $kefu_qq_tip     = $kefu_qq_m_tip;
    $kefu_wechat_tip = $kefu_wechat_m_tip;
    $kefu_diy_tip    = $kefu_diy_m_tip;
}
?>

<?php 
$kefu_html = '';
// 会员中心
//if ( $user_center_on && $kefu_user_m_on && wp_is_mobile() ){

    $user_center_link = _get_page_user_center_link();
    $user_avatar = _get_avatar($user_id=$current_user->ID, $user_email=$current_user->user_email, true);

    if( !is_user_logged_in()) {
        $kefu_html = '
        <li class="item user site-style-border-color site-style-border-radius"">
            <a rel="nofollow" href="javascript:;" class="signin-loader">
                <div class="icon site-style-color">
                    <i class="fa fa-user"></i>
                    <span>请登录</span>
                </div>
            </a>
        </li>';
    }elseif( is_user_logged_in()){
        $tag_i = $user_avatar ? '<i class="fa avatar">'.$user_avatar.'</i>' : '<i class="fa fa-user"></i>';
        $kefu_html = '
        <li class="item user site-style-border-color site-style-border-radius"">
            <a rel="nofollow" href="'.$user_center_link.'" class="signup-loader">
                <div class="icon site-style-color">
                    '.$tag_i.'
                    <span>'.$kefu_user_m_tip.'</span>
                </div>
            </a>
        </li>';
    }
//}

if( $kefu_sort ){
    $kefu_sort = explode(' ', $kefu_sort);
    foreach ($kefu_sort as $key => $value) {
        switch ($value) {
            // 回顶部
            case '1':
                $kefu_html .= '
                <li class="item top site-style-border-color site-style-border-radius">
                    <a href="javascript:(GSM.scrollTo());">
                        <div class="icon site-style-color">
                            <i class="fa fa-arrow-up"></i>
                            <span>'.$kefu_top_tip.'</span>
                        </div>
                    </a>
                </li>';
                break;
            // 去评论
            case '2':
                if( (is_single()||is_page()) && comments_open() ){
                    $kefu_html .= '
                    <li class="item comment site-style-border-color site-style-border-radius">
                        <a href="javascript:(GSM.scrollTo(\'#comments-form\',-300));">
                            <div class="icon site-style-color">
                                <i class="fa fa-comment"></i>
                                <span>'.$kefu_comt_tip.'</span>
                            </div>
                        </a>
                    </li>';
                }
                break;
            // 电话
            case '3':
                if( $kefu_tel_num ){
                    $kefu_html .= '
                    <li  class="item tel site-style-border-color site-style-border-radius">
                        <a href="tel:'. $kefu_tel_num .'">
                            <div class="icon site-style-color">
                                <i class="fa fa-phone"></i>
                                <span>'.$kefu_tel_tip.'</span>
                            </div>
                            <div class="popup site-style-color site-style-border-color site-style-border-radius">
                                <span>'.$kefu_tel_num.'</span>
                            </div>
                        </a>
                    </li>';
                }
                break;
            // 企鹅
            case '4':
                if( $kefu_qq_num ){
                    $kefu_html .= '
                    <li class="item qq site-style-border-color site-style-border-radius">
                        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin='.$kefu_qq_num.'&site=qq&menu=yes">
                            <div class="icon site-style-color">
                                <i class="fab fa-qq"></i>
                                <span>'.$kefu_qq_tip.'</span>
                            </div>
                            <div class="popup site-style-color site-style-border-color site-style-border-radius">
                                <span>'.$kefu_qq_num.'</span>
                            </div>
                        </a>
                    </li>';
                }
                break;
            // 微信
            case '5':
                if( $kefu_wechat_qr ){
                    $kefu_html .= '
                    <li class="item wechat site-style-border-color site-style-border-radius">
                        <a href="javascript:;">
                            <div class="icon site-style-color">
                                <i class="fab fa-weixin"></i>
                                <span>'.$kefu_wechat_tip.'</span>
                            </div>
                            <div class="popup site-style-color site-style-border-color site-style-border-radius">
                                <img src="'.$kefu_wechat_qr.'">
                            </div>
                        </a>
                    </li>';
                }
                break;
            // 自定义
            case '6':
                if( $kefu_diy_link ){
                    $kefu_html .= '
                    <li class="item link site-style-border-color site-style-border-radius">
                        <a target="_blank" href="'.$kefu_diy_link.'">
                            <div class="icon site-style-color">
                                <i class="fa fa-globe"></i>
                                <span>'.$kefu_diy_tip.'</span>
                            </div>
                        </a>
                    </li>';
                }
                break;
            default:
                break;
        }
    }

    echo '
    <div class="rollbar-kefu '.$class.'">
        <ul class="items">'.$kefu_html.'</ul>
    </div>';
}
?>