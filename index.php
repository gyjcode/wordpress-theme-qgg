<?php 
// 获取头部文件
	get_header();
?>
	<?php  
	if( QGG_options('full_screen_banner_open') ){
		include get_template_directory() . '/modules/module_full_screen_banner.php';
	}
	?>
	
	<section class="container">
		<?php
		// 专题推荐模块
		if( QGG_options('topic_card_box_open') ){
			the_module_loader('module_topic_card_box');
		}
		?>
		<!-- 网站主体 -->
		<div class="content">
			<div class="main">
				<?php
				// 图像盒子文章列表
				if( QGG_options('img_box_posts_open') ){
					the_module_loader('module_img_box_posts');
				}
				?>
				<?php 
				// 最新文章列表样式
				if( QGG_options('new_posts_excerpt_open') ){
					the_module_loader('module_new_posts_excerpt');
				}
				?>
				<?php
				// 双栏文章列表样式 1
				if( QGG_options('posts_list_double_s1_open') ){
					the_module_loader('module_posts_list_double_s1');
				}
				?>
				<?php
				// 双栏文章列表样式 2
				if( QGG_options('posts_list_double_s2_open') ){
					the_module_loader('module_posts_list_double_s2');
				}
				?>
			</div>
		</div>
		<?php get_sidebar(); ?>
	</section>
	
<?php get_footer();