<?php
/**
 * 整站头部
 */

$topbar_off        = QGG_Options('topbar_off') ?: false;
$brand_text        = QGG_Options('brand_text') ?: '';
$announcement_on   = QGG_Options('announcement_on') ?: false;
$announcement_list = QGG_Options('announcement_list') ?: '';
$user_center_on    = QGG_Options('user_center_on') ?: false;
$color_bar         = QGG_Options("color_bar") ?: false;
$search_baidu_on   = QGG_Options('search_baidu_on') ?: '';
$search_baidu_code = QGG_Options('search_baidu_code') ?: '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=11,IE=10,IE=9,IE=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="<?php echo home_url() . '/favicon.ico' ?>">
    <?php wp_head();?>
</head>
<body <?php body_class(_body_class()); ?>>
    <header id="header" class="site-style-childA-hover-color">
        <!-- 顶部工具条 -->
        <?php if( !$topbar_off ){ ?>

            <div class="top-bar">
                <div class="container">
                    <!-- 网站公告 -->
                    <?php if( $announcement_on ){ ?>
                        <div class="announcement-wrap container">
                            <ul id="announcement-list" class="announcement-list">
                                <?php $list = explode(PHP_EOL, $announcement_list);
                                    foreach ($list as $item) {
                                        echo '<li class="site-stytle-childA-hover-color"><i class="fa fa-volume-up"></i>'.stripslashes( $item ).'</li>';
                                    } 
                                ?>    
                            </ul>
                        </div>
                    <?php } ?>

                    <!-- 用户登录 -->
                    <?php if( $user_center_on ){ ?>
                        <div class="user-wrap site-stytle-childA-hover-color">
                            <?php if ( !is_user_logged_in() ) {?>
                                <a rel="nofollow" href="javascript:;" class="signin-loader">Hi, 请登录</a>
                                <a rel="nofollow" href="javascript:;" class="signup-loader">我要注册</a>
                                <a rel="nofollow" rel="nofollow" href="<?php echo _get_page_user_reset_pwd_link() ?>">找回密码</a>
                            <?php } elseif( is_user_logged_in() ) { ?>
                                嗨，<?php global $current_user; echo $current_user->display_name; ?>
                                <a href="<?php echo _get_page_user_center_link(); ?>" class="register">进入会员中心</a>
                                <?php if ( is_super_admin() ) { ?>
                                    <a rel="nofollow" target="_blank" href="<?php echo site_url('/wp-admin/') ?>">后台管理</a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?> 
                </div>
            </div>

        <?php } ?>
        <!-- 公共导航 -->
        <div class="site-nav">
            <div class="container">
                <!-- 手机导航按钮 -->
                <div class="mobile-nav site-stytle-childA-hover-color">
                    <a class="mobile-nav-btn" href="javascript:;"><i class="fa fa-bars"></i></a>
                </div>
                <!-- 站点 Logo -->
                <?php _site_logo() ?>
                <!-- 品牌文字 -->
                <?php
                    if( $brand_text ){
                        $brand_text = explode("\n", $brand_text);
                        echo '<div class="brand">'.$brand_text[0]. '<br>'.$brand_text[1].'</div>';
                    }
                ?>
                <!-- 搜索按钮 -->
                <div class="search site-stytle-childA-hover-color">
                    <a class="search-btn" href="javascript:;"><i class="fa fa-search"></i></a>
                </div>
                <!-- 导航内容 -->
                <div class="nav-list site-stytle-childA-hover-color">
                    <?php
                        !wp_is_mobile() ? _site_menu('site_nav') : _site_menu('site_nav_m'); 
                    ?>
                    <div class="mobile-nav-mask"></div>
                </div>
            </div>
        </div>
        <!-- 彩色条带 -->
        <?php
            if( $color_bar ){
                echo '<div class="color-bar" style="background: url(' . $color_bar . ');"></div>';
            }
        ?>
        <!-- 搜索框 -->
        <div id="search-box" class="search-box">
            <div class="container">
                <!-- WordPress 默认搜索 -->
                <form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
                    <input class="search-input site-style-border-color" name="s" type="text" placeholder="输入关键字" value="<?php echo htmlspecialchars($s); ?>">
                    <button class="search-submit site-style-border-color site-style-background-color" type="submit"><i class="fa fa-search"></i></button>
                </form>

                <?php
                // 百度搜索
                if( $search_baidu_on && $search_baidu_code ){
                    echo '
                    <form class="search-form">
                        <input id="bdcsMain" class="search-input" type="text" placeholder="输入关键字">
                        <button class="search-submit" type="submit"><i class="fa fa-search"></i></button>
                    </form>';

                    echo $search_baidu_code;
                }
                ?>
            </div>
        </div>
        
    </header>
    <!-- 面包屑导航 -->
    <?php _module_loader('module_breadcrumbs'); ?>
    