<?php
/**
 * 面包屑导航
 */

function module_breadcrumbs(){
    // 获取配置
    if( !QGG_Options('breadcrumbs_on') ) return false;
    $title_on = QGG_Options('breadcrumbs_title_on') ?: false;
    // 获取分类
    $categorys = get_the_category();
    if( is_home() || !$categorys ) return false;
    
    echo '<section class="container module breadcrumbs-wrapper">';
        $term_id = $categorys[0]->term_id;
        echo '当前位置：<a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a><small>/</small>'.get_category_parents($term_id, true, "<small>/</small>");
        // 文章页输出文章标题
        if( is_single() || is_page() ){
            echo $title_on ? get_the_title() : '正文';
        }
    echo '</section>';
}
