<?php
/**
 * @name 网站分享模板
 * @description 在引用位置处加载一个分享模块，用以用户分享网站内容
 */
$share_text = QGG_Options('post_share_text') ? QGG_Options('post_share_text') : "分享到：";
$like_text = QGG_Options('post_like_text') ? QGG_Options('post_like_text') : "喜欢";
$poster_text = QGG_Options('post_poster_text') ? QGG_Options('post_poster_text') : "分享海报";
$rewards_text = QGG_Options('post_rewards_text') ? QGG_Options('post_rewards_text') : "赏个钱儿";

$rewards_title = QGG_Options('post_rewards_title') ? QGG_Options('post_rewards_title') : "觉得文章有用就打赏一下作者吧";
$rewards_alipay = QGG_Options('post_rewards_alipay') ? QGG_Options('post_rewards_alipay') : get_template_directory_uri()."/img/qrcode.png";
$rewards_wechat = QGG_Options('post_rewards_wechat') ? QGG_Options('post_rewards_wechat') : get_template_directory_uri()."/img/qrcode.png";
?>

<section class="share-like-reward">
	<!-- 分享 -->
	<div class="post-share action action-share">
		<span class="share-text"><?php echo $share_text ?></span>
		<a class="share-qzone" href="javascript:;"  onclick="shareTo('qzone')" style="background-image:url('<?php echo get_template_directory_uri() ?>/img/share.png');"></a>
		<a class="share-qq" href="javascript:;" onclick="shareTo('qq')" style="background-image:url('<?php echo get_template_directory_uri() ?>/img/share.png');"></a>
		<!-- 文章二维码 -->
		<div class="post-qrcode-mask"></div>
		<div class="post-qrcode"></div>
		<a class="share-wechat" href="javascript:;" onclick="shareTo('wechat')" style="background-image:url('<?php echo get_template_directory_uri() ?>/img/share.png');"></a>
		<a class="share-weibo" href="javascript:;" onclick="shareTo('sina')"style="background-image:url('<?php echo get_template_directory_uri() ?>/img/share.png');"></a>
	</div>
	<div class="like-reward-poster">
	<!-- 喜欢 -->
	<div class="post-like action action-like">
		<?php $class = _is_my_like() ? " activted" : ""; ?>
		<a href="javascript:;" data-event="post-like" class="<?php echo $class; ?>" data-post_id="<?php echo get_the_ID(); ?>">
			<i class="iconfont qgg-like"></i>&nbsp;<?php echo $like_text ?>(<span><?php echo _get_post_likes();?></span>)
		</a>
	</div>
	<!-- 打赏 -->
	<div class="post-reward action action-reward">
		
		<a href="javascript:;" class="action action-rewards" data-event="rewards-popover">
			<i class="iconfont qgg-money"></i>&nbsp;<?php echo $rewards_text ?>
		</a>
		
		<div class="rewards-popover-mask" data-event="rewards-close"></div>
		
		<div class="rewards-popover-box">
			<h3><?php echo $rewards_title ?></h3>
			<?php if( $rewards_alipay ){ ?>
			<div class="rewards-popover-item">
				<h4>支付宝扫一扫打赏</h4>
				<img src="<?php echo $rewards_alipay ?>">
			</div>
			<?php } ?>
			<?php if( $rewards_wechat ){ ?>
			<div class="rewards-popover-item">
				<h4>微信扫一扫打赏</h4>
				<img src="<?php echo $rewards_wechat ?>">
			</div>
			<?php } ?>
			<span class="rewards-popover-close" data-event="rewards-close"><i class="iconfont qgg-cuohao"></i></span>
		</div>
		
	</div>
	<!-- 海报 -->
	<div class="post-poster action action-poster">
		
		<a href="javascript:;" class="item" data-event="poster-popover">
			<i class="iconfont qgg-paper_plane"></i><span>&nbsp;<?php echo $poster_text ?></span>
		</a>
		<div class="poster-qrcode" style="display:none;"></div>
		<div class="poster-popover-mask" data-event="poster-close"></div>
		
		<div class="poster-popover-box">
			<a class="poster-download btn btn-default" download="<?php echo get_the_id();?>.jpg">
				<span>下载海报</span>
			</a>
		</div>
		
	</div>
	</div>
	
</section>

