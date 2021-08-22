<?php 
/**
 * @name 相关视频列表
 * @description 在引用位置处加载一个相关文章列表模块
 */
?>

<?php
function module_posts_related_video($title='相关视频', $limit=8){
    global $post;

    $exclude_id = $post->ID; 
    $posttags = get_the_tags(); 
    $i = 1;
    $thumb_open = QGG_Options('video_lists_related_thumb');
    $thumb_open =true;
    echo '<h3 class="title">'.$title.'</h3>
        <ul id="video-lists-relates" class="video-lists-relates '.($thumb_open ? 'video-lists-relates-thumb' : '').'">';
        // 获取相同标签的文章
        if ( $posttags ) { 
            $tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->slug . ',';
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
            
                echo '<li class="item">';
                    echo $thumb_open ? '<a href="'.get_permalink().'"><div class="cover"><i class="iconfont qgg-play"></i></div>'._get_the_post_thumbnail().'</a>' : '<span class="lable lable-'.$i.'">'.$i.'</span>' ;
                    echo '<a href="'.get_permalink().'">
                        <h4>
                        <span>'.get_the_title()._get_the_post_subtitle().'</span>
                        <div><i class="iconfont qgg-eye"></i><b>'._get_the_post_views().'</b></div>
                        </h4>
                    </a>';
                echo '</li>';
                
                $exclude_id .= ',' . $post->ID; $i ++;
            };
            wp_reset_query();
        }
        // 相同标签不足相同分类补齐
        if ( $i <= $limit ) { 
            $cats = '';
            foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
            $args = array(
                'category__in'        => explode(',', $cats), 
                'post__not_in'        => explode(',', $exclude_id),
                'ignore_sticky_posts' => 1,
                'orderby'             => 'comment_date',
                'posts_per_page'      => $limit - $i + 1
            );
            
            query_posts($args);
            while( have_posts() ) { the_post();
                echo '<li class="item">';
                    echo $thumb_open ? '<a href="'.get_permalink().'"><div class="cover"><i class="iconfont qgg-play"></i></div>'._get_the_post_thumbnail().'</a>' : '<span class="lable lable-'.$i.'">'.$i.'</span>' ;
                    echo '<a href="'.get_permalink().'">
                        <h4>
                        <span>'.get_the_title()._get_the_post_subtitle().'</span>
                        <div><i class="iconfont qgg-eye"></i><b>'._get_the_post_views().'</b></div>
                        </h4>
                    </a>';
                echo '</li>';
                $i ++;
            };
            wp_reset_query();
        }
        if ( $i == 0 ){
            echo '<li>暂无文章</li>';
        }
    
    echo '</ul>';
}