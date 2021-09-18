<?php 
/**
 * Template name: 读者墙
 * Description:   展示网站读者，按评论排序
 */
get_header();
// 配置项
$limit_time = QGG_Options('readers_wall_limit_time') ?: 36;
$limit_num  = QGG_Options('readers_wall_limit_num') ?: 200;
?>

<div class="container">
    <!-- 页面菜单 -->
    <?php _module_loader('module_page_menu', false) ?>
    <!-- 页面内容 -->
    <div class="content-wrap">
        <div class="module content site-style-border-radius">
            <?php while (have_posts()) : the_post(); ?>
            <header class="page-header">
                <h1 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
            </header>
            <article class="page-content">
                <?php the_content(); ?>
            </article>
            <?php endwhile;  ?>

            <div class="readers-wall">
                <?php //_get_readers_wall(1, 6, 100); ?>
                <?php _get_readers_wall(1, $limit_time, $limit_num); ?>
            </div>

            <?php comments_template('', true); ?>
        </div>
    </div>
</div>

<?php
get_footer();

// 获取读者评论
function _get_readers_wall( $outer='1', $timer='3', $limit='100' ){
    // 数据库查询
    global $wpdb;
    $sql = "SELECT COUNT(comment_author) AS comment_count, user_id, comment_author, comment_author_url, comment_author_email
            FROM $wpdb->comments
            WHERE comment_date > DATE_SUB( NOW(), interval $timer MONTH ) AND user_id != '1' AND comment_author != '.$outer.' AND comment_approved = '1'
            GROUP BY comment_author
            ORDER BY comment_count DESC LIMIT $limit";

    $query_result = $wpdb->get_results($sql);

    // 处理查询结果
    $i = 0; $html = '';
    foreach ($query_result as $comment) {
        $i++;
        $author_url = $comment->comment_author_url;
        if (!$author_url) $author_url = 'javascript:;';
        
        $title = $i;
        if( $i == 1 ){
            $title = '金牌读者';
        }else if( $i == 2 ){
            $title = '银牌读者';
        }else if( $i == 3 ){
            $title = '铜牌读者';
        }else{
            $title = '第'.$i.'名';
        }
        if( $i < 4 ){
            $html .= '
            <a class="item item-'.$i.' item-top" target="_blank" href="'. $author_url . '">
                <h4>【'.$title.'】<small><i>评论：</i>('. $comment->comment_count . ')</small></h4>
                '.str_replace(' src=', ' data-src=', _get_avatar( $user_id=$comment->user_id, $user_email=$comment->comment_author_email) ).'
                <div class="desc"><span>'.$comment->comment_author.'</span><b>'.$author_url.'</b></div>
            </a>';
        }else{
            $html .= '
            <a class="item item-'.$i.'" target="_blank" href="'. $author_url . '" title="【'.$title.'】评论：'. $comment->comment_count . '">
                '.str_replace(' src=', ' data-src=', _get_avatar($user_id=$comment->user_id, $user_email=$comment->comment_author_email)).'
                <div class="desc"><span>'.$comment->comment_author.'</span></div>
            </a>';
        }
        
    }
    echo $html;
};
