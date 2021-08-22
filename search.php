<?php
/**
 * 搜索页面模板
 */
get_header();

if( !have_posts() ){
    get_template_part( '404' ); 
    get_footer();
    exit;
}
?>

<section class="container">
    <div class="content-wrapper">
        <div class="content">
            <div class="module search-title">
                <h1>
                    <i class="fa fa-search"></i>
                    <span class="site-style-color"><?php echo htmlspecialchars($s); ?></span>的搜索结果
                    <?php echo ($paged && $paged > 1) ? '<small>第'.$paged.'页</small>' : '' ?>
                </h1>
            </div>
            <?php 
                _module_loader('module_posts_excerpt_new');
                wp_reset_query();
            ?>
        </div>
    </div>
    <?php get_sidebar(); ?>
</section>

<?php
get_footer();
