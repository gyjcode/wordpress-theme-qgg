<?php 
/**
 * @name 404页面模板
 * @description 网站资源找不到事显示此模板
 */
get_header(); 
?>

<section class="container">
	<div class="not-found">
		<img src="<?php echo get_template_directory_uri() ?>/img/404.png">
		<h1>404 . Not Found</h1>
		<h3>沒有找到你要的内容！</h3>
		<p>
			<a class="btn btn-primary" href="<?php echo get_bloginfo('url') ?>">返回首页</a>
		</p>
	</div>
</section>

<?php get_footer(); ?>