<?php 
// 头部内容 header.php
    get_header();
?>

<?php 
// 全屏轮播 
if( QGG_Options('carousel_full_screen_on') ){
    if ( !wp_is_mobile() || (wp_is_mobile() && !QGG_Options('carousel_full_screen_m_off')) ){
        _module_loader('module_carousel_full_screen');
    }
}
?>
<section class="container">
    <?php
    // 专题推荐模块
    if( QGG_Options('topic_card_box_on') ){
        _module_loader('module_topic_card_box');
    }
    ?>
    <!-- 主体 -->
    <div class="content-wrapper">
        <div class="content">
            <?php
            // 图像盒子文章列表
            if( QGG_Options('img_box_posts_on') ){
                _module_loader('module_img_box_posts');
            }
            ?>
            <?php
            // 双栏文章列表样式 1
            if( QGG_Options('posts_list_double_s1_on') ){
                _module_loader('module_posts_2column_s1');
            }
            ?>
            <?php 
            // 最新文章列表样式
            if( QGG_Options('new_posts_excerpt_on') ){
                _module_loader('module_posts_excerpt_new');
            }
            ?>
            <?php
            // 双栏文章列表样式 2
            if( QGG_Options('posts_list_double_s2_on') ){
                _module_loader('module_posts_2column_s2');
            }
            ?>
        </div>
    </div>
    <!-- 侧栏 -->
    <?php get_sidebar(); ?>
</section>

    
<?php
// 页脚内容 footer.php
get_footer();