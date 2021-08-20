		<footer id="footer">
			<?php 
			// 全站底部三栏推广区
			if( QGG_options('footer_brand_lmr_open') ){
				include get_template_directory() . '/modules/module_footer_brand_lmr.php';				
			}
			?>
			<?php
			// 底部友情链接
			if( QGG_options('friendly_links_open') ){
				the_module_loader('module_friendly_links');
			}
			?>
			<?php 
			if( QGG_Options('site_footer_content') ){
				echo '<div class="site-footer-code container">'.QGG_Options('site_footer_content').'</div>';
			}
			?>
			<div class="footer-copyright container">
				<p>&copy; <?php echo date('Y'); ?>
				<a href="<?php echo home_url() ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a>
				<a href="http://beian.miit.gov.cn/" target="_blank"><?php echo get_option('zh_cn_l10n_icp_num')?></a>
				<?php echo QGG_Options('footer_custom_info') ?>
				</p>
				
				<?php if ( QGG_Options('website_running_time_open') || QGG_Options('webpage_loading_time_open') ){ ?>
				<p>>>>
					<?php echo QGG_Options('website_running_time_open') ? "网站已平稳运行：<span id='site_runtime' style='display:inline-block;margin：0 5px;color: #24a0f0;'></span>" : ""; ?>
					<?php QGG_Options('webpage_loading_time_open') ? printf('本次页面加载总用时<span style="color: #24a0f0;"> %1$s </span>秒，数据库查询<span style="color: #24a0f0;"> %2$s </span>次', timer_stop(0,3), get_num_queries()) : ""; ?>
				<<<</p>
				<?php } ?>
			</div>
			<?php echo QGG_Options('site_track_code') ?>
		</footer>
		<?php
		// 浮动客服模块
		if( QGG_options('rollbar_kefu_open') ){
			the_module_loader('module_rollbar_kefu');
		}
		?>
		
		<?php
		// 功能增强
		include get_template_directory() . '/enhance/index.php';
		?>
		<?php the_module_loader('module_get_page_user_reset_pwd'); ?>
		<script>
		window.jsui={
			www             : '<?php echo home_url(); ?>',
			uri             : '<?php echo get_template_directory_uri(); ?>',
			ver             : '<?php echo THEME_VER; ?>',
			reset_pwd       : '<?php echo module_get_page_user_reset_pwd(); ?>',
			ajax_url        : '<?php echo admin_url( "admin-ajax.php" ); ?>',
			logo_pure       : '<?php echo QGG_Options("logo_pure_src"); ?>',
			att_img         : '<?php global $post; echo wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), "full")[0]; ?>',
			excerpt         : '<?php echo str_replace(array("\r\n", "\r", "\n"), "", substr(get_the_excerpt() ,0, 360)); ?>',
			author          : '<?php echo get_the_author_meta( "display_name" ); ?>',
			update          : '<?php echo get_the_modified_date("y年m月d日"); ?>',
			cat_name        : '<?php echo get_the_category()[0]->cat_name; ?>',
			poster_logo     : '<?php echo QGG_Options("post_poster_logo"); ?>', 
			poster_siteicon : '<?php echo QGG_Options("post_poster_siteicon"); ?>',
			poster_slogan   : '<?php echo QGG_Options("post_poster_slogan"); ?>', 
			site_name       : '<?php echo get_bloginfo("name"); ?>',
			site_icon       : '<?php echo QGG_Options("post_poster_siteicon"); ?>',
			site_time       : '<?php echo QGG_Options("website_running_time_start"); ?>'
		};
		</script>
		<?php wp_footer();?>
	</body>
</html>