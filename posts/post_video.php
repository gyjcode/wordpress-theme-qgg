<?php
/**
  * WP Post Template: 视频文章
  */
get_header();

$director     = get_post_meta( get_the_ID(), 'video_director', true );    // 导演
$scriptwriter = get_post_meta( get_the_ID(), 'video_scriptwriter', true );    // 编剧
$actor        = get_post_meta( get_the_ID(), 'video_actor', true );    // 主演
$language     = get_post_meta( get_the_ID(), 'video_language', true );    // 语言
$publisher    = get_post_meta( get_the_ID(), 'video_publisher', true );    // 发行公司
$releasetime  = get_post_meta( get_the_ID(), 'video_releasetime', true );    // 上映时间
$thumbnail    = _get_the_post_thumbnail();
?>

<div class="container video-container">
    <!-- 主体 -->
    <div class="content-wrapper">
        <?php while (have_posts()) : the_post(); ?>   
        <section class="content site-style-border-radius">
            <div class="post-header">
                <div class="left">
                    <h1 class="title">
                        <?php the_title(); ?><?php echo _get_the_post_subtitle() ?>
                    </h1>
                    <div class="infos">
                    <?php 
                        echo $language     ? '<span class="language">'.$language.'</span>' : '';
                        echo $publisher    ? '<span class="publisher"><em>发行：</em>'.$publisher.'</span>' : '';
                        echo $releasetime  ? '<span class="releasetime"><em>上映：</em>'.$releasetime.'</span>' : '';
                        
                        echo '<span class="info more"><em>更多<i class="fa fa-angle-down"></i></em></span>
                            <div class="details-wrapper">
                                <div class="details">';
                                    echo $director     ? '<p><em>导演：</em>'.$director.'</p>': '';
                                    echo $scriptwriter ? '<p><em>编剧：</em>'.$scriptwriter.'</p>' : '';
                                    echo $actor        ? '<p><em>主演：</em>'.$actor.'</p>' : '';
                                    echo '<p class="except">'._get_the_post_excerpt().'</p>';
                                echo '
                                </div>
                                '.($thumbnail ? '<div class="poster">'.$thumbnail.'</div>' : '').'
                        </div>';
                    ?>
                    </div>
                </div>
                <div class="metas">
                    <!-- 评分数 -->
                    <span class="meta likes"><i class="fa fa-fire"></i><b><?php echo _get_the_post_likes() ?></b></span>
                    <!-- 阅读数 -->
                    <span class="meta views"><i class="fa fa-eye"></i><b><?php echo _get_the_post_views() ?></b></span>
                    <!-- 喜欢数 -->
                    <span class="meta likes"><i class="fa fa-heart"></i><b><?php echo _get_the_post_likes() ?></b></span>
                    <!-- 分享数 -->
                    <span class="meta likes"><i class="fa fa-share"></i><b><?php echo _get_the_post_views() ?></b></span>
                </div>
            </div>

            <!-- 广告代码 -->
            <?php _ads_loader($adsname='ads_post_video', $classname='ads-post-video') ?>

            <!-- 用户编辑内容 -->
            <div class="post-content">
                <?php the_content(); ?>
            </div>

            <!-- 点赞分享 -->
            <?php _module_loader('module_post_share_like_reward'); ?>

            <!-- 文章底部分页按钮 -->
            <?php wp_link_pages( array(
                'before'            => '<div class="page-links">',
                'after'             => '</div>',
                'link_before'       => '<span>',
                'link_after'        => '</span>',
                'next_or_number'    => 'number',
                'nextpagelink'      => __( '下一页 &raquo', 'QGG' ),
                'previouspagelink'  => __( '&laquo 上一页', 'QGG' ),
                'pagelink'          => '%',
                ) );
            ?>

            <!-- 作者信息 -->
            <?php _module_loader('module_post_author_panel'); ?>

            <!-- 版权信息 -->
            <?php _module_loader('module_post_copyright'); ?>
            
            <!-- 文章底部读者评论 -->
            <?php comments_template('', true); ?>
            
        </section>
        <?php endwhile; ?>
    </div>
    <!-- 侧栏 -->
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
<script type="text/javascript">
    $(".video-container .post-header").on("click", ".infos .more", function(){
        $(".video-container .post-header .details-wrapper").toggle();
    })
</script>