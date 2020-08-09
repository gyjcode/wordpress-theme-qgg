<?php
/**
  * WP Post Template: 视频文章
  */
	get_header();
	$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>

<div id ="video-banner" class="video-banner clearfix">
	<?php $background_img = get_post_meta( get_the_ID(), 'video_background', true ) ? get_post_meta( get_the_ID(), 'video_background', true ) : get_template_directory_uri() . '/img/banner-bg-1.png';?>
	<img class="bg-img" src="<?php echo $background_img; ?>">
	<div class="cover"></div>
	<div class="banner-wraper container">
		<div class="content">
			<div class="img-box">
				<div class="poster">
					<img src="<?php echo get_post_meta( get_the_ID(), 'video_poster', true ); ?>" alt="" class="stage-photo">
					<span>
						<?php echo get_post_meta( get_the_ID(), 'video_update_num', true ); ?> 集
					</span>
				</div>
			</div>
			<div class="text-box">
				<div class="info">
					<h1 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?><?php echo _get_the_subtitle() ?></a></h1>
					<ul>
						<li><span>浏览数</span><b><?php echo _get_post_views(); ?></b></li>
						<li><span>评论数</span><b><?php echo get_comments_number('0', '1', '%'); ?></b></li>
						<li><span>点赞数</span><b><?php echo _get_post_likes(); ?></b></li>
					</ul>
					<?php 
					$subname      = get_post_meta( get_the_ID(), 'video_subname', true );
					$director     = get_post_meta( get_the_ID(), 'video_director', true );
					$screenwriter = get_post_meta( get_the_ID(), 'video_screenwriter', true );
					$author       = get_post_meta( get_the_ID(), 'video_author', true );
					$starring     = get_post_meta( get_the_ID(), 'video_starring', true );
					$type         = get_post_meta( get_the_ID(), 'video_type', true );
					$publisher    = get_post_meta( get_the_ID(), 'video_publisher', true );
					$released     = get_post_meta( get_the_ID(), 'video_released', true );
					$language     = get_post_meta( get_the_ID(), 'video_language', true );
					$duration     = get_post_meta( get_the_ID(), 'video_duration', true );
					
					echo $subname ? '<p class="subname"><label>又名</label><i>'.$subname.'</i></p>' : '';
					echo $director ? '<p class="director"><label>导演</label><i>'.$director.'</i></p>' : '';
					echo $screenwriter ? '<p class="screenwriter"><label>编剧</label><i>'.$screenwriter.'</i></p>' : '';
					echo $author ? '<p class="author"><label>作者</label><i>'.$author.'</i></p>' : '';
					echo $starring ? '<p class="starring"><label>主演</label><i>'.$starring.'</i></p>' : '';
					echo $type ? '<p class="type"><label>类型</label><i>'.$type.'</i></p>' : '';
					echo $publisher ? '<p class="publisher"><label>发行</label><i>'.$publisher.'</i></p>' : '';
					echo $released ? '<p class="released"><label>上映</label><i>'.$released.'</i></p>' : '';
					echo $language ? '<p class="language"><label>语言</label><i>'.$language.'</i></p>' : '';
					echo $duration ? '<p class="duration"><label>时长</label><i>'.$duration.'</i></p>' : '';
					?>
					<p class="desc"> <?php echo has_excerpt() ? get_the_excerpt() : "该视频无摘要内容！！！" ; ?></p>
				</div>
				<div class="rating">
					<?php echo get_post_meta( get_the_ID(), 'video_update_num', true ); ?> 
					<i>分 / <?php echo _get_post_views(); ?> 人</i>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="content">
		<?php while (have_posts()) : the_post(); ?>
		<section id="video-wraper" class="video-wraper" style="<?php echo $borderRadius; ?>">
			<div class="video-player">
				<!-- 视频播放盒子-主体内容 -->
				<div class="player-main">
					<?php
					$video_link   = get_post_meta( get_the_ID(), 'video_list_info_1_link', true );
					$video_poster = get_template_directory_uri()."/img/video-poster.png"; 
					?>
					<video id="player" class="video-js vjs-big-play-centered" controls  preload="auto" poster="<?php echo $video_poster ?>"  data-setup="">
						<source src="<?php echo $video_link; ?>"></source>
					</video>
				</div>
				<!-- 视频播放盒子-侧栏列表 -->
				<div class="player-sidebar">
					<?php
					$update_num =get_post_meta( get_the_ID(), 'video_update_num', true );
					if ($update_num>1){
						the_module_loader('module_get_video_lists_diversity', true);
					}else{
						the_module_loader('module_get_video_lists_related', true);
					}
					?>
				</div>	
			</div>
			<!-- 视频 Meta 内容 -->
			<div class="video-info">
				<div class="info-main">
					<div class="title">
						<a href="javascript:;"><span class="rating">
							<b>共 123 人评分：</b>
							<span id="star01" class="stars" title="1星"><i class="iconfont qgg-star_filled"></i></span>
							<span id="star02" class="stars" title="2星"><i class="iconfont qgg-star_filled"></i></span>
							<span id="star03" class="stars" title="3星"><i class="iconfont qgg-star_filled"></i></span>
							<span id="star04" class="stars" title="4星"><i class="iconfont qgg-star_filled"></i></span>
							<span id="star05" class="stars" title="5星"><i class="iconfont qgg-star"></i></span>
						</span></a>
						<a href="<?php the_permalink() ?>"><h1><?php the_title(); ?></h1><?php echo _get_the_subtitle() ?></a>
					</div>
					<?php the_module_loader('module_post_share_like_reward'); ?>
				</div>
				<div class="info-ads">
					<!-- 广告代码 -->
					<?php _the_ads($name='ads_video_post_01', $class='ads-video-post-01') ?>
				</div>
			</div>
		</section>
	</div>	
	<div class="content">
		<section class="main" style="<?php echo $borderRadius; ?>">
			<!-- 用户编辑内容 -->
			<div class="article-content">
				<?php the_content(); ?>
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
		<?php endwhile; ?>
		
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer();