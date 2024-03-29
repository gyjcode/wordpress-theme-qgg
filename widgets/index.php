<?php
// 移除默认小工具
add_action('widgets_init', 'unregister_default_widget');
function unregister_default_widget(){
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Media_Audio');
    unregister_widget('WP_Widget_Media_Image');
    unregister_widget('WP_Widget_Media_Video');
    unregister_widget('WP_Widget_Media_Gallery');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('WP_Widget_Custom_HTML');
}

// 新增自定义小工具
$widgets = array(
    'about_site',
    'ads_code',
    'ads_text',
    'ads_text_pic',
    'tags_hot',
    'comments_new',
    'post_author',
    'posts_categories',
    'posts_polymer',
);
foreach ($widgets as $widget) {
    include 'widget_'.$widget.'.php';
}
// 注册小工具
add_action( 'widgets_init', '_widget_loader' );
function _widget_loader() {
    global $widgets;
    foreach ($widgets as $widget) {
        register_widget( 'widget_'.$widget );
    }
}
