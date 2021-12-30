<?php
/**
 * @name 网站分享模板
 * @description 在引用位置处加载一个分享模块，用以用户分享网站内容
 */
$img_root       = get_template_directory_uri().'/assets/img';

// 获取配置
$share_on       = QGG_Options('post_share_on') ?? false;
$share_text     = QGG_Options('post_share_text') ?? "分享到：";
$like_on        = QGG_Options('post_like_on') ?? false;
$like_text      = QGG_Options('post_like_text') ?? "喜欢";
$poster_on      = QGG_Options('post_poster_on') ?? false;
$poster_text    = QGG_Options('post_poster_text') ?? "分享海报";
$rewards_on     = QGG_Options('post_rewards_on') ?? false;
$rewards_text   = QGG_Options('post_rewards_text') ?? "赏个钱儿";
$rewards_title  = QGG_Options('post_rewards_title') ?? "觉得文章有用就打赏一下作者吧";
$rewards_alipay = QGG_Options('post_rewards_alipay') ?? $img_root.'/qrcode.png';
$rewards_wechat = QGG_Options('post_rewards_wechat') ?? $img_root.'/qrcode.png';
?>

<section class="module share-like-reward">
    <!-- 分享 -->
    <?php if($share_on){ ?>
    <div class="post-share action action-share">
        <!-- 显示 -->
        <span class="share-text"><?php echo $share_text ?></span>
        <a class="share-qzone" href="javascript:;"  onclick="shareTo('qzone')" style="background-image:url('<?php echo $img_root ?>/share.png');"></a>
        <a class="share-qq" href="javascript:;" onclick="shareTo('qq')" style="background-image:url('<?php echo $img_root ?>/share.png');"></a>
        <a class="share-wechat" href="javascript:;" onclick="shareTo('wechat')" style="background-image:url('<?php echo $img_root ?>/share.png');"></a>
        <a class="share-weibo" href="javascript:;" onclick="shareTo('sina')"style="background-image:url('<?php echo $img_root ?>/share.png');"></a>
        <!-- 弹窗 -->
        <div class="post-qrcode-mask"></div>
        <div class="post-qrcode"></div>
    </div>
    <?php } ?>
    <div class="like-reward-poster">
        <!-- 喜欢 -->
        <?php if($like_on){ ?>
        <div class="post-like action action-like">
            <?php $class = _is_my_like() ? " activted" : ""; ?>
            <a href="javascript:;" data-event="post-like" class="<?php echo $class; ?>" data-post_id="<?php echo get_the_ID(); ?>">
                <i class="fa fa-heart"></i>&nbsp;<?php echo $like_text ?>(<span><?php echo _get_the_post_likes();?></span>)
            </a>
        </div>
        <?php } ?>

        <!-- 打赏 -->
        <?php if($rewards_on){ ?>
        <div class="post-reward action action-reward">
            <!-- 显示 -->
            <a href="javascript:;" class="action action-rewards" data-event="rewards-popover">
                <i class="fa fa-gift"></i>&nbsp;<?php echo $rewards_text ?>
            </a>
            <!-- 弹窗 -->
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
                <span class="rewards-popover-close" data-event="rewards-close"><i class="fa fa-times"></i></span>
            </div>
        </div>
        <?php } ?>

        <!-- 海报 -->
        <?php if($poster_on){ ?>
        <div class="post-poster action action-poster">
            <!-- 显示 -->
            <a href="javascript:;" class="action action-poster" data-event="poster-popover">
                <i class="fa fa-paper-plane"></i><span>&nbsp;<?php echo $poster_text ?></span>
            </a>
            <div class="poster-qrcode" style="display:none;"></div>
            <div class="poster-popover-mask" data-event="poster-close"></div>
            <!-- 弹窗 -->
            <div class="poster-popover-box">
                <a class="poster-download btn btn-default" download="<?php echo get_the_id();?>.jpg">
                    <span>下载海报</span>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</section>
