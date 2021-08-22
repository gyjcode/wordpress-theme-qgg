<?php
/**
 * 作者页面模板
 */
get_header();
global $wp_query;
$curauth = $wp_query->get_queried_object();
?>

<section class="container">
    <div class="content-wrapper">
        <div class="content">
            <!-- 作者介绍 -->
            <div class="module author-title site-style-border-radius">
                <div class="avatar"><?php echo _get_avatar($curauth->ID, $curauth->user_email) ?></div>
                <h1  class="name site-style-color"><?php echo $curauth->display_name ?> 的文章</h1>
                <div class="desc"><?php echo get_the_author_meta('description', $curauth->ID) ?></div>
            </div>
            <!-- 文章列表 -->
            <?php
                _module_loader('module_posts_excerpt_new');
                wp_reset_query();
            ?>
        </div>
    </div>
    <?php get_sidebar() ?>
</section>

<?php get_footer();
