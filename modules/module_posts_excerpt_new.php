<?php 
/**
 * @name 最新文章摘要列表
 * @description 在引用位置处加载一个网站最新文章列表，可控制最新文章显示数量
 */

$title            = QGG_options('new_posts_excerpt_title') ?: '最新发布';
$title_more       = QGG_options('new_posts_excerpt_title_more') ?: '';
$list_type        = QGG_Options('new_posts_excerpt_list_type') ?: 'thumbnail';
$tag_category_on  = QGG_Options('post_tag_category_on') ?: false;
$tag_sticky_on    = QGG_Options('post_tag_sticky_on') ?: false;
$tag_new_on       = QGG_Options('post_tag_new_on') ?: false;
$new_limit_time   = QGG_Options('post_new_limit_time') ?: 72;
$meta_date_on     = QGG_Options('post_meta_date_on') ?: false;
$meta_author_on   = QGG_Options('post_meta_author_on') ?: false;
$meta_author_link = QGG_Options('post_meta_author_link_on') ?: '#';
$meta_view_on     = QGG_Options('post_meta_view_on') ?: false;
$meta_like_on     = QGG_Options('post_meta_like_on') ?: false;
$meta_comment_on  = QGG_Options('post_meta_comment_on') ?: false;

?>
<!-- 文章列表 -->
<section class="module new-posts-excerpt site-style-childA-hover-color site-style-border-radius">
    <?php
    if ( have_posts() ){
        // 首页显示标题
        if( is_home() ){
            echo '
            <div class="title">
                <div class="more">'.$title_more.'</div>
                <h3>'.$title.'</h3>
            </div>';
        };

        $i = 0;
        while ( have_posts() ) : the_post(); 
            // 获取文章特色图像
            $thumbnail = _get_the_post_thumbnail();
            // 摘要样式 # 判断当前文章的缩略图是不是默认图 data-thumb="default"
            $excerpt_css = '';
            if( $list_type == 'text' || ($list_type == 'thumbnail_if_has' && strstr($thumbnail, 'data-thumb="default"')) ){
                $excerpt_css .= 'excerpt-text';
            }
            // 置顶文章添加类
            if( is_sticky() ){
                $excerpt_css .= 'excerpt-sticky';
            }
            // 遍历显示最新文章
            $i++;
            echo '<article class="excerpt-'.$i.' '.$excerpt_css.'">';
                // 特色图像
                echo '<div class="thumbnail-wrapper  site-style-border-radius">';
                    // 分类
                    if( $tag_category_on && !is_category() ) {
                        $category = get_the_category();
                        if($category[0]){
                            echo '<a class="tag-category site-style-background-color" href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'<i></i></a> ';
                        }
                    };
                    // 图片
                    if( $list_type == 'thumbnail' || ($list_type == 'thumbnail_if_has' && !strstr($thumbnail, 'data-thumb="default"')) ){
                        echo '<a'._post_target_blank().' class="focus" href="'.get_permalink().'">'.$thumbnail.'</a>';
                    }
                echo '</div>';
                
                echo '<div class="content">';

                    // 文章标题
                    echo '<header>
                        <a '._post_target_blank().' href="'.get_permalink().'" title="'.get_the_title()._get_the_post_subtitle(false).'-'.get_bloginfo('name').'">
                            <h2>'.get_the_title()._get_the_post_subtitle().'</h2>
                        </a>';
                        // 图标 # 最新文章
                        if( $tag_new_on ){
                            date_default_timezone_set('PRC');
                            // 计算时间差
                            $diff = strtotime( date('Y-m-d H:i:s') )-strtotime( get_the_date('Y-m-d H:i:s') ) ;
                            if($diff < $new_limit_time * 3600 ){
                                echo '<span class="tag-icon tag-new">NEW</span>';
                            }
                        }
                        // 图标 # 置顶文章
                        if( $tag_sticky_on && is_sticky() ){
                            echo '<span class="tag-icon tag-sticky">推荐</span>';
                        }
                    echo '</header>';

                    // 文章 Meta
                    echo '<p class="meta">';
                        // 日期
                        if( $meta_date_on ){
                            echo '<span><i class="fal fa-clock"></i>&nbsp'.get_the_time('m-d').'</span>';
                        }
                        // 作者
                        if( $meta_author_on ){
                            $author = get_the_author();
                            if( $meta_author_link ){
                                $author = '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'"><i class="fal fa-user"></i> '.$author.'</a>';
                            } else {
                                $author = '<i class="fal fa-user"></i> '.$author;
                            }
                            echo '<span class="author">&nbsp'.$author.'</span>';
                        }
                        // 查阅
                        if( $meta_view_on ){
                            echo '<span class="view"><i class="fal fa-eye"></i> 阅读&nbsp('._get_the_post_views().')</span>';
                        }
                        // 喜欢
                        if( $meta_like_on ){
                            echo'<span class="like"><i class="fal fa-heart"></i>喜欢&nbsp('._get_the_post_likes().')</span>';
                        }
                        // 评论
                        if ( $meta_comment_on && comments_open() ) {
                            echo '<span class="comment"><a class="pc" href="'.get_comments_link().'"><i class="fal fa-comment"></i>&nbsp评论('.get_comments_number('0', '1', '%').')</a></span>';
                        }
                    echo '</p>';

                    // 文章摘要
                    echo '<p class="desc">'._get_the_post_excerpt().'</p>';
                    // 文章标签
                    echo the_tags( '<p class="tags">', '', '</p>' ); 
                    // 更多按钮
                    echo '<a'._post_target_blank().' href="'.get_permalink().'"><div class="tag-more site-style-background-color">了解更多</div></a>';
                echo '</div>';
                
            echo '</article>';

            // 每 10 篇文章，在第三篇位置处插入一个广告
            if ( $i == 3 ) {
                echo '<article class="excerpt-ads">';
                    _ads_loader($adsname='ads_post_list', $classname='ads-post-list site-style-border-radius');
                echo '</article>';
            }
               
        endwhile; 
        // 分页
        _module_loader('module_pagination');
    
    }else{
        get_template_part( '404' );
    }
    ?>
</section>

