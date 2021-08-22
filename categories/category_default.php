<?php
/**
  * @name    默认分类页面模板
  * @description 默认的分类页面模板，用户未选择任何分类模板时默认使用此模板
  */
?>

<section class="container">
    
    <div class="content">
        <div class="main">
            <?php 
            echo '<div class="cat-title  site-style-border-radius" ><h1>', single_cat_title(), $pagedtext.'</h1>'.( $description ? '<div class="cat-desc">' .$description. '</div>':'').'</div>';
            _module_loader('module_posts_excerpt_new');
            wp_reset_query();
            ?>
        </div>
    </div>
    
    <?php get_sidebar() ?>
    
</section>