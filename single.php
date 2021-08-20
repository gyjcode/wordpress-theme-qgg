<?php 
// 获取头部文件
	get_header();
	$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>
<section class="container">
	<div class="content-wrap">
		<div class="content" style="<?php echo $borderRadius; ?>">
			
			<?php while (have_posts()) : the_post(); ?>
			<!-- 文章头部Meta -->
			<header class="post-header">
				<h1 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?><?php echo _get_the_subtitle() ?></a></h1>
				<div class="post-meta">
					<?php 
						echo '<span class="cat">分类：';
						echo the_category('/'). '</span>';
					?>
					<?php
					if( QGG_Options('post_plugin_author') ){
						$author = get_the_author();
						if( QGG_Options('author_link') ){
							$author = '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.$author.'</a>';
						}
						echo '<span class="author"><i class="iconfont qgg-user"></i>&nbsp'.$author.'</span>';
					}
					
					if( QGG_Options('post_plugin_date') ){
						echo '<span class="time"><i class="iconfont qgg-time"></i>&nbsp'.get_the_time('m-d').'</span>';
					}
					
					if( QGG_Options('post_plugin_view') ){
						echo '<span class="reader"><i class="iconfont qgg-eye"></i>&nbsp阅读('._get_post_views().')</span>';
					}
					
					if ( comments_open() && QGG_Options('post_plugin_comt') ) {
						echo '<span class="comt"><a class="pc" href="'.get_comments_link().'"><i class="iconfont qgg-message"></i>&nbsp评论('.get_comments_number('0', '1', '%').')</a></span>';
					}
					?>
					<span class="edit"><?php edit_post_link('[编辑]'); ?></span>
				</div>
			</header>
			<!-- 广告代码 -->
			<?php _the_ads($name='ads_default_post_01', $class='ads-default-post-01') ?>
			<!-- 文章内容信息 -->
			<article class="article-content">
				<?php the_content(); ?>
			</article>
			<!-- 广告代码 -->
			<?php _the_ads($name='ads_default_post_02', $class='ads-default-post-02') ?>
			<!-- 文章底部分享点赞打赏 -->
			<?php the_module_loader('module_today_in_history'); ?>			
			<!-- 文章底部分页按钮 -->
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
			<!-- 文章底部作者信息 -->
			<?php if( QGG_Options('post_author_open') ){ ?>
			<div class="article-author">
				<div class="touxiang">
					<?php echo _get_the_avatar(get_the_author_meta('ID'), get_the_author_meta('email')); ?>
				</div>
				<div class="desc">
					<div class="title">
						<span><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">更多+</a></span>
						<h4>
							<i class="iconfont qgg-user"></i>
							<a title="查看更多文章" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_meta('nickname'); ?></a>
						</h4>
					</div>
					<div class="content">
						<p><?php echo get_the_author_meta('description'); ?></p>
					</div>
				</div>
			</div>
			<?php } ?>
			<!-- 文章底部文字广告 -->
			<?php if (QGG_Options('ads_text_post_footer_open')) {
				echo '<div class="ads-text-post-footer"><b>AD：</b><strong>【' . QGG_Options('ads_text_post_footer_title') . '】</strong><a'.(QGG_Options('ads_text_post_footer_blank')?' target="_blank"':'').' href="' . QGG_Options('ads_text_post_footer_link') . '">' . QGG_Options('ads_text_post_footer_desc') . '</a></div>';
			} ?>
			<!-- 文章底部版权信息 -->
			<?php if( QGG_Options('post_copyright_open') ){
				echo '<div class="post-copyright">'; 
					echo '<div class="title"><span>'. QGG_Options('post_copyright_title') .'</span></div>';
					echo '<div class="content">
						<p><span>文章标题：</span><a href="'. get_permalink(). '">' .get_bloginfo('name'). '&nbsp;&raquo;&nbsp;' .get_the_title(). '</a></p>
						<p><span>原文链接：</span>' .get_permalink(). '</p>
						<p><span>发布信息：</span>文章由【<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author().'</a>】于<'.get_the_time('Y-m-d').'>发布于【';
						echo ''.the_category('/').'】分类下</p>
						<p><span>相关标签：</span>';
						echo ''. the_tags('','|','') .'</p>
						</div>';
				echo '</div>';
			} ?>
			
			<?php endwhile; ?>
			
			<!-- 文章底部翻页导航 -->
			<?php if( QGG_Options('post_prevnext_open') ){ 
				if( QGG_Options('post_prevnext_img') ){
					$current_category = get_the_category();
					$previmg = get_the_post_thumbnail( get_previous_post($current_category,'')->ID, '', '' );
					$nextimg = get_the_post_thumbnail( get_next_post($current_category,'')->ID, '', '' );
				}
			?>
				<nav class="article-nav <?php echo QGG_Options('post_prevnext_img') ? "" : " article-nav-no-img"; ?>">
					<span class="article-nav-prev"><?php echo $previmg ?><div class="text"><?php previous_post_link('<i class="page">上一篇</i>%link'); ?></div></span>
					<span class="article-nav-next"><?php echo $nextimg ?><div class="text"><?php next_post_link('<i class="page">下一篇</i>%link'); ?></div></span>
				</nav>
			<?php } ?>
			<!-- 文章底部分享点赞打赏 -->
			<?php the_module_loader('module_post_share_like_reward'); ?>
			
			<!-- 文章底部相关文章 -->
			<?php 
			if( QGG_Options('post_related_open') ){
				the_module_loader('module_get_posts_related', false); 
				module_get_posts_related(QGG_Options('post_related_title'), QGG_Options('post_related_num'));
			}
			?>
			
			<!-- 文章底部读者评论 -->
			<?php comments_template('', true); ?>
			</div>
		</div>
	</div>
	<?php get_sidebar(); ?>
</section>

<?php get_footer();