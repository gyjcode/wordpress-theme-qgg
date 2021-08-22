<?php 
/** 
 * @name 首页图片盒子模块 
 * @description 在引用位置处加载一个图片盒子用于显示指定文章，默认获取特色图片显示，没有特色图片的自动获取首图显示
 */
?>

<section class="img-box-posts module site-style-border-radius">
    <?php
    $titleShow = QGG_options('img_box_posts_title_on') ?: '';

    $query_post = array(
        'posts_per_page'      => 5,
        'ignore_sticky_posts' => 0,
        'post_status'         => 'publish',
        'post_type'           => 'post',
        'orderby'             => 'rand',
        //'post__in'            => get_option('sticky_posts')
    );
    query_posts($query_post);

    while(have_posts()):the_post();
        echo '<a target="_blank" href="'.get_permalink().'" title="">';
            echo _get_the_post_thumbnail();

            if( $titleShow ){
                echo '<div class="mask"></div>';
                echo '<div class="info">';
                    echo '<h4><b>'.get_the_title().'</b></h4>';
                echo '</div>';
            }
            
        echo '</a>';
    endwhile;

    wp_reset_query();
    ?>
</section>