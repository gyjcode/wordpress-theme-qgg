<?php  
/**
 * @name 侧栏菜单模板
 * @description 在引用位置处加载一个菜单，后台菜单面板下设置对应的菜单即可，不设置默认会获取全部页面作为菜单
 */
?>
<div class="page-sidebar">
    <div class="page-menus site-style-border-radius">
        <ul class="page-menus-list">
            <?php the_nav_menu('page_nav') ?>
        </ul>
    </div>
</div>