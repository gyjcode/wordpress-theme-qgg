<?php
/**
  * WP Post Template: 产品文章
  */
get_header();
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>

<section class="product-info container" style="<?php echo $borderRadius; ?>">
	<div class="product-info-img">
		<div class="img-main">
			<?php echo _get_post_thumbnail(); ?>
		</div>
		<div class="img-list">
			<ul>
				<li>
					<?php echo _get_post_thumbnail(); ?>
				</li>
			</ul>
		</div>
	</div>
	<div class="product-info-content">
		<div class="title">
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?><?php echo _get_the_subtitle(); ?></a></h1>
		</div>
		<?php
		echo '<div class="meta">';
			$line_through =_get_product_meta("bargain_price") ? "line-through" : "none";
			if( _get_product_meta("original_price") ){
				echo '<p class="original"><label>产品原价</label>：<span style="text-decoration: '.$line_through.';">'._get_product_meta("original_price").'</span></p>';
			}
			if( _get_product_meta("bargain_price") ){
				echo '<p class="bargain"><label>特价</label>：<span>'._get_product_meta("bargain_price").'</span></p>';
			}
			if( _get_product_meta("product_info") ){
				echo '<p class="msg">'._get_product_meta("product_info").'</p>';
			}
		echo '</div>';
		echo the_tags( '<div class="tags">', '', '</div>' );
		?>
		<div class="btns">
			<a href="<?php echo _get_product_meta("product_link"); ?>" class="buy"><button>立即购买</button></a>
		</div>
	</div>
</section>

<div class="container">
	<div class="content">
		<section class="main" style="<?php echo $borderRadius; ?>">
			<!-- 用户编辑内容 -->
			<div class="article-content">
				<?php echo $post->post_content; ?>
			</div>
			
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
				echo '<div id="ads_text_post_footer"><b>AD：</b><strong>【' . QGG_Options('ads_text_post_footer_title') . '】</strong><a'.(QGG_Options('ads_text_post_footer_blank')?' target="_blank"':'').' href="' . QGG_Options('ads_text_post_footer_link') . '">' . QGG_Options('ads_text_post_footer_desc') . '</a></div>';
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
			
			<!-- 文章底部读者评论 -->
			<?php comments_template('', true); ?>
			
		</section>
		
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer();