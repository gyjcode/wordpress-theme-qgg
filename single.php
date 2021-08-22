<?php 
// 获取头部文件
get_header();
// 获取配置 # 头部
$meta_date_on     = QGG_Options('post_meta_date_on') ?: false;
$meta_author_on   = QGG_Options('post_meta_author_on') ?: false;
$meta_author_link = QGG_Options('post_meta_author_link_on') ?: '#';
$meta_view_on     = QGG_Options('post_meta_view_on') ?: false;
$meta_like_on     = QGG_Options('post_meta_like_on') ?: false;
$meta_comment_on  = QGG_Options('post_meta_comment_on') ?: false;
// 获取配置 # 模块
$ads_01_on     = QGG_Options('ads_post_default_01_on') ?: false;
$ads_02_on     = QGG_Options('ads_post_default_02_on') ?: false;
$ads_txt_on    = QGG_Options('ads_post_footer_text_on') ?: false;
$ads_txt_title = QGG_Options('ads_post_footer_text_title') ?: '子不语';
$ads_txt_desc  = QGG_Options('ads_post_footer_text_desc') ?: '一个不学无术的伪程序员';
$ads_txt_blank = QGG_Options('ads_post_footer_text_blank') ?: false;
$ads_txt_link  = QGG_Options('ads_post_footer_text_link') ?: 'https://zibuyu.life/';
?>

<section class="container">
    <div class="content-wrapper">
        <div class="content  site-style-border-radius">
            
            <?php while (have_posts()) : the_post(); ?>
            <!-- 文章头部Meta -->
            <header class="post-header">
                <h1 class="title">
                    <?php the_title(); ?><?php echo _get_the_post_subtitle() ?>
                </h1>
                <div class="meta site-style-childA-hover-color">
                    <!-- 分类 -->
                    <span class="category">分类：<?php the_category('/')?></span>
                    <?php
                    // 日期
                    if( $meta_date_on ){
                        echo '<span class="time"><i class="fal fa-clock"></i>&nbsp;'.get_the_time('m-d').'</span>';
                    }
                    // 作者
                    if( $meta_author_on ){
                        $author = get_the_author();
                        if( $meta_author_link ){
                            $author = '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'"><i class="fal fa-user"></i>&nbsp;'.$author.'</a>';
                        } else {
                            $author = '<i class="fal fa-user"></i>&nbsp;'.$author;
                        }
                        echo '<span class="author">&nbsp'.$author.'</span>';
                    }
                    // 阅读
                    if( $meta_view_on ){
                        echo '<span class="reader"><i class="fal fa-eye"></i>&nbsp阅读('._get_the_post_views().')</span>';
                    }
                    // 喜欢
                    if( $meta_like_on ){
                        echo'<span class="like"><i class="fal fa-heart"></i>喜欢&nbsp('._get_the_post_likes().')</span>';
                    }
                    // 评论
                    if ( $meta_comment_on && comments_open() ) {
                        echo '<span class="comt"><a class="pc" href="'.get_comments_link().'"><i class="fal fa-comment"></i>&nbsp评论('.get_comments_number('0', '1', '%').')</a></span>';
                    }
                    ?>
                    <!-- 编辑 -->
                    <span class="edit"><?php edit_post_link('[编辑]'); ?></span>
                </div>
            </header>

            <!-- 广告代码 -->
            <?php $ads_01_on ? _ads_loader($name='ads_post_default_01', $class='ads-post-default-01') : '' ?>

            <!-- 文章内容 -->
            <article class="post-content">
                <?php the_content(); ?>
            </article>

            <!-- 广告代码 -->
            <?php $ads_02_on ? _ads_loader($name='ads_post_default_02', $class='ads-post-default-02') : '' ?>

            <!-- 历史文章 -->
            <?php _module_loader('module_posts_today_in_history'); ?>

            <!-- 分页按钮 -->
            <?php wp_link_pages( array(    // 无法设置激活的样式
                'before'            => '<div class="module link-pages-wrapper">',
                'after'             => '</div>',
                'link_before'       => '<span>',
                'link_after'        => '</span>',
                'next_or_number'    => 'number',
                'pagelink'          => '%',
                ) );
            ?>

            <!-- 文字广告 -->
            <?php if ($ads_txt_on) {
                echo '
                <div class="module ads-post-footer-text site-style-childA-hover-color">
                    <b>AD：</b><strong>【'.$ads_txt_title.'】</strong>
                    <a'.( $ads_txt_blank ? ' target="_blank"':'' ).' href="'.$ads_txt_link.'">'.$ads_txt_desc.'</a>
                </div>';
            } ?>

            <!-- 作者信息 -->
            <?php _module_loader('module_post_author_panel'); ?>

            <!-- 版权信息 -->
            <?php _module_loader('module_post_copyright'); ?>
            
            <?php endwhile; ?>
            
            <!-- 翻页导航 -->
            <?php _module_loader('module_post_nav_prevnext'); ?>

            <!-- 文章底部分享点赞打赏 -->
            <?php _module_loader('module_post_share_like_reward'); ?>
            
            <!-- 文章底部相关文章 -->
            <?php 
            if( QGG_Options('posts_related_on') ){
                _module_loader('module_posts_related', false); 
                module_posts_related(QGG_Options('posts_related_title'), QGG_Options('posts_related_num'), QGG_Options('posts_related_thumb_on'));
            }
            ?>
            
            <!-- 文章底部读者评论 -->
            <?php comments_template('', true); ?>
            </div>
        </div>
    </div>
    <?php get_sidebar(); ?>
</section>

<?php get_footer();