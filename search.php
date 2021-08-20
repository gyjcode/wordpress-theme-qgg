<?php
/**
 * @name 搜索页面模板
 * @description 展示用户搜索结果，目前可设置两个广告
 */
get_header();
?>

<?php 
if( !have_posts() ){
	get_template_part( '404' ); 
	get_footer();
	exit;
}
?>

<section class="container">
	<div class="content-wrap">
		<div class="content">
			<div class="search-title">
				<h1><i class="iconfont qgg-search" aria-hidden="true"></i><span><?php echo htmlspecialchars($s); ?></span>的搜索结果</h1>
			</div>
			<?php 
				the_module_loader('module_new_posts_excerpt');
				wp_reset_query();
			?>
		</div>
	</div>
	<?php get_sidebar(); ?>
</section>

<?php get_footer(); ?>