<?php 
/**
 * @name 最新文章摘要列表
 * @description 在引用位置处加载一个网站最新文章列表，可控制最新文章显示数量
 */
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>
<!-- 广告代码 -->
<?php _the_ads($name='ads_post_list_01', $class='ads-post-list-01') ?>
<section class="new-posts-excerpt" style="<?php echo $borderRadius; ?>">
	<?php
	if ( have_posts() ){
		if( is_home() ){
			echo '<div class="new-posts-title">';
				echo '<div class="more">'.QGG_options('new_posts_excerpt_title_more').'</div>';
				echo '<h3 class="title">';
				echo QGG_options('new_posts_excerpt_title') ? QGG_options('new_posts_excerpt_title') : "最新发布";
				echo '</h3>';
			echo '</div>';
		};
	$i = 0;
	while ( have_posts() ) : the_post(); 

		$_thumb = _get_post_thumbnail();

		$_excerpt_css = '';
		if( QGG_Options('list_type')=='text' || (QGG_Options('list_type') == 'thumb_if_has' && strstr($_thumb, 'data-thumb="default"')) ){
			$_excerpt_css .= 'excerpt-text';
		}
		if( QGG_Options('home_sticky_open') && is_sticky() ){
			$_excerpt_css .= 'excerpt-sticky';
		}
		
		$i++;
		echo '<article class="excerpt-'.$i.' '. $_excerpt_css .'">';
		
			echo '<div class="new-posts-img">';
				if( QGG_Options('post_plugin_cat_link') && !is_category() ) {
					$category = get_the_category();
					if($category[0]){
						echo '<a class="cat-tag btn-default site-skin-bgc" href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'<i></i></a> ';
					}
				};
				if( QGG_Options('list_type') == 'thumb' ){
					echo '<a'.the_post_target_blank().' class="focus" href="'.get_permalink().'">'.$_thumb.'</a>';
				}else if( QGG_Options('list_type') == 'thumb_if_has' && !strstr($_thumb, 'data-thumb="default"') ){
					echo '<a'.the_post_target_blank().' class="focus" href="'.get_permalink().'">'.$_thumb.'</a>';
				}
			echo '</div>';
			
			echo '<div class="new-posts-content">';
				echo '<header>';
					echo '<h2>';
					echo '<a class="prettify" href="'.get_permalink().'"><i class="title-icon"></i></a>';
					echo '<a '.the_post_target_blank().' href="'.get_permalink().'" title="'.get_the_title()._get_the_subtitle(false).'-'.get_bloginfo('name').'">'.get_the_title()._get_the_subtitle().'</a>';
					echo '</h2>';
					// NEW 图标
					if( QGG_Options('post_new_open') ){
						$limit_new = QGG_Options('post_new_limit_time') ? QGG_Options('post_new_limit_time') : 72;
						date_default_timezone_set('PRC');
						$t1 = get_the_date('Y-m-d H:i:s');;
						$t2 = date('Y-m-d H:i:s');
						$diff = (strtotime($t2)-strtotime($t1))/3600;
						if($diff < $limit_new){
						    echo '<span class="new-icon">NEW</span>';
						}else{
							echo '';
						}
					}
					// 推荐图标
					if( QGG_Options('post_sticky_open') && is_sticky() ){
						echo '<span class="sticky-icon">推荐</span>';
					}
				echo '</header>';
				
				echo '<p class="meta">';
					if( QGG_Options('post_plugin_date') ){
						echo '<span><i class="iconfont qgg-time"></i>&nbsp'.get_the_time('Y-m-d').'</span>';
					}

					if( QGG_Options('post_plugin_author') ){
						$author = get_the_author();
						if( QGG_Options('author_link') ){
							$author = '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.$author.'</a>';
						}
						echo '<span class="author"><i class="iconfont qgg-user"></i>&nbsp'.$author.'</span>';
					}

					if( QGG_Options('post_plugin_view') ){
						echo '<span class="view"><i class="iconfont qgg-eye"></i> 阅读&nbsp('._get_post_views().')</span>';
					}

					if ( comments_open() && QGG_Options('post_plugin_comt') ) {
						echo '<span class="comment"><a class="pc" href="'.get_comments_link().'"><i class="iconfont qgg-message"></i>&nbsp评论('.get_comments_number('0', '1', '%').')</a></span>';
					}

					if( QGG_Options('post_plugin_like') ){
						echo'<span class="like"><i class="iconfont qgg-like"></i>喜欢&nbsp('._get_post_likes().')</span>';
					}
				echo '</p>';
				echo '<p class="desc">'.get_the_excerpt().'</p>';
				echo the_tags( '<p class="tags">', '', '</p>' ); 
				echo '<a'.the_post_target_blank().' href="'.get_permalink().'"><div class="more btn-primary">了解更多</div></a>';
			echo '</div>';
			
		echo '</article>';

	endwhile; 
	
	the_module_loader('module_paging_nav');
	
	}else{
		get_template_part( '404' );
	}
	?>
</section>
<!-- 广告代码 -->
<?php _the_ads($name='ads_post_list_02', $class='ads-post-list-02') ?>