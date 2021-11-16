<?php
defined('ABSPATH') or die('This file can not be loaded directly.');

// 获取配置
$off              = QGG_Options('comment_off') ?: false;
$title            = QGG_Options('comment_title') ?: '评论';
$count            = $post->comment_count;
$submit_text      = QGG_Options('comment_submit_text') ?: '提交评论';
$placeholder_text = QGG_Options('comment_placeholder_text') ?: '你的评论可以一针见血';
$background_img   = QGG_Options('comment_background_img') ?: '';
$emoji_on         = QGG_Options('comment_emoji_on') ?: false;
$getqqinfo_on     = QGG_Options('comment_getqqinfo_on') ?: false;

// 计算文章发布时间
date_default_timezone_set('PRC');
$post_time  = ( strtotime(date('Y-m-d G:i:s')) - strtotime(get_the_time('Y-m-d G:i:s')) )/86400;
?>
<!-- 广告代码 -->
<?php _ads_loader($adsname='ads_comment', $classname='module ads-comment') ?>
<!-- 加载评论区域 -->
<div class="module comments-wrapper site-style-childA-hover-color">
    
    <!-- 加载评论标题 -->
    <div class="title" id="comments-title">
        <h3>
            <?php echo $title ?> 
            <?php echo $count ? '<small class="count">('.$count.')</small>' : '<small>抢沙发</small>'; ?>
        </h3>
    </div>
    
    <!-- 加载评论回复框 -->
    <div class="comments-box" id="comments-box">
        <?php if ( $off || get_default_comment_status() == 'closed' ) { ?>
            <!-- 整站评论关闭 -->
            <div class="closed">
                <h3 class="site-comments-closed">
                    <strong>抱歉，整站评论功能已关闭！</strong>
                </h3>
            </div>
        <?php }elseif( !comments_open() || (get_option('close_comments_for_old_posts') && $post_time > get_option('close_comments_days_old')) ) { ?>
            <!-- 文章评论关闭 -->
            <div class="closed">
                <h3 class="post-comments-closed">
                    <strong>抱歉，当前文章评论已关闭！</strong>
                </h3>
            </div>
        <?php }elseif ( get_option('comment_registration') && !is_user_logged_in() ) { ?>
            <!-- 登录用户评论  -->
            <div class="sign-wrapper">
                <h3 class="msg">评论前必须登录</h3>
                <p>
                    <a rel="nofollow" href="javascript:;" class="btn btn-default signin-loader">立即登录</a> &nbsp; 
                    <a rel="nofollow" href="javascript:;" class="btn btn-primary signup-loader">我要注册</a>
                </p>
            </div>
        <?php }else{ ?>
            <!-- 加载评论表单  -->
            <form
            action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php"
            id="comment-form" method="post">
                <div class="content-wrapper">
                    <!-- 评论表单左侧头像标题 -->
                    <div class="avatar-wrapper">
                        <?php if ( is_user_logged_in() ) {
                            global $current_user;
                            echo _get_avatar($user_id=get_current_user_id(), $user_email=$current_user->user_email);
                            echo '<p class="author logged ">'.$user_identity.'</p>';
                        } else {
                            if( $comment_author_email !== '' ) {
                                echo _get_avatar($user_id='', $user_email=$comment->comment_author_email);
                            } else {
                                echo _get_avatar($user_id='', $user_email='');
                            }
                            // 更换用户
                            if ( !empty($comment_author) ){
                                echo '<p class="author notlogged">'.$comment_author.'</p>';
                                echo '<p class="change-author-info"><a rel="nofollow" href="javascript:;">更换</a></p>';
                            }
                        }?>
                        <!-- 取消回复他人，直接评论文章 -->
                        <p><a id="cancel-comment-reply-link" class="cancel-comment-reply-link" href="javascript:;" rel="nofollow" >取消</a></p>
                    </div>
                    
                    <!-- 评论表单右侧评论框 -->
                    <div class="content site-style-hover-border-color">
                        <!--评论文本框-->
                        <textarea
                            id="comment" name="comment"
                            rows="3" tabindex="1"
                            style="background-image: url(<?php echo $background_img ?>);"
                            placeholder="<?php echo $placeholder_text ?>"
                            onkeydown="if(event.ctrlKey&amp;&amp;event.keyCode==13){ document.getElementById('submit').click();return false };"></textarea>

                        <!--评论工具栏-->
                        <div class="ctrls">
                            <!-- 表情 emojis -->
                            <?php  echo $emoji_on ? '<span class="emojis" title="表情"><i class="fal fa-smile"></i></span>' : ''; ?>
                            <!--评论提交按钮-->
                            <button type="submit" id="comment-submit" name="submit" class="submit site-style-background-color" tabindex="5"><?php echo $submit_text ?></button>
                        </div>

                        <!--评论提示信息-->
                        <div class="tips">
                            <div class="tip loading"><span>评论提交中...</span></div>
                            <div class="tip error"><span><span></div>
                        </div>

                        <?php
                            comment_id_fields(); 
                            do_action('comment_form', $post->ID); 
                        ?>
                    </div>
                    <!-- 评论表情 -->
                    <?php $emoji_on ? include get_template_directory().'/action/get_emojis.php' : '';?>
                    
                    <?php
                        $placeholder = "";
                        if( $getqqinfo_on ){
                            echo '<script src="'.get_template_directory_uri().'/assets/js/get-qq-info.js" type="text/javascript" charset="utf-8"></script>';
                            $placeholder = "输入QQ号快速填写";
                        }
                    ?>
                    <!-- 未登录用户加载评论者信息输入框 -->
                    <?php if ( !is_user_logged_in() ) { ?>
                    <?php if( get_option('require_name_email') ){ ?>
                        <div id="comment-author-info" class="author-info" <?php if ( !empty($comment_author) ) echo 'style="display:none"'; ?>>
                            <ul>
                                <li class="form-inline">
                                    <label class="hide" for="author">昵称</label>
                                    <input type="text" name="author" id="comment-info-author" value="<?php echo esc_attr($comment_author); ?>" tabindex="2" placeholder="<?php echo $placeholder; ?>">
                                    <span>昵称<small>(必填)</small></span>
                                </li>
                                <li class="form-inline">
                                    <label class="hide" for="email">邮箱</label>
                                    <input type="text" name="email" id="comment-info-email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="3" placeholder="<?php echo $placeholder; ?>">
                                    <span>邮箱<small>(必填)</small></span>
                                </li>
                                <li class="form-inline">
                                    <label class="hide" for="url">ＱＱ</label>
                                    <input type="text" name="qq" id="comment-info-qq" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="4" placeholder="<?php echo $placeholder; ?>" onblur="get_qq_info()">
                                    <span>ＱＱ<small>(选填)</small></span>
                                </li>
                                <li class="form-inline">
                                    <label class="hide" for="url">网址</label>
                                    <input type="text" name="url" id="comment-info-url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="5" placeholder="">
                                    <span>网址<small>(选填)</small></span>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                    <?php } ?>
                    
                </div>
                
            </form>
        <?php } ?>
    </div>
    
    <div id="comments-list" class="comments-list">
        <!-- 加载评论列表 -->
        <?php if ( have_comments() ) { ?>
        <ol class="comments-list-ol">
            <?php 
            _module_loader('module_comments_list', false);
            wp_list_comments( 
                array(
                    'type'       => 'comment',
                    'respond_id' => "comments-box",
                    'callback'   => "module_comments_list",
                )
            );
            ?>
        </ol>
        <div class="page-nav">
            <?php paginate_comments_links('prev_next=0');?>
        </div>
        <?php } ?>
    </div>
</div>