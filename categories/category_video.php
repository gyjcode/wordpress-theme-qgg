<?php
/**
 * 视频分类页面模板
 */


?>
<section class="container">
    <!-- 主体 -->
    <div class="content-wrapper">
        <?php
        
        _module_loader('module_category_filter', false);
        $cat_ids = module_category_filter($cat_rid);

        if ( !have_posts() ){
            get_template_part( '404' );
            return;
        }
        // 查询相关文章
        $args = array(
            'cat'                 => $cat_ids,
            'orderby'             => 'date',
            'showposts'           => 60,
            'order'               => 'desc',
            'ignore_sticky_posts' => 1
        );
        query_posts($args);
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
        wp_reset_query();
        _module_loader('module_pagination');
        ?>
    </div>
</section>
