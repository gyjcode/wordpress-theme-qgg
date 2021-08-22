<?php 
/**
 * 文章作者信息
 */
?>
<?php 
function module_post_author_panel(){
    // 获取配置
    $author_on = QGG_Options('post_author_on') ?: false;

    $author_id          = get_the_author_meta('ID');
    $author_email       = get_the_author_meta('email');
    $author_nickname    = get_the_author_meta('nickname');
    $author_description = get_the_author_meta('description');

    $html ='
    <div class="module post-author-pannel">
        <div class="avatar-wrapper">'._get_avatar($author_id, $author_email).'</div>
        <div class="content-wrapper">
            <div class="title">
                <span><a href="'.get_author_posts_url( $author_id ).'">更多+</a></span> 
                <a href="'.get_author_posts_url(  $author_id ).'" title="查看更多文章">
                    <i class="fa fa-user"></i>
                    <h4>'. $author_nickname.'</h4>
                </a>
            </div>
            <div class="desc">
                <p>'.$author_description .'</p>
            </div>
        </div>
    </div>';   
    echo $author_on ? $html : ''; 
}
