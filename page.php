<?php
/**
 * 默认页面模板
 */
get_header(); 
?>
<section class="container">
    <!-- 页面菜单 -->
    <?php _module_loader('module_page_menu', false) ?>
    <!-- 页面内容 -->
    <div class="content-wrapper">
        <div class="content site-style-border-radius">
            <?php while (have_posts()) : the_post(); ?>
            <header class="page-header">
                <h1 class="page-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
            </header>
            <article class="page-content">
                <?php the_content(); ?>
            </article>
            <!-- 分页按钮 -->
            <?php wp_link_pages( array(    // 无法设置激活的样式
                'before'            => '<div class="module link-pages-wrapper">',
                'after'             => '</div>',
                'link_before'       => '<span>',
                'link_after'        => '</span>',
                'next_or_number'    => 'number',
                'pagelink'          => '%',
                ) );
            ?>
            <?php endwhile;  ?>
            <p>&nbsp;</p>
            <?php comments_template('', true); ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>