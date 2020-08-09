<?php 
/**
 * @name 相关阅读模块
 * @description 在引用位置处加载一个相关文章列表模块
 */
?>

<?php
function module_get_posts_related($title='相关阅读', $limit=8){
	global $post;

	$exclude_id = $post->ID; 
	$posttags = get_the_tags(); 
	$i = 0;
	$thumb_open = QGG_Options('post_related_thumb');
	echo '<section id="relates-post-list" class="'.($thumb_open ? 'post-relates-thumb' : '').'">
		<div class="title">
			<h3>'.$title.'</h3>
		</div>
		<ul>';
		// 获取相同标签的文章
		if ( $posttags ) { 
			$tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->slug . ',';
			$args = array(
				'post_status'         => 'publish',
				'tag_slug__in'        => explode(',', $tags), 
				'post__not_in'        => explode(',', $exclude_id), 
				'ignore_sticky_posts' => 1, 
				'orderby'             => 'comment_date', 
				'posts_per_page'      => $limit
			);
			query_posts($args); 
			while( have_posts() ) { the_post();
				echo '<li>';
				if( $thumb_open ) { 
					echo '
					<a href="'.get_permalink().'">'._get_post_thumbnail().'</a>
					<div class="ico">
						<span><i class="iconfont qgg-eye"></i>&nbsp;阅读 ('._get_post_views().')</span>
						<span><i class="iconfont qgg-message"></i>&nbsp;评论('.get_comments_number('0', '1', '%').')</a></span>
					</div>';
				}
				echo '<div class="caption">
						<i class="iconfont qgg-arrow_right" aria-hidden="true"></i>
						<a href="'.get_permalink().'">'.get_the_title()._get_the_subtitle().'</a>
					</div>';
				echo '</li>';
				
				$exclude_id .= ',' . $post->ID; $i ++;
			};
			wp_reset_query();
		}
		// 相同标签不足相同分类补齐
		if ( $i < $limit ) { 
			$cats = '';
			foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
			$args = array(
				'category__in'        => explode(',', $cats), 
				'post__not_in'        => explode(',', $exclude_id),
				'ignore_sticky_posts' => 1,
				'orderby'             => 'comment_date',
				'posts_per_page'      => $limit - $i
			);
			query_posts($args);
			while( have_posts() ) { the_post();
				echo '<li>';
				if( $thumb_open ) { 
					echo '
					<a href="'.get_permalink().'">'._get_post_thumbnail().'</a>
					<div class="ico">
						<span><i class="iconfont qgg--eye"></i>&nbsp;阅读 ('._get_post_views().')</span>
						<span><i class="iconfont qgg-message"></i>&nbsp;评论('.get_comments_number('0', '1', '%').')</a></span>
					</div>';
				}
				echo '<div class="caption">
						<i class="iconfont qgg-arrow_right"></i>
						<a href="'.get_permalink().'">'.get_the_title()._get_the_subtitle().'</a>
					</div>';
				echo '</li>';
				$i ++;
			};
			wp_reset_query();
		}
		if ( $i == 0 ){
			echo '<li>暂无文章</li>';
		}
		
		echo '</ul>
	</section>';
}