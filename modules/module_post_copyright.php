<?php 
/**
 * 文章版权信息
 */
?>
<?php 
function module_post_copyright(){
    // 获取配置
    $copyright_on = QGG_Options('post_copyright_on') ?: false;
    $copyright_title = QGG_Options('post_copyright_title') ?: '未经允许不得转载';
    if (!$copyright_on) return false;

    $html ='
    <div class="module post-copyright site-style-childA-color">
        <div class="content-wrapper">
            <div class="title"><span>'.$copyright_title.'</span></div>
            <div class="content site-style-border-radius">
                <p><span>文章标题：</span><a href="'.get_permalink().'">'.get_bloginfo('name').'&nbsp;&raquo;&nbsp;'.get_the_title().'</a></p>
                <p><span>原文链接：</span><a href="'.get_permalink().'">'.get_permalink().'</p></a>
                <p><span>发布信息：</span>文章由【<a href="'.get_author_posts_url(get_the_author_meta('ID')).'">'.get_the_author().'</a>】于<'.get_the_time('Y-m-d').'>发布于【'.get_the_category_list('/').'】分类下</p>
                <p><span>相关标签：</span>'.get_the_tag_list('',' | ','').'</p>
            </div>
        </div>
    </div>';  

    echo  $html; 
}
