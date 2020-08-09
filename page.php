<?php
/**
 * 默认页面模板
 */
get_header(); 
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>
<section class="container">
	<!-- 页面菜单 -->
	<?php the_module_loader('module_page_menu', false) ?>
	<!-- 页面内容 -->
	<div class="page-content">
		<div class="page-main" style="<?php echo $borderRadius; ?>">
			<?php while (have_posts()) : the_post(); ?>
			<header class="page-header">
				<h1 class="page-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
			</header>
			<article class="page-content">
				<?php the_content(); ?>
			</article>
			<?php wp_link_pages( array(
				'before'            => '<div class="page-links">',
				'after'             => '</div>',
				'link_before'       => '<span>',
				'link_after'        => '</span>',
				'next_or_number'    => 'number',
				'nextpagelink'      => __( '下一页 &raquo', 'QGG' ),
				'previouspagelink'  => __( '&laquo 上一页', 'QGG' ),
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