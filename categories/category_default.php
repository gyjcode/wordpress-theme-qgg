<?php
/**
  * @name        默认分类页面模板
  * @description 默认的分类页面模板，用户未选择任何分类模板时默认使用此模板
  */
?>

<section class="container">
    <!-- 内容 -->
    <div class="content-wrapper">
        <div class="module content">
            <div class="category-header  site-style-border-radius" >
                <h1 class="title"><?php echo single_cat_title().$pagedtext ?></h1>
                <?php echo $description ? '<div class="desc">'.$description.'</div>' : ''; ?>
            </div>
            <?php
                _module_loader('module_posts_excerpt_new');
                wp_reset_query();
            ?>
        </div>
    </div>
    <!-- 侧栏 -->
    <?php get_sidebar(); ?>
</section>
