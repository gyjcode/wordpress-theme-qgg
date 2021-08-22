<?php 
/** 
 * @name 双栏文章列表（类型文章）
 * @description 在引用位置处加载一个指定分类下的文章列表
 */

$left_title  = QGG_Options('posts_list_double_s2_title_left') ?: '未设置';
$left_catId  = QGG_Options('posts_list_double_s2_catId_left') ?: 1;
$right_title = QGG_Options('posts_list_double_s2_title_right') ?: '未设置';
$right_catId = QGG_Options('posts_list_double_s2_catId_right') ?: 1;
?>

<?php
function _get_post_list_by_cat($cat){
    
    $query_post = array(
        'cat' => $cat,
        'posts_per_page'      => 5,
        'ignore_sticky_posts' => 1,
        'post_status' => 'publish',
        'post_type' => 'post',
        'orderby' => 'date',
    );
    query_posts($query_post);
    
    $i = 0;
    while(have_posts()):the_post();
        $i++;
        echo'<li>
            '._get_the_post_thumbnail().'
            <span class="meta">'.get_the_time('m-d').'</span>
            <a target="_blank" href="'.get_permalink().'" title="'.get_the_title().'-'.get_bloginfo('name').'">'.get_the_title().'</a>
        </li>';
    endwhile;
    wp_reset_query();
    
}
?>

<section class="module posts-2column-s2 site-style-border-radius site-style-childA-hover-color">
    <div class="content-wrapper site-style-border-radius">
        <div class="title">
            <h3><?php echo $left_title ; ?></h3>
        </div>
        <div class="content">
            <ul>
            <?php _get_post_list_by_cat($left_catId); ?>
            </ul>
        </div>
    </div>
    <div class="content-wrapper site-style-border-radius">
        <div class="title">
            <h3><?php echo $right_title; ?></h3>
        </div>
        <div class="content">
            <ul>
            <?php _get_post_list_by_cat($right_catId);?>
            </ul>
        </div>
    </div>
</section>