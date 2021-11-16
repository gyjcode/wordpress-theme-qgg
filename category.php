<?php
/**
 * 分类页面
 */
get_header(); 
?>

<?php
// 分类页面通用
global $wp_query;
$cat_id      = get_query_var('cat');    // 获取当前分类页面的 ID
$cat_rid     = _get_cat_root_id($cat_id);    // 获取根分类页面的 ID
$paged       = get_query_var('paged') ?: 1;    // 获取分类页面页码
$pagedtext   = $paged ? '<small>第'.$paged.'页</small>' : '';
$description = trim(strip_tags(category_description()));    // 获取分类页面图像描述

// 获取分类页样式设置
$setting = _get_tax_meta($cat_rid, 'style');
$cat_styles = array(
    'default', 
    'video',
    'product',
);
// 设置分类样式
$cat_style = 'default';
if( !empty($setting) && in_array($setting, $cat_styles) ){
    $cat_style = $setting;
}
// 引入指定分类样式文件
include get_template_directory() . '/categories/category_'.$cat_style.'.php';
?>

<?php
get_footer();
