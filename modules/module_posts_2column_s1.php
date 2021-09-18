<?php 
/** 
 * @name 双栏文章列表（类型文章）
 * @description 在引用位置处加载一个指定类型下的文章列表，可自主控制列表显示的文章数量。目前支持：热读文章、热评文章、随机文章、最赞文章
 */
$left_title  = QGG_Options('posts_list_double_s1_title_left') ?: '未设置';
$left_order  = QGG_Options('posts_list_double_s1_feature_left') ?: 'rand';
$right_title = QGG_Options('posts_list_double_s1_title_right') ?: '未设置';
$right_order = QGG_Options('posts_list_double_s1_feature_right') ?: 'rand';
?>

<?php
function _get_post_list_by_order($order){
    
    if ($order == 'rand'){
        $orderby = 'rand';
        $metakey = '';
    }elseif($order == 'comment'){
        $orderby = 'comment_count';
        $metakey = '';
    }elseif($order == 'view'){
        $orderby = 'meta_value_num';
        $metakey = 'views';
    }elseif($order == 'like'){
        $orderby = 'meta_value_num';
        $metakey = 'likes';
    }elseif($order == 'modified'){
        $orderby = 'modified';
        $metakey = '';
    }
    
    $query_post = array(
        'meta_key'            => $metakey, 
        'posts_per_page'      => 5,
        'ignore_sticky_posts' => 1,
        'post_status'         => 'publish',
        'post_type'           => 'post',
        'orderby'             => $orderby,
    );
    query_posts($query_post);
    
    $i = 0;
    while( have_posts()):the_post();
        $i++;
        $meta = "";
        if( $orderby == 'comment_count' ){
            $meta = '评论('.get_comments_number('0', '1', '%').')';
        }elseif( $orderby =='meta_value_num' && $metakey == 'views'){
            $meta = '阅读 ('._get_the_post_views().')';
        }elseif( $orderby =='meta_value_num' && $metakey == 'likes'){
            $meta = '喜欢 ('._get_the_post_likes().')';
        }elseif( $orderby =='modified'){
            $meta = get_the_modified_time('Y-m-d');
        }else{
            $meta = get_the_time('m-d');
        }
        echo'<li>
            <span class="lable lable-'.$i.'">'.$i.'</span>
            <span class="meta">'.$meta.'</span>
            <a target="_blank" href="'.get_permalink().'" title="'.get_the_title().'-'.get_bloginfo('name').'">'.get_the_title().'</a>
        </li>';
    endwhile;
    wp_reset_query();
    
}
?>
<section class="module posts-2column-s1 site-style-border-radius site-style-childA-hover-color">
    <div class="content-wrapper">
        <div class="title">
            <h3><?php echo $left_title; ?></h3>
        </div>
        <div class="content">
            <ul>
            <?php _get_post_list_by_order($left_order); ?>
            </ul>
        </div>
    </div>                     
    <div class="content-wrapper">
        <div class="title">
            <h3><?php echo $right_title; ?></h3>
        </div>
        <div class="content">
            <ul>
            <?php _get_post_list_by_order($right_order); ?>
            </ul>
        </div>
    </div>                    
</section>