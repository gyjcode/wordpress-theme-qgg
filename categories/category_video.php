<?php
/**
 * 视频分类页面模板
 */

$video_per_page = QGG_Options('cat_video_per_page') ?: 60;
// 多重分类筛选
_module_loader('module_category_filter', false);
$cat_ids = module_category_filter($cat_rid);
$current_page = max(1, get_query_var('paged'));
// 查询相关文章
$args = array(
    'cat'                 => $cat_ids,
    'posts_per_page'      => $video_per_page,
    'paged'               => $current_page,
    'orderby'             => 'date',
    'order'               => 'desc',
    'ignore_sticky_posts' => 1
);
$result = query_posts($args);

// 无文章返回 404
if ( !have_posts() ){
    get_template_part( '404' );
    return false;
}

?>
<section class="container">
    <!-- 主体 -->
    <div class="content-wrapper">
        <?php

        echo '<div class="module content cat-video-content">';
        while ( have_posts() ) : the_post();
            echo '
            <article class="video  site-style-border-radius">
                <div class="thumb-wrapper">
                    <a class="thumb" target="_blank"  href="'.get_permalink().'">
                        '._get_the_post_thumbnail().'
                    </a>
                    <div class="mask"></div>
                    <i class="fa fa-play-circle"></i>
                </div>
                <div class="details-wrapper">
                    <a class="title" target="_blank" href="'.get_permalink().'"><h2>'.get_the_title().'</h2></a>
                    <div class="metas">
                        <a class="meta views" href="'.get_permalink().'" target="_blank">
                            <i class="fa fa-eye"></i>('.(int) get_post_meta($post->ID, 'views', true).')
                        </a>
                        <a class="meta comments" href="'.get_comments_link().'" target="_blank">
                            <i class="fa fa-comments"></i>('.get_comments_number('0', '1', '%').')
                        </a>
                        <a class="meta user" href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'" target="_blank">
                            <i class="fa fa-user"></i>'.get_the_author_meta( 'nickname' ).'
                        </a>
                    </div>
                </div>
            </article>';
        endwhile;
        echo '</div>';
        
        // 分页导航
        $max_pages = ceil( $wp_query->found_posts / $video_per_page );
        _module_loader('module_pagination', false);
        module_pagination($max_pages);
        ?>
    </div>
</section>
<?php
wp_reset_query();
