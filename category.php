<?php get_header(); ?>

<?php 
// GET ID
global $wp_query;
$cat_id = get_query_var('cat');    // 获取当前分类页面的 ID
$cat_root_id = _get_cat_root_id($cat_id);    // 获取根分类页面的 ID
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;    // 获取分类页面页码
$description = trim(strip_tags(category_description()));    // 获取分类页面图像描述


// GET STYLE
$meta_style = _get_tax_meta($cat_root_id, 'style');
$category_style = 'default';
$category_array = array(
	'default', 
	'video',
	'product'
);

if( !empty($meta_style) && in_array($meta_style, $category_array) ){
	$category_style = $meta_style;
}

// paging
$pagedtext = '';
if( $paged && $paged > 1 ){
	$pagedtext = ' <small>第'.$paged.'页</small>';
}

include get_template_directory() . '/categories/category_'.$category_style.'.php';
?>

<?php get_footer(); ?>