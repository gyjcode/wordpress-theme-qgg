<?php
/**
 * @name 分集视频列表模块
 * @description 在引用位置处加载一个分集视频列表模块
 */
?>

<?php

function module_posts_section_video($title='分集视频'){
    
    $update_num =get_post_meta( get_the_ID(), 'video_update_num', true );
    $output_html = '<h3 class="title">'.$title.'</h3>
    <ul id="video-lists-diversity" class="video-lists-diversity">';

    for ($i=1; $i<=$update_num; $i++) {
        $video_sort_key ='video_list_info_'.$i.'_sort';
        $video_title_key ='video_list_info_'.$i.'_title';
        $video_link_key ='video_list_info_'.$i.'_link';
        $video_poster_key ='video_list_info_'.$i.'_link';
        
        $video_sort =get_post_meta( get_the_ID(), 'video_list_info_'.$i.'_sort', true );
        $video_title =get_post_meta( get_the_ID(), 'video_list_info_'.$i.'_title', true );
        $video_link =get_post_meta( get_the_ID(), 'video_list_info_'.$i.'_link', true );
        $video_poster =get_post_meta( get_the_ID(), 'video_list_info_'.$i.'_poster', true ) ? get_post_meta( get_the_ID(), 'video_list_info_'.$i.'_poster', true ) : wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail')[0];
        
        $output_html.= '
        <li class="item">
            <a href= "javascript:;" class="video-lists-item" id="'.$video_title_key.'" data_src="'.$video_link.'">
                <div class="cover"><i class="iconfont qgg-play"></i></div>
                <img src="'.$video_poster.'" alt="'.$video_title.'">
                <h4>
                    <div><b>第</b><i>'.$video_sort.'</i><b>集</b></div>
                    <span>'.$video_title.'</span>
                </h4>
            </a>
        </li>';
        
    }

    $output_html .= '</ul>';
    echo $output_html;
}