<?php  
/**
 * @name 侧栏菜单模板
 * @description 在引用位置处加载一个菜单，后台菜单面板下设置对应的菜单即可，不设置默认会获取全部页面作为菜单
 */
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>
<div class="page-sidebar">
	<div class="page-menus" style="<?php echo $borderRadius; ?>">
		<ul class="page-menus-list">
			<?php the_nav_menu('page_nav') ?>
		</ul>
	</div>
</div>