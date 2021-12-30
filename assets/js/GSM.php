<?php
/**
 * 全局状态管理：Global State Management
 */

$vars = array(
    'www'             => home_url(),
    'uri'             => get_stylesheet_directory_uri(),
    'ver'             => THEME_VER,
    'ajax_url'        => admin_url( "admin-ajax.php" ) ?: '',
    'reset_pwd'       => _get_page_user_reset_pwd_link(),
    // 海报
    'att_img'         => wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), "full")[0] ?? '',
    'excerpt'         => _get_the_post_excerpt(360),
    'author'          => get_the_author_meta( "display_name" ),
    'update'          => get_the_modified_date("y年m月d日"),
    'cat_name'        => get_the_category()[0]->cat_name ??'',
    'poster_logo'     => QGG_Options("post_poster_logo") ?: '', 
    'poster_icon'     => QGG_Options("post_poster_icon") ?: '',
    'poster_slogan'   => QGG_Options("post_poster_slogan") ?: '', 
    'poster_name'     => get_bloginfo("name"),
    'site_time'       => QGG_Options("site_building_time") ?: '',
    'video_ias_on'    => QGG_Options("video_ias_on") ?: false,
    'video_ias_num'   => QGG_Options("video_ias_num") ?: '',
    'video_ias_tip'   => QGG_Options("video_ias_tip") ?: '点击加载更多',
);
?>
<script type="text/javascript">
    window.GSM = <?php echo json_encode($vars) ?>
</script>
