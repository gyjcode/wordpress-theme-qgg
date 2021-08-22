<?php
/**
 * @name       视频分类页面模板
 * @author     蝈蝈要安静 | 一个不学无术的伪程序员
 * @copyright  https://blog.quietguoguo.com/
 * @version    1.0.0
 */
?>

<?php 
    $args = array(
        'cat'                 => $cat_id,
        'orderby'             => 'date',
        'showposts'           => 60,
        'order'               => 'desc',
        'ignore_sticky_posts' => 1
    );
    query_posts($args);
?>

<section class="cat-video-content">
    <?php 
    if ( have_posts() ){
        echo '<div class="cat-video-main">';
            while ( have_posts() ) : the_post();
                echo '<article class="cat-video-item  site-style-border-radius">';
                    echo '<a class="thumbnail" target="_blank"  href="'.get_permalink().'">';
                        echo _get_the_post_thumbnail();
                        echo '<div class="mask"></div><i class="iconfont qgg-play"></i>';
                    echo '</a>';
                    echo '<a target="_blank" href="'.get_permalink().'"><h2>'.get_the_title().'</h2></a>';
                    echo '<div>';    
                        echo '<span><a target="_blank" href="'.get_permalink().'"><i class="iconfont qgg-eye"></i>  ('.(int) get_post_meta($post->ID, 'views', true).')</a></span>';
                        echo '<span><a href="'.get_comments_link().'"><i class="iconfont qgg-message"></i> ('.get_comments_number('0', '1', '%').')</a></span>';
                        echo '<span class="cat-video-author"><a  href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'"><i class="iconfont qgg-user"></i> '.get_the_author_meta( 'nickname' ).'</a></span>';
                    echo '</div>';
                echo '</article>';
            endwhile; 
        echo '</div>';
        
        _module_loader('module_pagination');
        
    }else{
        get_template_part( '404' );
    }
    ?>
</section>

<?php wp_reset_query(); ?>