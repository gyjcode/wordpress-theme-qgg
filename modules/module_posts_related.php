<?php 
/**
 * @name 相关阅读模块
 * @description 在引用位置处加载一个相关文章列表模块
 */

?>

<?php
function module_posts_related($title='相关阅读', $limit=8, $thumb_on = false){
    global $post;

    $exclude_id = $post->ID; 
    $post_tags   = get_the_tags(); 
    $i = 0;
    echo '
    <section class="module posts-related '.($thumb_on ? 'has-img' : '').'">
        <div class="title">
            <h3>'.$title.'</h3>
        </div>
        <ul>';
        // 获取相同标签的文章
        if ( $post_tags ) { 
            $tags = ''; foreach ( $post_tags as $tag ) $tags .= $tag->slug . ',';
            $args = array(
                'post_status'         => 'publish',
                'tag_slug__in'        => explode(',', $tags), 
                'post__not_in'        => explode(',', $exclude_id), 
                'ignore_sticky_posts' => 1, 
                'orderby'             => 'comment_date', 
                'posts_per_page'      => $limit
            );
            query_posts($args); 
            while( have_posts() ) { the_post();
                echo '<li>';
                if( $thumb_on ) { 
                    echo '
                    <a class="pic-wrapper" href="'.get_permalink().'">'._get_the_post_thumbnail().'</a>
                    <div class="meta">
                        <div class="site-style-background-color"><i class="fa fa-eye"></i>&nbsp;阅读 ('._get_the_post_views().')</div>
                        <div class="site-style-background-color"><i class="fa fa-comment"></i>&nbsp;评论('.get_comments_number('0', '1', '%').')</a></div>
                    </div>';
                }
                echo '<div class="caption">
                        <i class="fa fa-angle-right"></i>
                        <a href="'.get_permalink().'">'.get_the_title()._get_the_post_subtitle().'</a>
                    </div>';
                echo '</li>';
                
                $exclude_id .= ',' . $post->ID; $i ++;
            };
            wp_reset_query();
        }
        // 相同标签不足相同分类补齐
        if ( $i < $limit ) { 
            $cats = '';
            foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
            $args = array(
                'category__in'        => explode(',', $cats), 
                'post__not_in'        => explode(',', $exclude_id),
                'ignore_sticky_posts' => 1,
                'orderby'             => 'comment_date',
                'posts_per_page'      => $limit - $i
            );
            query_posts($args);
            while( have_posts() ) { the_post();
                echo '<li>';
                if( $thumb_on ) { 
                    echo '
                    <a class="pic-wrapper" href="'.get_permalink().'">'._get_the_post_thumbnail().'</a>
                    <div class="meta">
                        <div class="site-style-background-color"><i class="fa fa-eye"></i>&nbsp;阅读 ('._get_the_post_views().')</div>
                        <div class="site-style-background-color"><i class="fa fa-comment"></i>&nbsp;评论('.get_comments_number('0', '1', '%').')</a></div>
                    </div>';
                }
                echo '<div class="caption">
                        <i class="fa fa-angle-right"></i>
                        <a href="'.get_permalink().'">'.get_the_title()._get_the_post_subtitle().'</a>
                    </div>';
                echo '</li>';
                $i ++;
            };
            wp_reset_query();
        }
        if ( $i == 0 ){
            echo '<li>暂无文章</li>';
        }
        
        echo '</ul>
    </section>';
}
