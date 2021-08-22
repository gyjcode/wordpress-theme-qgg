<?php 
/**
 * Template name: 会员中心
 * Description:   用户中心页面包括文章、评论、资料修改等内容
 */
get_header();
?>

<?php if( !QGG_Options('user_center_on') ) exit('该功能需要开启！！！'); ?>

<section class="container">
    <div id="user-center"<?php echo is_user_logged_in() ? '' : ' class="is-sign-show" style="height:500px;"' ?>>
        <!-- 用户中心侧边栏 -->
        <div class="sidebar site-style-border-radius">
            <div class="user-title">
                <?php if( is_user_logged_in() ){ global $current_user; ?>
                <?php echo _get_avatar($user_id=$current_user->ID, $user_email=$current_user->user_email, true); ?>
                <h2><?php echo $current_user->display_name ?></h2>
            </div>
            <div class="user-menus">    
                <ul class="user-menu">
                    <?php if( QGG_Options('user_publish_on') ){ ?>
                    <li class="user-menu-publish <?php echo QGG_Options('user_publish_on') && !QGG_Options('user_erphpdown_on') ? 'm-menu' : ''; ?>"><a href="#publish"><i class="iconfont qgg-edit"></i><span>我要</span>投稿</a></li>
                    <?php } ?>
                    
                    <li class="user-menu-posts m-menu"><a href="#posts/all"><i class="iconfont qgg-copy"></i><span>我的</span><b>文章</b></a></li>
                    <li class="user-menu-comments <?php echo !QGG_Options('user_erphpdown_on') ? 'm-menu' : ''; ?> "><a href="#comments"><i class="iconfont qgg-message"></i><span>我的</span>评论</a></li>
                    
                    <?php if( QGG_Options('user_erphpdown_on') ){ ?>
                    <!-- 集成 Erphpdown -->        
                    <li class="user-menu-property m-menu"><a href="#property"><i class="iconfont qgg-money"></i><span>我的</span><b>资产</b></a></li>
                    <li class="user-menu-application"><a href="#application"><i class="iconfont qgg-balance"></i><span>我要</span><b>提现</b></a></li>
                    <li class="user-menu-tuiguang"><a href="#tuiguang"><i class="iconfont qgg-paper_plane"></i><span>我要</span><b>推广</b></a></li>
                    <li class="user-menu-vipservice m-menu"><a href="#vipservice"><i class="iconfont qgg-user"></i><span>购买</span><b>会员</b></a></li>
                    <?php } ?>
                    
                    <li class="user-menu-info m-menu"><a href="#info"><i class="iconfont qgg-info"></i><span>修改</span><b>资料</b></a></li>
                    <li class="user-menu-password m-menu"><a href="#password"><i class="iconfont qgg-lock"></i><span>修改</span><b>密码</b></a></li>
                    <li class="user-menu-signout <?php echo QGG_Options('user_publish_on') || QGG_Options('user_erphpdown_on') ? '' : 'm-menu'; ?> "><a href="<?php echo wp_logout_url(home_url()) ?>"><i class="iconfont qgg-sharing_box_filled"></i>退出</a></li>
                </ul>
            </div>
        </div>
        <!-- 用户中心内容区 -->
        <div class="content site-style-border-radius">
            <div id="content-frame">
                
                <div class="user-main">
                    
                </div>
                
                <?php 
                if( QGG_Options('user_erphpdown_on') ){ 
                    if( function_exists('recharge_money') ){ ?>
                    <!-- 集成 Erphpdown -->
                    <div class="user-main-property" style="display:none" >
                        <?php echo recharge_money(); ?>
                        <?php echo my_property(); ?> 
                        <?php echo purchased_goods_lists(); ?>
                    </div>
                    <?php 
                    }else{ 
                        echo '<div class="user-main-property" style="display:none" >请先自行安装 Erphpdown 插件！</div>';
                    }
                }?>
                
                <!-- 我要投稿 -->
                <?php if( QGG_Options('user_publish_on') ){ ?>
                <div class="user-main-publish" style="display:none">
                    <form class="user-publish-form">
                        <ul class="user-publish-meta">
                            <li><label>文章标题</label>
                                <input type="text" class="form-control" name="post_title" placeholder="请输入文章标题">
                            </li>
                            <li><label>文章内容</label>
                                <?php
                                    $content = '';
                                    $editor_id = 'post_content';
                                    $settings = array(
                                        'textarea_rows' => 10,
                                        'editor_height' => 350,
                                        'media_buttons' => false,
                                        'quicktags'     => false,
                                        'editor_css'    => '',
                                        'tinymce'       => array(
                                        'content_css'   => get_template_directory_uri() . '/css/editor-style.css'
                                        ),
                                        'teeny' => true,
                                    );
                                    wp_editor( $content, $editor_id, $settings );
                                ?>
                            </li>
                            <li><label>来源链接</label>
                                <input type="text" class="form-control" name="post_url" placeholder="文章来源链接地址">
                            </li>
                            <li>
                                <br>
                                <input type="button" evt="publish.submit" class="btn btn-default" name="submit" value="提交审核">
                                <input type="hidden" name="action" value="publish">
                            </li>
                        </ul>
                    </form>
                </div>
                <?php } ?>
                
                <div class="user-tips"></div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>

<?php if( is_user_logged_in() ){ ?>

<!-- 我要投稿 -->
<script id="temp-publish" type="text/x-jsrender">
    
</script>

<!-- 我的文章目录 -->
<script id="temp-post-menu" type="text/x-jsrender">
    <a href="#posts/{{>name}}">{{>title}}<small>({{>count}})</small></a>
</script>
<!-- 我的文章列表 -->
<script id="temp-post-item" type="text/x-jsrender">
    <li>
        <img data-src="{{>thumb}}" class="thumb">
        <div>
            <h2><a target="_blank" href="{{>link}}">{{>title}}</a></h2>
            <p class="desc">{{>desc}}</p>
            <p class="muted">{{>time}} &nbsp;&nbsp; 分类：{{>cat}} &nbsp;&nbsp; 阅读({{>view}}) &nbsp;&nbsp; 评论({{>comment}}) &nbsp;&nbsp; 喜欢({{>like}})</p>
        </div>
    </li>
</script>

<!-- 我的评论 -->
<script id="temp-comment-item" type="text/x-jsrender">
    <li>
        <time>{{>time}}</time>
        <p class="desc">{{>content}}</p>
        <p class="text-muted">文章：<a target="_blank" href="{{>post_link}}">{{>post_title}}</a></p>
    </li>
</script>

<!-- Erphpdown -->
<?php if( QGG_Options('user_erphpdown_on') && function_exists('recharge_money') ){ ?>
<script id="temp-property" type="text/x-jsrender">
    
</script>

<script id="temp-application" type="text/x-jsrender">
    <?php echo cash_application(); ?>
    <?php echo cash_application_lists(); ?>
</script>

<script id="temp-tuiguang" type="text/x-jsrender">
    <?php echo purchased_tuiguang_lists(); ?>
    <?php echo purchased_tuiguangxiazai_lists(); ?>
    <?php echo purchased_tuiguangvip_lists(); ?>
</script>

<script id="temp-vipservice" type="text/x-jsrender">    
    <?php echo vip_tracking_lists(); ?>
    <?php echo vip_member_service(); ?>
</script>
<?php } ?>

<!-- 修改资料 -->
<script id="temp-info" type="text/x-jsrender">
    <form enctype="multipart/form-data">
          <ul class="user-meta">
            <li class="user-avatar">
                <div evt="avatar.upload" class="user-avatar-img">
                <?php echo _get_avatar($user_id=$current_user->ID, $user_email=$current_user->user_email, $src = true, $size = 100) ; ?>
                </div>
                <input type="hidden" name="avatar" id="avatar" class="regular-text" value="">
                <input type="file" name="local-avatar-upload" id="local-avatar-upload" value="123" class="avatar" multiple="false">
            </li>
              <li><label>注册时间</label>
                <input type="input" class="form-control regtime" disabled value="{{>regtime}}">
            </li>
              <li><label>登录账号</label>
                <input type="input" class="form-control logname" disabled value="{{>logname}}">
              </li>
              <li><label>显示昵称</label>
                <input type="input" class="form-control nickname" name="nickname" value="{{>nickname}}">
              </li>
            <li><label>电子邮箱</label>
                <input type="email" class="form-control email" name="email" value="{{>email}}">
            </li>
              <li><label>博客网址</label>
                <input type="input" class="form-control url" name="url" value="{{>url}}">
              </li>
              <li><label>腾讯 QQ</label>
                <input type="input" class="form-control qq" name="qq" value="{{>qq}}">
              </li>
              <li><label>微信号码</label>
                <input type="input" class="form-control wechat" name="wechat" value="{{>wechat}}">
              </li>
              <li><label>微博地址</label>
                <input type="input" class="form-control weibo" name="weibo" value="{{>weibo}}">
              </li>
              <li>
                <input type="button" evt="info.submit" class="btn btn-default" name="submit" value="确认修改资料">
                <input type="hidden" name="action" value="info.edit">
              </li>
          </ul>
    </form>
</script>

<!-- 修改密码 -->
<script id="temp-password" type="text/x-jsrender">
    <form>
          <ul class="user-meta">
              <li><label><b>验证</b>原密码</label>
                <input type="password" class="form-control" name="passwordold">
              </li>
              <li><label><b>录入</b>新密码</label>
                <input type="password" class="form-control" name="password">
              </li>
              <li><label>确认<b>新密码</b></label>
                <input type="password" class="form-control" name="password2">
              </li>
              <li>
                <input type="button" evt="password.submit" class="btn btn-default" name="submit" value="确认修改密码">
                <input type="hidden" name="action" value="password.edit">
              </li>
          </ul>
    </form>
</script>

<?php } ?>

<?php 
get_footer(); 