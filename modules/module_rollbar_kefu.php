<?php 
/**
 * @name 首页滚动公告 
 * @description 在引用位置处加载一个滚动广告，后台主题设置可输入需要滚动的公告内容，每行一个，支持 HTML 代码
 */

global $current_user;
get_currentuserinfo();
?>

<?php 
$kefuhtml = '';
// 会员中心
if (QGG_Options('user_center_open') && QGG_Options('rollbar_user_center_m_open') && wp_is_mobile() ){
	the_module_loader('module_get_page_user_center');
	$user_center = module_get_page_user_center();
	$user_avatar = _get_the_avatar($user_id=$current_user->ID, $user_email=$current_user->user_email, true);
	if( !is_user_logged_in()) {
		$kefuhtml = '<li class="rollbar-login"><a rel="nofollow" href="javascript:;" class="signin-loader"><i class="iconfont qgg-user_filled"></i><span>请登录</span></a></li>';
	}elseif( is_user_logged_in()){
		$tag_i = $user_avatar ? '<i class="rollbar-avatar">'.$user_avatar.'</i>' : '<i class="iconfont qgg-user_filled"></i>';
		$kefuhtml = '<li class="rollbar-login"><a href="'.$user_center.'" class="register">'.$tag_i.'<span>'.QGG_Options('kefu_user_center_m_tip').'</span></a></li>';
	}
}

// 排序
if( QGG_Options('rollbar_kefu_m_open') && wp_is_mobile() ){
	$kefu_order = trim(QGG_Options('kefu_m_sort'));
}else{
	$kefu_order = trim(QGG_Options('kefu_sort'));
}

if( $kefu_order ){
	$kefu_order = explode(' ', $kefu_order);
	foreach ($kefu_order as $key => $value) {
		switch ($value) {
			// 回顶部
			case '1':
				$kefuhtml .= '
				<li class="rollbar-totop">
					<a href="javascript:(jsui.scrollTo());">
						<i class="iconfont qgg-to_top"></i>
						<span>'.QGG_Options('kefu_top_m_tip').'</span>
					</a>'.(QGG_Options('kefu_top_tip') ? '<h6>'. QGG_Options('kefu_top_tip') .'<i></i></h6>':'').'
				</li>';
				break;
			// 去评论
			case '2':
				if( (is_single()||is_page()) && comments_open() ){
					$kefuhtml .= '
					<li class="rollbar-comment">
						<a href="javascript:(jsui.scrollTo(\'#comments-form\',-300));">
							<i class="iconfont  qgg-message"></i>
							<span>'.QGG_Options('kefu_comment_m_tip').'</span>
						</a>'.(QGG_Options('kefu_comment_tip') ? '<h6>'. QGG_Options('kefu_comment_tip') .'<i></i></h6>':'').'
					</li>';
				}
				break;
			// 电话
			case '3':
				if( QGG_Options('kefu_tel_num') ){
					$kefuhtml .= '
					<li  class="rollbar-tel">
						<a href="tel:'. QGG_Options('kefu_tel_num') .'">
						<i class="iconfont qgg-telephone_filled"></i>
						<span>'.QGG_Options('kefu_tel_m_tip').'</span>
						</a>'.(QGG_Options('kefu_tel_tip')?'<h6>'. QGG_Options('kefu_tel_tip') .'<i></i></h6>':'').'
					</li>';
				}
				break;
			// 企鹅
			case '4':
				if( QGG_Options('kefu_qq_num') ){
					$kefuhtml .= '
					<li class="rollbar-qq">
						<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin='. QGG_Options('kefu_qq_id') .'&site=qq&menu=yes">
							<i class="iconfont qgg-qq_filled"></i>
							<span>'.QGG_Options('kefu_qq_m_tip').'</span>
						</a>'.(QGG_Options('kefu_qq_tip') ? '<h6>'. QGG_Options('kefu_qq_tip') .'<i></i></h6>':'').'
					</li>';
				}
				break;
			// 微信
			case '5':
				if( QGG_Options('kefu_wechat_qr') ){
					$kefuhtml .= '
					<li class="rollbar-wechat">
						<a href="javascript:;">
							<i class="iconfont qgg-wechat_filled"></i>
							<span>'.QGG_Options('kefu_wechat_m_tip').'</span>
						</a>'.(QGG_Options('kefu_wechat_tip') ? '<h6>'. QGG_Options('kefu_wechat_tip') .(QGG_Options('kefu_wechat_qr')?'<img src="'.QGG_Options('kefu_wechat_qr').'">':'').'<i></i></h6>':'').'
					</li>';
				}
				break;
			// 自定义
			case '6':
				if( QGG_Options('kefu_diy_link') ){
					$kefuhtml .= '
					<li class="rollbar-diy">
						<a target="_blank" href="'. QGG_Options('kefu_diy_link') .'">
							<i class="iconfont qgg-earth"></i>
							<span>'.QGG_Options('kefu_diy_m_tip').'</span>
						</a>'.(QGG_Options('kefu_diy_tip')?'<h6>'. QGG_Options('kefu_diy_tip') .'<i></i></h6>':'').'
					</li>';
				}
				break;
			default:
				break;
		}
	}

	echo '<div class="rollbar-kefu"><ul>'.$kefuhtml.'</ul></div>';
}
?>