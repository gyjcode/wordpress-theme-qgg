<?php
/**
 * 全局状态管理：Global State Management
 */

$vars = array(
    'www'             => home_url(),
    'uri'             => get_stylesheet_directory_uri(),
    'ver'             => THEME_VER,
    'reset_pwd'       => _get_page_user_reset_pwd_link(),
    'ajax_url'        => admin_url( "admin-ajax.php" ),
    'logo_pure'       => QGG_Options("logo_pure_src"),
    'att_img'         => wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), "full")[0],
    'excerpt'         => str_replace(array("\r\n", "\r", "\n"), "", substr(get_the_excerpt() ,0, 360)),
    'author'          => get_the_author_meta( "display_name" ),
    'update'          => get_the_modified_date("y年m月d日"),
    'cat_name'        => get_the_category()[0]->cat_name,
    'poster_logo'     => QGG_Options("post_poster_logo"), 
    'poster_siteicon' => QGG_Options("post_poster_siteicon"),
    'poster_slogan'   => QGG_Options("post_poster_slogan"), 
    'site_name'       => get_bloginfo("name"),
    'site_icon'       => QGG_Options("post_poster_siteicon"),
    'site_time'       => QGG_Options("site_building_time")
);
?>

<script>
    window.$GSM = <?php echo json_encode($vars) ?>
</script>
